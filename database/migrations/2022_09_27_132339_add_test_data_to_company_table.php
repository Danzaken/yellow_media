<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $usersList = [1,2,3,4,5,6,7,8,9];
    const COMPANY_TABLE_NAME = 'company';
    const COMPANY_USER_REL_TABLE_NAME = 'company_user_rel';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        for ($counter = 1; $counter < 100; $counter++) {
            $id = DB::table(self::COMPANY_TABLE_NAME)->insertGetId([
                    'title' => 'first_name' . $counter,
                    'description' => 'last_name' . $counter,
                    'phone' => 'email' . $counter,
                    'created_at' => 'NOW()',
                    'updated_at' => 'NOW()',
                ]
            );
            $usersList = $this->usersList;
            for ($index = 0; $index < random_int(1, 4); $index++) {
                $userId = random_int(0, count($usersList) - 1);
                unset($usersList[$userId]);
                DB::table(self::COMPANY_USER_REL_TABLE_NAME)->insert([
                        'company_id' => $id,
                        'user_id' => $userId,
                        'created_at' => 'NOW()',
                        'updated_at' => 'NOW()',
                    ]
                );
            }

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
