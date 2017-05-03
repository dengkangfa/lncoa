<?php

use App\Menu;
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
            'describe' => $faker->sentence,
            'icon' => 'ion-ios-speedometer',
            'uri' => '/',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $dashboard = Menu::create($dashboard);

        // 用户设置
        $userManagement = [
            'title' => 'user_setting',
            'describe' => $faker->sentence,
            'icon'  => 'ion-ios-people',
            'uri' => '',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $userManagement = Menu::create($userManagement);

        // 用户管理
        $user = [
            'title' => 'user',
            'describe' => $faker->sentence,
            'icon' => 'ion-person',
            'parent_id' => $userManagement->id,
            'uri' => '/users',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $user = Menu::create($user);

        // 角色管理
        $character = [
            'title' => 'character',
            'describe' => $faker->sentence,
            'icon' => 'ion-person-stalker',
            'parent_id' => $userManagement->id,
            'uri' => '/roles',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $character = Menu::create($character);

        // 权限管理
        $authority = [
            'title' => 'authority',
            'describe' => $faker->sentence,
            'icon' => 'ion-wrench',
            'parent_id' => $userManagement->id,
            'uri' => '/permissions',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $authority = Menu::create($authority);

        // 系统管理
        $systemManage = [
            'title' => 'system_manage',
            'describe' => "系统管理",
            'icon' => 'ion-cube',
            'uri' => '',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $systemManage = Menu::create($systemManage);

        //类型管理
        $type = [
            'title' => 'type',
            'describe' => '类型管理',
            'icon' => 'ion-clipboard',
            'parent_id' => $systemManage->id,
            'uri' => '/system-types',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $type = Menu::create($type);

        //文件管理
        $file = [
            'title' => 'file',
            'describe' => '文件管理',
            'icon' => 'ion-ios-folder',
            'parent_id' => $systemManage->id,
            'uri' => '/system-files',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $file = Menu::create($file);

        //申请中心
        $applicat = [
            'title' => 'applicat',
            'describe' => '申请中心',
            'icon' => 'ion-ios-compose',
            'uri' => '/applicat',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $applicat = Menu::create($applicat);

        //申请审核
        $applicat_review = [
            'title' => 'applicat_review',
            'describe' => '申请审核',
            'icon' => 'ion-ios-barcode',
            'uri' => '/review',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $applicat_review = Menu::create($applicat_review);

        //申请管理
        $applicat_manage = [
            'title' => 'applicat_manage',
            'describe' => '申请管理',
            'icon' => 'ion-briefcase',
            'uri' => '/applicat-manage',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $applicat_manage = Menu::create($applicat_manage);

        //个人文件夹
        $personal_file = [
            'title' => 'personal_file',
            'describe' => '个人文件夹',
            'icon' => 'ion-folder',
            'uri' => '/files',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $personal_file = Menu::create($personal_file);

        //通告
        $notice = [
            'title' => 'notice',
            'describe' => '站点通告',
            'icon' => 'ion-chatbox',
            'uri' => '/notice',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $notice = Menu::create($notice);

        // 系统配置
        $system = [
            'title' => 'system',
            'describe' => $faker->sentence,
            'icon' => 'ion-gear-b',
            'uri' => '/system',
            'created_at' => $date_time,
            'updated_at' => $date_time,
        ];
        $system = Menu::create($system);

        $role = App\Role::where('name','admin')->first();
        $role->menus()->attach([
            $dashboard->id,
            $userManagement->id,
            $user->id,
            $character->id,
            $authority->id,
            $systemManage->id,
            $type->id,
            $file->id,
            $applicat->id,
            $applicat_review->id,
            $applicat_manage->id,
            $personal_file->id,
            $notice->id,
            $system->id,
        ]);
        $role = App\Role::where('name','=','owner')->first();
        $role->menus()->attach([
            $dashboard->id,
            $applicat->id,
            $applicat_manage->id,
        ]);


    }
}
