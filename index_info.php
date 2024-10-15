<?php

function starter(){
	
	?>
	
	<!DOCTYPE html>
<html style="font-size:22px;"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title></title>
<link href="home_files/admin_style.css" rel="stylesheet">
</head>
<body>
	
	<?php
	
	}

function Global_None(){

starter();

?>

<div class="wrap">
	<div id="home_toptip"></div>
	<h2 class="h_a">系统信息</h2>
	<div class="home_info">
		<ul>
			<li>
				<em>系统时间</em>
				<span><?php date_default_timezone_set('PRC'); echo date("Y-m-d H:i:s"); ?> <a href="#" target="_blank">当前系统时间正确</a></span>
			</li>
			<!-- 
			<li>
				<em>操作系统</em>
				<span>WINNT</span>
			</li>
			 -->
			<li>
				<em>请假状态</em>
				<span><?php echo "正常"?></span>
			</li>
			<li>
				<em>登录用户</em>
				<span><?php echo $_SESSION['name']; ?></span>
			</li>
			<li>
				<em>服务</em>Hadoop </li>
			<li>
				<em>当前权限</em>
				<span><?php if(strpos($_SESSION['priv'],"leave") !== FALSE){
					    	echo "可请假 ";
					    }
					    if(strpos($_SESSION['priv'],"auth") !== FALSE){
                                            	echo "可审核";
                                            }else{
					    }
					   ?></span>
			</li>
			<li>
				<em>手机号码</em>
				<span><?php echo $_SESSION['phone'];?></span>
			</li>
			<li>
				<em>服务支持</em>
				<span>请联系团委刘志欣老师！</span>
			</li>
		</ul>
	</div>
	<h2 class="h_a">开发团队</h2>
	<div class="home_info" id="home_devteam">
	  <p>系统管理：团委学生会 德育中心</p>
	  <p>系统运维：学生会媒体部</p>
	  <p>内部电话： （010-8274 ） 7012</p>
	</div>
</div>
</div>

<?php

ender();

}

function ender(){
echo "</body></html>";
}

?>
								
								