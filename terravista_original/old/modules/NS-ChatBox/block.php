<?

########################################################################
# PHP-NUKE Add-On 5.0 : ChatBox Block AddOn
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

$module_name = "NS-ChatBox";
?>

<script type="text/javascript">
<!--
function openchatBOX() {
    window.open ("modules.php?op=modload&name=<? echo "$module_name"; ?>&file=index","ChatBox","toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=500,height=450");
}
//-->
</script>   

<table cellpadding="0" cellspacing="0" border="0">
<tr><td><font size="2" face="Arial">
Here you can ask your friends to chat live as they when online<br>
<strong><big>&middot;</big></strong> <a href="javascript:openchatBOX();">Chat Box</a>
</font></td></tr>
</table>



