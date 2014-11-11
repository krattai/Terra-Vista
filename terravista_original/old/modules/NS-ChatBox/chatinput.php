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

global $user;
if(isset($user)) {
	$user2 = base64_decode($user);
	$cookie = explode(":", $user2);
}

function insertChat($username, $message, $dbname) {
global $user, $prefix;
if(isset($user)) {
	$user2 = base64_decode($user);
	$cookie = explode(":", $user2);
}
?>
<html>

<head><title>chat input</title>
<link rel="stylesheet" href="modules/NS-ChatBox/chatbox.css">
</head>

<body bgcolor="#EEEEEE" topmargin="0" bottommargin="0" leftmargin="0">

<?php
	$username = stripslashes(FixQuotes($username));
	$message = stripslashes(FixQuotes(strip_tags(trim($message))));
	$ip = getenv("REMOTE_HOST");
	if (empty($ip)) {
		$ip = getenv("REMOTE_ADDR");
	}
	$messagearray = explode(" ", $message);
	foreach ($messagearray as $value) {
		//print("strlen:  " . strlen($value));
		if (strlen($value) >= 22) {
			$message = "";
			break;
		}
	}
	//print("1. |".$username."|<br>");
	if ($dbname == 0) { // if the username cookie didn't supply the name.
		$result = mysql_query("SELECT uname FROM $prefix"._users." WHERE uname = \"".trim($username)."\"");
		$thing = mysql_fetch_array($result);
		if (isset($thing["username"])) { // username is in the users db, sorry.
			$username = "";
		}
	}

	//print("2. |".$username."|<br>");
	if (!checkForDuplicate($message) && $message != "") {
		$result = mysql_query("INSERT INTO $prefix"._chatbox." VALUES ('".$username."', '".$ip."', '".$message."', '".time()."', '', ".$dbname.")");
		/*if (!$result) {
			print(mysql_errno(). ": ".mysql_error(). "<br>");
		}*/
	}
?>
<form name="form" action="modules.php" method="post">
<input type="hidden" name="op" value="modload">
<input type="hidden" name="name" value="NS-ChatBox">
<input type="hidden" name="file" value="chatinput">

<table width="100%">

<?php

if (!isset($cookie[1])) {
	print("	<tr>
		<td align=\"right\"><font class=\"catdesc\">Name:</font></td>
		<td><input type=\"text\" maxlength=\"20\" name=\"name\" tabindex=\"0\" value=\"".((isset($nameinput))?($nameinput):($REMOTE_ADDR))."\"></td>
		<td><font face=\"tahoma\" size=\"1\" color=\"black\">I see you are not member.<br><a href=\"user.php?op=register\" target=\"_blank\">Register Now!</a></font></td>
	</tr>");
} else {
print("<tr><td colspan=\"3\"><font class=\"catdesc\">You are logged in as<b> '".$cookie[1]."'.</b></font></td></tr>");
}

?>
	
<tr>
<td align="right" valign="top"><font class="catdesc">Text:</font></td>
<td><input type="text" maxlength="120" size="40" name="chat" tabindex="0">
    <input type="submit" value="Submit" tabindex="0"><br>
    <font class="catdesc">You can also use <a href="modules.php?op=modload&amp;name=Forum&amp;file=bb_smilies" target="_blank" class="addon">smiles</a></td>
	<td valign="top" align="center"><font face="tahoma" size="1" color="black">Addon by istrigo from <a href="http://www.thebix.com" class="addon">TheBix.com</a><br>
	Part of the <a href="http://www.nukeaddon.com" class="addon">NukeAddOn.com</a> Team</font></td></tr>

</table>


</form>

<script type="text/javascript">
<!--
document.form.chat.focus();
//-->
</script>

</body>
</html>
<?
}

function checkForDuplicate($message) {
	global $prefix;
	$result = mysql_query("SELECT message FROM $prefix"._chatbox." ORDER BY date DESC LIMIT 10");
	//$it = mysql_fetch_array($result);
		// make it where it checks the last 10 or so quotes for dupes (just to be sure).
	$it = mysql_fetch_array($result);
	$i = 0;
	while($i < 10) {
		//print("|" . $message . "|<br>|" . FixQuotes($it["message"]) . "|<br>");
		if ($message == FixQuotes($it["message"]))
			return true;
		$it = mysql_fetch_array($result);
		$i++;
	}
	return false;
}

switch($func) {

	default:
		if (!isset($cookie[1]) && isset($nameinput)) {
			$uname = $nameinput;
			$dbname = 0;
		} else {
			$uname = $cookie[1];
			$dbname = 1;
		}
	insertChat($uname, $chat, $dbname);
	break;

}

?>