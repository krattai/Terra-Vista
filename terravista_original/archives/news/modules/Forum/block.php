<?

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
# PHP-NUKE Add-On 5.0 : PHPBB Forum AddOn
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

$module_name = "Forum";
global $prefix;

$sql="SELECT topic_id, topic_title, forum_id FROM $prefix"._forumtopics." ORDER BY topic_time desc LIMIT 5"; 
$result=mysql_query($sql); 

echo "<table cellpadding=\"0\" cellspacing=\"0\">"; 
echo "<tr valign=\"top\"><td>";

while ($mytopic=mysql_fetch_array($result)) 
{ 

$latesttopic=$mytopic[topic_title];

echo "<font size=\"2\"><strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=viewtopic&amp;topic=$mytopic[topic_id]&amp;forum=$mytopic[forum_id]\">";

$count=0;
while ($latesttopic[$count]) {
	if ($count < 15) {
		echo "$latesttopic[$count]";
	} 
	elseif ($count == 15) { 
		echo "...";
	}
$count++;
}

echo "</a></font><br>"; 

} 

echo "</td></tr>";
echo "</table>"; 

?>