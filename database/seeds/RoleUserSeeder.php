<?php

use Illuminate\Database\Seeder;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_user')->insert([
            ['role_id' => 1, 'user_id' => 1, 'department_id' => 1]
        ]);

        DB::table('role_user')->insert([
            ['role_id' => 4, 'user_id' => 2, 'department_id' => 14]
        ]);

        DB::table('role_user')->insert([
            ['role_id' => 5, 'user_id' => 2, 'department_id' => 14]
        ]);
    }
}
