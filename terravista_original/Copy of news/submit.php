<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!isset($mainfile)) { include("mainfile.php"); }

function defaultDisplay() {
    global $AllowableHTML, $prefix, $user, $cookie, $anonymous;;
    include ('header.php');
    OpenTable();
    echo "<center><font size=\"4\"><b>"._SUBMITNEWS."</b></font><br><br>";
    echo "<font size=\"2\"><i>"._SUBMITADVICE."</i></font></center><br><br>";
    CloseTable();
    echo "<br>";
    OpenTable();
    if (is_user($user)) getusrinfo($user);
    include("functions.php");
    echo "<p><form action=\"submit.php\" method=\"post\">"
	."<b>"._YOURNAME.":</b> ";
    if (is_user($user)) {
	cookiedecode($user);
	echo "<a href=\"user.php\">$cookie[1]</a> <font size=2>[ <a href=\"user.php?op=logout\">"._LOGOUT."</a> ]</font>";
    } else {
    	echo "$anonymous <font size=2>[ <a href=\"user.php\">"._NEWUSER."</a> ]</font>";
    }
    echo "<br><br>"
        ."<b>"._SUBTITLE."</b> "
        ."("._BEDESCRIPTIVE.")<br>"
        ."<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"80\"><br><font size=\"2\">("._BADTITLES.")</font>"
        ."<br><br>"
        ."<b>"._TOPIC.":</b> <select name=\"topic\">";
    $toplist = mysql_query("select topicid, topictext from $prefix"._topics." order by topictext");
    echo "<option value=\"\">"._SELECTTOPIC."</option>\n";
    while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
        if ($topicid==$topic) {
	    $sel = "selected ";
	}
    	echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select>"
        ."<br><br>"
        ."<b>"._ARTICLETEXT."</b> "
        ."("._HTMLISFINE.")<br>"
        ."<textarea cols=\"50\" rows=\"12\" name=\"story\"></textarea><br>"
        ."<font size=\"2\">"._ALLOWEDHTML."<br>";
    while (list($key,) = each($AllowableHTML)) echo " &lt;".$key."&gt;";
    echo "<br>("._AREYOUSURE.")</font><br><br>"
        ."<input type=\"submit\" name=\"op\" value=\""._PREVIEW."\"> ("._SUBPREVIEW.")</form>";
    CloseTable();
    include ('footer.php');
}

function PreviewStory($name, $address, $subject, $story, $topic) {
    global $user, $cookie, $tipath, $bgcolor1, $bgcolor2, $anonymous, $prefix;
    include ('header.php');
    $subject = stripslashes(check_html($subject, "nohtml"));
    $story = stripslashes(check_html($story, ""));
    OpenTable();
    echo "<center><font size=\"4\"><b>"._NEWSUBPREVIEW."</b></font>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><i>"._STORYLOOK."</i></center><br><br>";
    echo "<table width=\"70%\" bgcolor=\"$bgcolor2\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\"align=\"center\"><tr><td>"
	."<table width=\"100%\" bgcolor=\"$bgcolor1\" cellpadding=\"8\" cellspacing=\"1\" border=\"0\"><tr><td>";
    if ($topic=="") {
        $topicimage="AllTopics.gif";
        $warning = "<center><blink><b>"._SELECTTOPIC."</b></blink></center>";
    } else {
        $warning = "";
        $result = mysql_query("select topicimage from $prefix"._topics." where topicid='$topic'");
        list($topicimage) = mysql_fetch_row($result);
    }
    echo "<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\">";
    themepreview($subject, nl2br($story));
    echo "$warning"
	."</td></tr></table></td></tr></table>"
	."<br><br><center><font size=\"1\">"._CHECKSTORY."</font></center>";
    CloseTable();
    echo "<br>";    
    OpenTable();
    echo "<p><form action=\"submit.php\" method=\"post\">"
	."<b>"._YOURNAME.":</b> ";
    if (is_user($user)) {
    	cookiedecode($user);
	echo "<a href=\"user.php\">$cookie[1]</a> <font size=\"2\">[ <a href=\"user.php?op=logout\">"._LOGOUT."</a> ]</font>";
    } else {
	echo "$anonymous";
    }
    echo "<br><br><b>"._SUBTITLE.":</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"80\" value=\"$subject\">"
	."<br><br><b>"._TOPIC.": </b><select name=\"topic\">";
    $toplist = mysql_query("select topicid, topictext from $prefix"._topics." order by topictext");
    echo "<OPTION VALUE=\"\">"._SELECTTOPIC."</option>\n";
    while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
        if ($topicid==$topic) { $sel = "selected "; }
	    echo "<option $sel value=\"$topicid\">$topics</option>\n";
	    $sel = "";
        }
    echo "</select>"
        ."<br><br><b>"._ARTICLETEXT."</b> "
        ."("._HTMLISFINE.")<br>"
        ."<textarea cols=\"50\" rows=\"12\" name=\"story\">$story</textarea><br>"
        ."<font size=\"2\">("._AREYOUSURE.")</font><br><br>"
        ."<input type=\"submit\" name=\"op\" value=\""._PREVIEW."\"> <input type=\"submit\" name=\"op\" value=\""._OK."\">"
	."</form>";
    CloseTable();
    include ('footer.php');
}

function submitStory($name, $address, $subject, $story, $topic) {
    global $user, $EditedMessage, $cookie, $anonymous, $notify, $notify_email, $notify_subject, $notify_message, $notify_from, $prefix;
    if (is_user($user)) {
    	cookiedecode($user);
	$uid = $cookie[0];
	$name = $cookie[1];
    } else {
    	$uid = 1;
	$name = "$anonymous";
    }
    $subject = stripslashes(FixQuotes(check_html($subject, "nohtml")));
    $story = nl2br(stripslashes(FixQuotes(check_html($story, ""))));
    $result = mysql_query("insert into $prefix"._queue." values (NULL, '$uid', '$name', '$subject', '$story', now(), '$topic')");
    if(!$result) {
    	echo mysql_errno(). ": ".mysql_error(). "<br>";
	exit();
    }
    if($notify) {
    	mail($notify_email, $notify_subject, $notify_message, "From: $notify_from\nX-Mailer: PHP/" . phpversion());
    }
    include ('header.php');
    OpenTable();
    $result = mysql_query("select * from $prefix"._queue."");
    $waiting = mysql_num_rows($result);
    echo "<center><font size=\"4\">"._SUBSENT."</font><br><br>"
	."<font size=\"3\"><b>"._THANKSSUB."</b><br><br>"
	.""._SUBTEXT.""
	."<br>"._WEHAVESUB." $waiting "._WAITING."";
    CloseTable();
    include ('footer.php');    
}

switch($op) {

    case ""._PREVIEW."":
	PreviewStory($name, $address, $subject, $story, $topic);
	break;

    case ""._OK."":
	SubmitStory($name, $address, $subject, $story, $topic);
	break;

    default:
	defaultDisplay();
	break;

}

?>