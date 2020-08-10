<?php

namespace App\Http\Controllers\Api\Superadmin;

use App\City;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CityController extends Controller
{

    public function index()
    {
        $cities = City::paginate(25);
        return CityResource::collection($cities);
    }

    public function indexTrashed()
    {
        $cities = City::onlyTrashed();
        return CityResource::collection($cities);
    }

    public function store(Request $request)
    {
        $validatedDate = $request->validate([
            'admin_id' => 'nullable|integer',
            'name' => 'required|string|unique:cities',
            'state' => 'required|string',
            'country' => 'required|string',
            'lon' => 'required|regex:/^\d*(\.\d*)?$/',
            'lat' => 'required|regex:/^\d*(\.\d*)?$/',
            'kibla' => 'required|regex:/^\d*(\.\d*)?$/',
        ]);

        if (isset($validatedDate['admin_id'])) {
            if (!User::find($validatedDate['admin_id'])->hasRole('admin')) {
                return response()->json(['error_message' => 'This user cannot set a city.'], 401);
            }
        }

        $city = new City($validatedDate);
        if ($city->save()) {
            return response()->json([
                'successfully_message' => "The city created successfully",
                "city" => new CityResource($city),
            ], 201);
        }

        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);

    }

    public function show(City $city)
    {
        $city->load('time');
        return new CityResource($city);
    }


    public function update(Request $request, City $city)
    {
        $validatedDate = $request->validate([
            'admin_id' => 'nullable|integer',
            'name' => 'required|string|unique:cities,name,' . $city->id,
            'state' => 'required|string',
            'country' => 'required|string',
            'lon' => 'required|regex:/^\d*(\.\d*)?$/',
            'lat' => 'required|regex:/^\d*(\.\d*)?$/',
            'kibla' => 'required|regex:/^\d*(\.\d*)?$/',
        ]);


        if (isset($validatedDate['admin_id'])) {
            if ($validatedDate['admin_id']) {
                if (!User::find($validatedDate['admin_id'])->hasRole('admin')) {
                    return response()->json(['error_message' => 'This user cannot set a city.'], 401);
                }
            }
            $city['admin_id'] = $validatedDate['admin_id'];
        }

        $city['name'] = $validatedDate['name'];

        if (isset($validatedDate['state'])) {
            $city['state'] = $validatedDate['state'];
        }

        $city['lon'] = $validatedDate['lon'];
        $city['lat'] = $validatedDate['lat'];
        $city['kibla'] = $validatedDate['kibla'];

        if ($city->save()) {
            return response()->json([
                'successfully_message' => "updated successfully.",
                "city" => new CityResource($city),
            ], 200);
        }

        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);
    }


    public function destroy(City $city, Request $request)
    {
        $validatedDate = $request->validate([
            'superadmin_password' => 'required|string|min:6'
        ]);

        // check admin password
        if (!Hash::check($validatedDate['superadmin_password'], auth()->user()->password)) {
            return response()->json(['error_message' => 'Your current admin password is incorrect'], 401);
        }

        if ($city->delete()) {
            return response()->json([
                'successfully_message' => "city softDeleted successfully."
            ], 200);
        }
        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);
    }

    public function restore(Request $request, $cityId)
    {
        $validatedDate = $request->validate([
            'superadmin_password' => 'required|string|min:6',
        ]);

        // check admin password
        if (!Hash::check($validatedDate['superadmin_password'], auth()->user()->password)) {
            return response()->json(['error_message' => 'Your current admin password is incorrect'], 401);
        }

        $city = City::withTrashed()->findOrFail($cityId);
        if ($city->restore()) {
            return response()->json([
                'successfully_message' => "user restored successfully."
            ], 200);
        }
        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);
    }

    public function putPhoto(Request $request, $city)
    {
        $validatedDate = $request->validate([
            'photo' => 'required|mimes:jpg,jpeg,png|max:2048',
        ]);

        $city=City::findOrFail($city);
        $photoName = $request->file('photo')->hashName();

        $uploaded = $request->file('photo')->storeAs('public/cities/' . $city->id, $photoName);

        if ($uploaded) {
            if ($city['photo']!='default_city_photo.jpg'){
                $deletedphoto = Storage::delete('public/cities/' . $city->id.'/'. $city['photo']);
            }
            $city['photo'] = $photoName;
            if ($city->update()) {
                return response()->json([
                    'successfully_message' => "The photo has successfully uploaded",
                ], 201);
            }
        }

        return response()->json([
            'error_message' => 'Some problems happened in the server.'
        ], 500);

    }

}
