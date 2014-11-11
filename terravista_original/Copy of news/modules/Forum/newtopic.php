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

function NewTopic($forum,$mod,$smile,$sig,$notify2,$bbcode,$html,$message,$image_subject,$subject,$password,$username,$submit) {
$forumpage = 1;
global $user,$REMOTE_ADDR,$HTTP_COOKIE_VARS,$bgcolor1,$bgcolor2,$bgcolor3,$textcolor1,$textcolor2,$prefix;

if($cancel) {
	header("Location: modules.php?op=modload&name=Forum&file=viewforum&forum=$forum");
}

include('config.php');
include('functions.php');
include('auth.php');

$sql = "SELECT forum_name, forum_access FROM $prefix"._forums." WHERE (forum_id = '$forum')";
if(!$result = mysql_query($sql, $db))
    forumerror("0001");
$myrow = mysql_fetch_array($result);
$forum_name = $myrow[forum_name];
$forum_access = $myrow[forum_access];
$forum_id = $forum;

if(!does_exists($forum, $db, "forum")) {
    forumerror("0030");
}

if($submit) {
	if($message == '')  {	
		$stop=1;
		}
	if($subject == '') {
		$stop=1;
		}
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
	}
	else {
	include('header.php');
	       $user = base64_decode($user);
	       $userdata = explode(":", $user);
	       $userdata = get_userdata($userdata[1], $db); 
	}
	// Either valid user/pass, or valid session. continue with post.
	if ($stop != 1) {
	if($allow_html == 0 || isset($html))
		$message = htmlspecialchars($message);
	if($sig && $userdata[uid] != 1) {
                $message .= "[addsig]";
        }
	if($allow_bbcode == 1 && !($HTTP_POST_VARS[bbcode]))
		$message = bbencode($message);
	$message = str_replace("\n", "<BR>", $message);
	if(!$smile) {
		$message = smile($message);
	}
	$message = make_clickable($message);
	$message = addslashes($message);
	$subject = strip_tags($subject);
	$subject = addslashes($subject);
	$poster_ip = $REMOTE_ADDR;
	$time = date("Y-m-d H:i");
	$sql = "INSERT INTO $prefix"._forumtopics." (topic_title, topic_poster, forum_id, topic_time, topic_notify) VALUES ('$subject', '$userdata[uid]', '$forum', '$time'";
	if(isset($notify2) && $userdata[uid] != 1) {
		$sql .= ", '1'";
	} else {
		$sql .= ", '0'";
	}
	$sql .= ")";
	if(!$result = mysql_query($sql, $db)) {
	    forumerror("0020");
	}
	$topic_id = mysql_insert_id($db);
	$sql = "INSERT INTO $prefix"._posts." (topic_id, image, forum_id, poster_id, post_text, post_time, poster_ip) VALUES ('$topic_id', '$image_subject', '$forum', '$userdata[uid]', '$message', '$time', '$poster_ip')";
	if(!$result = mysql_query($sql, $db)) {
	    forumerror("0020");
	}
	if($userdata[uid] != 1) {
		$sql = "UPDATE $prefix"._users_status." SET posts=posts+1 WHERE (uid = $userdata[uid])";
		$result = mysql_query($sql, $db);
		if (!$result) {
			echo mysql_error() . "<br>\n";
			    forumerror("0029");
		}
	}
	
	$topic = $topic_id;
	OpenTable();
	echo "<center>".translate("Your topic has been posted")."<br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewtopic&amp;topic=$topic_id&amp;forum=$forum\">".translate("Click here to view your post.")."</center></a>";
	CloseTable();
	}
	
	else {
	    OpenTable();
	    echo "<center>".translate("You must provide subject and message to post your topic.")."</center>";
	    CloseTable();
	}

} else {
include('header.php');
JScriptForum();
$moderator = get_moderator($mod,$db);
    OpenTable();
?>
	<P ALIGN=LEFT><FONT SIZE="2"><b><?php echo "".translate("Moderated By: ")."</b><a href=\"user.php?op=userinfo&uname=$moderator\">$moderator";?></a><br>
	<b><?php echo translate("Post New Topic in:");?></b>
	&nbsp;<a href="modules.php?op=modload&amp;name=Forum&amp;file=viewforum&amp;forum=<?php echo $forum?>"><?php echo $forum_name?></a><br>
	<a href=\"modules.php?op=modload&amp;name=Forum&amp;file=index\">Go to <?php echo "$sitename"; ?> Forums Index</a>
	</font></p>
	<?php CloseTable(); ?>
	<FORM ACTION="modules.php" METHOD="POST" NAME="coolsus">
	<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="100%"><TR><TD>
	<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
	<TR BGCOLOR="<?php echo $bgcolor2?>" ALIGN="LEFT">
		<TD width=25%><FONT COLOR="<?php echo $textcolor2;?>"><?php echo "<b>".translate("About Posting:")."</b>";?></FONT></TD>
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
	        $allow_to_post = 1;
	        }
	        else {
	        echo "<TR>";
	        echo "<TD BGCOLOR=$bgcolor3 COLSPAN=2 ALIGN=CENTER>".translate("You are not allowed to post in this forum")."<BR><A HREF=\"javascript:history.go(-1)\">".translate("back")."</A></TD>";
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
		$allow_to_post = 1;
	} elseif ($forum_access == 2) {
		echo "<TR ALIGN=LEFT>";
		echo "<TD  BGCOLOR=$bgcolor3  width=25%><b>".translate("Nickname: ")."<b></TD>";
		echo "<TD  BGCOLOR=$bgcolor1>";
		echo "<INPUT CLASS=textbox TYPE=\"TEXT\" NAME=\"username\" SIZE=\"25\" MAXLENGTH=\"40\"></TD> \n";
		echo "<TR ALIGN=\"LEFT\"> \n";
		echo "<TD BGCOLOR=\"$bgcolor3\" width=25%><b>".translate("Password: ")."</b></TD> \n";
		echo "<TD BGCOLOR=\"$bgcolor1\"><INPUT CLASS=textbox TYPE=\"PASSWORD\" NAME=\"password\" SIZE=\"25\" MAXLENGTH=\"25\"></TD> \n";
		echo "</TR> \n";
		$allow_to_post = 1;
	} elseif ($forum_access == 0) {
	        $allow_to_post = 1;
	}
	
if ($allow_to_post) {
?>
	<TR ALIGN="LEFT">
		<TD  BGCOLOR="<?php echo $bgcolor3?>" width=25%><b><?php echo translate("Subject: ");?></b></TD>
		<TD  BGCOLOR="<?php echo $bgcolor1?>"> <INPUT CLASS=textbox TYPE="TEXT" NAME="subject" SIZE="50" MAXLENGTH="100"></TD>
	</TR>
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
			echo "<IMG SRC=\"images/forum/subject/$file\" BORDER=0 Alt=\"\">&nbsp;";
			}
		if ($count == "8") { echo "<br>"; $count = 1; }
		$count++;
		}
	?>
		 </TD>
	</TR>
	<TR ALIGN="LEFT">
		<TD  BGCOLOR="<?php echo $bgcolor3?>" width=25% VALIGN="TOP"><b><?php echo translate("Message: ");?></b><br><br>
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
		?>
		</font></TD>
		<TD  BGCOLOR="<?php echo $bgcolor1?>"><TEXTAREA CLASS=textbox NAME="message" ROWS=10 COLS=45 WRAP="VIRTUAL"></TEXTAREA><br>
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
				<INPUT TYPE="CHECKBOX" NAME="bbcode"><?php echo "".translate("Disable")." <a href=\"modules.php?op=modload&amp;name=Forum&amp;file=bbcode_ref\" target=\"_blank\"><i>".translate("BBCode")."</i></a> ".translate("on this Post");?><BR>
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
		<INPUT TYPE="CHECKBOX" NAME="notify2"><?php echo translate("Notify by email when replies are posted");?><BR>
		</TD>
	</TR>
	<TR>
		<TD  BGCOLOR="<?php echo $bgcolor1?>" colspan=2 ALIGN="CENTER">
		<INPUT TYPE="HIDDEN" NAME="op" VALUE="modload">
		<INPUT TYPE="HIDDEN" NAME="name" VALUE="Forum">
		<INPUT TYPE="HIDDEN" NAME="file" VALUE="newtopic">
		<INPUT TYPE="HIDDEN" NAME="forum" VALUE="<?php echo $forum?>">
		<INPUT TYPE="SUBMIT" NAME="submit" VALUE="Submit">&nbsp;<INPUT TYPE="RESET" VALUE="Reset">
		&nbsp;<INPUT TYPE="SUBMIT" NAME="cancel" VALUE="<?php echo translate("Cancel Post");?>">
		</FORM>
	</TD></TR>
<?php
}
?>
	</TABLE></TD></TR></TABLE>

<?php
}
include ("footer.php");
}

# End AddOn Modules

switch($func) {

    default:
    NewTopic($forum,$mod,$smile,$sig,$notify2,$bbcode,$html,$message,$image_subject,$subject,$password,$username,$submit);
    break;
}

?>