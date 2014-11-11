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

if(!isset($mainfile)) { include("mainfile.php"); }
if(!isset($sid) && !isset($tid)) { exit(); }

if($save) {
    cookiedecode($user);
    mysql_query("update $prefix"._users." set umode='$mode', uorder='$order', thold='$thold' where uid='$cookie[0]'");
    getusrinfo($user);
    $info = base64_encode("$userinfo[uid]:$userinfo[uname]:$userinfo[pass]:$userinfo[storynum]:$userinfo[umode]:$userinfo[uorder]:$userinfo[thold]:$userinfo[noscore]");
    setcookie("user","$info",time()+$cookieusrtime);
}

if($op == "Reply") {
    Header("Location: comments.php?op=Reply&pid=0&sid=$sid&mode=$mode&order=$order&thold=$thold");
}

$result = mysql_query("select catid, aid, time, title, hometext, bodytext, topic, informant, notes FROM $prefix"._stories." where sid=$sid");
list($catid, $aid, $time, $title, $hometext, $bodytext, $topic, $informant, $notes) = mysql_fetch_row($result);
mysql_query("UPDATE $prefix"._stories." SET counter=counter+1 where sid=$sid");

$artpage = 1;
include ("header.php");
$artpage = 0;

formatTimestamp($time);
$title = stripslashes($title);
$hometext = stripslashes($hometext);
$bodytext = stripslashes($bodytext);
$notes = stripslashes($notes);

if ($notes != "") {
    $notes = "<br><br><b>"._NOTE."</b> <i>$notes</i>";
} else {
    $notes = "";
}

if($bodytext == "") { 
    $bodytext = "$hometext$notes";
} else { 
    $bodytext = "$hometext<br><br>$bodytext$notes";
}

if($informant == "") {
    $informant = $anonymous;
}

getTopics($sid);

if ($catid != 0) {
    $resultx = mysql_query("select title from $prefix"._stories."_cat where catid='$catid'");
    list($title1) = mysql_fetch_row($resultx);
    $title = "<a href=\"categories.php?op=newindex&amp;catid=$catid\">$title1</a>: $title";
}

echo "<table width=\"100%\" border=\"0\"><tr><td valign=\"top\">\n";
themearticle($aid, $informant, $datetime, $title, $bodytext, $topic, $topicname, $topicimage, $topictext);
echo "</td><td>&nbsp;</td><td valign=\"top\">\n";
loginbox();
$boxtitle = ""._RELATED."";
$boxstuff = "<font size=\"2\">";

$result=mysql_query("select name, url from $prefix"._related." where tid=$topic");
while(list($name, $url) = mysql_fetch_row($result)) {
    $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$url\" target=\"new\">$name</a><br>\n";
}

$boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"search.php?topic=$topic\">"._MOREABOUT." $topictext</a><br>\n";
$boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"search.php?author=$aid\">"._NEWSBY." $aid</a><br>\n";

/* This is a list for automatic related links on article internal page */
/* For this I used a multi-dimensional array so we can show the links titles */
/* as we want, doesn't matter the case of the string in the article. You can */
/* add or remove links from this array as you wish to fit your needs */

$relatedarray = array ("gpl" 		=> array ("GPL" 			=> "http://www.gnu.org"),
                       "linux" 		=> array ("Linux.com" 			=> "http://www.linux.com"),
		       "blender"	=> array ("Blender" 			=> "http://www.blender.nl"),
		       "gnu"		=> array ("GNU Project"			=> "http://www.gnu.org"),
		       "gimp"		=> array ("The GIMP"			=> "http://www.gimp.org"),
		       "cnn"		=> array ("CNN.com"			=> "http://www.cnn.com"),
		       "news.com"	=> array ("News.com"			=> "http://www.news.com"),
		       "ibm"		=> array ("IBM"				=> "http://www.ibm.com"),
		       "php"		=> array ("PHP HomePage"		=> "http://www.php.net"),
		       "mandrake"	=> array ("MandrakeSoft"		=> "http://www.mandrakesoft.com"),
		       "redhat"		=> array ("Red Hat"			=> "http://www.redhat.com"),
		       "red hat"	=> array ("Red Hat"			=> "http://www.redhat.com"),
		       "Debian"		=> array ("Debian GNU/Linux"		=> "http://www.debian.org"),
		       "Slackware"	=> array ("Slackware"			=> "http://www.slackware.com"),
		       "freebsd"	=> array ("FreeBSD"			=> "http://www.freebsd.org"),
		       "artist"		=> array ("Linux Artist"		=> "http://www.linuxartist.com"),
		       "games"		=> array ("Linux Games"			=> "http://www.linuxgames.com"),
		       "sourceforge"	=> array ("SourceForge"			=> "http://www.sourceforge.net"),
		       "source forge"	=> array ("SourceForge"			=> "http://www.sourceforge.net"),
		       "palm pilot"	=> array ("Palm Pilot"			=> "http://www.palm.com"),
		       "windows"	=> array ("Microsoft"			=> "http://www.microsoft.com"),
		       "microsoft"	=> array ("Microsoft"			=> "http://www.microsoft.com"),
		       "kernel"		=> array ("Linux Kernel Archives"	=> "http://www.kernel.org"),
		       "opensource"	=> array ("OpenSource"			=> "http://www.opensource.org"),
		       "open source"	=> array ("OpenSource"			=> "http://www.opensource.org"),
		       "nuke"		=> array ("PHP-Nuke"			=> "http://www.phpnuke.org"),
		       "compaq"		=> array ("Compaq"			=> "http://www.compaq.com"),
		       "intel"		=> array ("Intel"			=> "http://www.intel.com"),
		       "mysql"		=> array ("MySQL Database Server"	=> "http://www.mysql.com"),
		       "themes"		=> array ("Themes.org"			=> "http://www.themes.org"),
		       "suse"		=> array ("SuSE"			=> "http://www.suse.com"),
		       "script"		=> array ("HotScripts"			=> "http://www.hotscripts.com"),
		       "amd"		=> array ("AMD"				=> "http://www.amd.com"),
		       "transmeta"	=> array ("Transmeta"			=> "http://www.transmeta.com"),
		       "apple"		=> array ("Apple"			=> "http://www.apple.com"),
		       "apache"		=> array ("Apache Web Server"		=> "http://www.apache.org"),
		       "nasa"		=> array ("NASA"			=> "http://www.nasa.gov"),
		       "documentation"	=> array ("Linux Manuals"		=> "http://www.linuxdoc.org"),
		       "manual"		=> array ("Linux Manuals"		=> "http://www.linuxdoc.org"),
		       "howto"		=> array ("Linux Manuals"		=> "http://www.linuxdoc.org"),
		       "rtfm"		=> array ("Linux Manuals"		=> "http://www.linuxdoc.org"),
		       "dell"		=> array ("Dell"			=> "http://www.dell.com"),
		       "google"		=> array ("Google Search Engine"	=> "http://www.google.com"),
		       "translat"	=> array ("Babelfish Translator"	=> "http://babelfish.altavista.com"),
		       "w3"		=> array ("W3 Consortium"		=> "http://www.w3.org"),
		       "css"		=> array ("CSS Standard"		=> "http://www.w3.org/Style/CSS"),
		       " html"		=> array ("HTML Standard"		=> "http://www.w3.org/MarkUp"),
		       "xhmtl"		=> array ("XHTML Standard"		=> "http://www.w3.org/MarkUp"),
		       "rpm"		=> array ("RPM"				=> "http://www.rpm.org"),
		       "3com"		=> array ("3Com"			=> "http://www.3com.com"),
		       "sun microsyste" => array ("Sun Microsystems"		=> "http://www.sun.com"),
		       "staroffice" 	=> array ("Star Office"			=> "http://www.sun.com"),
		       "star office" 	=> array ("Star Office"			=> "http://www.sun.com"),
		       "openoffice" 	=> array ("Open Office"			=> "http://www.openoffice.org"),
		       "open office" 	=> array ("Open Office"			=> "http://www.openoffice.org"),
		       "oracle"		=> array ("Oracle"			=> "http://www.oracle.com"),
		       "informix"	=> array ("Informix"			=> "http://www.informix.com"),
		       "postgre"	=> array ("PostgreSQL"			=> "http://www.postgresql.org"),
		       "mp3"		=> array ("MP3.com"			=> "http://www.mp3.com"),
		       "gnome"		=> array ("GNOME"			=> "http://www.gnome.org"),
		       "kde"		=> array ("KDE"				=> "http://www.kde.org"),
		       "mozilla"	=> array ("Mozilla"			=> "http://www.mozilla.org"),
		       "netscape"	=> array ("Netscape"			=> "http://www.netscape.com"),
		       "corel"		=> array ("Corel"			=> "http://www.corel.com"),
		       " hp"		=> array ("Hewlett Packard"		=> "http://www.hp.com"),
		       "hewlett packar" => array ("Hewlett Packard"		=> "http://www.hp.com"),
		       "caldera"	=> array ("Caldera Systems"		=> "http://www.caldera.com"),
		       "freshmeat"	=> array ("Freshmeat"			=> "http://www.freshmeat.net"),
		       "slashdot"	=> array ("Slashdot"			=> "http://www.slashdot.org"),
		       "spam"		=> array ("Spam Cop"			=> "http://www.spamcop.net"),
		       "aol"		=> array ("America Online"		=> "http://www.aol.com"),
		       "america online"	=> array ("America Online"		=> "http://www.aol.com"),
		       "pov-ray"	=> array ("POV Ray"			=> "http://www.povray.org"),
		       "povray"		=> array ("POV Ray"			=> "http://www.povray.org"),
		       "pov ray"	=> array ("POV Ray"			=> "http://www.povray.org"),
		       "seti"		=> array ("SETI Institute"		=> "http://www.seti.org"),
		       "cnet"		=> array ("C|Net News"			=> "http://www.cnet.com"),
		       "zdnet"		=> array ("ZDNet News"			=> "http://www.zdnet.com"),
		       "napster"	=> array ("Napster"			=> "http://www.napster.com"),
		       "sony"		=> array ("Sony HomePage"		=> "http://www.sony.com"),
		       "xfree"		=> array ("X-Free86 Project"		=> "http://www.xfree.org"),
		       "x-free"		=> array ("X-Free86 Project"		=> "http://www.xfree.org"),
		       "x free"		=> array ("X-Free86 Project"		=> "http://www.xfree.org"),
		       "beos"		=> array ("BeOS"			=> "http://www.beos.com"),
		       "borland"	=> array ("Borland"			=> "http://www.borland.com"),
		       "kylix"		=> array ("Kylix HomePage"		=> "http://www.borland.com/kylix"),
		       "amazon"		=> array ("Amazon.com"			=> "http://www.amazon.com")
		       );

while (list ($key) = each ($relatedarray)) {
    $relarr = eregi($key, $bodytext);
    if ($relarr) {
        list($rep, $val) = each ($relatedarray[$key]);
        $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$val\" target=\"new\">$rep</a><br>\n";
    }
}

/* Multi-dimensional array end here */

$boxstuff .= "</font><br><hr noshade width=\"95%\" size=\"1\"><center><font size=\"2\"><b>"._MOSTREAD." $topictext:</b><br>\n";

$result2=mysql_query("select sid, title from $prefix"._stories." where topic=$topic order by counter desc limit 0,1");
list($topstory, $ttitle) = mysql_fetch_row($result2);

$boxstuff .= "<a href=\"article.php?sid=$topstory\">$ttitle</a></font></center><br><br>\n";
$boxstuff .= "<table border=\"0\" width=\"100%\"><tr><td align=\"left\">\n";
$boxstuff .= "</td><td align=\"right\">\n";
$boxstuff .= "<a href=\"print.php?sid=$sid\"><img src=\"images/print.gif\" border=0 Alt=\""._PRINTER."\" width=\"15\" height=\"11\"></a>&nbsp;&nbsp;";
$boxstuff .= "<a href=\"friend.php?op=FriendSend&amp;sid=$sid\"><img src=\"images/friend.gif\" border=\"0\" Alt=\""._FRIEND."\" width=\"15\" height=\"11\"></a>\n";
$boxstuff .= "</td></tr></table>\n";

$tbl = 180;
themesidebox($boxtitle, $boxstuff);
$tbl = 0;
echo "</td></tr></table>\n";
cookiedecode($user);
if($mode != "nocomments") {
    include("comments.php");
}
include ("footer.php");
?>