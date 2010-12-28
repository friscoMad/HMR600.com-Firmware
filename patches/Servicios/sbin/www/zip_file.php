<?
header('Content-Type: text/html; charset=utf-8');
error_reporting(0);
include '/tmp/lang.php';
//$mediapath = "/tmp/usbmounts" . $_GET['dir'];
//$mediapath = $_GET['dir'];
$filename = $_POST['filelist']; 
$dir = $_GET['dir'];

if (substr($dir, 0, 12) == "/tmp/myshare")
	$$mediapath = $_GET['dir'];
else
	$$mediapath = "/tmp/usbmounts" . $_GET['dir'];

$countdata = count($filename);

$mediapath = str_replace("(", "\(", $mediapath);
$mediapath = str_replace(")", "\)", $mediapath);
$mediapath = str_replace(" ", "\ ", $mediapath);

$filename = str_replace(" ", "\ ", $filename);
$filename = str_replace("(", "\(", $filename);
$filename = str_replace(")", "\)", $filename);


	for ($i=0; $i<$countdata; $i++){
			//$cmd = "tar -cvf ". $mediapath . "/" . $filename[$i].".tar ".$mediapath . "/" . $filename[$i] ;
		//if (substr($mediapath, 0, 12) == "/tmp/myshare"){
			$cmd = "cd ".$mediapath. ";tar -cf ". $filename[$i].".tar ".$filename[$i] ;
		//}else{
		//	$cmd = "cd /tmp/usbmounts".$mediapath. ";tar -cf ". $filename[$i].".tar ".$filename[$i] ;
		//}
		exec($cmd, $output, $result);
		if ($result != 0){
			echo "<script>alert('Cant zip $filename[$i]');</script>";		
		}

	}
	//echo "<script>parent.document.location.href = 'zip.php?dir=$dir';</script>";
	echo "<script>alert('Done');</script>";
	echo "<script>parent.window.close();</script>";

?>