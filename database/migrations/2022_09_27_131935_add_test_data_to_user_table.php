<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
return new class extends Migration
{
    const TABLE_NAME = 'user';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        for ($counter = 1; $counter < 10; $counter++) {
            DB::table(self::TABLE_NAME)->insert([
                    'first_name' => 'first_name' . $counter,
                    'last_name' => 'last_name' . $counter,
                    'email' => 'email' . $counter,
                    'password' => Hash::make('password' . $counter),
                    'phone' => 'phone' . $counter,
                    'created_at' => 'NOW()',
                    'updated_at' => 'NOW()',
                ]
            );
        }
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(self::TABLE_NAME);
    }
};
