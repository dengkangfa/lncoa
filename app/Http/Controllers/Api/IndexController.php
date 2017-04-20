<?php

namespace App\Http\Controllers\Api;

use Redis;
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
        $notice = $this->getNotice();

        $data = compact('users', 'applicats', 'files', 'loginLogs', 'notice');

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
        $loginLogs = Redis::lrange('loginlogs', 0, 10);
        $loginLogsArr = [];
        foreach($loginLogs as $key=>$loginLog) {
           if($key > 7) {
              break;
           }
           $loginLog = unserialize($loginLog);
           $loginLog['iplookup'] = getIpLookup($loginLog['ip']);
           $loginLogsArr[] = $loginLog;
        }
        return $loginLogsArr;
    }

    public function getNotice()
    {
        return Redis::get('notice');
    }
}
