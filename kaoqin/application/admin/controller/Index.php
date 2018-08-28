<?php
namespace app\admin\controller;


//use function PHPSTORM_META\type;
use think\Controller;
use think\Db;
use think\Env;

class Index extends Controller {

    public function to_work_push(){
        $url = Env::get('GER_URL').'/api/v4/pushes/wechat';//接收地址
        $date = date('Y-m-d',time());
        $openids = Db::table('users')->alias('u')->field('u.id u_id')->join('attendance a','u.id = a.user_id')->where('is_morning_status','<>',0)->where(['time_day'=>$date])->select();
//        var_dump($openids);exit;
        $miscellaneous = Db::table('miscellaneous')->where(['is_legal_holidays'=>1])->find();
        $ids = [];
        foreach ($openids as $openid){
            $ids[] = $openid['u_id'];
        }
        $user_openids =[];
        if (!empty($ids)){
            $users = Db::table('users')->where('id','not in',$ids)->select();
            foreach ($users as $user){
                if (!empty($user['openid'])){
                    $data = [
                        'openids'=>$user['openid'],
                        'news_entity'=>(object)[
                            "title"=>"考勤打卡",
                            "description"=>"马上上班了,别忘记打卡哦!",
                            'url'=>Env::get('GER_URL').'/oauth/authorize?client_id='.Env::get('GET_USER_CLIENT_ID').'&redirect_uri='.Env::get('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            "picurl"=>Env::get('SHOW_PIC')],
                        'template_entity'=>(object)[
                            'template_entity'=>'PPa-zE5EyBO1LCaWIWtMYXNVy5AEosufbhdj-5Z-6Fw',
                            'url'=>Env::get('GER_URL').'/oauth/authorize?client_id='.Env::get('GET_USER_CLIENT_ID').'&redirect_uri='.Env::get('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            'data'=>[
                                'first'=>[
                                    'value'=>'您好!你有一条考勤打卡提醒',
                                    'color'=>'#173177'
                                ],
                                'keyword1'=>[
                                    'value'=>$user['name'],
                                    'color'=>'#173177'
                                ],
                                'keyword2'=>[
                                    'value'=>date('Y-m-d H:i:s',time()),
                                    'color'=>'#173177'
                                ],
                                'keyword3'=>[
                                    'value'=>'上班未打卡',
                                    'color'=>'#173177'
                                ],
                                'remark'=>[
                                    'value'=>'上班时间为:'.$miscellaneous['to_work'].',请别忘记打卡',
                                    'color'=>'#173177'
                                ]

                            ]
                        ]
                    ];
                    $header = array('Authorization:'.Env::get('INTERFACE_SIGNATURE'));//定义content-type为xml
                    $ch = curl_init(); //初始化curl
                    curl_setopt($ch, CURLOPT_URL, $url);//设置链接
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
                    curl_setopt($ch, CURLOPT_POST, true);//设置为POST方式
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));//POST数据

                    $response = curl_exec($ch);//接收返回信息
                    if(curl_errno($ch)){//出错则显示错误信息
                        print curl_error($ch);
                    }
                    var_dump($response);exit;
                    curl_close($ch); //关闭curl链接
                }
            }
        }else{
            $users = Db::table('users')->select();

            foreach ($users as $user){
                if (!empty($user['openid'])){
                    $data = [
                        'openids'=>$user['openid'],
                        'news_entity'=>(object)[
                            "title"=>"考勤打卡",
                            "description"=>"马上上班了,别忘记打卡哦!",
                            'url'=>Env::get('GER_URL').'/oauth/authorize?client_id='.Env::get('GET_USER_CLIENT_ID').'&redirect_uri='.Env::get('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            "picurl"=>Env::get('SHOW_PIC')],
                        'template_entity'=>(object)[
                            'template_entity'=>'PPa-zE5EyBO1LCaWIWtMYXNVy5AEosufbhdj-5Z-6Fw',
                            'url'=>Env::get('GER_URL').'/oauth/authorize?client_id='.Env::get('GET_USER_CLIENT_ID').'&redirect_uri='.Env::get('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            'data'=>[
                                'first'=>[
                                    'value'=>'您好!你有一条考勤打卡提醒',
                                    'color'=>'#173177'
                                ],
                                'keyword1'=>[
                                    'value'=>$user['name'],
                                    'color'=>'#173177'
                                ],
                                'keyword2'=>[
                                    'value'=>date('Y-m-d H:i:s',time()),
                                    'color'=>'#173177'
                                ],
                                'keyword3'=>[
                                    'value'=>'上班未打卡',
                                    'color'=>'#173177'
                                ],
                                'remark'=>[
                                    'value'=>'上班时间为:'.$miscellaneous['to_work'].',请别忘记打卡',
                                    'color'=>'#173177'
                                ]

                            ]
                        ]
                    ];
                    $header = array('Authorization:'.Env::get('INTERFACE_SIGNATURE'));//定义content-type为xml
                    $ch = curl_init(); //初始化curl
                    curl_setopt($ch, CURLOPT_URL, $url);//设置链接
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
                    curl_setopt($ch, CURLOPT_POST, true);//设置为POST方式
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));//POST数据

                    $response = curl_exec($ch);//接收返回信息
                    if(curl_errno($ch)){//出错则显示错误信息
                        print curl_error($ch);
                    }
                    var_dump($response);exit;
                    curl_close($ch); //关闭curl链接
                }
            }
        }

    }

    public function out_work_push(){
        $url = Env::get('GER_URL').'/api/v4/pushes/wechat';//接收地址
        $date = date('Y-m-d',time());
        $openids = Db::table('users')->alias('u')->field('u.id u_id')->join('attendance a','u.id = a.user_id')->where('is_afternoon_status','<>',0)->where(['time_day'=>$date])->select();
//        var_dump($openids);exit;
        $miscellaneous = Db::table('miscellaneous')->where(['is_legal_holidays'=>1])->find();
        $ids = [];
        foreach ($openids as $openid){
            $ids[] = $openid['u_id'];
        }
        $user_openids =[];
        if (!empty($ids)){
            $users = Db::table('users')->where('id','not in',$ids)->select();

            foreach ($users as $user){
                if (!empty($user['openid'])){
                    $data = [
                        'openids'=>$user['openid'],
                        'news_entity'=>(object)[
                            "title"=>"考勤打卡",
                            "description"=>"马上下班了,别忘记打卡哦!",
                            'url'=>Env::get('GER_URL').'/oauth/authorize?client_id='.Env::get('GET_USER_CLIENT_ID').'&redirect_uri='.Env::get('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            "picurl"=>Env::get('SHOW_PIC')],
                        'template_entity'=>(object)[
                            'template_entity'=>'PPa-zE5EyBO1LCaWIWtMYXNVy5AEosufbhdj-5Z-6Fw',
                            'url'=>Env::get('GER_URL').'/oauth/authorize?client_id='.Env::get('GET_USER_CLIENT_ID').'&redirect_uri='.Env::get('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            'data'=>[
                                'first'=>[
                                    'value'=>'您好!你有一条考勤打卡提醒',
                                    'color'=>'#173177'
                                ],
                                'keyword1'=>[
                                    'value'=>$user['name'],
                                    'color'=>'#173177'
                                ],
                                'keyword2'=>[
                                    'value'=>date('Y-m-d H:i:s',time()),
                                    'color'=>'#173177'
                                ],
                                'keyword3'=>[
                                    'value'=>'下班未打卡',
                                    'color'=>'#173177'
                                ],
                                'remark'=>[
                                    'value'=>'下班时间为:'.$miscellaneous['out_work'].',请别忘记打卡',
                                    'color'=>'#173177'
                                ]

                            ]
                        ]
                    ];
                    $header = array('Authorization:'.Env::get('INTERFACE_SIGNATURE'));//定义content-type为xml
                    $ch = curl_init(); //初始化curl
                    curl_setopt($ch, CURLOPT_URL, $url);//设置链接
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
                    curl_setopt($ch, CURLOPT_POST, true);//设置为POST方式
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));//POST数据

                    $response = curl_exec($ch);//接收返回信息
                    if(curl_errno($ch)){//出错则显示错误信息
                        print curl_error($ch);
                    }
                    var_dump($response);exit;
                    curl_close($ch); //关闭curl链接
                }
            }
        }else{
            $users = Db::table('users')->select();
            foreach ($users as $user){
                if (!empty($user['openid'])){
                    $data = [
                        'openids'=>$user['openid'],
                        'news_entity'=>(object)[
                            "title"=>"考勤打卡",
                            "description"=>"马上下班了,别忘记打卡哦!",
                            'url'=>Env::get('GER_URL').'/oauth/authorize?client_id='.Env::get('GET_USER_CLIENT_ID').'&redirect_uri='.Env::get('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            "picurl"=>Env::get('SHOW_PIC')],
                        'template_entity'=>(object)[
                            'template_entity'=>'PPa-zE5EyBO1LCaWIWtMYXNVy5AEosufbhdj-5Z-6Fw',
                            'url'=>Env::get('GER_URL').'/oauth/authorize?client_id='.Env::get('GET_USER_CLIENT_ID').'&redirect_uri='.Env::get('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            'data'=>[
                                'first'=>[
                                    'value'=>'您好!你有一条考勤打卡提醒',
                                    'color'=>'#173177'
                                ],
                                'keyword1'=>[
                                    'value'=>$user['name'],
                                    'color'=>'#173177'
                                ],
                                'keyword2'=>[
                                    'value'=>date('Y-m-d H:i:s',time()),
                                    'color'=>'#173177'
                                ],
                                'keyword3'=>[
                                    'value'=>'下班未打卡',
                                    'color'=>'#173177'
                                ],
                                'remark'=>[
                                    'value'=>'下班时间为:'.$miscellaneous['to_work'].',请别忘记打卡',
                                    'color'=>'#173177'
                                ]

                            ]
                        ]
                    ];
                    $header = array('Authorization:'.Env::get('INTERFACE_SIGNATURE'));//定义content-type为xml
                    $ch = curl_init(); //初始化curl
                    curl_setopt($ch, CURLOPT_URL, $url);//设置链接
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//设置HTTP头
                    curl_setopt($ch, CURLOPT_POST, true);//设置为POST方式
                    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));//POST数据

                    $response = curl_exec($ch);//接收返回信息
                    if(curl_errno($ch)){//出错则显示错误信息
                        print curl_error($ch);
                    }
                    var_dump($response);exit;
                    curl_close($ch); //关闭curl链接
                }
            }
        }
    }
    public function getOauth(){
        $code = $_GET['code'];
//        var_dump($code);exit;
        $url = Env::get('XD_URL').'/api/token';//接收地址

//        $header ["Content-type"]= "application/x-www-form-urlencoded";
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));//设置HTTP头
        curl_setopt($ch, CURLOPT_POST, true);//设置为POST方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&client_id='.Env::get('XD_CLIENT_ID').'&client_secret='.Env::get('XD_CLIENT_SECRET').'&code='.$code.'&redirect_uri='.Env::get('GET_USER_REDIRECT_URL').'/admin/index/getOauth');//POST数据
//        var_dump($ch);exit;
        $response = curl_exec($ch);//接收返回信息
        if(curl_errno($ch)){//出错则显示错误信息
            print curl_error($ch);
        }
        curl_close($ch); //关闭curl链接
        $response = json_decode($response);
        $token = $response->access_token;
//        var_dump($response);exit;
//        echo $token;exit;
        //获取用户信息
        $url =Env::get('XD_URL').'/api/userDetail?access_token='.$token;//接收地址

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
//        $response1 =  json_decode($response1,true);
//        var_dump($response1);exit;

        return $this->redirect('index/index',['users'=>$response1]);
    }
    public function index(){
        $users = input('param.users');
        if ($users){
            $results = Db::table('attendance')->alias('a')->join('users u','a.user_id = u.id')->paginate(10);
//        var_dump($results);exit;
            $users = json_decode($users,true);
            $this->assign('results',$results);
            $this->assign('users',$users);
            return $this->fetch();
        }

    }

    //获取上班时间
    public function getTime(){
        $status = request()->get('status');
        if ($status==1){
            $res = Db::table('miscellaneous')->where(['is_legal_holidays'=>$status])->find();
            return $res;
        }else{
            $res = Db::table('miscellaneous')->where(['is_legal_holidays'=>$status])->find();
            return $res;
        }
    }

    //设置上班时间
    public function setTime(){
        $status = request()->get('status');
        $data['to_work'] = request()->get('to_time');
        $data['out_work'] = request()->get('out_time');
//        var_dump($data);exit;
        if ($status ==1){
            $res = Db::table('miscellaneous')->where(['is_legal_holidays'=>1])->update($data);
            if ($res){
                $result = Db::table('miscellaneous')->where(['is_legal_holidays'=>1])->find();
                return $result;
            }
        }else{
            $res= Db::table('miscellaneous')->where(['is_legal_holidays'=>2])->update($data);
            if ($res){
                $result = Db::table('miscellaneous')->where(['is_legal_holidays'=>2])->find();
                return $result;
            }
        }
    }
    //检索
    public function search(){
        $time='';
        $is_status='';
        $user_name='';
        $time = request()->get('time');
        $is_status = request()->get('is_status');
        $user_name = request()->get('user_name');
        $where = [];
        if ($is_status ==1){
            $where['a.is_morning_status']=1;
            $where['a.is_afternoon_status']=1;
        }elseif ($is_status==2){
            $where['a.is_morning_status']=2;

        }
        $res = Db::table('attendance')->alias('a')->where('a.time_day','like','%'.$time.'%')->where($where)->join('users u','a.user_id = u.id')->where('u.name','like','%'.$user_name.'%')->select();
//        var_dump($res);
        return $res;
    }

    //时间区间检索
    public function search_time(){
        $start_time='';
        $end_time='';
        $is_status='';
        $user_name='';
        $start_time = request()->get('start_time');
        $end_time = request()->get('end_time');
        $is_status = request()->get('is_status');
        $user_name = request()->get('user_name');
        $where = [];
        if ($is_status ==1){
            $where['a.is_morning_status']=1;
            $where['a.is_afternoon_status']=1;
        }elseif ($is_status==2){
            $where['a.is_morning_status']=2;

        }
        $results = Db::table('attendance')->alias('a')->where($where)->join('users u','a.user_id = u.id')->where('u.name','like','%'.$user_name.'%')->select();
        $result_time =[];
        $end_result_time=[];
        foreach ($results as $result){
            if (!empty($start_time)){
                if (strtotime($result['time_day'])>=strtotime($start_time)){
                    $result_time[] = $result;
                    if (!empty($end_time)){
                        if ((strtotime($result['time_day'])>=strtotime($start_time))&&(strtotime($result['time_day'])<=strtotime($end_time))){
                            $end_result_time[] =$result;
                        }
                    }
                }
            }else{
                    if (!empty($end_time)){
                        if (strtotime($result['time_day'])<=strtotime($end_time)){
                            $end_result_time[] =$result;
                        }
                    }
            }
        }

        if (!empty($end_result_time)){
            return $end_result_time;
        }else{
            return $result_time;
        }
    }
}