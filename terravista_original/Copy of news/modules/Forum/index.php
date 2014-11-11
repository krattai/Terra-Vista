<?php

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
# PHP-NUKE Add-On 5.0 : PHPBB Forum AddOn
# ============================================
# 
# Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#						Hutdik Hermawan AKA hotFix (hutdik76@hotmail.com)
#
# http://www.nukeaddon.com
#
#
#######################################################################


if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

$index = 0;

function ForumIndex() {
global $user,$HTTP_COOKIE_VARS,$bgcolor1,$bgcolor2,$bgcolor3,$textcolor1,$textcolor2;
include("config.php");
include("functions.php");
include("auth.php");
include("header.php");

if(!$result = mysql_query("SELECT * FROM $prefix"._catagories." ORDER BY cat_id"))
    forumerror("0022");

OpenTable();
echo "<center><b>Welcome to $sitename Forums!</b><br><br>";
echo "Select forums with discussions of your interest, participate and have a lot of fun!<br>";
echo "The only rule here is: Stay on topic of the discussions.<br><br>";
echo "<font size=1>".translate("Today is ").date("l, Y-m-d")."<br>";
echo "".translate("Your last visit was on ").$userdata[lastvisit]."</font>";
CloseTable();
?>

<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="100%"><TR><TD>
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $bgcolor2?>" ALIGN="LEFT">
	<TD BGCOLOR="<?php echo $bgcolor2?>" ALIGN="CENTER" VALIGN="MIDDLE">&nbsp;</TD>
	<TD><FONT SIZE=1><B><?php echo translate("Forum")?></B></font></TD>
	<TD ALIGN="CENTER"><FONT SIZE=1><B><?php echo translate("Type")?></B></font></TD>
	<TD ALIGN="CENTER"><FONT SIZE=1><B><?php echo translate("Topics")?></B></font></TD>
	<TD ALIGN="CENTER"><FONT SIZE=1><B><?php echo translate("Posts")?></B></font></TD>
	<TD ALIGN="CENTER"><FONT SIZE=1><B><?php echo translate("Last Posts")?></B></font></TD>
	<TD><FONT SIZE=1><B><?php echo translate("Moderator")?></B></font></TD>
</TR>

<?php
$row = @mysql_fetch_array($result);
do {
	$sub_sql = "SELECT f.*, u.uname FROM $prefix"._forums." f, $prefix"._users." u WHERE f.cat_id = '$row[cat_id]' AND f.forum_moderator = u.uid ORDER BY forum_id";
	if(!$sub_result = mysql_query($sub_sql, $db))
	    forumerror("0022");
	if($myrow = mysql_fetch_array($sub_result)) {
		$title = stripslashes($row[cat_title]);
		echo "<TR ALIGN=\"LEFT\" VALIGN=\"TOP\"><TD COLSPAN=7 BGCOLOR=\"$bgcolor1\"><FONT SIZE=2 COLOR=\"$textcolor2\"><B>$title</B></FONT></TD></TR>";
		do {
			$last_post = get_last_post($myrow[forum_id], $db, "forum");
			echo "<TR  ALIGN=\"LEFT\" VALIGN=\"TOP\">";
			$total_topics = get_total_topics($myrow[forum_id], $db);
			if(($userdata["lastvisit"] < $last_post) && $last_post != "No posts") {
				echo "<TD BGCOLOR=\"$bgcolor1\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" WIDTH=5%><IMG SRC=\"images/forum/icons/red_folder.gif\" Alt=\"\"></TD>";
			}
			else {
				echo "<TD BGCOLOR=\"$bgcolor1\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\" WIDTH=5%><IMG SRC=\"images/forum/icons/folder.gif\" Alt=\"\"></TD>";
			}
			$name = stripslashes($myrow[forum_name]);
			echo "<TD BGCOLOR=\"$bgcolor3\"><FONT SIZE=2 COLOR=\"$textcolor2\"><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewforum&amp;forum=$myrow[forum_id]\">$name </a></font>\n";
			$desc = stripslashes($myrow[forum_desc]);
			echo "<br><FONT SIZE=1 COLOR=\"$textcolor2\">$desc</font></TD>\n";
			if ($myrow[forum_access]=="0" && $myrow[forum_type]=="0")
			echo "<TD BGCOLOR=\"$bgcolor1\" WIDTH=5% ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><FONT SIZE=1 COLOR=\"$textcolor2\">".translate("Free for All")."</font></TD>\n";
			
			
			if ($myrow[forum_type] == "1")
			echo "<TD BGCOLOR=\"$bgcolor1\" WIDTH=5% ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><FONT SIZE=1 COLOR=\"$textcolor2\">".translate("Protected Area")."</font></TD>\n";
			
			
			
			if ($myrow[forum_access]=="1" && $myrow[forum_type] == "0")
			echo "<TD BGCOLOR=\"$bgcolor1\" WIDTH=5% ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><FONT SIZE=1 COLOR=\"$textcolor2\">".translate("Registered User")."</font></TD>\n";
			if ($myrow[forum_access]=="2" && $myrow[forum_type] == "0")
			echo "<TD BGCOLOR=\"$bgcolor1\" WIDTH=5% ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><FONT SIZE=1 COLOR=\"$textcolor2\">".translate("Moderator")."</font></TD>\n";
			// if ($myrow[forum_type] == "1")
			// echo "<TD BGCOLOR=\"$bgcolor1\" WIDTH=5% ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><FONT SIZE=1 COLOR=\"$textcolor2\">".translate("Protected Area")."</font></TD>\n";
			echo "<TD BGCOLOR=\"$bgcolor3\" WIDTH=5% ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><FONT SIZE=1 COLOR=\"$textcolor2\">$total_topics</font></TD>\n";
			$total_posts = get_total_posts($myrow[forum_id], $db, "forum");
			echo "<TD BGCOLOR=\"$bgcolor1\" WIDTH=5% ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><FONT SIZE=1 COLOR=\"$textcolor2\">$total_posts</font></TD>\n";
			echo "<TD BGCOLOR=\"$bgcolor3\" WIDTH=15% ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><FONT SIZE=1 COLOR=\"$textcolor2\">$last_post</font></TD>\n";
			$forum_moderator = $myrow[uname];
			echo "<TD BGCOLOR=\"$bgcolor1\" WIDTH=5% ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><FONT SIZE=1 COLOR=\"$textcolor2\"><a href=\"user.php?op=userinfo&uname=$forum_moderator\">$forum_moderator</a></font></TD></TR>\n";
		} while($myrow = mysql_fetch_array($sub_result));
	}
} while($row = mysql_fetch_array($result));
?>
<TR ALIGN="LEFT" BGCOLOR="<?php echo $bgcolor1?>">
<TD COLSPAN=7 NOWRAP ><b><?php echo "<font color=$textcolor2>".translate('Private Message')."</font>";?></b></TD></TR>
<TR ALIGN="LEFT" BGCOLOR="<?php echo $bgcolor3?>">
<TD COLSPAN=7 NOWRAP >&nbsp;<img src="images/forum/icons/inbox.gif" Alt="">&nbsp;<a href="viewpmsg.php"><?php echo translate('Inbox');?></a><br>
<?
if (!$user) echo "&nbsp;<font size=1 color=\"$textcolor2\">".translate("Log in to check your private message")."</font></TD></TR>";
else {
$user = base64_decode($user);
$userdata = explode(":", $user);
$total_messages = mysql_num_rows(mysql_query("SELECT msg_id FROM $prefix"._priv_msgs." WHERE to_userid = '$userdata[0]'"));
$new_messages = mysql_num_rows(mysql_query("SELECT msg_id FROM $prefix"._priv_msgs." WHERE to_userid = '$userdata[0]' AND read_msg='0'"));
echo "&nbsp;<font size=1 color=\"$textcolor2\">";
if ($total_messages > 0) {
	if ($new_messages > 0) echo "$new_messages&nbsp;".translate("New Messages")." | ";
	else echo "".translate("No New Messages")." | ";
echo "$total_messages&nbsp;".translate("Total Messages.")."</font>";
}
else echo "".translate("You don't have any Messages.")."</font>";
}
?>
</TD></TR>
</TD></TR></TABLE></TD></TR></TABLE>
<?php searchblock(); ?>
<?php OpenTable(); ?>
<TABLE ALIGN="CENTER" BORDER="0" WIDTH="95%"><TR><TD>
<FONT SIZE=1 COLOR="<?php echo $textcolor2?>">
<IMG SRC="images/forum/icons/red_folder.gif" Alt=""> = <?php echo translate("New Posts since your last visit.")?>
<BR><IMG SRC="images/forum/icons/folder.gif" Alt=""> = <?php echo translate("No New Posts since your last visit.")?>
</FONT></TD></TR></TABLE>
<?php CloseTable();
include ("footer.php");
}

# End AddOn Modules

switch($func) {

    default:
    ForumIndex();
    break;
}

?>