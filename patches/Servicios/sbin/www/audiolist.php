<?php
session_start();
error_reporting(0);
$_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
include "chooselang.php";
include '/tmp/lang.php';

//$root = "/tmp/usbmounts";

$id = $_POST['user_id'];
$pass = $_POST['user_pass'];
$filetypes = array (
                'wma' => 'audio.jpg',
                'mp3' => 'audio.jpg',
                'wav' => 'audio.jpg',
            );
$Netshare_path = substr($_GET['dir'],0,12);
if ($Netshare_path == "/tmp/myshare"){
		
	$mydir =  $_GET['dir'];
	$mediapath =  stripslashes($_GET['dir']);
	
	if ($mydir != "/tmp/myshare"){
		$uplink = substr_replace($_GET['dir'],'',strlen($_GET['dir'])-strlen(strrchr( $_GET['dir'],'/')));

		//check whether already mounted?
		$mydir1 = $mydir;
		$mydir1 = str_replace("(", "\(", $mydir1);
		$mydir1 = str_replace(")", "\)", $mydir1);
		$mydir1 = str_replace(" ", "\ ", $mydir1);
		$cmd = "mount |grep ".$mydir1;
		exec($cmd, $output1, $result1);

		if((substr($mydir, -4) == ".smb") || (substr($mydir, -4) == ".nfs") and ($output1[0] == "")){
		
			$ShareName = '/tmp/myshare/.cmd'.strrchr( $mydir,'/');
			$ShareName = str_replace("(", "\(", $ShareName);
			$ShareName = str_replace(")", "\)", $ShareName);
			$ShareName = str_replace(" ", "\ ", $ShareName);
			//mount
			$cmd = $ShareName . ' ' . $id . ' ' . $pass;
			exec($cmd, $output, $result);
							
			if ($result != 0){
				//$share = strrchr( $mydir,'/');
				//$cmd = $ShareName . " " . $id . " " . $pass;
				//echo "<script>alert('Can not mount $share');</script>";
				echo "<script>window.open('smblogin.php?dir=$mydir', 'All', 'width=350,height=150');</script>";
				//exec($cmd, $output, $result);
				//echo "<script>alert('mydirectory.....$cmd');</script>";
				echo "<script>location.href='audiolist.php?dir=$uplink';</script>";
			}else if($id != ''){
				echo "<script>location.href='audiolist.php?dir=$mydir';</script>";
			}
		}

		$files = myscan($mydir);
		sort($files);

		//$mydir = str_replace("(", "\(", $mydir);
		//$mydir = str_replace(")", "\)", $mydir);

		$command = 'cd ' .$mydir1.';ls -alh > /tmp/aaa' ;
		shell_exec($command);

		$file1 = "/tmp/aaa";
		$fp1 = fopen($file1, 'r');
		//$fileData1 = fread($fp1, filesize($file1));

		$j=0;

		while (!feof($fp1)) {
			$line1[$j++] = fgets($fp1, 4096);
		}
		fclose($fp1);

	}else{
		$file = "/tmp/myshare/share.list";
		$fp = fopen($file, 'r');
		//$fileData = fread($fp, filesize($file));
		
		$j=0;
		while (!feof($fp)) {
			$files[$j++] = fgets($fp, 4096);
		}

		fclose($fp);
	}
	//echo "<script>alert('val of J ......$j');</script>";

	//$files = explode("\n", $fileData);
	//$files = explode("\n", $fileData);


}else{
	$root = "/tmp/usbmounts";
	if ((substr($_GET['dir'],0,2) != '/.') and (substr($_GET['dir'],0,1) != '.') and ($_GET['dir'] != '')) {
		$mydir =  $root . $_GET['dir'];
		$mediapath =  stripslashes($_GET['dir']);
	}else{
		$mydir = $root;
	}
	//echo "<script>alert('$mydir');</script>";

	$uplink = substr_replace($_GET['dir'],'',strlen($_GET['dir'])-strlen(strrchr( $_GET['dir'],'/')));
	
	$files = myscan($mydir);
	sort($files);
	
	$mydir1 = $mydir;
	$mydir1 = str_replace("(", "\(", $mydir1);
	$mydir1 = str_replace(")", "\)", $mydir1);

	$command = 'cd ' .str_replace(" ", "\ ", $mydir1).';ls -alh > /tmp/aaa' ;
	shell_exec($command);


	$file1 = "/tmp/aaa";
	$fp1 = fopen($file1, 'r');
	//$fileData1 = fread($fp1, filesize($file1));

	$j=0;

	while (!feof($fp1)) {
		$line1[$j++] = fgets($fp1, 4096);
	}
	fclose($fp1);

}

function myscan($dir) {
	$arrfiles = array();
	$arrfiles = opendir(stripslashes($dir));
	while (false !== ($filename = readdir($arrfiles))) {
		   $files[] = $filename;
	}   
	return $files;
}

$HDDInfo = shell_exec("df -h|grep /dev/scsi/host0/bus0/target0/lun0/part1");
sscanf($HDDInfo,"%s %s %s %s", $aaa,$HDDTotal, $HDDUsed, $HDDFree);
?>


<HTML>
<head>

<script language=javascript>
//window.onload = function() {
//  myScroll = new iScroll('listingcontainer', {vScrollBar: true, hScrollBar: true});
//}

function goto(form){
	var index=form.File_Manager.selectedIndex
	if (form.File_Manager.options[index].value != "") {
		if (form.File_Manager.options[index].value == "creatfolder.php?dir=<?echo $mediapath;?>") {
			window.open(form.File_Manager.options[index].value,'New_folder','height=200,width=550,left=150,top=200');
		}else if (form.File_Manager.options[index].value == "copy.php?dir=<?echo $mediapath;?>") {
			window.open(form.File_Manager.options[index].value,'copy','height=635,width=987,left=50,top=50');
		}else{
			window.open(form.File_Manager.options[index].value,'FileManager','height=630,width=550,left=220,top=50');
		}
	}
}

function newwindow(w,h,webaddress,name){
	//alert(webaddress);
	var viewimageWin = window.open(webaddress,name,"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width="+w+",height="+h);
	viewimageWin.moveTo(screen.availWidth/2-(w/2),screen.availHeight/2-(h/2));
}

function PlayFolder(value){
	alert(value);
	document.folderPlay.target = 'gframe';
	document.folderPlay.action = value;
	document.folderPlay.submit();
}

function PlayFile(value){
	alert(value);
	document.filePlay.target = 'gframe';
	document.filePlay.action = value;
	document.filePlay.submit();
}

function setPng24(obj) { 
    obj.width=obj.height=1; 
    obj.className=obj.className.replace(/\bpng24\b/i,''); 
    obj.style.filter = 
    "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='"+ obj.src +"',sizingMethod='image');" 
    obj.src='';  
    return ''; 
} 

/*
for iPod div scrolling function by one finger
ref: http://chris-barr.com/entry/scrolling_a_overflowauto_element_on_a_touch_screen_device/

function isTouchDevice(){
	try{
		document.createEvent("TouchEvent");
		return true;
	}catch(e){
		return false;
	}
}

function touchScroll(id){
	if(isTouchDevice){ //if touch events exist...
		var el=document.getElementById(id);
		var scrollStartPos=0;

		document.getElementById(id).addEventListener("touchstart", function(event) {
			scrollStartPos=this.scrollTop+event.touches[0].pageY;
			event.preventDefault();
		},false);

		document.getElementById(id).addEventListener("touchmove", function(event) {
			this.scrollTop=scrollStartPos-event.touches[0].pageY;
			event.preventDefault();
		},false);
	}
}
*/
function alertUser(){
alert('<?echo $STR_Link_Copied_To_Clipboard;?>');
}
</script>


<!--embed name='CS1022563046202' src='dlf/click.wav' loop=false
autostart=false MASTERSOUND hidden=true width=0 height=0>
</embed-->


<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?echo $STR_Title;?></title>
<link rel="stylesheet" type="text/css" href="dlf/styles.css" />
</head>
<!--body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 oncontextmenu="return false;" onload="touchScroll('listing')" for iPod div scrilling-->
<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 oncontextmenu="return false;" onload="touchScroll('listing')">

    <div id="container"> 
	<center>
	<table width="996" cellspacing="0" cellpadding="0" border="0" valign="middle">
	   <!--tr>
			<td width="940" align="right" valign="middle"><font face="Arial" color="#748e94" size="1"><a href="logout.php"><font face="Arial" color="#748e94" size="1"><?echo $STR_Logout;?></a> | <a href="register_form.php"><font face="Arial" color="#748e94" size="1"><?echo $STR_Setup;?></a> </font></td>
	   </tr-->

	   <tr>
			<td align="center"><table height="94" background="dlf/top_menu.jpg" width="996" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="189"><img src="dlf/icon_music.jpg" width="189" height="94" /><td>
					<td valign="bottom">
						<table width="100%" border="0" cellspacing="0" cellpadding="0">

						<tr>
							<td width="5" valign="middle">&nbsp;</td>
							<td colspan="9" valign="middle"><font face="arial" color="#ff0000"><h2><?echo $STR_Audio_title;?></h2></font></td>
						</tr>

						<tr>
							<td width="5" valign="bottom">&nbsp;</td>
							<!--td height="30" width="40" valign="bottom"><font face="arial" color="white" size="2">
								<a href="index.php"><?echo $STR_Home;?></a></td-->
							<td height="30" width="40" valign="bottom" align="middle"><font face="arial" color="white" size="2">
								<a href="videolist.php?dir=<?echo $mediapath;?>"><?echo $STR_Video;?></a></td>
							<td height="30" width="65" valign="bottom" align="middle">
								<a href="audiolist.php?dir=<?echo $mediapath;?>">
								<font face="arial" color="#ff0000" size="2"><u><?echo $STR_Audio;?></u></font></a></td>
							<td height="30" width="40" valign="bottom" align="middle"><font face="arial" color="white" size="2">
								<a href ="imagelist.php?dir=<?echo $mediapath;?>"><?echo $STR_Photo;?></a></td>
							<td height="30" width="65"valign="bottom" align="middle"><font face="arial" color="white" size="2">
								<a href="otherlist.php?dir=<?echo $mediapath;?>"><?echo $STR_All;?></a>&nbsp&nbsp|

							<td height="30" width="30" valign="bottom">
								<?
								 //Mylist
								 if (($Netshare_path != "/tmp/myshare")){
									echo"<input type='button' class='btn_1' onMouseOver='this.style.color= \"#ff0000\"' onMouseOut='this.style.color=\"#FFFFFF\"' name='add' value='".$STR_Mylist."' onclick=\"newwindow(987, 675, 'm3uMusic.php', 'musiclist');\";>";
								 }
							echo '</td><td height="30" width="40" valign="bottom">';
								 //Upload
								 //if (($mediapath != '') and (strncmp($mediapath, '/Media_Library', 14))){
								if ($mediapath != ''){
									//echo"<input type='button' class='btn_1' name='upload' value='".$STR_Upload."' onclick=\"window.open('upload.php?dir=$mediapath','MyVideo','height=570,width=467,left=430,top=270');\";>";
									echo"<input type='button' class='btn_1' onMouseOver='this.style.color= \"#ff0000\"' onMouseOut='this.style.color=\"#FFFFFF\"' name='upload' value='".$STR_Upload."' onclick=\"newwindow(467, 600, 'upload.php?dir=$mediapath', 'upload');\";>";
								 }
							echo '</td><td height="30" width="40" valign="bottom">';
								 //Filemanager
								 //if (($mediapath != '') and (strncmp($mediapath, '/Media_Library', 14))){ 
								if ($mediapath != ''){ ?>
									<FORM NAME="FileManager">
									<select name="File_Manager" class="listbox" ONCHANGE="goto(this.form)">
										<option value=""><?echo $STR_Filemanager;?></option>
										<option value="creatfolder.php?dir=<?echo $mediapath;?>"><?echo $STR_NewFolder;?></option>
										<option value="rename.php?dir=<?echo $mediapath;?>"><?echo $STR_Rename;?></option>
										<option value="copy.php?dir=<?echo $mediapath;?>"><?echo $STR_CopyMove;?></option>
										<option value="delete.php?dir=<?echo $mediapath;?>"><?echo $STR_Delete;?></option>
										<option value="zip.php?dir=<?echo $mediapath;?>">Archive</option>
									</select>
									</FORM>
								<?
								 }

								?>

							</font></td>
						</tr>
						</table>
					</td>


					<td width="300" valign="bottom"><a href="index.php"><img src="dlf/mvix_logo.png" width="300" height="72"></td>



				</tr></table>
			</td>
	   </tr>

	   <tr height="12"><td></td></tr>

	<tr>
		<td height=100% align='center' valign='bottom'>

<!--div style='width: 996; height: 405; overflow: scroll; border: none ; background: transparent; background-image:url("dlf/list_image.png") '-->
        <div id="listingcontainer">
				<table><tr height=12><td></td></tr></table>
			<div id="listing">
<?
echo "<div>";
if(!$_GET["dir"]==''){
echo '<table width="846" height="27" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'" style="border-bottom:1px solid #000000;"><tr><td>';
echo "<table><tr><td><img src='dlf/dirup.png' align='center'>";
echo "<td colspan='250'><a href='" . $_SERVER['PHP_SELF'] . "?dir=" . $uplink ."'>" . $STR_ParentDirectory . "</a></td></tr></table>";
echo "</td></tr></table>";
}
/*
$mydir = str_replace("(", "\(", $mydir);
$mydir = str_replace(")", "\)", $mydir);

$command = 'cd ' .str_replace(" ", "\ ", $mydir).';ls -alh > /tmp/aaa' ;
shell_exec($command);


$file1 = "/tmp/aaa";
$fp1 = fopen($file1, 'r');
//$fileData1 = fread($fp1, filesize($file1));

$i=0;

while (!feof($fp1)) {
    $line1[$i++] = fgets($fp1, 4096);
}
fclose($fp1);
*/
//$line1 = preg_split("/\n/", $fileData1);


if ($Netshare_path == "/tmp/myshare"){
	for ($x=0; $x<($j-1); $x++) {

		if ($mydir == "/tmp/myshare"){
			echo '<table width="846" height="27" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'" style="border-bottom:1px solid #000000;">';
			echo '<tr><td width=400>';
			echo "<table><tr><td><img src='dlf/folder.png' align='center'>";
			echo "<td width=380><a href=\"" . $_SERVER['PHP_SELF'] . "?dir=" . $mediapath . "/" . $files[$x] . "\"   class='rollover'>" . $files[$x] . "</td>";
			echo "</tr></table>";
			echo "</td></tr></table>";
		}else if(substr($line1[$x],0,1) == 'd'){
			if (($files[$x] != '.') and ($files[$x] != "..") and ($files[$x] != "Recycled") and ($files[$x] != "System Volume Information") and (substr($files[$x],0,1) != ".") and ($files[$x] != "lost+found")){
				echo '<table width="846" height="27" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'" style="border-bottom:1px solid #000000;">';
				echo '<tr><td width=400>';

				if (strlen($files[$x]) > 40) {
					echo "<table><tr><td><img src='dlf/folder.png' align='center'>";
					echo "<td width=380><a href=\"" . $_SERVER['PHP_SELF'] . "?dir=" . $mediapath . "/" . $files[$x] . "\"   class='rollover'>" . substr($files[$x],0,40) . "...</td>";
					echo "</tr></table>";
				}else{
					echo "<table><tr><td><img src='dlf/folder.png' align='center'>";
					echo "<td width=380><a href=\"" . $_SERVER['PHP_SELF'] . "?dir=" . $mediapath . "/" . $files[$x] . "\"   class='rollover'>" . $files[$x] . "</td>";
					echo "</tr></table>";
				}

				//if ($mediapath != ""){
				echo "</td><td width='20'>";
				echo "<table  width='20' height='20' align='left' cellspacing='0' cellpadding='0' border='0'> <tr>";
				echo "<td><a onClick=\"newwindow(318, 356, 'play.php?dir=Video $mediapath&file=$files[$x]/', 'play');\"; href='#'><img src='dlf/video_tv.png' width='20' height='20' title='$STR_Play_Video'></td>";
				echo "</tr></table>";

				echo "</td><td width='20'>";
				echo "<table  width='20' height='20' align='left' cellspacing='0' cellpadding='0' border='0'> <tr>";
				echo "<td><a onClick=\"newwindow(318, 356, 'play.php?dir=Audio $mediapath&file=$files[$x]/' , 'play');\"; href='#'><img src='dlf/audio_tv.png' width='20' height='20' title='$STR_Play_Audio'></td>";
				echo "</tr></table>";

				echo "</td><td width='20'>";
				echo "<table  width='20' height='20' align='left' cellspacing='0' cellpadding='0' border='0'> <tr>";
				echo "<td><a onClick=\"newwindow(318, 356, 'play.php?dir=Photo $mediapath&file=$files[$x]/', 'play');\"; href='#'><img src='dlf/photo_tv.png' width='20' height='20' title='$STR_Play_Photo'></td>";
				echo "</tr></table>";

				//}

				echo "</td>";
				echo "<td width='370'></td>";
				echo "</tr></table>";
			}
		}
	}
}else{

for ($x=0; $x<($j-1); $x++) {
	if (($files[$x] != '.') and ($files[$x] != "..") and ($files[$x] != "Recycled") and ($files[$x] != "System Volume Information") and (substr($files[$x],0,1) != ".") and ($files[$x] != "lost+found")){
		//echo "<div>";
		//if(is_dir($mydir . "/" . $files[$x])) {
		if (substr($line1[$x],0,1) == 'd'){
			$files1[$x] = $files[$x];
			if ($aaa!= ""){
				$files1[$x] = str_replace("sda", "HDD", $files1[$x]);
				//$files1[$x] = str_replace("sdb1", "USB1", $files1[$x]);
				//$files1[$x] = str_replace("sdc1", "USB2", $files1[$x]);
				//$files1[$x] = str_replace("sdd1", "USB3", $files1[$x]);
				$files1[$x] = str_replace("sdb", "USB", $files1[$x]);
				$files1[$x] = str_replace("sdc", "USB", $files1[$x]);
			}else{
				//$files1[$x] = str_replace("sda1", "USB1", $files1[$x]);
				//$files1[$x] = str_replace("sdb1", "USB2", $files1[$x]);
				//$files1[$x] = str_replace("sdc1", "USB3", $files1[$x]);
				//$files1[$x] = str_replace("sdd1", "USB4", $files1[$x]);
				$files1[$x] = str_replace("sda", "USB", $files1[$x]);
				$files1[$x] = str_replace("sdb", "USB", $files1[$x]);
				$files1[$x] = str_replace("sdc", "USB", $files1[$x]);
				$files1[$x] = str_replace("sdd", "USB", $files1[$x]);
			}

			echo '<table width="846" height="27" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'" style="border-bottom:1px solid #000000;">';
			echo '<tr><td width=400>';

			if (strlen($files[$x]) > 40) {
				echo "<table><tr><td><img src='dlf/folder.png' align='center'>";
				echo "<td width=380><a href=\"" . $_SERVER['PHP_SELF'] . "?dir=" . $mediapath . "/" . $files[$x] . "\"   class='rollover'>" . substr($files1[$x],0,40) . "...</td>";
				echo "</tr></table>";
			}else{
				echo "<table><tr><td><img src='dlf/folder.png' align='center'>";
				echo "<td width=380><a href=\"" . $_SERVER['PHP_SELF'] . "?dir=" . $mediapath . "/" . $files[$x] . "\"   class='rollover'>" . $files1[$x] . "</td>";
				echo "</tr></table>";
			}

			if ($mediapath != ""){
				echo "</td><td width='20'>";
				echo "<table  width='20' height='20' align='left' cellspacing='0' cellpadding='0' border='0'> <tr>";
				echo "<td><a onClick=\"newwindow(318, 356, 'play.php?dir=Video $mediapath&file=$files[$x]/', 'play');\"; href='#'><img src='dlf/video_tv.png' width='20' height='20' title='$STR_Play_Video'></td>";
				echo "</tr></table>";

				echo "</td><td width='20'>";
				echo "<table  width='20' height='20' align='left' cellspacing='0' cellpadding='0' border='0'> <tr>";
				echo "<td><a onClick=\"newwindow(318, 356, 'play.php?dir=Audio $mediapath&file=$files[$x]/' , 'play');\"; href='#'><img src='dlf/audio_tv.png' width='20' height='20' title='$STR_Play_Audio'></td>";
				echo "</tr></table>";

				echo "</td><td width='20'>";
				echo "<table  width='20' height='20' align='left' cellspacing='0' cellpadding='0' border='0'> <tr>";
				echo "<td><a onClick=\"newwindow(318, 356, 'play.php?dir=Photo $mediapath&file=$files[$x]/', 'play');\"; href='#'><img src='dlf/photo_tv.png' width='20' height='20' title='$STR_Play_Photo'></td>";
				echo "</tr></table>";

			}
			echo "</td>";
			echo "<td width='360'></td>";
			
			echo "</tr></table>";
			$line1[$x] = NULL;
        }
	}
}
}

//for NetShares
if (($mediapath == "") and ($Netshare_path != "/tmp/myshare")){
			echo '<table width="846" height="27" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'" style="border-bottom:1px solid #000000;">';
			echo '<tr><td width=400>';


			echo "<table><tr><td><img src='dlf/folder.png' align='center'>";
			echo "<td width=380><a href=\"" . $_SERVER['PHP_SELF'] . "?dir=/tmp/myshare\" class='rollover'>Network Shares</td>";
			//echo "<td width=380><a href=\"" . $_SERVER['PHP_SELF'] . "?dir=" . $mediapath . "/" . $files[$x] . "\"   class='rollover'>Network Shares</td>";

			echo "</tr></table>";

			echo "</td>";
			echo "<td width='370'></td>";
			
			echo "</tr></table>";
}
//for NetShares


for ($x=0; $x<($j-1); $x++) {
	//if (($files[$x] != '.') and ($files[$x] != "..")) {
	//	echo "<div>";
		//if(is_dir($mydir . "/" . $files[$x])) {
		if($line1[$x] == NULL)
			continue;

		if (substr($line1[$x],0,1) != 'd'){		
			if ((strtolower(strrchr($files[$x],'.')) == ".wma")||(strtolower(strrchr($files[$x],'.')) == ".mp3")||(strtolower(strrchr($files[$x],'.')) == ".wav")||(strtolower(strrchr($files[$x],'.')) == ".mp2")||(strtolower(strrchr($files[$x],'.')) == ".aac")||(strtolower(strrchr($files[$x],'.')) == ".ac3") ||(strtolower(strrchr($files[$x],'.')) == ".ogg") ||(strtolower(strrchr($files[$x],'.')) == ".dts") ||(strtolower(strrchr($files[$x],'.')) == ".flac") ||(strtolower(strrchr($files[$x],'.')) == ".m3u")
			||(strtolower(strrchr($files[$x],'.')) == ".m3u8") ||(strtolower(strrchr($files[$x],'.')) == ".pls"))
			{

				if ($Netshare_path == "/tmp/myshare"){
					$file = "http://".$_SERVER["SERVER_NAME"].'/'. "media3" . substr($mediapath,12) .'/'.$files[$x];
				}else{
					$file = "http://".$_SERVER["SERVER_NAME"].'/'. "media" . $mediapath .'/'.$files[$x];
				}
				//$file = "http://".$_SERVER["SERVER_NAME"].'/'. "media" . $mediapath .'/'.$files[$x];
				$file1 = urlencode(str_replace(' ', '%20', $file));

				$ext = strtolower(substr($files[$x], strrpos($files[$x], '.')+1));
				if($filetypes[$ext]) {
					$icon = $filetypes[$ext];
				} else {
					$icon = 'unknown.png';
				}

				echo '<table width="846" height="27" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'" style="border-bottom:1px solid #000000;">';
			    echo '<tr ><td>';

					echo "<table width='390' cellspacing='0' cellpadding='0' border='0'> <tr>";
			 		if (strlen($files[$x]) > 40) {
						echo "<table><tr><td><img src='dlf/audio.png' align='center'>";
						echo "<td width='380'><a href='$file'><font color= 'white' face='Arial' size='2'>" . substr($files[$x],0,40) . "	</td>"; 
						echo "<td>";
						?>

						<object title="<?echo $STR_Copy_To_Clipboard;?>" width="18" height="18">
						<PARAM NAME=FlashVars VALUE=<?echo "txtToCopy=".$file1."&js=alertUser()";?>>
						<param name="movie" value="dlf/copyButton.swf">
						<embed src="dlf/copyButton.swf" flashvars=<?echo "txtToCopy=".$file1."&js=alertUser()";?> width="18" height="18" menu="false" wmode="transparent">
						</embed>
						</object>

						<?
						echo "</td>";
						
					}else {
						echo "<table><tr><td><img src='dlf/audio.png' align='center'></td>";
						echo "<td width='380'><a href='$file'><font color= 'white' face='Arial' size='2'>" . $files[$x] . "</td>";
						echo "<td>";
						?>

						<object title="<?echo $STR_Copy_To_Clipboard;?>" width="18" height="18">
						<PARAM NAME=FlashVars VALUE=<?echo "txtToCopy=".$file1."&js=alertUser()";?>>
						<param name="movie" value="dlf/copyButton.swf">
						<embed src="dlf/copyButton.swf" flashvars=<?echo "txtToCopy=".$file1."&js=alertUser()";?> width="18" height="18" menu="false"  wmode="transparent">
						</embed>
						</object>

						<?
							echo "</td>";
					}
						echo "</tr></table>";
						
						echo "<td>";
						echo "<table  width='20' height='16' align='left' cellspacing='0' cellpadding='0' border='0'> <tr>";
						echo "<td><a onClick=\"newwindow(318, 356, 'play.php?dir=Audio $mediapath&file=$files[$x]', 'play');\"; href='#'><img src='dlf/play.png' width='16' height='16' title='$STR_Play'></td>";
						echo "</tr></table>";
						echo "</td>";

						echo "<td>";
						echo "<table  width='15' height='2' cellspacing='0' cellpadding='0' border='0'> <tr>";
						echo "<td><a onClick=\"newwindow(600, 600, 'gomplay.php?dir=$mediapath&file=$files[$x]', 'gom');\";  href='#'><img  src='dlf/gom.png' width='18' height='17' title='Gom Player'></td>";
						echo "</tr></table>";
						echo "</td>";

						echo "<td>";
						echo "<table  width='15' height='2' align='left' cellspacing='0' cellpadding='0' border='0'> <tr>";
						echo "<td><a onClick=\"newwindow(700, 545, 'vlc.php?dir=$mediapath&file=$files[$x]', 'vlc');\"; class='g' href='#'>&nbsp&nbsp<img  src='dlf/vlc.png' width='15' height='17' title='VLC Player'></td>";
						echo "</tr></table>";
						echo "</td>";

						echo "<td>";
						echo "<table width='35' height='2' cellspacing='0' cellpadding='0' border='0'> <tr>";
						if ((strtolower(strrchr($files[$x],'.')) == ".wma")||(strtolower(strrchr($files[$x],'.')) == ".mp3")||(strtolower(strrchr($files[$x],'.')) == ".wav")||(strtolower(strrchr($files[$x],'.')) == ".mp2") ||(strtolower(strrchr($files[$x],'.')) == ".flac"))
						{
							echo "<td><a onClick=\"newwindow(600, 600, 'winplay.php?dir=$mediapath&file=$files[$x]', 'WMP');\"; href='#'>&nbsp&nbsp<img  src='dlf/winmedia.png' width='16' height='17' title='Windows Media Player'></td>";
						}
						echo "</tr></table>";
						echo "</td>";

			   echo "</a>";


			        echo "<td>";
			        echo "<table width='90' height='2' cellspacing='0' cellpadding='0' border='0' > <tr><td>";

						?>
						<font color= "white" face="Arial" size="2">
						<?
						//$i = 0;
						//while ($i < sizeof($line1)) {
						//	if($line1[$i] == NULL)
						//		continue;

							sscanf($line1[$x],"%s %s %s %s %s %s %s %s", $a,$b, $c, $d, $Size, $mnth, $day, $time);
						//	$Name = substr(strstr($line1[$i],$time),strlen($time)+1);
						//	if ($Name == $files[$x]) {
								echo $Size;

								echo "</td></tr> </table>";
								echo "</td>";

								echo "<td>";
								echo "<table width='170' height='2' cellspacing='0' cellpadding='0' border='0'> <tr><td>";
								echo '<font color= "white" face="Arial" size="2">';
								echo $mnth. ' ' .$day. ' ' .$time;
						//		$line1[$i] = NULL;
						//		break;
						//	}
						//	$i++;
						//}	

					echo "</td></tr> </table>";
					echo "</td>";

					echo "<td>";
					echo '<table width="80" height="3" cellspacing="0" cellpadding="0" border="0">';
					echo '<tr><td>';
					echo "<table cellspacing='0' cellpadding='0' cellspacing='0' cellpadding='0' border='0'><tr><td width='20'>
					<a href=\"download.php?dir=$mediapath&file=$files[$x]\";><img src='dlf/download_icon.png' align='center'>$nsbp$nsbp";
					echo "<td><a href=\"download.php?dir=$mediapath&file=$files[$x]\"; class='g'>Download</td></tr></table>";
					echo "</td></tr></table>";


					echo "</font>";
					echo "</td>";
					echo "</tr>";
					echo "</table>";
				}
			}
    //    echo "</div>";
	//}
}
                 ?>
        </td>
      </tr>
</table>

          </div>
        </div>
    </div>

<center>
<table cellspacing='0' cellpadding='0' border='0' width=996 valign="center">

	<tr height="5"><td></td></tr>
	<tr><td width=70></td><td width=700>
	<?
		$mediapath1 = $mediapath;
		if ($aaa!= ""){
			$mediapath1 = str_replace("sda", "HDD", $mediapath1);
			$mediapath1 = str_replace("sdb1", "USB1", $mediapath1);
			$mediapath1 = str_replace("sdc1", "USB2", $mediapath1);
			$mediapath1 = str_replace("sdd1", "USB3", $mediapath1);
			$mediapath1 = str_replace("sdb", "USB", $mediapath1);
			$mediapath1 = str_replace("sdc", "USB", $mediapath1);
			$mediapath1 = str_replace("/tmp/myshare", "/NetShare", $mediapath1);
		}else{
			$mediapath1 = str_replace("sda1", "USB1", $mediapath1);
			$mediapath1 = str_replace("sdb1", "USB2", $mediapath1);
			$mediapath1 = str_replace("sdc1", "USB3", $mediapath1);
			$mediapath1 = str_replace("sdd1", "USB4", $mediapath1);
			$mediapath1 = str_replace("sdb", "USB", $mediapath1);
			$mediapath1 = str_replace("sdc", "USB", $mediapath1);
			$mediapath1 = str_replace("/tmp/myshare", "/NetShare", $mediapath1);
		}

		if (strlen($mediapath) > 90) {
				echo "<font face='Arial' color='white' size='2'>" . substr($mediapath1,0,90)."...</font>";
		}else{
				echo "<font face='Arial' color='white' size='2'>$mediapath1</font>";
		}
	?>
	</td>
	
	<td>
	<? if (($mediapath != '') && ($aaa!= "") && (substr($mediapath, 0, 4) == "/sda")){ 
			echo '<table cellspacing="0" cellpadding="0" border="0"><tr>';
			echo '<td width=25><font face="Arial" color="White" size="1">HDD</font></td>';
			echo '<td width=50><font face="Arial" color="White" size="1">'. $STR_HDDUsed .'</font></td>';
			echo '<td width=50><font face="Arial" color="White" size="1">'. $STR_HDDFree .'</font></td>';
			echo '<td width=50><font face="Arial" color="White" size="1">'. $STR_HDDTotal .'</font></td>';
			echo '</tr>';
	
			echo '<tr>';
				echo '<td width=25></td>';
				echo '<td ><font face="Arial" color="White" size="1">';
					//$HDDInfo = shell_exec("df -h|grep /dev/scsi/host1/bus0/target0/lun0/part1");
					//sscanf($HDDInfo,"%s %s %s %s", $aaa,$HDDTotal, $HDDUsed, $HDDFree);
					echo "$HDDUsed";
				echo '</td>';

				echo '<td><font face="Arial" color="White" size="1">';
					echo "$HDDFree";
				echo '</td>';
				
				echo '<td><font face="Arial" color="White" size="1">';
					echo "$HDDTotal";
				echo '</td>';
			echo '</tr>';
			echo '</table>';
			}
			?>
			
	</td>


	</tr>
</table>

<iframe name='gframe' width=0 height=0 style="display:none"></iframe>

<table width="700"  border="0" cellspacing="0" cellpadding="0">
  <tr height=4><td></td></tr>	
  <tr>
    <td align="right" valign="top" style="border-top:solid 1px; border-top-color:#FFFFFF"><table width="900" border="0" cellspacing="0" cellpadding="0">
      <tr><td width=20></td>
        <td width=440 valign="middle"><font face="Arial" color="#748e94" size="2"><a href="index.php"><?echo $STR_Home;?></a> | <a href="register_form.php"><?echo $STR_Setup;?></a> 
		| <a href="#" onclick="newwindow(318, 356, 'rc', 'rc_1');";>RC</a> 
		| <a href="#" onclick="newwindow(250, 680, 'rc2', 'rc_2');";>RC2</a> 
		<?if (file_exists("/tmp/usbmounts/sda1/scripts/xJukebox/index.php")){?>
			| <a href="jukebox">Jukebox</a>
		<?}?>
		| <a href="logout.php"><?echo $STR_Logout;?></a></font></td>

		<td align=right>
			<table><tr><!--td align=right><font face="Arial" color="#000000" size="1"><b><?echo date('M, d Y | h:i A');?></td--></tr>
				   <tr><td align=right><font face="Arial" color="#000000" size="1"><b>Copyright ⓒ 2009 Xtreamer.net, All right reserved.</td></tr>
			</table>
		</td>

        <td align=right><img src="dlf/footer.png" width="175" height="51" usemap="#planetmap">
		<map name="planetmap">
		  <area shape="rect" coords="05,100,135,2" href='#' onclick="window.open('http://xtreamer.net/','MyVideo','height=675,width=987,left=100,top=100, toolbar=yes,location=yes,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no');";/>
		</map>
		</td>
      </tr>
    </table>
      </td>
  </tr>
</table>
</center>



</body>
</HTML>
