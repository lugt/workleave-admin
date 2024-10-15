<?php

// 开始前期工作

require_once "core/load/mod.php";
session_cache_limiter('private');
@session_start();
date_default_timezone_set('PRC'); 
if(isset($_REQUEST['cast'])){
	$act = Sec::A($_REQUEST['cast']);
}else{
	$act = null;
}

if($act == NULL){
	EC::R();
	session_start();
	if(!isset($_SESSION['uid'])){
		header("Location: login.html");
		die();
	}else{
		Global_Main();
	}
}else{
	// 需要登录
		
	EC::A();
	Load::loadmod("3s");
	Load::loadmod("Security.L");
	Load::loadmod("User");
	Load::loadmod("Teach");
	
	if(!isset($_SESSION['uid'])){
		Mod_Authorize("您已登出","您的账户已经登出，请重新登录"); // 没有登陆	
	}
	
	if($act == "leave"){
		Mod_Leave::doLeave();
	}else if($act == "inquire"){
		Mod_Search::range_search();
	}else if($act == "auth"){
		Mod_Authorize::doAct();
	}else if($act == "deauth"){
		Mod_Authorize::deAct();
	}else if($act == "detail"){
		Mod_Authorize::detail();
	}else if($act == "teacher" || $act == "me"){
		getTeach();
	}else if($act == "refresh"){
	 	// 操作刷新
		$tei = new Teach_Info();
		$tei->load();	
		$usb = new DB_Act("S","*","bwfx_teacher"," 1 ");
		if($usb->status != DB_Mod::$C_TRUE){
			//错误	
			Mod_Authorize::errp("刷新失败","检索信息数据不正确");
		}
		$out = getRefresh($usb);
		if($out != null && is_array($out->l) && count($out->l) > 0){
			Mod_Authorize::errp("刷新正确","刷新已经成功！");
		}else{
			Mod_Authorize::errp("刷新错误","结果为空！");
		}		
	}else if($act == "unregister"){
		// 未完成
		Mod_Authorize::errp("功能错误","很抱歉，您要找的功能暂时不可用");
	}else{
		// 默认页面 
		Mod_Authorize::errp("功能返回失败","很抱歉，您要找的功能尚未启用"); // 未知CAST
	}
}

// 程序结束

function getRefresh($usb){
		$sum = new Teach_Info();
		$row = $usb->get;
		if($row == NULL){
			//错误	
			Mod_Authorize::errp("刷新错误","存在错误的教师信息");
		}
		$now = new Teacher_A($row);
  	$sum->add($now);
		while($row = mysqli_fetch_array($usb->cd))
  	{
  		if($row == NULL){
			//错误	
				Mod_Authorize::errp("刷新错误","存在错误的教师信息	");
			}
  		$now = new Teacher_A($row);
  		$sum->add($now);
  	}
  	$ans = $sum->save();
  	if($ans == false || $ans <=  0){
  		Mod_Authorize::errp("刷新错误","保存失败！");
  	}
  	return $sum;
}

function getTeach(){
	if($_REQUEST['cast'] == "me"){
		$t = $_SESSION['uid'];
	}else{
		$t = Sec::C($_REQUEST['a']);	
	}
	$tei = new Teach_Info();
	if(false == $tei->load()){
		Mod_Authorize::errp("教师不存在！","教师信息打开失败！");
	}
	//print_r($tei->l);
	$an = $tei->getname($t);
	$ar = $tei->gettitle($t);
	$p = $tei->getphone($t);
	if($an == NULL || $an == FALSE){
	}else{
		Mod_Authorize::errp("<h1>".$an."</h1>","<h3>教师姓名 : ".$an."<br> 教师身份：".$ar."<br> 教师电话：".$p."</h3>");
	}	
	Mod_Authorize::errp("教师不存在！","教师信息不能检索！");
	
}




function Global_Main(){
	EC::A();
	if(!isset($_REQUEST['goto'])){
		require_once("index_home.html");	
	}else{
	
		$g = $_REQUEST['goto'];	
		if($g == "logout"){
			unset($_SESSION);
			session_destroy();
			header("Location: login.html");
			die();
		}
		
		require_once("index_info.php");
		if($g == "leave"){
			showModuleLeave();
		}else if($g == "always"){
			Global_None();
		}else if($g == "elder"){
			starter();
			Mod_Search::show();
			ender();
		}else if($g == "auth"){
			starter();
			Mod_Search::show();
			ender();
		}else{
			Global_None();
		}
	}	
	die();
}


function showModuleLeave(){
	// 按照顺序显示
	starter();
	Mod_Leave::show();
	ender();
}

function showModAlways(){
	// 按照顺序显示
	starter();
	showAlways();
	ender();
}

function showModuleInquire(){
	// 按照顺序显示
	starter();
	Mod_Search::show();
	ender();
}




// 教师（请假资格）
// 学生临时账号: 网上请假(可注册、可来宾)
// 单人请假 ， 统一事项、人名和班级可以多次填写
// 给定表格


// 教师（审核资格）（销假资格）

// 查阅特定时间段、特定班级学生信息
// 审核功能、取消审核功能、销假功能


// 领导（请假、审核、检查记录、统计信息）

// 


// 权限管理：
// 特定教师拥有审核权利，
// 全部教师拥有请假权利，包括学生
// 


// 管理员（后台全部模块可以使用）


class Mod_Leave{
	public static $ENC = "EXTERMINEsx02-3-4-#*($(&&(--2@##V";
	public static function show(){
		require_once("index_leave.html");
	}
	
	public static function doLeave(){
		// 检验权限
		if(strpos($_SESSION['priv'],"leave") === FALSE){
				// 错误，没有权限
			self::errp("您的权限不足！"); // 权限没有
		}		
		
		$lfo = self::Get_Req();
		if($lfo != NULL){
			$usb = self::DB_leave($lfo);
		}else{
			self::errp("您的输入不能被正确识别."); // 分析数据错误	
		}	
		
		if($usb->status == DB_Mod::$C_TRUE){
			self::suuc($lfo->lid,$usb->get);
		}else{
			if(is_array($usb->get)){
				$kkk = $usb->get[0] . "<br>" . base64_encode($usb->get[1]);
			}else{
				$kkk = "暂无";	
			}
			self::errp("您的输入无法记录，请检查<br>检查帮助代码:".$kkk); // 数据库错误
		}
	}
	
	public static function suuc($si,$pe){
		require_once("leave_answer.php");
		$retn = "您的请假已经记录，请联系审核教师准假！".
		"<br><span style=\"color:#f33\"> 您的请假代码:".($pe)."<br><span style=\"color:#ccc\">申请编号:SQ".$si."</span>";
		leave_answ("<h3>请假已提交</h3>",$retn);
		die();
	}
	
	public static function errp($si){
		require_once("leave_answer.php");
		leave_answ("请假不成功","您的请假不成功：".$si);
		die();
	}
	
	public static function Get_Req(){
		
		if(!isset($_REQUEST['na']) || !isset($_REQUEST['cl1']) || 
		!isset($_REQUEST['cl2']) || !isset($_REQUEST['st']) || 
		!isset($_REQUEST['en']) || !isset($_REQUEST['re']) || 
		!isset($_REQUEST['po']) ){
			self::errp("您输入的信息不完整");
		}
		
		$na = Sec::NS($_REQUEST['na']); // Names
		$cl1 = Sec::C($_REQUEST['cl1']); // Classes
		$cl2 = Sec::C($_REQUEST['cl2']); // Classes
		$st = Sec::C($_REQUEST['st']); // Start Time
		$en = Sec::C($_REQUEST['en']); // Ending 
		$ty = 1; // type 类型，现在默认都是 1
		$re = Sec::NS($_REQUEST['re']); // Reason （缘由）
		$ap = $_SESSION['uid']; // Applicant (JSON) 
		$at = time();	
		$po = Sec::NS($_REQUEST['po']); // Position (位置)
		$me = Sec::NS($_REQUEST['me']); // Memo (备注)
		
		$cl = $cl1*10 + $cl2;
		
		if($na != NULL && $cl > 0 && $st > 0 && 
		$en > 0 && $ty > 0 && $re != NULL && $ap != NULL && $po != NULL){
			// DO NOTHING	
		}else{
			self::errp("信息不完整<br>有格式不正确的信息");	 // 信息不完整
		}
		
		$leaveId = date("YmdHis");
			
		$now = new LeaveInfo();
		
		$now->na =  $na;
  	$now->cl =  $cl;
  	
  	$now->st =  strtotime($st);
  	
  	//
  	// 判断en
  	switch($en){
  		case 1:
 		  	$now->st = strtotime("13:00",$now->st);
 		  	break;
  		case 2:
  			$now->st = strtotime("18:40",$now->st);
  			break;
  		case 3:
  			$now->st = strtotime("10:00",$now->st);	
  			break;
  		default:
  			$now->st = strtotime("00:01",$now->st);
  	}
  	  	
  	$now->en =  strtotime("23:59",$now->st);
  	$now->re =  $re;
  	$now->me =  $me;
  	$now->ty =  $ty;
  	$now->sa =  100; // 状态 : 创建
  	$now->po =  $po;
  	$now->ap =  $ap;
  	$now->at =  $at;
  	$now->ex =  0;
  	$now->et =  0;
  	$now->fi =  0;
  	$now->ft =  0;
  	$now->lid = $leaveId;
		return $now;
	}
	
	public static function DB_leave($lfo){
		 $echos = "(NULL, '".$lfo->na."', ".$lfo->cl.", ".
			$lfo->st.", ".
			$lfo->en.", ".
			$lfo->ty.", ".
			$lfo->sa.", '".
			$lfo->re."', '".
			$lfo->po."', ".
			$lfo->ap.", ".
			$lfo->at.", ".
			$lfo->ex.", ".
			$lfo->et.", ".
			$lfo->fi.", ".
			$lfo->ft.", '".
			$lfo->me."', ".
			$lfo->lid.")";
			
			$sty = " (`id`, `name`, `class`, `start`, `end`, `type`, `state`, `reason`, `pos`, `apply`, `aptime`, `exam`, `extime`, `fire`, `fitime`, `memo`, `lid`)";
			$usb = new DB_Act("I",$echos,"bwfx_leave",$sty);	
			return $usb;
	}
}

class Mod_Search{
	
	public static function suuc($si){
		require_once("act/search_result.html");
		search_answer($si);
		die();
	}
	
	public static function errp($si){
		require_once("leave_answer.php");
		leave_answ("搜索失败",$si);
		die();
	}
	
	public static function range_search(){
		
		if(isset($_REQUEST['class'])){
			$class = Sec::C($_REQUEST['class']);
		}else{
			$class = 9999;
		}
		if(!isset($_REQUEST['time'])){
			$time = 1;
		}else{
			$time = Sec::C($_REQUEST['time']);
		}
		
		$GLOBALS['class'] = $class;
		$GLOBALS['time'] = $time;
		
		switch($class){
			case 1:
			 	// 高中
			 	$clcond = "`class` > 40  ";
			 	break;
			case 2:
			  // 高一
			 	$clcond = "`class` > 40 AND `class` < 50  ";
			 	break;
			case 3:
			  // 高二
			 	$clcond = "`class` > 50 AND `class` < 60  ";
			 	break;
			case 4:
			  // 高三
			 	$clcond = "`class` > 60 AND `class` < 70  ";
			 	break;
			case 5:
			  // 初yi
			 	$clcond = "`class` > 10 AND `class` < 20  ";
			 	break;
			case 6:
			  // 初er
			 	$clcond = "`class` > 20 AND `class` < 30  ";
			 	break;
			case 7:
			  // 初三
			 	$clcond = "`class` > 30 AND `class` < 40  ";
			 	break;
			case 9:
			  // 初zhong
			 	$clcond = "`class` > 10 AND `class` < 40  ";
			 	break;
			default:	
			  // 全校		
			 $clcond = "`class` >  0 ";
		}
		
		switch($time){
			
			case 1:
			  // 今明
			 	$stc = "`start` > ".strtotime("today 00:00")." AND `start` < ".strtotime("tomorrow 23:59");
			 	break;
			
			case 2:
			  // 今
			 	$stc = "`start` > ".strtotime("today 00:00")." AND `start` < ".strtotime("today 23:59");
			 	break; 	
			
			case 3:
			  // 明日
			 	$stc = "`start` > ".strtotime("tomorrow 00:00")." AND `start` < ".strtotime("tomorrow 23:59");
			 	break;	 	
			 	
		  case 5:
			  // 昨日
			 	$stc = "`start` > ".strtotime("yesterday 00:00")." AND `start` < ".strtotime("yesterday 23:59");
			 	break;	
		
			case 4:
			 	// 本周
			 	$stc = "`start` > ".strtotime("next Monday -1 week 00:00") . " AND `start` < ".strtotime("next Monday 00:00");
			 	break;
						 	
			default:	
			  // 前后三天
			  $stc = "`start` > ".strtotime("-3 day 00:00")." AND `start` < ".strtotime("+3 day 23:59");
		}
				
		// Range
		// 分析范围
		// 选定班级
		// 选定时间
		$usb = new DB_Act("S","*","bwfx_leave",$clcond ."  AND  ". $stc);
		if($usb->status != DB_Mod::$C_TRUE){
			// 出错误了
			self::errp("找不到学生假条！"); // 查询错误
		}else if($usb->get == NULL){
			self::errp("找不到学生假条！"); // 查询错误
		}
		// 存在结果			
		$answer = self::getSearch($usb);
		if($answer != null && count($answer) > 0){
			// 返回结果正确
			self::suuc($answer);
		}else{
			self::errp("出现问题");
		}
	}
	
	
	public static function getSearch($usb){
		$sum=array();
		$row = $usb->get;
		$now = LeaveInfo::GLF($row);
  	$sum[] = $now;
  	
		while($row = mysqli_fetch_array($usb->cd))
  	{
  		$now = LeaveInfo::GLF($row);
  		$sum[] = $now;
  	}
  	return $sum;		
	}
	
	
	public static function show(){
	  require_once("act/search_panel.html");
	}
	
	
}

// 核心 Bean
class LeaveInfo{
	public $na;
	public $cl;
	
	public $st = 0;
	public $en = 0;
	
	public $ty = 0;
	public $re;
	public $po;
		
	public $ap = 0;
	public $at = 0;
	
	public $ex = 0;
	public $et = 0;
	
	public $me;	
	public $sa = 0;
	
	public $fi = 0;
	
	public $ft = 0;
	public $id = 0;
	
	public $lid = 0;
	
	public function __construct(){
	}
	
	public function get_id($lid){
		$usb = new DB_Act("S","*","bwfx_leave","`id` = ".$lid."  ");		
		if($usb->status == DB_Mod::$C_TRUE){
			// 成功
			if($usb->get == NULL || $usb->get == false){
				// 失败了	
				return false;
			}else{
				return $this->GLA($usb->get);
			}
		}else{
			// 不成功
			return false;
		}
	}
	
	public function get_lid($lid){
		$usb = new DB_Act("S","*","bwfx_leave","`lid` = ".$lid."  ");		
		if($usb->status == DB_Mod::$C_TRUE){
			// 成功	
			if($usb->get == NULL || $usb->get == false){
				// 失败了	
				return false;
			}else{
				return $this->get_from($usb->get);
			}
		}else if(is_array($usb->status)){
			// 成功
			if($usb->get == NULL || $usb->get == false){
				// 失败了	
				return false;
			}else{
				return $this->get_from($usb->get);
			}
		}else{
			// 不成功
			return false;
		}
	}
	
	public function set($a,$b){
		EC::A();
		$usb = new DB_Act("U"," `".$a."` =  ".$b." ","bwfx_leave"," `id` = ".$this->id);
		if($usb->status == DB_Mod::$C_TRUE){
			// 成功	
			return true;
		}else{
			return false;	
		}
	}
		
	public static function GLF($row){
			$now = new LeaveInfo();
  		$now->na =  $row['name'];
  		$now->cl =  $row['class'];
  		
  		$now->st =  $row['start'];
  		$now->en =  $row['end'];
  		  		
  		$now->re =  $row['reason'];
  		$now->me =  $row['memo'];
  		
  		$now->ty =  $row['type'];
  		$now->sa =  $row['state']; // 状态 
  		$now->po =  $row['pos'];
  		
  		$now->ap =  $row['apply']; // 人 
  		$now->at =  $row['aptime']; 
  		
  		$now->ex =  $row['exam'];  // 人
  		$now->et =  $row['extime'];
  		
  		$now->fi =  $row['fire'];  // 人 
  		$now->ft =  $row['fitime']; 
  		
  		$now->id = $row['id'];
  		$now->lid = $row['lid'];
  		
  		// 添加队列
  		return $now;
		
	}
	
	public function GLA($row){
		
			if(!isset($row) || !is_array($row)){
				return false;
			}
			
			if(!isset($row['name']) || !isset($row['class']) ||
		!isset($row['start']) || !isset($row['end']) || !isset($row['reason']) || 
			!isset($row['memo']) || !isset($row['type']) || !isset($row['lid']) ||
			!isset($row['state']) || !isset($row['pos']) || !isset($row['apply']) || 
			!isset($row['aptime']) || !isset($row['exam']) || !isset($row['extime']) ||
			!isset($row['fire']) || !isset($row['fitime']) || !isset($row['id']) ){
				return false;
			}
			
  		$this->na =  $row['name'];
  		$this->cl =  $row['class'];
  		
  		$this->st =  $row['start'];
  		$this->en =  $row['end'];
  		  		
  		$this->re =  $row['reason'];
  		$this->me =  $row['memo'];
  		
  		$this->ty =  $row['type'];
  		$this->sa =  $row['state']; // 状态 
  		$this->po =  $row['pos'];
  		
  		$this->ap =  $row['apply']; // 人 
  		$this->at =  $row['aptime']; 
  		
  		$this->ex =  $row['exam'];  // 人
  		$this->et =  $row['extime'];
  		
  		$this->fi =  $row['fire'];  // 人 
  		$this->ft =  $row['fitime']; 
  		
  		$this->id = $row['id'];
  		$this->lid = $row['lid'];
  		
  		// 添加队列
  		return true;
		
	}
}

class Mod_Authorize{
	public static function doAct(){
		if(!isset($_REQUEST['a']) || $_REQUEST['a'] != "d"){
			self::Get_Req();
		}else{
			self::Get_De();	
		}
	}
	
	public static function Get_De(){
	$re = $_REQUEST['leave'];
		EC::R();
		session_start();
		EC::A();
		$pe = $_SESSION['uid'];
		$re = Sec::C($re);
		if($re < 0){
			self::errp("审核无效","您的操作不合规。");
		}
		$lfo = new LeaveInfo();
		if(true == $lfo->get_id($re)){
			// 成功找到	对应的请假条，
			
			if(!($lfo->ex == $_SESSION['uid'] &&  strpos($_SESSION['priv'],"deauth") === false) &&   strpos($_SESSION['priv'],"admin") === FALSE){
					//没有权利取消
				self::errp("审核无效","权限不足：您不能做此操作。");
			}
			$ans = $lfo->set("exam",0);
			if($ans != true){
				// 写入出错
				self::errp("审核无效","记录失败：审核内容错误。");
			}
			$ans = $lfo->set("extime",0);
			if($ans != true){
				self::errp("审核无效","记录失败：审核时间不合规。");
			}
			
			$ans = $lfo->set("state",100);
			if($ans != true){
				self::errp("审核无效","记录失败：状态不符合要求。");
			}
			$lfo->ex = 0;
			$lfo->et = 0;
			self::errp("审核成功","审核信息已取消，<br>已经取消，您可以再次申请审核");
		}else{
			self::errp("审核无效","很抱歉，没有这张请假条。");
		}
	}
	
	public static function Get_Req(){
		if(!isset($_REQUEST['leave'])){
			self::errp("审核无效","没有选定请假单");
		}
		$re = $_REQUEST['leave'];
		EC::R();
		session_start();
		EC::A();
		$pe = $_SESSION['uid'];
		$re = Sec::C($re);
		if($re <= 2 || $re > 100000){
			self::errp("审核无效","您的操作ID不合规。");
		}
		$lfo = new LeaveInfo();
		if(true == $lfo->get_id($re)){
			// 成功找到	对应的请假条，
			$ans = $lfo->set("exam",$_SESSION['uid']);
			if($ans != true){
				// 写入出错
				self::errp("审核无效","记录失败：输入不合规。");
			}
			$ty = time();
			$ans = $lfo->set("extime",$ty);
			if($ans != true){
				self::errp("审核无效","记录失败：输入不符合要求。");
			}
			$ans = $lfo->set("state",300);
			if($ans != true){
				self::errp("审核无效","记录失败：输入不达到要求。");
			}
			$lfo->ex = $re;
			$lfo->et = $ty;
			self::errp("<span style=\"color:#383;\">审核成功</span>","审核信息已记录。");
		}else{
			self::errp("审核无效","很抱歉，没有这张请假条。");
		}
	}
	
	public static function errp($ino,$inf){
		require_once("leave_answer.php");
		leave_answ($ino,$inf);
		die();
	}
	
	public static function detail(){
		
		if(!isset($_REQUEST['leave'])){
			self::errp("检索失败","没有选定请假单");
		}
		$re = $_REQUEST['leave'];
		$re = Sec::C($re);
		if($re <= 2 || $re > 100000){
			self::errp("检索失败","您的操作ID不合规。");
		}
		$lfo = new LeaveInfo();
		if(true == $lfo->get_id($re)){
			
   		require_once("index_info.php");
			require_once("act/auth_detail.php");
			auth_detail($lfo);
		}else{
			self::errp("检索失败","很抱歉，找不到指定假条。");
		}
	}
	
	
	
}
?>