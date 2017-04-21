<?php

namespace App\Listeners;

use Redis;
use Carbon\Carbon;
use App\Events\NewApplyEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewApplyListener
{
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
     * @param  NewApplyEvent  $event
     * @return void
     */
    public function handle(NewApplyEvent $event)
    {
        //获取当前的日期
        $date = Carbon::now()->toDateString();
        //存储申请数的键
        $AccessDateKey = 'lncoa:applycount:' . $date;
        //判断是否已经存在当前键
        if(Redis::command('GET', [$AccessDateKey])) {
           //存在则累计+1
           $count = Redis::command('GET', [$AccessDateKey]) + 1;
        }else{
           //反之赋值为1
           $count = 1;
        }
        Redis::command('SET',[$AccessDateKey, $count]);
    }
}
