<?php
ob_start();
session_start(); 

/*
$agent = getenv("HTTP_USER_AGENT");	
	
	if (preg_match("/MSIE/i", $agent))
	{
		$ie = true;
		$no_xhtml = true;
		$doesnt_work = true;		
	}
	elseif (preg_match("/Dillo/i", $agent))
	{
		$dillo = true;
		$no_xhtml = true;
	}
	else
	{
		$dillo = false;
		$ie = false;
	}
	
if ( !$no_xhtml ) { header("Content-Type: application/xhtml+xml;charset=UTF-8"); } else { header("Content-Type: text/html;charset=UTF-8"); }

*/

echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"
    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">";
echo "<html xmlns=\"http://www.w3.org/1999/xhtml\"
     xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
     xsi:schemaLocation=\"http://www.w3.org/MarkUp/SCHEMA/xhtml11.xsd\"
     xml:lang=\"fi\" >";
?>
	
	<head>
		<title>
			Lolzeria - Nörttien paras galtsu
		</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
		<link rel="stylesheet" media="handheld" type="text/css" href="handheld.css" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="shortcut icon" href="favicon.ico" />
		<script type="text/javascript" src="jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="ba-linkify.min.js"></script>
	</head>
	<body>
	
		<div id="cent0r" class="iehack_500">
		
			<div id="content" class="iehack2_500">
				<div id="logo" class="left" >
					
					<!-- Logo tähän / Logo here -->
					
					<h1>echo "LOLZERIA";</h1>
					<a href="index.php"><img src="img/logo-gnew.png" alt="" /></a>
					
					<!-- Logo loppuu / Logo ends -->
					
					
					
				</div>
			<?php
			
			include("config.php"); //asetustiedosto
			include("functions.php"); //funktiot
			$act = $_GET['act'];
			$failausmaximus = "0";
			$userid = GetIdByName($_SESSION['g_tunnus']);
			$logged = GetFieldById($userid, "name");
			$online = users_online(100, $userid); // 100 sekunnin aikaraja			
			if ($userid != 0) {
				echo '<div id="nav" ><a href="index.php">Yleisnäkymä</a> <a href="index.php?act=users">Käyttäjät</a> <a href="index.php?act=friends">Kaverit</a> <a href="index.php?act=userpage&id='.$userid.'">Oma sivu</a> <a href="index.php?act=messages">Viestilaatikko</a> <a href="index.php?act=groups">Yhteisöt<a> <a href="index.php?act=settings">Asetukset</a> ';
				if (GetFieldById($userid, "rights") == 1 || GetFieldById($userid, "rights") == 2) {
					echo '<a href="index.php?act=admin">Admin</a> ';
				}
				echo '<a href="index.php?act=logout">Kirjaudu ulos</a></div>';
			}
			echo '<div id="body">';
			
			if (empty($_SESSION["g_tunnus"]) or $_SESSION["g_iposoite"] != $_SERVER["REMOTE_ADDR"]) {
				$register = $_GET['reg'];
				if ($register == "1") {
					$tunnus = $_POST['tunnus'];
					$salis  = $_POST['salis'];
					$salis2 = $_POST['salis2'];
					$beta   = $_POST['betacode'];
					if (!isset($tunnus)) {
						echo '<form action="index.php?reg=1" method="post"><div><table><tr><td><b>Tunnus:</b></td><td><input type="text" name="tunnus" /> (3-20 merkkiä)</td></tr><tr><td><b>Salasana:</b></td><td><input type="password" name="salis" /> (5-20 merkkiä)</td></tr><tr><td><b>Salasana uudestaan:</b></td><td><input type="password" name="salis2" /> (sama uudestaan)</td></tr><tr><td><a href="index.php">Takaisin</a></td><td><input type="submit" value="Luo tunnus!" /></td></tr></table></div></form>';
					}else{
						if (checkValid($tunnus, $salis, $salis2)) {
							createAccount($tunnus, $salis);
							echo 'Tunnuksesi on luotu onnistuneesti! Voit nyt kirjautua sisään <a href="index.php">täällä</a>';
						}else{
							echo 'Korjaa virheet ja yritä uudelleen!';
						}
					}
					$failausmaximus = "1";
				}else{
					if (!isset($act)) {
						$tunnus = $_POST['tunnus'];
						$salis  = $_POST['salis'];
						if (!isset($tunnus)) {
							echo '<h1>Mikä Lolzeria? :O</h1>Lolzeria on <b>ilmainen</b> galleriasivusto joka on pyritty tekemään mahdollisimman helppokäyttöiseksi sekä selkeäksi. Mikään galleriassa ei maksa, joten ylläpiro ei hyödy tästä mitään. Lolzeria on sijoitettu ilmaishostiin. Jos sivusto hidastelee tai katkeilee, niin se on luultavasti arkku.netin syytä eikä sille voi mitään. Kehitysehdotukset ja mahdolliset bugit voit lähettää yksityisviestillä Lolzeriassa nimimerkille <b>TheDuck</b>.<br /><br /><form action="index.php" method="post"><div><table><tr><td><b>Tunnus:</b></td><td><input type="text" name="tunnus" /></td></tr><tr><td><b>Salasana:</b></td><td><input type="password" name="salis" /></td></tr><tr><td><a href="index.php?reg=1">Luo tunnus</a></td><td><input type="submit" value="Kirjaudu" /></td></tr></table></div></form><br /><a href="http://www.mozilla.com/?from=sfx&amp;uid=0&amp;t=306"><img src="http://sfx-images.mozilla.org/affiliates/Buttons/firefox3/110x32_get_ffx.png" alt="Get firefox" border="0" /></a>';
						}else{
							if (login($tunnus, $salis)) {
								session_register("g_tunnus");
								$_SESSION["g_tunnus"] = $tunnus;
								session_register("g_iposoite");
								$_SESSION["g_iposoite"] = $_SERVER["REMOTE_ADDR"];
								echo 'Kirjauduit sisään onnistuneesti! <a href="index.php">Jatka</a>';
								header("location: index.php");
							}else{
								echo 'Väärä tunnus tai salasana! <a href="index.php">Yritä uudestaan!</a>';
							}
						}
						$failausmaximus = "1";
					}
				}
			}else{			
				$act    = $_GET['act'];
				if (GetFieldById($userid, "rights") == 10 && $_GET['s'] != "logoff") {
					die('Tunnuksesi on bannattu! <a href="index.php?s=logoff">Kirjaudu ulos</a>			</div>
						<div id="footer">
				
						<!-- Copyright -->
				
						Copyright TheDuck &copy;  <span class="minitext">(Grafiikka, ulkoasu ja korruption korjailu by Esa)</span>
					
						<!-- Copyright loppuu / Copyright ends -->
					
						</div>
			
						</div>
		
						</div>
	
						</body>
	
						</html>');
				}elseif(GetFieldById($userid, "rights") == 10 && $_GET['s'] == "logoff") {
					session_unregister("g_iposoite");
					session_unregister("g_tunnus");
					die('Kirjauduttiin ulos onnistuneesti! <a href="index.php">Jatka</a></div>
							<div id="footer">
					
							<!-- Copyright -->
					
							Copyright TheDuck &copy;  <span class="minitext">(Grafiikka ja ulkoasu by Esa)</span>
						
							<!-- Copyright loppuu / Copyright ends -->
						
							</div>
				
							</div>
			
							</div>
		
							</body>
		
							</html>');
				}
				
				
				//etusivu
				if (!isset($act)) {
					$connection = mysql_connect($host, $user, $password);
					mysql_select_db($taulu, $connection);
										
					if ($_GET['do'] == "comment") {
						$msgee = trim(htmlspecialchars($_POST['msg']));
						if (isset($msgee) && $msgee != "" && strlen($msgee) <= 200) {
							$hakuadd = mysql_query("INSERT INTO chat (id, sender, msg, time) VALUES ('', '{$userid}', '$msgee', '".time()."');", $connection);
						}
					}
					
					$haku = mysql_query("SELECT * FROM messages WHERE receiver = '".GetIdByName($_SESSION['g_tunnus'])."' AND unread = '1'", $connection);
					$count = mysql_num_rows($haku);
					
					$kphaku = mysql_query("SELECT * FROM friendinvites WHERE name2 = ".GetIdByName($_SESSION['g_tunnus']), $connection);
					$kpmaara = mysql_num_rows($kphaku);
					
					echo '<h1>Hei '.GetFieldById($userid, "name").'!</h1>';
					if ($count == 0) {
						echo '<img src="img/mail/16/yellow/omail.png"></img> Ei uusia yksityisviestejä <br />';
					}else{
						echo '<img src="img/mail/16/yellow/mail.png"></img> <b>'.$count.'</b> uutta yksityisviestiä! <a href="index.php?act=messages">Viestilaatikkoon</a><br />';
					}
					if ($kpmaara == 0) {
						echo '<img src="img/misc/16/addfriend.png"></img> Ei uusia kaveripyyntöjä <br />';
					}else{
						echo '<img src="img/misc/16/addfriend.png"></img> <b>'.$kpmaara.'</b> uutta kaveripyyntöä! <a href="index.php?act=friends">Kaverisivulle</a><br />';
					}
					
					echo '<img src="img/misc/16/user.png"></img> <span id="users_online"><b>Loading...</b></span><br />';
						
					echo '<img src="img/misc/16/bug.png"></img> <a href="index.php?act=bugreport">Ilmoita bugista</a><br \>';
					echo '<img src="img/misc/16/exclaim.png"></img> <a href="index.php?act=suggest">Anna kehitysehdotus</a><br \><br \>';
					echo 'Luethan <a href="index.php?act=rules">säännöt</a> ennenkuin alat lolzaamaan! :D<br />Virallinen IRC-kanava <b>#lolzeria @ IRCNet</b><br /><br />';
					echo '<img src="img/misc/16/speech.png"></img> <b>Chat:</b><br /><br /><form id="chat_form" action="index.php?do=comment" method="post">&nbsp;&nbsp;<input id="form_text" type="text" size="50" name="msg" /> <input id="form_submit" type="submit" value="Lähetä!" disabled="disabled" /><img id="form_img" src="img/misc/24/ticker.gif" width="20px" height="20px" style="vertical-align:text-top; padding-left: 3px;" /></form><div id="tsat">';
					$haku2 = mysql_query("SELECT * FROM chat ORDER BY id DESC", $connection);
					for ($u = 0; $u < mysql_num_rows($haku2); $u++) { 
						echo '<span class="timestamp">['.date("d.m.Y G:i", mysql_result($haku2, $u, "time")).']</span>&nbsp;&nbsp;<b><a href="index.php?act=userpage&id='.mysql_result($haku2, $u, "sender").'">'.GetFieldById(mysql_result($haku2, $u, "sender"), "name").'</a>:</b> '.mysql_result($haku2, $u, "msg").'<br />';
					}
					if (mysql_num_rows($haku2) == 0) { 
						echo 'Ei viestejä'; 
					}
					echo '</div>';
					mysql_close($connection);
				}elseif($act == "logout"){
					$failausmaximus = "1";
					session_unregister("g_iposoite");
					session_unregister("g_tunnus");
					echo 'Kirjauduttiin ulos onnistuneesti! <a href="index.php">Jatka</a>';
					header("location: index.php");
				
				//käyttäjät
				}elseif($act == "users"){
					$userid = GetIdByName($_SESSION['g_tunnus']);
					echo '<form action="index.php?act=search" method="post"><div><input type="text" name="src" /> <input type="submit" value="Hae käyttäjää"></input> <b>Tai voit toki myös katsella</b> <a href="index.php?act=userpage&id='.$userid.'">omaa sivuasi!</a></div></form><br />';
					echo '<b>Järjestä</b> <a href="index.php?act=users">Liittymisaika</a>&nbsp;&nbsp;&nbsp;<a href="index.php?act=users&sort=ascii">Alkukirjain</a><br /><br />';
					$connection = mysql_connect($host, $user, $password);
					mysql_select_db($taulu, $connection);
					$sort = $_GET['sort'];
					if ($sort == "ascii") {
						$haku = mysql_query("SELECT * FROM ".$usertaulu." ORDER BY name ASC", $connection);
					}else{
						$haku = mysql_query("SELECT * FROM ".$usertaulu, $connection);
					}
					
					for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
						echo '<a href="index.php?act=userpage&id='.mysql_result($haku, $i, "id").'">'.mysql_result($haku, $i, "name").'</a>';
						if (mysql_result($haku, $i, "rights") == 1) {
							echo '<img src=img/em/circle/16/yellow.png></img>';
						}elseif(mysql_result($haku, $i, "rights") == 2) {
							echo '<img src=img/em/circle/16/gray.png></img>';
						}elseif(mysql_result($haku, $i, "rights") == 3) {
							echo '<img src=img/em/circle/16/green.png></img>';
						}elseif(mysql_result($haku, $i, "rights") == 4) {
							echo '<img src=img/em/circle/16/red.png></img>';
						}elseif(mysql_result($haku, $i, "rights") == 10) {
							echo '<span class="bold">[B]</span>';
						}
						echo '<br />';
					}
					mysql_close($connection);				
				
				//käyttäjähaku
				}elseif($act == "search"){
					$name = $_POST['src'];
					$name = strtolower($name);
					echo '<b>Löydetyt käyttäjät:</b><br /><br />';
					$found = "0";
					$connection = mysql_connect($host, $user, $password);
					mysql_select_db($taulu, $connection);
					$haku = mysql_query("SELECT * FROM ".$usertaulu, $connection);
					for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
						$resultti = strtolower(mysql_result($haku, $i, "name"));
						if ($name != "") {
							if (substr_count($resultti, $name) != 0) {
								echo '<a href="index.php?act=userpage&id='.mysql_result($haku, $i, "id").'">'.mysql_result($haku, $i, "name").'</a><br />';
								$found = "1";
							}
						}
					}
					mysql_close($connection);
					if ($found == "0") {
						echo 'Hakusanalla ei löytynyt käyttäjiä.';
					}
					
				//viestilaatikko
				}elseif($act == "messages") {
					$do = $_GET['do'];
					if ($do == "delete") {
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku2 = mysql_query("SELECT * FROM messages WHERE id = ".$_GET['id'], $connection);
						if (mysql_result($haku2, 0, "receiver") != $userid) {
							echo 'Et omista tätä viestiä! >:(';
						}else{
							$haku = mysql_query("DELETE FROM messages WHERE id = ".$_GET['id'], $connection);
							echo 'Viesti poistettu!<br /><a href="index.php?act=messages">Takaisin</a>';
						}
						mysql_close($connection);
					}elseif (!isset($do)) {
						echo '<a href="index.php?act=messages&do=write">Uusi viesti</a><br /><br /><table><tr><td><b>Otsikko</b></td><td><b>Lähettäjä</b></td><td><b>Aika</b></td><td><b>Toimenpide</b></td></tr>';
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku = mysql_query("SELECT * FROM messages WHERE receiver = ".GetIdByName($_SESSION['g_tunnus'])." ORDER BY id DESC", $connection);
						for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
							$haku2 = mysql_query("SELECT * FROM ".$usertaulu." WHERE id = ".mysql_result($haku, $i, "sender"), $connection);
							$rights = intval(mysql_result($haku2, 0, "rights"));
							if ($rights == 1) {
								$fold = "yellow";
							}elseif($rights == 2) {
								$fold = "red";
							}else{
								$fold = "blue";
							}
							if (mysql_result($haku, $i, "unread")) {
								$fil = "mail.png";
							}else{
								$fil = "omail.png";
							}
							echo '<tr><td><img alt="viesti" src="img/mail/16/'.$fold.'/'.$fil.'"></img> <a href="index.php?act=messages&do=read&id='.mysql_result($haku, $i, "id").'">'.mysql_result($haku, $i, "otsikko").'</a></td><td>'.GetFieldById(mysql_result($haku, $i, "sender"), "name").'</td><td>'.date("j.n.Y G:i", mysql_result($haku, $i, "time")).'</td><td><a href="index.php?act=messages&do=delete&id='.mysql_result($haku, $i, "id").'"><img src="img/misc/16/X.png"></img>Poista</a></td></tr>';
						}
						mysql_close($connection);
						echo '</table>';
						if ($i == 0) {
							echo '&nbsp;Ei viestejä.';
						}
					}elseif($do == "write"){
						$but = $_POST['button'];
						$nick = $_GET['nick'];
						$answer = $_GET['answer'];
						$addto = "";
						if (isset($answer)) {
							$addto = "RE: ".$answer;
						}
						if (!isset($but)) {
							echo '<a href="index.php?act=messages"><img src="img/misc/16/undo.png"></img>Peruuta</a><br /><br /><form action="index.php?act=messages&do=write" method="post"><div>
							<b>Vastaanottaja:</b> <input type="text" name="to" value="'.$nick.'" /><br />
							<b>Otsikko:</b> <input type="text" name="head" value="'.$addto.'" /><br />
							<b>Viesti:</b><br />
							<textarea cols="60" rows="15" name="message"></textarea><br />
							<input type="hidden" value="jotain" name="button" />
							<input type="submit" value="Lähetä" />
							</div></form>';
						}else{
							$to = htmlspecialchars($_POST['to']);
							$head = htmlspecialchars($_POST['head']);
							$msg = htmlspecialchars($_POST['message']);
							if ($to == "" or $head == "" or $msg == "") {
								echo 'Joku kenttä oli tyhjä!';
							}else{
								If (GetIdByName($to) == false) {
									echo 'Vastaanottajaa ei löydy tietokannasta!';
								}else{
									if (strlen($head) > 50) { 
										echo 'Otsikko saa olla maksimissaan 50 merkkiä pitkä!';
									}else{
										if (sendMessage($to, $_SESSION['g_tunnus'], $head, $msg)) {
											echo 'Viesti lähetetty onnistuneesti!';
										}else{
											echo 'Virhe viestin lähetyksessä!';
										}
									}
								}
							}
							echo '<br /><a href="index.php?act=messages">Takaisin viestilaatikkoon</a>';
						}	
					}elseif($do == "read"){
						$id = intval(mysql_escape_string($_GET['id']));
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku = mysql_query("SELECT * FROM messages WHERE id = ".$id, $connection);
						for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
							if (mysql_result($haku, 0, "receiver") != $userid) {
								echo 'Et omista tätä viestiä¡ >:(';
							}else{
								if (mysql_result($haku, $i, "unread")) {
									$haku2 = mysql_query("UPDATE messages SET unread = 0 WHERE id = '".mysql_result($haku, $i, "id")."';", $connection);
								}
								echo '<a href="index.php?act=messages&do=write&nick='.GetFieldById(mysql_result($haku, $i, "sender"), "name").'&answer='.mysql_result($haku, $i, "otsikko").'"><img src="img/misc/16/edit.png"></img>Vastaa viestiin</a>  <a href="index.php?act=messages&do=delete&id='.$id.'"><img src="img/misc/16/X.png"></img>Poista viesti</a>  <a href="index.php?act=messages"><img src="img/misc/16/undo.png"></img>Takaisin</a><br /><br /><table><tr><td><b>Otsikko:</b></td><td>'.mysql_result($haku, $i, "otsikko").'</td></tr><tr><td><b>Lähettäjä:</b></td><td>'.GetFieldById(mysql_result($haku, $i, "sender"), "name").'</td></tr></table><div style="padding-left: 3px;"><b>Viesti:</b><br />'.str_replace("\n", "<br />", mysql_result($haku, $i, "viesti")).'</div>';
							}
						}
						mysql_close($connection);
					}
					
				//asetuksia
				}elseif($act == "settings"){
					$but = $_POST['done'];
					$userid = GetIdByName($_SESSION['g_tunnus']);
					if (!isset($but)) {
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku = mysql_query("SELECT * FROM users WHERE id = ".$userid, $connection);
						echo '<form action="index.php?act=settings" method="post"><div><b>Uusi salasana:</b> <input type="password" name="pw1" /><br /><b>Sama uudestaan:</b> <input type="password" name="pw2" /><br />Voit muuttaa salasanaasi jos niin haluat. (Josset halua, jätä tyhjäksi)<br /><br /><b>Avatar:</b> <input type="text" name="ava" value="'.mysql_result($haku, 0, "avatar").'" /><br />Voit kirjoittaa avatarin osoitteen niin se lisätään omalle sivullesi.<br /><br /><b>Oma kuvaus:</b> <input type="text" name="desc" value="'.mysql_result($haku, 0, "kuvaus").'" /><br />Voit kirjoittaa maksimissaan 200 merkkiä pitkän kuvauksen itsestäsi<br /><br /><input type="submit" name="done" value="Tallenna asetukset" /></div></form>';
						mysql_close($connection);
					}else{
						$pw = $_POST['pw1'];
						$pw2 = $_POST['pw2'];
						$ava = htmlspecialchars($_POST['ava']);
						$kuvaus = htmlspecialchars($_POST['desc']);
						if (isset($pw) or isset($pw2) or $pw != "" or $pw2 != "") {
							if ($pw != $pw2) {
								echo 'Salasanat eivät täsmää!<br />';
								$updatepw = 0;
							}elseif(strlen($pw) <= 4 or strlen($pw) >= 21) {
								$updatepw = 0;
							}else{
								$updatepw = 1;
							}
						}else{
							$updatepw = 0;
						}
						if (strlen($kuvaus) >= 200) {
							echo 'Kuvaus on liian pitkä!<br />';
							$updatedesc = 0;
						}else{
							$updatedesc = 1;
						}
						if (strlen($ava) >= 200) {
							echo 'Avatarin osoite on liian pitkä!<br />';
							$updateavatar = 0;
						}elseif(strlen($ava) > 0){
							$updateavatar = 1;
						}else{
							$updateavatar = 0;
						}
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						if ($updateavatar) { 
							$haku = mysql_query("UPDATE users SET avatar = '$ava' WHERE id = ".$userid, $connection); 
							echo 'Avatar tallennettu!<br />';
						}
						if ($updatedesc) { 
							$haku2 = mysql_query("UPDATE users SET kuvaus = '$kuvaus' WHERE id = ".$userid, $connection); 
							echo 'Kuvaus tallennettu!<br />';
						}
						if ($updatepw) { 
							$haku3 = mysql_query("UPDATE users SET password = '".md5($pw)."' WHERE id = ".$userid, $connection); 
							echo 'Salasana tallennettu!<br />';
						}
						
					}
				
				//yhteisöt
				}elseif($act == "groups"){
					$do = $_GET['do'];
					$userid = GetIdByName($_SESSION['g_tunnus']);
					if (!isset($do)) {
						echo '<form action="index.php?act=groups&do=search" method="post"><div><input type="text" name="src" /> <input type="submit" value="Hae yhteisöä" /> <b>Eikö löydy?</b> <a href="index.php?act=groups&do=make">Luo yhteisö!</a></div></form>';
						echo '<br /><b>Järjestä</b> <a href="index.php?act=groups">Luomisaika</a>&nbsp;&nbsp;&nbsp;<a href="index.php?act=groups&sort=ascii">Alkukirjain</a><br /><br />';
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$sort = $_GET['sort'];
						if ($sort == "ascii") {
							$haku = mysql_query("SELECT * FROM groups ORDER BY name ASC", $connection);
						}else{
							$haku = mysql_query("SELECT * FROM groups ORDER BY id ASC", $connection);
						}
						for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
							echo '<a href="index.php?act=grouppage&id='.mysql_result($haku, $i, "id").'">'.mysql_result($haku, $i, "name").'</a>';
							if (mysql_result($haku, $i, "locked") == 1) {
								echo '<img src="img/misc/16/lock-closed.png"></img>';
							}
							echo '<br />';
						}
						mysql_close($connection);
					}elseif($do == "search"){
						$name = strtolower($_POST['src']);
						echo '<b>Löydetyt yhteisöt/b><br /><br />';
						$found = "0";
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku = mysql_query("SELECT * FROM groups", $connection);
						for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
							$resultti = strtolower(mysql_result($haku, $i, "name"));
							if ($name != "") {
								if (substr_count($resultti, $name) != 0) {
									echo '<a href="index.php?act=grouppage&id='.mysql_result($haku, $i, "id").'">'.mysql_result($haku, $i, "name").'</a><br />';
									$found = "1";
								}
							}
						}
						mysql_close($connection);
						if ($found == "0") {
							echo 'Hakusanalla ei löytynyt yhteisöitä';
						}
					}elseif($do == "make") {
						$sub = $_POST["sendit"];
						if (!isset($sub)) {
							echo '<a href="index.php?act=groups">Peruuta</a><br /><br /><form action="index.php?act=groups&do=make" method="post"><div><b>Yhteisön nimi:</b> <input type="text" name="nimi" /><br /><b>Yhteisön kuvaus:</b><br /><textarea name="kuv" rows="4" cols="40"></textarea><br /><input type="hidden" name="sendit" value="jotain" /><input type="submit" value="Luo yhteisö" /></div></form>';
						}else{
							$neim = trim(htmlspecialchars($_POST['nimi']));
							$kuv = htmlspecialchars($_POST['kuv']);
							$connection = mysql_connect($host, $user, $password);
							mysql_select_db($taulu, $connection);
							$haku = mysql_query("SELECT * FROM groups", $connection);
							$fail = 0;
							for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
								if (mysql_result($haku, $i, "name") == $neim) {
									echo 'Samanniminen yhteisö on jo olemassa! <a href="index.php?act=groups">Takaisin yhteisöihin</a>';
									$fail = 1;
								}
							}
							if (strlen($neim) >= 61) {
								echo 'Yhteisön nimi oli liian pitkä! (max. 60 merkkiä)';
								$fail = 1;
							}
							if (strlen($kuv) >= 1001) {
								echo 'Yhteisön kuvaus oli liian pitkä! (1000 merkkiä raja)';
								$fail = 1;
							}
							if ($fail != 1) {
								if (!isset($neim) or !isset($kuv) or $neim == "" or $kuv == "") {
									echo 'Jokin kenttä oli tyhjä¡';
								}else{
									$haku3 = mysql_query("INSERT INTO groups (id, name, kuvaus, time, locked) VALUES ('', '".$neim."', '".$kuv."', '".time()."', '0');", $connection);
									$haku2 = mysql_query("INSERT INTO groupmembers (name, grouppi, rights) VALUES ('{$userid}', '".mysql_insert_id()."', '1');", $connection);
									echo 'Yhteisö luotu! <a href="index.php?act=groups">Takaisin yhteisöön</a>';
								}
							}
							mysql_close($connection);
						}
					}
				
				//yhteisösivu
				}elseif($act == "grouppage"){
					$id = intval(mysql_escape_string($_GET['id']));
					$userid = GetIdByName($_SESSION['g_tunnus']);
					$do = $_GET['do'];
					$manage = $_GET['manage'];
					$connection = mysql_connect($host, $user, $password);
					mysql_select_db($taulu, $connection);

					$argho = 0;
					$argho2 = 0;
					$count = 0;
					$lhaku = mysql_query("SELECT * FROM groupmembers WHERE grouppi = ".$id, $connection);
					for ($x = 0; $x < mysql_num_rows($lhaku); $x++) { 
						if (mysql_result($lhaku, $x, "name") == $userid) {
							$argho = 1;
							if (mysql_result($lhaku, $x, "rights") >= 1) {
								$argho2 = mysql_result($lhaku, $x, "rights");
							}
						}
						$count++;
					}
					if ($do == "join") {
						$hakuasd = mysql_query("SELECT * FROM groups WHERE id = ".$id, $connection);
						if ($argho == 0 and mysql_result($hakuasd, 0, "locked") == 0) {
							$lhaku2 = mysql_query("INSERT INTO groupmembers (name, grouppi, rights) VALUES ('$userid', '$id', '0');", $connection);
						}
					}elseif($do == "leave") {
						if ($argho == 1) {
							$lhaku2 = mysql_query("DELETE FROM groupmembers WHERE name = '$userid' AND grouppi = '$id';", $connection);
							if ($count == 1) {
								$lhaku2 = mysql_query("DELETE FROM groups WHERE id = '$id';", $connection);
								$deleted = 1;
							}else{
								$deleted = 0;
							}
						}
					}elseif($do == "comment") {
						if ($argho == 1) {
							$mesg = htmlspecialchars($_POST['msg']);
							if (strlen($mesg) >= 201) {
								echo 'Viestin maksimipituus on 200 merkkiä!<br /><br />';
							}else{
								$lhaku3 = mysql_query("INSERT INTO groupmessages (id, sender, msg, time, grouppi) VALUES ('', '$userid', '$mesg', '".time()."', '$id');", $connection);
							}
						}
					}
					$haku = mysql_query("SELECT * FROM groups WHERE id = ".$id, $connection);
					$found = 0;
					for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
						if ($manage == "true") {
							if ($argho2 >= 1) {
								echo '<a href="index.php?act=grouppage&id='.$id.'">Takaisin yhteisön sivulle</a><br /><br />';
								$sendaus = $_POST['sendit'];
								if (!isset($sendaus)) {
									echo '<form action="index.php?act=grouppage&id='.$id.'&manage=true" method="post"><div>';
									echo '<b>Yhteisön kuvaus:</b><br /><textarea name="kuv" rows="4" cols="40">'.mysql_result($haku, $i, "kuvaus").'</textarea><br /><br />';
									$checkattu = mysql_result($haku, $i, "locked");
									if ($checkattu == 1) {
										echo '<b>Yhteisö lukossa?</b> <input type="checkbox" name="locki" value="yes" checked="1" /><br />';
									}else{
										echo '<b>Yhteisö lukossa?</b> <input type="checkbox" name="locki" value="yes" /><br />';
									}
									echo '<b>Tyhjennä yhteisötsätti?</b> <input type="checkbox" name="pika" value="yes" /><br /><br />';
									echo '<input type="submit" value="Tallenna asetukset" name="sendit" />';
									echo '</div></form>';
								}else{
									$kuv = htmlspecialchars($_POST['kuv']);
									$locki = $_POST['locki'];
									$pika = $_POST['pika'];
									if ($locki == "yes") {
										$locksu = 1;
									}else{
										$locksu = 0;
									}
									if (strlen($kuv) >= 1001) {
										echo 'Yhteisön kuvaus oli liian pitkä¡ (1000 merkkiä raja)<br />';
									}else{
										$dhaku2 = mysql_query("UPDATE groups SET kuvaus = '$kuv' WHERE id = ".$id, $connection);
									}
									$dhaku3 = mysql_query("UPDATE groups SET locked = '$locksu' WHERE id = ".$id, $connection);
									echo 'Asetukset tallennettu!<br />';
									if (isset($pika)) {
										$dhaku = mysql_query("DELETE FROM groupmessages WHERE grouppi = ".$id, $connection);
										echo 'Yhteisötsätti tyhjennetty!<br />';
									}
								}
							}else{
								echo 'Ei oikeuksia!';
							}
						}elseif($manage == "true2") {
							if ($argho2 == 1) {
								$kix = $_GET['kick'];
								$giv = $_GET['give'];
								$tak = $_GET['take'];
								if (isset($kix) and $kix != $userid) {
									$hakuaa = mysql_query("DELETE FROM groupmembers WHERE name = '$kix' AND grouppi = '$id';", $connection);
								}
								if (isset($giv) and $kix != $userid) {
									$hakuaaa = mysql_query("UPDATE groupmembers SET rights = '2' WHERE name = '$giv' AND grouppi = '$id';", $connection);
								}
								if (isset($tak) and $kix != $userid) {
									$hakuaaaa = mysql_query("UPDATE groupmembers SET rights = '0' WHERE name = '$tak' AND grouppi = '$id';", $connection);
								}
								echo '<a href="index.php?act=grouppage&id='.$id.'">Takaisin yhteisön sivulle</a><br /><br />';
								$ehaku = mysql_query("SELECT * FROM groupmembers WHERE grouppi = ".$id, $connection);
								echo '<table>';
								for ($y = 0; $y < mysql_num_rows($ehaku); $y++) { 
									if (mysql_result($ehaku, $y, "rights") == 2) {
										echo '<tr><td>'.GetFieldById(mysql_result($ehaku, $y, "name"), "name").'</td><td><a href="index.php?act=grouppage&id='.$id.'&manage=true2&kick='.mysql_result($ehaku, $y, "name").'">Potki käyttäjä</a>, <a href="index.php?act=grouppage&id='.$id.'&manage=true2&take='.mysql_result($ehaku, $y, "name").'">Poista oikeudet</a></td></tr>';
									}elseif(mysql_result($ehaku, $y, "rights") == 1){
										//comment
									}else{
										echo '<tr><td>'.GetFieldById(mysql_result($ehaku, $y, "name"), "name").'</td><td><a href="index.php?act=grouppage&id='.$id.'&manage=true2&kick='.mysql_result($ehaku, $y, "name").'">Potki käyttäjä</a>, <a href="index.php?act=grouppage&id='.$id.'&manage=true2&give='.mysql_result($ehaku, $y, "name").'">Anna oikeudet</a></td></tr>';
									}
								}
								echo '</table>';
							}else{
								echo 'Ei oikeuksia!';
							}
						}else{
							echo '<h2><u>'.mysql_result($haku, $i, "name").'</u></h2>';
							if ($argho2 >= 1) {
								echo '<a href="index.php?act=grouppage&id='.$id.'&manage=true">Yhteisön hallinta</a>';
							}
							if ($argho2 == 1) {
								echo ' | <a href="index.php?act=grouppage&id='.$id.'&manage=true2">Yhteisön käyttäjien hallinta</a>';
							}
							if ($argho2 >= 1) {
								echo '<br /><br />';
							}
							$kuva_us = trim(str_replace("\n", "<br />", mysql_result($haku, $i, "kuvaus")));
							if ($kuva_us == "" || !isset($kuva_us)) {
								$kuva_us = "-";
							}
							echo '<img src="img/misc/16/edit.png"></img> <b>Yhteisön kuvaus:</b><br />'.$kuva_us.'<br /><br /><img src="img/misc/16/addfriend.png"></img> <b>Yhteisön jäsenet:</b> ';
							$haku2 = mysql_query("SELECT * FROM groupmembers WHERE grouppi = ".$id, $connection);
							for ($a = 0; $a < mysql_num_rows($haku2); $a++) { 
								if (mysql_result($haku2, $a, "rights") == "1") {
									echo '<u>';
								}
								echo '<a href="index.php?act=userpage&id='.mysql_result($haku2, $a, "name").'">'.GetFieldById(mysql_result($haku2, $a, "name"), "name").'</a>';
								if (mysql_result($haku2, $a, "rights") == "1" or mysql_result($haku2, $a, "rights") == "2") {
									if (mysql_result($haku2, $a, "rights") == "1") {
										echo '</u>';
									}								
									echo '<img alt="ryhmäadmin" src="img/star/16/red.png"></img>';
								}
								echo ' ';
								if (mysql_result($haku2, $a, "name") == $userid) {
									$urin = 1;
								}
							}
							if ($urin == 0) {
								if (mysql_result($haku, $i, "locked") == 0) {
									echo '<br /><br /><a href="index.php?act=grouppage&id='.$id.'&do=join">Liity yhteisöön</a>';
								}else{
									echo '<br /><br />Tähän yhteisöön liittyminen on lukittu.';
								}
							}else{
								$delchat = intval($_GET['delchat']);
								if ($delchat != 0 && $argho2 >= 1) {
									$validquery = mysql_query("SELECT grouppi FROM groupmessages WHERE id = '$delchat'");
									if (mysql_result($validquery, 0, "grouppi") == $id) {
										$delquery = mysql_query("DELETE FROM groupmessages WHERE id = '$delchat'");
									}
								}
								echo '<br /><br /><img src="img/misc/16/speech.png"></img> <b>Yhteisötsätti:</b><br /><table>';
								$mhaku = mysql_query("SELECT * FROM groupmessages WHERE grouppi = ".$id." ORDER BY id ASC", $connection);
								for ($u = 0; $u < mysql_num_rows($mhaku); $u++) { 
									echo '<tr>';
									if ($argho2 >= 1) {
										echo '<td><a href="index.php?act=grouppage&id='.$id.'&delchat='.mysql_result($mhaku, $u, "id").'"><img src="img/misc/16/X.png"></img></a></td>';
									}
									echo '<td class="chattilol"><span class="timestamp">['.date("d.m.Y G:i", mysql_result($mhaku, $u, "time")).']</span></td><td><b><a href="index.php?act=userpage&id='.mysql_result($mhaku, $u, "sender").'">'.GetFieldById(mysql_result($mhaku, $u, "sender"), "name").'</a>:</b> '.mysql_result($mhaku, $u, "msg").'</td></tr>';
								}
								echo '</table>';
								if ($u == 0) {
									echo 'Ei viestejä!<br />';
								}
								echo '<br /><form action="index.php?act=grouppage&id='.$id.'&do=comment" method="post"><div><input type="text" name="msg" /> <input type="submit" value="Lähetä viesti!" /></div></form>';
								echo '<br /><a href="index.php?act=grouppage&id='.$id.'&do=leave">Poistu yhteisöstä!</a>';
							}
						}
						$found = 1;
					}
					if ($found == 0) {
						if ($deleted == 1) {
							echo 'Poistuit yhteisöstä ja se poistettiin koska käyttäjät loppuivat! <a href="index.php?act=groups">Takaisin yhteisöön</a>';
						}else{
							echo 'Yhteisöä ei löytynyt!';
						}
					}
					mysql_close($connection);
				
				//admin
				}elseif($act == "admin") {
					$userid = GetIdByName($_SESSION['g_tunnus']);
					$connection = mysql_connect($host, $user, $password);
					mysql_select_db($taulu, $connection);
					$haku = mysql_query("SELECT * FROM ".$usertaulu." WHERE id = $userid", $connection);
					for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
						if (mysql_result($haku, $i, "rights") == 1 || mysql_result($haku, $i, "rights") == 2) {
							$do = $_GET['s'];
							if (!isset($do)) {
								$subbi = $_POST['sub'];
								$subbi2 = $_POST['subbi'];
								echo '<u><a href="index.php?act=admin">Asetusten hallinta</a></u> | <a href="index.php?act=admin&s=users">Käyttäjien hallinta</a><br /><br />';
								if (!isset($subbi) && !isset($subbi2)) {
									echo '<form action="index.php?act=admin" method="post"><div><b><u>Joukkoviesti</u>:</b><br /><br /><b>Otsikko:</b> <input type="text" name="head" /><br /><b>Viesti:</b><br /><textarea cols="60" rows="15" name="message"></textarea><br /><input type="hidden" value="jotain" name="sub" /><input type="submit" value="Lähetä" /><br /><br /></div></form><form action="index.php?act=admin" method="post"><div><input type="submit" value="Tyhjennä yleischat" name="subbi" /></div></form>';
								}elseif(isset($subbi)) {
									$otsi = htmlspecialchars($_POST['head']);
									$msgi = htmlspecialchars($_POST['message']);
									if ($otsi == "" or $msgi == "") {
										echo 'Joku kenttä oli tyhjä!';
									}else{
										$haku4 = mysql_query("SELECT * FROM ".$usertaulu, $connection);
										for ($o = 0; $o < mysql_num_rows($haku4); $o++) { 
											if (sendMessage(mysql_result($haku4, $o, "name"), $_SESSION['g_tunnus'], $otsi, $msgi)) {
												echo 'Viesti lähetetty onnistuneesti!<br />';
											}else{
												echo 'Virhe viestin lähetyksessä!<br />';
											}
										}
									}
								}elseif(isset($subbi2)) {
									$haku2 = mysql_query("DELETE FROM chat", $connection);
									echo 'Tsätti tyhjennetty!';
								}
							}elseif($do == "users") {
								echo '<a href="index.php?act=admin">Asetusten hallinta</a> | <u><a href="index.php?act=admin&s=users">Käyttäjien hallinta</a></u><br /><br />';
								$ban = intval(mysql_escape_string($_GET['ban']));
								$unban = intval(mysql_escape_string($_GET['unban']));
								if (mysql_result($haku, $i, "rights") == "1" || mysql_result($haku, $i, "id") == "9") {		
									if (isset($ban)) {
										$haku5 = mysql_query("UPDATE users SET rights = '10' WHERE id = '$ban';", $connection);
									}
									if (isset($unban)) {
										$haku5 = mysql_query("UPDATE users SET rights = '0' WHERE id = '$unban';", $connection);
									}
									echo '<table cellpadding="0" cellspacing="0">';
									$haku5 = mysql_query("SELECT * FROM $usertaulu", $connection);
									for ($s = 0; $s < mysql_num_rows($haku5); $s++) {
										if (mysql_result($haku5, $s, "rights") == 10) {
											echo '<tr><td>'.mysql_result($haku5, $s, "name").'</td><td><a href="index.php?act=admin&s=users&unban='.GetIdByName(mysql_result($haku5, $s, "name")).'">UNBAN!</a></td></tr>';
										}else{
											echo '<tr><td>'.mysql_result($haku5, $s, "name").'</td><td><a href="index.php?act=admin&s=users&ban='.GetIdByName(mysql_result($haku5, $s, "name")).'">BAN!</a></td></tr>';
										}
									}
									echo '</table>';
								}else{
									echo 'ADMIN ONLY!';
								}
							}
						}else{
							echo 'Ei oikeuksia!';
						}
					}
					@mysql_close($connection);
										
				//säännöt				
				}elseif($act == "rules") {
					echo '<h1>Lolzerian yleiset säännöt</h1>';
					echo '<b>Yleinen</b><br />-Vältä kiroilua ja asiatonta käytöstä<br />-Älä hauku muita<br />-Ei turhaa spämmäystä esimerkiksi yhteisötsätissä tai kuvien kommentoinnissa<br /><br />';
					echo '<b>Kuvat</b><br />-Porno ja kaikki muu K-18 sisältö on kielletty<br />-Ylläpidolla on oikeus poistaa kuvia huomauttamatta jos kokee ne loukkaaviksi<br />-Pidetään ne kuvaukset asiallisina<br /><br />';
					echo '<b>Yhteisöt</b><br />-Älä lähetä viestejä tsättiin tyyliin "AAAAAAAAAAAAA..", koska se on turhaa<br />-Kuvaukset asiallisina<br /><br />';
					echo '<b>Muuta</b><br />-Bugien hyväksikäytöstä voidaan rangaista riippuen siitä, aiheuttiko se vahinkoa<br />-Kritiikki sallittua<br />-Bannien pituus on yleensä loputon, mutta niitä voi toki anoa pois.';
				
				//bugreport
				}elseif($act == "bugreport") {
					$submit = $_POST['sub'];
					$otsikko = $_POST['otsikko'];
					$message = $_POST['message'];
					if (!isset($submit)) {
						echo '<h1>Bugien ilmoittaminen</h1>';
						echo 'Järjestelmän hyväksikäytöstä tai spämmimisestä tulee bannit.<br /><br />';
						echo '<form action="index.php?act=bugreport" method="post"><input type="hidden" name="sub" value="jotain" /><b>Otsikko:</b> <input type="text" name="otsikko" size="30" /><br /><br/><b>Kuvaus (paikka, miten, milloin?):<br/> </b><textarea cols="60" rows="15" name="message"></textarea><br /><input type="submit" value="Lähetä" /></form>';
					}else{
						if ($otsikko != "" && $message != "") {
							if (strlen($otsikko) > 50) {
								echo 'Otsikko saa olla korkeintaan 50 merkkiä pitkä¡';
							}else{
								$msg = "Ilmoittaja: ".GetFieldById($userid, "name")."\r\n______________________________\r\n".$message;
								$otsi = "[Bugreport] ".$otsikko;
								if (sendMessage("TheDuck", "LolzBot", $otsi, $msg) && sendMessage("esa", "LolzBot", $otsi, $msg)) {
									echo 'Bugi ilmoitettu onnistuneesti!<br />';
								}else{
									echo 'MySQL-virhe tai jotain ihan sekavaa.<br />';
								}
							}
						}else{
							echo 'Jotain unohtui! Ilmoitusta ei lå©¥tetty!';
						}
					}
					
				//kehitysehdotus
				}elseif($act == "suggest") {
					$submit = $_POST['sub'];
					$otsikko = $_POST['otsikko'];
					$message = $_POST['message'];
					if (!isset($submit)) {
						echo '<h1>Kehitysehdotus</h1>';
						echo 'Auta meitä parantamaan Lolzerian käyttäjäystävällisyyttä ja toimivuutta tekemällä ehdotus!<br /><br />';
					echo '<form action="index.php?act=suggest" method="post"><input type="hidden" name="sub" value="jotain" /><b>Otsikko:</b> <input type="text" name="otsikko" size="30" /><br /><br/><b>Ehdotuksesi:<br/> </b><textarea cols="60" rows="15" name="message"></textarea><br /><input type="submit" value="Lähetä" /></form>';
					}else{
						if ($otsikko != "" && $message != "") {
							if (strlen($otsikko) > 50) {
								echo 'Otsikko saa olla korkeintaan 50 merkkiä pitkä¡';
							}else{
								$msg = "Ehdottaja: ".GetFieldById($userid, "name")."\r\n______________________________\r\n".$message;
								$otsi = "[Suggestion] ".$otsikko;
								if (sendMessage("TheDuck", "LolzBot", $otsi, $msg) && sendMessage("esa", "LolzBot", $otsi, $msg)) {
									echo 'Kiitos ehdotuksestasi!!<br />';
								}else{
									echo 'MySQL-virhe tai jotain ihan sekavaa.<br />';
								}
							}
						}else{
							echo 'Jotain unohtui! Ehdotusta ei lähetetty!';
						}
					}
					
				//kaverit
				}elseif($act == "friends") {
					$do = $_GET['do'];
					$id = intval($_GET['id']);
					$useri = intval($_GET['user']);
					if (!isset($do)) {
						echo '<b>Kaveripyynnöt:</b><br \><br \>';
						$query = mysql_query("SELECT * FROM friendinvites WHERE name2 = '$userid'");
						if (mysql_num_rows($query) == 0) {
							echo 'Ei uusia kaveripyyntöjä!<br \><br \>';
						}else{
							echo '<table cellpadding="0" cellspacing="0"><tr><td><b>Nimi</b></td><td><b>Toiminnot</b></td></tr>';
							for ($i = 0; $i < mysql_num_rows($query); $i++) {
								echo '<tr><td style="min-width: 100px;"><a href="index.php?act=userpage&id='.mysql_result($query, $i, "name").'">'.GetFieldById(mysql_result($query, $i, "name"), "name").'</a></td><td><a href="index.php?act=friends&do=accept&id='.mysql_result($query, $i, "id").'"><img src="img/misc/16/ok.png"></img>Hyväksy</a> <a href="index.php?act=friends&do=decline&id='.mysql_result($query, $i, "id").'"><img src="img/misc/16/X.png"></img>Hylkää</a></td></tr>';
							}
							echo '</table><br \>';
						}
						echo '<b>Nykyiset kaverit:</b><br \><br \>';
						$query2 = mysql_query("SELECT * FROM friends WHERE name2 = '$userid'");
						if (mysql_num_rows($query2) == 0) {
							echo 'Sinulla ei ole vielä kavereita!';
						}else{
							echo '<table cellpadding="0" cellspacing="0"><tr><td><b>Nimi</b></td><td><b>Toiminnot</b></td></tr>';
							for ($i = 0; $i < mysql_num_rows($query2); $i++) {
								echo '<tr><td style="min-width: 100px;"><a href="index.php?act=userpage&id='.mysql_result($query2, $i, "name").'">'.GetFieldById(mysql_result($query2, $i, "name"), "name").'</a></td><td><a href="index.php?act=friends&do=delete&user='.mysql_result($query2, $i, "name").'"><img src="img/misc/16/X.png"></img>Poista</a> <a href="index.php?act=messages&do=write&nick='.GetFieldByID(mysql_result($query2, $i, "name"), "name").'"><img src="img/misc/16/edit.png"></img>Lähetä viesti</a></td></tr>';
							}
							echo '</table>';
						}
					}elseif($do == "accept") {
						$query = mysql_query("SELECT * FROM friendinvites WHERE id = '$id'");
						if (@mysql_result($query, 0, "name2") == $userid) {
							$addquery = mysql_query("INSERT INTO friends (id, name, name2) VALUES ('', '".mysql_result($query, 0, "name")."', '".mysql_result($query, 0, "name2")."')");
							$addquery2 = mysql_query("INSERT INTO friends (id, name, name2) VALUES ('', '".mysql_result($query, 0, "name2")."', '".mysql_result($query, 0, "name")."')");
							$removequery = mysql_query("DELETE FROM friendinvites WHERE id = '$id'");
							echo 'Sinä ja '.GetFieldByID(mysql_result($query, 0, "name"), "name").' olette nyt kavereita!';
						}else{
							echo 'Älä edes yritä -.-';
						}
					}elseif($do == "decline") {
						$query = mysql_query("SELECT * FROM friendinvites WHERE id = '$id'");
						if (mysql_result($query, 0, "name2") == $userid) {
							$removequery = mysql_query("DELETE FROM friendinvites WHERE id = '$id'");
							echo 'Hylkäsit käyttäjän '.GetFieldByID(mysql_result($query, 0, "name"), "name").' kaveripyynnön';
						}else{
							echo 'Älä edes yritä -.-';
						}
					}elseif($do == "delete") {
						$query = mysql_query("SELECT * FROM friends WHERE name = '$userid' AND name2 = '$useri'");
						if (mysql_num_rows($query) > 0) {
							$delquery = mysql_query("DELETE FROM friends WHERE name = '$userid' AND name2 = '$useri'");
							$delquery2 = mysql_query("DELETE FROM friends WHERE name = '$useri' AND name2 = '$userid'");
							echo 'Sinä ja '.GetFieldByID($useri, "name").' ette ole enää kavereita!';
						}else{
							echo 'Huh? Et ole kyseisen käyttäjän kaveri!';
						}
					}elseif($do == "invite") {
						$query = mysql_query("SELECT * FROM friendinvites WHERE (name = '$userid' OR name = '$useri') AND (name2 = '$useri' OR name2 = '$userid')");
						if (mysql_num_rows($query) > 0) {
							echo 'Äläs nyt noin innoissasi ole! Yksi pyyntö henkilölle kerrallaan riittää!';
						}else{
							$query2 = mysql_query("SELECT * FROM friends WHERE name = '$userid' AND name2 = '$useri'");
							if (mysql_num_rows($query2) > 0) {
								echo 'Olette jo kavereita! Ei nyt tuplasuhteita sallita!';
							}else{
								$addquery = mysql_query("INSERT INTO friendinvites (id, name, name2) VALUES ('', '$userid', '$useri')");
								echo 'Kaveripyyntö lähetetty!';
							}
						}
					}
				}elseif($act == "online") {
					echo '<h1>Tällä hetkellä on onlinessa seuraavat käyttäjät:</h1>';
					$onlinet = file("nyt_online.txt");
					foreach($onlinet as $key => $value) {
						if ($key != 0) {
							$userinfo = explode("|", $value);
							if ($userinfo[2] == "") {
								echo 'Ei-kirjautunut käyttäjä.<br />';
							}else{
								echo '<a href="index.php?act=userpage&id='.$userinfo[2].'">'.GetFieldByID($userinfo[2], "name").'</a><br />';
							}
						}
					}
				}else{
					if ($act != "userpage" and $act != "album" and $act != "image") {
						echo '404';
					}
				}
			}
			
			
			
			
			//////////////////////////////////////////
			//Unregistered
			
			
			
			
			
			//userpage
			if($act == "userpage") {
				$id   = $_GET['id'];
				$userid = GetIdByName($_SESSION['g_tunnus']);
				$taso = array("0" => "Normaali käyttäjä", "1" => "Admin", "2" => "Moderaattori", "3" => "Testaaja", "4" => "Botti", "10" => "Bannattu");
				$timg = array("1" => "yellow.png", "2" => "gray.png", "3" => "green.png", "4" => "red.png");
				$connection = mysql_connect($host, $user, $password);
				mysql_select_db($taulu, $connection);
				$haku = mysql_query("SELECT * FROM ".$usertaulu." WHERE id = {$id}", $connection);
				$found = 0;
				for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
					if (empty($_SESSION['g_tunnus'])){
						echo '<a href="index.php">Kirjaudu sisään!</a><br /><br />';
					}
					$kuvaus = mysql_result($haku, $i, "kuvaus");
					if ($kuvaus == "") { $kuvaus = "-"; }
					echo '<table><tr><td><b>Nimi:</b></td><td>'.mysql_result($haku, $i, "name").'</td></tr>
					<tr><td><b>Rekisteröitynyt:</b></td><td>'.date("j.n.Y G:i", mysql_result($haku, $i, "time")).'</td></tr>
					<tr><td><b>Käyttäjätaso:</b></td><td>'.$taso[mysql_result($haku, $i, "rights")];
					if (mysql_result($haku, $i, "rights") != 0 && mysql_result($haku, $i, "rights") != 10) {
						echo '<img alt="käyttäjän oikeudet" src="img/em/circle/16/'.$timg[mysql_result($haku, $i, "rights")].'"></img>';
					}
					echo '</td></tr>
					<tr><td><b>Käyttäjän kuvaus:</b></td><td>'.$kuvaus.'</td></tr></table><br />';
					
					$ava = mysql_result($haku, $i, "avatar");
					if ($ava == "") { $ava = "img/avatar_no.png"; }
					echo '<img src="'.$ava.'" alt="käyttäjän avatar"></img><br /><br />';
					
					echo '<b>Käyttäjän yhteisöt</b> ';
					$hakuasdi = mysql_query("SELECT * FROM groupmembers WHERE name = {$id}", $connection);
					for ($z = 0; $z < mysql_num_rows($hakuasdi); $z++) { 
						if ($z >= 1) {
							echo ', ';
						}
						echo '<a href="index.php?act=grouppage&id='.mysql_result($hakuasdi, $z, "grouppi").'">'.GetGroupFieldById(mysql_result($hakuasdi, $z, "grouppi"), "name").'</a>';
						if (mysql_result($hakuasdi, $z, "rights") >= 1) {
							echo '<img alt="yhteisöadmin" src="img/star/16/red.png"></img>';
						}
					}
					if ($z == 0) { echo '<br />Käyttäjä ei ole yhdessäkään yhteisössä!'; }
					echo '<br /><br />';
					
					echo '<b>Käyttäjän albumit:</b><br />';
					$haku2 = mysql_query("SELECT * FROM albums WHERE owner = {$id}", $connection);
					$c = 0;
					for ($a = 0; $a < mysql_num_rows($haku2); $a++) { 
						echo '<a href="index.php?act=album&id='.mysql_result($haku2, $a, "id").'"><img alt="albumi" src="img/misc/16/folder.png"></img>'.mysql_result($haku2, $a, "name").'</a><br />';
						$c++;
					}
					if ($c == 0) {
						echo 'Käyttäjällä ei ole albumeja!<br \>';
					}
					if ($id == $userid) {
						echo '<br /><a href="index.php?act=album&create=true"><img alt="luo albumi" src="img/misc/16/plus.png"></img>Luo uusi albumi</a>';
					}
					if (!empty($_SESSION['g_tunnus'])){
						echo '<br /><br /><a href="index.php?act=messages&do=write&nick='.GetFieldById($id, "name").'"><img src="img/misc/16/edit.png"></img>Lähetä viesti</a> ';
						if ($id != $userid) {
							$checkquery = mysql_query("SELECT * FROM friendinvites WHERE (name = '$userid' OR name = '$id') AND (name2 = '$id' OR name2 = '$userid')");
							echo '<img src="img/misc/16/addfriend.png"></img>';
							if (mysql_num_rows($checkquery) > 0) {
								echo 'Viimeisintä kaveripyyntöä ei ole vielä käsitelty!';
							}else{
								$checkquery2 = mysql_query("SELECT * FROM friends WHERE name = '$userid' AND name2 = '$id'");
								if (mysql_num_rows($checkquery2) > 0) {
									echo 'Sinä ja '.GetFieldByID($id, "name").' olette kavereita!';
								}else{
									echo '<a href="index.php?act=friends&do=invite&user='.$id.'">Pyydä kaveriksi</a>';
								}
							}
						}
					}
					$found++;
				}
				if ($found == 0){
					echo 'Käyttäjää ei löytynyt!';
				}
				mysql_close($connection);
				
			//album	
			}elseif($act == "album"){
				$create = $_GET['create'];
				$userid = GetIdByName($_SESSION['g_tunnus']);
				if ($create == "true") {
					echo '<a href="index.php?act=userpage&id='.$userid.'">Peruuta</a><br /><br />';
					$sub = $_POST['send'];
					if (!isset($sub)) {
						echo '<form action="index.php?act=album&create=true" method="post"><div><b>Albumin nimi:</b> <input type="text" name="nimi" /><br /><input type="submit" name="send" value="Luo albumi!" /></div></form>';
					}else{
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku = mysql_query("SELECT * FROM albums WHERE owner = ".$userid, $connection);
						$rivit = mysql_num_rows($haku);
						mysql_close($connection);
						if ($rivit > 4) {
							echo 'Et voi luoda enää albumia, maksimimäärä on 5!<br /><a href="index.php?act=userpage&id='.$userid.'">Palaa omalle sivullesi</a>';
						}else{
							$neim = $_POST['nimi'];
							if (strlen($neim) >= 51) {
								echo 'Albumin nimi on liian pitkä¡';
							}else{
								if (createAlbum($userid, htmlspecialchars($_POST['nimi']))) {
									echo 'Albumi luotu onnistuneesti!<br /><a href="index.php?act=userpage&id='.$userid.'">Palaa omalle sivullesi</a>';
								}
							}
						}
					}
				}elseif(!isset($create)){
					$do = $_GET['do'];
					$id = intval(mysql_escape_string($_GET['id']));
					$userid = GetIdByName($_SESSION['g_tunnus']);
					if (!isset($do)) {
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku = mysql_query("SELECT * FROM albums WHERE id = ".$id, $connection);
						for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
							echo '<a href="index.php?act=userpage&id='.mysql_result($haku, $i, "owner").'">Takaisin</a><br /><h2>'.mysql_result($haku, $i, "name").'</h2><b>Omistaja:</b> '.GetFieldById(mysql_result($haku, $i, "owner"), "name").'<br /><br />';
							$haku2 = mysql_query("SELECT * FROM images WHERE album = ".$id, $connection);
							$c = 0;
							for ($a = 0; $a < mysql_num_rows($haku2); $a++) { 
								echo '<a href="index.php?act=image&id='.mysql_result($haku2, $a, "id").'"><img alt="pikkukuva" src="'.LataaPikkuKuva(mysql_result($haku2, $a, "imgname"), 100, 100).'"></img></a> ';
								$c++;
							}
							if ($c == 0) {
								echo 'Tässä albumissa ei ole kuvia!<br />';
							}
							if (mysql_result($haku, $i, "owner") == $userid) { 
								echo '<br /><br /><a href="index.php?act=album&id='.$id.'&do=upload"><img alt="upload" src="img/misc/16/up.png"></img>Lisää kuva</a> <a href="index.php?act=album&id='.$id.'&do=del"><img alt="poista albumi" src="img/misc/16/X.png"></img>Poista albumi</a>';
							}
						}
						mysql_close($connection);
					}elseif($do == "del"){
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku = mysql_query("SELECT * FROM albums WHERE id = ".$id, $connection);
						for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
							$haku3 = mysql_query("SELECT * FROM images WHERE album = ".$id, $connection);
							for ($c = 0; $c < mysql_num_rows($haku3); $c++) {
								unlink("uploads/".mysql_result($haku3, $c, "imgname"));
								$haku2 = mysql_query("DELETE FROM images WHERE id = ".mysql_result($haku3, $c, "id"), $connection);
							}
							if (mysql_result($haku, $i, "owner") == $userid) {
								$haku2 = mysql_query("DELETE FROM albums WHERE id = ".$id, $connection);
								echo 'Albumi poistettu onnistuneesti! <a href="index.php?act=userpage&id='.$userid.'">Takaisin omalle sivullesi</a>';
							}else{
								echo 'Et omista tätä albumia!';
							}
						}
					}elseif($do == "upload"){
						// Olen kyllästynyt huonoihin uppiscriptoihin, jotka sallivat
						// php-scriptojen uppaamisen jne.

						/*
						* Copyright (c) 2006 Frozenball <orkkiolento@gmail.com>
						* All rights reserved.
						* Redistribution and use in source and binary forms, with or without
						* modification, are permitted provided that the following conditions are met:
						*
						*     * Redistributions of source code must retain the above copyright
						*       notice, this list of conditions and the following disclaimer.
						*     * Redistributions in binary form must reproduce the above copyright
						*       notice, this list of conditions and the following disclaimer in the
						*       documentation and/or other materials provided with the distribution.
						*     * Names of its contributors may not be used to endorse or promote products
						*       derived from this software without specific prior written permission.
						*
						* THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND ANY
						* EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
						* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
						* DISCLAIMED. IN NO EVENT SHALL THE REGENTS AND CONTRIBUTORS BE LIABLE FOR ANY
						* DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
						* (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
						* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
						* ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
						* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
						* SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
						*/

						// Asetukset
						$conf['filetypes'] = array("jpg","jpeg","png");
						$conf['max_filesize'] = 300; // Max. tiedoston koko Kt. Eli 1024 = 1MB, 2048 = 2MB.
						$conf['kansio'] = "uploads/"; // Kansio minne kaikki laitetaan.
						$conf['fix_pahat_kirjaimet'] = true; // Muuttaa å¥«kò³¥´ aakkosiksi, poistaa ()[] jne.
						$conf['polku'] = ""; // Laitetaan alkuun joku osoite. Älä muuta jos et tiedä¡­mitä olet tekemässä
						$conf['banned_ips'] = array("123.123.123.123","123.123.123.123"); // IP:t, jotka eivät voi lisätä tiedostoja.
						$conf['imagecheck'] = true; // Tarkistaa onko kyseessä oikeasti kuva, jos tiedostopääte on GIF, JPG, PNG, SWF, SWC, PSD, TIFF, BMP, IFF, JP2, JPX, JB2, JPC, XBM tai WBMP.
						$conf['nayta_tiedostot'] = true; // Näyttää listauksen upatuista tiedostoista
						$conf['session'] = "upload"; // SESSION:in nimi. Ei tarvitse muuttaa, jos et tajua sitä
						$conf['charset'] = "ISO-8859-15"; // Merkistö - yleensä ISO-8859-15

						// Itse scripta. Älä koske enää ;)

						// Tarvittavat functiot
						function humanread($bytes) {
							$pp = array("B","KB","MB","GB","TB");
							$menossa = 0;
							while ($bytes >= 1024) {
								$menossa++;
								$bytes = $bytes / 1024;
							}   
							return round($bytes,2).$pp[$menossa];
						}

						function msgdie($msg,$error=false) {
							echo $msg;
						exit();

						}

						// Käydään bannatut IP:t läpi
						foreach ($conf['banned_ips'] as $ip) {
							if ($_SERVER['REMOTE_ADDR'] == $ip) msgdie("Olet bannattu eli sinulla on porttikielto.",true);
						}
						// Jos uppaus?
						if (is_numeric($_POST['upload'])) {

							// Aikatarkistukset
							if (strlen($_POST['upload']) > (strlen(time()) * 100)) msgdie("Jotain ihmeellistä on tapahtunut...",true);
							if ($_POST['upload'] > time()) msgdie("Olet etuajassa.",true);
							if ($_SESSION[$conf['session']] == $_POST['upload']) msgdie("Olet nähtävästi yrittänyt painaa Refresh nappulaa. Paina mielummin <a href='".$_SERVER['PHP_SELF']."'>tätä</a>.",true);
						   
							// Tiedostolle tarkistukset
							if ($_FILES['file']['size'] > ($conf['max_filesize'] * 1024)) msgdie("Tiedosto on liian suuri. Kokorajoitus on ".humanread($conf['max_filesize'] * 1024),true);
							if ($_FILES['file']['size'] == 0) msgdie("Yritätkö edes lähettää tiedostoa...?",true);
						   
							// Tiedostonpå¥´e
							list($tiedoston_paate) = array_reverse(explode(".",$_FILES['file']['name']));
							$tiedoston_paate = strtolower($tiedoston_paate);
							if ($tiedoston_paate == "") msgdie("Tällä tiedostolla ei ole tiedostonpäätettä tai jotakin ihmeellistä tapahtui.<br /><br />Palaa <a href='".$_SERVER['PHP_SELF']."'>tästä</a> takaisin.",true);
							$sallitaanko = false;
							foreach ($conf['filetypes'] as $sallittu) {
								if ($tiedoston_paate == strtolower($sallittu)) $sallitaanko = true;   
							}   
							if ($sallitaanko == false) msgdie("Tiedostonpääte ".$tiedoston_paate." ei ole sallittu.<br /><br />Palaa <a href='".$_SERVER['PHP_SELF']."'>tästä </a> takaisin.",true);
						   
							// Tarkistus
							if ($conf['imagecheck'] == true) {
								#GIF, JPG, PNG, SWF, SWC, PSD, TIFF, BMP, IFF, JP2, JPX, JB2, JPC, XBM tai WBMP
								$tuetut = array("gif","jpg","jpeg","png","swf","swc","psd","tiff","bmp","iff","jp2","jpx","jb2","jpc","xbm","xbmp");
								if (in_array($tiedoston_paate,$tuetut)) {
									if (!@getimagesize($_FILES['file']['tmp_name'])) msgdie("Tiedostopääte ".$tiedoston_paate." ei ole oikea. Olet saattanut nimetä esimerkiksi zip tiedoston tiedostopäätteen.<br /><br />Palaa <a href='".$_SERVER['PHP_SELF']."'>tästä </a> takaisin.",true);
								}
							}


							if (!is_dir($conf['kansio'])) { @mkdir($conf['kansio']); }
							// Nyt on siirron aika
							if (!is_dir($conf['kansio'])) { @mkdir($conf['kansio']) or msgdie("Kansiota ".$conf['kansio']." ei ole olemassa.",true); }
							$tiedoston_nimi_orginal = htmlspecialchars(basename($_FILES['file']['name']));



							// ÅKK×KORJAUS
							if ($conf['fix_pahat_kirjaimet'] == true) {
								$tiedoston_nimi_orginal = str_replace(array("ä","ö","å","Ä","Ö","Å"," ", ";", ":", "\\", "&", "#"),array("a","o","a","A","O","A","_", "", "", "", "", ""),$tiedoston_nimi_orginal);
								$tiedoston_nimi_orginal = preg_replace("[^a-zA-Z0-9]","",$tiedoston_nimi_orginal);
							}
							
							
							// Harvinaista - mutta mahdollista
							if ($tiedoston_nimi_orginal == "") { $tiedoston_nimi_orginal = base64_encode(basename($_FILES['file']['name']));  }

							$tiedoston_nimi = $tiedoston_nimi_orginal;

							$i = 1;
							while (is_file($conf['kansio'].$tiedoston_nimi)) {
								// Tiedosto on jo olemassa, eli keksitå¥® uusi nimi
								$halkaistu = explode(".",$tiedoston_nimi_orginal);
								$halkaistu[0] = $halkaistu[0]."_".$i;
								$tiedoston_nimi = implode(".",$halkaistu);
								$i++;
							}
							
							$connection = mysql_connect($host, $user, $password);
							mysql_select_db($taulu, $connection);
							$haku = mysql_query("SELECT * FROM albums WHERE id = ".$id, $connection);
							for ($i = 0; $i < mysql_num_rows($haku); $i++) { 
								if (mysql_result($haku, $i, "owner") != $userid) {
									msgdie("Et omista tätä albumia!");
								}
							}
							
							$kuvaus = htmlspecialchars($_POST['kuvaus']);
						    if (strlen($kuvaus) >= 201) { 
								msgdie("Kuvaus on liian pitkä");
							}
							if (@move_uploaded_file($_FILES['file']['tmp_name'], $conf['kansio']."/".$tiedoston_nimi)) {
								$polku = $conf['polku'].$conf['kansio'].$tiedoston_nimi;
								if ($conf['polku'] != "") $polku = $conf['polku'].$tiedoston_nimi;
							   
								$_SESSION[$conf['session']] = $_POST['upload'];
																
								$connection = mysql_connect($host, $user, $password);
								mysql_select_db($taulu, $connection);
								$haku = mysql_query("INSERT INTO images (id, kuvaus, imgname, album) VALUES ('', '{$kuvaus}', '$tiedoston_nimi', '{$id}');", $connection);
								mysql_close($connection);
								
								msgdie("Tiedosto ".$tiedoston_nimi." on siirretty onnistuneesti.<br /><br /><a href='index.php?act=album&id={$id}'>Palaa albumiin</a>");
							} else {
								msgdie("Tiedoston siirto ei onnistunut. Tämä johtuu mm. seuraavista syistä<br />* Tällä scriptillä ei ole oikeuksia upload kansioon.<br /><br />Palaa <a href='".$_SERVER['PHP_SELF']."'>tästä </a> takaisin.",true);
							}


						}else{
							echo '<a href="index.php?act=album&id='.$id.'">Peruuta</a><br /><br />';
						}
						echo '<form action="index.php?act=album&id='.$id.'&do=upload" method="POST" enctype="multipart/form-data">
						<input type="hidden" name="upload" value="'.time().'">
						<label for="file">Tiedosto:</label>
						<input type="file" name="file" id="file">
						<br />
						Sallitut tiedostopäätteet '.implode(", ",$conf['filetypes']) .' ja suurin sallittu tiedostonkoko on '.humanread($conf['max_filesize'] * 1024).'<br /><br />
						Kuvaus: <input type="text" name="kuvaus" /><br />Kuvaus voi olla maksimissaan 200 merkkiä pitkä <br /><br />
						<input type="submit" name="submit" id="submit" value="Lisää kuva"></form>';
					}
					
					///////
					//upload loppui
				}else{
					echo 'Epic fail';
				}
				
			//image
			}elseif($act == "image"){
				$id = intval(mysql_escape_string($_GET['id']));
				$do = $_GET['do'];
				$userid = GetIdByName($_SESSION['g_tunnus']);
				if (!isset($do)) {
					$connection = mysql_connect($host, $user, $password);
					mysql_select_db($taulu, $connection);
					$kommentointi = $_GET['comment'];
					if ($kommentointi == "true") {
						if (!empty($_SESSION['g_tunnus'])) {
							$kom = htmlspecialchars($_POST['kommentti']);
							if (strlen($kom) >= 201) {
								echo 'Kommentti oli liian pitkä!';
							}else{
								if (isset($kom)) {
									$haku4 = mysql_query("INSERT INTO imagecomments (id, sender, msg, time, img) VALUES ('', '".$userid."', '$kom', '".time()."', '$id');", $connection);
								}
							}
						}
					}
					$haku2 = mysql_query("SELECT * FROM images WHERE id = ".$id, $connection);
					for ($a = 0; $a < mysql_num_rows($haku2); $a++) { 
						echo '<a href="index.php?act=album&id='.mysql_result($haku2, $a, "album").'">Takaisin albumiin</a><br /><br />';
						$haku = mysql_query("SELECT * FROM albums WHERE id = ".mysql_result($haku2, $a, "album"), $connection);
						for ($b = 0; $b < mysql_num_rows($haku); $b++) { 
							if (mysql_result($haku, $b, "owner") == $userid) {
								echo '<a href="index.php?act=image&id='.$id.'&do=del"><img alt="poista kuva" src="img/misc/16/X.png"></img>Poista kuva</a> <a href="index.php?act=image&id='.$id.'&do=move"><img alt="siirrä kuva" src="img/misc/16/move.png"></img>Siirrä kuva</a> <a href="index.php?act=image&id='.$id.'&do=description"><img alt="muokkaa kuvausta" src="img/misc/16/edit.png"></img>Muokkaa kuvausta</a><br /><br />';
							}
						}
						$kuvaaus = mysql_result($haku2, $a, "kuvaus");
						if ($kuvaaus == "") { $kuvaaus = "-"; }
						echo '<a href="uploads/'.mysql_result($haku2, $a, "imgname").'"><img alt="Klikkaa näyttääksesi alkuperäisen kuvan" title="Klikkaa näyttääksesi alkuperäisen kuvan" src="'.LataaPikkukuva(mysql_result($haku2, $a, "imgname"), 800, 800, "bigies").'"></img></a><br /><img src="img/misc/16/edit.png"></img> <b>Kuvaus:</b> '.$kuvaaus.'<br /><br />';
						if (empty($_SESSION['g_tunnus'])) {
							echo 'Vain rekisteröityneet käyttäjät voivat kommentoida muiden kuvia ja lukea muiden kommentteja!';
						}else{
							echo '<img src="img/misc/16/speech.png"></img> <b>Kommentit:</b><br /><table>';
							$haku3 = mysql_query("SELECT * FROM imagecomments WHERE img = ".$id." ORDER BY id", $connection);
							$x = 0;
							for ($d = 0; $d < mysql_num_rows($haku3); $d++) { 
								echo '<tr><td><span class="timestamp">['.date("d.m.Y G:i", mysql_result($haku3, $d, "time")).']</span></td><td><b><a href="index.php?act=userpage&id='.mysql_result($haku3, $d, "sender").'">'.GetFieldById(mysql_result($haku3, $d, "sender"), "name").'</a>:</b> '.mysql_result($haku3, $d, "msg").'</td></tr>';
								$x++;
							}
							if ($x == 0) {
								echo 'Ei kommentteja!';
							}
							echo '</table><br /><form action="index.php?act=image&id='.$id.'&comment=true" method="post"><div><input type="text" name="kommentti" /> <input type="submit" value="Kommentoi!" /></div></form>';
						}
					}
					mysql_close($connection);
				}elseif($do == "del"){
					$connection = mysql_connect($host, $user, $password);
					mysql_select_db($taulu, $connection);
					$haku2 = mysql_query("SELECT * FROM images WHERE id = ".$id, $connection);
					for ($a = 0; $a < mysql_num_rows($haku2); $a++) { 
						$haku = mysql_query("SELECT * FROM albums WHERE id = ".mysql_result($haku2, $a, "album"), $connection);
						for ($b = 0; $b < mysql_num_rows($haku); $b++) { 
							if (mysql_result($haku, $b, "owner") == $userid) {
								unlink("uploads/".mysql_result($haku2, 0, "imgname"));
								$haku3 = mysql_query("DELETE FROM images WHERE id = ".$id, $connection);
								$haku4 = mysql_query("DELETE FROM imagecomments WHERE img = ".$id, $connection);
								echo 'Kuva poistettu onnistuneesti! <a href="index.php?act=album&id='.mysql_result($haku2, $a, "album").'">Takaisin albumiin</a>';
							}else{
								echo 'Et omista tätä albumia!';
							}
						}
					}
					mysql_close($connection);
				}elseif($do == "move"){
					$place = $_GET['to'];
					if (!isset($place)) {
						echo '<a href="index.php?act=image&id='.$id.'">Peruuta</a><br /><br />';
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku2 = mysql_query("SELECT * FROM albums WHERE owner = ".$userid, $connection);
						echo '<b>Valiste albumi johon kuva siirretään:</b><br /><br />';
						for ($a = 0; $a < mysql_num_rows($haku2); $a++) { 
							echo mysql_result($haku2, $a, "name").' <a href="index.php?act=image&id='.$id.'&do=move&to='.mysql_result($haku2, $a, "id").'"><img alt="ok!" src="img/misc/16/ok.png"></img></a><br />';
						}
						mysql_close($connection);
					}else{
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku2 = mysql_query("SELECT * FROM images WHERE id = ".$id, $connection);
						for ($a = 0; $a < mysql_num_rows($haku2); $a++) { 
							$haku = mysql_query("SELECT * FROM albums WHERE id = ".$place, $connection);
							$haku3 = mysql_query("SELECT * FROM albums WHERE id = ".mysql_result($haku2, $a, "album"), $connection);
							if ($userid == mysql_result($haku, 0, "owner") and mysql_result($haku3, 0, "owner") == $userid) {
								$haku = mysql_query("UPDATE images SET album = {$place} WHERE id = '".$id."';", $connection);
								echo 'Kuva siirretty! <a href="index.php?act=userpage&id='.$userid.'">Takaisin omalle sivullesi</a>';
							}else{
								echo 'Et omista tätä albumia!';
							}
						}
						mysql_close($connection);
						
					}
				}elseif($do == "description"){
					$but = $_POST['buttoni'];
					if (!isset($but)) {
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku2 = mysql_query("SELECT * FROM images WHERE id = ".$id, $connection);
						echo '<a href="index.php?act=image&id='.$id.'">Peruuta</a><br /><br /><form action="index.php?act=image&id='.$id.'&do=description" method="post"><div>Kuvaus: <input type="text" name="desc" value="'.mysql_result($haku2, 0, "kuvaus").'" /><br /><input type="submit" name="buttoni" value="Muokkaa" /></div></form>';
						mysql_close($connection);
					}else{
						$uuskuvaus = htmlspecialchars($_POST['desc']);
						$connection = mysql_connect($host, $user, $password);
						mysql_select_db($taulu, $connection);
						$haku2 = mysql_query("SELECT * FROM images WHERE id = ".$id, $connection);
						for ($a = 0; $a < mysql_num_rows($haku2); $a++) { 
							$haku = mysql_query("SELECT * FROM albums WHERE id = ".mysql_result($haku2, $a, "album"), $connection);
							if ($userid == mysql_result($haku, 0, "owner")) {
								if (strlen($uuskuvaus) >= 201) { 
									echo 'Kuvaus on liian pitkä¡ <a href="index.php?act=image&id='.$id.'">Takaisin</a>';
								}else{
									$haku = mysql_query("UPDATE images SET kuvaus = '$uuskuvaus' WHERE id = '$id';", $connection);
									echo 'Kuvaus päivitetty! <a href="index.php?act=image&id='.$id.'">Takaisin</a>';
								}
							}else{
								echo 'Et omista tätä albumia!';
							}
						}
						mysql_close($connection);
					}
				}

			
			//404
			}else{
				if (!isset($_SESSION['g_tunnus'])) {
					if ( $failausmaximus == "0" ) echo 'Tämä sivu on vain rekisteröityneille käyttäjille tai sitä ei ole olemassa! Ole hyvä ja <a href="index.php">kirjaudu sisään</a> tai <a href="index.php?reg=1">luo tunnus!</a>';
				}
			}
			?>
				</div>
				<div id="footer">
				
				<!-- Copyright -->
				
				Copyright TheDuck 2010 &copy; <span class="minitext">(Grafiikka ja ulkoasu by Esa) (Ajax ja JS by tuhoojabotti) || <a href="http://urli.net">Urli.net</a> || v. 2.2</span>
				
				<!-- Copyright loppuu / Copyright ends -->
				
				</div>
			
			</div>
		
		</div>
	
		<?php
		if (!isset($act)) {
			echo '<script type="text/javascript">
					//Setup some globals
					var NewestPostID = 0;
					var PostUpdateDelay = 1000;
					var OnlineUsers = 0;
					var OnlineUpdateDelay = 10000;
					
					//Once the document is ready, as in loaded.
					$(document).ready(function(){
						//Request to see what the current post ID is.
						$.get(\'chat.php?id=9999999999\',
							function(data) {
								$("#tsat").prepend(data); //Add the data to the chat.
								NewestPostID=$("#newid").text(); //Read the data from the chat.
								$("#newid").remove(); //Delete the data from the chat.
								$("#form_img").fadeOut("slow"); //After success, fadeOut loader gif.
								document.getElementById("form_submit").disabled=""; //Enable sending messages once the new ID is claimed.
								updateMessages(); //Update messages, this must be called once after the ID is claimed, then it continues to call itself over and over again!
							}
						);
						updateOnline(); //Update online users amount
						$("#tsat").html(linkify($("#tsat").html()));
					});
					//Setup a click event for the submit button
					$("#form_submit").click(function(event) {
						event.preventDefault(); //Stop the submit button from doing what it should do.
						document.getElementById("form_submit").disabled="disabled"; //Disable button, so that no one shouldn\'t send double messages by accident.
						$msg=document.getElementById("form_text").value; //Save the message!
						document.getElementById("form_text").disabled="disabled"; //Disable text editing while sending
						$("#form_img").stop(true, true).fadeIn("fast"); //fadeIn a nice loading gif
						//POST the message!
						$.post("index.php?do=comment",
								{"msg":$msg}, //Serialize form data!
								function(data){
									$("#form_img").fadeOut("slow"); //after success, fadeOut loader gif.
									document.getElementById("form_submit").disabled=""; //Enable submit button again.
									document.getElementById("form_text").value=""; //Empty the the text form.
									document.getElementById("form_text").disabled=""; //Enable again!
								}
						);
					});
					//This function updates new messages to the chat AND loads the newest post ID.
					function updateMessages(){
						$.get(\'chat.php?id=\'+NewestPostID,
							function(data) {
								$("#tsat").prepend(linkify(data)); //apply new messages to the chat
								NewestPostID=$("#newid").text(); //read the newest ID
								$("#newid").remove(); //delete the newest ID data from the chat
								setTimeout("updateMessages()",PostUpdateDelay); //update again later
							}
						);
					}
					//This function updates the amount of people online.
					function updateOnline(){
						$.get("users.php",
							function(data) {
								OnlineUsers=parseInt(data);
								if(isNaN(OnlineUsers)){
									$("#users_online").html("<b>"+"?"+"</b> käyttäjää <a href=\'index.php?act=online\'>online!</a>");
									setTimeout("updateOnline()",500); //update!!
									return false;
								}
								if(OnlineUsers<2){
									PostUpdateDelay = 3000; //No one here, we don\'t need to update messages so fast...
									$("#users_online").html("<b>"+OnlineUsers+"</b> käyttäjä <a href=\'index.php?act=online\'>online!</a>");
								}else{
									PostUpdateDelay = 1000; //Woot, people here, let\'s update chat faster!
									$("#users_online").html("<b>"+OnlineUsers+"</b> käyttäjää <a href=\'index.php?act=online\'>online!</a>");
								}
								setTimeout("updateOnline()",OnlineUpdateDelay); //update again later
							}
						);
					}
				</script>';
		}
		?>
	</body>
</html>
<?php ob_end_flush(); ?>
