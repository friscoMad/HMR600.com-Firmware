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


	//Read currently playing file name
	exec("echo -n '*' >> /tmp/ir");
	usleep(300000);
	$file1 = "/tmp/nowstatus";
	$fp1 = fopen($file1, 'r');
	$fileData1 = fread($fp1, filesize($file1));
	fclose($fp1);
	$line1 = explode("\n", $fileData1);
	$type = $line1[1];
	$playing = substr(strrchr($line1[2], '/'), 1);
	$shufl_seq = $line1[3];
	$repeat = $line1[4];
	$time = $line1[5];
	//echo "<script>alert('$playing');</script>";
?>

<!--meta http-equiv="refresh" content="5"-->


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
</head>
<body>
    <img id="progressImg" src="statics/images/wifi_icon.png" />
    <map name="mainPage">
        <area shape="rect" coords="2,60,85,0" title="Power" href="#" onclick="goTo('fPower')">
        <area shape="rect" coords="238,70,318,127" title="Subtitles" href="#" onclick="goTo('fSubtitles')">
        <area shape="rect" coords="237,60,317,2" title="Info" href="#" onclick="goTo('fInfo')">
        <area shape="rect" coords="238,130,319,167" title="Home" href="#" onclick="goTo('fHome')">
        <area shape="rect" coords="85,131,0,167" title="Return" href="#" onclick="goTo('fReturn')">
        <area shape="rect" coords="79,61,3,127" title="Settings" href="#" onclick="goTo('fSettings')">
        <area shape="poly" coords="127,53,106,31,85,83,105,132,126,112,116,82,125,54,124,53,123,52,127,53" title="Left" href="#" onclick="goTo('fLeftarrow')">
        <area shape="poly" coords="191,53,213,33,235,81,214,134,195,112,206,83,191,52,191,52,191,53" title="Right" href="#" onclick="goTo('fRightarrow')">
        <area shape="poly" coords="128,49,156,38,188,49,211,29,158,3,105,29,108,31,108,31,128,49" title="Up" href="#" onclick="goTo('fToparrow')">
        <area shape="poly" coords="130,114,158,125,188,114,211,136,158,158,108,137,129,116,129,116,130,114" title="Down" href="#" onclick="goTo('fBottomarrow')">
        <area shape="circle" coords="162,84,40" title="Enter" href="#" onclick="goTo('fEnter')">
        <area shape="circle" coords="192,194,31" title="Stop" href="#" onclick="goTo('fStop')">
        <area shape="circle" coords="126,196,30" title="Play/Pause" href="#" onclick="goTo('fPlay')">
        <area shape="rect" coords="226,170,270,220" title="Next" href="#" onclick="goTo('fNext')">
        <area shape="rect" coords="93,170,50,224" title="Previous" href="#" onclick="goTo('fPrevious')">

		<area shape="rect" coords="58,263,5,215" title="Rewind" href="#" onclick="goTo('fRW')">
		<area shape="rect" coords="260,263,310,215" title="FastForward" href="#" onclick="goTo('fFF')">

        <area shape="rect" coords="118,267,1,308" title="Vol -" href="#" onclick="goTo('fVolumminus')">
        <area shape="rect" coords="207,309,319,268" title="Vol +" href="#" onclick="goTo('fVolumplus')">
        <area shape="rect" coords="123,246,205,377" title="<?echo $STR_Sub_panel;?>" href="#" onclick="goTo('fGotosub')">
        <area shape="rect" coords="44,314,-2,360" title="<?echo $STR_Refresh;?>" href="#" onclick="goTo('fAll')">
        <area shape="rect" coords="49,313,109,356" title="<?echo $STR_Play_Video_list;?>" href="#" onclick="goTo('fMovies')">
        <area shape="rect" coords="210,313,264,355" title="<?echo $STR_Play_Audeo_list;?>" href="#" onclick="goTo('fMusic')">
        <area shape="rect" coords="270,313,318,355" title="<?echo $STR_Play_Photo_list;?>" href="#" onclick="goTo('fPictures')">
    </map>
    <img src="statics/images/main.png" border="0" usemap="#mainPage"/>
    <div id="nowplaying"><marquee scrolldelay=200 onmouseover="this.stop();" onmouseout="this.start();" behavior="alternate"><a href="nowplaying.php"><span id="filename"><?echo $playing;?></span></a></marquee></div>  

<iframe name='gframe' width=0 height=0 style="display:none"></iframe>
</body>
</html>
