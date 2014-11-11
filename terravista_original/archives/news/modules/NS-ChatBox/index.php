<?php

########################################################################
# PHP-NUKE Add-On 5.0 : ChatBox AddOn
# =========================================
#
#	Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#						Istrigo (TheBix.com)
#
# http://www.nukeaddon.com
# http://www.theBix.com
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.       
######################################################################

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

$index = 0;

function printPage() {
	include("config.php");
	echo "<link rel=\"stylesheet\" href=\"chatbox.css\">";
	print("
		<html>
		<head><title>".$sitename."'s chat box</title></head>
		<frameset rows=\"85%,*\">
			<frame src=\"modules.php?op=modload&name=NS-ChatBox&file=chattop\" frameborder=\"0\" noresize scrolling=\"no\">
			<frame src=\"modules.php?op=modload&name=NS-ChatBox&file=chatinput\" frameborder=\"0\" noresize scrolling=\"no\">
		</frameset>
		</html>
		");
}

switch($func) {
	default:
	printPage();	
	break;
}

?>