﻿//Javascript Document
var loginxhr;	
var block = false;
var home_pass = false;
var OK_PNG = '<img src="images/s_c/yes.png">';
var tmp = new Array();
var q = Array();
var E_NOSUB = 3094;
var E_NOANYCOOKIE = 3095;
var ERR_PNG = "<img src='images/s_c/can.png' class='spppd'/>";
var args_page = get_ar2();
var pp;
var mf;
var PIC_WAIT = "<div style='position:relative;magin-top:10px;'><img width='32' height='32' src='/images/login/ajax-loader.gif'/></div>";
var msgg = _id('msgc');
var i = 0;
var block = false;
function _id(cc){
	return document.getElementById(cc);
}
function get_reg(thing){
		var pdd = thing;
		if(pdd.slice(0,3)=="ERR"){
			se_co(err_solve(pdd));
		}else if(pdd.slice(0,3)=="ULS"){
			var sid = new Array();
			sid = pdd.split("---");
			if(sid[0] == "ULS" && sid[1].length == 80){	
				var out = n_set_cookie("ucid",1,sid[1]);
				console.error(sid[1]);
				n_set_cookie = "";
				if(out == true){
					setTimeout(chrr,3000);
				}else{
					var fq = n_cookerrt(out);
					se_co(fq);	
				}
			}else{
				se_co("无法识别登陆返回信息");
			}
		}
}

function get_login(pdd){
	if(pdd.slice(0,3)=="ERR"){
			se_co(err_solve(pdd));
		}else if(pdd.slice(0,3)=="ULS"){
				mask("登陆成功，为您跳转");
				setTimeout(chrr,1000);
		}else{
			se_co("无法识别登陆返回信息");
		}
	
	}

var cnow = "log";

function n_ajax_init(thing){
if (window.XMLHttpRequest){
	return new XMLHttpRequest();
} else if(window.AtiveX("HTMLREQUEST")){
	return new XMLHttpRequest();
}else{
	location.href="/help/co.php?code=ERRAJAX";
	}
}

var froo = Array();
function n_ajax_get(addr,func){
	tmp = n_ajax_init();
	n_ajax_start(tmp,"GET",function(){aj_respond(tmp,func)},addr,null,false);
}
function n_ajax_post(addr,meth,func){
	tmp = n_ajax_init();
	n_ajax_start(tmp,"POST",function(){aj_respond(tmp,func)},addr,meth,false);
}
function n_ajax_start(aobj,method,func,addr,post,away){
	ato = function(){};
	if(away == true || !away || away == null){
		aobj.open(method,addr);
	}else{
		ato = func;
		aobj.open(method.addr,away);
	}
	if(method == "POST"){
		aobj.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	}
	aobj.onreadystatechange = func;
	aobj.send(post);
	ato();
}
function aj_respond(aobj,func){
	if (aobj.readyState==4 && aobj.status==200)
    {
		block = false;
		if(aobj.responseText == "" || !aobj.response || aobj.responseText == null){
			showd("无法读取登录信息");	
		}
		func(aobj.responseText,aobj);
	}
	if(aobj.readyState==2){
		mask("正在提交");
	}else if(aobj.readyState==3){
 		mask("正在处理");	
	}
	if(aobj.readyState==4 && aobj.status == 404){
		func("ERR404");
	}
}

function doreg(){
	if(validate() != true){se_co(validate());return;}
	block = true;
	mask('很抱歉，注册服务暂时不可用！请联系刘志新老师');
	//n_ajax_post("/uc/reg.php?org=dor&rand="+Math.random(),"usn=" + encodeURIComponent(_id("i0116").value) + "&pss=" + encodeURIComponent(_id("i0118").value) + "&email="+encodeURIComponent(_id("i0120").value)+"&qq="+encodeURIComponent(_id("i0121").value),get_reg);
}
function validate(){
	if(_id("i0119").value != _id("i0118").value){return "两次输入的密码不一致，请更改";}
	return true;
}

function heart(){
	if(cnow == "log" && localStorage.getItem('usn') != null){
	_id("i0116").value = localStorage.getItem('usn');
	_id("i0118").value = localStorage.getItem('pss');
	}
}
function evt_Login_onload(event){
	login_page = _id("rightTD").innerHTML;
	demask();
	heart();
}
function gote(){
	if(block == false){
	block = true;
	var x1 = _id('i0116').value;
	var x2 = _id('i0118').value;
	var x3 = _id('idChkBx_PWD_KMSI0Pwd').checked;
  	mask("请稍候");
	n_ajax_post("auth/fast.php?v=2","login="+encodeURIComponent(x1)+"&passwd="+encodeURIComponent(x2)+"&ranz="+Math.random(),get_login);
	mask("正在登录");
	if(x3 == "on" || x3 == 1 || x3 == true){
		localStorage.setItem('usn',x1);
		//localStorage.setItem('pss',x2);
	}
	}
}	

function mask(code1){
		_id('masK').style.display = "block";
		_id('masK').innerHTML = PIC_WAIT + code1;
	
}
function demask(code1){
		_id('masK').style.display = "none";
		_id('masK').innerHTML = ERR_PNG + "您好，您的浏览器可能不支持我们的技术，详情请联系刘志新老师";
}
function se_co(code){
	//Return To Main;
	demask();
	_id('idTd_Tile_Error').style.display = "block";
	_id("idTd_Tile_ErrorMsg_Login").innerHTML = code;
}
function chrr(){
	//跳转
	
	top.location.href = "index.php";

}

function err_solve(ttt){
	switch(ttt.substr(3)){
	case "INT-1006":dfv =  "您的输入的数据中存在不合法的内容，我们不能识别您输入的字符，详情请";break;
	case "INT-1007":dfv =  "您的输入可能含过长或者过短";break;
	case "UNAUTH":dfv =  "您的浏览器可能对我们的服务支持不兼容，访问受限";break;
	case "EXIST":dfv = "用户已存在";break;
	case "INT":dfv = "内部错误";break;
	case "NONE":dfv = "没有数据或长度过短";break;
	case "EXT-1006":dfv="我们不能识别您输入的字符(或空)，请勿输入符号";break;
	case "EXT-1007":dfv="您的输入的数据不完整或者长度过短";break;
	case "NOUSN":dfv = "您输入的用户名太短";break;
	case "NOPSS":dfv = "您输入的密码太短";break;
	case "NOEMI":dfv = "您输入的邮箱太短";break;
	case "NOQQ":dfv = "您输入的QQ太短";break;
	case "LEN":dfv = "您的输入太短或过长";break;
	case "USNDU":dfv = "用户名已存在，请直接登录";break;
	case "QQDU":dfv = "QQ已存在";break;
	case "EMIDU":dfv = "邮箱已存在";break;
	case "NOUS":dfv = "用户不存在";break;
	case "LEN":dfv = "用户名或密码过短";break;
	case "PASS":dfv = "密码不匹配";break;
	default:dfv = "内部错误："+ttt;
	}
	return dfv;
}
