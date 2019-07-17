<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/1
 * Time: 15:17
 */

namespace App\Tools;


class RedisTools
{

    private static $_instance;
    private static $_serviceSet;

    private function __construct()
    {
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * 析构函数,检查缓存目录是否有效,默认赋值
     */
    public static function getInstance()
    {
        if(!isset(self::$_instance)){
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    /**
     * @return \Predis\Client
     */
    public function getPredis()
    {
        $config = ['host'=>'127.0.0.1','port'=>'6379','database'=>'0'];
        if(!isset(self::$_serviceSet['predis'])){
            self::$_serviceSet['predis'] =  new \Predis\Client($config);
        }
        return self::$_serviceSet['predis'];
    }
    /**
     * @return Redis
     */
    public function getRedis()
    {
        if(!isset(self::$_serviceSet['redis'])){
            self::$_serviceSet['redis'] =  new Redis();
        }
        return  self::$_serviceSet['redis'];
    }

}