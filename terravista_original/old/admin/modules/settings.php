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
$hlpfile = "manual/config.html";
$result = mysql_query("select radminsuper from $prefix"._authors." where aid='$aid'");
list($radminsuper) = mysql_fetch_row($result);
if ($radminsuper==1) {

/*********************************************************/
/* Configuration Functions to Setup all the Variables    */
/*********************************************************/

function Configure() {
    global $hlpfile, $admin;
    include ("config.php");
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._SITECONFIG."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._GENSITEINFO."</b></font></center>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\"><tr><td>"
	.""._SITENAME.":</td><td><input type=\"text\" name=\"xsitename\" value=\"$sitename\" size=\"50\" maxlength=\"100\">"
	."</td></tr><tr><td>"
	.""._SITEURL.":</td><td><input type=\"text\" name=\"xnukeurl\" value=\"$nukeurl\" size=\"50\" maxlength=\"200\">"
	."</td></tr><tr><td>"
	.""._SITELOGO.":</td><td><input type=\"text\" name=\"xsite_logo\" value=\"$site_logo\" size=\"20\" maxlength=\"25\">"
	."</td></tr><tr><td>"
	.""._SITESLOGAN.":</td><td><input type=\"text\" name=\"xslogan\" value=\"$slogan\" size=\"50\" maxlength=\"100\">"
	."</td></tr><tr><td>"
	.""._STARTDATE.":</td><td><input type=\"text\" name=\"xstartdate\" value=\"$startdate\" size=\"20\" maxlength=\"30\">"
	."</td></tr><tr><td>"
	.""._ADMINEMAIL.":</td><td><input type=\"text\" name=\"xadminmail\" value=\"$adminmail\" size=30 maxlength=100>"
	."</td></tr><tr><td>"
	.""._ITEMSTOP.":</td><td><select name=\"xtop\">"
	."<option name=\"xtop\">$top</option>"
	."<option name=\"xtop\">5</option>"
	."<option name=\"xtop\">10</option>"
        ."<option name=\"xtop\">15</option>"
        ."<option name=\"xtop\">20</option>"
        ."<option name=\"xtop\">25</option>"
        ."<option name=\"xtop\">30</option>"
        ."</select>"
        ."</td></tr><tr><td>"
        .""._STORIESHOME.":</td><td><select name=\"xstoryhome\">"
        ."<option name=\"xstoryhome\">$storyhome</option>"
        ."<option name=\"xstoryhome\">5</option>"
        ."<option name=\"xstoryhome\">10</option>"
        ."<option name=\"xstoryhome\">15</option>"
        ."<option name=\"xstoryhome\">20</option>"
        ."<option name=\"xstoryhome\">25</option>"
        ."<option name=\"xstoryhome\">30</option>"
        ."</select>"
        ."</td></tr><tr><td>"
        .""._OLDSTORIES.":</td><td><select name=\"xoldnum\">"
        ."<option name=\"xoldnum\">$oldnum</option>"
        ."<option name=\"xoldnum\">10</option>"
        ."<option name=\"xoldnum\">20</option>"
        ."<option name=\"xoldnum\">30</option>"
        ."<option name=\"xoldnum\">40</option>"
        ."<option name=\"xoldnum\">50</option>"
        ."</select>"
        ."</td></tr><tr><td>"
        .""._ACTULTRAMODE."</td><td>";
    if ($ultramode==1) {
	echo "<input type=\"radio\" name=\"xultramode\" value=\"1\" checked>"._YES." &nbsp;
	<input type=\"radio\" name=\"xultramode\" value=\"0\">"._NO."";
    } else {
	echo "<input type=\"radio\" name=\"xultramode\" value=\"1\">"._YES." &nbsp;
	<input type=\"radio\" name=\"xultramode\" value=\"0\" checked>"._NO."";
    }
    echo "</td></tr><tr><td>
    "._ALLOWANONPOST." </td><td>";
    if ($anonpost==1) {
	echo "<input type=\"radio\" name=\"xanonpost\" value=\"1\" checked>"._YES." &nbsp;
	<input type=\"radio\" name=\"xanonpost\" value=\"0\">"._NO."";
    } else {
	echo "<input type=\"radio\" name=\"xanonpost\" value=\"1\">"._YES." &nbsp;
	<input type=\"radio\" name=\"xanonpost\" value=\"0\" checked>"._NO."";
    }
    echo "</td></tr><tr><td>"
	.""._DEFAULTTHEME.":</td><td><select name=\"xDefault_Theme\">";
    $handle=opendir('themes');
    while ($file = readdir($handle)) {
	if ( (!ereg("[.]",$file)) ) {
		$themelist .= "$file ";
	}
    }
    closedir($handle);
    $themelist = explode(" ", $themelist);
    sort($themelist);
    for ($i=0; $i < sizeof($themelist); $i++) {
	if($themelist[$i]!="") {
	    echo "<option name=\"xDefault_Theme\" value=\"$themelist[$i]\" ";
		if($themelist[$i]==$Default_Theme) echo "selected";
		echo ">$themelist[$i]\n";
	}
    }
    echo "</select>"
	."</td></tr><tr><td>"
	.""._SELLANGUAGE.":</td><td>"
	."<select name=\"xlanguage\">";
    $handle=opendir('language');
    while ($file = readdir($handle)) {
	if (ereg("^lang\-(.+)\.php", $file, $matches)) {
            $langFound = $matches[1];
            $languageslist .= "$langFound ";
        }
    }
    closedir($handle);
    $languageslist = explode(" ", $languageslist);
    for ($i=0; $i < sizeof($languageslist); $i++) {
	if($languageslist[$i]!="") {
	    echo "<option name=\"xlanguage\" value=\"$languageslist[$i]\" ";
		if($languageslist[$i]==$language) echo "selected";
		echo ">$languageslist[$i]\n";
	}
    }
    echo "</select>"
	."</td></tr><tr><td>"
	.""._LOCALEFORMAT.":</td><td><input type=\"text\" name=\"xlocale\" value=\"$locale\" size=\"20\" maxlength=\"40\">"
	."</td></tr></table>";
    CloseTable();
    echo "<br><a name=\"banners\">";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._BANNERSOPT."</b></font></center>"
	."<table border=\"0\"><tr><td>"
	.""._ACTBANNERS."</td><td>";
    if ($banners==1) {
	echo "<input type=\"radio\" name=\"xbanners\" value=\"1\" checked>"._YES." &nbsp;"
	    ."<input type=\"radio\" name=\"xbanners\" value=\"0\">"._NO."";
    } else {
	echo "<input type=\"radio\" name=\"xbanners\" value=\"1\">"._YES." &nbsp;"
	    ."<input type=\"radio\" name=\"xbanners\" value=\"0\" checked>"._NO."";
    }
    echo "</td></tr><tr><td>"
	.""._YOURIP.":</td><td>"
	."<input type=\"text\" name=\"xmyIP\" value=\"$myIP\">"
	."</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._FOOTERMSG."</b></font></center>"
	."<table border=\"0\"><tr><td>"
	.""._FOOTERLINE1.":</td><td><textarea name=\"xfoot1\" cols=\"60\" rows=\"5\">$foot1</textarea>"
	."</td></tr><tr><td>"
	.""._FOOTERLINE2.":</td><td><textarea name=\"xfoot2\" cols=\"60\" rows=\"5\">$foot2</textarea>"
	."</td></tr><tr><td>"
	.""._FOOTERLINE3.":</td><td><textarea name=\"xfoot3\" cols=\"60\" rows=\"5\">$foot3</textarea>"
	."</td></tr><tr><td>"
	.""._FOOTERLINE4.":</td><td><textarea name=\"xfoot4\" cols=\"60\" rows=\"5\">$foot4</textarea>"
	."</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._BACKENDCONF."</b></font></center>"
	."<table border=\"0\"><tr><td>"
	.""._BACKENDTITLE.":</td><td><input type=\"text\" name=\"xbackend_title\" value=\"$backend_title\" size=\"50\" maxlength=\"100\">"
	."</td></tr><tr><td>"
	.""._BACKENDLANG.":</td><td><input type=\"text\" name=\"xbackend_language\" value=\"$backend_language\" size=\"10\" maxlength=\"10\">"
	."</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._WEBLINKSCONF."</b></font></center>"
	."<table border=\"0\"><tr><td>"
	.""._LINKSPAGE.":</td><td>"
	."<select name=\"xperpage\">"
	."<option name=\"xperpage\" value=\"$perpage\" selected>$perpage</option>"
	."<option name=\"xperpage\" value=\"10\">10</option>"
	."<option name=\"xperpage\" value=\"15\">15</option>"
	."<option name=\"xperpage\" value=\"20\">20</option>"
	."<option name=\"xperpage\" value=\"25\">25</option>"
	."<option name=\"xperpage\" value=\"30\">30</option>"
	."<option name=\"xperpage\" value=\"50\">50</option>"
	."</select>"
	."<input type=\"hidden\" value=\"$links_anonaddlinklock\" name=\"xlinks_anonaddlinklock\">"
	."<input type=\"hidden\" value=\"$anonwaitdays\" name=\"xanonwaitdays\">"
	."<input type=\"hidden\" value=\"$outsidewaitdays\" name=\"xoutsidewaitdays\">"
	."<input type=\"hidden\" value=\"$useoutsidevoting\" name=\"xuseoutsidevoting\">"
	."<input type=\"hidden\" value=\"$anonweight\" name=\"xanonweight\">"
	."<input type=\"hidden\" value=\"$outsideweight\" name=\"xoutsideweight\">"
	."<input type=\"hidden\" value=\"$detailvotedecimal\" name=\"xdetailvotedecimal\">"
	."<input type=\"hidden\" value=\"$mainvotedecimal\" name=\"xmainvotedecimal\">"
	."<input type=\"hidden\" value=\"$toplinkspercentrigger\" name=\"xtoplinkspercentrigger\">"
	."<input type=\"hidden\" value=\"$toplinks\" name=\"xtoplinks\">"
	."<input type=\"hidden\" value=\"$mostpoplinkspercentrigger\" name=\"xmostpoplinkspercentrigger\">"
	."<input type=\"hidden\" value=\"$mostpoplinks\" name=\"xmostpoplinks\">"
	."<input type=\"hidden\" value=\"$featurebox\" name=\"xfeaturebox\">"
	."<input type=\"hidden\" value=\"$linkvotemin\" name=\"xlinkvotemin\">"
	."<input type=\"hidden\" value=\"$blockunregmodify\" name=\"xblockunregmodify\">"
	."</td></tr><tr><td>"
	.""._TOBEPOPULAR.":</td><td>"
	."<select name=\"xpopular\">"
	."<option name=\"xpopular\" value=\"$popular\" selected>$popular</option>"
	."<option name=\"xpopular\" value=\"100\">100</option>"
	."<option name=\"xpopular\" value=\"250\">250</option>"
	."<option name=\"xpopular\" value=\"500\">500</option>"
	."<option name=\"xpopular\" value=\"1000\">1000</option>"
	."<option name=\"xpopular\" value=\"1500\">1500</option>"
	."<option name=\"xpopular\" value=\"2000\">2000</option>"
	."</select>"
	."</td></tr><tr><td>"
	.""._LINKSASNEW.":</td><td>"
	."<select name=\"xnewlinks\">"
	."<option name=\"xnewlinks\" value=\"$newlinks\" selected>$newlinks</option>"
	."<option name=\"xnewlinks\" value=\"10\">10</option>"
	."<option name=\"xnewlinks\" value=\"15\">15</option>"
	."<option name=\"xnewlinks\" value=\"20\">20</option>"
	."<option name=\"xnewlinks\" value=\"25\">25</option>"
	."<option name=\"xnewlinks\" value=\"30\">30</option>"
	."<option name=\"xnewlinks\" value=\"50\">50</option>"
	."</select>"
	."</td></tr><tr><td>"
	.""._LINKSASBEST.":</td><td>"
	."<select name=\"xtoplinks\">"
	."<option name=\"xtoplinks\" value=\"$toplinks\" selected>$toplinks</option>"
	."<option name=\"xtoplinks\" value=\"10\">10</option>"
	."<option name=\"xtoplinks\" value=\"15\">15</option>"
	."<option name=\"xtoplinks\" value=\"20\">20</option>"
	."<option name=\"xtoplinks\" value=\"25\">25</option>"
	."<option name=\"xtoplinks\" value=\"30\">30</option>"
	."<option name=\"xtoplinks\" value=\"50\">50</option>"
	."</select>"
	."</td></tr><tr><td>"
	.""._LINKSINRES.":</td><td>"
	."<select name=\"xlinksresults\">"
	."<option name=\"xlinksresults\" value=\"$linksresults\" selected>$linksresults</option>"
	."<option name=\"xlinksresults\" value=\"10\">10</option>"
	."<option name=\"xlinksresults\" value=\"15\">15</option>"
	."<option name=\"xlinksresults\" value=\"20\">20</option>"
	."<option name=\"xlinksresults\" value=\"25\">25</option>"
	."<option name=\"xlinksresults\" value=\"30\">30</option>"
	."<option name=\"xlinksresults\" value=\"50\">50</option>"
	."</select>"
	."</td></tr><tr><td>"
	.""._ANONPOSTLINKS."</td><td>";
    if ($links_anonaddlinklock==1) {
	echo "<input type=\"radio\" name=\"xlinks_anonaddlinklock\" value=\"1\" checked>"._YES." &nbsp;
	<input type=\"radio\" name=\"xlinks_anonaddlinklock\" value=\"0\">"._NO."";
    } else {
	echo "<input type=\"radio\" name=\"xlinks_anonaddlinklock\" value=\"1\">"._YES." &nbsp;
	<input type=\"radio\" name=\"xlinks_anonaddlinklock\" value=\"0\" checked>"._NO."";
    }
    echo "</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._MAIL2ADMIN."</b></font></center>"
	."<table border=\"0\"><tr><td>"
	.""._NOTIFYSUBMISSION."</td><td>";
    if ($notify==1) {
	echo "<input type=\"radio\" name=\"xnotify\" value=\"1\" checked>"._YES." &nbsp;
	<input type=\"radio\" name=\"xnotify\" value=\"0\">"._NO."";
    } else {
	echo "<input type=\"radio\" name=\"xnotify\" value=\"1\">"._YES." &nbsp;
	<input type=\"radio\" name=\"xnotify\" value=\"0\" checked>"._NO."";
    }
    echo "</td></tr><tr><td>"
	.""._EMAIL2SENDMSG.":</td><td><input type=\"text\" name=\"xnotify_email\" value=\"$notify_email\" size=\"30\" maxlength=\"100\">"
	."</td></tr><tr><td>"
	.""._EMAILSUBJECT.":</td><td><input type=\"text\" name=\"xnotify_subject\" value=\"$notify_subject\" size=\"50\" maxlength=\"100\">"
	."</td></tr><tr><td>"
	.""._EMAILMSG.":</td><td><textarea name=\"xnotify_message\" cols=\"40\" rows=\"8\">$notify_message</textarea>"
	."</td></tr><tr><td>"
	.""._EMAILFROM.":</td><td><input type=\"text\" name=\"xnotify_from\" value=\"$notify_from\" size=\"15\" maxlength=\"25\">"
	."</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._COMMENTSMOD."</b></font></center>"
	."<table border=\"0\"><tr><td>"
	.""._MODTYPE.":</td><td>"
	."<select name=\"xmoderate\">";
    if ($moderate==1) {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
    } elseif ($moderate==2) {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
    } elseif ($moderate==0) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";
    }
    echo "<option name=\"xmoderate\" value=\"1\" $sel1>"._MODADMIN."</option>"
        ."<option name=\"xmoderate\" value=\"2\" $sel2>"._MODUSERS."</option>"
        ."<option name=\"xmoderate\" value=\"0\" $sel3>"._NOMOD."</option>"
	."</select></td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._COMMENTSOPT."</b></font></center>"
	."<table border=\"0\"><tr><td>"
	.""._COMMENTSLIMIT.":</td><td><input type=\"text\" name=\"xcommentlimit\" value=\"$commentlimit\" size=\"11\" maxlength=\"10\">"
	."</td></tr><tr><td>"
	.""._ANONYMOUSNAME.":</td><td><input type=\"text\" name=\"xanonymous\" value=\"$anonymous\">"
	."</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._SURVEYSCONF."</b></font></center>"
	."<table border=\"0\"><tr><td>"
	.""._SCALEBAR.":</td><td><input type=\"text\" name=\"xBarScale\" value=\"$BarScale\" size=\"4\" maxlength=\"3\">"
	."</td></tr><tr><td>"
	.""._ALLOWTWICE."</td><td>";
    if ($setCookies==0) {
	echo "<input type=\"radio\" name=\"xsetCookies\" value=\"0\" checked>"._YES." &nbsp;
	<input type=\"radio\" name=\"xsetCookies\" value=\"1\">"._NO."";
    } else {
	echo "<input type=\"radio\" name=\"xsetCookies\" value=\"0\">"._YES." &nbsp;
	<input type=\"radio\" name=\"xsetCookies\" value=\"1\" checked>"._NO."";
    }
    echo "</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._GRAPHICOPT."</b></font></center>"
	."<table border=\"0\"><tr><td>"
	.""._TOPICSPATH.":</td><td><input type=\"text\" name=\"xtipath\" value=\"$tipath\" size=\"50\" maxlength=\"100\">"
	."</td></tr><tr><td>"
	.""._USERPATH.":</td><td><input type=\"text\" name=\"xuserimg\" value=\"$userimg\" size=\"50\" maxlength=\"100\">"
	."</td></tr><tr><td>"
	.""._ADMINPATH.":</td><td><input type=\"text\" name=\"xadminimg\" value=\"$adminimg\" size=\"50\" maxlength=\"100\">"
	."</td></tr><tr><td>"
	.""._ADMINGRAPHIC."</td><td>";
    if ($admingraphic==1) {
	echo "<input type=\"radio\" name=\"xadmingraphic\" value=\"1\" checked>"._YES." &nbsp;
	<input type=\"radio\" name=\"xadmingraphic\" value=\"0\">"._NO."";
    } else {
	echo "<input type=\"radio\" name=\"xadmingraphic\" value=\"1\">"._YES." &nbsp;
	<input type=\"radio\" name=\"xadmingraphic\" value=\"0\" checked>"._NO."";
    }
    echo "</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._MISCOPT."</b></font></center>"
	."<table border=\"0\"><tr><td>"
	.""._ARTINADMIN.":</td><td>"
        ."<select name=\"xadmart\">"
        ."<option name=\"xadmart\" value=\"$admart\">$admart</option>"
        ."<option name=\"xadmart\" value=\"10\">10</option>"
        ."<option name=\"xadmart\" value=\"15\">15</option>"
        ."<option name=\"xadmart\" value=\"20\">20</option>"
        ."<option name=\"xadmart\" value=\"25\">25</option>"
        ."<option name=\"xadmart\" value=\"30\">30</option>"
        ."</select>"
        ."</td></tr><tr><td>"
        .""._PASSWDLEN.":</td><td>"
        ."<select name=\"xminpass\">"
        ."<option name=\"xminpass\" value=\"$minpass\">$minpass</option>"
        ."<option name=\"xminpass\" value=\"3\">3</option>"
        ."<option name=\"xminpass\" value=\"5\">5</option>"
        ."<option name=\"xminpass\" value=\"8\">8</option>"
        ."<option name=\"xminpass\" value=\"10\">10</option>"
        ."</select>"
        ."</td></tr><tr><td>"
        .""._ACTIVATEHTTPREF."</td><td>";
    if ($httpref==1) {
	echo "<input type=\"radio\" name=xhttpref value=\"1\" checked>"._YES." &nbsp;
	<input type=\"radio\" name=xhttpref value=\"0\">"._NO."";
    } else {
	echo "<input type=\"radio\" name=\"xhttpref\" value=\"1\">"._YES." &nbsp;
	<input type=\"radio\" name=\"xhttpref\" value=\"0\" checked>"._NO."";
    }
    echo "</td></tr><tr><td>"
	.""._MAXREF."</td><td>"
	."<select name=\"xhttprefmax\">"
        ."<option name=\"xhttprefmax\" value=\"$httprefmax\">$httprefmax</option>"
        ."<option name=\"xhttprefmax\" value=\"100\">100</option>"
        ."<option name=\"xhttprefmax\" value=\"250\">250</option>"
        ."<option name=\"xhttprefmax\" value=\"500\">500</option>"
        ."<option name=\"xhttprefmax\" value=\"1000\">1000</option>"
        ."<option name=\"xhttprefmax\" value=\"1000\">2000</option>"
        ."</select>"
        ."</td></tr><tr><td>"
        .""._COMMENTSPOLLS."</td><td>";
    if ($pollcomm==1) {
	echo "<input type=\"radio\" name=\"xpollcomm\" value=\"1\" checked>"._YES." &nbsp;
	<input type=\"radio\" name=\"xpollcomm\" value=\"0\">"._NO."";
    } else {
	echo "<input type=\"radio\" name=\"xpollcomm\" value=\"1\">"._YES." &nbsp;
	<input type=\"radio\" name=\"xpollcomm\" value=\"0\" checked>"._NO."";
    }
    echo "</td></tr></table><br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"ConfigSave\">"
	."<center><input type=\"submit\" value=\""._SAVECHANGES."\"></center>"
	."</form>";
    CloseTable();
    include ("footer.php");
}

function ConfigSave($xsitename, $xnukeurl, $xsite_logo, $xslogan, $xstartdate, $xadminmail, $xtop, $xstoryhome, $xoldnum, $xultramode, $xanonpost, $xDefault_Theme, $xbanners, $xmyIP, $xfoot1, $xfoot2, $xfoot3, $xfoot4, $xbackend_title, $xbackend_language, $xlanguage, $xlocale, $xperpage, $xpopular, $xnewlinks, $xtoplinks, $xlinksresults, $xlinks_anonaddlinklock, $xanonwaitdays, $xoutsidewaitdays, $xuseoutsidevoting, $xanonweight, $xoutsideweight, $xdetailvotedecimal, $xmainvotedecimal, $xtoplinkspercentrigger, $xtoplinks, $xmostpoplinkspercentrigger, $xmostpoplinks, $xfeaturebox, $xlinkvotemin, $xblockunregmodify, $xnotify, $xnotify_email, $xnotify_subject, $xnotify_message, $xnotify_from, $xmoderate, $xcommentlimit, $xanonymous, $xBarScale, $xsetCookies, $xtipath, $xuserimg, $xadminimg, $xadmingraphic, $xadmart, $xminpass, $xhttpref, $xhttprefmax, $xpollcomm) {
    include ("config.php");
    $xsitename = FixQuotes($xsitename);
    $xnukeurl = FixQuotes($xnukeurl);
    $xsite_logo = FixQuotes($xsite_logo);
    $xslogan = FixQuotes($xslogan);
    $xstartdate = FixQuotes($xstartdate);
    $xDefault_Theme = FixQuotes($xDefault_Theme);
    $xmyIP = FixQuotes($xmyIP);
    $xfoot1 = FixQuotes($xfoot1);
    $xfoot2 = FixQuotes($xfoot2);
    $xfoot3 = FixQuotes($xfoot3);
    $xfoot4 = FixQuotes($xfoot4);
    $xbackend_title = FixQuotes($xbackend_title);
    $xbackend_language = FixQuotes($xbackend_language);
    $xlanguage = FixQuotes($xlanguage);
    $xlocale = FixQuotes($xlocale);
    $xnotify_email = FixQuotes($xnotify_email);
    $xnotify_subject = FixQuotes($xnotify_subject);
    $xnotify_message = FixQuotes($xnotify_message);
    $xnotify_from = FixQuotes($xnotify_from);
    $xanonymous = FixQuotes($xanonymous);
    $xtipath = FixQuotes($xtipath);
    $xuserimg = FixQuotes($xuserimg);
    $xadminimg = FixQuotes($xadminimg);
    $file = fopen("config.php", "w");
    $line = "######################################################################\n";
    $content = "<?php\n\n";
    $content .= "$line";
    $content .= "# PHP-NUKE: Web Portal System\n";
    $content .= "# ===========================\n";
    $content .= "#\n";
    $content .= "# Copyright (c) 2000 by Francisco Burzi (fbc@mandrakesoft.com)\n";
    $content .= "# http://phpnuke.org\n";
    $content .= "#\n";
    $content .= "# This module is to configure the main options for your site\n";
    $content .= "#\n";
    $content .= "# This program is free software. You can redistribute it and/or modify\n";
    $content .= "# it under the terms of the GNU General Public License as published by\n";
    $content .= "# the Free Software Foundation; either version 2 of the License.\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Database & System Config\n";
    $content .= "#\n";
    $content .= "# dbhost:   MySQL Database Hostname\n";
    $content .= "# dbuname:  MySQL Username\n";
    $content .= "# dbpass:   MySQL Password\n";
    $content .= "# dbname:   MySQL Database Name\n";
    $content .= "# system:   0 for Unix/Linux, 1 for Windows\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$dbhost = \"$dbhost\";\n";
    $content .= "\$dbuname = \"$dbuname\";\n";
    $content .= "\$dbpass = \"$dbpass\";\n";
    $content .= "\$dbname = \"$dbname\";\n";
    $content .= "\$system = $system;\n";
    $content .= "\$prefix = $prefix;\n";
    $content .= "\n";
    $content .= "/*********************************************************************/\n";
    $content .= "/* You finished to configure the Database. Now you can change all    */\n";
    $content .= "/* you want in the Administration Section.   To enter just launch    */\n";
    $content .= "/* you web browser pointing to http://yourdomain.com/admin.php       */\n";
    $content .= "/*                                                                   */\n";
    $content .= "/* At the prompt use the following ID to login (case sensitive):     */\n";
    $content .= "/*                                                                   */\n";
    $content .= "/* AdminID: God                                                      */\n";
    $content .= "/* Password: Password                                                */\n";
    $content .= "/*                                                                   */\n";
    $content .= "/* Be sure to change inmediately the God login & password clicking   */\n";
    $content .= "/* on Edit Admin in the Admin menu. After that, click on Preferences */\n";
    $content .= "/* to configure your new site. In that menu you can change all you   */\n";
    $content .= "/* need to change.                                                   */\n";
    $content .= "/*                                                                   */\n";
    $content .= "/* Remember to chmod 666 this file in order to let the system write  */\n";
    $content .= "/* to it properly. If you can't change the permissions you can edit  */\n";
    $content .= "/* the rest of this file by hand.                                    */\n";
    $content .= "/*                                                                   */\n";
    $content .= "/* Congratulations! now you have an automated news portal!           */\n";
    $content .= "/* Thanks for choose PHP-Nuke: The Future of the Web                 */\n";
    $content .= "/*********************************************************************/\n";
    $content .= "\n\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# General Site Configuration\n";
    $content .= "#\n";
    $content .= "# \$sitename:      Your Site Name\n";
    $content .= "# \$nukeurl:      Complete URL for your site (Do not put / at end)\n";
    $content .= "# \$site_logo:     Logo for Printer Friendly Page (It's good to have a Black/White graphic)\n";
    $content .= "# \$slogan:        Your site's slogan\n";
    $content .= "# \$startdate:     Start Date to display in Statistic Page\n";
    $content .= "# \$adminmail:     Site Administrator's Email\n";
    $content .= "# \$anonpost:      Allow Anonymous to Post Comments? (1=Yes 0=No)\n";
    $content .= "# \$Default_Theme: Default Theme for your site (See /themes directory for the complete list, case sensitive!)\n";
    $content .= "# \$foot(x):       Messages for all footer pages (Can include HTML code)\n";
    $content .= "# \$commentlimit:  Maximum number of bytes for each comment\n";
    $content .= "# \$anonymous:     Anonymous users Default Name\n";
    $content .= "# \$minpass:       Minimum character for users passwords\n";
    $content .= "# \$pollcomm:      Activate comments in Polls? (1=Yes 0=No)\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$sitename = \"$xsitename\";\n";
    $content .= "\$nukeurl = \"$xnukeurl\";\n";
    $content .= "\$site_logo = \"$xsite_logo\";\n";
    $content .= "\$slogan = \"$xslogan\";\n";
    $content .= "\$startdate = \"$xstartdate\";\n";
    $content .= "\$adminmail = \"$xadminmail\";\n";
    $content .= "\$anonpost = $xanonpost;\n";
    $content .= "\$Default_Theme = \"$xDefault_Theme\";\n";
    $content .= "\$foot1 = \"$xfoot1\";\n";
    $content .= "\$foot2 = \"$xfoot2\";\n";
    $content .= "\$foot3 = \"$xfoot3\";\n";
    $content .= "\$foot4 = \"$xfoot4\";\n";
    $content .= "\$commentlimit = $xcommentlimit;\n";
    $content .= "\$anonymous = \"$xanonymous\";\n";
    $content .= "\$minpass = $xminpass;\n";
    $content .= "\$pollcomm = $xpollcomm;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# General Stories Options\n";
    $content .= "#\n";
    $content .= "# \$top:       How many items in Top Page?\n";
    $content .= "# \$storyhome: How many stories to display in Home Page?\n";
    $content .= "# \$oldnum:    How many stories in Old Articles Box?\n";
    $content .= "# \$ultramode: Activate ultramode plain text file backend syndication? (1=Yes 0=No  Need to chmod 666 ultramode.txt file)\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$top = $xtop;\n";
    $content .= "\$storyhome = $xstoryhome;\n";
    $content .= "\$oldnum = $xoldnum;\n";
    $content .= "\$ultramode = $xultramode;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Banners/Advertising Configuration\n";
    $content .= "#\n";
    $content .= "# \$banners: Activate Banners Ads for your site? (1=Yes 0=No)\n";
    $content .= "# \$myIP:    Write your IP number to not count impressions, be fair about this!\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$banners = $xbanners;\n";
    $content .= "\$myIP = \"$xmyIP\";\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# XML/RDF Backend Configuration\n";
    $content .= "#\n";
    $content .= "# \$backend_title:    Backend title, can be your site's name and slogan\n";
    $content .= "# \$backend_language: Language format of your site\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$backend_title = \"$xbackend_title\";\n";
    $content .= "\$backend_language = \"$xbackend_language\";\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Site Language Preferences\n";
    $content .= "#\n";
    $content .= "# \$language: Language of your site (You need to have lang-xxxxxx.php file for your selected language in the /language directory of your site)\n";
    $content .= "# \$locale:   Locale configuration to correctly display date with your country format. (See /usr/share/locale)\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$language = \"$xlanguage\";\n";
    $content .= "\$locale = \"$xlocale\";\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Web Links Preferences (Some variables are valid also for Downloads)\n";
    $content .= "#\n";
    $content .= "# \$perpage:      	    	How many links to show on each page?\n";
    $content .= "# \$popular:      	    	How many hits need a link to be listed as popular?\n";
    $content .= "# \$newlinks:     	    	How many links to display in the New Links Page?\n";
    $content .= "# \$toplinks:     	    	How many links to display in The Best Links Page? (Most Popular)\n";
    $content .= "# \$linksresults: 	    	How many links to display on each search result page?\n";
    $content .= "# \$links_anonaddlinklock:   	Lock Unregistered users from Suggesting New Links? (1=Yes 0=No)\n";
    $content .= "# \$anonwaitdays:        	Number of days anonymous users need to wait to vote on a link\n";
    $content .= "# \$outsidewaitdays:     	Number of days outside users need to wait to vote on a link (checks IP)\n";
    $content .= "# \$useoutsidevoting:        	Allow Webmasters to put vote links on their site (1=Yes 0=No)\n";
    $content .= "# \$anonweight:          	How many Unregistered User vote per 1 Registered User Vote?\n";
    $content .= "# \$outsideweight:       	How many Outside User vote per 1 Registered User Vote?\n";
    $content .= "# \$detailvotedecimal:       	Let Detailed Vote Summary Decimal out to N places. (no max)\n";
    $content .= "# \$mainvotedecimal:     	Let Main Vote Summary Decimal show out to N places. (max 4)\n";
    $content .= "# \$toplinkspercentrigger:   	1 to Show Top Links as a Percentage (else # of links)\n";
    $content .= "# \$toplinks:            	Either # of links OR percentage to show (percentage as whole number. #/100)\n";
    $content .= "# \$mostpoplinkspercentrigger:	1 to Show Most Popular Links as a Percentage (else # of links)\n";
    $content .= "# \$mostpoplinks:        	Either # of links OR percentage to show (percentage as whole number. #/100)\n";
    $content .= "# \$featurebox:          	1 to Show Feature Link Box on links Main Page? (1=Yes 0=No)\n";
    $content .= "# \$linkvotemin:         	Number votes needed to make the 'top 10' list\n";
    $content .= "# \$blockunregmodify:        	Block unregistered users from suggesting links changes? (1=Yes 0=No)\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$perpage = $xperpage;\n";
    $content .= "\$popular = $xpopular;\n";
    $content .= "\$newlinks = $xnewlinks;\n";
    $content .= "\$toplinks = $xtoplinks;\n";
    $content .= "\$linksresults = $xlinksresults;\n";
    $content .= "\$links_anonaddlinklock = $xlinks_anonaddlinklock;\n";
    $content .= "\$anonwaitdays = $xanonwaitdays;\n";
    $content .= "\$outsidewaitdays = $xoutsidewaitdays;\n";
    $content .= "\$useoutsidevoting = $xuseoutsidevoting;\n";
    $content .= "\$anonweight = $xanonweight;\n";
    $content .= "\$outsideweight = $xoutsideweight;\n";
    $content .= "\$detailvotedecimal = $xdetailvotedecimal;\n";
    $content .= "\$mainvotedecimal = $xmainvotedecimal;\n";
    $content .= "\$toplinkspercentrigger = $xtoplinkspercentrigger;\n";
    $content .= "\$toplinks = $xtoplinks;\n";
    $content .= "\$mostpoplinkspercentrigger = $xmostpoplinkspercentrigger;\n";
    $content .= "\$mostpoplinks = $xmostpoplinks;\n";
    $content .= "\$featurebox = $xfeaturebox;\n";
    $content .= "\$linkvotemin = $xlinkvotemin;\n";
    $content .= "\$blockunregmodify = $xblockunregmodify;\n";
    $content .= "\n";
    $content .= "$line\n";
    $content .= "# Downloads Preferences\n";
    $content .= "#\n";
    $content .= "# $newdownloads:     	    	  How many downloads to display in the New downloads Page?\n";
    $content .= "# $topdownloads:     	    	  How many downloads to display in The Best downloads Page? (Most Popular)\n";
    $content .= "# $downloadsresults: 	    	  How many downloads to display on each search result page?\n";
    $content .= "# $downloads_anonadddownloadlock: Lock Unregistered users from Suggesting New downloads? (1=Yes 0=No)\n";
    $content .= "# $user_adddownload:		  Let users to add new downloads? (1=Yes 0=No)\n";
    $content .= "# $topdownloadspercentrigger:     1 to Show Top downloads as a Percentage (else # of downloads)\n";
    $content .= "# $topdownloads:            	  Either # of downloads OR percentage to show (percentage as whole number. #/100)\n";
    $content .= "# $mostpopdownloadspercentrigger: 1 to Show Most Popular downloads as a Percentage (else # of downloads)\n";
    $content .= "# $mostpopdownloads:        	  Either # of downloads OR percentage to show (percentage as whole number. #/100)\n";
    $content .= "# $downloadvotemin:         	  Number votes needed to make the 'top 10' list\n";
    $content .= "$line\n";
    $content .= "\n";
    $content .= "\$newdownloads = $newdownloads;\n";
    $content .= "\$topdownloads = $topdownloads;\n";
    $content .= "\$downloadsresults = $downloadsresults;\n";
    $content .= "\$downloads_anonadddownloadlock = $downloads_anonadddownloadlock;\n";
    $content .= "\$user_adddownload = $user_adddownload;\n";
    $content .= "\$topdownloadspercentrigger = $topdownloadspercentrigger;\n";
    $content .= "\$topdownloads = $topdownloads;\n";
    $content .= "\$mostpopdownloadspercentrigger = $mostpopdownloadspercentrigger;\n";
    $content .= "\$mostpopdownloads = $mostpopdownloads;\n";
    $content .= "\$downloadvotemin = $downloadvotemin;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Notification of News Submissions\n";
    $content .= "#\n";
    $content .= "# \$notify:         Notify you each time your site receives a news submission? (1=Yes 0=No)\n";
    $content .= "# \$notify_email:   Email, address to send the notification\n";
    $content .= "# \$notify_subject: Email subject\n";
    $content .= "# \$notify_message: Email body, message\n";
    $content .= "# \$notify_from:    account name to appear in From field of the Email\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$notify = $xnotify;\n";
    $content .= "\$notify_email = \"$xnotify_email\";\n";
    $content .= "\$notify_subject = \"$xnotify_subject\";\n";
    $content .= "\$notify_message = \"$xnotify_message\";\n";
    $content .= "\$notify_from = \"$xnotify_from\";\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Moderation Config (not 100% working)\n";
    $content .= "#\n";
    $content .= "# \$moderate:   Activate moderation system? (1=Yes 0=No)\n";
    $content .= "# \$resons:     List of reasons for the moderation (each reason under quotes and comma separated)\n";
    $content .= "# \$badreasons: Number of bad reasons in the reasons list\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$moderate = $xmoderate;\n";
    $content .= "\$reasons = array(\"As Is\",
		    \"Offtopic\",
		    \"Flamebait\",
		    \"Troll\",
		    \"Redundant\",
		    \"Insighful\",
		    \"Interesting\",
		    \"Informative\",
		    \"Funny\",
		    \"Overrated\",
		    \"Underrated\");\n";
    $content .= "\$badreasons = 4;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Survey/Polls Config\n";
    $content .= "#\n";
    $content .= "# \$BarScale:   Scale for the Bar, multiple of 100, You may leave this to 1\n";
    $content .= "# \$setCookies: Set cookies to prevent visitors vote twice in a period of 24 hours? (1=Yes 0=No)\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$BarScale = $xBarScale;\n";
    $content .= "\$setCookies = $xsetCookies;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Some Graphics Options\n";
    $content .= "#\n";
    $content .= "# \$tipath:       Topics images path (put / only at the end, not at the begining)\n";
    $content .= "# \$userimg:      User images path (No / at begining and at the end)\n";
    $content .= "# \$adminimg:     Administration system images path (put / only at the end, not at the begining)\n";
    $content .= "# \$admingraphic: Activate graphic menu for Administration Menu? (1=Yes 0=No)\n";
    $content .= "# \$admart:       How many articles to show in the admin section?\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$tipath = \"$xtipath\";\n";
    $content .= "\$userimg = \"$xuserimg\";\n";
    $content .= "\$adminimg = \"$xadminimg\";\n";
    $content .= "\$admingraphic = $xadmingraphic;\n";
    $content .= "\$admart = $xadmart;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# HTTP Referers Options\n";
    $content .= "#\n";
    $content .= "# \$httpref:    Activate HTTP referer logs to know who is linking to our site? (1=Yes 0=No)";
    $content .= "# \$httprefmax: Maximum number of HTTP referers to store in the Database (Try to not set this to a high number, 500 ~ 1000 is Ok)\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$httpref = $xhttpref;\n";
    $content .= "\$httprefmax = $xhttprefmax;\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Allowable HTML tags\n";
    $content .= "#\n";
    $content .= "# \$AllowableHTML: HTML command to allow in the comments\n";
    $content .= "#                  =>2 means accept all qualifiers: <foo bar>\n";
    $content .= "#                  =>1 means accept the tag only: <foo>\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$AllowableHTML = array(\"p\"=>2,
		    \"b\"=>1,
		    \"i\"=>1,
		    \"a\"=>2,
		    \"em\"=>1,
		    \"br\"=>1,
		    \"strong\"=>1,
		    \"blockquote\"=>1,
                    \"tt\"=>1,
                    \"li\"=>1,
                    \"ol\"=>1,
                    \"ul\"=>1);\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Filters Options\n";
    $content .= "#\n";
    $content .= "# \$CensorList:	List of bad word to be replaced on Comments\n";
    $content .= "# \$CensorMode:  	0 = No Filtering (leave the bad words)\n";
    $content .= "# 			1 = Exact Match\n";
    $content .= "#			2 = Match Word at the Begining\n";
    $content .= "#			3 = Match String Anywhere in the Text\n";
    $content .= "# \$CensorReplace:	String to replace bad words\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$CensorList = array(\"fuck\",
		    \"cunt\",
		    \"fucker\",
		    \"fucking\",
		    \"pussy\",
		    \"cock\",
		    \"c0ck\",
		    \"cum\",
		    \"twat\",
		    \"clit\",
		    \"bitch\",
		    \"fuk\",
		    \"fuking\",
		    \"motherfucker\");\n";
    $content .= "\$CensorMode = 1;\n";
    $content .= "\$CensorReplace = \"*****\";\n";
    $content .= "\n";
    $content .= "$line";
    $content .= "# Do not touch the following options!\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "\$cookieadmtime = 2592000;\n";
    $content .= "\$cookiePrefix = \"NukePoll\";\n";
    $content .= "\$Version_Num = \"$Version_Num\";\n";
    $content .= "\n";
    $content .= "?>";
    fwrite($file, $content);
    fclose($file);
    Header("Location: admin.php?op=adminMain");
}

switch($op) {
	
    case "Configure":
    Configure();
    break;
		
    case "ConfigSave":
    ConfigSave($xsitename, $xnukeurl, $xsite_logo, $xslogan, $xstartdate, $xadminmail, $xtop, $xstoryhome, $xoldnum, $xultramode, $xanonpost, $xDefault_Theme, $xbanners, $xmyIP, $xfoot1, $xfoot2, $xfoot3, $xfoot4, $xbackend_title, $xbackend_language, $xlanguage, $xlocale, $xperpage, $xpopular, $xnewlinks, $xtoplinks, $xlinksresults, $xlinks_anonaddlinklock, $xanonwaitdays, $xoutsidewaitdays, $xuseoutsidevoting, $xanonweight, $xoutsideweight, $xdetailvotedecimal, $xmainvotedecimal, $xtoplinkspercentrigger, $xtoplinks, $xmostpoplinkspercentrigger, $xmostpoplinks, $xfeaturebox, $xlinkvotemin, $xblockunregmodify, $xnotify, $xnotify_email, $xnotify_subject, $xnotify_message, $xnotify_from, $xmoderate, $xcommentlimit, $xanonymous, $xBarScale, $xsetCookies, $xtipath, $xuserimg, $xadminimg, $xadmingraphic, $xadmart, $xminpass, $xhttpref, $xhttprefmax, $xpollcomm, $xEphemerids);
    break;

}

} else {
    echo "Access Denied";
}
?>
