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

function ReplyForum($topic,$forum,$mod,$smile,$sig,$bbcode,$html,$message,$image_subject,$subject,$password,$username,$submit,$quote) {
$forumpage = 1;
global $user,$REMOTE_ADDR,$HTTP_COOKIE_VARS,$bgcolor1,$bgcolor2,$bgcolor3,$textcolor1,$textcolor2,$post,$prefix,$nukeurl;

if($cancel) {
	header("Location: modules.php?op=modload&name=Forum&file=viewtopic&topic=$topic&forum=$forum");
}

include('config.php');
include('functions.php');
include('auth.php');

$sql = "SELECT forum_name, forum_access FROM $prefix"._forums." WHERE (forum_id = '$forum')";
if(!$result = mysql_query($sql, $db)) {
    forumerror("0001");
}
$myrow = mysql_fetch_array($result);
$forum_name = $myrow[forum_name];
$forum_access = $myrow[forum_access];
$forum_id = $forum;

if(is_locked($topic, $db)) {
    forumerror("0025");
}
	
if(!does_exists($forum, $db, "forum") || !does_exists($topic, $db, "topic")) {
    forumerror("0026");
}

if($submit) {
	if($message == '') $stop=1;
	
	if (!$user) {
        	if($username == '' && $password == '' && $forum_access == 0) {
                	// Not logged in, and username and password are empty and forum_access is 0 (anon posting allowed)
                        $userdata = array("uid" => 1);
                        include('header.php');
                }
                else {
        	        // no valid session, need to check user/pass.
	                if($username == '' || $password == '') {
			    forumerror("0027");
			}
			$userdata = get_userdata($username, $db);
			if (!$system) $md_pass = crypt($password,substr($userdata[pass],0,2));
			else $md_pass = $password;
        	        if($md_pass == $userdata[pass]) {
        	                $info = base64_encode("$userdata[uid]:$userdata[uname]:$userdata[pass]:$userdata[storynum]:$userdata[umode]:$userdata[uorder]:$userdata[hold]:$userdata[noscore]:$userdata[ublockon]:$userdata[theme]:$userdata[commentmax]");
	                        setcookie("user","$info",time()+15552000);
	                        include('header.php');
                	}
                	else {
			    forumerror("0028");
                	}
        	}
	} else {
	include('header.php');
	       $user = base64_decode($user);
	       $userdata = explode(":", $user);
	       $userdata = get_userdata($userdata[1], $db); 
	}
	// Either valid user/pass, or valid session. continue with post.
	
	if ($stop != 1) {
	$poster_ip = $REMOTE_ADDR;
	if($allow_html == 0 || isset($html)) $message = htmlspecialchars($message);
	if($sig && $userdata[uid] != 1) $message .= "[addsig]";        
	if($allow_bbcode == 1 && !isset($bbcode)) $message = bbencode($message);
	$message = str_replace("\n", "<BR>", $message);
	if(!$smile) $message = smile($message);
	$message = make_clickable($message);
	$message = addslashes($message);
	$time = date("Y-m-d H:i");
	$sql = "INSERT INTO $prefix"._posts." (topic_id, image, forum_id, poster_id, post_text, post_time, poster_ip) VALUES ('$topic', '$image_subject', '$forum', '$userdata[uid]', '$message', '$time', '$poster_ip')";
	if(!$result = mysql_query($sql, $db)) {
	    forumerror("0020");
	}
	$sql = "UPDATE $prefix"._forumtopics." SET topic_time = '$time' WHERE topic_id = '$topic'";
	if(!$result = mysql_query($sql, $db)) {
	    forumerror("0020");
	}
	if($userdata["uid"] != 1) {
		$sql = "UPDATE $prefix"._users_status." SET posts=posts+1 WHERE (uid = $userdata[uid])";
		$result = mysql_query($sql, $db);
		if (!$result) {
		    echo mysql_error() . "<br>\n";
		    forumerror("0029");
		}
	}
	$sql = "SELECT t.topic_notify, u.email, u.uname FROM $prefix"._forumtopics." t, $prefix"._users." u WHERE t.topic_id = '$topic' AND t.topic_poster = u.uid";
	if(!$result = mysql_query($sql, $db)) {
	    forumerror("0022");
        }
	$m = mysql_fetch_array($result);
	if($m[topic_notify] == 1 && $m[uname] != $username) {
		$subject = "A reply to your topic has been posted.";
		$message = "Dear $m[uname]\r\nYou are receiving this email because a message you posted on $sitename forums has been replied to, and you selected";
		$message .= " to be notified on this event.\r\n\r\nYou may view the topic at: ";
		$message .= "$nukeurl/viewtopic.php?topic=$topic&forum=$forum\r\nOr view $sitename forum index at $nukeurl/forum.php";
		$message .= "\r\n\r\nThank you for using $sitename forums. \r\nHave a nice day.";
		if (!$system) {
		    mail($m[email], $subject, $message, "From: NukeAddOn_forum@$SERVER_NAME\r\nX-Mailer: NukeAddOn_forum");
		}
	}
	OpenTable2();
	echo "<center>".translate("Reply Posted.")."<br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewtopic&amp;topic=$topic&amp;forum=$forum\">".translate("Click here to view your topic")."</center><P></font>";
	CloseTable();
	} else {	
	    OpenTable2();
	    echo "<center>".translate("You must type a message to post.")."<br><br>";
	    echo "[ <A HREF=javascript:history.go(-1)>".translate("Go Back")."</A> ]</center>";
	    CloseTable();
	}
} else {
	include('header.php');
	JScriptForum();
$moderator = get_moderator($mod,$db);
list($topic_title) = mysql_fetch_array(mysql_query("select topic_title from $prefix"._forumtopics." where topic_id='$topic'"));
OpenTable();
?>
	<P ALIGN=LEFT><FONT SIZE="2"><b><?php echo "".translate("Moderated By: ")."</b><a href=user.php?op=userinfo&uname=$moderator>$moderator";?></a><br>
	<b><?php echo translate("Forum");?>:</b> &nbsp;<a href="modules.php?op=modload&amp;name=Forum&amp;file=viewforum&amp;forum=<?php echo $forum?>"><?php echo $forum_name?></a><br>
	<b><?php echo translate("Post Reply in Topic:");?></b>&nbsp;<a href="modules.php?op=modload&amp;name=Forum&amp;file=viewtopic&amp;forum=<?php echo $forum?>&amp;topic=<?php echo $topic?>"><?php echo $topic_title?></a><br>
	<a href="modules.php?op=modload&amp;name=Forum&amp;file=index">Go to <?php echo "$sitename"; ?> Forums Index</a>
	</font></p>
<?php CloseTable(); ?>
	<FORM ACTION="modules.php" METHOD="POST" NAME="coolsus">
	<TABLE BORDER="0" CELLPADDING="1" CELLSPACING=0" ALIGN="CENTER" VALIGN="TOP" WIDTH="100%"><TR><TD>
	<TABLE BORDER="0" CELLPADDING="1" CELLSPACING=1" WIDTH="100%">
	<TR BGCOLOR="<?php echo $bgcolor2?>" ALIGN="LEFT">
		<TD width=25%><FONT COLOR="<?php echo $textcolor2;?>"><b><?php echo translate("About Posting:");?></b></FONT></TD>
<?php
	if($forum_access == 0) {
?>
		<TD><FONT COLOR="<?php echo $textcolor2;?>"><?php echo translate("Anonymous users can post new topics and replies in this forum.");?></FONT></TD>
		
<?php
	}
	else if($forum_access == 1) {
?>
		<TD><FONT COLOR="<?php echo $textcolor2;?>"><?php echo translate("All registered users can post new topics and replies to this forum.");?></FONT></TD>
<?php
	}
	else if($forum_access == 2) {
?>
		<TD><FONT COLOR="<?php echo $textcolor2;?>"><?php echo translate("Only Moderators and Administrators can post new topics and replies in this forum.");?></FONT></TD>
<?php
	}
?>
	</TR>
<?PHP
	if (isset($user) && $forum_access == 1) {
	        $user = base64_decode($user);
	        $userdata = explode(":", $user);
	        $sql = "SELECT u.pass, s.level FROM $prefix"._users." u, $prefix"._users_status." s WHERE u.uid = '$userdata[0]' and s.uid = '$userdata[0]'";
	        $result = mysql_query($sql, $db);
	        $user = mysql_fetch_array($result);
	        $password = $user[pass];
	        if ($password == $userdata[2] && $forum_access <= $user[level]) {
	        echo "<TR ALIGN=LEFT>";
		echo "<TD  BGCOLOR=$bgcolor3  width=25%><b>".translate("Nickname: ")."<b></TD>";
		echo "<TD  BGCOLOR=$bgcolor1>";
	        echo $userdata[1] . " \n";
	        echo "</TD></TR> \n";
	        $allow_to_reply= 1;
	        }
	        else {
	        echo "<TR>";
	        echo "<TD BGCOLOR=$bgcolor3 COLSPAN=2 ALIGN=CENTER>".translate("You are not allowed to reply in this forum")."<BR><A HREF=javascript:history.go(-1)>".translate("back")."</A></TD>";
	        echo "</TR>";
	        }
	} elseif (!isset($user) && $forum_access == 1) {
	        echo "<TR ALIGN=LEFT>";
		echo "<TD  BGCOLOR=$bgcolor3  width=25%><b>".translate("Nickname: ")."<b></TD>";
		echo "<TD  BGCOLOR=$bgcolor1>";
		echo "<INPUT CLASS=textbox TYPE=\"TEXT\" NAME=\"username\" SIZE=\"25\" MAXLENGTH=\"40\" ></TD> \n";
		echo "<TR ALIGN=\"LEFT\"> \n";
		echo "<TD BGCOLOR=\"$bgcolor3\" width=25%><b>".translate("Password: ")."</b></TD> \n";
		echo "<TD BGCOLOR=\"$bgcolor1\"><INPUT CLASS=textbox TYPE=\"PASSWORD\" NAME=\"password\" SIZE=\"25\" MAXLENGTH=\"25\"></TD> \n";
		echo "</TR> \n";
		$allow_to_reply = 1;
	} elseif ($forum_access == 2) {
		echo "<TR ALIGN=LEFT>";
		echo "<TD  BGCOLOR=$bgcolor3  width=25%><b>".translate("Nickname: ")."<b></TD>";
		echo "<TD  BGCOLOR=$bgcolor1>";
		echo "<INPUT CLASS=textbox TYPE=\"TEXT\" NAME=\"username\" SIZE=\"25\" MAXLENGTH=\"40\"></TD> \n";
		echo "<TR ALIGN=\"LEFT\"> \n";
		echo "<TD BGCOLOR=\"$bgcolor3\" width=25%><b>".translate("Password: ")."</b></TD> \n";
		echo "<TD BGCOLOR=\"$bgcolor1\"><INPUT CLASS=textbox TYPE=\"PASSWORD\" NAME=\"password\" SIZE=\"25\" MAXLENGTH=\"25\"></TD> \n";
		echo "</TR> \n";
		$allow_to_reply = 1;
	} elseif ($forum_access == 0) {
	        $allow_to_reply = 1;
	}

if ($allow_to_reply) {
?>
	<TR ALIGN="LEFT">
		<TD  BGCOLOR="<?php echo $bgcolor3?>" width=25% VALIGN="TOP"><b><?php echo translate("Message Icon: ");?></b></TD>
		<TD  BGCOLOR="<?php echo $bgcolor1?>">
	<?php
		$handle=opendir("./images/forum/subject");
		while ($file = readdir($handle))
			{
			$filelist[] = $file;
		}
		asort($filelist);
		while (list ($key, $file) = each ($filelist))
		{
		ereg(".gif|.jpg",$file);
		if ($file == "." || $file == ".." || $file == "index.html") $a=1;
		else {
			echo "<input type=\"radio\" value=\"$file\" name=\"image_subject\">&nbsp;";
			echo "<IMG SRC=\"images/forum/subject/$file\" BORDER=0>&nbsp;";
			}
		if ($count == "8") { echo "<br>"; $count = 1; }
		$count++;
		}
	?>
		 </TD>
	</TR>
	<TR ALIGN="LEFT" VALIGN="TOP">
		<TD  BGCOLOR="<?php echo $bgcolor3?>" width=25%><b><?php echo translate("Message: ");?></b><br><br>
		<font size=-1>
		<?php
		echo "HTML : ";
		if($allow_html == 1)
			echo "On<BR>\n";
		else
			echo "Off<BR>\n";
		echo "<a href=\"modules.php?op=modload&amp;name=Forum&amp;file=bbcode_ref\" TARGET=\"blank\">BBCode</a> : ";
		if($allow_bbcode == 1)
			echo "On<br>\n";
		else
			echo "Off<BR>\n";

		if($quote) {
			$sql = "SELECT p.post_text, p.post_time, u.uname FROM $prefix"._posts." p, $prefix"._users." u WHERE post_id = '$post' AND p.poster_id = u.uid";
			if($r = mysql_query($sql, $db)) {
				$m = mysql_fetch_array($r);
				$text = desmile($m[post_text]);
				$text = str_replace("<BR>", "\n", $text);
				$text = stripslashes($text);
				$text = bbdecode($text);
				$reply = "[quote]\nOn $m[post_time], $m[uname] wrote:\n$text\n[/quote]";
			}
			else {
				$reply = "Error Contacting database. Please try again.\n";
			}
		}				
		?>		
		</font></TD>
		<TD  BGCOLOR="<?php echo $bgcolor1?>"><TEXTAREA CLASS=textbox NAME="message" ROWS=10 COLS=45 WRAP="VIRTUAL"><?php echo $reply?></TEXTAREA><BR>
		<?php putitems(); ?>
		</TD>
	</TR>
	<TR ALIGN="LEFT">
		<TD  BGCOLOR="<?php echo $bgcolor3?>" width=25%><b><?php echo translate("Options: ");?></b></TD>
		<TD  BGCOLOR="<?php echo $bgcolor1?>" >
		<?php
			if($allow_html == 1) {
		?>	
				<INPUT TYPE="CHECKBOX" NAME="html"><?php echo translate("Disable HTML on this Post");?><BR>
		<?php
			}
		?>
		<?php
			if($allow_bbcode == 1) {
		?>	
				<INPUT TYPE="CHECKBOX" NAME="bbcode"><?php echo "".translate("Disable")." <a href=\"modules.php?op=modload&amp;name=Forum&amp;file=bbcode_ref\" target=\"_blank\"><i>BBCode</i></a> ".translate("on this Post");?><BR>
		<?php
			}
		?>

		<INPUT TYPE="CHECKBOX" NAME="smile"><?php echo "".translate("Disable")." <a href=\"modules.php?op=modload&amp;name=Forum&amp;file=bb_smilies\" target=\"_blank\"><i>".translate("smilies")."</i></a> ".translate("on this Post");?><BR>
		<?php
			if($allow_sig == 1) {
			    $asig = mysql_query("select attachsig from $prefix"._users_status." where uid='$cookie[0]'");
			    list($attachsig) = mysql_fetch_row($asig);
				if ($attachsig == 1) {
				    $s = "CHECKED";
				}

		?>
				<INPUT TYPE="CHECKBOX" NAME="sig" <?php echo $s?>><?php echo translate("Show signature");?> <font size=-2>(<?php echo translate("This can be altered or added in your profile");?>)</font><BR>
		<?php
			}
		?>
		</TD>
	</TR>
	<TR>
		<TD  BGCOLOR="<?php echo $bgcolor1?>" colspan=2 ALIGN="CENTER">
		<INPUT TYPE="HIDDEN" NAME="op" VALUE="modload">
		<INPUT TYPE="HIDDEN" NAME="name" VALUE="Forum">
		<INPUT TYPE="HIDDEN" NAME="file" VALUE="reply">
		<INPUT TYPE="HIDDEN" NAME="forum" VALUE="<?php echo $forum?>">
		<INPUT TYPE="HIDDEN" NAME="topic" VALUE="<?php echo $topic?>">
		<INPUT TYPE="SUBMIT" NAME="submit" VALUE="Submit">&nbsp;<INPUT TYPE="RESET" VALUE="Reset">
		&nbsp;<INPUT TYPE="SUBMIT" NAME="cancel" VALUE="<?php echo translate("Cancel Post");?>">
	</TR>
<?php
}
?>
	</TABLE></TD></TR></TABLE>
	</FORM>
	<BR>
	<CENTER>
	<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="100%"><TR><TD>
	<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
	<TR><TD COLSPAN=2 BGCOLOR="<?php echo $bgcolor2;?>" ALIGN="CENTER"><b><FONT SIZE=1 COLOR="<?php echo $textcolor2;?>"><?php echo translate("Topic Review");?></b></FONT></TD></TR>
<?php
$sql = "SELECT p.*, u.*, s.* FROM $prefix"._posts." p, $prefix"._users." u, $prefix"._users_status." s WHERE topic_id = '$topic' AND p.poster_id = u.uid AND p.poster_id = s.uid";
if(!$result = mysql_query($sql, $db))
    forumerror("0001");
$myrow = mysql_fetch_array($result);
$count=0;
do {
if (($count%2)!=0) $color=$bgcolor3;
else $color=$bgcolor1;
   echo "<TR BGCOLOR=\"$color\" ALIGN=\"LEFT\">\n";
   echo "<TD valign=top WIDTH=\"20%\"><B>$myrow[uname]</B><BR>";
   $posts = $myrow[posts];
   if($myrow[uid] != 1) {
	   if($myrow[rank] != 0) 
	     $sql = "SELECT rank_title FROM $prefix"._ranks." WHERE rank_id = '$myrow[rank]'";
	   else
	     $sql = "SELECT rank_title FROM $prefix"._ranks." WHERE rank_min <= " . $myrow[posts] . " AND rank_max >= " . $myrow[posts] . " AND rank_special = 0";
	   if(!$rank_result = mysql_query($sql, $db))
	    forumerror("0001");
	   list($rank) = mysql_fetch_array($rank_result);
	   
	   echo "<font size=1>" . stripslashes($rank) . "</font>";
	   echo "<BR><font size=1>".translate("Joined: ")."$myrow[user_regdate]</font>\n";
	   echo "<br><font size=1>".translate("Posts: ")."$posts<br>\n";
	   echo "".translate("From: ")."$myrow[user_from]<br></FONT>\n";
	}
   else {
	   echo "<font size=1>".translate("Unregistered User")."</font>";
	}
   if ($myrow[user_avatar] != '')
   echo "<img src=\"images/forum/avatar/$myrow[user_avatar]\"></TD>";
   if ($myrow[image] != "") echo "<TD><img src=\"images/forum/subject/$myrow[image]\"><font size=1>";
   else echo "<TD><img src=\"images/forum/icons/posticon.gif\"><font size=1>";
   echo "&nbsp;".translate("Posted: ")."$myrow[post_time]&nbsp;&nbsp;&nbsp";
   echo "<HR></font>\n";
   $message = stripslashes($myrow[post_text]);
   $message = eregi_replace("\[addsig]", "<BR>-----------------<BR>" . $myrow[user_sig], $message);
   echo $message."<BR><BR>";
   echo "</TD></TR>";
   $count++;
} while($myrow = mysql_fetch_array($result));
echo "</TABLE></TD></TR></TABLE>\n"; 
}
include ("footer.php");
}

# End AddOn Modules

switch($func) {

    default:
    ReplyForum($topic,$forum,$mod,$smile,$sig,$bbcode,$html,$message,$image_subject,$subject,$password,$username,$submit,$quote);
    break;
}

?>