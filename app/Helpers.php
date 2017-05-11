<?php
if(!function_exists('lang')) {
    function lang($text, $parameters = []){
        return trans('lnc.'.$text, $parameters);
    }
}

if (! function_exists('csrf_token')) {
    /**
     * Get the CSRF token value.
     *
     * @return string
     *
     * @throws \RuntimeException
     */
    function csrf_token()
    {
        $session = app('session');

        if (isset($session)) {
            return $session->token();
        }

        throw new RuntimeException('Application session store not set.');
    }
}

if(!function_exists('human_filesize')) {
    /**
     * Get a readable file size.
     *
     * @param $bytes
     * @param int $decimals
     * @return string
     */
    function human_filesize($bytes, $decimals = 2) {
        $size = ['B', 'kB', 'MB', 'GB', 'TB', 'PB'];

        $floor = floor((strlen($bytes)-1)/3);

        return sprintf("%.{$decimals}f", $bytes/pow(1024, $floor)).@$size[$floor];
    }
}

if(!function_exists('get_attr')) {
      function get_attr($a, $pid, $parent_id='parent_id', $children='children'){
          $tree = array();                                //每次都声明一个新数组用来放子元素
          foreach($a as $v){
              if($v[$parent_id] == $pid){                      //匹配子记录
                  $v[$children] = get_attr($a,$v['id'],$parent_id,$children); //递归获取子记录
                  if($v[$children] == null){
                      unset($v[$children]);             //如果子元素为空则unset()进行删除，说明已经到该分支的最后一个元素了（可选）
                  }
                  $tree[] = $v;                           //将记录存入新数组
              }
          }
          return $tree;                                  //返回新数组
      }
}

if(!function_exists('getIpLookup')) {
    function getIpLookup($ip = ''){
      if(empty($ip)){
          //获取ip
          $ip = $_SERVER['REMOTE_ADDR'];
      }
      $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
      //当访问不了api返回false
      if(empty($res)){ return false; }
      $jsonMatches = array();
      //返回值为var remote_ip_info ={}，获取{}
      preg_match('#\{.+?\}#', $res, $jsonMatches);
      if(!isset($jsonMatches[0])){ return false; }
      $json = json_decode($jsonMatches[0], true);
      return $json;
  }
}

//获取天气预报
if(!function_exists('getWeather')) {
    function getWeather($city = '') {
        //判断是否指定城市
        if(empty($city)) {
            $city = config('lnc.k780.weaid');
        }
        $appkey = config('lnc.k780.appkey');
        $sign = config('lnc.k780.sign');
        //天气预报api地址
        $apiurl = "http://api.k780.com:88/?app=weather.future&weaid=$city&&appkey=$appkey&sign=$sign&format=json";
        if(!$callapi=file_get_contents($apiurl)){
            return false;
        }
        //将json结果转换成为数组
        if(!$a_cdata=json_decode($callapi,true)){
            return false;
        }
        //判断请求是否成功，错误则返回错误码以及错误消息，反之返回结果
       if($a_cdata['success']!='1'){
           \Log::error("调用天气预报k780api时出现错误:".$callapi);
           return false;
       }
       return $a_cdata['result'];
    }
}

if(!function_exists('getTodayWeather')) {
    function getTodayWeather($city = '') {
        //判断是否指定城市
        if(empty($city)) {
            $city = config('lnc.k780.weaid');
        }
        $appkey = config('lnc.k780.appkey');
        $sign = config('lnc.k780.sign');
        //天气预报api地址
        $apiurl = "http://api.k780.com:88/?app=weather.today&weaid=$city&&appkey=$appkey&sign=$sign&format=json";
        if(!$callapi=file_get_contents($apiurl)){
            return false;
        }
        //将json结果转换成为数组
        if(!$a_cdata=json_decode($callapi,true)){
            return false;
        }
        //判断请求是否成功，错误则返回错误码以及错误消息，反之返回结果
       if($a_cdata['success']!='1'){
           \Log::error("调用实时天气预报k780api时出现错误:".$callapi);
           return false;
       }
       return $a_cdata['result'];
    }
}
