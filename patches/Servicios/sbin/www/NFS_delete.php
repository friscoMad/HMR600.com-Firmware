<?
header('Content-Type: text/html; charset=utf-8');
error_reporting(0);
include '/tmp/lang.php';
$data = $_POST['mylist'];
$dir = $_GET['dir'];

$filename = "/usr/local/etc/nfs";
if (file_exists($filename)) {
	$fp = fopen($filename, 'r');
	if (filesize($filename)>0){
		$fileData = fread($fp, filesize($filename));
	}
	fclose($fp);
}

$line = explode("\n", $fileData);

$countdata = count($data);	
$countline = count($line);

//remove related line from share.list 
$file = "/tmp/myshare/share.list";
if (file_exists($file)) {
	$tmpfile = fopen($file, 'r');
	if (filesize($file)>0){
		$tmpfileData = fread($tmpfile, filesize($file));
	}
	fclose($tmpfile);
}

$tmpline = explode("\n", $tmpfileData);
$counttmpline = count($tmpline);

	//echo "<script>alert('lines...: $countline');</script>";
	
	for ($i=0; $i<$countdata; $i++){
		$data[$i] = stripslashes($data[$i]);
		for ($j=0; $j<$countline; $j++){
			$line1 = explode("#", $line[$j]);


			if ($data[$i] == $line1[1]){
				$dataset = explode("->", $line1[1]);
								
				$l_mount = str_replace("/NetShare/", "", $dataset[0]);
				$r_mount = explode(":", $dataset[1]);

				//echo "<script>alert('$l_mount');</script>";
				//echo "<script>alert('$r_mount[0]');</script>";

				$command = "umount -f '/tmp/myshare/".$l_mount."(".$r_mount[0].").NFS'";
				//echo "<script>alert('$command');</script>";
				exec($command,$output,$result);

				
				$delfile = "'/usr/local/etc/NetShareSave/".$l_mount."(".$r_mount[0].").NFS'";
				//echo "<script>alert('$delfile');</script>";
				exec("rm ".$delfile);
				
				//$name = "11/tmp/myshare/".$l_mount."(".$r_mount[0].").NFS/";
				$name = $l_mount."(".$r_mount[0].").NFS";

				//$cmd = "sed '/".$name."/d' /usr/local/etc/.myshortcut > /tmp/tmpshortcut";
				//exec($cmd);
				//copy('/tmp/tmpshortcut', '/usr/local/etc/.myshortcut');

			
				for ($k=$counttmpline; $k>0; $k--){
					if ($name == $tmpline[$k]){
						unset($tmpline[$k]);
						break;
					}
				}

				//remove line from nfs file
				unset($line[$j]);
			}
		}
	}


	$nfsfile = fopen($filename, "w");
	for ($j=0; $j<$countline; $j++){
		if ($line[$j] != "")
	  	   fwrite($nfsfile, $line[$j]."\n");
	}
		   fclose($nfsfile);

	$tmpfile = fopen($file, "w");
	for ($k=0; $k<$counttmpline; $k++){
		if ($tmpline[$k] != "")
			fwrite($tmpfile, $tmpline[$k]."\n");
	}
		   fclose($tmpfile);
	

?>
<script language=javascript>
	parent.document.location.href = 'setup_nfs.php';
</script>