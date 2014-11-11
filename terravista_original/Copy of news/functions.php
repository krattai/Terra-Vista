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
# PHP-NUKE Add-On 5.0.RC1 : PHPBB Forum AddOn
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

function putitems() {
	global $prefix;
    echo "Click on the <a href=\"modules.php?op=modload&name=Forum&file=bb_smilies\">Smilies</a> to insert it on your Message:<br>";
   if ($activesmiles = mysql_query("SELECT code,smile_url,active FROM $prefix"._smiles."")){
      while ($actsmiles = mysql_fetch_array($activesmiles)) {
      if ($actsmiles[active]==1) {
    echo "&nbsp;<A href=\"javascript: x()\" onClick=\"DoSmilie(' $actsmiles[code] ');\"><IMG width=\"15\" height=\"15\" src=\"images/forum/smilies/$actsmiles[smile_url]\" border=\"0\" alt=\"$actsmiles[code]\"></A>&nbsp;";
	}
	}
	}    
	echo "<br><br>";
    echo "Click on the buttoms to add <a href=\"modules.php?op=modload&name=Forum&file=bbcode_ref\">BBCode</a> to your message:<br><br>";
    echo "<A href=\"javascript: x()\" onClick=\"DoPrompt('url');\"><IMG src=\"images/forum/b_url.gif\" width=\"83\" height=\"21\" border=\"0\" alt=\"BBCode: Web Address\"></A>";
    echo "<A href=\"javascript: x()\" onClick=\"DoPrompt('email');\"><IMG src=\"images/forum/b_email.gif\" width=\"83\" height=\"21\" border=\"0\" alt=\"BBCode: Email Address\"></A>";
    echo "<A href=\"javascript: x()\" onClick=\"DoPrompt('image');\"><IMG src=\"images/forum/b_image.gif\" width=\"83\" height=\"21\" border=\"0\" alt=\"BBCode: Load Image from Web\"></A>";
    echo "<A href=\"javascript: x()\" onClick=\"DoPrompt('bold');\"><IMG src=\"images/forum/b_bold.gif\" width=\"83\" height=\"21\" border=\"0\" alt=\"BBCode: Bold Text\"></A>";
    echo "<A href=\"javascript: x()\" onClick=\"DoPrompt('italic');\"><IMG src=\"images/forum/b_italic.gif\" width=\"83\" height=\"21\" border=\"0\" alt=\"BBCode: Italic Text\"></A><br>";
    echo "<A href=\"javascript: x()\" onClick=\"DoPrompt('quote');\"><IMG src=\"images/forum/b_quote.gif\" width=\"83\" height=\"21\" border=\"0\" alt=\"BBCode: Quote\"></A>";
    echo "<A href=\"javascript: x()\" onClick=\"DoPrompt('code');\"><IMG src=\"images/forum/b_code.gif\" width=\"83\" height=\"21\" border=\"0\" alt=\"BBCode: Code\"></A>";
    echo "<A href=\"javascript: x()\" onClick=\"DoPrompt('listopen');\"><IMG src=\"images/forum/b_listopen.gif\" width=\"83\" height=\"21\" border=\"0\" alt=\"BBCode: Open List\"></A>";
    echo "<A href=\"javascript: x()\" onClick=\"DoPrompt('listitem');\"><IMG src=\"images/forum/b_listitem.gif\" width=\"83\" height=\"21\" border=\"0\" alt=\"BBCode: List Item\"></A>";
    echo "<A href=\"javascript: x()\" onClick=\"DoPrompt('listclose');\"><IMG src=\"images/forum/b_listclose.gif\" width=\"83\" height=\"21\" border=\"0\" alt=\"BBCode: Close List\"></A>";
    
}

function get_total_topics($forum_id, $db) {
	global $prefix;
	$sql = "SELECT count(*) AS total FROM $prefix"._forumtopics." WHERE forum_id = '$forum_id'";
	if(!$result = mysql_query($sql, $db))
		return("ERROR");
	if(!$myrow = mysql_fetch_array($result))
		return("ERROR");
	
	return($myrow[total]);
}

function get_total_posts($id, $db, $type) {
	global $prefix;
	switch($type) {
		
		case 'users':
		    $sql = "SELECT count(*) AS total FROM $prefix"._users." WHERE uid != 1";
		break;
		
		case 'all':
		    $sql = "SELECT count(*) AS total FROM $prefix"._posts."";
		break;
		
		case 'forum':
		    $sql = "SELECT count(*) AS total FROM $prefix"._posts." WHERE forum_id = '$id'";
		break;
		
		case 'topic':
		    $sql = "SELECT count(*) AS total FROM $prefix"._posts." WHERE topic_id = '$id'";
		break;

		case 'user':
		    forumerror(0031);
	}

	if(!$result = mysql_query($sql, $db))
		return("ERROR");
	if(!$myrow = mysql_fetch_array($result))
		return("0");
	return($myrow[total]);
}

function get_last_post($id, $db, $type) {
	global $prefix;
	switch($type) {
		case 'forum':
			$sql = "SELECT p.post_time, p.poster_id, u.uname FROM $prefix"._posts." p, $prefix"._users." u WHERE p.forum_id = '$id' AND p.poster_id = u.uid ORDER BY post_time DESC";
		break;
		case 'topic':
			$sql = "SELECT p.post_time, u.uname FROM $prefix"._posts." p, $prefix"._users." u WHERE p.topic_id = '$id' AND p.poster_id = u.uid ORDER BY post_time DESC";
		break;
	}
	if(!$result = mysql_query($sql, $db))
		return("ERROR");

	if(!$myrow = mysql_fetch_array($result))
		return("No posts");
	$val = sprintf("%s <br> by %s", $myrow[post_time], $myrow[uname]);
	return($val);
}

function get_moderator($user_id, $db) {
	global $prefix;
	if ($user_id == 0) {
		return("None");
	}
	$sql = "SELECT uname FROM $prefix"._users." WHERE uid = '$user_id' ";
	if(!$result = mysql_query($sql, $db))
		return("ERROR");
	if(!$myrow = mysql_fetch_array($result))
		return("ERROR");
	return("$myrow[uname]");
}

function get_forum_mod($forum_id, $db) {
	global $prefix;
	$sql = "SELECT forum_moderator FROM $prefix"._forums." WHERE forum_id = '$forum_id'";
	if(!$result = mysql_query($sql, $db))
                return("-1");
        if(!$myrow = mysql_fetch_array($result))
                return("-1");
        return("$myrow[forum_moderator]");
}

/**
 * Nathan Codding - July 19, 2000
 * Checks the given password against the DB for the given username. Returns true if good, false if not.
 */
function check_user_pw($username, $password, $db, $system) {
	global $prefix;
	#if (!$system) $password = crypt($password);
	if (!$system) $password = crypt($password,substr($password,0,2));
	else $password = $password;
	$sql = "SELECT uid FROM $prefix"._users." WHERE (uname = '$username') AND (pass = '$password')";
	$resultID = mysql_query($sql, $db);
	if (!$resultID) {
		echo mysql_error() . "<br>";
		forumerror(0032);
	}
	return mysql_num_rows($resultID);
} // check_user_pw()


/**
 * Nathan Codding - July 19, 2000
 * Returns a count of the given userid's private messages.
 */
function get_pmsg_count($user_id, $db) {
	global $prefix;
	$sql = "SELECT msg_id FROM $prefix"._priv_msgs." WHERE (to_userid = $user_id)";
	$resultID = mysql_query($sql);
	if (!$resultID) {
		echo mysql_error() . "<br>";
		forumerror(0033);
	}
	return mysql_num_rows($resultID);
} // get_pmsg_count()


/**
 * Nathan Codding - July 19, 2000
 * Checks if a given username exists in the DB. Returns true if so, false if not.
 */
function check_username($username, $db) {
	global $prefix;
	$sql = "SELECT uid FROM $prefix"._users." WHERE (uname = '$username')";
	$resultID = mysql_query($sql);
	if (!$resultID) {
		echo mysql_error() . "<br>";
		forumerror(0034);
	}
	return mysql_num_rows($resultID);
} // check_username()


/**
 * Nathan Codding, July 19/2000
 * Get a user's data, given their user ID. 
 */

function get_userdata_from_id($userid, $db) {
		global $prefix;
	$sql = "SELECT u.*, s.* FROM $prefix"._users." u, $prefix"._users_status." s WHERE s.uid = $userid AND u.uid = $userid";
	if(!$result = mysql_query($sql, $db)) {
		$userdata = array("error" => "1");
		return ($userdata);
	}
	if(!$myrow = mysql_fetch_array($result)) {
		$userdata = array("error" => "1");
		return ($userdata);
	}
	
	return($myrow);
}

function get_userdata($username, $db) {
	global $prefix;
	$sql = "SELECT * FROM $prefix"._users." WHERE uname = '$username'";
	if(!$result = mysql_query($sql, $db))
		$userdata = array("error" => "1");
	if(!$myrow = mysql_fetch_array($result))
		$userdata = array("error" => "1");
	
	return($myrow);
}


function does_exists($id, $db, $type) {
	global $prefix;
	switch($type) {
		case 'forum':
			$sql = "SELECT forum_id FROM $prefix"._forums." WHERE forum_id = '$id'";
		break;
		case 'topic':
			$sql = "SELECT topic_id FROM $prefix"._forumtopics." WHERE topic_id = '$id'";
		break;
	}
	if(!$result = mysql_query($sql, $db))
		return(0);
	if(!$myrow = mysql_fetch_array($result)) 
		return(0);
	return(1);
}

function is_locked($topic, $db) {
	global $prefix;
	$sql = "SELECT topic_status FROM $prefix"._forumtopics." WHERE topic_id = '$topic'";
	if(!$r = mysql_query($sql, $db))
		return(FALSE);
	if(!$m = mysql_fetch_array($r))
		return(FALSE);
	if($m[topic_status] == 1)
		return(TRUE);
	else
		return(FALSE);
}

/**
 * bbdecode/bbencode functions:
 * Rewritten - Nathan Codding - Aug 24, 2000
 * Using Perl-Compatible regexps now. Won't kill special chars 
 * outside of a [code]...[/code] block now, and all BBCode tags
 * are implemented.
 * Note: the "i" matching switch is used, so BBCode tags are 
 * case-insensitive.
 */
function bbdecode($message) {
	global $prefix;				
		// Undo [code]
		$message = preg_replace("#<!-- BBCode Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Code:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><PRE>(.*?)</PRE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode End -->#s", "[code]\\1[/code]", $message);
				
		// Undo [quote]						
		$message = preg_replace("#<!-- BBCode Quote Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Quote:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><BLOCKQUOTE>(.*?)</BLOCKQUOTE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode Quote End -->#s", "[quote]\\1[/quote]", $message);
		
		// Undo [b] and [i]
		$message = preg_replace("#<!-- BBCode Start --><B>(.*?)</B><!-- BBCode End -->#s", "[b]\\1[/b]", $message);
		$message = preg_replace("#<!-- BBCode Start --><I>(.*?)</I><!-- BBCode End -->#s", "[i]\\1[/i]", $message);
		
		// Undo [url] (both forms)
		$message = preg_replace("#<!-- BBCode Start --><A HREF=\"http://(.*?)\" TARGET=\"_blank\">(.*?)</A><!-- BBCode End -->#s", "[url=\\1]\\2[/url]", $message);
		
		// Undo [email]
		$message = preg_replace("#<!-- BBCode Start --><A HREF=\"mailto:(.*?)\">(.*?)</A><!-- BBCode End -->#s", "[email]\\1[/email]", $message);
		
		// Undo [img]
		$message = preg_replace("#<!-- BBCode Start --><IMG SRC=\"(.*?)\"><!-- BBCode End -->#s", "[img]\\1[/img]", $message);
		
		// Undo lists (unordered/ordered)
	
		// unordered list code..
		$matchCount = preg_match_all("#<!-- BBCode ulist Start --><UL>(.*?)</UL><!-- BBCode ulist End -->#s", $message, $matches);
	
		for ($i = 0; $i < $matchCount; $i++)
		{
			$currMatchTextBefore = preg_quote($matches[1][$i]);
			$currMatchTextAfter = preg_replace("#<LI>#s", "[*]", $matches[1][$i]);
		
			$message = preg_replace("#<!-- BBCode ulist Start --><UL>$currMatchTextBefore</UL><!-- BBCode ulist End -->#s", "[list]" . $currMatchTextAfter . "[/list]", $message);
		}
		
		// ordered list code..
		$matchCount = preg_match_all("#<!-- BBCode olist Start --><OL TYPE=([A1])>(.*?)</OL><!-- BBCode olist End -->#si", $message, $matches);
		
		for ($i = 0; $i < $matchCount; $i++)
		{
			$currMatchTextBefore = preg_quote($matches[2][$i]);
			$currMatchTextAfter = preg_replace("#<LI>#s", "[*]", $matches[2][$i]);
			
			$message = preg_replace("#<!-- BBCode olist Start --><OL TYPE=([A1])>$currMatchTextBefore</OL><!-- BBCode olist End -->#si", "[list=\\1]" . $currMatchTextAfter . "[/list]", $message);
		}	
		
		return($message);
}

function bbencode($message) {
	global $prefix;
	// [CODE] and [/CODE] for posting code (HTML, PHP, C etc etc) in your posts.
	$matchCount = preg_match_all("#\[code\](.*?)\[/code\]#si", $message, $matches);
	
	for ($i = 0; $i < $matchCount; $i++)
	{
		$currMatchTextBefore = preg_quote($matches[1][$i]);
		$currMatchTextAfter = htmlspecialchars($matches[1][$i]);
		$message = preg_replace("#\[code\]$currMatchTextBefore\[/code\]#si", "<!-- BBCode Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Code:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><PRE>$currMatchTextAfter</PRE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode End -->", $message);
	}

	// [QUOTE] and [/QUOTE] for posting replies with quote, or just for quoting stuff.	
	$message = preg_replace("#\[quote\](.*?)\[/quote]#si", "<!-- BBCode Quote Start --><TABLE BORDER=0 ALIGN=CENTER WIDTH=85%><TR><TD><font size=-1>Quote:</font><HR></TD></TR><TR><TD><FONT SIZE=-1><BLOCKQUOTE>\\1</BLOCKQUOTE></FONT></TD></TR><TR><TD><HR></TD></TR></TABLE><!-- BBCode Quote End -->", $message);
	
	// [b] and [/b] for bolding text.
	$message = preg_replace("#\[b\](.*?)\[/b\]#si", "<!-- BBCode Start --><B>\\1</B><!-- BBCode End -->", $message);
	
	// [i] and [/i] for italicizing text.
	$message = preg_replace("#\[i\](.*?)\[/i\]#si", "<!-- BBCode Start --><I>\\1</I><!-- BBCode End -->", $message);
	
	// [url]www.phpbb.com[/url] code..
	$message = preg_replace("#\[url\](http://)?(.*?)\[/url\]#si", "<!-- BBCode Start --><A HREF=\"http://\\2\" TARGET=\"_blank\">\\2</A><!-- BBCode End -->", $message);
	
	// [url=www.phpbb.com]phpBB[/url] code..
	$message = preg_replace("#\[url=(http://)?(.*?)\](.*?)\[/url\]#si", "<!-- BBCode Start --><A HREF=\"http://\\2\" TARGET=\"_blank\">\\3</A><!-- BBCode End -->", $message);
	
	// [email]user@domain.tld[/email] code..
	$message = preg_replace("#\[email\](.*?)\[/email\]#si", "<!-- BBCode Start --><A HREF=\"mailto:\\1\">\\1</A><!-- BBCode End -->", $message);
	
	// [img]image_url_here[/img] code..
	$message = preg_replace("#\[img\](.*?)\[/img\]#si", "<!-- BBCode Start --><IMG SRC=\"\\1\"><!-- BBCode End -->", $message);
	
	// unordered list code..
	$matchCount = preg_match_all("#\[list\](.*?)\[/list\]#si", $message, $matches);
	
	for ($i = 0; $i < $matchCount; $i++)
	{
		$currMatchTextBefore = preg_quote($matches[1][$i]);
		$currMatchTextAfter = preg_replace("#\[\*\]#si", "<LI>", $matches[1][$i]);
	
		$message = preg_replace("#\[list\]$currMatchTextBefore\[/list\]#si", "<!-- BBCode ulist Start --><UL>$currMatchTextAfter</UL><!-- BBCode ulist End -->", $message);
	}
	
	// ordered list code..
	$matchCount = preg_match_all("#\[list=([a1])\](.*?)\[/list\]#si", $message, $matches);
	
	for ($i = 0; $i < $matchCount; $i++)
	{
		$currMatchTextBefore = preg_quote($matches[2][$i]);
		$currMatchTextAfter = preg_replace("#\[\*\]#si", "<LI>", $matches[2][$i]);
		
		$message = preg_replace("#\[list=([a1])\]$currMatchTextBefore\[/list\]#si", "<!-- BBCode olist Start --><OL TYPE=\\1>$currMatchTextAfter</OL><!-- BBCode olist End -->", $message);
	}
		
	return($message);
}

function get_forum_name($forum_id, $db) {
	global $prefix;
	$sql = "SELECT forum_name FROM $prefix"._forums." WHERE forum_id = '$forum_id'";
	if(!$r = mysql_query($sql, $db))
		return("ERROR");
	if(!$m = mysql_fetch_array($r))
		return("None");
	return($m[forum_name]);
}


/**
 * Modified by Nathan Codding - July 20, 2000.
 * Made it only work on URLs and e-mail addresses preceeded by a space, in order to stop
 * mangling HTML code.
 *
 * The Following function was taken from the Scriplets area of http://www.phpwizard.net, and was written by Tobias Ratschiller.
 *   Visit phpwizard.net today, its an excellent site! 
 */

function make_clickable($text) {
	$ret = eregi_replace(" ([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])", " <a href=\"\\1://\\2\\3\" target=\"_blank\" target=\"_new\">\\1://\\2\\3</a>", $text);
        $ret = eregi_replace(" (([a-z0-9_]|\\-|\\.)+@([^[:space:]]*)([[:alnum:]-]))", " <a href=\"mailto:\\1\" target=\"_new\">\\1</a>", $ret);
        return($ret);
}

/**
 * Nathan Codding - August 24, 2000.
 * Takes a string, and does the reverse of the PHP standard function
 * htmlspecialchars().
 */
function undo_htmlspecialchars($input) {
	$input = preg_replace("/&gt;/i", ">", $input);
	$input = preg_replace("/&lt;/i", "<", $input);
	$input = preg_replace("/&quot;/i", "\"", $input);
	$input = preg_replace("/&amp;/i", "&", $input);
	
	return $input;
}

function searchblock() {
   global $textcolor1;
    OpenTable();
    echo "<form action=\"modules.php\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"op\" value=\"modload\">";
	echo "<input type=\"hidden\" name=\"name\" value=\"Forum\">";
	echo "<input type=\"hidden\" name=\"file\" value=\"searchbb\">";
    echo "<input type=\"hidden\" name=\"addterm\" value=\"any\">";
    echo "<input type=\"hidden\" name=\"sortby\" value=\"p.post_time\">";
    echo "&nbsp;&nbsp;<b>"._SEARCH."</b>&nbsp;<input type=\"text\" name=\"term\" size=\"15\">";
    echo "<input type=\"hidden\" name=\"submit\" value=\"submit\"></form>";
    echo "<div align=\"left\"><font size=\"2\">&nbsp;&nbsp;[ <a href=\"modules.php?op=modload&name=Forum&file=searchbb&addterms=any&sortby=p.post_time&adv=1\">Advanced Search</a> ]</font></div>";
    CloseTable();
}

function forumerror($e_code) {
    global $sitename, $header, $footer;
    if ($e_code == "0001") {
	$error_msg = "Could not connect to the forums database.";
    }
    if ($e_code == "0002") {
	$error_msg = "The forum you selected does not exist. Please go back and try again.";
    }
    if ($e_code == "0003") {
	$error_msg = "Password Incorrect.";
    }
    if ($e_code == "0004") {
	$error_msg = "Could not query the topics database.";
    }
    if ($e_code == "0005") {
	$error_msg = "Error getting messages from the database.";
    }
    if ($e_code == "0006") {
	$error_msg = "Please enter the Nickname and the Password.";
    }
    if ($e_code == "0007") {
	$error_msg = "You are not the Moderator of this forum therefore you can't perform this function.";
    }
    if ($e_code == "0008") {
	$error_msg = "You did not enter the correct password, please go back and try again.";
    }
    if ($e_code == "0009") {
	$error_msg = "Could not remove posts from the database.";
    }
    if ($e_code == "0010") {
	$error_msg = "Could not move selected topic to selected forum. Please go back and try again.";
    }
    if ($e_code == "0011") {
	$error_msg = "Could not lock the selected topic. Please go back and try again.";
    }
    if ($e_code == "0012") {
	$error_msg = "Could not unlock the selected topic. Please go back and try again.";
    }
    if ($e_code == "0013") {
	$error_msg = "Could not query the database. <BR>Error: mysql_error()";
    }
    if ($e_code == "0014") {
	$error_msg = "No such user or post in the database.";
    }
    if ($e_code == "0015") {
	$error_msg = "Search Engine was unable to query the forums database.";
    }
    if ($e_code == "0016") {
	$error_msg = "That user does not exist. Please go back and search again.";
    }
    if ($e_code == "0017") {
	$error_msg = "You must type a subject to post. You can't post an empty subject. Go back and enter the subject";
    }
    if ($e_code == "0018") {
	$error_msg = "You must choose message icon to post. Go back and choose message icon.";
    }
    if ($e_code == "0019") {
	$error_msg = "You must type a message to post. You can't post an empty message. Go back and enter a message.";
    }
    if ($e_code == "0020") {
	$error_msg = "Could not enter data into the database. Please go back and try again.";
    }
    if ($e_code == "0021") {
	$error_msg = "Can't delete the selected message.";
    }
    if ($e_code == "0022") {
	$error_msg = "An error ocurred while querying the database.";
    }
    if ($e_code == "0023") {
	$error_msg = "Selected message was not found in the forum database.";
    }
    if ($e_code == "0024") {
	$error_msg = "You can't reply to that message. It wasn't sent to you.";
    }
    if ($e_code == "0025") {
	$error_msg = "You can't post a reply to this topic, it has been locked. Contact the administrator if you have any question.";
    }
    if ($e_code == "0026") {
	$error_msg = "The forum or topic you are attempting to post to does not exist. Please try again.";
    }
    if ($e_code == "0027") {
	$error_msg = "You must enter your username and password. Go back and do so.";
    }
    if ($e_code == "0028") {
	$error_msg = "You have entered an incorrect password. Go back and try again.";
    }
    if ($e_code == "0029") {
	$error_msg = "Couldn't update post count.";
    }
    if ($e_code == "0030") {
	$error_msg = "The forum you are attempting to post to does not exist. Please try again.";
    }
    if ($e_code == "0031") {
	return(0);
    }
    if ($e_code == "0032") {
	$error_msg = "Error doing DB query in check_user_pw()";
    }
    if ($e_code == "0033") {
	$error_msg = "Error doing DB query in get_pmsg_count";
    }
    if ($e_code == "0034") {
	$error_msg = "Error doing DB query in check_username()";
    }
    if ($e_code == "0035") {
	$error_msg = "You can't edit a post that's not yours.";
    }
    if ($e_code == "0036") {
	$error_msg = "You do not have permission to edit this post.";
    }
    if ($e_code == "0037") {
	$error_msg = "You did not supply the correct password or do not have permission to edit this post. Please go back and try again.";
    }
    if (!isset($header)) {
	include("header.php");
    }
    OpenTable2();
    echo "<center><font size=\"2\"><b>$sitename Error</b></font><br><br>";
    echo "Error Code: $e_code<br><br><br>";
    echo "<b>ERROR:</b> $error_msg<br><br><br>";
    echo "[ <a href=\"javascript:history.go(-1)\">Go Back</a> ]<br><br>";
    CloseTable2();
    include("footer.php");
    die("");
}

Function JScriptForum() {
   	echo "<SCRIPT type=\"text/javascript\"><!--\n\n";
    echo "function x () {\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";    
    echo "function DoSmilie(addSmilie) {\n";
    echo "\n";
    echo "var addSmilie;\n";
    echo "var revisedMessage;\n";
    echo "var currentMessage = document.coolsus.message.value;\n";
    echo "revisedMessage = currentMessage+addSmilie;\n";
    echo "document.coolsus.message.value=revisedMessage;\n";
    echo "document.coolsus.message.focus();\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";
    echo "function DoPrompt(action) {\n";
    echo "var revisedMessage;\n";
    echo "var currentMessage = document.coolsus.message.value;\n";
    echo "\n";
    echo "if (action == \"url\") {\n";
    echo "var thisURL = prompt(\"Enter the URL for the link you want to add.\", \"http://\");\n";
    echo "var thisTitle = prompt(\"Enter the web site title\", \"Page Title\");\n";
    echo "var urlBBCode = \"[URL=\"+thisURL+\"]\"+thisTitle+\"[/URL]\";\n";
    echo "revisedMessage = currentMessage+urlBBCode;\n";
    echo "document.coolsus.message.value=revisedMessage;\n";
    echo "document.coolsus.message.focus();\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";
    echo "if (action == \"email\") {\n";
    echo "var thisEmail = prompt(\"Enter the email address you want to add.\", \"\");\n";
    echo "var emailBBCode = \"[EMAIL]\"+thisEmail+\"[/EMAIL]\";\n";
    echo "revisedMessage = currentMessage+emailBBCode;\n";
    echo "document.coolsus.message.value=revisedMessage;\n";
    echo "document.coolsus.message.focus();\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";
    echo "if (action == \"bold\") {\n";
    echo "var thisBold = prompt(\"Enter the text that you want to make bold.\", \"\");\n";
    echo "var boldBBCode = \"[B]\"+thisBold+\"[/B]\";\n";
    echo "revisedMessage = currentMessage+boldBBCode;\n";
    echo "document.coolsus.message.value=revisedMessage;\n";
    echo "document.coolsus.message.focus();\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";
    echo "if (action == \"italic\") {\n";
    echo "var thisItal = prompt(\"Enter the text that you want to make italic.\", \"\");\n";
    echo "var italBBCode = \"[I]\"+thisItal+\"[/I]\";\n";
    echo "revisedMessage = currentMessage+italBBCode;\n";
    echo "document.coolsus.message.value=revisedMessage;\n";
    echo "document.coolsus.message.focus();\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";
    echo "if (action == \"image\") {\n";
    echo "var thisImage = prompt(\"Enter the URL for the image you want to display.\", \"http://\");\n";
    echo "var imageBBCode = \"[IMG]\"+thisImage+\"[/IMG]\";\n";
    echo "revisedMessage = currentMessage+imageBBCode;\n";
    echo "document.coolsus.message.value=revisedMessage;\n";
    echo "document.coolsus.message.focus();\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";
    echo "if (action == \"quote\") {\n";
    echo "var quoteBBCode = \"[QUOTE]  [/QUOTE]\";\n";
    echo "revisedMessage = currentMessage+quoteBBCode;\n";
    echo "document.coolsus.message.value=revisedMessage;\n";
    echo "document.coolsus.message.focus();\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";
    echo "if (action == \"code\") {\n";
    echo "var codeBBCode = \"[CODE]  [/CODE]\";\n";
    echo "revisedMessage = currentMessage+codeBBCode;\n";
    echo "document.coolsus.message.value=revisedMessage;\n";
    echo "document.coolsus.message.focus();\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";
    echo "if (action == \"listopen\") {\n";
    echo "var liststartBBCode = \"[LIST]\";\n";
    echo "revisedMessage = currentMessage+liststartBBCode;\n";
    echo "document.coolsus.message.value=revisedMessage;\n";
    echo "document.coolsus.message.focus();\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";
    echo "if (action == \"listclose\") {\n";
    echo "var listendBBCode = \"[/LIST]\";\n";
    echo "revisedMessage = currentMessage+listendBBCode;\n";
    echo "document.coolsus.message.value=revisedMessage;\n";
    echo "document.coolsus.message.focus();\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";
    echo "if (action == \"listitem\") {\n";
    echo "var thisItem = prompt(\"Enter the new list item. Note that each list group must be preceeded by a List Close and must be ended with List Close.\", \"\");\n";
    echo "var itemBBCode = \"[*]\"+thisItem;\n";
    echo "revisedMessage = currentMessage+itemBBCode;\n";
    echo "document.coolsus.message.value=revisedMessage;\n";
    echo "document.coolsus.message.focus();\n";
    echo "return;\n";
    echo "}\n";
    echo "\n";
    echo "}\n";
    echo "//--></SCRIPT>\n";
    echo "\n\n\n";
    }

?>
