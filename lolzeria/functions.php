<?php
	// Fix for removed Session functions
	function fix_session_register(){
		function session_register(){
			$args = func_get_args();
			foreach ($args as $key){
				$_SESSION[$key]=$GLOBALS[$key];
			}
		}
		function session_is_registered($key){
			return isset($_SESSION[$key]);
		}
		function session_unregister($key){
			unset($_SESSION[$key]);
		}
	}
	if (!function_exists('session_register')) fix_session_register(); 

	function checkValid($nick, $pass, $pass2) {
		include("config.php");
		if (strlen($nick) <= 2) { 
			echo 'Tunnus pitää olla vähintään 3 merkkiä pitkä!<br />';
			$no = "1";
		}
		if (strlen($nick) >= 21) { 
			echo 'Tunnus saa olla enintään 20 merkkiä pitkä!<br />'; 
			$no = "1";
		}
		if (strlen($pass) <= 4) { 
			echo 'Salasanan pitää olla vähintään 5 merkkiä pitkä!<br />'; 
			$no = "1";
		}
		if (strlen($pass) >= 21) { 
			echo 'Salasana saa olla enintään 20 merkkiä pitkä!<br />'; 
			$no = "1";
		}
		if ($pass != $pass2) { 
			echo 'Salasanat eivät täsmää!<br />'; 
			$no = "1";
		}
		$nick = htmlspecialchars($nick);
		$nick2 = str_replace(array("ä","ö","å","Ä","Ö","Å"," ", ";", ":", "\\", "&", "#"),array("a","o","a","A","O","A","_", "", "", "", "", ""),$nick);
		if ($nick != $nick2) {
			echo 'Nimi sisälsi kiellettyjä merkkejä!<br />';
			$no = "1";
		}
		$connection = mysql_connect($host, $user, $password);
		mysql_select_db($taulu, $connection);
		$haku = mysql_query("SELECT * FROM ".$usertaulu, $connection);
		for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
			if (mysql_result($haku, $i, "name") == $nick) {
				echo 'Samanniminen henkilö on jo olemassa!<br />';
				$no = "1";
			}
		}
		mysql_close($connection);
		
		if ($no == "1") {return false;}else{return true;}
	}
	
	function createAccount($nick, $pass) {
		$nick = str_replace('<', '&lt;', $nick); 
		$nick = str_replace('>', '&gt;', $nick);
		include("config.php");
		$connection = mysql_connect($host, $user, $password);
		mysql_select_db($taulu, $connection);
		$haku = mysql_query("INSERT INTO {$usertaulu} (id, name, password, time, avatar, kuvaus, rights) VALUES ('', '{$nick}', '".md5($pass)."', '".time()."', '', '', '0');", $connection);
		mysql_close($connection);
	}
	
	function login($nick, $pass) {
		include("config.php");
		$connection = mysql_connect($host, $user, $password);
		mysql_select_db($taulu, $connection);
		$haku = mysql_query("SELECT * FROM ".$usertaulu." WHERE name = '".$nick."'", $connection);
		for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
			if (strtolower(mysql_result($haku, $i, "name")) == strtolower($nick) && mysql_result($haku, $i, "password") == md5($pass)) {return true;}
		}
		mysql_close($connection);
		return false;
	}
	
	function GetIdByName($nick) {
		include("config.php");
		$connection = mysql_connect($host, $user, $password);
		mysql_select_db($taulu, $connection);
		$haku = mysql_query("SELECT * FROM ".$usertaulu, $connection);
		for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
			if (strtolower(mysql_result($haku, $i, "name")) == strtolower($nick)) {return mysql_result($haku, $i, "id");}
		}
		mysql_close($connection);
		return false;
	}
	
	function GetFieldById($id, $field) {
		include("config.php");
		$connection = mysql_connect($host, $user, $password);
		mysql_select_db($taulu, $connection);
		$haku = mysql_query("SELECT * FROM ".$usertaulu, $connection);
		for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
			if (mysql_result($haku, $i, "id") == $id) {return mysql_result($haku, $i, $field);}
		}
		mysql_close($connection);
		return false;
	}

	function GetGroupFieldById($id, $field) {
		include("config.php");
		$connection = mysql_connect($host, $user, $password);
		mysql_select_db($taulu, $connection);
		$haku = mysql_query("SELECT * FROM groups", $connection);
		for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
			if (mysql_result($haku, $i, "id") == $id) {return mysql_result($haku, $i, $field);}
		}
		mysql_close($connection);
		return false;
	}
	
	function sendMessage($recv, $send, $head, $msg) {
		include("config.php");
		$connection2 = mysql_connect($host, $user, $password);
		mysql_select_db($taulu, $connection2);
		$haku = mysql_query("INSERT INTO messages (id, sender, receiver, otsikko, time, viesti, unread) VALUES ('', '".GetIdByName($send)."', '".GetIdByName($recv)."', '{$head}', '".time()."', '{$msg}', '1');", $connection2);
		mysql_close($connection2);
		return true;
	}
	
	function createAlbum($owner, $name) {
		include("config.php");
		$connection = mysql_connect($host, $user, $password);
		mysql_select_db($taulu, $connection);
		$haku = mysql_query("INSERT INTO albums (id, name, owner) VALUES ('', '{$name}', '{$owner}');", $connection);
		mysql_close($connection);
		return true;
	}
	
	function LuoPikkukuva( $filu, $w, $h, $kansio = "thumbs" )
	{
		
		$pa = explode( '.', $filu );
		$p = $pa[1];
		
		if ( preg_match( '/jpg|jpeg/i', $p ) )
		{
		
			$img = imagecreatefromjpeg( "uploads/$filu" );
			$pp = "jpg";
		
		}
		
		elseif ( preg_match( '/png/i', $p ) )
		{
		
			$img = imagecreatefrompng( "uploads/$filu" );
			$pp = "png";
		
		}
		
		else
		{
		
			return 1;
			
		}
		
		$imgx = imageSX( $img );
		$imgy = imageSY( $img );
				
		if( $imgx < $w )
		{
		
			$tnw = $imgx;
		
		}
			
		if ( $imgy < $h )
		{
		
			$tnh = $imgy;
		
		}
			
		if ( $imgx > $imgy )
		{
		
			if ( $tnw != $imgx ) $tnw = $w;	 //Leveys
			$tnh = $imgy * ( $h / $imgx ); //Korkeus = maksimi * ( leveys / kuvanleveys )
		
		}
		elseif ( $imgx < $imgy )
		{
			
			$tnw = $imgx * ( $w / $imgy );
			if ( $tnh != $imgy ) $tnh = $h;
			
		}
		else
		{
		
			if ( $tnw != $imgx ) $tnw = $w;
			if ( $tnh != $imgy ) $tnh = $h;
		
		}
		
		//echo "w = $w<br /> h = $h<br />Imgx = $imgx<br /> Imgy = $imgy<br />Tnw = $tnw<br /> Tnh = $tnh<br />";
		
		$tulos = ImageCreateTrueColor( $tnw, $tnh );
		imagecopyresampled( $tulos, $img, 0, 0, 0, 0, $tnw, $tnh, $imgx, $imgy );
		
		if ( $pp = "jpg" )
			imagejpeg( $tulos, "uploads/$kansio/$filu" );
		
		elseif ( $pp = "png" )
			imagepng( $tulos, "uploads/$kansio/$filu" );
			
		else
			return 2;
			
		imagedestroy( $tulos );
		imagedestroy( $img );
		
		if ( file_exists( "uploads/$kansio/$filu" ) && is_file( "uploads/$kansio/$nimi" ) )
			return 0;
			
		else
			return 3;	
	}

	function LataaPikkukuva( $nimi, $w, $h, $kansio = "thumbs" )
	{	

		if ( file_exists( "uploads/$nimi" ) && is_file( "uploads/$nimi" ) )
		{
			
			$imgs = getimagesize( "uploads/$nimi" );
			if ( $imgs[0] > $w || $imgs[1] > $h )
			{
				
				if ( file_exists( "uploads/$kansio/$nimi" ) && is_file( "uploads/$kansio/$nimi" ) )
				{
					
					return "uploads/$kansio/$nimi";
					
				}
				
				else
				{
				
					LuoPikkukuva( $nimi, $w, $h, $kansio );
					return "uploads/$kansio/$nimi";
				
				}
			
			}
			
			else
			{
				
				return "uploads/$nimi";
			
			}
		
		}

	}

	function users_online($aika, $id){
		$tiedosto = "nyt_online.txt"; // tiedosto mihin tallennetaan
		$ip = $_SERVER['REMOTE_ADDR'];
		$mk = time(); // nykyinen aika
		$table[] = "";

		foreach(file($tiedosto) as $rivi){
			$osa = explode("|", $rivi);
			// laitetaan uusi rivi taulukkoon jos aikaraja ei ole ohittunut
			if($mk-$osa[1] <= $aika) $table[$osa[0]] = "$osa[0]|$osa[1]|$osa[2]|";
		}// Online laskuri by T.M. - www.HC-Codes.net

		// asetetaan taulukkoon sinun tietosi jokatapauksessa
		$table[$ip] = "$ip|$mk|$id|";
		$filu = fopen($tiedosto, "w");
		fwrite($filu, implode("\r\n", $table));
		fclose($filu);

		// palautetaan taulukon alkioiden lukumäärä
		return count($table)-1;
	}

?>