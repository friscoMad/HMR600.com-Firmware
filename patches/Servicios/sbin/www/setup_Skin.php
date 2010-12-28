<?
header('Content-Type: text/html; charset=utf-8');
session_start();
error_reporting(0);
$_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
include "chooselang.php";
include '/tmp/lang.php';

$root = "/tmp/usbmounts";  // this will the the root position of this script

//Set our root position and make sure the URL input is not manually manipulated
if ((substr($_GET['dir'],0,2) != '/.') and (substr($_GET['dir'],0,1) != '.') and ($_GET['dir'] != '')) {
	$mydir = $root . $_GET['dir'];
	$mediapath =  $_GET['dir']; }
else {
	$mydir = $root;
}

$uplink = substr_replace($_GET['dir'],'',strlen($_GET['dir'])-strlen(strrchr( $_GET['dir'],'/')));

$files = myscan($mydir);
sort($files);

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

<html>
<head>
<title><?echo $STR_Setup;?></title>
<link href="dlf/styles.css" rel="stylesheet" type="text/css">
</head>
<body marginheight="0" marginwidth="0" leftmargin="0" topmargin="0" bgcolor="#012436" oncontextmenu="return false;">


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

function putvalue(){
	for(i=0; i<document.filelist.length; i++){
		if(document.filelist[i].checked == true){
			document.filelist.new_name.value = document.filelist[i].value;
			document.filelist.old_name.value = document.filelist[i].value;
			break;
		}else{
			document.filelist.new_name.value = "";
		}
	}
}

function skin_change(){
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
		alert("<?echo $STR_No_item_selected;?>");
		return false;
	}
	if(confirm("<?echo $STR_Skin_Change_Confirmation;?>")){
		loadDivEl = document.getElementById("loadDiv");
		loadDivEl.style.visibility = 'visible';
		document.filelist.target = 'gframe';
		document.filelist.action = 'skin_change.php?dir=<? echo $mediapath;?>';
		document.filelist.submit();
	}
}

function newwindow(w,h,webaddress,name){
	var viewimageWin = window.open(webaddress,name,"toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,copyhistory=yes,width="+w+",height="+h);
	viewimageWin.moveTo(screen.availWidth/2-(w/2),screen.availHeight/2-(h/2));
}

function newwindow1(w,h,webaddress,name){
	var viewimageWin = window.open(webaddress,name,"toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width="+w+",height="+h);
	viewimageWin.moveTo(screen.availWidth/2-(w/2),screen.availHeight/2-(h/2));
}
</script>


<center>
<table cellspacing="0" cellpadding="0" border="0" height="500" width="996">

	<tr><td width=300>&nbsp</td>
		<td width=620>&nbsp</td>
        <td height="100" align="right" valign="bottom"><a href="index.php"><Img src="dlf/mvix_logo.png" width="300" height="72"></td>
    </tr>


<tr><td width=350>&nbsp</td>
	<td width=620 valign="top">

	<table width=540 height="100"  cellspacing="0" cellpadding="0" border="0">
	<tr><td height=40></td></tr>
	<tr><td>
		<table cellspacing="0" cellpadding="0" border="0"><tr>
			<td><font face="arial" color="white" size="2">
				<a href="register_form.php"><b><?echo $STR_Login_Head;?> </b></font>
				<font face="arial" color="white" size="2">|&nbsp</td>
			<td><font face="arial" color="white" size="2">
				<a href="setup_ddns.php"><b><?echo $STR_DDNS_Head;?> </b>
				<font face="arial" color="white" size="2">|&nbsp</td>
			<td><font face="arial" color="white" size="2">
				<a href="setup_http.php"><b><?echo $STR_HTTP_Head;?> </b>
				<font face="arial" color="white" size="2">|&nbsp</td>
			<td><font face="arial" color="white" size="2">
				<a href="setup_ftp.php"><b><?echo $STR_FTP_Head;?> </b>
				<font face="arial" color="white" size="2">|&nbsp</td>
			<td><font face="arial" color="white" size="2">
				<a href="setup_live_keyword.php"><b><?echo $STR_LiveKeyword_Head;?> </b>
				<font face="arial" color="white" size="2">|&nbsp</td>
			<td><font face="arial" color="white" size="2">
				<a href="setup_backup.php"><b><?echo $STR_Backup_Head;?> </b>
				<font face="arial" color="white" size="2">|&nbsp</td>
		</tr></table>
	</td></tr>
	
	<tr><td>
		<table cellspacing="0" cellpadding="0" border="0"><tr>
			<td width="110"></td>
			<td><font face="arial" color="white" size="2">
				<a href="setup_language.php"><b><?echo $STR_Language_Head;?> </b>
				<font face="arial" color="white" size="2">|&nbsp</td>
			<td><font face="arial" color="white" size="2">
				<a href="setup_upnp_boost.php"><b><?echo $STR_NAS_Mode;?></b>
				<font face="arial" color="white" size="2">|&nbsp</td>
			<!--td><font face="arial" color="white" size="2">
				<a href="setup_time.php"><b>Time Server</b>
				<font face="arial" color="white" size="2">|&nbsp</td-->
			<td><font face="arial" color="white" size="2">
				<a href="setup_nfs.php"><b><?echo $STR_NFS_Client;?></b>
				<font face="arial" color="white" size="2">|&nbsp</td>
			<td><font face="arial" color="white" size="2">
				<a href="setup_Host_workgroup.php"><b>Workgroup/Hostname</b>
				<font face="arial" color="white" size="2">|&nbsp</td>
			<td><a href="setup_Skin.php">
				<font face="arial" color="#ff0000" size="2"><b><u><?echo $STR_Skin_Head;?></u> </b></font></td>
		</tr></table>
	</td></tr>
	</table>
	
	
	

	<table cellspacing="0" cellpadding="0" border="0">
	<tr><td height=50 width=100></td>
	<td><font face="Arial" color="#748e94" size="2"><?echo $STR_Skin_Note;?></td></tr>
	
	<tr><td width=100></td>
		<td>
			
			<?
			echo "<div style='width: 500; height: 200; overflow: auto; border: 1px solid #ff0000; background: transparent;'>";

			if(!$_GET["dir"]==''){
				echo '<table width="480" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'"><tr><td>';
				echo "<table cellspacing='1' cellpadding='0'><tr><td><img src='dlf/dirup.png' align='center'>";
				echo "<td colspan='200'><font face='Arial' color='white' size='2'><a href='" . $_SERVER['PHP_SELF'] . "?dir=" . $uplink ."'>" . $STR_ParentDirectory . "</a></td></tr></table>";
				echo "</td></tr></table>";
				echo "<input type='checkbox' name='selectall' onclick='checkedAll(filelist);'><font face='Arial' color='white' size='2'>" . $STR_SelectAll;

			}

			
			echo "<form id='filelist' name='filelist' method='post' action='javascript:change();'>";

			for ($x=0; $x<sizeof($files); $x++) {
				if (($files[$x] != '.') and ($files[$x] != "..") and ($files[$x] != "Recycled") and ($files[$x] != "System Volume Information") and (substr($files[$x],0,1) != ".") and ($files[$x] != "lost+found")) {
					if(is_dir($mydir . "/" . $files[$x])) {

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

					echo '<table width="480" height="3" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'">';
						echo "<tr> <td>";
						echo "<table cellspacing='1' cellpadding='0'><tr>";
						echo "<td><img src='dlf/folder.png' align='center'>";
						echo "<td colspan='200'><font face='Arial' color='white' size='2'><a href=\"" . $_SERVER['PHP_SELF'] . "?dir=" . $mediapath . "/" . $files[$x] . "\">" . $files1[$x] . "</td>";
						echo "</tr></table>";
						echo "</td></tr></table>";
					}
				}
			}
	
			
			for ($x=0; $x<sizeof($files); $x++) {
				if (($files[$x] != '.') and ($files[$x] != "..")) {
					if(!is_dir($mydir . "/" . $files[$x])) {
						 if (strtolower(strrchr($files[$x],'.')) == ".bmp"){
							echo '<table width="480" cellspacing="0" cellpadding="0" border="0" onMouseOver="this.style.backgroundImage= \'url(dlf/rollover_bar.png)\'" onMouseOut="this.style.backgroundImage=\'none\'">';
							echo "<tr> <td>";

							echo "<table cellspacing='1' cellpadding='0'><tr><td><input type='checkbox' name='filelist[]' value=\"$files[$x]\">";
							echo "<td colspan='200'><font face='Arial' color='white' size='2'>" . $files[$x] . "</font></td>";
							echo "</tr></table>";
							echo "</td></tr></table>";
						}
					}
				}
			}
				echo "</div>";
			?>	
				
		
		</td>
	</tr>
	<tr><tr height=3></tr>
	<td></td><td>
		<table border=0 width=500><tr><td>
				<font face="Arial" color="#748e94" size="2">
				<a href="#" onclick="newwindow(900, 700, 'http://forum.xtreamer.net/mediawiki-1.15.1/index.php/Skins', 'skin');">
				<?echo $STR_View_All_Skin;?></a></td>
			<td rowspan=2 align=right><input type="button" class='btn_2' name="apply" value="<?echo $STR_Apply;?>" onClick="javascript:skin_change();"> 
			</td></tr>
			<tr><td><font face="Arial" color="#748e94" size="2">
				<a href="#" onclick="newwindow(900, 700, 'http://forum.xtreamer.net/mediawiki-1.15.1/index.php/Skin_modding_guide', 'skinguid');">
				<?echo $STR_Skin_Wiki;?></a>
			</td></tr>
		</table>
		
	</td></tr>
	</table>

	</td>

	<td width="337" align="right" valign="middle"><img src="dlf/pvr_img.png" width="337" height="250"></td>
</tr>
</table>

<iframe name='gframe' width=0 height=0 style="display:none"></iframe>
<div id="loadDiv" name="loadDiv" style="position:absolute; visibility:hidden; left:340;top:190;width:200; height:100; z-index:1;">
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

<table width="700"  border="0" cellspacing="0" cellpadding="0">
  <tr height=4><td></td></tr>	
  <tr>
    <td align="right" valign="top" style="border-top:solid 1px; border-top-color:#FFFFFF"><table width="900" border="0" cellspacing="0" cellpadding="0">
      <tr><td width=20></td>
        <td width=440 valign="middle"><font face="Arial" color="#748e94" size="2"><a href="index.php"><?echo $STR_Home;?></a> | <a href="register_form.php"><?echo $STR_Setup;?></a> 
		| <a href="#" onclick="newwindow1(318, 356, 'rc', 'rc_1');";>RC</a> 
		| <a href="#" onclick="newwindow1(250, 680, 'rc2', 'rc_2');";>RC2</a> 
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
</html>
