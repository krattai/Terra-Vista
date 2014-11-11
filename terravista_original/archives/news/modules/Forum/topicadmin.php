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

function TopicAdmin($mode,$topic,$forum,$post,$newforum,$level,$username,$passwd,$submit) {
global $user,$prefix;
include('config.php');
include('functions.php');
include('auth.php');
include('header.php');

if($submit) {
	$sql = "SELECT forum_moderator FROM $prefix"._forums." WHERE forum_id = '$forum'";
	if(!$result = mysql_query($sql, $db))
	    forumerror("0001");
	$myrow = mysql_fetch_array($result);
	$mod_data = get_userdata($username, $db);
	if ($username == '' || $passwd == '')
	    forumerror("0006");
	if($myrow[forum_moderator] != $mod_data[uid] && $level >= 2)
	    forumerror("0007");
	if (!$system) $md_pass = crypt($passwd,substr($mod_data[pass],0,2));
	else $md_pass = $passwd;
	if($mod_data[pass] != $md_pass)
	    forumerror("0008");
	echo "<center>";
	switch($mode) {
		case 'del':
			$sql = "DELETE FROM $prefix"._posts." WHERE topic_id = '$topic'";
			if(!$result = mysql_query($sql, $db))
			    forumerror("0009");
			$sql = "DELETE FROM $prefix"._forumtopics." WHERE topic_id = '$topic'";
			if(!$result = mysql_query($sql, $db))
			echo translate("The topic has been removed from the database.")."<br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewforum&amp;forum=$forum\">".translate("Click here to return to the forum.")."</a><br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=index\">".translate("Click here to return to the forum index.")."</a>";
		break;
		case 'move':
			$sql = "UPDATE $prefix"._forumtopics." SET forum_id = '$newforum' WHERE topic_id = '$topic'";
			if(!$r = mysql_query($sql, $db))
			    forumerro(0010);
			$sql = "UPDATE $prefix"._posts." SET forum_id = '$newforum' WHERE topic_id = '$topic'";
			if(!$r = mysql_query($sql, $db))
			    forumerror("0010");
			echo translate("The topic has been moved.")."<br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewtopic&amp;topic=$topic&amp;forum=$newforum\">".translate("Click here to view the updated topic.")."</a><br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=index\">".translate("Click here to return to the forum index.")."</a>";
		break;
		case 'lock':
			$sql = "UPDATE $prefix"._forumtopics." SET topic_status = 1 WHERE topic_id = '$topic'";
			if(!$r = mysql_query($sql, $db))
			    forumerror("0011");
			echo translate("The topic has been locked.")."<br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewtopic&amp;topic=$topic&amp;forum=$forum\">".translate("Click here to view the updated topic.")."</a><br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=index\">".translate("Click here to return to the forum index.")."</a>";
		break;
		case 'unlock':
			$sql = "UPDATE $prefix"._forumtopics." SET topic_status = '0' WHERE topic_id = '$topic'";
			if(!$r = mysql_query($sql, $db))
			    forumerror("0012");
			echo translate("The topic has been unlocked.")."<br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewtopic&amp;topic=$topic&amp;forum=$forum\">".translate("Click here to view the updated topic.")."</a><br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=index\">".translate("Click here to return to the forum index.")."</a>";
		break;
		case 'viewip':
			$sql = "SELECT u.uname, p.poster_ip FROM $prefix"._users." u, $prefix"._posts." p WHERE p.post_id = '$post' AND u.uid = p.poster_id";
			if(!$r = mysql_query($sql, $db))
			    forumerror("0013");
			if(!$m = mysql_fetch_array($r)) 
			    forumerror("0014");
?>
<TABLE BORDER="0" CELLPADDING="1" CELLSPACEING="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="95%"><TR><TD BGCOLOR="<?php echo $table_bgcolor?>">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACEING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $bgcolor3?>" ALIGN="LEFT">
	<TD COLSPAN="2" ALIGN="CENTER"><?php echo translate("Users IP and Account information");?></TD>
</TR>
<TR BGCOLOR="<?php echo $bgcolor1?>" ALIGN="LEFT">
	<TD><?php echo translate("Nickname: ");?></TD>
	<TD><?php echo $m[uname]?></TD>
</TR>
<TR BGCOLOR="<?php echo $bgcolor1?>" ALIGN="LEFT">
	<TD><?php echo translate("User IP: ");?></TD>
	<TD><?php echo $m[poster_ip]?></TD>
</TR>
</TABLE></TD></TR></TABLE>
<?php
		break;

	}
	echo "</center>";
}
else {
	       $user = base64_decode($user);
	       $userdata = explode(":", $user);
	       $userdata = get_userdata_from_id($userdata[0], $db);
	       
	       $sql = "SELECT forum_moderator FROM $prefix"._forums." WHERE forum_id = '$forum'";
	       if(!$result = mysql_query($sql, $db))
	        forumerror("0001");
	       $myrow = mysql_fetch_array($result);
	       
	       if ($userdata[level] >= 2 && $userdata[uid] == $myrow[forum_moderator]) {
?>
<FORM ACTION="modules.php" METHOD="POST">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="100%"><TR><TD>
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $bgcolor3?>" ALIGN="LEFT">
<?php
	switch($mode) {
		case 'del':
?>
	<TD COLSPAN=2><B><?php echo translate("Read This: ");?></b><?php echo translate("Please identify yourself as moderator of this forum.")?><br><FONT SIZE=1><i><?php echo translate("Once you press the delete button at the bottom of this form the topic you have selected, and all its related posts, will be permanently removed.");?></FONT></i></TD>
<?php
		break;
		case 'move':
?>
	<TD COLSPAN=2><B><?php echo translate("Read This: ");?></b><?php echo translate("Please identify yourself as moderator of this forum.")?><br><FONT SIZE=1><i><?php echo translate("Once you press the move button at the bottom of this form the topic you have selected, and its related posts, will be moved to the forum you have selected.");?></i></FONT></TD>
<?php
		break;
		case 'lock':
?>
	<TD COLSPAN=2><B><?php echo translate("Read This: ");?></b><?php echo translate("Please identify yourself as moderator of this forum.")?><br><FONT SIZE=1><i><?php echo translate("Once you press the lock button at the bottom of this form the topic you have selected will be locked. You may unlock it at a later time if you like.");?></FONT></i></TD>
<?php
		break;
		case 'unlock':
?>
	<TD COLSPAN=2><B><?php echo translate("Read This: ");?></b><?php echo translate("Please identify yourself as moderator of this forum.")?><br><FONT SIZE=1><i><?php echo translate("Once you press the unlock button at the bottom of this form the topic you have selected will be unlocked. You may lock it again at a later time if you like.");?></i></FONT></TD>
<?php
		break;
		case 'viewip':
?>
	<TD COLSPAN=2><B><?php echo translate("Read This: ");?></b><?php echo translate("Please identify yourself as moderator of this forum.")?></FONT></TD>
<?php
		break;
	}
?>
</TR>
<TR>
	<TD BGCOLOR="<?php echo $bgcolor3?>"><?php echo translate("Nickname: ");?></TD>
	<TD BGCOLOR="<?php echo $bgcolor1?>"><INPUT CLASS=textbox TYPE="TEXT" NAME="username" SIZE="25" MAXLENGTH="40"></TD>
</TR>
<TR>
	<TD BGCOLOR="<?php echo $bgcolor3?>"><?php echo translate("Password: ");?></TD>
	<TD BGCOLOR="<?php echo $bgcolor1?>"><INPUT CLASS=textbox TYPE="PASSWORD" NAME="passwd" SIZE="25" MAXLENGTH="25"></TD>
</TR>
<?php
	if($mode == 'move') {
?>
<TR>
	<TD BGCOLOR="<?php echo $bgcolor3?>"><?php echo translate("Move Topic To: ");?></TD>
	<TD BGCOLOR="<?php echo $bgcolor1?>"><SELECT NAME="newforum" SIZE="0">
<?php
	$sql = "SELECT forum_id, forum_name FROM $prefix"._forums." WHERE forum_id != '$forum' ORDER BY forum_id";
	if($result = mysql_query($sql, $db)) {
		if($myrow = mysql_fetch_array($result)) {
			do {
				echo "<OPTION VALUE=\"$myrow[forum_id]\">$myrow[forum_name]</OPTION>\n";
			} while($myrow = mysql_fetch_array($result));
		}
		else {
			echo "<OPTION VALUE=\"-1\">".translate("No More Forums")."</OPTION>\n";
		}
	}
	else {
		echo "<OPTION VALUE=\"-1\">Database Error</OPTION>\n";
	}
?>
	</SELECT></TD>
</TR>
<?php
	}
?>
<TR BGCOLOR="<?php echo $bgcolor3?>">
	<TD COLSPAN="2" ALIGN="CENTER">
<?php
	switch($mode) {
		case 'del':
?>
		<INPUT TYPE="HIDDEN" NAME="mode" VALUE="del">
		<INPUT TYPE="HIDDEN" NAME="topic" VALUE="<?php echo $topic?>">
		<INPUT TYPE="HIDDEN" NAME="forum" VALUE="<?php echo $forum?>">		
		<INPUT TYPE="SUBMIT" NAME="submit" VALUE="<?php echo translate("Delete Topic");?>">
<?php
		break;
		case 'move':
?>
		<INPUT TYPE="HIDDEN" NAME="mode" VALUE="move">
		<INPUT TYPE="HIDDEN" NAME="topic" VALUE="<?php echo $topic?>">
		<INPUT TYPE="HIDDEN" NAME="forum" VALUE="<?php echo $forum?>">		
		<INPUT TYPE="SUBMIT" NAME="submit" VALUE="<?php echo translate("Move Topic");?>">
<?php
		break;
		case 'lock':
?>
		<INPUT TYPE="HIDDEN" NAME="mode" VALUE="lock">
		<INPUT TYPE="HIDDEN" NAME="topic" VALUE="<?php echo $topic?>">
		<INPUT TYPE="HIDDEN" NAME="forum" VALUE="<?php echo $forum?>">		
		<INPUT TYPE="SUBMIT" NAME="submit" VALUE="<?php echo translate("Lock Topic");?>">
<?php
		break;
		case 'unlock':
?>
		<INPUT TYPE="HIDDEN" NAME="mode" VALUE="unlock">
		<INPUT TYPE="HIDDEN" NAME="topic" VALUE="<?php echo $topic?>">
		<INPUT TYPE="HIDDEN" NAME="forum" VALUE="<?php echo $forum?>">		
		<INPUT TYPE="SUBMIT" NAME="submit" VALUE="<?php echo translate("Unlock Topic");?>">
<?php
		break;
		case 'viewip':
?>
		<INPUT TYPE="HIDDEN" NAME="mode" VALUE="viewip">
		<INPUT TYPE="HIDDEN" NAME="post" VALUE="<?php echo $post?>">
		<INPUT TYPE="HIDDEN" NAME="forum" VALUE="<?php echo $forum?>">
		<INPUT TYPE="SUBMIT" NAME="submit" VALUE="<?php echo translate("View IP");?>">
<?php
		break;
	}
?>
<INPUT TYPE="HIDDEN" NAME="op" VALUE="modload">
<INPUT TYPE="HIDDEN" NAME="name" VALUE="Forum">
<INPUT TYPE="HIDDEN" NAME="file" VALUE="topicadmin">
<INPUT TYPE="HIDDEN" NAME="level" VALUE="<?php echo $userdata[level];?>">
</TD></TR>
</FORM>
</TABLE></TD></TR></TABLE></TD></TR></TABLE>
<?php
}
else {
?>
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING=0" ALIGN="CENTER" VALIGN="TOP" WIDTH="100%"><TR><TD>
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING=1" WIDTH="100%">
<TR BGCOLOR="<?php echo $bgcolor3?>">
<TD ALIGN="CENTER">
You are not the moderator of this forum therefor you cannot perform this function.<br>
<A HREF="javascript:history.go(-1)">back</a>
</TD>
</TR>
</TABLE></TD></TR></TABLE>
<?
}
}
include ("footer.php");
}

# End AddOn Modules

switch($func) {

    default:
    TopicAdmin($mode,$topic,$forum,$post,$newforum,$level,$username,$passwd,$submit);
    break;
}

?>