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
$hlpfile = "manual/topics.html";
$result = mysql_query("select radmintopic, radminsuper from $prefix"._authors." where aid='$aid'");
list($radmintopic, $radminsuper) = mysql_fetch_row($result);
if (($radmintopic==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Topics Manager Functions                              */
/*********************************************************/

function topicsmanager() {
    global $hlpfile, $tipath, $prefix;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._TOPICSMANAGER."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._CURRENTTOPICS."</b></font><br>"._CLICK2EDIT."</font></center><br>"
	."<table border=\"0\" width=\"100%\" align=\"center\" cellpadding=\"2\">";
    $count = 0;
    $result = mysql_query("select topicid, topicname, topicimage, topictext from $prefix"._topics." order by topicname");
    while(list($topicid, $topicname, $topicimage, $topictext) = mysql_fetch_array($result)) {
	echo "<td align=\"center\">"
	    ."<a href=\"admin.php?op=topicedit&amp;topicid=$topicid\"><img src=\"$tipath$topicimage\" border=\"0\" alt=\"\"></a><br>"
	    ."<font size=\"2\"><b>$topictext</td>";
	$count++;
	if ($count == 5) {
	    echo "</tr><tr>";
	    $count = 0;
	}
    }
    echo "</table>";
    CloseTable();
    echo "<br><a name=\"Add\">";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._ADDATOPIC."</b></font></center><br>"
    	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TOPICNAME.":</b><br><font size=\"1\">"._TOPICNAME1."<br>"
	.""._TOPICNAME2."</font><br>"
	."<input type=\"text\" name=\"topicname\" size=\"20\" maxlength=\"20\" value=\"$topicname\"><br><br>"
	."<b>"._TOPICTEXT.":</b><br><font size=\"1\">"._TOPICTEXT1."<br>"
	.""._TOPICTEXT2."</font><br>"
	."<input type=\"text\" name=\"topictext\" size=\"40\" maxlength=\"40\" value=\"$topictext\"><br><br>"
	."<b>"._TOPICIMAGE.":</b><br><font size=\"1\">("._TOPICIMAGE1." $tipath)<br>"
	.""._TOPICIMAGE2."</font><br>"
	."<input type=\"text\" name=\"topicimage\" size=\"20\" maxlength=\"20\" value=\"$topicimage\"><br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"topicmake\">"
	."<input type=\"submit\" value=\""._ADDTOPIC."\">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function topicedit($topicid) {
    global $tipath, $prefix;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._TOPICSMANAGER."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $result = mysql_query("select topicid, topicname, topicimage, topictext from $prefix"._topics." where topicid=$topicid");
    list($topicid, $topicname, $topicimage, $topictext) = mysql_fetch_array($result);
    echo "<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\" alt=\"$topictext\">"
	."<font size=\"3\"><b>"._EDITTOPIC.": $topictext</b></font>"
	."<br><br>"
	."<form action=\"admin.php\" method=\"post\"><br>"
	."<b>"._TOPICNAME.":</b><br><font size=\"1\">"._TOPICNAME1."<br>"
	.""._TOPICNAME2."</font><br>"
	."<input type=\"text\" name=\"topicname\" size=\"20\" maxlength=\"20\" value=\"$topicname\"><br><br>"
	."<b>"._TOPICTEXT.":</b><br><font size=\"1\">"._TOPICTEXT1."<br>"
	.""._TOPICTEXT2."</font><br>"
	."<input type=\"text\" name=\"topictext\" size=\"40\" maxlength=\"40\" value=\"$topictext\"><br><br>"
	."<b>"._TOPICIMAGE.":</b><br><font size=\"1\">("._TOPICIMAGE1." $tipath)<br>"
	.""._TOPICIMAGE2."</font><br>"
	."<input type=\"text\" name=\"topicimage\" size=\"20\" maxlength=\"20\" value=\"$topicimage\"><br><br>"
	."<b>"._ADDRELATED.":</b><br>"
	.""._SITENAME.": <input type=\"text\" name=\"name\" size=\"30\" maxlength=\"30\"><br>"
	.""._URL.": <input type=\"text\" name=\"url\" value=\"http://\" size=\"50\" maxlength=\"200\"><br><br>"
	."<b>"._ACTIVERELATEDLINKS.":</b><br>"
	."<table width=\"100%\" border=\"0\">";
    $res=mysql_query("select rid, name, url from $prefix"._related." where tid=$topicid");
    $num = mysql_num_rows($res);
    if ($num == 0) {
	echo "<tr><td><font size=\"1\">"._NORELATED."</font></td></tr>";
    }
    while(list($rid, $name, $url) = mysql_fetch_row($res)) {
        echo "<tr><td align=\"left\"><font size=\"2\"><strong><big>&middot;</big></strong>&nbsp;&nbsp;<a href=\"$url\">$name</a></td>"
    	    ."<td align=\"center\"><font size=\"2\"><a href=\"$url\">$url</a></td><td align=\"right\"><font size=\"2\">[ <a href=\"admin.php?op=relatededit&amp;tid=$topicid&amp;rid=$rid\">"._EDIT."</a> | <a href=\"admin.php?op=relateddelete&amp;tid=$topicid&amp;rid=$rid\">"._DELETE."</a> ]</td></tr>";
    }
    echo "</table><br><br>"
	."<input type=\"hidden\" name=\"topicid\" value=\"$topicid\">"
	."<input type=\"hidden\" name=\"op\" value=\"topicchange\">"
        ."<INPUT type=\"submit\" value=\""._SAVECHANGES."\"> <font size=\"2\">[ <a href=\"admin.php?op=topicdelete&amp;topicid=$topicid\">"._DELETE."</a> ]</font>"
	."</form>";
    CloseTable();
    include("footer.php");
}

function relatededit($tid, $rid) {
    global $tipath, $prefix;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._TOPICSMANAGER."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result=mysql_query("select name, url from $prefix"._related." where rid=$rid");
    list($name, $url) = mysql_fetch_row($result);
    $result2=mysql_query("select topictext, topicimage from $prefix"._topics." where topicid=$tid");
    list($topictext, $topicimage) = mysql_fetch_row($result2);
    OpenTable();    
    echo "<center>"
	."<img src=\"$tipath$topicimage\" border=\"0\" alt=\"$topictext\" align=\"right\">"
	."<font size=\"3\"><b>"._EDITRELATED."</b></font><br>"
	."<b>"._TOPIC.":</b> $topictext</center>"
	."<form action=\"admin.php\" method=\"post\">"
	.""._SITENAME.": <input type=\"text\" name=\"name\" value=\"$name\" size=\"30\" maxlength=\"30\"><br><br>"
	.""._URL.": <input type=\"text\" name=\"url\" value=\"$url\" size=\"60\" maxlength=\"200\"><br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"relatedsave\">"
	."<input type=\"hidden\" name=\"tid\" value=\"$tid\">"
        ."<input type=\"hidden\" name=\"rid\" value=\"$rid\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\"> "._GOBACK.""
	."</form>";
    CloseTable();
    include("footer.php");
}

function relatedsave($tid, $rid, $name, $url) {
    global $prefix;
    mysql_query("update $prefix"._related." set name='$name', url='$url' where rid=$rid");
    Header("Location: admin.php?op=topicedit&topicid=$tid");
}

function relateddelete($tid, $rid) {
    global $prefix;
    mysql_query("delete from $prefix"._related." where rid='$rid'");
    Header("Location: admin.php?op=topicedit&topicid=$tid");
}

function topicmake($topicname, $topicimage, $topictext) {
    global $prefix;
    $topicname = stripslashes(FixQuotes($topicname));
    $topicimage = stripslashes(FixQuotes($topicimage));
    $topictext = stripslashes(FixQuotes($topictext));
    mysql_query("INSERT INTO $prefix"._topics." VALUES (NULL,'$topicname','$topicimage','$topictext','0')");
    Header("Location: admin.php?op=topicsmanager#Add");
}

function topicchange($topicid, $topicname, $topicimage, $topictext, $name, $url) {
    global $prefix;
    $topicname = stripslashes(FixQuotes($topicname));
    $topicimage = stripslashes(FixQuotes($topicimage));
    $topictext = stripslashes(FixQuotes($topictext));
    $name = stripslashes(FixQuotes($name));
    $url = stripslashes(FixQuotes($url));
    mysql_query("update $prefix"._topics." set topicname='$topicname', topicimage='$topicimage', topictext='$topictext' where topicid=$topicid");
    if (!$name) {
    } else {
        mysql_query("insert into $prefix"._related." VALUES (NULL, '$topicid','$name','$url')");
    }
    Header("Location: admin.php?op=topicedit&topicid=$topicid");
}

function topicdelete($topicid, $ok=0) {
    global $prefix;
    if ($ok==1) {
	$result=mysql_query("select sid from $prefix"._stories." where topic='$topicid'");
	list($sid) = mysql_fetch_row($result);
	mysql_query("delete from $prefix"._stories." where topic='$topicid'");
	mysql_query("delete from $prefix"._topics." where topicid='$topicid'");
	mysql_query("delete from $prefix"._related." where tid='$topicid'");
	$result = mysql_query("select sid from $prefix"._comments." where sid='$sid'");
	list($sid) = mysql_fetch_row($result);
	mysql_query("delete from $prefix"._comments." where sid='$sid'");
	Header("Location: admin.php?op=topicsmanager");
    } else {
	global $tipath, $topicimage;
	include("header.php");
	GraphicAdmin($hlpfile);
        OpenTable();
	echo "<center><font size=\"4\"><b>"._TOPICSMANAGER."</b></font></center>";
	CloseTable();
	echo "<br>";
	$result2=mysql_query("select topicimage, topictext from $prefix"._topics." where topicid='$topicid'");
	list($topicimage, $topictext) = mysql_fetch_row($result2);
	OpenTable();
	echo "<center><img src=\"$tipath$topicimage\" border=\"0\" alt=\"$topictext\"><br><br>"
	    ."<b>"._DELETETOPIC." $topictext</b><br><br>"
	    .""._TOPICDELSURE." <i>$topictext</i>?<br>"
	    .""._TOPICDELSURE1."<br><br>"
	    ."[ <a href=\"admin.php?op=topicsmanager\">"._NO."</a> | <a href=\"admin.php?op=topicdelete&amp;topicid=$topicid&amp;ok=1\">"._YES."</a> ]</center><br><br>";
	CloseTable();
	include("footer.php");
    }
}

switch ($op) {

    case "topicsmanager":
    topicsmanager();
    break;

    case "topicedit":
    topicedit($topicid);
    break;

    case "topicmake":
    topicmake($topicname, $topicimage, $topictext);
    break;

    case "topicdelete":
    topicdelete($topicid, $ok);
    break;

    case "topicchange":
    topicchange($topicid, $topicname, $topicimage, $topictext, $name, $url);
    break;

    case "relatedsave":
    relatedsave($tid, $rid, $name, $url);
    break;
	
    case "relatededit":
    relatededit($tid, $rid);
    break;
			
    case "relateddelete":
    relateddelete($tid, $rid);
    break;

}

} else {
    echo "Access Denied";
}

?>