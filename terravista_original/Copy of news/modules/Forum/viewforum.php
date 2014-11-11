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

function ViewForum($forum,$start) {
global $user,$HTTP_COOKIE_VARS,$bgcolor1,$bgcolor2,$bgcolor3,$textcolor1,$textcolor2,$passwd,$prefix;
include('config.php');
include('functions.php');
include('auth.php');

if($forum == -1)
	$forum += 2;

$sql = "SELECT f.forum_type, f.forum_pass, f.forum_name, u.uname FROM $prefix"._forums." f, $prefix"._users." u WHERE forum_id = '$forum' AND f.forum_moderator = u.uid";
if(!$result = mysql_query($sql, $db))
    forumerror(0001);	
if(!$myrow = mysql_fetch_array($result))
    forumerror(0002);	
$forum_name = stripslashes($myrow[forum_name]);
$moderator = $myrow[uname];
$forum_pass = $myrow[forum_pass];

if($myrow[forum_type] == 1 && $myrow[forum_name] != $Forum_Name && $passwd != $myrow[forum_pass]) {

//if($myrow[forum_type] == 1 && $myrow[forum_name] != $Forum_Name ) {
    include('header.php');

    ?>

    <?php OpenTable(); ?>
    <TABLE BORDER="0" WIDTH="100%"><TR><TD ALIGN=LEFT> -->
    <FONT SIZE=2><b><?php echo translate("Moderated By: "); $moderator_data = get_userdata($moderator, $db); ?><a href="user.php?op=userinfo&uname=<?php echo $moderator_data[uname]?>"><?php echo $moderator?></a></b><br>
    <a href="modules.php?op=modload&amp;name=Forum&amp;file=index"><?php echo "$sitename ".translate("Forum Index");?></a> <b>» »</b>
    <a href="modules.php?op=modload&amp;name=Forum&amp;file=viewforum&amp;forum=<?php echo $forum?>"><?php echo stripslashes($forum_name)?></a></FONT>
    </TD><TD ALIGN=RIGHT><a href="modules.php?op=modload&amp;name=Forum&amp;file=newtopic&amp;forum=<?php echo $forum?>&amp;mod=<?php echo $moderator_data[uid]?>"><IMG SRC="images/forum/icons/new_topic-dark.jpg" BORDER="0"></a>
    </TD></TR></TABLE>
    <?php CloseTable(); ?>
    <FORM ACTION="modules.php" METHOD="POST">
    <TABLE BORDER="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="95%"><TR><TD  BGCOLOR="<?php echo $table_bgcolor?>">
    <TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
    <TR BGCOLOR="<?php echo $bgcolor2?>" ALIGN="LEFT">
    <TD ALIGN="CENTER"><FONT COLOR="<?php echo $textcolor2;?>"><?php echo translate("This is a Private Forum. Please enter the password to gain access");?></FONT></TD>
    </TR>
    <TR BGCOLOR="<?php echo $bgcolor1?>" ALIGN="LEFT">
    <TD ALIGN="CENTER"><INPUT TYPE="PASSWORD" NAME="passwd" SIZE="25" MAXLENGTH="30"></TD>
    </TR>
    <TR BGCOLOR="<?php echo $bgcolor2?>" ALIGN="LEFT">
    <TD ALIGN="CENTER">
		<INPUT TYPE="HIDDEN" NAME="op" VALUE="modload">
		<INPUT TYPE="HIDDEN" NAME="name" VALUE="Forum">
		<INPUT TYPE="HIDDEN" NAME="file" VALUE="viewforum">
		<INPUT TYPE="HIDDEN" NAME="forum" VALUE="<?php echo $forum?>">
		<INPUT TYPE="SUBMIT" NAME="submit" VALUE="Enter">
		<INPUT TYPE="HIDDEN" NAME="bypass" VALUE="1">
		<INPUT TYPE="RESET" VALUE="Clear">
    </TD>
    </TR>
    </TABLE></TD></TR></TABLE>
    </FORM>
<?php
} elseif ($passwd == $myrow[forum_pass]) {               

//	if((base64_encode($passwd) != $myrow[forum_pass]) && $myrow[forum_type] >= 1)
//	    forumerror(0003);

include('header.php');
?>


<?php OpenTable(); ?>
<TABLE BORDER="0" WIDTH="100%"><TR><TD ALIGN=LEFT>
<FONT SIZE=2><b><?php echo translate("Moderated By: "); $moderator_data = get_userdata($moderator, $db); ?><a href="user.php?op=userinfo&uname=<?php echo $moderator_data[uname]?>"><?php echo $moderator?></a></b><br>
<a href="modules.php?op=modload&amp;name=Forum&amp;file=index"><?php echo "$sitename ".translate("Forum Index");?></a> <b>» »</b>
<a href="modules.php?op=modload&amp;name=Forum&amp;file=viewforum&amp;forum=<?php echo $forum?>"><?php echo stripslashes($forum_name)?></a></FONT>
</TD><TD ALIGN=RIGHT><a href="modules.php?op=modload&amp;name=Forum&amp;file=newtopic&amp;forum=<?php echo $forum?>&amp;mod=<?php echo $moderator_data[uid]?>"><IMG SRC="images/forum/icons/new_topic-dark.jpg" BORDER="0"></a>
</TD></TR></TABLE>
<?php CloseTable(); ?>



<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="100%"><TR><TD>
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $bgcolor2?>" ALIGN="LEFT">
	<TD WIDTH=2%>&nbsp;</TD>
	<TD WIDTH=2%>&nbsp;</TD>
	<TD><font size="1"><B>&nbsp;<?php echo translate("Topic");?></B></font></TD>
	<TD WIDTH=9% ALIGN="CENTER"><font size="1"><B><?php echo translate("Replies");?></B></font></TD>
	<TD WIDTH=20% ALIGN="CENTER"><font size="1"><B><?php echo translate("Poster");?></B></font></TD>
	<TD WIDTH=8% ALIGN="CENTER"><font size="1"><B><?php echo translate("Views");?></B></font></TD>
	<TD WIDTH=15% ALIGN="CENTER"><font size="1"><B><?php echo translate("Date");?></B></font></TD>		
</TR>
<?php
if(!$start) $start = 0;
   
$sql = "SELECT t.*, u.uname FROM $prefix"._forumtopics." t, $prefix"._users." u WHERE t.forum_id = '$forum' AND t.topic_poster = u.uid ORDER BY topic_time DESC LIMIT $start, $topics_per_page";
if(!$result = mysql_query($sql, $db))
	forumerror(0004);
if($myrow = mysql_fetch_array($result)) {
   do {
      echo"<TR>\n";
      $replys = get_total_posts($myrow[topic_id], $db, "topic");
      $last_post = get_last_post($myrow[topic_id], $db, "topic");
      list ($image_subject) = mysql_fetch_array(mysql_query("SELECT image FROM $prefix"._posts." WHERE topic_id = '$myrow[topic_id]' ORDER BY post_time DESC"));
      $replys--;
      if($replys >= $hot_threshold) {
	 if($userdata["lastvisit"] > $last_post) 
	   $image = "images/forum/icons/hot_folder.gif";
	 else 
	   $image = "images/forum/icons/hot_red_folder.gif";
      }
      else {
	 if($userdata["lastvisit"] > $last_post) 
	$image = "images/forum/icons/folder.gif";
	 else
	   $image = "images/forum/icons/red_folder.gif";
      }
      if($myrow[topic_status] == 1)
	$image = "images/forum/icons/lock.gif";
      
      echo "<TD BGCOLOR=\"$bgcolor1\"><IMG SRC=\"$image\"></TD>\n";
      
      if($image_subject != "") echo "<TD BGCOLOR=\"$bgcolor3\"><IMG SRC=\"images/forum/subject/$image_subject\"></TD>\n";
      else echo "<TD BGCOLOR=\"$bgcolor3\"><IMG SRC=\"images/forum/icons/posticon.gif\"></TD>\n";
      
      $topic_title = stripslashes($myrow[topic_title]);
      echo "<TD BGCOLOR=\"$bgcolor3\"><font size=\"2\">&nbsp;<a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewtopic&amp;topic=$myrow[topic_id]&amp;forum=$forum\">$topic_title</a></font></TD>\n";
      echo "<TD BGCOLOR=\"$bgcolor1\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><font size=\"2\">$replys</font></TD>\n";
      echo "<TD BGCOLOR=\"$bgcolor3\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><font size=\"2\">$myrow[uname]</font></TD>\n";
      echo "<TD BGCOLOR=\"$bgcolor1\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><font size=\"2\">$myrow[topic_views]</font></TD>\n";
      echo "<TD BGCOLOR=\"$bgcolor3\" ALIGN=\"CENTER\" VALIGN=\"MIDDLE\"><font size=\"1\">$last_post</font></TD></TR>\n";
      
   } while($myrow = mysql_fetch_array($result));
}
else {
	echo "<TD BGCOLOR=\"$bgcolor1\" colspan = 7 ALIGN=CENTER>".translate("There are no topics for this forum. ")."<br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=newtopic&amp;forum=$forum&amp;mod=$moderator_data[uid]\">".translate("You can post one here.")."</a></TD></TR>\n";
}
?>
<TR ALIGN="LEFT" BGCOLOR="<?php echo $bgcolor1?>">
<TD COLSPAN=6 NOWRAP ><b><?php echo "<font color=$textcolor2>".translate('Private Message')."</font>";?></b></TD>
<TD COLSPAN=1 NOWRAP VALIGN="TOP" ALIGN="RIGHT">
<?php
$sql = "SELECT count(*) AS total FROM $prefix"._forumtopics." WHERE forum_id = '$forum'";
if(!$r = mysql_query($sql, $db))
    forumerror(0001);
list($all_topics) = mysql_fetch_array($r);   
$count = 1;
$next = $start + $topics_per_page;
if($all_topics > $topics_per_page) {
    if($next >= $all_topics)
      	   echo "<font size=1><b>".translate("Next Page")." [ \n";
      	 else
      	   echo "<a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewforum&amp;forum=$forum&amp;start=$next\"><font size=1><b>".translate("Next Page")."</a> [ "; 
   for($x = 0; $x < $all_topics; $x++) {
      if(!($x % $topics_per_page)) {
	 if($x == $start)
	   echo "$count\n";
	 else
	   echo "<a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewforum&amp;forum=$forum&amp;start=$x\">$count</a>\n";
	 $count++;
	 if(!($count % 10)) echo "<BR>";
      }
      
   }
   echo " ]</b></font>";
}
echo "<BR>\n";
?>
</TD></TR>
<TR ALIGN="LEFT" BGCOLOR="<?php echo $bgcolor3?>">
<TD COLSPAN=7 NOWRAP >&nbsp;<img src="images/forum/icons/inbox.gif">&nbsp;<a href="viewpmsg.php"><?php echo translate('Inbox');?></a><br>
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
<!-- <TR ALIGN="RIGHT" >
<TD COLSPAN=7 BGCOLOR="<?php echo $bgcolor2?>" ><?php searchblock()?></TD></TR> -->
</TABLE></TD></TR></TABLE>
<?php searchblock(); ?>
<?php
OpenTable();
//<TABLE ALIGN="CENTER" BORDER="0" WIDTH="95%"><TR><TD VALIGN="TOP">
?>
<font size="1">
<IMG SRC="images/forum/icons/red_folder.gif"> = <?php echo translate("New Posts since your last visit.");?> (<IMG SRC="images/forum/icons/hot_red_folder.gif"> = <?php echo "".translate("More then ")."$hot_threshold ".translate("Posts");?>)
<BR><IMG SRC="images/forum/icons/folder.gif"> = <?php echo translate("No New Posts since your last visit.");?> (<IMG SRC="images/forum/icons/hot_folder.gif"> = <?php echo "".translate("More then ")."$hot_threshold ".translate("Posts");?>)
<BR><IMG SRC="images/forum/icons/lock.gif"> = <?php echo translate("Topic is Locked - No new posts may be made in it");?>
</font></TD>
<TD ALIGN="RIGHT">
<FORM ACTION="modules.php" METHOD="GET">
<font size="2"><?php echo translate("Jump To:");?></font> <SELECT NAME="forum"><OPTION VALUE="-1"><?php echo translate("Select a Forum")?></OPTION>
<?php
	$sql = "SELECT cat_id, cat_title FROM $prefix"._catagories." ORDER BY cat_id";
	if($result = mysql_query($sql, $db)) {
		$myrow = mysql_fetch_array($result);
		do {
		//	echo "<OPTION VALUE=\"-1\">&nbsp;</OPTION>\n";
			echo "<OPTION VALUE=\"-1\"><b>$myrow[cat_title]</b></OPTION>\n";
			echo "<OPTION VALUE=\"-1\">----------------</OPTION>\n";
			$sub_sql = "SELECT forum_id, forum_name FROM $prefix"._forums." WHERE cat_id = '$myrow[cat_id]' ORDER BY forum_id";
			if($res = mysql_query($sub_sql, $db)) {
				if($row = mysql_fetch_array($res)) {
					do {
						$name = stripslashes($row[forum_name]);
						echo "<OPTION VALUE=\"$row[forum_id]\">&nbsp;&nbsp;&nbsp;$name</OPTION>\n";
					} while($row = mysql_fetch_array($res));
				}
				else {
					echo "<OPTION VALUE=\"0\">".translate("No More Forums")."</OPTION>\n";
				}
			}
			else {
				echo "<OPTION VALUE=\"0\">Error Connecting to DB</OPTION>\n";
			}
		} while($myrow = mysql_fetch_array($result));
	}
	else {
		echo "<OPTION VALUE=\"-1\">ERROR</OPTION>\n";
	}
?>
</SELECT>
<INPUT TYPE="HIDDEN" NAME="op" VALUE="modload">
<INPUT TYPE="HIDDEN" NAME="name" VALUE="Forum">
<INPUT TYPE="HIDDEN" NAME="file" VALUE="viewforum">
<INPUT TYPE="SUBMIT" VALUE="Go">
</FORM>

<?php
CloseTable();
}

include ("footer.php");
}

# End AddOn Modules

switch($func) {

    default:
    ViewForum($forum,$start);
    break;
}

?>