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

function EditPost($post_id,$forum_id,$topic_id,$delete,$html,$bbcode,$image_subject,$message,$topic,$forum,$submit) {
global $user,$bgcolor1,$bgcolor2,$bgcolor3,$textcolor1,$textcolor2,$prefix,$system;
include("config.php");
include("functions.php");
include("auth.php");

$moderator = get_forum_mod($forum, $db);

if($submit) {
   include("header.php");
   $sql = "SELECT poster_id FROM $prefix"._posts." WHERE (post_id = $post_id)";
   
   $result = mysql_query($sql, $db);
   if (!$result) {
    forumerror("0022");
   }
   $row = mysql_fetch_array($result);
   
   $posterdata = get_userdata_from_id($row[poster_id], $db);
   $date = date("Y-m-d H:i");
   if ($user) {
   	       $user = base64_decode($user);
	       $cookie = explode(":", $user);
	       $userdata = get_userdata($cookie[1], $db);
      // valid session.. just check it's the right user.
      if (($posterdata[uid] != $userdata[uid] && $userdata[level] == 1)) {
	 $die = 1;
      } elseif ($userdata[level] == 2 && $userdata[uid] != get_forum_mod($forum_id, $db)) {
        forumerror("0035");
      }
   } else {
	forumerror("0036");
   }
   
   // IF we made it this far we are allowed to edit this message, yay!
    
   $message = eregi_replace("-----------------", "", $message);
   $message = str_replace("\n", "<BR>", $message);
   if ($user_sig)
   $message = str_replace(nl2br($user_sig), "[addsig]", $message);
   
   if($allow_html == 0 || isset($html) )
     $message = htmlspecialchars($message);
   if($allow_bbcode == 1 && !isset($bbcode))
     $message = bbencode($message);
   if(!$smile) 
     $message = smile($message);
							
   $message = str_replace("\n", "<BR>", $message);

   $message .= "<BR><BR><font size=1>[ This message was edited by: $userdata[uname] on $date ]</font>";
   $message = addslashes($message);
	if(!$delete) {
		$topic = $topic_id;
		$forum = $forum_id;
		$sql = "UPDATE $prefix"._posts." SET post_text = '$message', image='$image_subject' WHERE (post_id = '$post_id')";
		if(!$result = mysql_query($sql, $db))
		    forumerror("0001");
		OpenTable();
		echo "<center>".translate("Your post has been updated.")."<br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewtopic&amp;topic=$topic_id&amp;forum=$forum_id\">".translate("Click here to view the update")."<br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewforum&amp;forum=$forum_id\">".translate("Click here to return to the forum topic listing.")."</center><P></font>";
		CloseTable();
	}
	else {
		$sql = "DELETE FROM $prefix"._posts." WHERE post_id = '$post_id'";
		if(!$r = mysql_query($sql))
		    forumerror("0001");
		if(get_total_posts($topic_id, $db, "topic") == 0) {
			$sql = "DELETE FROM $prefix"._forumtopics." WHERE topic_id = '$topic_id'";
			if(!$r = mysql_query($sql, $db))
				forumerror("0001");
		}
		echo "<center>".translate("Your post has been deleted.")."<br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewforum&amp;forum=$forum_id\">".translate("Click here to return to the forum topic listing.")."<br><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=index\">".translate("Click here to return to the forum index.")."</center><P></font>";
	}

} else {

   include("header.php");
   $sql = "SELECT p.*, u.uname, u.uid, u.user_sig FROM $prefix"._posts." p, $prefix"._users." u WHERE (p.post_id = '$post_id') AND (p.poster_id = u.uid)";  
   if (!$result = mysql_query($sql, $db)) {
	forumerror("0001");
    }
   $myrow = mysql_fetch_array($result);
   if (isset($user)) {
   	$user = base64_decode($user);
	$cookie = explode(":", $user);
	$userdata = get_userdata($cookie[1], $db);
    	if ($userdata[uid] != $myrow[uid] && $userdata[uid] != $moderator) {
	    forumerror("0035");
	}
   }

   $message = $myrow[post_text];
   $message = eregi_replace("\[addsig]$", "\n-----------------\n" . $myrow[user_sig], $message);   
   $message = str_replace("<BR>", "\n", $message);
   $message = stripslashes($message);
   $message = desmile($message);
   $message = bbdecode($message);
   $message = undo_htmlspecialchars($message);
   list($day, $time) = split(" ", $myrow[post_time]);
?>
<FORM ACTION="modules.php" METHOD="POST">


<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="95%"><TR><TD  BGCOLOR="<?php echo $bgcolor1?>">
<TABLE BORDER="0" CELLPADDING="3" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $bgcolor2?>" ALIGN="LEFT">
	<TD ALIGN="CENTER" COLSPAN="2">

	<B><?php echo translate("Editing Post")?></B></TD>
</TR>

<?PHP
	if (isset($user)) {
			#if (!$system) $password = base64_decode($userdata[pass]);
		if (!$system) {
		    $password = crypt($userdata[pass],substr($userdata[pass],0,2));
		} else {
		    $password = $userdata[pass];
		}
	        if ($password == $cookie[2]) {
	    	    echo "<TR>";
		    echo "<TD BGCOLOR=$bgcolor3  width=25%><b>".translate("Nickname: ")."<b></TD>";
		    echo "<TD BGCOLOR=$bgcolor1>";
	    	    echo $userdata[uname] . " \n";
	    	    echo "</TD></TR> \n";
	        } else {
		    forumerror("0037");
		}
	} else {
	    forumerror("0036");
	}
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
		if ($file == "." || $file == "..") $a=1;
		else {
			if ($file == $myrow[image]) echo "<input type=\"radio\" value=\"$file\" name=\"image_subject\" checked>&nbsp;";
			else echo "<input type=\"radio\" value=\"$file\" name=\"image_subject\">&nbsp;";
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
		?>	
		</font></TD>
		<TD  BGCOLOR="<?php echo $bgcolor1?>"><TEXTAREA CLASS=textbox NAME="message" ROWS=10 COLS=45 WRAP="VIRTUAL"><?php echo $message?></TEXTAREA></TD>
	</TR>
	<TR ALIGN="LEFT">
		<TD  BGCOLOR="<?php echo $bgcolor3?>" width=25%><b><?php echo translate("Options: ");?></b></TD>
		<TD  BGCOLOR="<?php echo $bgcolor1?>" >
		<?php
			$now_hour = date("H");
			$now_min = date("i");
			list($hour, $min) = split(":", $time);
			if(($now_hour == $hour && $min_now - 30 < $min) || ($now_hour == $hour +1 && $now_min - 30 > 0)) {
		?>
				<INPUT TYPE="CHECKBOX" NAME="delete"><?php echo translate("Delete this Post");?><BR>
		<?php
			}
		?>
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
		</TD>
	</TR>
<TR>
	<TD  BGCOLOR="<?php echo $bgcolor1?>" colspan=2 ALIGN="CENTER">
	<INPUT TYPE="HIDDEN" NAME="op" VALUE="modload">
	<INPUT TYPE="HIDDEN" NAME="name" VALUE="Forum">
	<INPUT TYPE="HIDDEN" NAME="file" VALUE="editpost">	
	<INPUT TYPE="HIDDEN" NAME="post_id" VALUE="<?php echo $post_id?>">
	<INPUT TYPE="HIDDEN" NAME="forum_id" VALUE="<?php echo $forum?>">
	<INPUT TYPE="HIDDEN" NAME="topic_id" VALUE="<?php echo $topic?>">
        <INPUT TYPE="HIDDEN" NAME="user_sig" VALUE="<?php echo $myrow[user_sig]?>">
	<INPUT TYPE="SUBMIT" NAME="submit" VALUE="Submit">&nbsp;<INPUT TYPE="RESET" VALUE="Clear" NAME="clear">
</TR>
</TABLE></TD></TR></TABLE>
</FORM>
<?php
}
include('footer.php');
}

# End AddOn Modules

switch($func) {

    default:
    EditPost($post_id,$forum_id,$topic_id,$delete,$html,$bbcode,$image_subject,$message,$topic,$forum,$submit);
    break;
}

?>