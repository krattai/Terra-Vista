<?php

######################################################################
# PHP-NUKE: Web Portal System
# ===========================
#
# Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)
# http://phpnuke.org
#
# This modules is the main administration part
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################
# PHP-NUKE Add-On 5.0.RC1 : PHPBB Forum AddOn
# ============================================
# 
# Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#						Hutdik Hermawan AKA hotFix (hutdik76@hotmail.com)
#
# http://www.nukeaddon.com
#
#
#######################################################################


/* Make a database connection */

if(!$db = @mysql_connect("$dbhost", "$dbuname", "$dbpass")) {
    die("<font size=\"+1\">An Error Occured</font><hr>phpBB was unable to connect to the database. <BR>Please check $dbhost, $dbuser, and $dbpasswd in config.php.");
}

if(!@mysql_select_db("$dbname",$db)) {
    die("<font size=\"+1\">An Error Occured</font><hr>phpBB was unable to find the database <b>$dbname</b> on your MySQL server. <br>Please make sure you ran the phpBB installation script.");
}

/* Code for the LastVisit cookie */

if(isset($HTTP_COOKIE_VARS["LastVisit"])) {
    $userdata["lastvisit"] = $HTTP_COOKIE_VARS["LastVisit"];
} else {
    $value = date("Y-m-d H:i");
    /* one year 'til expiry */
    $time = (time() + 3600 * 24 * 7 * 52);
    setcookie("LastVisit", $value, $time);
}

list($day, $time) = split(" ", $userdata[lastvisit]);
list($hour, $min) = split(":", $time);
$this_min = date("i");
$this_hour = date("H");
$this_day = date("Y-m-d");

/* Only set the last visit cookie if 10 mins have gone by, or its the next day or hour or something... */
/* This is kinda ugly but it works :) */
if( ($this_day > $day) || ($this_hour > $hour) || (($this_min - 10) > $min) ) {
    $value = date("Y-m-d H:i");
    $time = (time() + 3600 * 24 * 7 * 52);
    setcookie("LastVisit", $value, $time);
}

$sql = "SELECT * FROM $prefix"._config."";

if($result = mysql_query($sql, $db)) {
	if($myrow = mysql_fetch_array($result)) {
		$allow_html = $myrow["allow_html"]; 
		$allow_bbcode = $myrow["allow_bbcode"]; 
		$allow_sig = $myrow["allow_sig"]; 
		$posts_per_page = $myrow["posts_per_page"];
		$hot_threshold = $myrow["hot_threshold"];
		$topics_per_page = $myrow["topics_per_page"];
	}
}

?>