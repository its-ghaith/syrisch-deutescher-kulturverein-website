<?php

use App\User as User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InsertSuperadmin extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $user = DB::table('users')->insert(array(
            'name' => config('app.superAdminName'),
            'email' => config('app.superAdminEmail'),
            'username' => config('app.superAdminName'),
            'password' => bcrypt(config('app.superAdminPassword')),
            'created_at' => date('Y-m-d H:m:s'),
            'updated_at' => date('Y-m-d H:m:s')
        ));
        $user = User::where('email', config('app.superAdminEmail'))->first();
        $user->assignRole('superadmin');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
