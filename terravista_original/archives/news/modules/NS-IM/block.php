<?

########################################################################
# PHP-NUKE Add-On 5.0 : Instant Messaging Block AddOn
# ===================================================
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

$module_name = "NS-IM";
$active = 1; #  1 = Turn On and 0 = Turn Off the Instant Messaging
global $user, $index;


if ($index) {
if (!$active) {
?>

<table cellpadding="0" cellspacing="0" border="0">
<tr><td><font size="2" face="Arial">Instant Messaging is now disable</font></td></tr>
</table>

<?
}
else {
	# Java Scripts for IM #
	#######################
    if ($user) {
	    echo "<script type=\"text/javascript\">\n";
	    echo "<!--\n\n";
	    echo "window.open('im.php?action=Check+IM', 'legend', 'toolbars=0, scrollbars=1, location=0, statusbars=0, menubars=0, resizable=0, width=200, height=350')\n";
	    echo "//-->\n";
	    echo "</script>\n";
    }
?>

<table cellpadding="0" cellspacing="0" border="0">
<tr><td><font size="2" face="Arial">Instant Messaging is now activated</font></td></tr>
</table>

<?
}
}
?> 


