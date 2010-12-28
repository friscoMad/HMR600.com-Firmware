<?
header('Content-Type: text/html; charset=utf-8');
session_start();
error_reporting(0);
$_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
include "chooselang.php";
include '/tmp/lang.php';

//read ddns and port...
$filename = "/usr/local/etc/setup.php";
$fp = fopen($filename, 'r');
$fileData = fread($fp, filesize($filename));
fclose($fp);

$line = explode("\n", $fileData);
$i = 0;
while ($i <= 5) {
	   $dataPair = explode('=', $line[$i]);
	   if ($dataPair[0] == DDNS){
			$data = explode('.', $dataPair[1]);
			$ddns = $data[0];
		}else if ($dataPair[0] == Port) {
			$port = $dataPair[1];
		}
	$i++;
}

//read hostname...
//$file = fopen("/etc/hostname", 'r');
//$hostname = fread($file, filesize("/etc/hostname"));
//fclose($file);
$hostname = trim(file_get_contents('/etc/hostname'));

//read workgroup...
$file = fopen("/usr/local/etc/workgroup", 'r');
$fileData = fread($file, filesize("/usr/local/etc/workgroup"));
fclose($file);
$line = explode("=", $fileData);
$workgroup = $line[1];
?>

<html>
<head>
<title><?echo $STR_Setup;?></title>
<link href="dlf/styles.css" rel="stylesheet" type="text/css">
</head>
<body marginheight="0" marginwidth="0" leftmargin="0" topmargin="0" bgcolor="#012436" oncontextmenu="return false;" onload="document.forms.workgroup.workgroup.focus()">

<script language="javascript">

function change_workgroup(){
	if(!document.workgroup.workgroup.value){
		alert('Please specify Workgroup!');
		document.workgroup.workgroup.focus();
	}else if(confirm('Do you want to change Workgroup?')){
		loadDivEl = document.getElementById("loadDiv");
		loadDivEl.style.visibility = 'visible';
		document.workgroup.target = 'gframe';
		document.workgroup.action = 'workgroup.php';
		document.workgroup.submit();
	}
}

function change_hostname(){
	if(!document.workgroup.host.value){
		alert('Please specify Hostname!');
		document.workgroup.host.focus();
	}else if(confirm('Do you want to change Hostname?')){
		loadDivEl = document.getElementById("loadDiv");
		loadDivEl.style.visibility = 'visible';
		document.workgroup.target = 'gframe';
		document.workgroup.action = 'hostname.php';
		document.workgroup.submit();
	}
}

function newwindow(w,h,webaddress,name){
	var viewimageWin = window.open(webaddress,name,"toolbar=yes,location=yes,directories=yes,status=yes,menubar=yes,scrollbars=yes,resizable=yes,copyhistory=yes,width="+w+",height="+h);
	viewimageWin.moveTo(screen.availWidth/2-(w/2),screen.availHeight/2-(h/2));
}

function newwindow1(w,h,webaddress){
	var viewimageWin = window.open(webaddress,"New_Window","toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width="+w+",height="+h);
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
				<a href="register_form.php"><b><?echo $STR_Login_Head;?> </b>
				<font face="arial" color="white" size="2">|&nbsp</td>
			<td><font face="arial" color="white" size="2">
				<a href="setup_ddns.php"><b><?echo $STR_DDNS_Head;?> </b></font>
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
			<td><a href="setup_Host_workgroup.php">
				<font face="arial" color="#ff0000" size="2"><b><u>Workgroup/Hostname</u></b></font>
				<font face="arial" color="white" size="2">|&nbsp</td>
			<td><font face="arial" color="white" size="2">
				<a href="setup_Skin.php"><b><?echo $STR_Skin_Head;?></b></td>
		</tr></table>
	</td></tr>	
	</table>

	<table cellspacing="0" cellpadding="0" border="0">
	<tr><td height=100 width=50></td><td></td></tr>

	<tr><td width=50></td>
		<td>
		<form name="workgroup" method="post">
           <table border="0" >
              <tr><td ><font face="Arial" color="white" size="2">Workgroup</td>
                  <td><input type="text" name="workgroup" class="textbox" size="35" value="<?echo $workgroup;?>">
					  <input type="button" class='btn_2' name="add" value="Change" onClick="javascript:change_workgroup();">
				  </td>
			  </tr>

			  <tr><td ><font face="Arial" color="white" size="2">Hostname:</td>
                  <td><input type="text" name="host" class="textbox" size="35" maxlength="12" value="<?echo $hostname;?>">
					  <input type="button" class='btn_2' name="change" value="Change" onClick="javascript:change_hostname();">
				  </td>
			  </tr>

			  <tr><td ></td>
                  <td><font face="Arial" color="white" size="2"><a href=# onclick="newwindow(987, 675, 'http://<?echo $hostname.":".$port;?>');";><?echo 'http://'.$hostname.":".$port;?></a></td>
			  </tr>
			  <tr><td ></td>
				  <td><font face="Arial" color="white" size="2"><?echo '\\'.'\\'.$hostname;?></td>
			  </tr>
           </table>
        </form>
		</td>
	</tr>
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

<center>
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
