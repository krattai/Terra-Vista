<?PHP

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)            */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$hlpfile = "manual/users.html";
$result = mysql_query("select radminuser, radminsuper from $prefix"._authors." where aid='$aid'");
list($radminuser, $radminsuper) = mysql_fetch_row($result);
if (($radminuser==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Users Functions                                       */
/*********************************************************/

function displayUsers() {
    global $hlpfile, $admin;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._EDITUSER."</b></font><br><br>"
	."<form method=\"post\" action=\"admin.php\">"
	."<b>"._NICKNAME.": </b> <input type=\"text\" name=\"chng_uid\" size=\"20\">\n"
	."<select name=\"op\">"
	."<option value=\"modifyUser\">"._MODIFY."</option>\n"
	."<option value=\"delUser\">"._DELETE."</option></select>\n"
	."<input type=\"submit\" value=\""._OK."\"></form></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._ADDUSER."</b></font><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\" width=\"100%\">"
        ."<tr><td width=\"100\">"._NICKNAME."</td>"
        ."<td><input type=\"text\" name=\"add_uname\" size=\"30\" maxlength=\"25\"> <font size=\"1\">"._REQUIRED."</font></td></tr>"
        ."<tr><td>"._NAME."</td>"
        ."<td><input type=\"text\" name=\"add_name\" size=\"30\" maxlength=\"50\"></td></tr>"
        ."<tr><td>"._EMAIL."</td>"
        ."<td><input type=\"text\" name=\"add_email\" size=\"30\" maxlength=\"60\"> <font size=\"1\">"._REQUIRED."</font></td></tr>"
        ."<tr><td>"._FAKEEMAIL."</td>"
        ."<td><input type=\"text\" name=\"add_femail\" size=\"30\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._URL."</td>"
        ."<td><input type=\"text\" name=\"add_url\" size=\"30\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._ICQ."</td>"
        ."<td><input type=\"text\" name=\"add_user_icq\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._AIM."</td>"
        ."<td><input type=\"text\" name=\"add_user_aim\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._YIM."</td>"
        ."<td><input type=\"text\" name=\"add_user_yim\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._MSNM."</td>"
        ."<td><input type=\"text\" name=\"add_user_msnm\" size=\"20\" maxlength=\"20\"></td></tr>"
        ."<tr><td>"._LOCATION."</td>"
        ."<td><input type=\"text\" name=\"add_user_from\" size=\"25\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._OCCUPATION."</td>"
        ."<td><input type=\"text\" name=\"add_user_occ\" size=\"25\" maxlength=\"60\"></td></tr>"
        ."<tr><td>"._INTERESTS."</td>"
        ."<td><input type=\"text\" name=\"add_user_intrest\" size=\"25\" maxlength=\"255\"></td></tr>"

#Insert for PHPBB usages
########################
        ."<tr><td>"._RANK."</td>"
        ."<td><select name=\"add_rank\">";
    $sql = "SELECT rank_id, rank_title FROM $prefix"._ranks." WHERE rank_special = 1";
    $r = mysql_query($sql);
    if($m = mysql_fetch_array($r)) {
	echo "<option value=\"0\">"._NORANK."</option>"
	    ."<option value=\"0\">------------------------</option>";
	do {
	    unset($selected);
	    echo "<option value=\"$m[rank_id]\">$m[rank_title]</option>\n";
	} while($m = mysql_fetch_array($r));
	echo "</select>\n";
    } else {
	echo "<option value=\"0\">"._NODBRANK."</option></select>\n";
    }
    echo "</td></tr><tr><td>"._USERLEVEL."</td>"
	."<td><select name=\"add_level\">";
    $sql = "SELECT access_id, access_title FROM $prefix"._access."";
    $r = mysql_query($sql);
    if($m = mysql_fetch_array($r)) {
    	do {
            unset($selected);
            echo "<option value=\"$m[access_id]\">$m[access_title]</option>\n";
        } while($m = mysql_fetch_array($r));
    }
    echo "</select></td></tr>"


		."<tr><td>"._OPTION."</td>"
        ."<td><input type=\"checkbox\" name=\"add_user_viewemail\" VALUE=\"1\"> "._ALLOWUSERS."</td></tr>"
        ."<tr><td>"._SIGNATURE."</td>"
        ."<td><textarea name=\"add_user_sig\" rows=\"6\" cols=\"45\"></textarea></td></tr>"
        ."<tr><td>"._PASSWORD."</td>"
        ."<td><input type=\"password\" name=\"add_pass\" size=\"12\" maxlength=\"12\"> <font size=\"1\">"._REQUIRED."</font></td></tr>"
        ."<input type=\"hidden\" name=\"add_avatar\" value=\"blank.gif\">"
        ."<input type=\"hidden\" name=\"op\" value=\"addUser\">"
        ."<tr><td><input type=\"submit\" value=\""._ADDUSERBUT."\"></form></td></tr>"
        ."</table>";
    CloseTable();
    include("footer.php");
}

function modifyUser($chng_user) {
    global $prefix;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = mysql_query("select uid, uname, name, url, email, femail, user_icq, user_aim, user_yim, user_msnm, user_from, user_occ, user_intrest, user_viewemail, user_avatar, user_sig, pass from $prefix"._users." where uid='$chng_user' or uname='$chng_user'");
    if(mysql_num_rows($result) > 0) {
        list($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_icq, $chng_user_aim, $chng_user_yim, $chng_user_msnm, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_user_sig, $chng_pass) = mysql_fetch_row($result);
	OpenTable();
	echo "<center><font size=\"3\"><b>"._USERUPDATE.": <i>$chng_user</i></b></font></center>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    ."<table border=\"0\">"
	    ."<tr><td>"._USERID."</td>"
	    ."<td><b>$chng_uid</b></td></tr>"
	    ."<tr><td>"._NICKNAME."</td>"
	    ."<td><input type=\"text\" name=\"chng_uname\" value=\"$chng_uname\"> <font size=\"1\">"._REQUIRED."</font></td></tr>"
	    ."<tr><td>"._NAME."</td>"
	    ."<td><input type=\"text\" name=\"chng_name\" value=\"$chng_name\"></td></tr>"
	    ."<tr><td>"._URL."</td>"
	    ."<td><input type=\"text\" name=\"chng_url\" value=\"$chng_url\" size=\"30\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._EMAIL."</td>"
	    ."<td><input type=\"text\" name=\"chng_email\" value=\"$chng_email\" size=\"30\" maxlength=\"60\"> <font size=\"1\">"._REQUIRED."</font></td></tr>"
	    ."<tr><td>"._FAKEEMAIL."</td>"
	    ."<td><input type=\"text\" name=\"chng_femail\" value=\"$chng_femail\" size=\"30\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._ICQ."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_icq\" value=\"$chng_user_icq\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._AIM."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_aim\" value=\"$chng_user_aim\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._YIM."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_yim\" value=\"$chng_user_yim\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._MSNM."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_msnm\" value=\"$chng_user_msnm\" size=\"20\" maxlength=\"20\"></td></tr>"
	    ."<tr><td>"._LOCATION."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_from\" value=\"$chng_user_from\" size=\"25\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._OCCUPATION."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_occ\" value=\"$chng_user_occ\" size=\"25\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._INTERESTS."</td>"
	    ."<td><input type=\"text\" name=\"chng_user_intrest\" value=\"$chng_user_intrest\" size=\"25\" maxlength=\"255\"></td></tr>";

#Insertion for PHPBB usage
##########################
	$mytest = mysql_query("select rank, level from $prefix"._users_status." where uid='$chng_uid'");
	list($chng_rank, $chng_level) = mysql_fetch_row($mytest);
	echo "<tr><td>"._RANK."</td>"
	    ."<td><select name=\"rank\">";
	$sql = "SELECT rank_id, rank_title FROM $prefix"._ranks." WHERE rank_special = 1";
	$r = mysql_query($sql);
	if($m = mysql_fetch_array($r)) {
	    echo "<option value=\"0\">"._NORANK."</option>"
		."<option value=\"0\">------------------------</option>";
	    do {
		unset($selected);
		if($chng_rank == $m[rank_id])
		    $selected = "selected";
		echo "<option value=\"$m[rank_id]\" $selected>$m[rank_title]</option>\n";
	    } while($m = mysql_fetch_array($r));
	    echo "</select>\n";
	} else {
	    echo "<option value=\"0\">"._NODBRANK."</option></select>\n";
	}
	echo "</td></tr><tr><td>"._USERLEVEL."</td>"
	    ."<td><select name=\"level\">";
	$sql = "SELECT access_id, access_title FROM $prefix"._access."";
	$r = mysql_query($sql);
        if($m = mysql_fetch_array($r)) {
    	    do {
                unset($selected);
                if($chng_level == $m[access_id])
            	    $selected = "selected";
                echo "<option value=\"$m[access_id]\" $selected>$m[access_title]</option>\n";
            } while($m = mysql_fetch_array($r));
	}
	echo "</select></td></tr>";

	echo "<tr><td>"._OPTION."</td>";
	if ($chng_user_viewemail ==1) {
	    echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\" checked> "._ALLOWUSERS."</td></tr>";
	} else {
	    echo "<td><input type=\"checkbox\" name=\"chng_user_viewemail\" value=\"1\"> "._ALLOWUSERS."</td></tr>";
	}
	echo "<tr><td>"._SIGNATURE."</td>"
	    ."<td><textarea name=\"chng_user_sig\" rows=\"6\" cols=\"45\">$chng_user_sig</textarea></td></tr>"
	    ."<tr><td>"._PASSWORD."</td>"
	    ."<td><input type=\"password\" name=\"chng_pass\" size=\"12\" maxlength=\"12\"></td></tr>"
	    ."<tr><td>"._RETYPEPASSWD."</td>"
	    ."<td><input type=\"password\" name=\"chng_pass2\" size=\"12\" maxlength=\"12\"> <font size=\"1\">"._FORCHANGES."</font></td></tr>"
	    ."<input type=\"hidden\" name=\"chng_avatar\" value=\"$chng_avatar\">"
	    ."<input type=\"hidden\" name=\"chng_uid\" value=\"$chng_uid\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"updateUser\">"
	    ."<tr><td><input type=\"submit\" value=\""._SAVECHANGES."\"></form></td></tr>"
	    ."</table>";
	CloseTable();
    } else {
	OpenTable();
	echo "<center><b>"._USERNOEXIST."</b><br><br>"
	    .""._GOBACK."</center>";
	CloseTable();
    }
    include("footer.php");
}

function updateUser($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_icq, $chng_user_aim, $chng_user_yim, $chng_user_msnm, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_sig, $chng_pass, $chng_pass2, $rank, $level) { 
    global $prefix;
    $tmp = 0;
    if ($chng_pass2 != "") {
        if($chng_pass != $chng_pass2) {
            include("header.php");
	    GraphicAdmin($hlpfile);
	    OpenTable();
	    echo "<center><font size=\"4\"><b>"._USERADMIN."</b></font></center>";
	    CloseTable();
	    echo "<br>";
	    OpenTable();
            echo "<center>"._PASSWDNOMATCH."<br><br>"
		.""._GOBACK."</center>";
	    CloseTable();
            include("footer.php");
            exit;
        }
        $tmp = 1;
    }
    if ($tmp == 0) {
	mysql_query("update $prefix"._users." set uname='$chng_uname', name='$chng_name', email='$chng_email', femail='$chng_femail', url='$chng_url', user_icq='$chng_user_icq', user_aim='$chng_user_aim', user_yim='$chng_user_yim', user_msnm='$chng_user_msnm', user_from='$chng_user_from', user_occ='$chng_user_occ', user_intrest='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_sig' where uid='$chng_uid'");
	mysql_query("update $prefix"._users_status." set rank='$rank', level='$level' where uid='$chng_uid'");
    }
    if ($tmp == 1) {
        $cpass = crypt($chng_pass);
        mysql_query("update $prefix"._users." set uname='$chng_uname', name='$chng_name', email='$chng_email', femail='$chng_femail', url='$chng_url', user_icq='$chng_user_icq', user_aim='$chng_user_aim', user_yim='$chng_user_yim', user_msnm='$chng_user_msnm', user_from='$chng_user_from', user_occ='$chng_user_occ', user_intrest='$chng_user_intrest', user_viewemail='$chng_user_viewemail', user_avatar='$chng_avatar', user_sig='$chng_sig', pass='$cpass' where uid='$chng_uid'");
        mysql_query("update $prefix"._users_status." set rank='$rank', level='$level' where uid='$chng_uid'");
    }
    Header("Location: admin.php?op=adminMain");
}

switch($op) {

    case "mod_users":
    displayUsers();
    break;

    case "modifyUser":
    modifyUser($chng_uid);
    break;

    case "updateUser":
	updateUser($chng_uid, $chng_uname, $chng_name, $chng_url, $chng_email, $chng_femail, $chng_user_icq, $chng_user_aim, $chng_user_yim, $chng_user_msnm, $chng_user_from, $chng_user_occ, $chng_user_intrest, $chng_user_viewemail, $chng_avatar, $chng_sig, $chng_pass, $chng_pass2, $rank, $level); 
    break;

    case "delUser":
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._USERADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._DELETEUSER."</b></font><br><br>"
	.""._SURE2DELETE." $chng_uid?<br><br>"
	."[ <a href=\"admin.php?op=delUserConf&amp;del_uid=$chng_uid\">"._YES."</a> | <a href=\"admin.php?op=mod_users\">"._NO."</a> ]</center>";
    CloseTable();
    include("footer.php");
    break;

    case "delUserConf":
    mysql_query("delete from $prefix"._users." where uid='$del_uid' or uname='$del_uid'");
    mysql_query("delete from $prefix"._users_status." where uid='$del_uid'");
    Header("Location: admin.php?op=adminMain");
    echo mysql_error();
    break;

    case "addUser":
    if ($system==1) {
    } else {
        $add_pass = crypt($add_pass);
    }
    if (!($add_uname && $add_email && $add_pass)) {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._USERADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
        OpenTable();
	echo "<center><b>"._NEEDTOCOMPLETE."</b><br><br>"
	    .""._GOBACK."";
	CloseTable();
	include("footer.php");
        return;
    }
    $user_regdate = date("M d, Y");
    $sql    = "insert into $prefix"._users." ";
    $sql    .= "(uid,name,uname,email,femail,url,user_regdate,user_icq,user_aim,user_yim,user_msnm,user_from,user_occ,user_intrest,user_viewemail,user_avatar,user_sig,pass) ";
    $sql    .= "values (NULL,'$add_name','$add_uname','$add_email','$add_femail','$add_url','$user_regdate','$add_user_icq','$add_user_aim','$add_user_yim','$add_user_msnm','$add_user_from','$add_user_occ','$add_user_intrest','$add_user_viewemail','$add_avatar','$add_user_sig','$add_pass')";
    $result = mysql_query($sql);
    if (!$result) {
        echo mysql_errno(). ": ".mysql_error(). "<br>"; return;
    }
    $result = mysql_query("insert into $prefix"._users_status." values (NULL,'0','0','$add_rank','$add_level')");
    Header("Location: admin.php?op=adminMain");
    break;
			
}

} else {
    echo "Access Denied";
}

?>