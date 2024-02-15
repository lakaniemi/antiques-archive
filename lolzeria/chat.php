<?php
	include("config.php");
	include("functions.php");
	$connection = mysql_connect($host, $user, $password);
	mysql_select_db($taulu, $connection);
	$id = intval($_GET['id']);
	$haku = mysql_query("SELECT * FROM chat ORDER BY id DESC LIMIT 1");
	$count = 0;
	$count = @mysql_result($haku, 0, "id");
	$delhaku = mysql_query("DELETE FROM chat WHERE id < $count - 50");
	echo '<span id="newid">'.$count.'</span>';
	$haku2 = mysql_query("SELECT * FROM chat WHERE id > '$id' ORDER BY id DESC", $connection);
	for ($u = 0; $u < mysql_num_rows($haku2); $u++) { 
		echo '<span class="timestamp">['.date("d.m.Y G:i", mysql_result($haku2, $u, "time")).']</span>&nbsp;&nbsp;<b><a href="index.php?act=userpage&id='.mysql_result($haku2, $u, "sender").'">'.GetFieldById(mysql_result($haku2, $u, "sender"), "name").'</a>:</b> '.mysql_result($haku2, $u, "msg").'<br />';
	}
	mysql_close($connection);
?>