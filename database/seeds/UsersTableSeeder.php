<?php

use Carbon\Carbon;
use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $identicon = new \Identicon\Identicon();
        $users = [
            [
                'name' => 'admin',
                'avatar' => $identicon->getImageDataUri('dkf'),
                'email' => 'admin@pigjian.com',
                'password' => Hash::make('admin'),
                'status' => true,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ],
            [
                'name' => '邓康发',
                'avatar' => $identicon->getImageDataUri('dkf'),
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin'),
                'status' => true,
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now()
            ]
        ];

        DB::table('users')->insert($users);

        factory(User::class, 10)->create();
    }
}
