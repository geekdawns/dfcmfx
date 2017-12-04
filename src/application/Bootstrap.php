<?php
/**
 * 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
 * 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
 * 调用的次序, 和申明的次序相同
 */
use Illuminate\Translation\Translator;
use Illuminate\Translation\FileLoader;
use Illuminate\Filesystem\Filesystem;
use Medoo\Medoo;

class Bootstrap extends Yaf_Bootstrap_Abstract
{

    public function _initConfig()
    {
        $config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set("config", $config);

        $db = $config->get("db");
        Yaf_Registry::set("db", new medoo([
            'database_type' => 'mysql',
            'database_name' => $db->database,
            'server' => $db->host,
            'username' => $db->user,
            'password' => $db->pwd,
            'charset' => $db->charset,
        ]));
    }

    public function _initDefaultName(Yaf_Dispatcher $dispatcher)
    {
        $dispatcher->setDefaultModule("Index")->setDefaultController("Index")->setDefaultAction("index");
    }

    /**
     * 自定义视图引擎
     */
    public function _initBlade(Yaf_Dispatcher $dispatcher)
    {
        $dispatcher->setView(new View(APPLICATION_PATH . '/application/views', APPLICATION_PATH . '/application/cache'));
    }

    /**
     * 添加路由规则
     */
    public function _initRoute(Yaf_Dispatcher $dispatcher)
    {
        $rules = Yaf_Registry::get('config')->routes;
        if (!empty($rules)) {
            $router = $dispatcher->getRouter();
            $router->addConfig($rules);
        }
    }
}