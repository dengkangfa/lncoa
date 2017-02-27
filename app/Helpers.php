<?php
if(!function_exists('lang')) {
    function lang($text, $parameters = []){
        return trans('lnc.'.$text, $parameters);
    }
}
