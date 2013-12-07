<?php

class UserTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('users')->delete();
        User::create(array(
            'name'     => 'Sam',
            'email'    => 'sam@uccbm.com',
            'password' => Hash::make('internet'),
        ));
    }

}
