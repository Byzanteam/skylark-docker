<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Env;

class Index extends Controller
{
    public function index()
    {
        $url =Env::get('GER_URL').'/oauth/authorize';
//        var_dump($url);exit;
        $data = [
            'client_id'=>Env::get('GET_USER_CLIENT_ID'),
            'response_type'=>'code',
            'grant_type'=>'authorization_code',
            'redirect_uri'=>Env::get('GET_USER_REDIRECT_URL').'/index/index/getuser'
        ];
//        $header = 'Authorization:3d06df76b958483cb6e36d59acfde7ef12a0b3a24587cc1476a776158145043d:eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lc3BhY2VfaWQiOjF9.sBNW1M4e1SMYVq4oJhS6qu3rkk7FgzBgkryVK-L5dXA';//定义content-type为xml
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
        curl_setopt($ch, CURLOPT_POST, true);//设置为POST方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//POST数据
        $response = curl_exec($ch);//接收返回信息
        if(curl_errno($ch)){//出错则显示错误信息
            print curl_error($ch);
        }
        curl_close($ch); //关闭curl链接
        $response = json_decode($response);
        var_dump($response);
    }

    public function getUser(){

        $code = $_GET['code'];
        $url =Env::get('GER_URL').'/oauth/token';
//        var_dump($url);exit;
        $data = [
            'client_id'=>Env::get('GET_USER_CLIENT_ID'),
            'client_secret'=>Env::get('GET_USER_CLIENT_SECRET'),
            'code'=>$code,
            'grant_type'=>'authorization_code',
            'redirect_uri'=>Env::get('GET_USER_REDIRECT_URL').'/index/index/getuser'
        ];
//        $header = 'Authorization:3d06df76b958483cb6e36d59acfde7ef12a0b3a24587cc1476a776158145043d:eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lc3BhY2VfaWQiOjF9.sBNW1M4e1SMYVq4oJhS6qu3rkk7FgzBgkryVK-L5dXA';//定义content-type为xml
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
        curl_setopt($ch, CURLOPT_POST, true);//设置为POST方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//POST数据

        $response = curl_exec($ch);//接收返回信息
        if(curl_errno($ch)){//出错则显示错误信息
            print curl_error($ch);
        }
        curl_close($ch); //关闭curl链接
        $response = json_decode($response);
        $token = $response->access_token;
//        echo $token;exit;
        //获取用户信息
        $url = Env::get('GER_URL').'/api/v1/user?access_token='.$token;//接收地址

//        $header = 'Authorization:3d06df76b958483cb6e36d59acfde7ef12a0b3a24587cc1476a776158145043d:eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lc3BhY2VfaWQiOjF9.sBNW1M4e1SMYVq4oJhS6qu3rkk7FgzBgkryVK-L5dXA';//定义content-type为xml
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
        curl_setopt($ch, CURLOPT_POST, false);//设置为POST方式


        $response1 = curl_exec($ch);//接收返回信息
        if(curl_errno($ch)){//出错则显示错误信息
            print curl_error($ch);
        }
        curl_close($ch); //关闭curl链接
        $response1 =  json_decode($response1,true);
        $id =$response1['id'];
//        $date = date('Y-m-d',time());
//
//        $res = Db::name('users')->alias('u')->where(['u.id'=>$id])->join('attendance a','u.id = a.user_id')->field('u.id as u_id,name,headimgurl,time_day,morning_time,afternoon_time,is_morning_status,morning_remarks,morning_address,afternoon_address,is_afternoon_status,afternoon_remarks')->where(['a.time_day'=>$date])->find();

            return $this->redirect('attendance/index',['user_id'=>$id]);

    }

    public function import(){
        $url = Env::get('GER_URL').'/api/v4/organizations/387/members?id=387&with_descendants=45';
        $options = array(
            'http' => array(
                'method' => 'GET',
                'header' => 'Authorization:'.Env::get('INTERFACE_SIGNATURE'),

                //'timeout' => 60 * 60 // 超时时间（单位:s）
            )
        );
        $context = stream_context_create($options);

        $result = file_get_contents($url,false,$context);

        $results = json_decode($result,true);
        $res= Db::table('users')->field('id')->select();
        $ids = [];
        foreach ($res as $re){
            $ids[] = $re['id'];
        }

        if ($ids){

            Db::startTrans();
            try{

                $res = Db::table('users')->where("id","in",$ids)->delete();

                $res = Db::name('users')->insertAll($results);

                // 提交事务

                Db::commit();
                echo 'success';
            } catch (\Exception $e) {
                // 回滚事务
                var_dump(222);
                Db::rollback();
            }

        }


    }

//    public function update_page(){
//        $url = 'https://beta.skylarkly.com/oauth/authorize?client_id=128e3b3439f7c4743e8f198ee876c1ab3f641143a091d6942f8026865ce64134&redirect_uri=http://kaoqin.webuildus.com/index/index/getuser&response_type=code';//接收地址
//
////        $header = 'Authorization:3d06df76b958483cb6e36d59acfde7ef12a0b3a24587cc1476a776158145043d:eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJuYW1lc3BhY2VfaWQiOjF9.sBNW1M4e1SMYVq4oJhS6qu3rkk7FgzBgkryVK-L5dXA';//定义content-type为xml
//        $ch = curl_init(); //初始化curl
//        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
////        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
//        curl_setopt($ch, CURLOPT_POST, false);//设置为POST方式
//
//        $response = curl_exec($ch);//接收返回信息
//        if(curl_errno($ch)){//出错则显示错误信息
//            print curl_error($ch);
//        }
//        curl_close($ch); //关闭curl链接
//    }
}
