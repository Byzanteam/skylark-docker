<?php
namespace app\admin\controller;


//use function PHPSTORM_META\type;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Loader;
use think\Env;

class Index extends Controller {

    public function to_work_push(){
        //这里有改动
        $url = getenv('GER_URL').'/api/v4/pushes/wechat';//接收地址
        $date = date('Y-m-d',time());
        $openids = Db::table('users')->alias('u')->field('u.id u_id')->join('attendance a','u.id = a.user_id')->where('is_morning_status','<>',0)->where(['time_day'=>$date])->select();

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
                            'url'=>getenv('GER_URL').'/oauth/authorize?client_id='.getenv('GET_USER_CLIENT_ID').'&redirect_uri='.getenv('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            "picurl"=>getenv('SHOW_PIC')],
                        'template_entity'=>(object)[
                            'template_id'=>getenv('TEMPLATE_ID'),
                            'url'=>getenv('GER_URL').'/oauth/authorize?client_id='.getenv('GET_USER_CLIENT_ID').'&redirect_uri='.getenv('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            'data'=>[
                                'first'=>[
                                    'value'=>$user['name'].'您好!你有一条考勤打卡提醒',
                                    'color'=>'#173177'
                                ],
                                'keyword1'=>[
                                    'value'=>"上班打卡",
                                    'color'=>'#173177'
                                ],
                                'keyword2'=>[
                                    'value'=>date('Y-m-d H:i:s',time()),
                                    'color'=>'#173177'
                                ],
                                'keyword3'=>[
                                    'value'=>'校园内',
                                    'color'=>'#173177'
                                ],
                                'remark'=>[
                                    'value'=>'上班时间为:'.$miscellaneous['to_work'].',请别忘记打卡哟',
                                    'color'=>'#173177'
                                ]

                            ]
                        ]
                    ];
                    //这里
                    $header = array('Authorization:'.getenv('INTERFACE_SIGNATURE'));//定义content-type为xml
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
                            'url'=>getenv('GER_URL').'/oauth/authorize?client_id='.getenv('GET_USER_CLIENT_ID').'&redirect_uri='.getenv('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            "picurl"=>getenv('SHOW_PIC')],
                        'template_entity'=>(object)[
                            'template_id'=>getenv('TEMPLATE_ID'),
                            'url'=>getenv('GER_URL').'/oauth/authorize?client_id='.getenv('GET_USER_CLIENT_ID').'&redirect_uri='.getenv('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            'data'=>[
                                'first'=>[
                                    'value'=>$user['name'].'您好!你有一条考勤打卡提醒',
                                    'color'=>'#173177'
                                ],
                                'keyword1'=>[
                                    'value'=>"上班打卡",
                                    'color'=>'#173177'
                                ],
                                'keyword2'=>[
                                    'value'=>date('Y-m-d H:i:s',time()),
                                    'color'=>'#173177'
                                ],
                                'keyword3'=>[
                                    'value'=>'校园内',
                                    'color'=>'#173177'
                                ],
                                'remark'=>[
                                    'value'=>'下班时间为:'.$miscellaneous['out_work'].',请别忘记打卡哟',
                                    'color'=>'#173177'
                                ]

                            ]
                        ]
                    ];
                    $header = array('Authorization:'.getenv('INTERFACE_SIGNATURE'));//定义content-type为xml
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

                    curl_close($ch); //关闭curl链接
                }
            }
        }

    }

    public function out_work_push(){
        $url = getenv('GER_URL').'/api/v4/pushes/wechat';//接收地址
        $date = date('Y-m-d',time());
        $openids = Db::table('users')->alias('u')->field('u.id u_id')->join('attendance a','u.id = a.user_id')->where('is_afternoon_status','<>',0)->where(['time_day'=>$date])->select();
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
                            'url'=>getenv('GER_URL').'/oauth/authorize?client_id='.getenv('GET_USER_CLIENT_ID').'&redirect_uri='.getenv('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            "picurl"=>getenv('SHOW_PIC')],
                        'template_entity'=>(object)[
                            'template_id'=>getenv('TEMPLATE_ID'),
                            'url'=>getenv('GER_URL').'/oauth/authorize?client_id='.getenv('GET_USER_CLIENT_ID').'&redirect_uri='.getenv('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            'data'=>[
                                'first'=>[
                                    'value'=>$user['name'].'您好!你有一条考勤打卡提醒',
                                    'color'=>'#173177'
                                ],
                                'keyword1'=>[
                                    'value'=>"下班打卡",
                                    'color'=>'#173177'
                                ],
                                'keyword2'=>[
                                    'value'=>date('Y-m-d H:i:s',time()),
                                    'color'=>'#173177'
                                ],
                                'keyword3'=>[
                                    'value'=>'校园内',
                                    'color'=>'#173177'
                                ],
                                'remark'=>[
                                    'value'=>'下班时间为:'.$miscellaneous['out_work'].',请别忘记打卡哟',
                                    'color'=>'#173177'
                                ]

                            ]
                        ]
                    ];
                    $header = array('Authorization:'.getenv('INTERFACE_SIGNATURE'));//定义content-type为xml
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
                            'url'=>getenv('GER_URL').'/oauth/authorize?client_id='.getenv('GET_USER_CLIENT_ID').'&redirect_uri='.getenv('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            "picurl"=>getenv('SHOW_PIC')],
                        'template_entity'=>(object)[
                            'template_id'=>getenv('TEMPLATE_ID'),
                            'url'=>getenv('GER_URL').'/oauth/authorize?client_id='.getenv('GET_USER_CLIENT_ID').'&redirect_uri='.getenv('GET_USER_REDIRECT_URL').'/index/index/getuser&response_type=code',
                            'data'=>[
                                'first'=>[
                                    'value'=>$user['name'].'您好!你有一条考勤打卡提醒',
                                    'color'=>'#173177'
                                ],
                                'keyword1'=>[
                                    'value'=>"下班打卡",
                                    'color'=>'#173177'
                                ],
                                'keyword2'=>[
                                    'value'=>date('Y-m-d H:i:s',time()),
                                    'color'=>'#173177'
                                ],
                                'keyword3'=>[
                                    'value'=>'校园内',
                                    'color'=>'#173177'
                                ],
                                'remark'=>[
                                    'value'=>'下班时间为:'.$miscellaneous['out_work'].',请别忘记打卡哟',
                                    'color'=>'#173177'
                                ]

                            ]
                        ]
                    ];
                    $header = array('Authorization:'.getenv('INTERFACE_SIGNATURE'));//定义content-type为xml
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

                    curl_close($ch); //关闭curl链接
                }
            }
        }
    }
    public function getOauth(){
        $code = $_GET['code'];
        $url = getenv('XD_URL').'/api/token';//接收地址

//        $header ["Content-type"]= "application/x-www-form-urlencoded";
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));//设置HTTP头
        curl_setopt($ch, CURLOPT_POST, true);//设置为POST方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=authorization_code&client_id='.getenv('XD_CLIENT_ID').'&client_secret='.getenv('XD_CLIENT_SECRET').'&code='.$code.'&redirect_uri='.getenv('GET_USER_REDIRECT_URL').'/admin/index/getOauth');//POST数据
        $response = curl_exec($ch);//接收返回信息
        if(curl_errno($ch)){//出错则显示错误信息
            print curl_error($ch);
        }
        curl_close($ch); //关闭curl链接
        $response = json_decode($response);
        $token = $response->access_token;
        //获取用户信息
        $url =getenv('XD_URL').'/api/userDetail?access_token='.$token;//接收地址
        $ch = curl_init(); //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);//设置链接
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//设置是否返回信息
        curl_setopt($ch, CURLOPT_POST, false);//设置为POST方式


        $response1 = curl_exec($ch);//接收返回信息
        if(curl_errno($ch)){//出错则显示错误信息
            print curl_error($ch);
        }
        curl_close($ch); //关闭curl链接

        Cookie::set('users',$response1);
        return $this->redirect('index/index');
    }
    public function index(){
        $users = Cookie::get('users');
        if ($users){
            $results = Db::table('attendance')->alias('a')->join('users u','a.user_id = u.id')->paginate(10);
            $users = json_decode($users,true);
            $this->assign('results',$results);
            $this->assign('users',$users);
            return $this->fetch();
        }else{
            return $this->redirect('http://117.48.200.115:8080');
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
        $page_number = '';
        $time = request()->get('time');
        $is_status = request()->get('is_status');
        $user_name = request()->get('user_name');
        $start_time = request()->get('start_time');
        $end_time = request()->get('end_time');
        $page_number = request()->get('page_number')??20;
        $no_card = request()->get('no_card');
        if (empty($start_time)){
            $start_time=0;
        }
        if (empty($end_time)){
            $end_time = date('Y-m-d'.time());
        }
        $date = date("Ymd",time());
        $url = "http://api.goseek.cn/Tools/holiday?date=".$date;
        $holiday = @file_get_contents($url);
        $holiday = json_decode($holiday,true);
        if (!$holiday){
            $holiday['data']=1;
        }
        if($holiday['data'] == 1 || $holiday['data'] == 2){
            $miscellaneous = Db::table('miscellaneous')->where(['is_legal_holidays'=>2])->find();
        }else{
            $miscellaneous = Db::table('miscellaneous')->where(['is_legal_holidays'=>1])->find();
        }
        $where = [];
        $results = Db::table('attendance')->alias('a')->where('a.time_day','like','%'.$time.'%')->where('time_day','>=',$start_time)->where('time_day','<=',$end_time)->where($where)->join('users u','a.user_id = u.id')->where('u.name','like','%'.$user_name.'%')->paginate($page_number);
        if ($is_status ==1){
            if ($time ==date('Y-m-d',time())&&date('H:i:s',time())<=$miscellaneous['out_work']){
                $where['a.is_morning_status']=1;
                $where['a.is_afternoon_status']=0;
                $results = Db::table('attendance')
                    ->alias('a')
                    ->where('a.time_day','like','%'.$time.'%')
                    ->where('time_day','>=',$start_time)
                    ->where('time_day','<=',$end_time)
                    ->where($where)
                    ->join('users u','a.user_id = u.id')
                    ->where('u.name','like','%'.$user_name.'%')
                    ->paginate($page_number);
            }else{
                $where['a.is_morning_status']=1;
                $where['a.is_afternoon_status']=1;
                $results = Db::table('attendance')
                    ->alias('a')
                    ->where('a.time_day','like','%'.$time.'%')
                    ->where('time_day','>=',$start_time)
                    ->where('time_day','<=',$end_time)
                    ->where($where)
                    ->join('users u','a.user_id = u.id')
                    ->where('u.name','like','%'.$user_name.'%')
                    ->paginate($page_number);
            }

        }elseif ($is_status==2){
            if($time ==date('Y-m-d',time())&&date('H:i:s',time())<=$miscellaneous['out_work']){
                $results = Db::table('attendance')
                    ->alias('a')
                    ->where('a.time_day','like','%'.$time.'%')
                    ->where('time_day','>=',$start_time)
                    ->where('time_day','<=',$end_time)
                    ->where(function ($query){
                        $query->where('a.is_morning_status|a.is_afternoon_status','in',[2,3]);
                    })
                    ->join('users u','a.user_id = u.id')
                    ->where('u.name','like','%'.$user_name.'%')
                    ->paginate($page_number);
            }else{
                $results = Db::table('attendance')
                    ->alias('a')
                    ->where('a.time_day','like','%'.$time.'%')
                    ->where('time_day','>=',$start_time)
                    ->where('time_day','<=',$end_time)
                    ->where(function ($query){
                        $query->where('a.is_morning_status|a.is_afternoon_status','<>',1);
                    })
                    ->join('users u','a.user_id = u.id')
                    ->where('u.name','like','%'.$user_name.'%')
                    ->paginate($page_number);
            }

        }
//        $results = Db::table('attendance')->alias('a')->where('a.time_day','like','%'.$time.'%')->where('time_day','>=',$start_time)->where('time_day','<=',$end_time)->where($where)->join('users u','a.user_id = u.id')->where('u.name','like','%'.$user_name.'%')->paginate($page_number);
//        var_dump($results);

//            var_dump($miscellaneous);exit;

        foreach ($results as $key=>$result){
            if (date('H:i:s',time())<=$miscellaneous['to_work']&&empty($result['to_work'])&&$result['time_day']==date('Y-m-d',time())){
                $result['is_morning_status']=1;
            }elseif (date('H:i:s',time())<=$miscellaneous['out_work']&&empty($result['out_work'])&&$result['time_day']==date('Y-m-d',time())){
                $result['is_afternoon_status']=1;
            }
            $results[$key] = $result;
        }
        return $results;
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
    public function import_excel(){
        $time='';
        $is_status='';
        $user_name='';
        $time = request()->get('time');
        $is_status = request()->get('is_status');
        $user_name = request()->get('user_name');
        $start_time = request()->get('start_time');
        $end_time = request()->get('end_time');
        if (empty($start_time)){
            $start_time=0;
        }
        if (empty($end_time)){
            $end_time = date('Y-m-d'.time());
        }
        $where = [];
        if ($is_status ==1){
            $where['a.is_morning_status']=1;
            $where['a.is_afternoon_status']=1;
        }elseif ($is_status==2){
            $where['a.is_morning_status']=2;

        }
        $res =Db::table('attendance')->alias('a')->where('a.time_day','like','%'.$time.'%')->where('time_day','>',$start_time)->where('time_day','<=',$end_time)->where($where)->join('users u','a.user_id = u.id')->where('u.name','like','%'.$user_name.'%')->field('name,time_day,morning_time,morning_address,is_morning_status,afternoon_time,afternoon_address,is_afternoon_status')->select();
        $results= [];
        foreach ($res as $r){
            $r['is_morning_status'] = $r['is_morning_status']==1?'正常':($r['is_morning_status']==2?'异常':'未打卡');
            $r['is_afternoon_status'] = $r['is_afternoon_status']==1?'正常':($r['is_afternoon_status']==2?'异常':'未打卡');
            $results []= $r;
        }
        $table = ['用户名', '考勤时间', '上班时间','上班打卡地点' ,'上班打卡状态','下班时间', '下班打卡地点','下班打卡状态'];
        $tableName = '老师打卡情况表';
        $this->excel($results,$tableName,$table);
    }

    //更新待打卡人员数据
    public function get_no_card(){
        $users = Db::table('users')->field('id')->select();
        $user_info = [];
        $user_des = [];
        foreach ($users as $user){
            $user_des['time_day'] = date('Y-m-d',time());
            $user_des['user_id'] = $user['id'];
            $user_info[] = $user_des;
        }
        Db::table('attendance')->insertAll($user_info);
    }
    //导出数据表
    public function excel($userinfo=[],$tableName,$xlsHeader=[]){
        Loader::import('PHPExcel.PHPExcel.Classes.PHPExcel',EXTEND_PATH);
        Loader::import('PHPExcel.PHPExcel.Classes.PHPExcel.IOFactory.PHPExcel_IOFactory',EXTEND_PATH);
        /*$userinfo = [
            ['id' => 1, 'username' => 'zhangsan', 'email' => "zhangsan@itsource.cn"],
            ['id' => 2, 'username' => 'lisi', 'email' => "lisi@itsource.cn"],
            ['id' => 3, 'username' => 'wangwu', 'email' => "wangwu@itsource.cn"],
            ['id' => 4, 'username' => 'ermazi', 'email' => "ermazi@itsource.cn"]
        ];*/
        $objPHPExcel = new \PHPExcel();

        //添加一个表单
        $objPHPExcel->setActiveSheetIndex(0);

        //设置表单名称
        $objPHPExcel->getActiveSheet()->setTitle($tableName);
        /**
         * 准备表头的名称
         */
//        $xlsHeader = [
//            'ID',
//            '用户名',
//            '邮箱'
//        ];

        /**
         * 准备表格列名
         */
        $cellName = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ'];

        /**
         * 将表格第一行作为表格的简介行，需要合并
         */
//>>1.获取需要合并多少列
        $column_count = count($xlsHeader);
//>>2.合并第一行的三列
        $objPHPExcel->getActiveSheet()->mergeCells("A1:" . $cellName[$column_count - 1] . "1");
//>>3.设置合并后的内容
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "用户信息统计  创建时间：" . date("Y-m-d"));

        /**
         * 表格第二行开始设置表头
         */
        foreach ($xlsHeader as $k => $v) {
            $objPHPExcel->getActiveSheet()->setCellValue($cellName[$k] . "2", $v);
        }

        /**
         * 表格第三行开始添加表格数据
         */
        foreach ($userinfo as $k => $v) {
            //获取当前多少行
            $line = 3 + $k;
            $i = 0;
            foreach ($v as $key => $value) {
                $objPHPExcel->getActiveSheet()->setCellValue($cellName[$i] . $line, $value);
                ++$i;
            }
        }
        //导出excel
        $xlsname = iconv("utf-8", "gb2312", $tableName);

// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $xlsname . '.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }
}
