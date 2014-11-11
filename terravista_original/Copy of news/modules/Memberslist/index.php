<?php

########################################################################
# PHP-NUKE Add-On 5.0 : Memberslist AddOn
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

$module_name = "Memberslist";
$show_user = 20; # Show number of memberlist
$index = 0;

######################################################################
# Do not edit anything below this line
######################################################################


function AddMemberlist($sortby,$from,$sortorder) {
global $admin,$prefix,$show_user,$bgcolor1,$bgcolor2,$bgcolor3,$textcolor1,$textcolor2,$module_name;
include("header.php");
OpenTable();
    $result = mysql_query("select uname FROM $prefix"._users." WHERE uid!='1' order by uid DESC limit 0,1");
    list($lastuser) = mysql_fetch_row($result);
    echo "<div align=\"center\">Greetings to our last registered user: <a href=\"user.php?op=userinfo&uname=$lastuser\">$lastuser</a><br>";
    $result = mysql_query("select * FROM $prefix"._users." WHERE uid!='1' order by uid");
    $numrows = mysql_num_rows($result);
    echo "We have <b>$numrows</b> registered users</div><br><br>";
?>

<?php

$dcolor = "$bgcolor1";


	$temp = mysql_query("select * FROM $prefix"._users."");
	$row = mysql_num_rows($temp);

	
	if ($from == "") $from = 0;
	if ($row < $show_user) $to = $row; 
	$to = $show_user;	
	
	if (!$sortby) {
        $result = mysql_query("SELECT uid, name, uname, femail, url, user_regdate, user_icq, user_from, user_aim, user_yim, user_msnm FROM $prefix"._users." WHERE uid!='1' ORDER BY uid LIMIT $from, $to"); }
	else {
		$result = mysql_query("SELECT uid, name, uname, femail, url, user_regdate, user_icq, user_from, user_aim, user_yim, user_msnm FROM $prefix"._users." WHERE uid!='1' ORDER BY $sortby $sortorder LIMIT $from, $to"); }


	
	echo "	
	<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"2\">";
 	
 	$next = $row/$show_user;
	$modnext = $row/$show_user;

 	echo "<tr><td colspan=\"9\" align=\"center\"> ["; 
 	$h = 0;
 	if ($modnext !="0") $next++;
 	for ($count=1;$count<$next;$count++) {
 	if ($h==$from) { echo " $count "; }
 	else { 
 	echo " <a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=$sortby&sortorder=$sortorder&from=$h\">$count</a> ";
   	}
 	$h=$h + $show_user;
 	}
 	echo " ]</td></tr>
 	<tr><td colspan=9>&nbsp;</td></tr>
	<TR bgcolor=$bgcolor2>";
		if ($sortby=="uname") {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=uname\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><font face=\"Verdana\" size=\"2\" color=\"white\">User Name</font></B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=uname&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		} else {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=uname\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=uname\"><font face=\"Verdana\" size=\"2\" color=\"white\">User Name</font></B></a><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=uname&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		}
		if ($sortby=="user_from") {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_from\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><font face=\"Verdana\" size=\"2\" color=\"white\">User From</font></B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_from&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		} else {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_from\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_from\"><font face=\"Verdana\" size=\"2\" color=\"white\">User From</font></B></a><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_from&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		}
		if ($sortby=="user_regdate") {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_regdate\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><font face=\"Verdana\" size=\"2\" color=\"white\">Date Joined</font></B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_regdate&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		} else {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_regdate\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_regdate\"><font face=\"Verdana\" size=\"2\" color=\"white\">Date Joined</font></B></a><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_regdate&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		}
		if ($sortby=="femail") {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=femail\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><font face=\"Verdana\" size=\"2\" color=\"white\">E-mail</font></B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=femail&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		} else {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=femail\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=femail\"><font face=\"Verdana\" size=\"2\" color=\"white\">E-mail</font></B></a><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=femail&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		}
		if ($sortby=="url") {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=url\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><font face=\"Verdana\" size=\"2\" color=\"white\">URL</font></B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=url&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		} else {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=url\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=url\"><font face=\"Verdana\" size=\"2\" color=\"white\">URL</font></B></a><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=url&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		}
		if ($sortby=="user_icq") {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_icq\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><font face=\"Verdana\" size=\"2\" color=\"white\">ICQ</font></B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_icq&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		} else {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_icq\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_icq\"><font face=\"Verdana\" size=\"2\" color=\"white\">ICQ</font></B></a><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_icq&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		}
		if ($sortby=="user_aim") {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_aim\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><font face=\"Verdana\" size=\"2\" color=\"white\">AIM</font></B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_aim&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		} else {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_aim\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_aim\"><font face=\"Verdana\" size=\"2\" color=\"white\">AIM</font></B></a><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_aim&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		}
		if ($sortby=="user_yim") {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_yim\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><font face=\"Verdana\" size=\"2\" color=\"white\">YIM</font></B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_yim&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		} else {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_yim\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_yim\"><font face=\"Verdana\" size=\"2\" color=\"white\">YIM</font></B></a><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_yim&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		}
		if ($sortby=="user_msnm") {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_msnm\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><font face=\"Verdana\" size=\"2\" color=\"white\">MSNM</font></B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_msnm&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		} else {
		echo "<td width=\"25%\" height=\"25\" nowrap><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_msnm\"><img src=\"images/download/up.gif\" alt=\"Sort Ascending\" title=\"Sort Ascending\" border=\"0\" height=\"9\" width=\"15\"></a><B><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_msnm\"><font face=\"Verdana\" size=\"2\" color=\"white\">MSNM</font></B></a><a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=user_msnm&sortorder=DESC\"><img src=\"images/download/down.gif\" title=\"".translate("Sort Descending")."\" alt=\"Sort Descending\" border=\"0\" height=\"9\" width=\"15\"></TD>";
		}
        if($admin) {
            echo "<td><font size=\"2\" color=\"$textcolor1\"><b>".translate("Admin")."</b></font></td>\n";
        }
	echo"</TR>";

 	while  ($userinfo = mysql_fetch_array($result)) {

if ($dcolor == "$bgcolor1") {
	$dcolor="$bgcolor1"; 
	} else {
	$dcolor="$bgcolor3"; 
	}
	
	echo "
	<TR bgcolor=\"$dcolor\">
		<td width=\"25%\" height=\"25\" nowrap><font face=\"Verdana\" size=\"2\">".$userinfo[uname]."</TD>
		<td width=\"31%\" height=\"25\"><font face=\"Verdana\" size=\"2\">".$userinfo[user_from]."</TD>
		<td width=\"9%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\">".$userinfo[user_regdate]."</TD>";
        if (!$userinfo[femail]) {
        	echo"
		<td width=\"9%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\">&nbsp;</TD>"; }
		else {
		echo "
		<td width=\"9%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\"><a href=\"mailto:".$userinfo[femail]."\"><img src=\"images/forum/email.gif\" border=\"0\"></a></TD>"; }

        if (!$userinfo[url]) {
        	echo"
        	<td width=\"7%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\">&nbsp;</TD>"; }
        	else {
        	echo "
        	<td width=\"7%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\"><a href=\"".$userinfo[url]."\"><img src=\"images/forum/www_icon.gif\" border=\"0\"></a></TD>"; }

        if (!$userinfo[user_icq]) {
        	echo"
        	<td width=\"7%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\">&nbsp;</TD>"; }
        	else {
        	echo"
        	<td width=\"7%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\"><a href=\"http://www.mirabilis.com/".$userinfo[user_icq]."\"><img src=\"http://wwp.icq.com/scripts/online.dll?icq=".$userinfo[user_icq]."&img=5\" border=\"0\" width=\"18\" height=\"18\" alt=\"Click here to send ".$userinfo[uname]." an ICQ message.\"></a></TD>"; }

        if (!$userinfo[user_aim]) {
        	echo"
        	<td width=\"7%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\">&nbsp;</TD>"; }
        	else {
        	echo"
 		<td width=\"7%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\"><a href=\"aim:goim?screenname=".$userinfo[user_aim]."&message=Hi+".$userinfo[user_aim].".+Are+you+there?\"><img src=\"images/forum/aim.gif\" border=\"0\" alt=\"Visit ".$userinfo[uname]."'s AIM Web Site\"></a></TD>"; }

        if (!$userinfo[user_yim]) {
        	echo"
        	<td width=\"6%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\">&nbsp;</TD>"; }
        	else {
        	echo"
 		<td width=\"6%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\"><a href=\"http://edit.yahoo.com/config/send_webmesg?.target=".$userinfo[user_yim]."&.src=pg\"><img src=\"images/forum/yim.gif\" border=\"0\" alt=\"Visit ".$userinfo[uname]."'s YIM Web Site\"></a></TD>"; }


        if (!$userinfo[user_msnm]) {
        	echo"
        	<td width=\"7%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\">&nbsp;</TD>"; }
        	else {
        	echo"
 		<td width=\"7%\" height=\"25\" nowrap align=\"center\"><font face=\"Verdana\" size=\"2\"><img src=\"images/forum/msnm.gif\" border=\"0\" alt=\"User ".$userinfo[user_msnm]."\"></a></TD>"; }

        if($admin) {
            echo "<td><font size=\"2\" color=\"#000000\">[ <A HREF=\"admin.php?chng_uid=$userinfo[uid]&op=modifyUser\"><font color=\"#000000\">".translate("Edit")."</font></a><font size=\"1\" color=\"#000000\"> | </font>\n";
            echo "<A HREF=\"admin.php?op=delUser&chng_uid=$userinfo[uid]\"><font color=\"#000000\">".translate("Delete")."</font></a> ]</font></td>\n";
        }

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
 	echo " <a href=\"modules.php?op=modload&name=$module_name&file=index&sortby=$sortby&sortorder=$sortorder&from=$h\">$count</a> ";
  	} 	$h=$h + $show_user;

 	}
 	
 	echo " ]</td></tr></table><hr>";
?>


<?php
CloseTable();
include("footer.php");
}

switch($func) {
        default:
                AddMemberlist($sortby,$from,$sortorder);
                break;
}

?>