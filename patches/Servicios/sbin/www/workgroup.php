<?php
header('Content-Type: text/html; charset=utf-8');
error_reporting(0);
include '/tmp/lang.php';

$workInput = $_POST['workgroup'];

$firstchar = substr($workInput, 0, 1);
//echo "<script>alert('$hh');</script>";

//if (ctype_alnum($workInput)){
//	if (!is_numeric($firstchar)){
		//exec('hostname '.$hostInput);
		$workInput = "workgroup=".$workInput;
		$filename = "/usr/local/etc/workgroup";
		$fp = fopen($filename, 'w');
		fwrite($fp, $workInput);
		fclose($fp);

		exec('/usr/local/daemon/script/samba restart');
		
		echo "<script>alert('Workgroup changed successfully.');</script>";
		//echo "<script>parent.window.location.reload();</script>";
		echo "<script>parent.window.location.href='setup_Host_workgroup.php';</script>";
//	}else{
//		echo "<script>alert('First character should not be a number!');</script>";
//		echo "<script>loadDivEl = parent.document.getElementById('loadDiv');
//					loadDivEl.style.visibility = 'hidden';</script>";
//		echo "<script>parent.document.ddns.workgroup.focus();</script>";
//	}

//}else{
//		echo "<script>alert('Please insert only alphanumeric characters!');</script>";
//		echo "<script>loadDivEl = parent.document.getElementById('loadDiv');
//					loadDivEl.style.visibility = 'hidden';</script>";		
//		echo "<script>parent.document.ddns.workgroup.focus();</script>";
//}

?>