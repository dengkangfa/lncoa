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
          // $ip = GetIp();
          return '';
      }
      $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
      if(empty($res)){ return false; }
      $jsonMatches = array();
      preg_match('#\{.+?\}#', $res, $jsonMatches);
      if(!isset($jsonMatches[0])){ return false; }
      $json = json_decode($jsonMatches[0], true);
      return $json;
  }
}
