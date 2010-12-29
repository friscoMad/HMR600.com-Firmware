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

exec("echo -n '*' >> /tmp/ir");
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
	$path = '../media3'.$cur_dir;
}else{																					//for HDD files
	$cur_dir = substr($line1[2], 18, strrpos($line1[2], '/')-18);
	$playing_file = '../media2'.rawurlencode($cur_dir).'/'.rawurlencode($playing);
	$path = '../media2'.$cur_dir;
	//echo "<script>alert('$path');</script>";
}
$file_without_ext = substr($playing, 0, strrpos($playing, '.'));
//echo "<script>alert('$file_without_ext');</script>";
$shufl_seq = $line1[3];
$repeat = $line1[4];
$time = $line1[5];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
    <title>XtreamerPro</title>
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta names="apple-mobile-web-app-status-bar-style" content="black-translucent" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="statics/styles/general.css" rel="stylesheet" type="text/css" />
    <script src="statics/scripts/panel_funcs.js"></script>    
    <script src="statics/scripts/global.js"></script> 
</head>
<body>
    <img id="progressImg" src="statics/images/wifi_icon.png" />
    <map name="nowplayingPage">
        <area shape="rect" coords="112,1,2,63" title="<?echo $STR_Sub_panel;?>" href="#" onclick="goTo('fGotosub')">
        <area shape="rect" coords="317,2,205,65" title="<?echo $STR_Refresh;?>" href="#" onclick="goTo('fShowNowPlaying')">
        <area shape="rect" coords="200,0,116,117" title="<?echo $STR_Main;?>" href="#" onclick="goTo('fShowMainControl')">
        <area shape="rect" coords="42,68,-1,113" title="Previous" href="#" onclick="goTo('fPrevious')">
        <area shape="rect" coords="111,66,44,115" title="Play/Pause" href="#" onclick="goTo('fPlay')">
        <area shape="rect" coords="261,67,205,115" title="Stop" href="#" onclick="goTo('fStop')">
        <area shape="rect" coords="318,69,263,115" title="Next" href="#" onclick="goTo('fNext')">       
    </map>
    <img src="statics/images/nowplaying.png" border="0" usemap="#nowplayingPage"/> 
    <div id="cover">
		<?if($type == "Video"){
			if(file_exists($path.'/'. $file_without_ext. '.jpg')){
				echo "<a href='$path/$file_without_ext.jpg'><img src='$path/$file_without_ext.jpg' width='146px' height='213px' title='$playing' border='0' /></a>";
			}else if(file_exists($path.'/Cover.jpg')){
				echo "<a href='$path/Cover.jpg'><img src='$path/Cover.jpg' width='146px' height='213px' title='$playing' border='0' /></a>";
			}else if(file_exists($path.'/folder.jpg')){
				echo "<a href='$path/folder.jpg'><img src='$path/folder.jpg' width='146px' height='213px' title='$playing' border='0' /></a>";
			}else{
				echo "<a href='statics/images/movie_coverart.png'><img src='statics/images/movie_coverart.png' width='146px' height='213px' title='Video' border='0' /></a>";
			}
		}else if($type == "Music"){
			if(file_exists('../mp3jacket')){
				echo "<a href='../mp3jacket'><img src='../mp3jacket' width='146px' height='213px' title='Audio' border='0' /></a>";
			}else{
				echo "<a href='statics/images/music_coverart.png'><img src='statics/images/music_coverart.png' width='146px' height='213px' title='Audio' border='0' /></a>";
			}
		}else if($type == "Photo"){
			echo "<a href='$playing_file'><img src='$playing_file' width='146px' height='213px' title='$playing' border='0' /></a>";
		}
		?>
    </div>
    <div id="nowplayingproperties">
        <div class="ttl">Title:</div>
        <div id="npName"><?echo "<a href='$playing_file'>$playing</a>";?></div>
        <br />
        <div class="ttl">Time:</div>
        <div id="nptime"><?echo $time;?></div>
		<br />
        <div class="ttl">Shuffle:</div>
        <div id="shuffle"><?echo $shufl_seq;?></div>
		<br />
        <div class="ttl">Repeat:</div>
        <div id="repeat"><?echo $repeat;?></div>
    
    </div>
<iframe name='gframe' width=0 height=0 style="display:none"></iframe>
</body>
</html>