<?php

use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = new User;
        $user->name = 'Administrator';
        $user->username = 'admin';
        $user->domain = 'internal';
        $user->email = 'pcrs@melaka.gov.my';
        $user->password = bcrypt('abc123');
        $user->anggota_id = 0;
        $user->save();

        $user = new User;
        $user->name = 'Md Ridzuan bin Mohammad Latiah';
        $user->username = 'mdridzuan';
        $user->domain = 'melaka.gov';
        $user->email = 'mdridzuan@melaka.gov.my';
        $user->password = bcrypt('abc123');
        $user->anggota_id = 11;
        $user->save();
    }
}
