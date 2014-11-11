<?php

########################################################################
# PHP-NUKE Add-On 5.0 : ChatBox AddOn
# =========================================
#
#	Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#						Istrigo (TheBix.com)
#
# http://www.nukeaddon.com
# http://www.theBix.com
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.       
######################################################################

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

$index = 0;

/*function viewsmile($message) {
global $user,$prefix;
   if ($getsmiles = mysql_query("SELECT * FROM $prefix"._smiles."")){
      while ($smiles = mysql_fetch_array($getsmiles)) {
	 $message = str_replace($smiles[code], "<IMG SRC=\"images/forum/smilies/$smiles[smile_url]\">", $message);
      }
   }
   return($message);
}

if(isset($user)) {
	$user2 = base64_decode($user);
	$cookie = explode(":", $user2);
}*/

function printPage() {
echo "<link rel=\"stylesheet\" href=\"modules/NS-ChatBox/chatbox.css\">";
$thecolor = "green";

print("<body border=\"0\" onload=\"setInterval('self.location.reload()',7500)\" topmargin=\"0\" link=\"$thecolor\" vlink=\"$thecolor\" alink=\"$thecolor\">");

	global $admin,$user,$sitename,$prefix;
//	OpenTable();

	print("<br><center><font class=\"catdescb\"><b>Welcome to $sitename Chat BOX</font></b></center>");

	$result = mysql_query("SELECT DISTINCT ip FROM $prefix"._chatbox." WHERE date >= ".(time()-(60*5))."");
	$numofchatters = mysql_num_rows($result);
	print("<br><font size=1>There ");
	if ($numofchatters == 1) {
		print("is <font color=\"red\">1</font> person chatting right now.");
	} else {
		print("are <font color=\"red\">".$numofchatters."</font> people chatting right now.");
	}
	print("</font><br><hr size=\"1\" color=\"#EEEEEE\"");

	$thing = "";
	$result = mysql_query("SELECT username, message, ip, dbname FROM $prefix"._chatbox." ORDER BY date DESC LIMIT 20");
	while(list($username, $message, $ip, $dbname) = mysql_fetch_array($result)) {
		if ($dbname == 1) {
			$thing .= "<a href=\"user.php?op=userinfo&uname=".$username."\" target=\"_blank\">".$username."</a>";
		} else {
			$thing .= "<font color=\"blue\">".$username."</font>";
		}
		$message = smile($message);
		$thing .= "&gt;  " . $message . "<br>\n";
	}
	$thing .= "</font>";

	print($thing);

//	CloseTable();
//	include ('footer.php');
}


switch($func) {
	default:
	printPage();
	break;
}

?>
