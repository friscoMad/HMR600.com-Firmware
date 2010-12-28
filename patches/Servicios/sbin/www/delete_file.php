<?
header('Content-Type: text/html; charset=utf-8');
error_reporting(0);
include '/tmp/lang.php';
//$mediapath = "/tmp/usbmounts" . $_GET['dir'];
$mediapath = $_GET['dir'];
$filename = $_POST['filelist']; 
$dir = $_GET['dir'];

$countdata = count($filename);
//echo "<script>alert('$mediapath');</script>";
//echo "<script>alert('$filename[0]');</script>";

for ($i=0; $i<$countdata; $i++){
		$filename[$i] = stripslashes($filename[$i]);
}

//even delets non-empty dir
function deleteDir($dir) {
   $dhandle = opendir($dir);

   if ($dhandle) {
      while (false !== ($fname = readdir($dhandle))) {
         if (is_dir( "{$dir}/{$fname}" )) {
            if (($fname != '.') && ($fname != '..')) {
             //  echo "<u>Deleting Files in the Directory</u>: {$dir}/{$fname} <br />";
               deleteDir("$dir/$fname");
            }
         } else {
           // echo "Deleting File: {$dir}/{$fname} <br />";
            unlink("{$dir}/{$fname}");
         }
      }
      closedir($dhandle);
    }
//   echo "<u>Deleting Directory</u>: {$dir} <br />";
   rmdir($dir);
}


if($countdata == 0){
	echo "<script>alert('$STR_NoFileToDel');</script>";
}else{
	for ($i=0; $i<$countdata; $i++){
		if (substr($mediapath, 0, 12) == "/tmp/myshare"){
			if (!is_dir($mediapath . "/" . $filename[$i])){			
				unlink($mediapath . "/" . $filename[$i]);
			}else{
				deleteDir($mediapath . "/" . $filename[$i]);
			}
		}else{
			if (!is_dir("/tmp/usbmounts" . $mediapath . "/" . $filename[$i])){			
				unlink("/tmp/usbmounts" . $mediapath . "/" . $filename[$i]);
				//echo "<u>Deleting Files: {$mediapath}/{$filename[$i]} <br />";
			}else{
				deleteDir("/tmp/usbmounts" . $mediapath . "/" . $filename[$i]);
			}
		}
	}
	echo "<script>parent.document.location.href = 'delete.php?dir=$dir';</script>";
}
?>