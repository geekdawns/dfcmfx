<?php

/**
 * 统一异常处理接口
 *  @author Jhin
 */
class ErrorController extends Yaf_Controller_Abstract{
    public function errorAction($exception){
        echo $exception->getMessage();
    }
}