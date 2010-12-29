<?
error_reporting(0);
session_start();
$_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
include "../chooselang.php";
include '/tmp/lang.php';

$file = "/usr/local/etc/setup.php";
$fp = fopen($file, 'r');
$fileData = fread($fp, filesize($file));
fclose($fp);

$line = explode("\n", $fileData);
$i = 1;
while ($i <= 5) {
	$dataPair = explode('=', $line[$i]);
	if ($dataPair[0] == "Login" && $dataPair[1] == "true") {
			if ($_SESSION['loggedIn'] != 1) {
				   //header("Location:../login_form.php");
				   echo "<script>document.location.href='../login_form.php'</script>";
					exit;
			}
	}
	$i++;
}

exec("echo -n ',*' >> /tmp/ir");

//exec("echo -n '*' >> /tmp/ir");
usleep(300000);
$file1 = "/tmp/nowstatus";
$fp1 = fopen($file1, 'r');
$fileData1 = fread($fp1, filesize($file1));
fclose($fp1);
$line1 = explode("\n", $fileData1);
$type = $line1[1];
$playing = substr(strrchr($line1[2], '/'), 1);

if (substr($line1[2], 0, 12) == "/tmp/myshare"){                                       //for NetShare
	$cur_dir = substr($line1[2], 12, strrpos($line1[2], '/')-12);
	$playing_file = '../media3'.rawurlencode($cur_dir).'/'.rawurlencode($playing);
	//$path = '../media3'.$cur_dir;
}else{																					//for HDD files
	$cur_dir = substr($line1[2], 18, strrpos($line1[2], '/')-18);
	$playing_file = '../media2'.rawurlencode($cur_dir).'/'.rawurlencode($playing);
	//$path = '../media2'.$cur_dir;
}

//echo "<script>alert('$cur_dir');</script>";
//$file_without_ext = substr($playing, 0, strrpos($playing, '.'));
//echo "<script>alert('$file_without_ext');</script>";
//$shufl_seq = $line1[3];
//$repeat = $line1[4];
$time = $line1[5];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>Xtreamer</title>
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="statics/styles/general.css" rel="stylesheet" type="text/css" />
    <script src="statics/scripts/panel_funcs.js"></script>    
    <script src="statics/scripts/global.js"></script> 
	<!--script>
		function aaa(value){
			goTo(value);
			window.location.reload();
		}
	</script-->
</head>
<body>
    <img id="progressImg" src="statics/images/wifi_icon.png" />
    <map name="screenshotPage">
        <area shape="rect" coords="12,198,73,260" title="Settings" href="#" onclick="goTo('fSettings')">
        <area shape="rect" coords="243,198,313,260" title="<?echo $STR_Refresh;?>" href="#" onclick="goTo('fRefresh')">
		<area shape="rect" coords="1,265,75,308" title="<?echo $STR_Sub_panel;?>" href="#" onclick="goTo('fGotosub')">
		<area shape="rect" coords="243,265,313,308" title="Return" href="#" onclick="goTo('fReturn')">

        <area shape="poly" coords="101,220,77,274,101,334,130,306,118,277,129,247,101,220" title="Left" href="#" onclick="goTo('fLeftarrow')">
        <area shape="poly" coords="191,247,201,274,189,306,214,334,238,274,218,220,191,247" title="Right" href="#" onclick="goTo('fRightarrow')">
        <area shape="poly" coords="130,246,159,235,189,246,216,219,157,200,106,220,130,246" title="Up" href="#" onclick="goTo('fToparrow')">
        <area shape="poly" coords="130,306,103,333,159,354,216,334,188,306,159,318,130,306" title="Down" href="#" onclick="goTo('fBottomarrow')">
        <area shape="circle" coords="160,278,40" title="Enter" href="#" onclick="goTo('fEnter')">

		<area shape="circle" coords="20,336,20" title="Previous" href="#" onclick="goTo('fPrevious')">
        <area shape="circle" coords="63,336,20" title="Play/Pause" href="#" onclick="goTo('fPlay')">
		<area shape="circle" coords="258,336,20" title="Stop" href="#" onclick="goTo('fStop')">
		<area shape="circle" coords="298,336,20" title="Next" href="#" onclick="goTo('fNext')">
    </map>
    <img src="statics/images/screenshot.png" border="0" usemap="#screenshotPage"/>

	<?
		if($type == "Photo"){
			echo "<div id='screenshot'>";
			echo "<a href='$playing_file'><img src='$playing_file' width='320px' height='180px' title='$playing' border='0' /></a>";
			echo "</div>";
		}else{
	?>
		<div id="screenshot">
			<a href='../screenshot.bmp'><img src='../screenshot.bmp' width='320px' height='180px' title='ScreenShot' border='0' /></a>
		</div>
	<?}?>

	<div id="screenshotinfo">
        <div id="npName"><marquee scrolldelay=200 onmouseover="this.stop();" onmouseout="this.start();" behavior="alternate"><a href="nowplaying.php"><?echo $playing;?></a></marquee></div>
		<!--div id="nptime1"><?echo $time;?></div-->
    </div>

<iframe name='gframe' width=0 height=0 style="display:none"></iframe>
</body>
</html>