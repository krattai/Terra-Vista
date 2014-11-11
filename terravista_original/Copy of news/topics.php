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

if (!IsSet($mainfile)) { include ('mainfile.php'); }

include("header.php");
$result = mysql_query("select topicid, topicname, topicimage, topictext from $prefix"._topics." order by topicname");
if (mysql_num_rows($result)==0) {
    include("header.php");
    include("footer.php");
}
if (mysql_num_rows($result)>0) {
    OpenTable();
    echo "<center><font size=\"3\"><b>"._ACTIVETOPICS."</b></font><br>\n"
	."<font size=\"2\">"._CLICK2LIST."</font></center><br>\n"
	."<table border=\"0\" width=\"100%\" align=\"center\" cellpadding=\"2\"><tr>\n";
    while(list($topicid, $topicname, $topicimage, $topictext) = mysql_fetch_array($result)) {
    	if ($count == 5) {
	    echo "<tr>\n";
	    $count = 0;
	}
	echo "<td align=\"center\">\n"
	    ."<a href=\"search.php?query=&amp;topic=$topicid\"><img src=\"$tipath$topicimage\" border=\"0\" alt=\"$topictext\"></a><br>\n"
	    ."<font size=\"2\"><b>$topictext</b></font>\n"
	    ."</td>\n";
	/* Thanks to John Hoffmann from softlinux.org for the next 5 lines ;) */
	$count++;
	if ($count == 5) {
	    echo "</tr>\n";
	}
    }
    if ($count == 5) {
	echo "</table>\n";
    } else {
	echo "</tr></table>\n";
    }
} 
CloseTable();
mysql_free_result($result);
include("footer.php");

?>