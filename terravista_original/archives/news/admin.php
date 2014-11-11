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

include("auth.inc.php");
if (!IsSet($mainfile)) { include ("mainfile.php"); }
if(!isset($op)) { $op = "adminMain"; }

/*********************************************************/
/* Login Function                                        */
/*********************************************************/

function login() {
    include ("header.php");
    OpenTable();
    echo "<center><font size=\"4\"><b>"._ADMINLOGIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<form action=\"admin.php\" method=\"post\">"
        ."<table border=\"0\">"
	."<tr><td>"._ADMINID."</td>"
	."<td><input type=\"text\" NAME=\"aid\" SIZE=\"20\" MAXLENGTH=\"20\"></td></tr>"
	."<tr><td>"._PASSWORD."</td>"
	."<td><input type=\"password\" NAME=\"pwd\" SIZE=\"20\" MAXLENGTH=\"18\"></td></tr>"
	."<tr><td>"
	."<input type=\"hidden\" NAME=\"op\" value=\"login\">"
	."<input type=\"submit\" VALUE=\""._LOGIN."\">"
	."</td></tr></table>"
	."</form>";
    CloseTable();
    include ("footer.php");
}

function deleteNotice($id, $table, $op_back) {
    mysql_query("delete from $table WHERE id = $id");
    Header("Location: admin.php?op=$op_back");
}

/*********************************************************/
/* Administration Menu Function                          */
/*********************************************************/

function adminmenu($url, $title, $image) {
    global $counter, $admingraphic, $adminimg;
    if ($admingraphic == 1) {
	$img = "<img src=\"$adminimg$image\" border=\"0\" alt=\"\"></a><br>";
	$close = "";
    } else {
	$image = "";
	$close = "</a>";
    }
    echo "<td align=\"center\"><font size=\"2\"><a href=\"$url\">$img<b>$title</b>$close</font></td>";
    if ($counter == 5) {
	echo "</tr><tr>";
	$counter = 0;
    } else {
	$counter++;
    }
}

function GraphicAdmin($hlpfile) {
    global $aid, $admingraphic, $adminimg, $language, $admin, $banners, $prefix;
    $result = mysql_query("SELECT qid FROM $prefix"._queue."");
    $newsubs = mysql_num_rows($result);
    $result = mysql_query("select radminarticle,radmintopic,radminuser,radminsurvey,radminsection,radminlink,radminephem,radminfilem,radminfaq,radmindownload,radminreviews,radminsuper from $prefix"._authors." where aid='$aid'");
    list($radminarticle,$radmintopic,$radminuser,$radminsurvey,$radminsection,$radminlink,$radminephem,$radminfilem,$radminfaq,$radmindownload,$radminreviews,$radminsuper) = mysql_fetch_array($result);
    OpenTable();	
    echo "<center><font size=\"4\"><b><a href=\"admin.php\">"._ADMINMENU."</a></b>";
    if ($radminsuper==1) {
        echo"&nbsp;&nbsp;&nbsp;<b><a href=\"admin.php?op=BannersAdmin\">"._BANNERSADMIN."</a></b>";
    }
    if (!$hlpfile) {
    } else {
        echo "</font><br><br>[ <a href=\"javascript:openwindow()\">"._ONLINEMANUAL."</a> ]</center>";
    }
    echo "<br><br>";
    echo"<table border=\"0\" width=\"100%\" cellspacing=\"1\"><tr>";
    $linksdir = dir("admin/links");
    while($func=$linksdir->read()) {
        if(substr($func, 0, 6) == "links.") {
    	    $menulist .= "$func ";
	}
    }
    closedir($linksdir->handle);
    $menulist = explode(" ", $menulist);
    sort($menulist);
    for ($i=0; $i < sizeof($menulist); $i++) {
	if($menulist[$i]!="") {
	    $counter = 0;
	    include($linksdir->path."/$menulist[$i]");
	}
    }
    adminmenu("admin.php?op=logout", ""._ADMINLOGOUT."", "exit.gif");
    echo"</tr></table></center>";
    CloseTable();
    echo "<br>";
}

/*********************************************************/
/* Administration Main Function                          */
/*********************************************************/

function adminMain() {
    global $language, $hlpfile, $admin, $admart, $aid, $prefix;
    $hlpfile = "manual/admin.html";
    include ("header.php");
    $dummy = 0;
    GraphicAdmin($hlpfile);
    $result2 = mysql_query("select radminarticle, radminsuper from $prefix"._authors." where aid='$aid'");
    list($radminarticle, $radminsuper) = mysql_fetch_row($result2);
    OpenTable();
    echo "<center><b>"._AUTOMATEDARTICLES."</b></center><br>";
    $count = 0;
    $result = mysql_query("select anid, aid, title, time from $prefix"._autonews." order by time ASC");
    while(list($anid, $said, $title, $time) = mysql_fetch_row($result)) {
	if ($anid != "") {
	    if ($count == 0) {
		echo "<table border=\"1\" width=\"100%\">";
		$count = 1;
	    }
	    $time = ereg_replace(" ", "@", $time);
	    if (($radminarticle==1) OR ($radminsuper==1)) {
		if (($radminarticle==1) AND ($aid == $said) OR ($radminsuper==1)) {
    		    echo "<tr><td>&nbsp;(<a href=\"admin.php?op=autoEdit&amp;anid=$anid\">"._EDIT."</a>-<a href=\"admin.php?op=autoDelete&amp;anid=$anid\">"._DELETE."</a>)&nbsp;</td><td width=\"100%\">&nbsp;$title&nbsp;</td><td>&nbsp;$time&nbsp;</td></tr>";
		} else {
		    echo "<tr><td>&nbsp;("._NOFUNCTIONS.")&nbsp;</td><td width=\"100%\">&nbsp;$title&nbsp;</td><td>&nbsp;$time&nbsp;</td></tr>";
		}
	    } else {
		echo "<tr><td width=\"100%\">&nbsp;$title&nbsp;</td><td>&nbsp;$time&nbsp;</td></tr>";
	    }
	}
    }
    if (($anid == "") AND ($count == 0)) {
	echo "<center><i>"._NOAUTOARTICLES."</i></center>";
    }
    if ($count == 1) {
        echo "</table>";
    }
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>"._LAST." $admart "._ARTICLES."</b></center><br>";
    $result = mysql_query("select sid, aid, title, time, topic, informant from $prefix"._stories." order by time desc limit 0,$admart");
    echo "<center><table border=\"1\" width=\"100%\" bgcolor=\"$bgcolor1\">";
    while(list($sid, $said, $title, $time, $topic, $informant) = mysql_fetch_row($result)) {
	$ta = mysql_query("select topicname from $prefix"._topics." where topicid=$topic");
	list($topicname) = mysql_fetch_row($ta);
	formatTimestamp($time);
	echo "<tr><td align=\"right\"><b>$sid</b>"
	    ."</td><td align=\"left\" width=\"100%\"><a href=\"article.php?sid=$sid\">$title</a>"
	    ."</td><td align=\"right\">$topicname";
	if (($radminarticle==1) OR ($radminsuper==1)) {
	    if (($radminarticle==1) AND ($aid == $said) OR ($radminsuper==1)) {
		echo "</td><td align=\"right\">(<a href=\"admin.php?op=EditStory&sid=$sid\">"._EDIT."</a>-<a href=\"admin.php?op=RemoveStory&sid=$sid\">"._DELETE."</a>)"
		    ."</td></tr>";
	    } else {
		echo "</td><td align=\"right\"><font size=\"2\"><i>("._NOFUNCTIONS.")</i></font>"
		    ."</td></tr>";
	    }
	} else {
	    echo "</td></tr>";
	}
    }
    echo "
    </table>";
    if (($radminarticle==1) OR ($radminsuper==1)) {
	echo "<center>
	<form action=\"admin.php\" method=\"post\">
	"._STORYID.": <input type=\"text\" NAME=\"sid\" SIZE=\"10\">
	<select name=\"op\">
	<option value=\"EditStory\" SELECTED>"._EDIT."</option>
	<option value=\"RemoveStory\">"._DELETE."</option>
	</select>
	<input type=\"submit\" value=\""._GO."\">
	</form></center>";
    }
    CloseTable();
    $result = mysql_query("SELECT pollID, pollTitle, timeStamp FROM $prefix"._poll_desc." ORDER BY pollID DESC limit 1");
    $object = mysql_fetch_object($result);
    $pollTitle = $object->pollTitle;
    echo "<br>";
    OpenTable();
    echo "<center>"._CURRENTPOLL.": $pollTitle</center>";
    CloseTable();
    include ("footer.php");
}

$basedir = dirname($SCRIPT_FILENAME);
$textrows = 20;
$textcols = 85;
$udir = dirname($PHP_SELF);
if(!$wdir) $wdir="/";
if($cancel) $op="FileManager";
if($upload) {
    copy($userfile,$basedir.$wdir.$userfile_name); 
    $lastaction = ""._UPLOADED." $userfile_name --> $wdir";
    // This need a rewrite
    //include("header.php");
    //GraphicAdmin($hlpfile);
    //html_header();
    //displaydir();
    $wdir2="/";
    chdir($basedir . $wdir2);
    //CloseTable();
    //include("footer.php");
    Header("Location: admin.php?op=FileManager");
    exit;
}

if($admintest) {

    switch($op) {

	case "deleteNotice":
	deleteNotice($id, $table, $op_back);
	break;

	case "GraphicAdmin":
        GraphicAdmin($hlpfile);
        break;

	case "adminMain":
	adminMain();
	break;

	case "logout":
	setcookie("admin");
	include("header.php");
	OpenTable();
	echo "<center><font size=\"4\"><b>"._YOUARELOGGEDOUT."</b></font></center>";
	CloseTable();
	include("footer.php");
	break;

	case "login";
	unset($op);

	default:
	$casedir = dir("admin/case");
	while($func=$casedir->read()) {
	    if(substr($func, 0, 5) == "case.") { 
		include($casedir->path."/$func");
	    }
	}
	closedir($casedir->handle);
	break;

	}

} else {

    login();

}

?>