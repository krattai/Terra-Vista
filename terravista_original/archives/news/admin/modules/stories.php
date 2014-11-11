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

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$result = mysql_query("select radminarticle, radminsuper from $prefix"._authors." where aid='$aid'");
list($radminarticle, $radminsuper) = mysql_fetch_row($result);
if (($radminarticle==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Story/News Functions                                  */
/*********************************************************/

function puthome($ihome) {
    echo "<br><b>"._PUBLISHINHOME."</b>&nbsp;&nbsp;";
    if (($ihome == 0) OR ($ihome == "")) {
	$sel1 = "checked";
	$sel2 = "";
    }
    if ($ihome == 1) {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<input type=\"radio\" name=\"ihome\" value=\"0\" $sel1>"._YES."&nbsp;"
	."<input type=\"radio\" name=\"ihome\" value=\"1\" $sel2>"._NO.""
	."&nbsp;&nbsp;<font size=\"2\">[ "._ONLYIFCATSELECTED." ]</font><br>";
}

function deleteStory($qid) {
    global $prefix;
    $result = mysql_query("delete from $prefix"._queue." where qid=$qid");
    if (!$result) {
	echo mysql_errno(). ": ".mysql_error(). "<br>";
	return;
    }
    Header("Location: admin.php?op=submissions");
}

function SelectCategory($cat) {
    global $prefix;
    $selcat = mysql_query("select catid, title from $prefix"._stories."_cat");
    $a = 1;
    echo "<b>"._CATEGORY."</b> ";
    echo "<select name=\"catid\">";
    if ($cat == 0) {
	$sel = "selected";
    } else {
	$sel = "";
    }
    echo "<option name=\"catid\" value=\"0\" $sel>"._ARTICLES."</option>";
    while(list($catid, $title) = mysql_fetch_row($selcat)) {
	if ($catid == $cat) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"catid\" value=\"$catid\" $sel>$title</option>";
	$a++;
    }
    echo "</select> [ <a href=\"admin.php?op=AddCategory\">"._ADD."</a> | <a href=\"admin.php?op=EditCategory\">"._EDIT."</a> | <a href=\"admin.php?op=DelCategory\">"._DELETE."</a> ]";
}

function AddCategory () {
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._CATEGORYADD."</b></font><br><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._CATNAME.":</b> "
	."<input type=\"text\" name=\"title\" size=\"22\" maxlength=\"20\"> "
	."<input type=\"hidden\" name=\"op\" value=\"SaveCategory\">"
	."<input type=\"submit\" value=\""._SAVE."\">"
	."</form></center>";
    CloseTable();
    include("footer.php");
}

function EditCategory($catid) {
    global $prefix;
    $result = mysql_query("select title from $prefix"._stories."_cat where catid='$catid'");
    list($title) = mysql_fetch_row($result);
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._EDITCATEGORY."</b></font><br>";
    if (!$catid) {
	$selcat = mysql_query("select catid, title from $prefix"._stories."_cat");
	echo "<form action=\"admin.php\" method=\"post\">";
	echo "<b>"._ASELECTCATEGORY."</b>";
	echo "<select name=\"catid\">";
	echo "<option name=\"catid\" value=\"0\" $sel>Articles</option>";
	while(list($catid, $title) = mysql_fetch_row($selcat)) {
	    echo "<option name=\"catid\" value=\"$catid\" $sel>$title</option>";
	}
	echo "</select>";
	echo "<input type=\"hidden\" name=\"op\" value=\"EditCategory\">";
	echo "<input type=\"submit\" value=\""._EDIT."\"><br><br>";
	echo ""._NOARTCATEDIT."";
    } else {
	echo "<form action=\"admin.php\" method=\"post\">";
	echo "<b>"._CATEGORYNAME.":</b> ";
	echo "<input type=\"text\" name=\"title\" size=\"22\" maxlength=\"20\" value=\"$title\"> ";
	echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
	echo "<input type=\"hidden\" name=\"op\" value=\"SaveEditCategory\">";
	echo "<input type=\"submit\" value=\""._SAVECHANGES."\"><br><br>";
	echo ""._NOARTCATEDIT."";
	echo "</form>";
    }
    echo "</center>";
    CloseTable();
    include("footer.php");
}

function DelCategory($cat) {
    global $prefix;
    $result = mysql_query("select title from $prefix"._stories."_cat where catid='$cat'");
    list($title) = mysql_fetch_row($result);
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._DELETECATEGORY."</b></font><br>";
    if (!$cat) {
	$selcat = mysql_query("select catid, title from $prefix"._stories."_cat");
	echo "<form action=\"admin.php\" method=\"post\">"
	    ."<b>"._SELECTCATDEL.": </b>"
	    ."<select name=\"cat\">";
	while(list($catid, $title) = mysql_fetch_row($selcat)) {
	    echo "<option name=\"cat\" value=\"$catid\">$title</option>";
	}
	echo "</select>"
	    ."<input type=\"hidden\" name=\"op\" value=\"DelCategory\">"
	    ."<input type=\"submit\" value=\"Delete\">"
	    ."</form>";
    } else {
	$result2 = mysql_query("select * from $prefix"._stories." where catid='$cat'");
	$numrows = mysql_num_rows($result2);
	if ($numrows == 0) {
	    mysql_query("delete from $prefix"._stories."_cat where catid='$cat'");
	    echo "<br><br>"._CATDELETED."<br><br>"._GOTOADMIN."";
	} else {
	    echo "<br><br><b>"._WARNING.":</b> "._THECATEGORY." <b>$title</b> "._HAS." <b>$numrows</b> "._STORIESINSIDE."<br>"
		.""._DELCATWARNING1."<br>"
		.""._DELCATWARNING2."<br><br>"
		.""._DELCATWARNING3."<br><br>"
		."<b>[ <a href=\"admin.php?op=YesDelCategory&amp;catid=$cat\">"._YESDEL."</a> | "
		."<a href=\"admin.php?op=NoMoveCategory&amp;catid=$cat\">"._NOMOVE."</a> ]</b>";
	}
    }
    echo "</center>";
    CloseTable();
    include("footer.php");
}

function YesDelCategory($catid) {
    global $prefix;
    mysql_query("delete from $prefix"._stories."_cat where catid='$catid'");
    $result = mysql_query("select sid from $prefix"._stories." where catid='$catid'");
    while(list($sid) = mysql_fetch_row($result)) {
	mysql_query("delete from $prefix"._stories." where catid='$catid'");
	mysql_query("delete from $prefix"._comments." where sid='$sid'");
    }
    Header("Location: admin.php");
}

function NoMoveCategory($catid, $newcat) {
    global $prefix;
    $result = mysql_query("select title from $prefix"._stories."_cat where catid='$catid'");
    list($title) = mysql_fetch_row($result);
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._MOVESTORIES."</b></font><br><br>";
    if (!$newcat) {
	echo ""._ALLSTORIES." <b>$title</b> "._WILLBEMOVED."<br><br>";
	$selcat = mysql_query("select catid, title from $prefix"._stories."_cat");
	echo "<form action=\"admin.php\" method=\"post\">";
	echo "<b>"._SELECTNEWCAT.":</b> ";
	echo "<select name=\"newcat\">";
        echo "<option name=\"newcat\" value=\"0\">"._ARTICLES."</option>";
	while(list($newcat, $title) = mysql_fetch_row($selcat)) {
    	    echo "<option name=\"newcat\" value=\"$newcat\">$title</option>";
	}
	echo "</select>";
	echo "<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
	echo "<input type=\"hidden\" name=\"op\" value=\"NoMoveCategory\">";
	echo "<input type=\"submit\" value=\""._OK."\">";
	echo "</form>";
    } else {
	$resultm = mysql_query("select sid from $prefix"._stories." where catid='$catid'");
	while(list($sid) = mysql_fetch_row($resultm)) {
	    mysql_query("update $prefix"._stories." set catid='$newcat' where sid='$sid'");
	}
	mysql_query("delete from $prefix"._stories."_cat where catid='$catid'");
	echo ""._MOVEDONE."";
    }
    CloseTable();
    include("footer.php");
}

function SaveEditCategory($catid, $title) {
    global $prefix;
    $title = ereg_replace("\"","",$title);
    $check = mysql_query("select catid from $prefix"._stories."_cat where title=$title");
    if ($check) {
	$what1 = _CATEXISTS;
	$what2 = _GOBACK;
    } else {
	$what1 = _CATSAVED;
	$what2 = "[ <a href=\"admin.php\">"._GOTOADMIN."</a> ]";
	$result = mysql_query("update $prefix"._stories."_cat set title='$title' where catid='$catid'");
	if (!$result) {
	    echo mysql_errno(). ": ".mysql_error(). "<br>";
	    return;
	}    
    }
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"2\"><b>$what1</b></font><br><br>";
    echo "$what2</center>";
    CloseTable();
    include("footer.php");
}

function SaveCategory($title) {
    global $prefix;
    $title = ereg_replace("\"","",$title);
    $check = mysql_query("select catid from $prefix"._stories."_cat where title=$title");
    if ($check) {
	$what1 = _CATEXISTS;
	$what2 = _GOBACK;
    } else {
	$what1 = _CATADDED;
	$what2 = _GOTOADMIN;
	$result = mysql_query("insert into $prefix"._stories."_cat values (NULL, '$title', '0')");
	if (!$result) {
	    echo mysql_errno(). ": ".mysql_error(). "<br>";
	    return;
	}    
    }
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._CATEGORIESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"2\"><b>$what1</b></font><br><br>";
    echo "$what2</center>";
    CloseTable();
    include("footer.php");
}

function autodelete($anid) {
    global $prefix;
    mysql_query("delete from $prefix"._autonews." where anid=$anid");
    Header("Location: admin.php?op=adminMain");
}

function autoEdit($anid) {
    global $aid, $hlpfile, $tipath, $bgcolor1, $bgcolor2, $prefix;
    $result = mysql_query("select radminarticle, radminsuper from $prefix"._authors." where aid='$aid'");
    list($radminarticle, $radminsuper) = mysql_fetch_row($result);
    $result2 = mysql_query("select aid from $prefix"._stories." where sid='$sid'");
    list($aaid) = mysql_fetch_row($result2);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
    include ("header.php");
    $result = mysql_query("select catid, aid, title, time, hometext, bodytext, topic, informant, notes, ihome from $prefix"._autonews." where anid=$anid");
    list($catid, $aid, $title, $time, $hometext, $bodytext, $topic, $informant, $notes, $ihome) = mysql_fetch_row($result);
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._ARTICLEADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";		
    echo "<center><font size=\"3\"><b>"._AUTOSTORYEDIT."</b></font></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">";
    $title = stripslashes($title);
    $hometext = stripslashes($hometext);
    $bodytext = stripslashes($bodytext);
    $notes = stripslashes($notes);
    $result=mysql_query("select topicimage from $prefix"._topics." where topicid=$topic");
    list($topicimage) = mysql_fetch_row($result);
    echo "<table border=\"0\" width=\"75%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>"
	."<table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"$bgcolor1\"><tr><td>"
	."<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\">";
    themepreview($title, $hometext, $bodytext);
    echo "</td></tr></table></td></tr></table>"
	."<br><br><b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"title\" size=\"50\" value=\"$title\"><br><br>"
	."<b>"._TOPIC."</b> <select name=\"topic\">";
    $toplist = mysql_query("select topicid, topictext from $prefix"._topics." order by topictext");
    echo "<option value=\"\">"._ALLTOPICS."</option>\n";
    while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
    if ($topicid==$topic) { $sel = "selected "; }
        echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select><br><br>";
    $cat = $catid;
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome);
    echo "<br><b>"._STORYTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"hometext\">$hometext</textarea><br><br>"
	."<b>"._EXTENDEDTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"bodytext\">$bodytext</textarea><br>"
	."<font size=\"2\">"._ARESUREURL."</font><br><br>";
    if ($aid != $informant) {
    	echo "<b>"._NOTES."</b><br>
	<textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\">$notes</textarea><br><br>";
    }
    echo "<br><b>"._CHNGPROGRAMSTORY."</b><br><br>"
	.""._NOWIS.": $date<br><br>";
    $xday = 1;
    echo ""._DAY.": <select name=\"day\">";
    while ($xday <= 31) {
	if ($xday == $datetime[3]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$xday</option>";
	$xday++;
    }
    echo "</select>";
    $xmonth = 1;
    echo ""._UMONTH.": <select name=\"month\">";
    while ($xmonth <= 12) {
	if ($xmonth == $datetime[2]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$xmonth</option>";
	$xmonth++;
    }
    echo "</select>";
    echo ""._YEAR.": <input type=\"text\" name=\"year\" value=\"$datetime[1]\" size=\"5\" maxlength=\"4\">";
    echo "<br>"._HOUR.": <select name=\"hour\">";
    $xhour = 0;
    $cero = "0";
    while ($xhour <= 23) {
	$dummy = $xhour;
	if ($xhour < 10) {
	    $xhour = "$cero$xhour";
	}
	if ($xhour == $datetime[4]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"hour\" $sel>$xhour</option>";
	$xhour = $dummy;
	$xhour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $xmin = 0;
    while ($xmin <= 59) {
	if (($xmin == 0) OR ($xmin == 5)) {
	    $xmin = "0$xmin";
	}
	if ($xmin == $datetime[5]) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"min\" $sel>$xmin</option>";
	$xmin = $xmin + 5;
    }
    echo "</select>";
    echo ": 00<br><br>
    <input type=\"hidden\" name=\"anid\" value=\"$anid\">
    <input type=\"hidden\" name=\"op\" value=\"autoSaveEdit\">
    <input type=\"submit\" value=\""._SAVECHANGES."\">
    </form>";
    CloseTable();
    include ('footer.php');
    } else {
	include ('header.php');
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._ARTICLEADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>"._NOTAUTHORIZED1."</b><br><br>"
	    .""._NOTAUTHORIZED2."<br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
    }
}

function autoSaveEdit($anid, $year, $day, $month, $hour, $min, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome) {
    global $aid, $ultramode, $prefix;
    $result = mysql_query("select radminarticle, radminsuper from $prefix"._authors." where aid='$aid'");
    list($radminarticle, $radminsuper) = mysql_fetch_row($result);
    $result2 = mysql_query("select aid from $prefix"._stories." where sid='$sid'");
    list($aaid) = mysql_fetch_row($result2);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
    if ($day < 10) {
	$day = "0$day";
    }
    if ($month < 10) {
	$month = "0$month";
    }
    $sec = "00";
    $date = "$year-$month-$day $hour:$min:$sec";
    $title = stripslashes(FixQuotes($title));
    $hometext = stripslashes(FixQuotes($hometext));
    $bodytext = stripslashes(FixQuotes($bodytext));
    $notes = stripslashes(FixQuotes($notes));
    $result = mysql_query("update $prefix"._autonews." set catid='$catid', title='$title', time='$date', hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome' where anid=$anid");
    if (!$result) {
	echo mysql_errno(). ": ".mysql_error(). "<br>";
	exit();
    }
    if ($ultramode) {
	ultramode();
    }
    Header("Location: admin.php?op=adminMain");
    } else {
	include ('header.php');
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._ARTICLEADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>"._NOTAUTHORIZED1."</b><br><br>"
	    .""._NOTAUTHORIZED2."<br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
    }
}

function displayStory($qid) {
    global $user, $subject, $story, $tipath, $bgcolor1, $bgcolor2, $anonymous, $prefix;
    include ('header.php');
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._SUBMISSIONSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $ttmon = $today[mon];
    if ($ttmon < 10){
	$ttmon = "0$ttmon";
    }
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    $result = mysql_query("SELECT qid, uid, uname, subject, story, topic FROM $prefix"._queue." where qid=$qid");
    list($qid, $uid, $uname, $subject, $story, $topic) = mysql_fetch_row($result);
    mysql_free_result($result);
    $subject = stripslashes($subject);
    $story = stripslashes($story);
    OpenTable();
    echo "<font size=\"3\">"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._NAME."</b><br>"
	."<input type=\"text\" NAME=\"author\" size=\"25\" value=\"$uname\">";
    if ($uname != $anonymous) {
	$res = mysql_query("select email from $prefix"._users." where uname='$uname'");
	list($email) = mysql_fetch_row($res);
	echo "&nbsp;&nbsp;<font size=\"2\">[ <a href=\"mailto:$email\">Email User</a> | <a href=\"replypmsg.php?send=1&amp;uname=$uname\">Send Private Message</a> ]</font>";
    }
    echo "<br><br><b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"><br><br>";
    if($topic=="") {
        $topic = 1;
    }
    $result = mysql_query("select topicimage from $prefix"._topics." where topicid=$topic");
    list($topicimage) = mysql_fetch_row($result);
    echo "<table border=\"0\" width=\"70%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>"
	."<table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"$bgcolor1\"><tr><td>"
	."<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\" alt=\"\">";
    themepreview($subject, $story);
    echo "</td></tr></table></td></tr></table>"
	."<br><b>"._TOPIC."</b> <select name=\"topic\">";
    $toplist = mysql_query("select topicid, topictext from $prefix"._topics." order by topictext");
    echo "<option value=\"\">"._SELECTTOPIC."</option>\n";
    while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
        if ($topicid==$topic) {
	    $sel = "selected ";
	}
        echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select>";
    echo "<br><br>";
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome);
    echo "<br><b>"._STORYTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$story</textarea><br><br>"
	."<b>"._EXTENDEDTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"8\" name=\"bodytext\"></textarea><BR>"
	."<font size=\"2\">"._AREYOUSURE."</font><br><br>"
	."<b>"._NOTES."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\"></textarea><br>"
	."<input type=\"hidden\" NAME=\"qid\" size=\"50\" value=\"$qid\">"
	."<input type=\"hidden\" NAME=\"uid\" size=\"50\" value=\"$uid\">"
	."<br><b>"._PROGRAMSTORY."</b>&nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"1\">"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"0\" checked>"._NO."<br><br>"
	.""._NOWIS.": $date<br><br>";
    $day = 1;
    echo ""._DAY.": <select name=\"day\">";
    while ($day <= 31) {
	if ($tday==$day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$day</option>";
	$day++;
    }
    echo "</select>";
    $month = 1;
    echo ""._UMONTH.": <select name=\"month\">";
    while ($month <= 12) {
	if ($ttmon==$month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$month</option>";
	$month++;
    }
    echo "</select>";
    $date = getdate();
    $year = $date[year];
    echo ""._YEAR.": <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">";
    echo "<br>"._HOUR.": <select name=\"hour\">";
    $hour = 0;
    $cero = "0";
    while ($hour <= 23) {
	$dummy = $hour;
	if ($hour < 10) {
	    $hour = "$cero$hour";
	}
	echo "<option name=\"hour\">$hour</option>";
	$hour = $dummy;
	$hour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $min = 0;
    while ($min <= 59) {
	if (($min == 0) OR ($min == 5)) {
	    $min = "0$min";
	}
	echo "<option name=\"min\">$min</option>";
	$min = $min + 5;
    }
    echo "</select>";
    echo ": 00<br><br>"
	."<select name=\"op\">"
	."<option value=\"DeleteStory\">"._DELETESTORY."</option>"
	."<option value=\"PreviewAgain\" selected>"._PREVIEWSTORY."</option>"
	."<option value=\"PostStory\">"._POSTSTORY."</option>"
	."</select>"
	."<input type=\"submit\" value=\""._OK."\">"
	."</form>";
    CloseTable();	
    include ('footer.php');
}

function previewStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome) {
    global $user, $boxstuff, $tipath, $anonymous, $bgcolor1, $bgcolor2, $prefix;
    include ('header.php');
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._ARTICLEADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    $subject = stripslashes($subject);
    $hometext = stripslashes($hometext);
    $bodytext = stripslashes($bodytext);
    $notes = stripslashes($notes);
    OpenTable();
    echo "<font size=\"3\">"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._NAME."</b><br>"
	."<input type=\"text\" name=\"author\" size=\"25\" value=\"$author\">";
    if ($author != $anonymous) {
	$res = mysql_query("select email from $prefix"._users." where uname='$author'");
	list($email) = mysql_fetch_row($res);
	echo "&nbsp;&nbsp;<font size=\"2\">[ <a href=\"mailto:$email\">Email User</a> | <a href=\"replypmsg.php?send=1&amp;uname=$author\">Send Private Message</a> ]</font>";
    }
    echo "<br><br><b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"><br><br>";
    $result = mysql_query("select topicimage from $prefix"._topics." where topicid=$topic");
    list($topicimage) = mysql_fetch_row($result);
    echo "<table width=\"70%\" bgcolor=\"$bgcolor2\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\"align=\"center\"><tr><td>"
	."<table width=\"100%\" bgcolor=\"$bgcolor1\" cellpadding=\"8\" cellspacing=\"1\" border=\"0\"><tr><td>"
	."<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\">";
    themepreview($subject, $hometext, $bodytext, $notes);
    echo "</td></tr></table></td></tr></table>"
	."<br><b>"._TOPIC."</b> <select name=\"topic\">";
    $toplist = mysql_query("select topicid, topictext from $prefix"._topics." order by topictext");
    echo "<option value=\"\">"._ALLTOPICS."</option>\n";
    while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
        if ($topicid==$topic) {
	    $sel = "selected ";
	}
        echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select>";
    echo "<br><br>";
    $cat = $catid;
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome);
    echo "<br><b>"._STORYTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$hometext</textarea><br><br>"
	."<b>"._EXTENDEDTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"10\" name=\"bodytext\">$bodytext</textarea><br>"
	."<font size=\"2\">"._AREYOUSURE."</font><br><br>"
	."<b>"._NOTES."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\">$notes</textarea><br><br>"
	."<input type=\"hidden\" NAME=\"qid\" size=\"50\" value=\"$qid\">"
	."<input type=\"hidden\" NAME=\"uid\" size=\"50\" value=\"$uid\">";
    if ($automated == 1) {
	$sel1 = "checked";
	$sel2 = "";
    } else {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<b>"._PROGRAMSTORY."</b>&nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"1\" $sel1>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"0\" $sel2>"._NO."<br><br>"
	.""._NOWIS.": $date<br><br>";
    $xday = 1;
    echo ""._DAY.": <select name=\"day\">";
    while ($xday <= 31) {
	if ($xday == $day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$xday</option>";
	$xday++;
    }
    echo "</select>";
    $xmonth = 1;
    echo ""._UMONTH.": <select name=\"month\">";
    while ($xmonth <= 12) {
	if ($xmonth == $month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$xmonth</option>";
	$xmonth++;
    }
    echo "</select>";
    echo ""._YEAR.": <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">";
    echo "<br>"._HOUR.": <select name=\"hour\">";
    $xhour = 0;
    $cero = "0";
    while ($xhour <= 23) {
	$dummy = $xhour;
	if ($xhour < 10) {
	    $xhour = "$cero$xhour";
	}
	if ($xhour == $hour) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"hour\" $sel>$xhour</option>";
	$xhour = $dummy;
	$xhour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $xmin = 0;
    while ($xmin <= 59) {
	if (($xmin == 0) OR ($xmin == 5)) {
	    $xmin = "0$xmin";
	}
	if ($xmin == $min) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"min\" $sel>$xmin</option>";
	$xmin = $xmin + 5;
    }
    echo "</select>";
    echo ": 00<br><br>"
	."<select name=\"op\">"
	."<option value=\"DeleteStory\">"._DELETESTORY."</option>"
	."<option value=\"PreviewAgain\" selected>"._PREVIEWSTORY."</option>"
	."<option value=\"PostStory\">"._POSTSTORY."</option>"
	."</select>"
	."<input type=\"submit\" value=\""._OK."\">"
	."</form>";
    CloseTable();
    include ('footer.php');
}

function postStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome) {
    global $aid, $ultramode, $prefix;
    if ($automated == 1) {
    if ($day < 10) {
	$day = "0$day";
    }
    if ($month < 10) {
	$month = "0$month";
    }
    $sec = "00";
    $date = "$year-$month-$day $hour:$min:$sec";	
	if ($uid == 1) $author = "";
	if ($hometext == $bodytext) $bodytext = "";
	$subject = stripslashes(FixQuotes($subject));
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	$notes = stripslashes(FixQuotes($notes));
	$result = mysql_query("insert into $prefix"._autonews." values (NULL, '$catid', '$aid', '$subject', '$date', '$hometext', '$bodytext', '$topic', '$author', '$notes', '$ihome')");
	if (!$result) {
		echo mysql_errno(). ": ".mysql_error(). "<br>";
		return;
	}
	if ($uid == 1) {
	} else {
	    mysql_query("update $prefix"._users." set counter=counter+1 where uid='$uid'");
	}
	    mysql_query("update $prefix"._authors." set counter=counter+1 where aid='$aid'");
	if ($ultramode) {
    	    ultramode();
	}
	mysql_query("delete from $prefix"._queue." where qid=$qid");
	Header("Location: admin.php?op=submissions");    
    } else {

    if ($uid == 1) $author = "";
    if ($hometext == $bodytext) $bodytext = "";
    $subject = stripslashes(FixQuotes($subject));
    $hometext = stripslashes(FixQuotes($hometext));
    $bodytext = stripslashes(FixQuotes($bodytext));
    $notes = stripslashes(FixQuotes($notes));
    $result = mysql_query("insert into $prefix"._stories." values (NULL, '$catid', '$aid', '$subject', now(), '$hometext', '$bodytext', '0', '0', '$topic','$author', '$notes', '$ihome')");
    if (!$result) {
	echo mysql_errno(). ": ".mysql_error(). "<br>";
	return;
    }
    if ($uid == 1) {
    } else {
        mysql_query("update $prefix"._users." set counter=counter+1 where uid='$uid'");
    }
    mysql_query("update $prefix"._authors." set counter=counter+1 where aid='$aid'");
    if ($ultramode) {
        ultramode();
    }
    deleteStory($qid);
}
}

function editStory($sid) {
    global $user, $tipath, $bgcolor1, $bgcolor2, $aid, $prefix;
    $result = mysql_query("select radminarticle, radminsuper from $prefix"._authors." where aid='$aid'");
    list($radminarticle, $radminsuper) = mysql_fetch_row($result);
    $result2 = mysql_query("select aid from $prefix"._stories." where sid='$sid'");
    list($aaid) = mysql_fetch_row($result2);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
	include ('header.php');
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._ARTICLEADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	$result = mysql_query("SELECT catid, title, hometext, bodytext, topic, notes, ihome FROM $prefix"._stories." where sid=$sid");
        list($catid, $subject, $hometext, $bodytext, $topic, $notes, $ihome) = mysql_fetch_row($result);
	$subject = stripslashes($subject);
        $hometext = stripslashes($hometext);
        $bodytext = stripslashes($bodytext);
        $notes = stripslashes($notes);
        $result2=mysql_query("select topicimage from $prefix"._topics." where topicid=$topic");
        list($topicimage) = mysql_fetch_row($result2);
        OpenTable();
        echo "<center><font size=\"3\"><b>"._EDITARTICLE."</b></font></center><br>"
	    ."<table width=\"80%\" border=\"0\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>"
	    ."<table width=\"100%\" border=\"0\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"$bgcolor1\"><tr><td>"
	    ."<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\">";
	themepreview($subject, $hometext, $bodytext, $notes);
	echo "</td></tr></table></td></tr></table><br><br>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    ."<b>"._TITLE."</b><br>"
	    ."<input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"><br><br>"
	    ."<b>"._TOPIC."</b> <select name=\"topic\">";
	$toplist = mysql_query("select topicid, topictext from $prefix"._topics." order by topictext");
	echo "<option value=\"\">"._ALLTOPICS."</option>\n";
	while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
    	    if ($topicid==$topic) { $sel = "selected "; }
        	echo "<option $sel value=\"$topicid\">$topics</option>\n";
		$sel = "";
	}
	echo "</select>";
	echo "<br><br>";
	$cat = $catid;
	SelectCategory($cat);
	echo "<br>";
	puthome($ihome);
	echo "<br><b>"._STORYTEXT."</b><br>"
	    ."<textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$hometext</textarea><br><br>"
	    ."<b>"._EXTENDEDTEXT."</b><br>"
	    ."<textarea wrap=\"virtual\" cols=\"50\" rows=\"10\" name=\"bodytext\">$bodytext</textarea><br>"
	    ."<font size=\"2\">"._AREYOUSURE."</font><br><br>"
	    ."<b>"._NOTES."</b><br>"
	    ."<textarea wrap=\"virtual\" cols=\"50\" rows=\"4\" name=\"notes\">$notes</textarea><br><br>"
	    ."<input type=\"hidden\" NAME=\"sid\" size=\"50\" value=\"$sid\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"ChangeStory\">"
	    ."<input type=\"submit\" value=\""._SAVECHANGES."\">"
	    ."</form>";
	CloseTable();
	include ('footer.php');
    } else {
	include ('header.php');
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._ARTICLEADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>"._NOTAUTHORIZED1."</b><br><br>"
	    .""._NOTAUTHORIZED2."<br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
    }
}

function removeStory($sid, $ok=0) {
    global $ultramode, $aid, $prefix;
    $result = mysql_query("select counter, radminarticle, radminsuper from $prefix"._authors." where aid='$aid'");
    list($counter, $radminarticle, $radminsuper) = mysql_fetch_row($result);
    $result2 = mysql_query("select aid from $prefix"._stories." where sid='$sid'");
    list($aaid) = mysql_fetch_row($result2);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
	if($ok) {
	    $counter--;
    	    mysql_query("DELETE FROM $prefix"._stories." where sid=$sid");
	    mysql_query("DELETE FROM $prefix"._comments." where sid=$sid");
	    $result = mysql_query("update $prefix"._authors." set counter='$counter' where aid='$aid'");
	    if ($ultramode) {
	        ultramode();
	    }
	    Header("Location: admin.php");
	} else {
	    include("header.php");
	    GraphicAdmin($hlpfile);
	    OpenTable();
	    echo "<center><font size=\"4\"><b>"._ARTICLEADMIN."</b></font></center>";
	    CloseTable();
	    echo "<br>";
	    OpenTable();
	    echo "<center>"._REMOVESTORY." $sid "._ANDCOMMENTS."";
	    echo "<br><br>[ <a href=\"admin.php\">"._NO."</a> | <a href=\"admin.php?op=RemoveStory&amp;sid=$sid&amp;ok=1\">"._YES."</a> ]</center>";
    	    CloseTable();
	    include("footer.php");
	}
    } else {
	include ('header.php');
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._ARTICLEADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>"._NOTAUTHORIZED1."</b><br><br>"
	    .""._NOTAUTHORIZED2."<br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
    }
}


function changeStory($sid, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome) {
    global $aid, $ultramode, $prefix;
    $result = mysql_query("select radminarticle, radminsuper from $prefix"._authors." where aid='$aid'");
    list($radminarticle, $radminsuper) = mysql_fetch_row($result);
    $result2 = mysql_query("select aid from $prefix"._stories." where sid='$sid'");
    list($aaid) = mysql_fetch_row($result2);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
	$subject = stripslashes(FixQuotes($subject));
	$hometext = stripslashes(FixQuotes($hometext));
	$bodytext = stripslashes(FixQuotes($bodytext));
	$notes = stripslashes(FixQuotes($notes));
	mysql_query("update $prefix"._stories." set catid='$catid', title='$subject', hometext='$hometext', bodytext='$bodytext', topic='$topic', notes='$notes', ihome='$ihome' where sid=$sid");
	if ($ultramode) {
    	    ultramode();
	}
	Header("Location: admin.php?op=adminMain");
    }
}

function adminStory() {
    global $hlpfile, $prefix;
    $hlpfile = "manual/newarticle.html";
    include ('header.php');
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._ARTICLEADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $ttmon = $today[mon];
    if ($ttmon < 10){
	$ttmon = "0$ttmon";
    }
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._ADDARTICLE."</b></font></center><br><br>"
    	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\"><br><br>"
	."<b>"._TOPIC."</b>";
    $toplist = mysql_query("select topicid, topictext from $prefix"._topics." order by topictext");
    echo "<select name=\"topic\">";
    echo "<option value=\"\">"._SELECTTOPIC."</option>\n";
    while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
        if ($topicid == $topic) {
	    $sel = "selected ";
	}
    	echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select><br><br>";
    $cat = 0;
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome);
    echo "<br><b>"._STORYTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"70\" rows=\"12\" name=\"hometext\"></textarea><br><br>"
	."<b>"._EXTENDEDTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"70\" rows=\"12\" name=\"bodytext\"></textarea><br>"
	."<font size=\"2\">"._ARESUREURL."</font>"
	."<br><br><b>"._PROGRAMSTORY."</b>&nbsp;&nbsp;"
	."<input type=radio name=automated value=1>"._YES." &nbsp;&nbsp;"
	."<input type=radio name=automated value=0 checked>"._NO."<br><br>"
	.""._NOWIS.": $date<br><br>";
    $day = 1;
    echo ""._DAY.": <select name=\"day\">";
    while ($day <= 31) {
	if ($tday==$day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$day</option>";
	$day++;
    }
    echo "</select>";
    $month = 1;
    echo ""._UMONTH.": <select name=\"month\">";
    while ($month <= 12) {
	if ($ttmon==$month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$month</option>";
	$month++;
    }
    echo "</select>";
    $date = getdate();
    $year = $date[year];
    echo ""._YEAR.": <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">"
	."<br>"._HOUR.": <select name=\"hour\">";
    $hour = 0;
    $cero = "0";
    while ($hour <= 23) {
	$dummy = $hour;
	if ($hour < 10) {
	    $hour = "$cero$hour";
	}
	echo "<option name=\"hour\">$hour</option>";
	$hour = $dummy;
	$hour++;
    }
    echo "</select>"
	.": <select name=\"min\">";
    $min = 0;
    while ($min <= 59) {
	if (($min == 0) OR ($min == 5)) {
	    $min = "0$min";
	}
	echo "<option name=\"min\">$min</option>";
	$min = $min + 5;
    }
    echo "</select>";
    echo ": 00<br><br>"
	."<select name=\"op\">"
	."<option value=\"PreviewAdminStory\" selected>"._PREVIEWSTORY."</option>"
	."<option value=\"PostAdminStory\">"._POSTSTORY."</option>"
	."</select>"
	."<input type=\"submit\" value=\""._OK."\">"
	."</form>";
    CloseTable();
    include ('footer.php');
}

function previewAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $topic, $catid) {
    global $user, $tipath, $bgcolor1, $bgcolor2, $prefix;
    include ('header.php');
    if ($topic<1) {
        $topic = 1;
    }
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._ARTICLEADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._PREVIEWSTORY."</b></font></center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"catid\" value=\"$catid\">";
    $subject = stripslashes($subject);
    $hometext = stripslashes($hometext);
    $bodytext = stripslashes($bodytext);
    $result=mysql_query("select topicimage from $prefix"._topics." where topicid=$topic");
    list($topicimage) = mysql_fetch_row($result);
    echo "<table border=\"0\" width=\"75%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>"
	."<table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"$bgcolor1\"><tr><td>"
	."<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\" alt=\"\">";
	themepreview($subject, $hometext, $bodytext);
    echo "</td></tr></table></td></tr></table>"
	."<br><br><b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"><br><br>"
	."<b>"._TOPIC."</b><select name=\"topic\">";
    $toplist = mysql_query("select topicid, topictext from $prefix"._topics." order by topictext");
    echo "<option value=\"\">"._ALLTOPICS."</option>\n";
    while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
	if ($topicid==$topic) {
	    $sel = "selected ";
	}
        echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select><br><br>";
    $cat = $catid;
    SelectCategory($cat);
    echo "<br>";
    puthome($ihome);
    echo "<br><b>"._STORYTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"hometext\">$hometext</textarea><br><br>"
	."<b>"._EXTENDEDTEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"12\" name=\"bodytext\">$bodytext</textarea><br><br>";
    if ($automated == 1) {
	$sel1 = "checked";
	$sel2 = "";
    } else {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<br><b>"._PROGRAMSTORY."</b>&nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"1\" $sel1>"._YES." &nbsp;&nbsp;"
	."<input type=\"radio\" name=\"automated\" value=\"0\" $sel2>"._NO."<br><br>"
	.""._NOWIS.": $date<br><br>";
    $xday = 1;
    echo ""._DAY.": <select name=\"day\">";
    while ($xday <= 31) {
	if ($xday == $day) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"day\" $sel>$xday</option>";
	$xday++;
    }
    echo "</select>";
    $xmonth = 1;
    echo ""._UMONTH.": <select name=\"month\">";
    while ($xmonth <= 12) {
	if ($xmonth == $month) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"month\" $sel>$xmonth</option>";
	$xmonth++;
    }
    echo "</select>";
    echo ""._YEAR.": <input type=\"text\" name=\"year\" value=\"$year\" size=\"5\" maxlength=\"4\">";
    echo "<br>"._HOUR.": <select name=\"hour\">";
    $xhour = 0;
    $cero = "0";
    while ($xhour <= 23) {
	$dummy = $xhour;
	if ($xhour < 10) {
	    $xhour = "$cero$xhour";
	}
	if ($xhour == $hour) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"hour\" $sel>$xhour</option>";
	$xhour = $dummy;
	$xhour++;
    }
    echo "</select>";
    echo ": <select name=\"min\">";
    $xmin = 0;
    while ($xmin <= 59) {
	if (($xmin == 0) OR ($xmin == 5)) {
	    $xmin = "0$xmin";
	}
	if ($xmin == $min) {
	    $sel = "selected";
	} else {
	    $sel = "";
	}
	echo "<option name=\"min\" $sel>$xmin</option>";
	$xmin = $xmin + 5;
    }
    echo "</select>";
    echo ": 00<br><br>"
	."<select name=\"op\">"
	."<option value=\"PreviewAdminStory\" selected>"._PREVIEWSTORY."</option>"
	."<option value=\"PostAdminStory\">"._POSTSTORY."</option>"
	."</select>"
	."<input type=\"submit\" value=\""._OK."\">"
	."</form>";
    CloseTable();
    include ('footer.php');
}

function postAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $introstory, $fullstory, $topic, $catid, $ihome) {
    global $aid, $prefix;
    if ($automated == 1) {
    if ($day < 10) {
	$day = "0$day";
    }
    if ($month < 10) {
	$month = "0$month";
    }
    $sec = "00";
    $date = "$year-$month-$day $hour:$min:$sec";
    $notes = "";
    $author = $aid;
    $subject = stripslashes(FixQuotes($subject));
    $introstory = stripslashes(FixQuotes($introstory));
    $fullstory = stripslashes(FixQuotes($fullstory));
    $result = mysql_query("insert into $prefix"._autonews." values (NULL, '$catid', '$aid', '$subject', '$date', '$introstory', '$fullstory', '$topic', '$author', '$notes', '$ihome')");
    if (!$result) {
	echo mysql_errno(). ": ".mysql_error(). "<br>";
	exit();
    }
    $result = mysql_query("update $prefix"._authors." set counter=counter+1 where aid='$aid'");
    if ($ultramode) {
	ultramode();
    }
    Header("Location: admin.php?op=adminMain");
    } else {
	$subject = stripslashes(FixQuotes($subject));
	$introstory = stripslashes(FixQuotes($introstory));
	$fullstory = stripslashes(FixQuotes($fullstory));
	$result = mysql_query("insert into $prefix"._stories." values (NULL, '$catid', '$aid', '$subject', now(), '$introstory', '$fullstory', '0', '0', '$topic', '$aid', '$notes', '$ihome')");
	if (!$result)
	{
		echo mysql_errno(). ": ".mysql_error(). "<br>";
		exit();
	}
	$result = mysql_query("update $prefix"._authors." set counter=counter+1 where aid='$aid'");
	if ($ultramode) {
	    ultramode();
	}
	Header("Location: admin.php?op=adminMain");
    }
}

function submissions() {
    global $hlpfile, $admin, $bgcolor1, $bgcolor2, $prefix;
    $dummy = 0;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._SUBMISSIONSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
	$result = mysql_query("SELECT qid, subject, timestamp FROM $prefix"._queue." order by timestamp");
	if(mysql_num_rows($result) == 0) {
	    echo "<table width=\"100%\"><tr><td bgcolor=\"$bgcolor1\" align=\"center\"><b>"._NOSUBMISSIONS."</b></td></tr></table>\n";
	} else {
	    echo "<center><font size=\"2\"><b>"._NEWSUBMISSIONS."</b></font><form action=\"admin.php\" method=\"post\"><table width=\"100%\" border=\"1\" bgcolor=\"$bgcolor2\">\n";
	    while (list($qid, $subject, $timestamp) = mysql_fetch_row($result)) {
		$hour = "AM";
		ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $timestamp, $datetime);
		if ($datetime[4] > 12) { $datetime[4] = $datetime[4]-12; $hour = "PM"; }
		$datetime = date(translate("datestring"), mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
		echo "<tr>\n"
		    ."<td align=\"center\"><font size=\"2\">&nbsp;(<a href=\"admin.php?op=DeleteStory&amp;qid=$qid\">"._DELETE."</a>)&nbsp;</td>\n"
	    	    ."<td width=100%><font size=3>\n";
		if ($subject == "") {
		    echo "&nbsp;<a href=\"admin.php?op=DisplayStory&amp;qid=$qid\">"._NOSUBJECT."</a></font>\n";
		} else {
		    echo "&nbsp;<a href=\"admin.php?op=DisplayStory&amp;qid=$qid\">$subject</a></font>\n";
		}
		$timestamp = ereg_replace(" ", "@", $timestamp);
		echo "</td><td align=\"right\"><font size=\"2\">&nbsp;$timestamp&nbsp;</font></td></tr>\n";
		$dummy++;
	    }
	    if ($dummy < 1) {
		echo "<tr><td bgcolor=\"$bgcolor1\" align=\"center\"><b>"._NOSUBMISSIONS."</b></form></td></tr></table>\n";
	    } else {
		echo "</table></form>\n";
	    }
	}
    CloseTable();
    include ("footer.php");
}

switch($op) {

    case "EditCategory":
    EditCategory($catid);
    break;
		
    case "DelCategory":
    DelCategory($cat);
    break;

    case "YesDelCategory":
    YesDelCategory($catid);
    break;

    case "NoMoveCategory":
    NoMoveCategory($catid, $newcat);
    break;
		
    case "SaveEditCategory":
    SaveEditCategory($catid, $title);
    break;

    case "SelectCategory":
    SelectCategory($cat);
    break;

    case "AddCategory":
    AddCategory();
    break;

    case "SaveCategory":
    SaveCategory($title);
    break;

    case "DisplayStory":
    displayStory($qid);
    break;

    case "PreviewAgain":
    previewStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome);
    break;

    case "PostStory":
    postStory($automated, $year, $day, $month, $hour, $min, $qid, $uid, $author, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome);
    break;

    case "EditStory":
    editStory($sid);
    break;

    case "RemoveStory":
    removeStory($sid, $ok);
    break;

    case "ChangeStory":
    changeStory($sid, $subject, $hometext, $bodytext, $topic, $notes, $catid, $ihome);
    break;

    case "DeleteStory":
    deleteStory($qid);
    break;

    case "adminStory":
    adminStory($sid);
    break;

    case "PreviewAdminStory":
    previewAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $topic, $catid, $ihome);
    break;

    case "PostAdminStory":
    postAdminStory($automated, $year, $day, $month, $hour, $min, $subject, $hometext, $bodytext, $topic, $catid, $ihome);
    break;

    case "autoDelete":
    autodelete($anid);
    break;

    case "autoEdit":
    autoEdit($anid);
    break;

    case "autoSaveEdit":
    autoSaveEdit($anid, $year, $day, $month, $hour, $min, $title, $hometext, $bodytext, $topic, $notes, $catid, $ihome);
    break;

    case "submissions":
    submissions();
    break;

}

} else {
    echo "Access Denied";
}

?>