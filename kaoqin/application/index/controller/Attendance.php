<?php
namespace app\index\controller;

use think\Controller;
use think\Db;

class Attendance extends Controller
{
    public function index()
    {
//        $user_id = request()->get('id');
//        $id = request()->get('user_id');
        $id = input('param.user_id');

//        var_dump($id);exit;
//        var_dump($id);exit;
        $res1 = Db::name('users')->where(['id' => $id])->find();
        $date = date('Y-m-d',time());
        $res = Db::name('users')->alias('u')->where(['u.id'=>$id])->join('attendance a','u.id = a.user_id')->field('u.id as u_id,name,headimgurl,time_day,morning_time,afternoon_time,is_morning_status,morning_remarks,morning_address,afternoon_address,is_afternoon_status,afternoon_remarks')->where(['a.time_day'=>$date])->find();
//        var_dump($res);exit;
        $date = date("Ymd",time());
        //dump($date);die;
        $url = "http://api.goseek.cn/Tools/holiday?date=".$date;
        $holiday = file_get_contents($url);
        $holiday = json_decode($holiday,true);
        if($holiday['data'] == 1 || $holiday['data'] == 2){
            $miscellaneous = Db::table('miscellaneous')->where(['is_legal_holidays'=>2])->find();
        }else{
            $miscellaneous = Db::table('miscellaneous')->where(['is_legal_holidays'=>1])->find();
        }

        if ($res) {
            $this->assign('res',$res);
            $this->assign('miscellaneous',$miscellaneous);
            return $this->fetch('index@index/index');
        } else {
            if ($res1){
                $res1['u_id'] = $res1['id'];
                $this->assign('res',$res1);
                $this->assign('miscellaneous',$miscellaneous);
                return $this->fetch('index@index/index');
            }else{
                return json_encode([
                    'code' => 200,
                    'msg' => '未找相关用户信息',
                    'data' => $res1
                ]);
            }

        }
    }
        //上班打卡
    public function punchClock(){
        $user_id = request()->post('user_id');
//        echo $user_id;exit;
        $data['morning_time'] = request()->post('morning_time');
        $data['morning_address'] = request()->post('position');
        $data['time_day'] = request()->post('time_day');
        $data['is_morning_status'] = request()->post('is_morning_status');
        $data['morning_remarks'] = request()->post('morning_remarks');

        $day = date('Y-m-d');
        $res = Db::table('attendance')->where(['user_id'=>$user_id,'time_day'=>$day])->find();
        if ($res){
            $result = Db::table('attendance')->where(['user_id'=>$user_id,'time_day'=>$day])->update($data);
            if ($result){
                return json_encode(
                    [
                        'code'=>200,
                        'msg'=>'',
                        'data'=>['morning_time'=>$data['morning_time'],'is_morning_status'=>$data["is_morning_status"]]
                    ]
                );
            }else{
                return json_encode(
                    [
                        'code'=>500,
                        'msg'=>'打卡失败',
                        'data'=>'',
                    ]
                );
            }
        }else{
            $data['user_id']=$user_id;

            $result = Db::table('attendance')->insert($data);

            if ($result){
                return json_encode(
                    [
                        'code'=>200,
                        'msg'=>'',
                        'data'=>['morning_time'=>$data['morning_time'],'is_morning_status'=>$data["is_morning_status"]]
                    ]
                );
            }else{
                return json_encode(
                    [
                        'code'=>500,
                        'msg'=>'打卡失败',
                        'data'=>'',
                    ]
                );
            }
        }
    }

    //下班打卡
    public function goClock(){
        $user_id = request()->post('user_id');
//        echo $user_id;exit;
//        var_dump($user_id);exit;
        $data['afternoon_time'] = request()->post('afternoon_time');
        $data['afternoon_address'] = request()->post('position');
        $data['time_day'] = request()->post('time_day');
        $data['is_afternoon_status'] = request()->post('is_afternoon_status');
        $data['afternoon_remarks'] = request()->post('afternoon_remarks');

        $day = date('Y-m-d');
        $res = Db::table('attendance')->where(['user_id'=>$user_id,'time_day'=>$day])->find();
        if ($res){
            $result = Db::table('attendance')->where(['user_id'=>$user_id,'time_day'=>$day])->update($data);
            if ($result){
                return json_encode(
                    [
                        'code'=>200,
                        'msg'=>'',
                        'data'=>''
                    ]
                );
            }else{
                return json_encode(
                    [
                        'code'=>500,
                        'msg'=>'打卡失败',
                        'data'=>'',
                    ]
                );
            }
        }else{
            $data['user_id']=$user_id;

            $result = Db::table('attendance')->insert($data);

            if ($result){
                return json_encode(
                    [
                        'code'=>200,
                        'msg'=>'',
                        'data'=>''
                    ]
                );
            }else{
                return json_encode(
                    [
                        'code'=>500,
                        'msg'=>'打卡失败',
                        'data'=>'',
                    ]
                );
            }
        }
    }

    //迟到备注
    public function lateRemark(){

    }

    //统计

    public function statistics_user(){
        $user_id = request()->get('user_id');
        $time = date('Y-m',time());
        $users = Db::table('users')->where(['id'=>$user_id])->find();
        $results = Db::table('attendance')->where(['user_id'=>$user_id])->where('time_day','like','%'.$time.'%')->select();
//        var_dump($results);exit;
        $this->assign('results',$results);
        $this->assign('users',$users);
        return $this->fetch('index@index/static');
    }

    //展示选取日期的打卡情况
    public function dayStatistics(){
        $user_id = request()->get('user_id');
        $day = request()->get('day_time');
//        var_dump($day);
        $day_results = Db::table('attendance')->where(['user_id'=>$user_id,'time_day'=>$day])->find();
//        var_dump($day_results);exit;
        return $day_results;
    }
    //日历直观展示
    public function showCard(){
        $time = request()->get('time');
        $user_id = request()->get('user_id');
        $results = Db::table('attendance')->where(['user_id'=>$user_id])->where('time_day','like','%'.$time.'%')->select();
//        var_dump($results);exit;
        return $results;
    }
}