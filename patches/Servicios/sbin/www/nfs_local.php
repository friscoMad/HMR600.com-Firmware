<?
	session_start();
	error_reporting(0);

	include '/tmp/lang.php';

	$l_mount = $_POST['lmount'];
	$r_mount = $_POST['rmount'];
	$m_option = $_POST['opt'];

	$l_mount = str_replace(" ","\ ", $l_mount);
	$r_mount = str_replace(" ","\ ", $r_mount);

	//echo "<script>alert('$l_mount');</script>";
	//echo "<script>alert('$r_mount');</script>";
	//echo "<script>alert('$m_option');</script>";

	//if (substr($l_mount, 0, 13) == "/tmp/nfsmount"){
	//	$l_mount1 = str_replace("/tmp/nfsmount", "", $l_mount);
	//}else{
	//	$l_mount1 = $l_mount;
	//}

/*	$data = $l_mount."->".$r_mount." -o ".$m_option;
	
	$command = "mount -t nfs ".$r_mount ." ".$l_mount." -o nolock,".$m_option;
	echo "<script>alert('$command');</script>";
	
	$filename = "/usr/local/etc/nfs";
	$fp = fopen($filename, 'r');
	$fileData = fread($fp, filesize($filename));
	fclose($fp);

	$line = explode("\n", $fileData);
	$count = count($line);
	
	$count1 = 0;
	for ($i=0; $i<$count; $i++)
	{
		if ($line[$i] != "")
			$count1 += 1;
	}

	//echo "<script>alert('$count1');</script>";

	if($count1 <10){
		exec("$command",$result,$output);
		if($output == 0){
			$fp = fopen($filename, 'a');
			fwrite($fp,$data."\n");
			fclose($fp);

			$_SESSION['NFS'] = "";
			$_SESSION['NFS_show'] = "";
			echo "<script>parent.window.location.href='setup_nfs.php'; </script>";
		}else{
			echo "<script>alert('$STR_mount_failed');</script>";
			echo "<script>loadDivEl = parent.document.getElementById('loadDiv');
					loadDivEl.style.visibility = 'hidden';</script>";
		}
	}else{
		echo "<script>alert('$STR_mount_Max');</script>";
		echo "<script>loadDivEl = parent.document.getElementById('loadDiv');
					loadDivEl.style.visibility = 'hidden';</script>";
	}
	*/

	$ip = explode(":", $r_mount);
	//$data = "11/tmp/myshare/".$l_mount."(".$ip[0].").NFS/";
	$data = $l_mount."(".$ip[0].").NFS";
	//echo "<script>alert('$data');</script>";

	$command = "mkdir -p '/tmp/myshare/".$l_mount."(".$ip[0].").NFS'\nmount -t nfs '".$r_mount ."' '/tmp/myshare/".$l_mount."(".$ip[0].").NFS' -o nolock,".$m_option;

	exec("$command",$result,$output);
	if($output == 0){
		//$shortcutfile = "/usr/local/etc/.myshortcut";
		$shortcutfile = "/tmp/myshare/share.list";
		$fp = fopen($shortcutfile, 'a');
		if ($fp != NULL){
			//echo "<script>alert('file opened succ');</script>";
			fwrite($fp,$data."\n");
			fclose($fp);
		}

		$filename = "/usr/local/etc/NetShareSave/".$l_mount."(".$ip[0].").NFS";
		$filedata = "#/NetShare/".$l_mount."->".$r_mount." -o ".$m_option."\nmkdir -p '/tmp/myshare/".$l_mount."(".$ip[0].").NFS'\nmount -t nfs '".$r_mount ."' '/tmp/myshare/".$l_mount."(".$ip[0].").NFS' -o nolock,".$m_option;

		//echo "<script>alert('$filename');</script>";
		$fp1 = fopen($filename, 'w');
		if ($fp1 != NULL){
			fwrite($fp1,$filedata);
			fclose($fp1);
		}
		chmod($filename, 0777);

		//echo "<script>alert('Finish');</script>";
		echo "<script>parent.window.location.href='setup_nfs.php'; </script>";
	}else{
			echo "<script>alert('$STR_mount_failed');</script>";
			echo "<script>loadDivEl = parent.document.getElementById('loadDiv');
					loadDivEl.style.visibility = 'hidden';</script>";
	}

	
	//echo "<script>alert('Finish');</script>";
	//echo "<script>parent.window.location.href='setup_nfs.php'; </script>";
?>
