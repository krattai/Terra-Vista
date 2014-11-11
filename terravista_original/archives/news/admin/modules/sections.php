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
$hlpfile = "manual/sections.html";
$result = mysql_query("select radminsection, radminsuper from $prefix"._authors." where aid='$aid'");
list($radminsection, $radminsuper) = mysql_fetch_row($result);
if (($radminsection==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Sections Manager Functions                            */
/*********************************************************/

function sections() {
    global $hlpfile, $admart, $prefix;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._SECTIONSADMIN."</b></font></center>";
    CloseTable();
    $result = mysql_query("select secid, secname from $prefix"._sections." order by secid");
    if (mysql_num_rows($result) > 0) {
	echo "<br>";
	OpenTable();
	echo "<center><b>"._ACTIVESECTIONS."</b><br><font size=\"2\">"._CLICK2EDITSEC."</font></center><br>"
	    ."<table border=0 width=100% align=center cellpadding=1 align=\"center\"><tr><td align=center>";
	while(list($secid, $secname) = mysql_fetch_array($result)) {
	    echo "<strong><big>&middot;</big></strong>&nbsp;&nbsp;<a href=\"admin.php?op=sectionedit&amp;secid=$secid\">$secname</a>";
	}
	echo "</td></tr></table>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><font size=\"3\"><b>"._ADDSECARTICLE."</b></font></center><br>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    ."<b>"._TITLE."</b><br>"
	    ."<input type=\"text\" name=\"title\" size=\"60\"><br><br>"
	    ."<b>"._SELSECTION.":</b><br>";
	$result = mysql_query("select secid, secname from $prefix"._sections." order by secid");
	while(list($secid, $secname) = mysql_fetch_array($result)) {
	    echo "<input type=\"radio\" name=\"secid\" value=\"$secid\"> $secname<br>";
	}
	echo "<font size=\"2\">"._DONTSELECT."</font><br>"
	    ."<br><b>"._CONTENT."</b><br>"
	    ."<textarea name=\"content\" cols=\"60\" rows=\"10\"></textarea><br>"
	    ."<font size=\"2\">"._PAGEBREAK."</font><br><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"secarticleadd\">"
	    ."<input type=\"submit\" value=\""._ADDARTICLE."\">"
	    ."</form>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><font size=\"3\"><b>"._LAST." $admart "._ARTICLES."</b></font></center><br>"
	    ."<ul>";
	$result = mysql_query("select artid, secid, title, content from $prefix"._seccont." order by artid desc limit 0,$admart");
	while(list($artid, $secid, $title, $content) = mysql_fetch_array($result)) {
	    $result2 = mysql_query("select secid, secname from $prefix"._sections." where secid='$secid'");
	    list($secid, $secname) = mysql_fetch_row($result2);
	    echo "<li>$title ($secname) [ <a href=\"admin.php?op=secartedit&amp;artid=$artid\">"._EDIT."</a> | <a href=\"admin.php?op=secartdelete&amp;artid=$artid&amp;ok=0\">"._DELETE."</a> ]";
	}
	echo "</ul>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    .""._EDITARTID.": <input type=\"text\" name=\"artid\" size=\"10\">&nbsp;&nbsp;"
	    ."<input type=\"hidden\" name=\"op\" value=\"secartedit\">"
	    ."<input type=\"submit\" value=\""._OK."\">"
	    ."</form>";
	CloseTable();
    } 
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._ADDSECTION."</b></font></center><br>"
	."<form action=\"admin.php\" method=\"post\"><br>"
	."<b>"._SECTIONNAME.":</b><br>"
	."<input type=\"text\" name=\"secname\" size=\"40\" maxlength=\"40\"><br><br>"
	."<b>"._SECTIONIMG."</b><br><font size=\"1\">"._SECIMGEXAMPLE."</font><br>"
	."<input type=\"text\" name=\"image\" size=\"40\" maxlength=\"50\"><br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"sectionmake\">"
	."<INPUT type=\"submit\" value=\""._ADDSECTIONBUT."\">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function secarticleadd($secid, $title, $content) {
    global $prefix;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    mysql_query("INSERT INTO $prefix"._seccont." VALUES (NULL,'$secid','$title','$content','0')");
    Header("Location: admin.php?op=sections");
}

function secartedit($artid) {
    global $prefix;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._SECTIONSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = mysql_query("select artid, secid, title, content from $prefix"._seccont." where artid='$artid'");
    list($artid, $secid, $title, $content) = mysql_fetch_array($result);
    OpenTable();
    echo "<center><font size=\"3\"><b>"._EDITARTICLE."</b></font></center><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._TITLE."</b><br>"
	."<input type=\"text\" name=\"title\" size=\"60\" value=\"$title\"><br><br>"
	."<b>"._SELSECTION.":</b><br>";
    $result2 = mysql_query("select secid, secname from $prefix"._sections." order by secname");
    while(list($secid2, $secname) = mysql_fetch_array($result2)) {
	    if ($secid2==$secid) {
		$che = "checked";
	    }
	    echo "<input type=\"radio\" name=\"secid\" value=\"$secid2\" $che>$secname<br>";
	    $che = "";
    }
    echo "<br><b>"._CONTENT."</b><br>"
	."<textarea name=\"content\" cols=\"60\" rows=\"10\">$content</textarea><br><br>"
	."<input type=\"hidden\" name=\"artid\" value=\"$artid\">"
	."<input type=\"hidden\" name=\"op\" value=\"secartchange\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\"> [ <a href=\"admin.php?op=secartdelete&amp;artid=$artid&amp;ok=0\">"._DELETE."</a> ]"
	."</form>";
    CloseTable();
    include("footer.php");
}

function sectionmake($secname, $image) {
    global $prefix;
    $secname = stripslashes(FixQuotes($secname));
    $image = stripslashes(FixQuotes($image));
    mysql_query("INSERT INTO $prefix"._sections." VALUES (NULL,'$secname', '$image')");
    Header("Location: admin.php?op=sections");
}

function sectionedit($secid) {
    global $prefix;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._SECTIONSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = mysql_query("select secid, secname, image from $prefix"._sections." where secid=$secid");
    list($secid, $secname, $image) = mysql_fetch_array($result);
    $result2 = mysql_query("select artid from $prefix"._seccont." where secid=$secid");
    $number = mysql_num_rows($result2);
    OpenTable();
    echo "<img src=\"images/sections/$image\" border=\"0\" alt=\"\"><br><br>"
	."<font size=\"3\"><b>"._EDITSECTION.": $secname</b></font>"
	."<br>("._SECTIONHAS." $number "._ARTICLESATTACH.")"
	."<br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._SECTIONNAME."</b><br><font size=\"1\">"._40CHARSMAX."</font><br>"
	."<input type=\"text\" name=\"secname\" size=\"40\" maxlength=\"40\" value=\"$secname\"><br><br>"
	."<b>"._SECTIONIMG."</b><br><font size=\"1\">"._SECIMGEXAMPLE."</font><br>"
	."<input type=\"text\" name=\"image\" size=\"40\" maxlength=\"50\" value=\"$image\"><br><br>"
	."<input type=\"hidden\" name=\"secid\" value=\"$secid\">"
	."<input type=\"hidden\" name=\"op\" value=\"sectionchange\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\"> [ <a href=\"admin.php?op=sectiondelete&amp;secid=$secid&amp;ok=0\">"._DELETE."</a> ]"
	."</form>";
    CloseTable();
    include("footer.php");
}

function sectionchange($secid, $secname, $image) {
    global $prefix;
    $secname = stripslashes(FixQuotes($secname));
    $image = stripslashes(FixQuotes($image));
    mysql_query("update $prefix"._sections." set secname='$secname', image='$image' where secid=$secid");
    Header("Location: admin.php?op=sections");
}

function secartchange($artid, $secid, $title, $content) {
    global $prefix;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    mysql_query("update $prefix"._seccont." set secid='$secid', title='$title', content='$content' where artid=$artid");
    Header("Location: admin.php?op=sections");
}

function sectiondelete($secid, $ok=0) {
    global $prefix;
    if ($ok==1) {
        mysql_query("delete from $prefix"._seccont." where secid='$secid'");
        mysql_query("delete from $prefix"._sections." where secid='$secid'");
        Header("Location: admin.php?op=sections");
    } else {
        include("header.php");
        GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._SECTIONSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	$result=mysql_query("select secname from $prefix"._sections." where secid=$secid");
	list($secname) = mysql_fetch_row($result);
	OpenTable();
	echo "<center><b>"._DELSECTION.": $secname</b><br><br>"
	    .""._DELSECWARNING." $secname?<br>"
	    .""._DELSECWARNING1."<br><br>"
	    ."[ <a href=\"admin.php?op=sections\">"._NO."</a> | <a href=\"admin.php?op=sectiondelete&amp;secid=$secid&amp;ok=1\">"._YES."</a> ]</center>";
	CloseTable();
        include("footer.php");
    }
}

function secartdelete($artid, $ok=0) {
    global $prefix;
    if ($ok==1) {
        mysql_query("delete from $prefix"._seccont." where artid='$artid'");
        Header("Location: admin.php?op=sections");
    } else {
        include("header.php");
        GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._SECTIONSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	$result = mysql_query("select title from $prefix"._seccont." where artid=$artid");
	list($title) = mysql_fetch_row($result);
	OpenTable();
	echo "<center><b>"._DELARTICLE.": $title</b><br><br>"
	    .""._DELARTWARNING."<br><br>"
	    ."[ <a href=\"admin.php?op=sections\">"._NO."</a> | <a href=\"admin.php?op=secartdelete&amp;artid=$artid&amp;ok=1\">"._YES."</a> ]</center>";
	CloseTable();
        include("footer.php");
    }
}

switch ($op) {

    case "sections":
    sections();
    break;

    case "sectionedit":
    sectionedit($secid);
    break;

    case "sectionmake":
    sectionmake($secname, $image);
    break;

    case "sectiondelete":
    sectiondelete($secid, $ok);
    break;

    case "sectionchange":
    sectionchange($secid, $secname, $image);
    break;

    case "secarticleadd":
    secarticleadd($secid, $title, $content);
    break;
		
    case "secartedit":
    secartedit($artid);
    break;
			
    case "secartchange":
    secartchange($artid, $secid, $title, $content);
    break;
		
    case "secartdelete":
    secartdelete($artid, $ok);
    break;

}

} else {
    echo "Access Denied";
}

?>