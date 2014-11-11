<?php

########################################################################
# PHP-NUKE Add-On 5.0 : Glossary AddOn
# ===========================================
#
#	Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#
#						The Teams:
#                       Hutdik Hermawan AKA hotFix (hutdik76@hotmail.com)
# 						Max Demian AKA Max (Max@Wackowoh.com)
# 						Istrigo (TheBix.com)
# 						drgbows (ecomjunk.com)
# 						Sivaprasad R.L (netlogger.net)
# 						Rob Sutton (smart.xnettech.net)
#
# http://www.nukeaddon.com
#
# 
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.       
######################################################################

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

$module_name = "Glossary";
$show_glossary = 20; # Show the number of glossary
$index = 0;

######################################################################
# Do not edit anything below this line
######################################################################

function GlossaryShow($sortby,$from) {
global $prefix,$show_glossary, $bgcolor2, $bgcolor1, $bgcolor3, $sitename, $module_name;
include("header.php");
	OpenTable();
    echo "<center><b>".translate("Welcome to")." $sitename ".translate("Glossary")."</b><br><br>\n";

$dcolor = "$bgcolor1";

	$temp = mysql_query("select * from $prefix"._glossary."");
	$row = mysql_num_rows($temp);

	
	if ($from == "") $from = 0;
	if ($row < $show_glossary) $to = $row; 
	$to = $show_glossary;	
	
	if (!$sortby) {
        $result = mysql_query("SELECT gid, gterm, gdefinition FROM $prefix"._glossary." ORDER BY gid LIMIT $from, $to"); }
	else {
		$result = mysql_query("SELECT gid, gterm, gdefinition FROM $prefix"._glossary." ORDER BY $sortby LIMIT $from, $to"); }


	echo "	
	<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">";
 	if (!$row) {
 	$row = 1;
 	}
  	$next = $row/$show_glossary;
	$modnext = $row/$show_glossary;

 	echo "<tr><td colspan=\"9\" align=\"center\"> ["; 
 	$h = 0;
 	if ($modnext !="0") $next++;
 	for ($count=1;$count<$next;$count++) {
 	if ($h==$from) { echo " $count "; }
 	else { 
 	if (!$sortby) echo " <a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=index&amp;from=$h\">$count</a> ";
 	else echo " <a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=index&sortby=$sortby&amp;from=$h\">$count</a> ";
  	}
 	$h=$h + $show_glossary;
 	}
 	echo " ]</td></tr>
 	<tr><td colspan=\"9\">&nbsp;</td></tr>
	<TR bgcolor=\"$bgcolor2\">
		<td width=\"15%\" height=\"25\" nowrap><B><a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=index&amp;sortby=gterm\"><b><font face=\"Verdana\" size=\"2\" color=\"white\">Terms</font></B></a></TD>
		<td width=\"90%\" height=\"25\"><a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=index&amp;sortby=gdefinition\"><b><font face=\"Verdana\" size=\"2\" color=\"white\">Definitions</font></B></a></TD>
	</TR>";

 	while ($glossaryout = mysql_fetch_array($result)) {

if ($dcolor == "$bgcolor1") {
	$dcolor="$bgcolor1"; 
	} else {
	$dcolor="$bgcolor3"; 
	}
	
	echo "
	<TR bgcolor=\"$dcolor\" valign=\"top\">
		<td width=\"15%\" height=\"25\" nowrap><font face=\"Verdana\" size=\"2\">".$glossaryout[gterm]."</TD>
		<td width=\"90%\" height=\"25\"><font face=\"Verdana\" size=\"2\">".$glossaryout[gdefinition]."</TD>";
	echo"</TR>";

if ($dcolor == "$bgcolor1" ) {
   $dcolor = "$bgcolor3";
    }
    else if ($dcolor == "$bgcolor3") {
    $dcolor = "$bgcolor1";

    }
 	
 	
 	}
 	
 	$h=0;
 	echo "
 	<tr><td colspan=\"9\">&nbsp;</td></tr>
 	<tr><td colspan=\"9\" align=\"center\">[ "; 
 	for ($count=1;$count<$next;$count++) {
 	if ($h==$from) { echo " $count "; }
 	else { 
 	if (!$sortby) echo " <a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=index&amp;from=$h\">$count</a> ";
 	else echo " <a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=index&amp;sortby=$sortby&amp;from=$h\">$count</a> ";
  	} 	$h=$h + $show_glossary;

 	}
 	
 	echo " ]</td></tr></table>";
?>


<?php
CloseTable();
include("footer.php");
}

# End AddOn Modules

switch($func) {

    default:
    GlossaryShow($sortby,$from);
    break;
}

?>