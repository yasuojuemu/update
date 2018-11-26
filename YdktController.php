<?php
/**
 * Created for ttt.
 * User: ttt
 * Date: 2017/10/30
 * Time: 17:20
 */
 class YdktController extends BaseController
 {

     //接口预定义
     public $model = '';
     public $field = '*';
     public $params = [];
     public $likeK = '';
     public $likeV = '';
     public $page  = [];
     public $order = '';
     public $isCount = true;

     //后台预定义
     public $ProName = 'ydkt';//项目跳转url
     public $layout = 'login';//默认为登录布局
     public $pageSize = 10;//每页记录数
     public $title = '云朵课堂';
     public $name = '云朵课堂';
     public $IsSafe = true;
     public $prefix = 'ydkt';

     public function init()
     {
         $arr_host = [

         ];
         $http_Origin = isset($_SERVER['HTTP_ORIGIN'])&&(!in_array($_SERVER['HTTP_ORIGIN'],$arr_host))?$_SERVER['HTTP_ORIGIN']:'';
         if($http_Origin){//@todo 关闭
//             $this->IsSafe = false;
            header('Access-Control-Allow-Origin:'.$http_Origin);
             header('Access-Control-Allow-Credentials:true');
             header('Access-Control-Allow-Headers:X-Requested-With');
         }
     }
    //前台接口部分
    //预算课程
    //规划结果存储接口方法（已测）
    public function actionAddgh()
    {
        $uid = RequestHelper::post('uid', '');
        $classid = RequestHelper::post('classid', '');
        $grade = RequestHelper::post('grade', '');
        $income = RequestHelper::post('income', '');
        $remains = RequestHelper::post('remains', '');
        $love = RequestHelper::post('love', '');
        $exp = RequestHelper::post('exp', '');
        $name = RequestHelper::post('name', '');
        $profile_pic = RequestHelper::post('profile_pic', '');
        //var_dump(json_decode($info,true));exit;//将数据转为数组
        if(empty($uid) || empty($classid) || empty($grade) || empty($income) || empty($remains) || empty($love) || empty($exp) || empty($name) || empty($profile_pic)){
            $this->returnJson(['code'=>0, 'message'=>'请求参数不足，请检查']);
        }
		if($grade>5) $this->returnJson(['code'=>2, 'message'=>'您已完成预算课程']);
        $data=array(
            'uid'=>$uid,
            'classid'=>$classid,
            'name'=>$name,
            'profile_pic'=>$profile_pic,
            'grade'=>$grade,
            'income'=>$income,
            'remains'=>$remains,
            'love'=>$love,
            'exp'=>$exp,
            'addtime'=>time()
        );
        $model = new Yskcb();
        //先查询数据库中数据是否存在（如果存在直接更新）
        $xccon['uid']=$uid;
        $xccon['classid']=$classid;
        $flag = $model->Sel($this->field,$xccon,'','','','',false);
        if(empty($flag)){//添加操作
            $id = $model->Add($data);
        }else{//更新操作
            $rs = $model->Up($data,['uid'=>$uid,'classid'=>$classid]);
        }
        $params['classid'] = $classid;
        $res = $model->Sel($this->field,$params,'','','','',false);
        $this->returnJson(['code'=>1, 'message'=>'请求成功','data'=>$res]);

    }
    //排行榜结果读取接口方法（已测）
    public function actionPhlist()
    {
        $uid = RequestHelper::post('uid', '');
        $classid = RequestHelper::post('classid', '');
        if(empty($uid) || empty($classid)){
            $this->returnJson(['code'=>0, 'message'=>'请求参数不足，请检查']);
        }
        $model = new Yskcb();
        $params['classid'] = $classid;
        $res = $model->Sel($this->field,$params,'','','','',false);
        //等第三方接口
        $this->returnJson(['code'=>1, 'message'=>'请求成功','data'=>$res]);
    }

    //投资课程
    //进度储存接口方法(已测)
    public function actionJdsave()
    {
        $uid = RequestHelper::post('uid', '');
        $classid = RequestHelper::post('classid', '');
        $lessonid = RequestHelper::post('lessonid', '');
        $progress = RequestHelper::post('progress', '');
        if(empty($uid) || empty($classid) || empty($lessonid) || empty($progress)){
            $this->returnJson(['code'=>0, 'message'=>'请求参数不足，请检查']);
        }
        $data=array(
            'uid'=>$uid,
            'classid'=>$classid,
            'lessonid'=>$lessonid,
            'progress'=>$progress,
            'addtime'=>time()
        );
        $jdccmodel = new Jdcc();
        $id = $jdccmodel->Add($data);
        if($id){
            $this->returnJson(['code'=>1, 'message'=>'存储成功']);
        }else{
            $this->returnJson(['code'=>0, 'message'=>'存储失败']);
        }
    }
    //进度读取接口方法（已测）
    public function actionJddq()
    {
        $uid = RequestHelper::post('uid', '');
        $classid = RequestHelper::post('classid', '');
        $lessonid = RequestHelper::post('lessonid', '');
        if(empty($uid) || empty($classid) || empty($lessonid)){
            $this->returnJson(['code'=>0, 'message'=>'请求参数不足，请检查']);
        }
        $jdccmodel = new Jdcc();
        $params['uid'] = $uid;
        $params['classid'] = $classid;
        $params['lessonid'] = $lessonid;
        $this->page = ['page'=>0,'pageSize'=>1];
        $res = $jdccmodel->Sel($this->field,$params,'','',$this->page,'',false);
        $data=$res[0]['progress'];
        if($res){
            $this->returnJson(['code'=>1, 'message'=>'读取成功','progress'=>$data]);
        }else{
            $this->returnJson(['code'=>0, 'message'=>'没有查到任何结果']);
        }
    }
    //答题回传接口方法（已测）
    public function actionDthc()
    {
        $uid        = RequestHelper::post('uid', '');
        $classid    = RequestHelper::post('classid', '');
        $lessonid   = RequestHelper::post('lessonid', '');
        $questionid = RequestHelper::post('questionid', '');
        $iscorrect  = RequestHelper::post('iscorrect', '');
		$answer     = RequestHelper::post('answer', '');
        $points     = RequestHelper::post('points', '');
        if(empty($uid) || empty($classid) || empty($lessonid) || empty($answer) || empty($questionid) || empty($iscorrect) || empty($points)){
            $this->returnJson(['code'=>0, 'message'=>'请求参数不足，请检查']);
        }
        $data=array(
            'uid'       =>$uid,
            'classid'   =>$classid,
            'lessonid'  =>$lessonid,
            'questionid'=>$questionid,
            'iscorrect' =>$iscorrect,
			'answer'    =>$answer,
            'points'    =>$points,
            'addtime'   =>time()
        );
        $qlogmodel = new Qlog();
        $id = $qlogmodel->Add($data);
        if($id){
            $this->returnJson(['code'=>1, 'message'=>'记录成功']);
        }else{
            $this->returnJson(['code'=>0, 'message'=>'记录失败']);
        }
    }
     
     //后台--登陆
     public function actionLogin()
     {
         $this->Login($this->ProName);
     }

     //后台--登出
     public function actionLoginOut()
     {
         unset(Yii::app()->session['admin']);
         $this->showMessage('正在退出',U("$this->ProName/login"));
     }

     //后台--列表
     public function actionList()
     {
         $this->layout = 'table';
         if(!$this->checkLogin()) $this->showMessage('你还未登录',U("$this->ProName/login"));

         $start = RequestHelper::get('start', 0);
         $end   = RequestHelper::get('end', date('Ymd'));
         //$type = RequestHelper::get('type', '');
         $page  = RequestHelper::get('page', 1);
         $params['1'] = 1;
         $params['start']  = strtotime($start);
         $params['end']    = strtotime($end);
         /*if($type){
             $params['grade'] =$type;
         }*/
         $this->page = ['page'=>$page,'pageSize'=>$this->pageSize];
         $this->model = new Yskcb();
         /*$sql ="SELECT classid FROM `w_yd_yskcb` GROUP BY classid";
         $classinfo   = $this->model->dbConnection->createCommand($sql)->queryAll();
         var_dump($classinfo);exit;*/
         $rs = $this->model->Sel($this->field,$params,'','',$this->page,'addtime desc',false);
         //var_dump($rs);exit;
         $params['count'] = $this->model->SelCount($params,'','');
         $pages = new CPagination($params['count']);
         $pages->pageSize = $this->pageSize;
         $this->render('list',[
             'url'=>[U("$this->ProName/list"),U("$this->ProName/getexcel"),U("$this->ProName/jdcclist"),U("$this->ProName/qloglist")],
             'pages'=>$pages,
             'params'=>$params,
             'data'=>$rs,
         ]);
     }
     public function actionJdcclist()
     {
         $this->layout = 'table';
         if(!$this->checkLogin()) $this->showMessage('你还未登录',U("$this->ProName/login"));
         $start = RequestHelper::get('start', 0);
         $end   = RequestHelper::get('end', date('Ymd'));
         $page  = RequestHelper::get('page', 1);
         $params['1'] = 1;
         $params['start']  = strtotime($start);
         $params['end']    = strtotime($end);
         $this->page = ['page'=>$page,'pageSize'=>$this->pageSize];
         $this->model = new Jdcc();
         $rs = $this->model->Sel($this->field,$params,'','',$this->page,'addtime desc',false);
         //var_dump($rs);exit;
         $params['count'] = $this->model->SelCount($params,'','');
         $pages = new CPagination($params['count']);
         $pages->pageSize = $this->pageSize;
         $this->render('jdcclist',[
             'url'=>[U("$this->ProName/list"),U("$this->ProName/getexcel"),U("$this->ProName/jdcclist"),U("$this->ProName/qloglist")],
             'pages'=>$pages,
             'params'=>$params,
             'data'=>$rs,
         ]);
     }
     public function actionQloglist()
     {
         $this->layout = 'table';
         if(!$this->checkLogin()) $this->showMessage('你还未登录',U("$this->ProName/login"));
         $start    = RequestHelper::get('start', 0);
         $end      = RequestHelper::get('end', date('Ymd'));
         $userdh   = RequestHelper::get('userdh', '');
         $classid  = RequestHelper::get('classid', '');
         $lessonid = RequestHelper::get('lessonid', '');
         $page     = RequestHelper::get('page', 1);

         $params['1']      = 1;
         $params['start']  = strtotime($start);
         $params['end']    = strtotime($end);
         if($userdh)$params['uid'] = $userdh;
         $classid? $params['classid'] =$classid:'';
         $lessonid? $params['lessonid'] =$lessonid:'';


         $this->page = ['page'=>$page,'pageSize'=>$this->pageSize];
         $this->model = new Qlog();
         $rs = $this->model->Sel($this->field,$params,'','',$this->page,'addtime desc',false);
         //var_dump($rs);exit;
         $params['count'] = $this->model->SelCount($params,'','');
         $pages = new CPagination($params['count']);
         $pages->pageSize = $this->pageSize;
         $this->render('qloglist',[
             'url'=>[U("$this->ProName/list"),U("$this->ProName/getexcel"),U("$this->ProName/jdcclist"),U("$this->ProName/qloglist")],
             'pages'=>$pages,
             'params'=>$params,
             'data'=>$rs,
         ]);
     }

    

     public function actionGetexcel()
     {
         set_time_limit(0);
         $start = RequestHelper::get('start', 0);
         $end   = RequestHelper::get('end', date('Ymd'));
         $type  = RequestHelper::get('type', '');

         $params['start']  = strtotime($start);
         $params['end']    = strtotime($end);
         $type? $params['grade'] =$type:'';

         $this->model = new Yskcb();
		 ini_set('memory_limit', '999M');
         $rs = $this->model->Sel($this->field,$params,'','',[],'addtime desc',false);

         if(empty($rs)){
             $this->showMessage('没有可以导出的数据',U("$this->ProName/list"));exit;
         }
         $rs = array_map(function($i){
             return [
                 $i['uid'],
                 $i['classid'],
                 $i['name'],
                 $i['grade'],
                 $i['income'],
                 $i['remains'],
                 $i['love'],
                 $i['exp'],
                 date('Y-m-d H:i:s',$i['addtime']),
             ];
         },$rs);
         $params	= array('filename'=>date('Y-m-d').$this->name,
             'title'=>$this->name,
             'cell_title'=>array(
                 array('key'=>'0','name'=>'用户代号'),
                 array('key'=>'1','name'=>'班级代号'),
                 array('key'=>'2','name'=>'姓名'),
                 array('key'=>'3','name'=>'年级'),
                 array('key'=>'4','name'=>'收入'),
                 array('key'=>'5','name'=>'结赊'),
                 array('key'=>'6','name'=>'恋爱'),
                 array('key'=>'7','name'=>'经验'),
                 array('key'=>'8','name'=>'游戏时间'),
             ),
             'list'=>$rs
         );
         ExcelTool::postExcerpt($params);
         exit;
     }
     public function actionGetexcel1()
     {
         set_time_limit(0);
         $start    = RequestHelper::get('start', 0);
         $end      = RequestHelper::get('end', date('Ymd'));
         $userdh   = RequestHelper::get('userdh', '');
         $classid  = RequestHelper::get('classid', '');
         $lessonid = RequestHelper::get('lessonid', '');

         $params['start']  = strtotime($start);
         $params['end']    = strtotime($end);
         if($userdh)$params['uid'] = $userdh;
         $classid? $params['classid'] =$classid:'';
         $lessonid? $params['lessonid'] =$lessonid:'';

         $this->model = new Qlog();
		 ini_set('memory_limit', '999M');
         $rs = $this->model->Sel($this->field,$params,'','',[],'addtime desc',false);
         if(empty($rs)){
             $this->showMessage('没有可以导出的数据',U("$this->ProName/list"));exit;
         }
         $rs = array_map(function($i){
             return [
                 $i['uid'],
                 $i['classid'],
                 $i['lessonid'],
                 $i['questionid'],
                 $i['answer'],
                 $i['iscorrect'],
                 $i['points'],
                 date('Y-m-d H:i:s',$i['addtime']),
             ];
         },$rs);
         $params	= array('filename'=>date('Y-m-d').$this->name,
             'title'=>$this->name,
             'cell_title'=>array(
                 array('key'=>'0','name'=>'用户号'),
                 array('key'=>'1','name'=>'班级号'),
                 array('key'=>'2','name'=>'课程号'),
                 array('key'=>'3','name'=>'问题编号'),
                 array('key'=>'4','name'=>'答案'),
                 array('key'=>'5','name'=>'是否正确'),
                 array('key'=>'6','name'=>'分数'),
                 array('key'=>'7','name'=>'答题时间'),
             ),
             'list'=>$rs
         );
         ExcelTool::postExcerpt($params);
         exit;
     }


 }