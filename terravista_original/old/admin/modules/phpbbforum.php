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
$hlpfile = "manual/forumcat.html";
$result = mysql_query("select radminforum, radminsuper from $prefix"._authors." where aid='$aid'");
list($radminforum, $radminsuper) = mysql_fetch_row($result);
if (($radminforum==1) OR ($radminsuper==1)) {

/*********************************************************/
/* PHPBB Forum Admin Function                            */
/*********************************************************/

function ForumAdmin() {
    global $hlpfile, $admin, $bgcolor2, $bgcolor4, $textcolor2, $prefix;
    include ("config.php");
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "
    <center><font size=4><b>".translate("Forum Categories")."</b></font></center>
    <form action=admin.php method=post>
    <center><table border=1 width=100%><tr>
	<td bgcolor=$bgcolor2><font color=$textcolor2><center><b>".translate("ID")."</b></td>
	<td bgcolor=$bgcolor2><font color=$textcolor2><center><b>".translate("Categories")."</b></td>
	<td bgcolor=$bgcolor2><font color=$textcolor2><center><b>".translate("No. of Forum(s)")."</b></td>
	<td bgcolor=$bgcolor2><font color=$textcolor2><center><b>".translate("Functions")."</b></td></tr>";

    $result = mysql_query("select cat_id, cat_title from $prefix"._catagories." order by cat_id");
    while(list($cat_id, $cat_title) = mysql_fetch_row($result)) {

    $gets = mysql_query("select count(*) as total from $prefix"._forums." where cat_id=$cat_id");
	$numbers= mysql_fetch_array($gets);
    	echo "
	<td align=center>$cat_id</td>
	<td align=center>$cat_title</td>
	<td align=center>$numbers[total]</td>
	<td align=center>[ <a href=\"admin.php?op=ForumGo&cat_id=$cat_id&ctg=$cat_title\">Edit Forum(s)</a> | <a href=admin.php?op=ForumCatEdit&cat_id=$cat_id>".translate("Edit")."</a> | <a href=admin.php?op=ForumCatDel&cat_id=$cat_id&ok=0>".translate("Delete")."</a> ]</td><tr>";
    }
    echo "</form></td></tr></table>
    <br><br>
    </center><font size=4><b>".translate("Add Categories")."</b><br><br>
    <font size=2>
    <form action=admin.php method=post>
    <table border=0 width=100%><tr><td>
    ".translate("Categories:")." </td><td><input type=text name=catagories size=31></td></tr><tr><td>
    </td></tr></table>
    <input type=hidden name=op value=ForumCatAdd>
    <input type=submit value=".translate("Add").">
    </form>
    </td></tr></table></td></tr></table>";
    include("footer.php");
}

function ForumGo($cat_id,$ctg) {
    global $hlpfile, $admin, $bgcolor2, $textcolor2, $prefix;
    include ("config.php");
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "
    <center><font size=4><b>".translate("Forum listed at")." $ctg</b></font></center>
    <form action=admin.php method=post>
    <center><table border=1 width=100%><tr>
	<td bgcolor=$bgcolor2><font color=$textcolor2><center><b>".translate("Forum Name")."</b></td>
	<td bgcolor=$bgcolor2><font color=$textcolor2><center><b>".translate("Description")."</b></td>
	<td bgcolor=$bgcolor2><font color=$textcolor2><center><b>".translate("Moderator")."</b></td>
	<td bgcolor=$bgcolor2><font color=$textcolor2><center><b>".translate("Access")."</b></td>	
	<td bgcolor=$bgcolor2><font color=$textcolor2><center><b>".translate("Type")."</b></td>
	<td bgcolor=$bgcolor2><font color=$textcolor2><center><b>".translate("Functions")."</b></td></tr>";

    $result = mysql_query("select forum_id, forum_name, forum_desc, forum_access, forum_moderator, forum_type from $prefix"._forums." where cat_id='$cat_id'");
    while(list($forum_id, $forum_name, $forum_desc, $forum_access, $forum_moderator, $forum_type) = mysql_fetch_row($result)) {

	$userdata = mysql_query("select uname from $prefix"._users." where uid='$forum_moderator'");
    	list($uname) = mysql_fetch_row($userdata);
	echo "
	<tr>
	<td align=center>$forum_name</td>
	<td align=center>$forum_desc</td>
	<td align=center>$uname</td>";
	switch($forum_access) {
	case (0):
	echo "<td align=center>Anonymous Posting</td>";
	break;
	case (1):
	echo "<td align=center>Registered User</td>";
	break;
	case (2):
	echo "<td align=center>Moderators/Administrator</td>";
	break;
	}
	if ($forum_type==0) {
	echo "<td align=center>Public</td>";
	}
	else {
	echo "<td align=center>Private</td>";
	}
	echo "
	<td align=center><a href=admin.php?op=ForumGoEdit&forum_id=$forum_id>".translate("Edit")."</a> | <a href=admin.php?op=ForumGoDel&forum_id=$forum_id&ok=0>".translate("Delete")."</a></td></tr>";
    }
    echo "</form></td></tr></table>
    <br><br>
    </center><font size=4><b>".translate("Add More Forum for")." $ctg</b><br><br>
    <font size=2>
    <form action=admin.php method=post>
    <table border=0 width=100%>
    <tr><td>".translate("Forum Name:")." </td><td><input type=text name=forum_name size=31></td></tr>
    <tr><td>".translate("Description:")." </td><td><textarea name=forum_desc cols=60 rows=5></textarea></td></tr>
    <tr><td> ".translate("Moderator:")." </td>

    <td><input type=\"text\" name=\"forum_mod\">
    </td></tr>";
    echo "<tr><td>".translate("Access Level:")." </td>
    <td><SELECT NAME=forum_access>
		<OPTION VALUE=0>Anonymous Posting</OPTION>
		<OPTION VALUE=1>Registered users only</OPTION>
		<OPTION VALUE=2>Moderators/Administrators only</OPTION>
	</SELECT>
    </td></tr>
    <tr><td>".translate("Type:")." </td>
    <td><SELECT NAME=forum_type>
	        <OPTION VALUE=0>Public</OPTION>
	        <OPTION VALUE=1>Private</OPTION>
        </SELECT>
    </td></tr>
    <tr><td>".translate("Password (if private):")." </td><td><input type=text name=forum_pass size=31></td></tr>
    </table>
    <input type=hidden name=cat_id value=\"$cat_id\">
    <input type=hidden name=op value=ForumGoAdd>
    <input type=submit value=".translate("Add").">
    </form>
    </td></tr></table></td></tr></table>";
    include("footer.php");
}

function ForumGoEdit($forum_id) {
    global $hlpfile, $admin, $prefix;
    include ("config.php");
    include ("header.php");
    GraphicAdmin($hlpfile);
    $result = mysql_query("select forum_id, forum_name, forum_desc, forum_access, forum_moderator, cat_id, forum_type from $prefix"._forums." where forum_id='$forum_id'");
    list($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id_1, $forum_type) = mysql_fetch_row($result);
    OpenTable();
    echo "
    <center><font size=4><b>".translate("Edit")." $forum_name</b></font></center>
    <form action=admin.php method=post>
    <input type=hidden name=forum_id value=$forum_id>
    <table border=0 width=100%><tr><td>
    <tr><td>".translate("Forum Name:")." </td><td><input type=text name=forum_name size=31 value=\"$forum_name\"></td></tr>
    <tr><td>".translate("Description:")." </td><td><textarea name=forum_desc cols=60 rows=5>$forum_desc</textarea></td></tr>
    <tr><td> ".translate("Moderator:")." </td>";
    $result = mysql_query("select uname from $prefix"._users." where uid='$forum_mod'");
    list($uname) = mysql_fetch_row($result);
    echo "<td><input type=\"text\" name=\"forum_mod\" value=\"$uname\">";
    echo "</td><tr>
    <tr><td>".translate("Access Level:")." </td>
    <td><SELECT NAME=forum_access>";
    if ($forum_access == 0) {
        echo "<OPTION VALUE=0 selected>Anonymous Posting</OPTION>"; }
    if ($forum_access == 1) {
	echo "<OPTION VALUE=1 selected>Registered users only</OPTION>"; }
    if ($forum_access == 2) {
	echo "<OPTION VALUE=2 selected>Moderators/Administrators only</OPTION>";
    }
	echo"
        <OPTION VALUE=0>Anonymous Posting</OPTION>
	<OPTION VALUE=1>Registered users only</OPTION>
	<OPTION VALUE=2>Moderators/Administrators only</OPTION>
	</SELECT>
    </td></tr>
    <tr><td>".translate("Change Categories:")." </td>
    <td><SELECT NAME=cat_id>";
    $result = mysql_query("select cat_id, cat_title from $prefix"._catagories."");
    while(list($cat_id, $cat_title) = mysql_fetch_row($result)) {
	if ($cat_id == $cat_id_1) {
    echo "<OPTION VALUE=\"$cat_id\" selected>$cat_title</OPTION>";
    } else {
    echo "<OPTION VALUE=\"$cat_id\">$cat_title</OPTION>";
    }
    }
    echo "
    </SELECT>
    </td></tr>
    <tr><td>".translate("Type:")." </td>
    <td><SELECT NAME=forum_type>";
    if ($forum_type == 0) {
        echo "<OPTION VALUE=0 selected>Public</OPTION>"; }
    if ($forum_type == 1) {
	echo "<OPTION VALUE=1 selected>Private</OPTION>"; }
	echo "
	        <OPTION VALUE=0>Public</OPTION>
	        <OPTION VALUE=1>Private</OPTION>
        </SELECT>
    </td></tr>
    <tr><td>".translate("Password (if private):")." </td><td><input type=text name=forum_pass size=31></td></tr>
    </table>
    <input type=hidden name=op value=ForumGoSave>
    <input type=submit value=".translate("Save Changes").">
    </form>

    </td></tr></table></td></tr></table>";
    include("footer.php");
}

function ForumCatEdit($cat_id) {
    global $hlpfile, $admin, $prefix;
    include ("config.php");
    include ("header.php");
    GraphicAdmin($hlpfile);
    $result = mysql_query("select cat_id, cat_title from $prefix"._catagories." where cat_id='$cat_id'");
    list($cat_id, $cat_title) = mysql_fetch_row($result);
    OpenTable();
    echo "
    <center><font size=4><b>".translate("Edit Categories")."</b></font></center>
    <form action=admin.php method=post>
    <input type=hidden name=cat_id value=$cat_id>
    <table border=0 width=100%><tr><td>
    ".translate("Categories:")." </td><td><input type=text name=cat_title size=31 value=\"$cat_title\"></td></tr><tr><td>
    </td></tr></table>
    <input type=hidden name=op value=ForumCatSave>
    <input type=submit value=".translate("Save Changes").">
    </form>
    </td></tr></table></td></tr></table>";
    include("footer.php");
}


function ForumCatSave($cat_id, $cat_title) {
	global $prefix;
    mysql_query("update $prefix"._catagories." set cat_title='$cat_title' where cat_id='$cat_id'");
    Header("Location: admin.php?op=ForumAdmin");
}

function ForumGoSave($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass) {
	global $prefix;
    $result = mysql_query("select uid from $prefix"._users." where uname='$forum_mod'");
    list($forum_moderator) = mysql_fetch_row($result);
    if ($forum_moderator == "") {
	include ("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center>The select Moderator doesn't exist in the database.<br>";
	echo "Please check and edit the Moderator's name.<br><br>";
	echo "[ <A HREF=javascript:history.go(-1)>".translate("Go Back")."</A> ]</center>";
	CloseTable();
	include("footer.php");
    } else {
	mysql_query("update $prefix"._users_status." set level='2' where uid='$forum_moderator'");    
	if (!$forum_pass) {
	    mysql_query("update $prefix"._forums." set forum_name='$forum_name', forum_desc='$forum_desc', forum_access='$forum_access', forum_moderator='$forum_moderator', cat_id='$cat_id', forum_type='$forum_type', forum_pass='$forum_pass' where forum_id='$forum_id'");
	} else {
	    mysql_query("update $prefix"._forums." set forum_name='$forum_name', forum_desc='$forum_desc', forum_access='$forum_access', forum_moderator='$forum_moderator', cat_id='$cat_id', forum_type='$forum_type' where forum_id='$forum_id'");
	}
	Header("Location: admin.php?op=ForumAdmin");
    }
}

function ForumCatAdd($catagories) {
	global $prefix;
    mysql_query("insert into $prefix"._catagories." values (NULL, '$catagories')");
    Header("Location: admin.php?op=ForumAdmin");
}

function ForumGoAdd($forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass) {
    global $hlpfile, $prefix;
    $result = mysql_query("select uid from $prefix"._users." where uname='$forum_mod'");
    list($forum_moderator) = mysql_fetch_row($result);
    if ($forum_moderator == "") {
	include ("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center>The select Moderator doesn't exist in the database.<br>";
	echo "Please check and edit the Moderator's name.<br><br>";
	echo "[ <A HREF=javascript:history.go(-1)>".translate("Go Back")."</A> ]</center>";
	CloseTable();
	include("footer.php");
    } else {
	mysql_query("update $prefix"._users_status." set level='2' where uid='$forum_moderator'");
	mysql_query("insert into $prefix"._forums." values (NULL, '$forum_name', '$forum_desc', '$forum_access', '$forum_moderator', '$cat_id', '$forum_type', '$forum_pass')");
	Header("Location: admin.php?op=ForumGo&cat_id=$cat_id");
    }
}

function ForumCatDel($cat_id, $ok=0) {
	global $prefix;
    if($ok==1) {
	$result = mysql_query("select forum_id from $prefix"._forums." where cat_id='$cat_id'");
	while(list($forum_id) = mysql_fetch_row($result)) {
	mysql_query("delete from $prefix"._forumtopics." where forum_id=$forum_id");
	}
	mysql_query("delete from $prefix"._forums." where cat_id=$cat_id");
	mysql_query("delete from $prefix"._catagories." where cat_id=$cat_id");
	Header("Location: admin.php?op=ForumAdmin");
    } else {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><br>";
	echo "<font size=3 color=Red>";
	echo "<b>".translate("WARNING: Are you sure you want to delete this Categories, Forums and all its Topics?")."</b><br><br><font color=Black>";
    }
	echo "[ <a href=admin.php?op=ForumCatDel&cat_id=$cat_id&ok=1>".translate("Yes")."</a> | <a href=admin.php?op=ForumAdmin>".translate("No")."</a> ]<br><br>";
	echo "</TD></TR></TABLE></TD></TR></TABLE>";
	include("footer.php");	
}

function ForumGoDel($forum_id, $ok=0) {
	global $prefix;
    if($ok==1) {
	mysql_query("delete from $prefix"._forumtopics." where forum_id=$forum_id");
	mysql_query("delete from $prefix"._forums." where forum_id=$forum_id");
	Header("Location: admin.php?op=ForumAdmin");
    } else {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><br>";
	echo "<font size=3 color=Red>";
	echo "<b>".translate("WARNING: Are you sure you want to delete this Forums and all its Topics?")."</b><br><br><font color=Black>";
    }
	echo "[ <a href=admin.php?op=ForumGoDel&forum_id=$forum_id&ok=1>".translate("Yes")."</a> | <a href=admin.php?op=ForumAdmin>".translate("No")."</a> ]<br><br>";
	CloseTable();
	include("footer.php");	
}

} else {
    echo "Access Denied";
}

?>