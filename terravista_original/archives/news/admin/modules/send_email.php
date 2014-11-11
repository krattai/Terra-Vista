<?PHP

######################################################################
# PHP-NUKE: Web Portal System
# ===========================
#
# Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)
# http://phpnuke.org
#
# This modules is the main administration part
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################
# PHP-NUKE Add-On 5.0.RC1 : Email Users AddOn
# ============================================
#
#              Email Users Add-On base on ThatWare admin script
# 			   Copyright (C) 2000  Thatware Development Team
# 			   http://atthat.com/
# 
# Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#	
# http://www.nukeaddon.com
#
#
#######################################################################
# Copyright (C) 2000  Thatware Development Team
# http://atthat.com/
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
###############################################################################

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$hlpfile = "manual/send_email.html";
$result = mysql_query("select radminsuper from $prefix"._authors." where aid='$aid'");
list($radminsuper) = mysql_fetch_row($result);
if ($radminsuper==1) {

/*********************************************************/
/* EMAIL USERS Block Functions                           */
/*********************************************************/

function EmailUser() {
   	global $hlpfile, $admin, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
	OpenTable();
	?>
	<center><font face="arial" size="4">Email User(s)</font></center>
	<form action="admin.php" method="post">
	<input type="hidden" name="op" value="send_email_to_user">
	<table border="0">
	<?
	echo"
	<tr><td> ".translate("Username:")." </td>
    <td><SELECT NAME=\"username\">";
    $result = mysql_query("select uid, uname from $prefix"._users." order by uname");
    while(list($uid, $uname) = mysql_fetch_row($result)) {
    echo "<OPTION VALUE=\"$uid\">$uname</OPTION>";
    }
    ?>
    </select></td></tr>
	<tr><td>&nbsp;</td><td><input type="checkbox" name="all" value="1"><?PHP echo "".translate("Email to all users").""; ?></td></tr>
	<tr><td><?php echo "".translate("Send by:").""; ?></td><td><input type="text" size="28" name="fromname"></td></tr>
	<tr><td><?php echo "".translate("Reply-to Address:").""; ?></td><td><input type="text" size="28" name="from"></td></tr>
	<tr><td><?php echo "".translate("Subject:").""; ?></td><td><input type="text" size="28" name="subject"></td></tr>
	<tr><td><?php echo "".translate("Message:").""; ?></td><td><textarea wrap="virtual" cols="42" rows="12" name="message"></textarea></td></tr>
	<tr><td>&nbsp;</td><td><INPUT type="submit" value="Send Mail"></td></tr>
	</table></form>
	<?
	CloseTable();
	include("footer.php");
}

function send_email_to_user($username, $fromname, $from, $subject, $message, $all) {
	global $prefix;
	if($all) {
		$result = mysql_query("SELECT DISTINCT email FROM $prefix"._users."");
	} else {
		$result = mysql_query("SELECT email FROM $prefix"._users." WHERE uid='$username'");
	}
	if(!$result) {
		echo mysql_errno(). ": ".mysql_error(). "<br>";
		exit();
	}
	for($i=mysql_num_rows($result); $i-1>0; $i--) {
		$db_info = mysql_fetch_row($result);
		$email .= "$db_info[0], ";
	}
	$db_info = mysql_fetch_row($result);
	$message = stripslashes($message);
	$subject = stripslashes($subject);
	$email .= "$db_info[0]";

	mail($from, $subject, $message, "From: \"$fromname\" <$from>\nBcc: $email\nX-Mailer: PHP/" . phpversion());
	Header("Location: admin.php?op=adminMain");
}


} else {
    echo "Access Denied";
}
?>