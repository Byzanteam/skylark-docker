<?php
namespace app\index\controller;

use think\Db;
use think\Exception;
use think\Request;

class Base{
    public function __construct()
    {
        $keys = Request::instance()->header('Accept-keys');
        if (empty($keys)) throw new Exception('缺少token');
        $arr = explode('-',$keys);
        $md5 = array_pop($arr);
        if ($md5 !== md5(md5(implode($arr)))) {
            throw new Exception('token错误或失效');
        }
        //>>获取当前用户
        $user = Db::name('users')->where(['openid'=>$arr[0]])->find()['id'];
        if (empty($user)) {
            throw new Exception(OPENID_ERROR);
        }
        return $user;
    }
    public static function checkToken()
    {
        $keys = Request::instance()->header('Accept-keys');
        if (empty($keys)) throw new Exception('缺少token');
        $arr = explode('-',$keys);
        $md5 = array_pop($arr);
        if ($md5 !== md5(md5(implode($arr)))) {
            throw new Exception('token错误或失效');
        }
        //>>获取当前用户
        $user = Db::name('users')->where(['openid'=>$arr[0]])->find()['id'];
        if (empty($user)) {
            throw new Exception(OPENID_ERROR);
        }
        return $user;
    }
}