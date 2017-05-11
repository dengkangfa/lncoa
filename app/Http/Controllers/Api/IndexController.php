<?php

namespace App\Http\Controllers\Api;

use Auth;
use Redis;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Storage;
use App\Repositories\ApplicatRepository;

class IndexController extends ApiController
{
    //天气预报过期时间
    const weatherExpireSec = 3600;

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

    /**
     * 统计
     * @return array
     */
    public function statistics(Request $request)
    {
        if($request->has('show')) {
            $show = $request->get('show');
            $showArr = explode(',', $show);
        }else{
            return $this->noContent();
        }
        //如果卡片可视，则获取用户数，申请数， 申请完成数， 进入文档上传数
        if(in_array('card', $showArr)) {
          $users = $this->user->getNumber();
          $applicats = $this->applicat->getNumber();
          $applicatFulfill = $this->applicat->getFulfillNumber();
          $files = $this->getNewFiles('proposal');
        }

        //如果折线图可视，则获取折线图数据
        if(in_array('chart', $showArr)) {
          $accessCountLogs = $this->getChartData();
        }
        //如果站点公告可视，则获取站点公告
        if(in_array('notice', $showArr)){
          $notice = $this->getNotice();
        }

        $data = compact('users', 'applicats', 'applicatFulfill', 'files', 'accessCountLogs', 'notice');

        return $this->respondWithArray($data);
    }

    /**
     * 获取天气预报
     * @return [type] [description]
     */
    public function weather()
    {
        return $this->respondWithArray($this->getWeather());
    }

    /**
     * 获取最近登录访问记录
     * @return [type] [description]
     */
    public function loginlogs()
    {
        //如果访问历史可视，则获取访问历史记录
        return $this->respondWithArray($this->getLoginLog());
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
    public function getChartData()
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
        $enabledKey = 'lncoa:notice:enabled';
        if(!Redis::get($enabledKey)){
            return '';
        }
        $contentKey = 'lncoa:notice:content';

        return Redis::get($contentKey);
    }

    public function getWeather()
    {
        //获取用户所在城市<清远市>
        $location = Auth::user()->city;
        $city = '';
        //如果用户所在城市为空，则使用用户访问ip获取所在地址
        if(empty($location)){
            $ip = request()->ip();
            $city = getIpLookup('113.100.63.12')['city'];
        }else{
            $city = mb_substr($location, 0, -1, 'utf-8');
        }
        $weatherKey = 'lncoa:weather:' . $city;
        if(Redis::command('EXISTS', [$weatherKey])){
            return unserialize(Redis::command('GET', [$weatherKey]));
        }
        if(!$weather=getWeather($city)){
            return \Response::json([
                'status' => "error",
                'message' => "访问异常"
            ], 502);
        }
        if($todayWeather=getTodayWeather($city)){
            $weather[0] = $todayWeather;
        }
        //将当前城市的天气预报数据存储到redis，过期时间为一小时
        Redis::command('SET', [$weatherKey, serialize($weather)]);
        Redis::command('EXPIRE', [$weatherKey, self::weatherExpireSec]);
        return $weather;
    }
}
