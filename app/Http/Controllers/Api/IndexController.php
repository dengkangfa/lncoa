<?php

namespace App\Http\Controllers\Api;

use Redis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ApplicatRepository;

class IndexController extends ApiController
{
    protected $user;
    protected $applicat;

    public function __construct(
        UserRepository $user,
        ApplicatRepository $applicat
      )
    {
        $this->user = $user;
        $this->applicat = $applicat;
    }

    public function statistics()
    {
        $users = $this->user->getNumber();
        $applicats = $this->applicat->getNumber();
        $files = $this->getNewFiles('proposal');
        $loginLogs = $this->getLoginLog();
        $accessCountLogs = $this->getAccessCount();
        $notice = $this->getNotice();

        $data = compact('users', 'applicats', 'files', 'loginLogs', 'accessCountLogs', 'notice');

        return $this->respondWithArray($data);
    }

    /**
     * 获取今日文档提交数
     * @param  文档目录地址
     * @return int
     */
    public function getNewFiles($directory)
    {
        $files = Storage::allFiles($directory);
        $number = 0;
        foreach($files as $file){
            $time = Storage::lastModified($file);
            $this->getDate($time) ? $number=++$number : '';
        }
        return $number;
    }

    /**
     * 获取今天的时间戳
     * @param  [type] $time [description]
     * @return [type]       [description]
     */
    public function getDate($time)
    {
        $begintime = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $endtime = mktime(0, 0, 0, date('m'), date('d')+1, date('Y'))-1;
        if($time > $begintime && $time < $endtime) {
            return true;
        }
        return false;
    }

    /**
     * 获取最近访问的后8位ip信息
     * @return [type] [description]
     */
    public function getLoginLog()
    {
        //获取近10条的访问数据
        $loginLogs = Redis::lrange('user:loginlogs', 0, 10);
        $loginLogsArr = [];
        foreach($loginLogs as $key=>$loginLog) {
           if($key > 7) {
              break;
           }
           $loginLog = unserialize($loginLog);
           //将ip转成对应的地址(最好在前端转化)
           $loginLog['iplookup'] = getIpLookup($loginLog['ip']);
           $loginLogsArr[] = $loginLog;
        }
        return $loginLogsArr;
    }

    /**
     * 获取最近一个星期的访问量记录
     * @return [type] [description]
     */
    public function getAccessCount()
    {
        $AccessLog = [];
        for($i=6; $i >= 0; $i--){
            //获取最近一个星期每天对应的日期
            $date = Carbon::now("Asia/Shanghai")->subDays($i)->toDateString();
            //访问量的键
            $AccessDateKey = 'lncoa:accesscount:' . $date;
            //申请量的键
            $ApplyKey = 'lncoa:applycount:' . $date;
            //用日期作为图标的横坐标
            $AccessLog['labels'][] = $date;
            //获取访问量
            $access = Redis::command('GET', [$AccessDateKey]);
            $AccessLog['access'][] = $access ? $access : 0;
            //获取申请量
            $apply = Redis::command('GET', [$ApplyKey]);
            $AccessLog['apply'][] = $apply ? $apply : 0;
        }
        return $AccessLog;
    }

    /**
     * 获取站内通告
     * @return [type] [description]
     */
    public function getNotice()
    {
        return Redis::get('notice');
    }
}
