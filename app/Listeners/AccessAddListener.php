<?php

namespace App\Listeners;

use Redis;
use Carbon\Carbon;
use App\Events\UserAccessEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AccessAddListener
{
    /**
    * 同一用户浏览同一post过期时间
    */
    const ipExpireSec   = 300;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserAccessEvent  $event
     * @return void
     */
    public function handle(UserAccessEvent $event)
    {
        //访问的用户
        $user = $event->user;
        $user_id = $user->id;
        //用户登录的ip
        $ip = $event->ip;
        if($this->ipViewLimit($user_id,$ip)) {
            //更新访问数
            $this->updateAccessCount();
            //更新访问记录
            $this->updateAccessRecord($user,$ip);
        }
    }

    public function ipViewLimit($user_id, $ip)
    {
        //用于判断用户ip登录的key
        $ipUserViewKey = 'user:ip:limit:'.$user_id;
        //查看集合类型Set里面有没有存在该键.
        $existsInRedisSet = Redis::command('SISMEMBER', [$ipUserViewKey, $ip]);
        if(!$existsInRedisSet) {
            //SADD,集合类型指令,向ipUserViewKey键中加一个值ip
            Redis::command('SADD', [$ipUserViewKey, $ip]);
            //并给该键设置生命时间,这里设置300秒,300秒后同一IP访问就当做是新的浏览量了
            Redis::command('EXPIRE', [$ipUserViewKey, self::ipExpireSec]);
            return true;
        }
        return false;
    }

    public function updateAccessCount()
    {
      $date = Carbon::now()->toDateString();
      $AccessDateKey = 'lncoa:accesscount:' . $date;
      if(Redis::command('GET', [$AccessDateKey])) {
         $count = Redis::command('GET', [$AccessDateKey]) + 1;
      }else{
         $count = 1;
      }
      Redis::command('SET',[$AccessDateKey, $count]);

      // $access = [];
      // //获取最后的一条访问记录
      // $endAccessLog = unserialize(Redis::command('RPOP', [$AccessDateKey]));
      // //获取今日的日期
      // $date = Carbon::now()->addDays(1)->toDateString();
      //   if($endAccessLog && $endAccessLog['date'] == $date) {
      //       $access['date'] = $endAccessLog['date'];
      //       $access['count'] = $endAccessLog['count'] + 1;
      //   }else{
      //       $access['date'] = $date;
      //       $access['count'] = 1;
      //   }
      //   Redis::command('LPUSH', [$AccessDateKey, serialize($access)]);

    }

    public function updateAccessRecord($user, $ip)
    {
        $userAccessLogKey = 'user:loginlogs';
        $login = [];
        $login['user_name'] = $user->name;
        $login['ip'] = $ip;
        $login['create_at'] = time();
        // Redis::lpush($userAccessLogKey, serialize($login));
        Redis::command('LPUSH', [$userAccessLogKey, serialize($login)]);
        //只保留最近的10条访问记录
        Redis::command('LTRIM', [$userAccessLogKey, 0, 9]);
        // Redis::ltrim($userAccessLogKey, 0, 9);
    }
}
