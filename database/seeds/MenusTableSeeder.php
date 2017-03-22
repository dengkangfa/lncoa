<?php

use Illuminate\Database\Seeder;

class MenusTableSeeder extends Seeder
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
            'icon' => 'ion-ios-speedometer',
            'uri' => '/',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $dashboard = App\Menu::create($dashboard);

        $userManagement = [
            'title' => 'user_setting',
            'description' => $faker->sentence,
            'icon'  => 'ion-ios-people',
            'uri' => '',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $userManagement = App\Menu::create($userManagement);

        $user = [
            'title' => 'user',
            'description' => $faker->sentence,
            'icon' => 'ion-person',
            'parent_id' => $userManagement->id,
            'uri' => '/users',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $user = App\Menu::create($user);

        $character = [
            'title' => 'character',
            'description' => $faker->sentence,
            'icon' => 'ion-person-stalker',
            'parent_id' => $userManagement->id,
            'uri' => '/roles',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $character = App\Menu::create($character);

        $authority = [
            'title' => 'authority',
            'description' => $faker->sentence,
            'icon' => 'ion-wrench',
            'parent_id' => $userManagement->id,
            'uri' => '/permissions',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $authority = App\Menu::create($authority);

        $system = [
            'title' => 'system',
            'description' => $faker->sentence,
            'icon' => 'ion-gear-b',
            'parent_id' => $userManagement->id,
            'uri' => '/system',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $system = App\Menu::create($system);

        $role = App\Role::where('name','admin')->first();
        $role->menus()->attach([
            $dashboard->id,
            $userManagement->id,
            $user->id,
            $character->id,
            $authority->id,
            $system->id
        ]);
        $role = App\Role::where('name','=','owner')->first();
        $role->menus()->attach([
            $dashboard->id,
            $userManagement->id,
            $user->id,
        ]);


    }
}
