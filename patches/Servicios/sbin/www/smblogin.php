<?
header('Content-Type: text/html; charset=utf-8');
session_start();
error_reporting(0);
$_SESSION['redirect'] = $_SERVER['REQUEST_URI'];
include "chooselang.php";
include '/tmp/lang.php';
$dir = $_GET['dir'];
//parent address
$aaa = $_SERVER['HTTP_REFERER'];
//echo "<script>alert('$aaa');</script>";
?>

<html>
<head>
<title>NetShare Login</title>
<link href="dlf/styles.css" rel="stylesheet" type="text/css">
</head>
<body marginheight="0" marginwidth="0" leftmargin="0" topmargin="0" bgcolor="#012436" oncontextmenu="return false;" onload="document.forms.smb_login.user_id.focus()">

<script language=javascript>

function log_in(){
		document.smb_login.target = 'gframe';
		//document.smb_login.action = 'otherlist.php?dir=<?echo $dir;?>';
		document.smb_login.action = '<?echo $aaa;?>';
		document.smb_login.submit();
		window.opener.location.href='<?echo $aaa;?>';
		window.close();
}
</script>

<center>
<form name="smb_login" method="post" action='javascript:log_in();'>
	<table cellspacing="1" cellpadding="0" border="0">
		<tr><td height="45"></td></tr>
		<tr><td width="140"><font face="Arial" color="white" size="2"><?echo $STR_Username;?></td>
		<td><input type="text" name="user_id" class="textbox" size="20" maxlength="16" ><br></td></tr>

		<tr><td width="140"><font face="Arial" color="white" size="2"><?echo $STR_Password;?></td>
		<td><input type="password" name="user_pass" class="textbox" size="20" maxlength="16" ><br></td></tr>
		  
		<tr><td height="5"></td></tr>
		<tr><td></td><td><input type=button class='btn_2' onclick="javascript:log_in()" value="<?echo $STR_Apply;?>"></td></tr>

	</table>
</form>

<iframe name='gframe' width=0 height=0 style="display:none"></iframe>

</center>

</body>
</html>
