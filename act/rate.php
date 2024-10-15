<?php
header("Location: http://niimei.com/zy/rate.php?product=bangpai");
die();
require_once "../core/load.mod.php";
Load::ER();
Load::I("UA");
Load::I("Security");
Load::I("DB");
$rate = Sec::N($_REQUEST['rate']);
if($rate != "" && substr($rate,0,3) != "ERR"){
	//die($rate);
	$usb = new DB_Act("I","(NULL, 'zuoyefeed', '".$rate/*iconv("utf-8","gb2312",$rate)*/."', '".time()."')","nim_ratings"," (`id`, `product`, `rate`, `time`)");
	if($usb == TRUE || $usb == DB_Act::$C_TRUE){
	die("Thankyou");
	}else{
	//die($usb );
	die("Sorry");
	}
}else{
  d();	
}
function d(){
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>反馈 Feed | BangPAI</title>
<link rel="stylesheet" href="themes/Corel.min.css" />
<link rel="stylesheet" href="themes/jquery.mobile.icons.min.css" />
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.0/jquery.mobile.structure-1.4.0.min.css" />
<style>
*{font-family:Segoe, "Segoe UI", "DejaVu Sans", "Trebuchet MS", Verdana, sans-serif;}
table { width:100%; border-spacing: 0; }
th { text-align:left; }
th h3 { margin:.6em 0 .6em .5em; }
th, td { vertical-align:top; border-top:1px solid #eee; padding: 1px 3px; background-color:#fcfcfc; }
td .ui-btn { margin:.4em 0 .5em 0; }
td .ui-btn-inner { padding: .4em 15px; }</style>
<script src="http://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="http://code.jquery.com/mobile/1.4.0/jquery.mobile-1.4.0.min.js"></script>
	
</head>

<body>
<form>
<div data-role="page" data-theme="a">
			<div data-role="header" data-position="inline" data-theme="d">
				<h1 style="font-size:1.2em;">建议圈    |   点点</h1>
			</div>
			<div data-role="content" data-theme="d">
				<p>&nbsp;</p>
				<p style="color:#004400;" class="ui-shadow ui-corner-all ui-content">额，我们有很长的路要走，希望这路上，能有你的一份帮助。写下建议，让我们做得更好！</p>
                <p>如果允许，请提前登陆以便我们与您进一步联系。</p>
				<p><hr></p>
  <label for="is_recom">是否为建议?</label>
                <select name="is_recom" id="is_recom" data-role="flipswitch" data-theme="d">
                    <option value="off">不</option>
                    <option value="on">是的</option>
                </select>
				<p></p>
                <label for="rate">内容:</label>
    <textarea name="rate" id="rate" min-height="200px" data-theme="b" style="letter-spacing:0.2em;font-size:13px;font-family:Impact, Haettenschweiler, 'Franklin Gothic Bold', 'Arial Black', sans-serif"></textarea>
			    <div data-role="controlgroup" data-type="horizontal" data-mini="true">
    			<a href="#" id="rate-del"  class="ui-shadow ui-btn ui-corner-all ui-btn-icon-left ui-icon-delete ui-btn-b">重置</a>
    			<a href="#" id="rate-add"  class="ui-shadow ui-btn ui-corner-all ui-btn-icon-left ui-icon-plus ui-btn-b">恢复</a>
    			<a href="#" id="rate-up" class="ui-shadow ui-btn ui-corner-all ui-btn-icon-left ui-icon-grid ui-btn-b">告诉我们</a>
				</div>
</form
                 >
                 <center style="width:100%"><p>Niimei.com &copy; 2013</p></center>
<script>
var q;
$( document ).on( "click", "#rate-del", function() {
	q = $("#rate")[0].value;
	$("#rate")[0].value = "";
});
$( document ).on( "click", "#rate-add", function() {
	$("#rate")[0].value = q;
});
$( document ).on( "click", "#rate-up", function() {
			$.ajax("rate.php?rate="+$("#rate")[0].value).done(on_or);			
			var $this = $( this ),
			theme = $this.jqmData( "theme" ) || $.mobile.loader.prototype.options.theme,
			msgText = "你写的信正在以光速飞向Niimei的客户专员";//$this.jqmData( "msgtext" ) || $.mobile.loader.prototype.options.text,
			textVisible = true;//$this.jqmData( "textvisible" ) || $.mobile.loader.prototype.options.textVisible,
			textonly = !!$this.jqmData( "textonly" );
			html = $this.jqmData( "html" ) || "";
			$.mobile.loading( "show", {
					text: msgText,
					textVisible: textVisible,
					theme: theme,
					textonly: textonly,
					html: html
   		 	});
})

			</script>
			<script>
			function on_or(s){
				if(s == "Thankyou"){
				var $this = $(this),
				theme = $.mobile.loader.prototype.options.theme,
				msgText = "你的大恩大德我们会记住的，内牛满面啊!";//$this.jqmData( "msgtext" ) || $.mobile.loader.prototype.options.text,
				textVisible = true;
				textonly = true;
				html = "";
				$.mobile.loading( "show", {
					text: msgText,
					textVisible: textVisible,
					theme: theme,
					textonly: textonly,
					html: html
   		 		});
				c = true;
				setTimeout(stop_buf,2000);
				}else{
				theme = $.mobile.loader.prototype.options.theme,
				msgText = "咦，这个好像没有被传上去。别着急，再试试！";//$this.jqmData( "msgtext" ) || $.mobile.loader.prototype.options.text,
				textVisible = true;
				textonly = true;
				html = "";
				$.mobile.loading( "show", {
					text: msgText,
					textVisible: textVisible,
					theme: theme,
					textonly: textonly,
					html: html
   		 		});
				c = false;
				setTimeout(stop_buf,2000);				
				}
			}
			var c;
			function stop_buf(){
				$.mobile.loading( "hide" );
					if(c == true){
					history.go(-1);
					}
				}
			</script>
</body>
</html>
<?php }
?>
