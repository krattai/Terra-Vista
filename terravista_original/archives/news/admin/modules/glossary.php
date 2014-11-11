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
# PHP-NUKE Add-On 5.0.RC1 : Glossary AddOn
# =========================================
#
#	Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji (rtirtadji@hotmail.com)
#
# http://www.nukeaddon.com
#
######################################################################


if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$hlpfile = "manual/glossary.html";
$result = mysql_query("select radminsuper from $prefix"._authors." where aid='$aid'");
list($radminsuper) = mysql_fetch_row($result);
if ($radminsuper==1) {

/*********************************************************/
/* Faq Admin Function                                    */
/*********************************************************/

function GlossaryAdmin() {
    global $hlpfile, $admin, $bgcolor2, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "
    <center><font size=\"4\"><b>".translate("Glossary")."</b></font></center>
    <form action=\"admin.php\" method=\"post\">
    <center><table border=\"1\" width=\"100%\"><tr>
	<td bgcolor=\"$bgcolor2\"><font color=\"#ffffff\"><center>".translate("ID")."</td>
	<td bgcolor=\"$bgcolor2\"><font color=\"#ffffff\"><center>".translate("Terms")."</td>
	<td bgcolor=\"$bgcolor2\"><font color=\"#ffffff\"><center>".translate("Definitions")."</td>
	<td>&nbsp</td></tr>";

    $result = mysql_query("select gid, gterm, gdefinition from $prefix"._glossary." order by gid");
    while(list($gid, $gterm, $gdefinition) = mysql_fetch_row($result)) {
	echo "
	<td bgcolor=\"$bgcolor2\" align=\"center\"><font color=\"#ffffff\">$gid</td>
	<td bgcolor=\"$bgcolor2\" align=\"center\"><font color=\"#ffffff\">$gterm</td>
	<td bgcolor=\"$bgcolor2\" align=\"center\"><font color=\"#ffffff\">$gdefinition</td>
	<td bgcolor=\"$bgcolor2\" align=center><font color=\"#ffffff\"><a href=\"admin.php?op=GlossaryEdit&gid=$gid\">".translate("Edit")."</a> | <a href=\"admin.php?op=GlossaryDel&gid=$gid&ok=0\">".translate("Delete")."</a></td><tr>";
    }
    echo "</form></td></tr></table>
    <br><br>
    <center><font size=\"4\"><b>".translate("Add New Glossary")."</b></font></center><br><br>
    <font size=\"2\">
    <form action=\"admin.php\" method=\"post\">
    <table border=\"0\" width=\"100%\">
    <tr><td>".translate("Term:")." </td><td><input type=\"text\" name=\"gterm\" size=\"30\"></td></tr>
    <tr valign=\"top\"><td>".translate("Definition:")." </td><td><textarea name=\"gdefinition\" cols=\"60\" rows=\"5\"></textarea></td></tr>
    </table>
    <input type=\"hidden\" name=\"op\" value=\"GlossaryAdd\">
    <input type=\"submit\" value=\"".translate("Add")."\">
    </form>
    </td></tr></table></td></tr></table>";
    include("footer.php");
}

function GlossaryEdit($gid) {
    global $hlpfile, $admin, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    $result = mysql_query("select gterm, gdefinition from $prefix"._glossary." where gid='$gid'");
    list($gterm,$gdefinition) = mysql_fetch_row($result);
    OpenTable();
    echo "
    <center><font size=\"4\"><b>".translate("Edit Glossary")."</b></font></center>
    <form action=\"admin.php\" method=\"post\">
    <input type=\"hidden\" name=\"gid\" value=\"$gid\">
    <table border=\"0\" width=\"100%\">
    <tr><td>".translate("Terms:")." </td><td><input type=\"text\" name=\"gterm\" size=\"30\" value=\"$gterm\"></td></tr>
    <tr><td>".translate("Definitions:")." </td><td><textarea name=\"gdefinition\" cols=\"60\" rows=\"5\">$gdefinition</textarea></td></tr>
    </table>
    <input type=\"hidden\" name=\"op\" value=\"GlossarySave\">
    <input type=\"submit\" value=\"".translate("Save Changes")."\">
    </form>
    </td></tr></table></td></tr></table>";
    include("footer.php");
}

function GlossarySave($gid, $gterm, $gdefinition) {
	global $prefix;
    $gterm = stripslashes(FixQuotes($gterm));
    mysql_query("update $prefix"._glossary." set gterm='$gterm',gdefinition='$gdefinition' where gid='$gid'");
    Header("Location: admin.php?op=GlossaryAdmin");
}

function GlossaryAdd($gterm, $gdefinition) {
	global $prefix;
    $categories = stripslashes(FixQuotes($gterm));
    mysql_query("insert into $prefix"._glossary." values (NULL, '$gterm', '$gdefinition')");
    Header("Location: admin.php?op=GlossaryAdmin");
}

function GlossaryDel($gid, $ok=0) {
	global $prefix;
    if($ok==1) {
	mysql_query("delete from $prefix"._glossary." where gid=$gid");
	Header("Location: admin.php?op=GlossaryAdmin");
    } else {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><br>";
	echo "<font size=\"3\" color=\"Red\">";
	echo "<b>".translate("WARNING: Are you sure you want to delete this Terms?")."</b><br><br><font color=\"Black\">";
    }
	echo "[ <a href=\"admin.php?op=GlossaryDel&gid=$gid&ok=1\">".translate("Yes")."</a> | <a href=\"admin.php?op=GlossaryAdmin\">".translate("No")."</a> ]<br><br>";
	echo "</TD></TR></TABLE></TD></TR></TABLE>";
	include("footer.php");	
}

} else {
    echo "Access Denied";
}

?>