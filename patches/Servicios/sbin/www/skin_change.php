<script language=javascript>
function newwindow(w,h,webaddress){
	var viewimageWin = window.open(webaddress,"New_Window","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width="+w+",height="+h);
	viewimageWin.moveTo(screen.availWidth/2-(w/2),screen.availHeight/2-(h/2));
}
</script>

<?
header('Content-Type: text/html; charset=utf-8');
//error_reporting(0);
include '/tmp/lang.php';
$mediapath = "/tmp/usbmounts" . $_GET['dir'];
$list = $_POST['filelist']; 
$dir = $_GET['dir'];

$countdata = count($list);

for ($i=0; $i<$countdata; $i++){
		$list[$i] = stripslashes($list[$i]);
}

/*
for ($i=0; $i<$countdata; $i++){
	for ($x=0; $x<sizeof($files); $x++) {
		if ($files[$x] == $list[$i]){
			//echo "<script>alert('$files[$x]');</script>";
			//echo "<script>alert('File= $list[$i]');</script>";
			copy($mediapath."/".$list[$i], "/usr/local/bin/Resource/bmp/". $files[$x]);
			break;
		}
		
	}
}
*/

$filename = "/tmp/skin.txt";
$fp = fopen($filename, 'w');


for ($i=0; $i<$countdata; $i++){

		if (file_exists("/usr/local/bin/Resource/bmp/". $list[$i])){
			//echo "<script>alert('$files[$x]');</script>";
			//echo "<script>alert('File= $list[$i]');</script>";
			if (filesize("/usr/local/bin/Resource/bmp/". $list[$i]) > 2097152){
				fwrite($fp, $list[$i]. "----".$STR_Skin_Size_Fail."\n");
			}else{
				copy($mediapath."/".$list[$i], "/usr/local/bin/Resource/bmp/". $list[$i]);
				fwrite($fp, $list[$i]. "----".$STR_Skin_OK."\n");
			}
		}else{
			fwrite($fp, $list[$i]. "----".$STR_Skin_Fail."\n");
		}
}
fclose($fp);
?>

<script>
	newwindow(520, 420, 'skin_log.php');

	loadDivEl = parent.document.getElementById('loadDiv');
	loadDivEl.style.visibility = 'hidden';

	aa= parent.document.getElementById('filelist');
	for (var i =0; i < aa.elements.length; i++)
	aa.elements[i].checked = false;
	parent.document.getElementById('selectall').checked = false;
</script>
