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
if(!isset($sid)) { exit(); }

function PrintPage($sid) {
    global $site_logo, $nukeurl, $sitename, $datetime, $prefix;
    $result=mysql_query("select title, time, hometext, bodytext, topic, notes from $prefix"._stories." where sid=$sid");
    list($title, $time, $hometext, $bodytext, $topic, $notes) = mysql_fetch_row($result);
    $result2=mysql_query("select topictext from $prefix"._topics." where topicid=$topic");
    list($topictext) = mysql_fetch_row($result2);
    formatTimestamp($time);
    echo "
    <html>
    <head><title>$sitename</title></head>
    <body bgcolor=\"#ffffff\" text=\"#000000\">
    <table border=\"0\"><tr><td>

    <table border=\"0\" width=\"640\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\"><tr><td>
    <table border=\"0\" width=\"640\" cellpadding=\"20\" cellspacing=\"1\" bgcolor=\"#ffffff\"><tr><td>
    <center>
    <img src=\"images/$site_logo\" border=\"0\" alt=\"\"><br><br>
    <font size=\"+2\">
    <b>$title</b></font><br>
    <font size=1><b>"._PDATE."</b> $datetime<br><b>"._PTOPIC."</b> $topictext</font><br><br>
    </center>
    <font size=\"2\">
    $hometext<br><br>
    $bodytext<br><br>
    $notes<br><br>
    </font>
    </td></tr></table></td></tr></table>
    <br><br><center>
    <font size=\"2\">
    "._COMESFROM." $sitename<br>
    <a href=\"$nukeurl\">$nukeurl</a><br><br>
    "._THEURL."<br>
    <a href=\"$nukeurl/article.php?sid=$sid\">$nukeurl/article.php?sid=$sid</a>
    </font>
    </td></tr></table>
    </body>
    </html>
    ";
}

PrintPage($sid);

?>