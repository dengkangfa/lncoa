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
        //面板
        $dashboard = [
            'title' => 'dashboard',
            'description' => $faker->sentence,
            'icon' => 'ion-ios-speedometer',
            'uri' => '/',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $dashboard = App\Menu::create($dashboard);

        // 用户设置
        $userManagement = [
            'title' => 'user_setting',
            'description' => $faker->sentence,
            'icon'  => 'ion-ios-people',
            'uri' => '',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $userManagement = App\Menu::create($userManagement);

        // 用户管理
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

        // 角色管理
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

        // 权限管理
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

        // 系统管理
        $systemManage = [
            'title' => 'system_manage',
            'description' => "系统管理",
            'icon' => 'ion-cube',
            'uri' => '',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $systemManage = App\Menu::create($systemManage);

        //场地管理
        $site = [
            'title' => 'site',
            'description' => '场地管理',
            'icon' => 'ion-clipboard',
            'parent_id' => $systemManage->id,
            'uri' => '/sites',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $site = App\Menu::create($site);

        //文件管理
        $file = [
            'title' => 'file',
            'description' => '文件管理',
            'icon' => 'ion-ios-folder',
            'parent_id' => $systemManage->id,
            'uri' => '/files',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $file = App\Menu::create($file);

        // 系统配置
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
