<?php

use Illuminate\Database\Seeder;

class TreeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(Faker\Generator::class);
        $date_time = $faker->date.' '.$faker->time;
        $dashboard = [
            'title' => 'dashboard',
            'description' => $faker->sentence,
            'icon' => 'ion-iso-speedometer',
            'uri' => '/home',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $dashboard = App\Tree::create($dashboard);

        $userManagement = [
            'title' => 'userManagement',
            'description' => $faker->sentence,
            'icon'  => 'ion-ios-people',
            'uri' => '',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $userManagement = App\Tree::create($userManagement);

        $user = [
            'title' => 'user',
            'description' => $faker->sentence,
            'icon' => 'ion-person-stalker',
            'parent_id' => $userManagement->id,
            'uri' => '/users',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $user = App\Tree::create($user);

        $character = [
            'title' => 'character',
            'description' => $faker->sentence,
            'icon' => 'ion-person-stalker',
            'parent_id' => $userManagement->id,
            'uri' => '/characters',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $character = App\Tree::create($character);

        $authority = [
            'title' => 'authority',
            'description' => $faker->sentence,
            'icon' => 'ion-person-stalker',
            'parent_id' => $userManagement->id,
            'uri' => '/authoritys',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $authority = App\Tree::create($authority);

        $role = App\Role::where('name','admin')->first();
        $role->trees()->attach([
            $dashboard->id,
            $userManagement->id,
            $user->id,
            $character->id,
            $authority->id
        ]);
        $role = App\Role::where('name','=','owner')->first();
        $role->trees()->attach([
            $dashboard->id,
            $userManagement->id,
            $user->id,
        ]);


    }
}
