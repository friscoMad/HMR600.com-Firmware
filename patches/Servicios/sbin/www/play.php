<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(0);

//$dir = stripslashes($_GET['dir']);
//$file = stripslashes($_GET['file']);
$dir = $_GET['dir'];
$file = $_GET['file'];

//$dir = utf8_encode($dir);
//$file = utf8_encode($file);

//echo "Playing your file on TV...";

//echo "<script>alert('$dir');</script>";
//echo "<script>alert('$file');</script>";

/*if (substr($dir,6,5) == "/sda1"){
	$path = substr($dir,0,6) . "C:" . substr($dir,11);
}else if (substr($dir,6,5) == "/sda2"){
	$path = substr($dir,0,6) . "D:" . substr($dir,11);
}else if (substr($dir,6,5) == "/sdb1"){
	$path = substr($dir,0,6) . "E:" . substr($dir,11);
}else if (substr($dir,6,5) == "/sdb2"){
	$path = substr($dir,0,6) . "F:" . substr($dir,11);
}else{
	$path = $dir;
}*/

$Netshare_path = substr($_GET['dir'],6,12);
if ($Netshare_path == "/tmp/myshare"){
	$path = $dir;
}elseif (($dir != "Video playlist")&&($dir != "Audio playlist")&&($dir != "Photo playlist")){
	$dir1 = substr($dir,6,5);
	//echo "<script>alert('$dir1');</script>";
	if (substr($dir1, -1) == "/"){
		$dir1 = substr($dir1,0,4);
	//echo "<script>alert('After Cutting: $dir1');</script>";
	}

	$HDDInfo = shell_exec("ls -l /tmp/ramfs/volumes/|grep $dir1");
	sscanf($HDDInfo,"%s %s %s %s %s %s %s %s %s", $a, $b, $c, $d, $e, $f, $g, $h, $drive);
	//echo "<script>alert('$drive');</script>";
	$path = substr($dir,0,6) . $drive . substr($dir,11);
}else{
	$path = $dir;
}


$filename = "/tmp/webrun";
$fp = fopen($filename, 'w');
if ($file != "")
	fwrite($fp, $path."/".$file);
else
	fwrite($fp, $path);
fclose($fp);
exec("echo -n '%' > /tmp/ir");

echo "<script>window.location.href='rc/main.php';</script>";
?>
