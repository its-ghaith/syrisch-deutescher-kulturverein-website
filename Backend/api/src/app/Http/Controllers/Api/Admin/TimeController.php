<?php

namespace App\Http\Controllers\Api\Admin;

use App\City;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\TimeResource;
use App\Time;
use Illuminate\Http\Request;
use DateTime;
use App\Imports\TimesImport;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class TimeController extends Controller
{

    public function index()
    {
        $cities = auth()->user()->adminCities()->paginate(10);
        return CityResource::collection($cities);
    }


    public function store(Request $request)
    {
        $validatedDate = $request->validate([
            'city_id' => 'required|integer|unique:times',
            'calc_method' => 'nullable|string',
            'time_file' => 'required|mimes:xlsx,xls|max:2048',
        ]);


        $city = City::findOrFail($validatedDate['city_id']);

        if (auth()->id() != $city->admin_id) {
            return response()->json([
                'error_message' => 'Your don\'t own this city'
            ], 401);
        }

        $file_name = $request->file('time_file')->hashName();

        if (strpos($file_name, '.xlsx'))
            $file_name = str_replace('.xlsx', '.json', $file_name);

        elseif (strpos($file_name, '.xls'))
            $file_name = str_replace('.xls', '.json', $file_name);


        $time_in_json = $this->importTimes();

        $uploaded = Storage::put('public/cities/' . $city->id . '/' . $file_name, json_encode($time_in_json));
        if ($uploaded) {
            $times = [
                'admin_id' => auth()->id(),
                'city_id' => $city->id,
                'calc_method' => isset($validatedDate['calc_method']) ? $validatedDate['calc_method'] : null,
                'time_file_name' => $file_name,
            ];
            $savedTimes = new Time($times);

            if ($savedTimes->save()) {
                return response()->json([
                    'successfully_message' => "The time created successfully",
                    "times" => new TimeResource($savedTimes),
                ], 201);
            }
        }
        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);
    }


    public function show(City $city)
    {
        if (auth()->id() != $city->admin_id) {
            return response()->json([
                'error_message' => 'Your don\'t own this city'
            ], 401);
        }
        $cityResource = new CityResource($city);
        return $cityResource;
    }


    public function update(Request $request, City $city)
    {
        $validatedDate = $request->validate([
            'calc_method' => 'nullable|string',
            'time_file' => 'required|mimes:xlsx,xls|max:2048',
        ]);

        if (auth()->id() != $city->admin_id) {
            return response()->json([
                'error_message' => 'Your don\'t own this city'
            ], 401);
        }

        $file_name = $request->file('time_file')->hashName();

        if (strpos($file_name, '.xlsx'))
            $file_name = str_replace('.xlsx', '.json', $file_name);

        elseif (strpos($file_name, '.xls'))
            $file_name = str_replace('.xls', '.json', $file_name);

        $time_in_json = $this->importTimes();
        $time_file_name = $city->time()->time_file_name;

        if ($city->time->delete()) {
            $deletedTime = Storage::delete('public/cities/' . $city->id . '/' . $time_file_name);
            if ($deletedTime) {
                $uploaded = Storage::put('public/cities/' . $city->id . '/' . $file_name, json_encode($time_in_json));
                if ($uploaded) {
                    $times = [
                        'admin_id' => auth()->id(),
                        'city_id' => $city->id,
                        'calc_method' => isset($validatedDate['calc_method']) ? $validatedDate['calc_method'] : null,
                        'time_file_name' => $file_name,
                    ];
                    $updatedTime = $city->time;
                    $updatedTime = $times;
                    if ($updatedTime->update()) {
                        return response()->json([
                            'successfully_message' => "The time deleted successfully",
                        ], 201);
                    }
                }
            }
        }
        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);
    }


    public function destroy(City $city, Request $request)
    {

        $validatedDate = $request->validate([
            'admin_password' => 'required|string|min:6'
        ]);

        if (!Hash::check($validatedDate['admin_password'], auth()->user()->password)) {
            return response()->json(['error_message' => 'Your current admin password is incorrect'], 401);
        }


        if (auth()->id() != $city->admin_id) {
            return response()->json([
                'error_message' => 'Your don\'t own this city'
            ], 401);
        }

        $time_file_name = $city->time['time_file_name'];
        if ($city->time->delete()) {
            $deletedTime = Storage::delete('public/cities/' . $city->id . '/' . $time_file_name);
            if ($deletedTime) {
                return response()->json([
                    'successfully_message' => "The time deleted successfully",
                ], 201);
            }
        }
        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);
    }

    private function convertDate($arr, $var, $format = "h:i")
    {
        return date($format, Date::excelToDateTimeObject($arr[$var])->getTimestamp());
    }

    /**
     * @return array
     */
    public function importTimes(): array
    {
        $excel_file = Excel::toCollection(new TimesImport, request()->file('time_file'))->all();
        $excel_file = $excel_file[0]->toArray();
        $time_in_json = array();
        foreach ($excel_file as $row) {
            $row["date"] = $this->convertDate($row, "date", "Y-m-d");
            unset($row["hijri"]);
            $row["fadjr"] = $this->convertDate($row, "fadjr");
            $row["shuruk"] = $this->convertDate($row, "shuruk");
            $row["duhr"] = $this->convertDate($row, "duhr");
            $row["assr"] = $this->convertDate($row, "assr");
            $row["maghrib"] = $this->convertDate($row, "maghrib");
            $row["ishaa"] = $this->convertDate($row, "ishaa");
            array_push($time_in_json, $row);
        }
        return $time_in_json;
    }

}
