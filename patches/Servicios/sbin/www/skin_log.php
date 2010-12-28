<?
header('Content-Type: text/html; charset=utf-8');
error_reporting(0);
include '/tmp/lang.php';
$filename = "/tmp/skin.txt";
if (file_exists($filename)) {
    $fp = fopen($filename, 'r');
    if (filesize($filename)>0){
	$fileData = fread($fp, filesize($filename));
    }
    fclose($fp);
}
?>

<html>
<title></title>
<link href="dlf/styles.css" rel="stylesheet" type="text/css">
<body oncontextmenu="return false;" valign="middle">

		  <center>
          <table valign="middle" border="0" width="500">
              <tr><td>
				<textarea cols="60" rows="22" name="url" id="edit-comment" readonly="readonly"><?echo $fileData;?></textarea>
			  </td></tr>
              <tr>  
				<td align='center'>
				<input type="button" name="" class='btn_2' onClick="javascript:window.close()" value="<?echo $STR_Close;?>"></td>
              </tr>
          </table>
		  </center>

</body>
</html>
