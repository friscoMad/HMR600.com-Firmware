<?
	if(file_exists("/tmp/usbmounts/sda1/scripts/xJukebox/index.php")){
		$destination = 'Location: http://'.$_ENV["HTTP_HOST"].'/media/sda1/scripts/xJukebox/index.php?get=webinterface';
		header($destination);
	}else if(file_exists("/tmp/usbmounts/sda/scripts/xJukebox/index.php")){
		$destination = 'Location: http://'.$_ENV["HTTP_HOST"].'/media/sda/scripts/xJukebox/index.php?get=webinterface';
		header($destination);
	}
?>
<table cellspacing="0" cellpadding="0" border="0" align="center" height="100%" width="100%">
	<tr>
		<!--td align="center"><img src="jbx.JPG" width="100%" height="100%"></td-->
		<td align="center">JUKEBOX IS NOT INSTALLED.</td>
	</tr>
<table>