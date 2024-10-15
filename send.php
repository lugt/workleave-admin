<?php
require_once "../core/load.php";
Load::I("Security");
Load::I("DB");
/*
Load::I("User");
Load::I("User.Check");
*/
Load::ER();
//File : data/
//Fast Mode
$f0 = file("ratedata.dat");
$number = $_GET['rate'];
echo "<span style=\"color:#FF4400\">";
if(strlen($number) < 6){
	$numbers = filter_var($number, FILTER_SANITIZE_NUMBER_INT);
	$q = Sec::C($numbers);
	$usb = new DB_Act("I","(NULL, 'zuoye1.0.00.01r20140109', '".$numbers."', '".time()."')","nim_ratings"," (`id`, `product`, `rate`, `time`)");
}

$g = $f0[0];
$t = $f0[1];
if($_REQUEST['chart'] == "view"){
	echo "好: ".$g."烂: ".$t;
	die();
	}
$f = fopen("ratedata.dat","w+");
if($_REQUEST['rt'] == "on"){
	$t = (Int)$t + 1;
	echo "谢谢你的评判，我们将做得更好。 &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  ";
	}else{
	$g = (Int)$g + 1;
	echo "谢谢你的夸奖哦。 &nbsp; &nbsp;  &nbsp;  &nbsp;  &nbsp;  ";
	}
fwrite($f,(Int)$g."\n".(Int)$t);
fclose($f);
echo "好: ".$g."&nbsp;&nbsp;&nbsp;&nbsp;烂: ".$t;
echo "</span>";
?>