<?php
	session_start();
	include("functions.php");
	$userid = GetIdByName($_SESSION['g_tunnus']);
	echo users_online(100, $userid);
?>