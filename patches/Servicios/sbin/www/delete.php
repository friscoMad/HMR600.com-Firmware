<?php
//This file will allow to create Play List (mylist.All).
header('Content-Type: text/html; charset=utf-8');
session_start();
error_reporting(0);
$_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
include '/tmp/lang.php';
//if login option is true check login status
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
                       header("Location:login_form.php");
                        exit;
                }
        }
        $i++;
}

$id = $_POST['user_id'];
$pass = $_POST['user_pass'];

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
				//echo "<script>alert('Can not mount $share');</script>";
				//echo "<script>alert('mydirectory.....$mydir');</script>";
				echo "<script>window.open('smblogin.php?dir=$mydir', 'All', 'width=350,height=150');</script>";
				echo "<script>location.href='delete.php?dir=$uplink';</script>";
			}else if($id != ''){
				echo "<script>location.href='delete.php?dir=$mydir';</script>";
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

//to deetct internal HDD exists or not
$HDDInfo = shell_exec("df -h|grep /dev/scsi/host0/bus0/target0/lun0/part1");
sscanf($HDDInfo,"%s %s %s %s", $aaa,$HDDTotal, $HDDUsed, $HDDFree);
?>

<script language=javascript>
checked=false;
function checkedAll(filelist) {
	var aa= document.getElementById('filelist');
	 if (checked == false)
          {
			checked = true
          }else{
	        checked = false
          }
	for (var i =0; i < aa.elements.length; i++){
		 aa.elements[i].checked = checked;
	}
}

function change(){
	flg = 0;
	for(i=0;i<document.filelist.length;i++){
		if(document.filelist[i].checked == true){
			flg = 1;
			break;
		}else{
			flg = 0;
		}
	}

	if(flg == 0){
		alert("<?echo $STR_NoFileToDel;?>");
		return false;
	}

	var ok = confirm('<?echo $STR_ReallyDelete;?>');
	if(ok){
		document.filelist.target = 'gframe';
		document.filelist.action = 'delete_file.php?dir=<? echo $mediapath;?>';
//		document.filelist.action = window.open('delete_file.php?dir=<? echo $mediapath;?>','Playlist','height=300,width=987,left=150,top=200');
		document.filelist.submit();
	}
}
//window.open('m3uMusic.php','Playlist','height=675,width=987,left=150,top=200');\";>";
</script>


<HTML>
<head>
    <!--meta http-equiv="Content-Type" content="text/html; charset=utf-8" /-->
    <title> <?echo $STR_DeleteTitle;?></title>
	<link rel="stylesheet" type="text/css" href="dlf/styles.css" />
</head>
<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 oncontextmenu="return false;" onunLoad="window.opener.location.reload();">
	<table width="100%" height="50" background="dlf/top_menu.jpg" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td width=850 height="50" align=middle><font face="arial" color="#ff0000"><h2><?echo $STR_DeleteTitle;?></h2></font></td>
	    </tr>
	</table>

<center>
<table cellspacing="0" cellpadding="0" border="0" width=450 height=500>
	<tr><td height="10"></td><tr>
	<tr><td cellspacing="0" cellpadding="0" border="0" height=500 width=500 valign="top">
	<div id="listingcontainer1">

	<table cellspacing="0" cellpadding="0" border="0">
	<tr height=18><td></td></tr>
	<tr><td width=10></td>

	<td>
	<div id="listing1">

<?
//echo "<div style='width: 850; height: 520; overflow: scroll; border: 1px solid #A7C5FF; background: transparent ;'>";
if(!$_GET["dir"]==''){
	echo '<table width="500" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'"><tr><td>';
	echo "<table><tr><td><img src='dlf/dirup.png' align='center'>";
	echo "<td colspan='200'><a href='" . $_SERVER['PHP_SELF'] . "?dir=" . $uplink ."'>" . $STR_ParentDirectory . "</a></td></tr></table>";
	echo "</td></tr></table>";
}

echo "<form id='filelist' name='filelist' method='post' action='javascript:change();'>";
if (($mediapath != '')and ($mydir != "/tmp/myshare")){
//	echo "&nbsp";
	echo "<input type='checkbox' name='selectall' onclick='checkedAll(filelist);'><font face='Arial' color='white' size='2'>" . $STR_SelectAll;
}


if ($Netshare_path == "/tmp/myshare"){
	for ($x=0; $x<($j-1); $x++) {
		if ($mydir == "/tmp/myshare"){
			echo '<table width="500" height="3" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'">';
			echo '<tr><td>';
			echo "<table><tr><td><img src='dlf/folder.png' align='center'>";
			echo "<td width=380><a href=\"" . $_SERVER['PHP_SELF'] . "?dir=" . $mediapath . "/" . $files[$x] . "\"   class='rollover'>" . $files[$x] . "</td>";
			echo "</tr></table>";
			echo "</td></tr></table>";
		}else if(substr($line1[$x],0,1) == 'd'){
			if (($files[$x] != '.') and ($files[$x] != "..") and ($files[$x] != "Recycled") and ($files[$x] != "System Volume Information") and (substr($files[$x],0,1) != ".") and ($files[$x] != "lost+found")){
				
				echo '<table width="500" height="3" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'">';
				echo "<tr> <td>";
				echo "<table cellspacing='1' cellpadding='0'><tr><td><input type='checkbox' name='filelist[]' value=\"$files[$x]\">";
				echo "<td><img src='dlf/folder.png' align='center'>";
				echo "<td width=380><a href=\"" . $_SERVER['PHP_SELF'] . "?dir=" . $mediapath . "/" . $files[$x] . "\"   class='rollover'>" . $files[$x] . "</td>";
				echo "</tr></table>";

				echo "</td>";
				echo "<td width='370'></td>";
				echo "</tr></table>";
			}
		}
	}
}else{

	for ($x=0; $x<($j-1); $x++) {
		if (($files[$x] != '.') and ($files[$x] != "..") and ($files[$x] != "Recycled") and ($files[$x] != "System Volume Information") and (substr($files[$x],0,1) != ".") and ($files[$x] != "lost+found")) {
			if (substr($line1[$x],0,1) == 'd'){
			$files1[$x] = $files[$x];
			if ($aaa!= ""){
				$files1[$x] = str_replace("sda", "HDD", $files1[$x]);
				$files1[$x] = str_replace("sdb1", "USB1", $files1[$x]);
				$files1[$x] = str_replace("sdc1", "USB2", $files1[$x]);
				$files1[$x] = str_replace("sdd1", "USB3", $files1[$x]);
				$files1[$x] = str_replace("sdb", "USB", $files1[$x]);
				$files1[$x] = str_replace("sdc", "USB", $files1[$x]);
			}else{
				$files1[$x] = str_replace("sda1", "USB1", $files1[$x]);
				$files1[$x] = str_replace("sdb1", "USB2", $files1[$x]);
				$files1[$x] = str_replace("sdc1", "USB3", $files1[$x]);
				$files1[$x] = str_replace("sdd1", "USB4", $files1[$x]);
				$files1[$x] = str_replace("sdb", "USB", $files1[$x]);
				$files1[$x] = str_replace("sdc", "USB", $files1[$x]);
			}

			echo '<table width="500" height="3" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'">';
			echo "<tr> <td>";
			echo "<table cellspacing='1' cellpadding='0'><tr>";
			if ($mediapath != "")
			echo "<td><input type='checkbox' name='filelist[]' value=\"$files[$x]\">";
			echo "<td><img src='dlf/folder.png' align='center'>";
			echo "<td colspan='200'><font face='Arial'><a href='" . $_SERVER['PHP_SELF'] . "?dir=" . $mediapath . "/" . $files[$x] . "'>" . $files1[$x] . "</td>";
			echo "</tr></table>";
			echo "</td></tr></table>";
			}
		}
	}
}

//for NetShares
if (($mediapath == "") and ($Netshare_path != "/tmp/myshare")){
	echo '<table width="500" height="3" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'">';
	echo '<tr><td>';
	echo "<table cellspacing='1' cellpadding='0'><tr><td><img src='dlf/folder.png' align='center'>";
	echo "<td width=380><a href=\"" . $_SERVER['PHP_SELF'] . "?dir=/tmp/myshare\" class='rollover'>Network Shares</td>";
	
	echo "</tr></table>";

	echo "</td>";
	echo "<td width='370'></td>";

	echo "</tr></table>";
}
//for NetShares

for ($x=0; $x<($j-1); $x++) {
	//if (($files[$x] != '.') and ($files[$x] != "..")) {
		//echo "<div>";
		//if(!is_dir($mydir . "/" . $files[$x])) {
		if ((substr($line1[$x],0,1) != 'd') and  ($mydir != "/tmp/myshare")){
			 if (($files[$x] != "mylist.All") and ($files[$x] != "mylist.Music") and ($files[$x] != "mylist.Picture") and ($files[$x] != "mylist.Video") and ($files[$x] != "keyword.data")){

				echo '<table width="500" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'">';
				echo "<tr> <td>";
				echo "<table cellspacing='1' cellpadding='0'><tr><td><input type='checkbox' name='filelist[]' value=\"$files[$x]\">";
				echo "<td colspan='200'><font face='Arial' color='white' size='2'>" . $files[$x] . "</font></td>";
				echo "</tr></table>";
				echo "</td></tr></table>";
			}
		}
		//echo "</div>";
	//}

}
	echo "</div>";
?>

			</td></tr></table>
        </td>

     </tr>
</table>

	
<table width="450" border="0" cellspacing="0" cellpadding="0">
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
	
	if (strlen($mediapath) > 40){
		$currentpath = "..." . substr($mediapath1, -36);
	}else{
		$currentpath = $mediapath1;
	}
?>

	<tr><td width=340>&nbsp <font color=white face='Arial' size='2'><?echo $currentpath; ?>/</td>
		<td><input type=button class='btn_2' onclick="javascript:change()" class="web-button" value="<?echo $STR_Delete;?>"> </td>
		&nbsp<td ><input type=button class='btn_2' onclick="window.close()" class="web-button" value="<?echo $STR_Close;?>"></td>
	</tr>

</table>
</form>
<iframe name='gframe' width=0 height=0 style="display:none"></iframe>
</body>
</HTML>