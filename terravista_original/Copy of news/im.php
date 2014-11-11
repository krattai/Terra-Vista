<?php
######################################################################
# PHP-NUKE Add-On 3.0.BETA : 
# ==========================
#
#
#	Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2000 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#                       Hutdik Hermawan AKA hotFix (hutdik76@hotmail.com)
#						Heinrich Steenberg AKA KodeWulf
#
# http://www.nukeaddon.com
#
#
######################################################################
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################
# Special thanks to:
# Jscript refresh bugs come from Pipolo and 
# Jscript Netscape fixed come from CHristian Niss 
######################################################################

$nocache = 1;

if (!IsSet($mainfile)) { include ("mainfile.php"); }
echo "<HTML><HEAD>";
cookiedecode($user);

if ($action == "Check IM") {

echo "<script  type=\"text/javascript\">\n";
echo "<!--\n";
echo "function IM(IM) { var MainWindow = window.open (IM, \"_blank\",\"width=360,height=200,toolbar=no,location=no,menubar=no,scrollbars=no,resizeable=no,status=no\");}\n";
echo "//-->\n";
echo "</script>";

echo "<title>PHP Nuke IM</title>\n</HEAD>\n";
echo "<body onload=\"setInterval('self.location.reload()',20000)\" bgcolor=\"#ffffff\" text=\"#003300\" link=\"#CC3333\" alink=\"#999999\" vlink=\"#999999\">\n";

$expire = time()-432000;

$sql = mysql_query("DELETE FROM $prefix"._im." WHERE time < $expire");
$sql = mysql_query("SELECT * FROM $prefix"._im." WHERE username_to = '$cookie[1]'");

if ($row = mysql_num_rows($sql)) {

	while ($im = mysql_fetch_array($sql)) {

echo "<SCRIPT  type=\"text/javascript\">\n";
echo "<!--\n";
echo "var telwin = null;\n";
echo "telwin = open('im.php?action=read&IMID=$im[IMID]', '$im[time]', 'toolbars=0, scrollbars=1, location=0, statusbars=0, menubars=0, resizable=0, width=360, height=200');\n";
echo "//-->\n";
echo "</SCRIPT>\n\n";
	}
}
?>
<center>
<b>Keep this box open in order to receive messages.</b><br><br>
<small>To send a messages just click on the IM images next to the username</small><br><br>
</center>
<?
$sql = mysql_query("SELECT username FROM $prefix"._session." where guest=0");
$member_online_num = mysql_num_rows($sql);

$who_online_num = $member_online_num;

if ($member_online_num == "1"){
$ms = '';
}
else{
$ms = "s";
}

$who_online = "<center>$member_online_num member$ms online.</center><BR>\n";

$i = 1;
while ($session = mysql_fetch_array($sql)) {
	
	if ($session[guest] == 0) {
		if ($i == $who_online_num) {
			$who_online .= "<A href=\"javascript:IM('im.php?action=compose&to=$session[username]')\"><img src=\"images/im/im.gif\" border=0 alt=\"Send me an Instant Message\"></a> <a href=\"user.php?op=userinfo&uname=$session[username]\" target=\"main\">$session[username]</a>";
		} else {
			$who_online .= "<A href=\"javascript:IM('im.php?action=compose&to=$session[username]')\"><img src=\"images/im/im.gif\" border=0 alt=\"Send me an Instant Message\"></a> <a href=\"user.php?op=userinfo&uname=$session[username]\" target=\"main\">$session[username]</a><br>\n";
		}
	}

$i++;
}

echo "$who_online</body></HTML>";

} elseif ($action == "read") {

$sql = mysql_query("SELECT * FROM $prefix"._im." WHERE IMID=$IMID AND username_to='$cookie[1]'");
$im = mysql_fetch_array($sql);

$username_from = $im[username_from];
$subject = $im[subject];
$message = stripslashes(nl2br($im[message]));
$time = $im[time];

$date = date("F d, Y",$time+($timeoffset*3600));
$time = date("h:i A",$time+($timeoffset*3600));
?>

<html>
<head>

<?
echo"<title>Recieved IM From: $username_from</title>";
?>

</head>

<body bgcolor="#ffffff" text="#003300" link="#CC3333" alink="#999999" vlink="#999999">
<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td><font face="arial" size="2"><b>Sent at:</b></font></td><td><font face="arial" size="2"><? echo "$date ($time)"; ?></font></td></tr>
<tr><td><font face="arial" size="2"><b>From:</b></font></td><td><font face="arial" size="2"><? echo "$username_from"; ?></font></td></tr>
<tr><td valign="top"><font face="arial" size="2"><b>Subject:</b></font></td><td><font face="arial" size="2"><? echo"$subject"; ?></font></td></tr>
<tr><td valign="top"><font face="arial" size="2"><b>Message:</b></font></td><td><font face="arial" size="2"><? echo"$message"; ?></font></td></tr>
</table>
</td></tr>
</table>

<center><? echo "<a href=\"im.php?action=compose&to=$username_from\">"; ?><img src="images/im/sendim.gif" border=0></a>
<a href="javascript:window.close()"><img src="images/im/closeim.gif" border=0></a>
</center>

</body>
</html>

<?
$sql = mysql_query("DELETE FROM $prefix"._im." WHERE IMID = '$IMID'");


} elseif ($action == "compose") {

?>

<html>
<head>
<title>Compose IM</title>
</head>

<body bgcolor="#ffffff" text="#003300" link="#CC3333" alink="#999999" vlink="#999999">
<FORM METHOD=POST ACTION="im.php" TARGET=_self>

<center><font face="arial" size="2"><b><? echo"$message";?></b></font></center>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center">
<tr><td>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td><font face="arial" size="2"><b>To:</b></font></td><td><input type="TEXT" name="username_to" value="<? echo"$to"; ?>" size="30" maxlength="50"></td></tr>
<tr><td valign="top"><font face="arial" size="2"><b>Subject:</b></font></td><td><input type="TEXT" name="subject" size="30" maxlength="50"></td></tr>
<tr><td valign="top"><font face="arial" size="2"><b>Message:</b></font></td><td><textarea name="message" wrap="VIRTUAL" cols="26" rows="3"></textarea></td>
</tr>
</table>
</td></tr>
</table>
<br><center>
<input type=HIDDEN name="action" value="send">
<input type=image src="images/im/sendim.gif" border=0 width="77" height="23">
<INPUT type=image src="images/im/closeim.gif" border=0 width="77" height="23" onClick="window.close()">
</form>
</center>
</body>
</html>

<?
} elseif ($action == "send") {


$message = smile($message);
$sendmsg= addslashes($message);
$sql = mysql_query("INSERT INTO $prefix"._im." (username_to, username_from, time, subject, message) VALUES ('$username_to', '$cookie[1]', '" . time() ."', '" . addslashes($subject) . "', '$sendmsg')");

$message = "IM Sent!";
?>

<html>
<head>
<title>Compose IM</title>
</head>

<body bgcolor="#ffffff" text="#003300" link="#CC3333" alink="#999999" vlink="#999999">
<FORM METHOD=POST ACTION="im.php" TARGET="_self">

<center><b><? echo "$message";?></b</center>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#ffffff" align="center">
<tr>
<td>
<table width="100%" border="0" cellspacing="1" cellpadding="1">
<tr><td><font face="arial" size="2"><b>To:</b></font></td><td><input type="TEXT" name="username_to" value="<? echo"$to"; ?>" size="30" maxlength="50"></td></tr>
<tr><td valign="top"><font face="arial" size="2"><b>Subject:</b></font></td><td><input type="TEXT" name="subject" size="30" maxlength="50"></td></tr>
<tr><td valign="top"><font face="arial" size="2"><b>Message:</b></font></td><td><textarea name="message" wrap="VIRTUAL" cols="26" rows="3"></textarea></td></tr>
</table>
</td></tr>
</table>
<br><center>
<input type=HIDDEN name="action" value="send">
<input type=image src="images/im/sendim.gif" border=0 width="77" height="23">
<INPUT type=image src="images/im/closeim.gif" border=0 width="77" height="23" onClick="window.close()">
</form>
</center>
</body>
</html>

<?
} else {

}

exit;

?>