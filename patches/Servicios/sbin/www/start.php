<? 
error_reporting(0);
	//sleep(10);
	exec("echo -n O > /tmp/ir");
	sleep(1);
	exec("echo -n j > /tmp/ir");
	sleep(1);
	exec("echo -n ' ' > /tmp/ir");
	
	/*$cmd = "ps -aux | grep DvdPlayer";
	exec($cmd, $output, $result); 

	if (count($output) <= 2){
		exec("echo -n O > /tmp/ir");
		sleep(1);
		exec("echo -n j > /tmp/ir");
		sleep(1);
		exec("echo -n ' ' > /tmp/ir");
		
		//exec('/usr/local/etc/rcS > /dev/null &');
		//sleep(10);
		//exec('killall NAS_Mode_App > /dev/null &');

	}*/
	echo "<script>parent.document.write_form.Button1.disabled=false;</script>";
	echo "<script>parent.document.write_form.Button2.disabled=true;</script>";
?>