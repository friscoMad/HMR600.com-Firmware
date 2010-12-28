<?php
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

if ($_GET['dir'] != "")
	$Netshare_path = substr($_GET['dir'],0,12);
else
	$Netshare_path = substr($_GET['zipdir'],0,12);

if ($Netshare_path == "/tmp/myshare"){
	
	if ($_GET['dir'] != ""){
		$mydir = $_GET['dir'];
		$mediapath =  stripslashes($_GET['dir']);
	}else{
		$mydir = $_GET['zipdir'];
		$mediapath =  stripslashes($_GET['zipdir']);
	}
	
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
				echo "<script>location.href='unzip.php?dir=$uplink';</script>";
			}else if($id != ''){
				echo "<script>location.href='unzip.php?dir=$mydir';</script>";
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
	
	if ($_GET["val"]==1){
		$_SESSION['zip_file_name'] = $_GET["zipdir"] .'/'. $_GET["zipfile"];
	}
}else{
	$root = "/tmp/usbmounts";


	if ((substr($_GET['dir'],0,2) != '/.') and (substr($_GET['dir'],0,1) != '.') and ($_GET['dir'] != '')) {
		$mydir = $root . $_GET['dir'];
		//$mydir = $root;
		$mediapath =  $_GET['dir']; 
	}else {
		$mydir = $root .  $_GET['zipdir'];
		$mediapath =  $_GET['zipdir']; 
	}

	if ($_GET["val"]==1){
		$_SESSION['zip_file_name'] = "/tmp/usbmounts" .$_GET["zipdir"] .'/'. $_GET["zipfile"];
		//$zip_file_name = "/tmp/usbmounts" .$_GET["zipdir"] .'/'. $_GET["zipfile"];
	}

	if ($_GET['dir'] != ''){
		$uplink = substr_replace($_GET['dir'],'',strlen($_GET['dir'])-strlen(strrchr( $_GET['dir'],'/')));
	}else{
		$uplink = substr_replace($_GET['zipdir'],'',strlen($_GET['zipdir'])-strlen(strrchr( $_GET['zipdir'],'/')));
	}

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
function makefolder(){
	document.makedir.target = 'gframe';
	document.makedir.action="./unzip_newfolder.php?dir=<?echo $mediapath;?>";
	document.makedir.submit();
}

function zipun(){
	var aaa = '<? echo $mediapath;?>';
	//alert(aaa);
	if (aaa){
		loadDivEl = document.getElementById("loadDiv");
		loadDivEl.style.visibility = 'visible';
		document.filelist.target = 'gframe';
		document.filelist.action = "unzip_file.php?dir=<? echo $mediapath;?>&file=<? echo $_SESSION['zip_file_name'];?>";
		document.filelist.submit();
	}else{
		alert('Please select target directory.');
	}
}
</script>


<HTML>
<head>
<!--meta http-equiv="Content-Type" content="text/html; charset=utf-8" /-->
<title> Target Directory</title>
<link rel="stylesheet" type="text/css" href="dlf/styles.css" />
</head>
<body marginwidth=0 marginheight=0 leftmargin=0 topmargin=0 oncontextmenu="return false;" onunLoad="window.opener.location.reload();">
<table width="100%" height="50" background="dlf/top_menu.jpg" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width=850 height="50" align=middle><font face="arial" color="#ff0000"><h2>Target Directory</h2></font></td>
	</tr>
</table>

<center>
<table cellspacing="0" cellpadding="0" border="0" width=450 height=500>
	<tr><td >
	<?if ($_GET['dir'] != '' || $_GET['zipdir'] != '' and $mydir != "/tmp/myshare"){?>
		<form name="makedir" action='javascript:makefolder();' method="post" enctype="multipart/form-data">
		<table width=455 cellspacing="0" cellpadding="0" border="0">
			
		<tr height=5><td></td></tr>
		<tr><td><font face="Arial" color="white" size="2"><?echo $STR_NewFolderName;?>&nbsp
			<input type="text" name="dir_name" class="textbox" size="35" maxlength="255"> 
			<input type=button class='btn_2' name=create value="<?echo $STR_Create;?>" onclick='javascript:makefolder();';>
		</td></tr>
		<tr height=5><td></td></tr>
		</table>		
		</form>
	<?}?>
	</td><tr>

	<tr><td cellspacing="0" cellpadding="0" border="0" height=500 width=500 valign="top">
	<div id="listingcontainer1">

	<table cellspacing="0" cellpadding="0" border="0">
	<tr height=18><td></td></tr>
	<tr><td width=10></td>

	<td>
	<div id="listing1">

<?
if(($_GET["dir"]!='') || ($_GET["zipdir"]!='')){
	echo '<table width="500" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'"><tr><td>';
	echo "<table><tr><td><img src='dlf/dirup.png' align='center'>";
	echo "<td colspan='200'><a href='" . $_SERVER['PHP_SELF'] . "?dir=" . $uplink ."'>" . $STR_ParentDirectory . "</a></td></tr></table>";
	echo "</td></tr></table>";
}

echo "<form id='filelist' name='filelist' method='post'>";

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
				echo "<table cellspacing='1' cellpadding='0'><tr><td><img src='dlf/folder.png' align='center'>";
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

for ($x=0; $x<sizeof($files); $x++) {
	//if (($files[$x] != '.') and ($files[$x] != "..")) {
	//	if(!is_dir($mydir . "/" . $files[$x])) {
		if ((substr($line1[$x],0,1) != 'd') and  ($mydir != "/tmp/myshare")){
			 if (($files[$x] != "mylist.All") and ($files[$x] != "mylist.Music") and ($files[$x] != "mylist.Picture") and ($files[$x] != "mylist.Video") and ($files[$x] != "keyword.data")){

				 $ext = strtolower(substr($files[$x], strrpos($files[$x], '.')+1));
					if($filetypes[$ext]) {
							$icon = $filetypes[$ext];
					} else {
							$icon = 'unknown.png';
					}

				echo '<table width="500" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'">';
				echo "<tr> <td>";
				echo "<table cellspacing='1' cellpadding='0'><tr>";
				echo "<td colspan='200'><font face='Arial' color='white' size='2'>" . $files[$x] . "</font></td>";
				echo "</tr></table>";
				echo "</td></tr></table>";
			}
		}
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
		<td><input type=button class='btn_2' onclick="javascript:zipun()" class="web-button" value="Unzip"> </td>
		&nbsp<td ><input type=button class='btn_2' onclick="window.close()" class="web-button" value="<?echo $STR_Close;?>"></td>
	</tr>

</table>
</form>
<iframe name='gframe' width=0 height=0 style="display:none"></iframe>
<div id="loadDiv" name="loadDiv" style="position:absolute; visibility:hidden; left:200;top:250;width:200; height:100; z-index:1;">
	<table cellspacing="0" cellpadding="0" border="0" width=100% height=100%>
		<td valign=middle align=center>
			<table borde=0 align=center>
				<td align=center>			
					<img src="dlf/upload.gif">
				</td>
			</table>
		</td>
	</table>
</div>
</body>
</HTML>