<?php
	function DoReload($polecenie)
	{
		$ip = "ip";
		$port = "port";
		$rconpw = "rcon paswsword";
		
		require_once("srcds_rcon.php");

		$srcds_rcon = new srcds_rcon();
		$srcds_rcon->rcon_command($ip, $port, $rconpw, $polecenie);
	}
?>