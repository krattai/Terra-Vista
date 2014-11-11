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

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$hlpfile = "manual/forumconfig.html";
$result = mysql_query("select radminforum, radminsuper from $prefix"._authors." where aid='$aid'");
list($radminforum, $radminsuper) = mysql_fetch_row($result);
if (($radminforum==1) OR ($radminsuper==1)) {

function JavaScriptAdmin() {
    echo "<SCRIPT type=\"text/javascript\">\n";
    echo "<!--\n\n";
    echo "function showsmilies() {\n";
    echo "if (!document.images)\n";
    echo "return\n";
    echo "document.images.smilies.src=\n";
    echo "'images/forum/smilies/' + document.TheAdmin.smile_url.options[document.TheAdmin.smile_url.selectedIndex].value\n";
    echo "}\n\n";
    echo "//-->\n";
    echo "</SCRIPT>\n";
}

function ForumManagerAdmin() {
    global $hlpfile, $admin, $bgcolor2, $textcolor2, $prefix;
    include ("header.php");
	JavaScriptAdmin();
    GraphicAdmin($hlpfile);
    OpenTable();
    echo"
    <center><font size=\"4\"><b>".translate("Forum Smilies")."</b></font></center>
    <form action=\"admin.php\" method=\"post\">
    <center><table border=\"1\" width=\"100%\"><tr>
	<td bgcolor=\"$bgcolor2\"><font color=\"$textcolor2\"><center><b>".translate("Code")."</b></td>
	<td bgcolor=\"$bgcolor2\"><font color=\"$textcolor2\"><center><b>".translate("Emotions")."</b></td>
	<td bgcolor=\"$bgcolor2\"><font color=\"$textcolor2\"><center><b>".translate("Icons")."</b></td>
	<td bgcolor=\"$bgcolor2\"><font color=\"$textcolor2\"><center><b>".translate("Active")."</b></td>
	<td bgcolor=\"$bgcolor2\"></td></tr>";
    $result = mysql_query("SELECT id,code,smile_url,emotion,active FROM $prefix"._smiles."");
    while(list($id,$code,$smile_url,$emotion,$active) = mysql_fetch_row($result)) {
    echo "
	<td align=\"center\">$code</td>
	<td align=\"center\">$emotion</td>
	<td align=\"center\"><IMG SRC=\"images/forum/smilies/$smile_url\"></td>";
	if (!$active=="1") {
	echo"<td align=\"center\">No</td>"; }
	else { echo"<td align=\"center\">Yes</td>"; }
	echo"<td align=center>[  <a href=\"admin.php?op=ForumSmiliesEdit&id=$id\">".translate("Edit")."</a> | <a href=\"admin.php?op=ForumSmiliesDel&id=$id&ok=0\">".translate("Delete")."</a> ]</td><tr>";
    }
    echo "</form></table>";
    echo "<br><br>
    </center><font size=\"4\"><b>".translate("Add Smilies")."</b></font></center><br><br>
    <font size=\"2\">
    <form action=\"admin.php\" name=\"TheAdmin\" method=\"post\">
    <table border=\"0\" width=\"100%\">
    <tr><td>".translate("Code:")." </td><td><input type=\"text\" name=\"code\" size=\"10\"></td></tr>
    <tr><td>".translate("Emotions:")." </td><td><input type=\"text\" name=\"emotion\" size=\"31\"></td></tr>
    <tr><td>".translate("Icon:")." </td><td><select name=\"smile_url\" onChange=\"showsmilies()\">";

		$direktori = "images/forum/smilies";
		$handle=opendir($direktori);
		while ($file = readdir($handle))
			{
			$filelist[] = $file;
		}
		asort($filelist);
		while (list ($key, $file) = each ($filelist))
		{
		ereg(".gif|.jpg",$file);
		if ($file == "." || $file == "..") $a=1;
		else {
			echo "<option value=\"$file\">$file</option>";
			}
		}

	echo"
	</select>&nbsp;&nbsp;<img src=\"images/forum/smilies/blank.gif\" name=\"smilies\">
	</td></tr>
    <tr><td>".translate("Activated:")." </td><td><input type=\"checkbox\" name=\"active\" value=\"1\"></td></tr>
    <tr><td><input type=\"submit\" value=\"".translate("Add")."\"></td></tr>
    <input type=\"hidden\" name=\"op\" value=\"ForumSmiliesAdd\">
    </form>
    </table>";
    CloseTable();
    include("footer.php");
}

function ForumSmiliesEdit($id) {
    global $hlpfile, $admin, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    $result = mysql_query("SELECT id,code,smile_url,emotion,active FROM $prefix"._smiles." WHERE id='$id'");
    list($id,$code,$smile_url,$emotion,$active) = mysql_fetch_row($result);
    OpenTable();
    echo "
    <center><font size=\"4\"><b>".translate("Edit Smilies")."</b></font></center>
    <form action=\"admin.php\" name=\"TheAdmin\" method=\"post\">
    <table border=\"0\" width=\"100%\">
    <tr><td>".translate("Code:")." </td><td><input type=\"text\" name=\"code\" size=\"4\" value=\"$code\"></td></tr>
    <tr><td>".translate("Emotions:")." </td><td><input type=\"text\" name=\"emotion\" size=\"31\" value=\"$emotion\"></td></tr>
    <tr><td>".translate("Icon:")." </td><td><select name=\"smile_url\" onChange=\"showsmilies()\">";

		$direktori = "images/forum/smilies";
		$handle=opendir($direktori);
		while ($file = readdir($handle))
			{
			$filelist[] = $file;
		}
		asort($filelist);
		while (list ($key, $file) = each ($filelist))
		{
		ereg(".gif|.jpg",$file);
		if ($file == "." || $file == "..") $a=1;
		else {
			if ($smile_url==$file) {
			echo "<option value=\"$file\" selected>$file</option>"; }
			else {
			echo "<option value=\"$file\">$file</option>"; }
			}
		}

	echo"
	</select>&nbsp;&nbsp;<img src=\"images/forum/smilies/$smile_url\" name=\"smilies\">
	</td></tr>";
	if ($active==1) {	
    echo"<tr><td>".translate("Activated:")." </td><td><input type=\"checkbox\" name=\"active\" value=\"1\" checked></td></tr>"; }
    else {
    echo"<tr><td>".translate("Activated:")." </td><td><input type=\"checkbox\" name=\"active\" value=\"1\"></td></tr>"; }
    echo"<tr><td><input type=\"submit\" value=\"".translate("Add")."\"></td></tr>
    <input type=\"hidden\" name=\"id\" value=\"$id\">
    <input type=\"hidden\" name=\"op\" value=\"ForumSmiliesSave\">
    </form>
    </table>";
    CloseTable();
    include("footer.php");
}

function ForumSmiliesSave($id,$code,$smile_url,$emotion,$active) {
	global $prefix;
    mysql_query("update $prefix"._smiles." set id='$id',code='$code',smile_url='$smile_url',emotion='$emotion',active='$active' where id='$id'");
    Header("Location: admin.php?op=ForumManager");
}

function ForumSmiliesAdd($code,$smile_url,$emotion,$active) {
	global $prefix;
    mysql_query("insert into $prefix"._smiles." values (NULL,'$code','$smile_url','$emotion','$active')");
    Header("Location: admin.php?op=ForumManager");
}

function ForumSmiliesDel($id,$ok) {
	global $prefix;
    if($ok==1) {
	mysql_query("delete from $prefix"._smiles." where id=$id");
	Header("Location: admin.php?op=ForumManager");
    } else {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><br>";
	echo "<font size=\"3\" color=\"Red\">";
	echo "<b>".translate("WARNING: Are you sure you want to delete this Smilies?")."</b><br><br><font color=\"Black\">";
    }
	echo "[ <a href=\"admin.php?op=ForumSmiliesDel&id=$id&ok=1\">".translate("Yes")."</a> | <a href=\"admin.php?op=ForumManager\">".translate("No")."</a> ]<br><br>";
	echo "</TD></TR></TABLE></TD></TR></TABLE>";
    CloseTable();
	include("footer.php");	
}

} else {
    echo "Access Denied";
}

?>