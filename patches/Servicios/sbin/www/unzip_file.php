<?
header('Content-Type: text/html; charset=utf-8');
error_reporting(0);
include '/tmp/lang.php';
$dir = $_GET['dir'];
//$target_dir = "/tmp/usbmounts" . $_GET['dir'];
$filename = $_GET['file']; 
//echo "<script>alert('$filename');</script>";

if (substr($dir, 0, 12) == "/tmp/myshare")
	$target_dir = $_GET['dir'];
else
	$target_dir = "/tmp/usbmounts" . $_GET['dir'];

$target_dir = str_replace("(", "\(", $target_dir);
$target_dir = str_replace(")", "\)", $target_dir);
$target_dir = str_replace(" ", "\ ", $target_dir);

$filename = str_replace(" ", "\ ", $filename);
$filename = str_replace("(", "\(", $filename);
$filename = str_replace(")", "\)", $filename);

if (strtolower(strrchr($filename,'.')) == ".zip"){
	$cmd = "unzip " . $filename." -d ".$target_dir;
}else if (strtolower(strrchr($filename,'.')) == ".tar"){
	$cmd = "tar xf " . $filename." -C ".$target_dir;
}else if (strtolower(strrchr($filename,'.')) == ".bz2"){
	$cmd = "tar -xjvf " . $filename." -C ".$target_dir;
}
//echo "<script>alert('$cmd');</script>";
exec($cmd);

	echo "<script>parent.document.location.href = 'unzip.php?dir=$dir';</script>";
?>