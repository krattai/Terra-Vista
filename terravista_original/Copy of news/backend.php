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

include("mainfile.php");
header("Content-Type: text/xml");
$result = mysql_query("SELECT sid, title FROM $prefix"._stories." ORDER BY sid DESC limit 10");
    if (!result) {
	echo "An error occured";
    } else {
        echo "<?xml version=\"1.0\" encoding=\"ISO-8859-1\"?>\n\n";
	echo "<!DOCTYPE rss PUBLIC \"-//Netscape Communications//DTD RSS 0.91//EN\"\n";
	echo " \"http://my.netscape.com/publish/formats/rss-0.91.dtd\">\n\n";
        echo "<rss version=\"0.91\">\n\n";
	echo "<channel>\n";
        echo "<title>$sitename</title>\n";
        echo "<link>$nukeurl</link>\n";
        echo "<description>$backend_title</description>\n";
	echo "<language>$backend_language</language>\n\n";
        for ($m=0; $m < mysql_num_rows($result); $m++) {
            list($sid, $title) = mysql_fetch_row($result);
            echo "<item>\n";
            echo "<title>$title</title>\n";
            echo "<link>$nukeurl/article.php?sid=$sid</link>\n";
            echo "</item>\n\n";
        }
	echo "</channel>\n";
        echo "</rss>";
    }

?>