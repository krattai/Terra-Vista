<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$hlpfile = "manual/reviews.html";
$result = mysql_query("select radminsuper from $prefix"._authors." where aid='$aid'");
list($radminsuper) = mysql_fetch_row($result);
if ($radminsuper==1) {

/*********************************************************/
/* Messages Functions                                    */
/*********************************************************/

function MsgDeactive() {
    global $prefix;
    mysql_query("update $prefix"._message." set active='0'");
    Header("Location: admin.php?op=messages");
}

function messages() {
    global $hlpfile, $prefix;
    include ("header.php");
    $hlpfile = "manual/messages.html";
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._MESSAGESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = mysql_query("select title, content, date, expire, active, view from $prefix"._message."");
    list($title, $content, $mdate, $expire, $active, $view) = mysql_fetch_row($result);
    OpenTable();
    if ($active == 1) {
	$asel1 = "checked";
	$asel2 = "";
    } elseif ($active == 0) {
	$asel1 = "";
	$asel2 = "checked";
    }
    if ($view == 1) {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "";
    } elseif ($view == 2) {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
	$sel4 = "";
    } elseif ($view == 3) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";
	$sel4 = "";
    } elseif ($view == 4) {    
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "selected";
    }
    if ($expire == 86400) {
	$esel1 = "selected";
	$esel2 = "";
	$esel3 = "";
	$esel4 = "";
	$esel5 = "";
	$esel6 = "";
    } elseif ($expire == 172800) {
	$esel1 = "";
	$esel2 = "selected";
	$esel3 = "";
	$esel4 = "";
	$esel5 = "";
	$esel6 = "";
    } elseif ($expire == 432000) {
	$esel1 = "";
	$esel2 = "";
	$esel3 = "selected";
	$esel4 = "";
	$esel5 = "";
	$esel6 = "";
    } elseif ($expire == 1296000) {
	$esel1 = "";
	$esel2 = "";
	$esel3 = "";
	$esel4 = "selected";
	$esel5 = "";
	$esel6 = "";
    } elseif ($expire == 2592000) {
	$esel1 = "";
	$esel2 = "";
	$esel3 = "";
	$esel4 = "";
	$esel5 = "selected";
	$esel6 = "";
    } elseif ($expire == 0) {
	$esel1 = "";
	$esel2 = "";
	$esel3 = "";
	$esel4 = "";
	$esel5 = "";
	$esel6 = "selected";
    }
    echo "<form action=\"admin.php\" method=\"post\">"
	."<br><b>"._MESSAGETITLE.":</b><br>"
	."<input type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\"><br><br>"
	."<b>"._MESSAGECONTENT.":</b><br>"
	."<textarea name=\"content\" rows=\"15\" wrap=\"virtual\" cols=\"60\">$content</textarea><br><br>"
	."<b>"._EXPIRATION.":</b> <select name=\"expire\">"
	."<option name=\"expire\" value=\"86400\" $esel1>1 "._DAY."</option>"
	."<option name=\"expire\" value=\"172800\" $esel2>2 "._DAYS."</option>"
	."<option name=\"expire\" value=\"432000\" $esel3>5 "._DAYS."</option>"
	."<option name=\"expire\" value=\"1296000\" $esel4>15 "._DAYS."</option>"
	."<option name=\"expire\" value=\"2592000\" $esel5>30 "._DAYS."</option>"
	."<option name=\"expire\" value=\"0\" $esel6>"._UNLIMITED."</option>"
	."</select><br><br>"
	."<b>Active?</b> <input type=\"radio\" name=\"active\" value=\"1\" $asel1>"._YES." "
	."<input type=\"radio\" name=\"active\" value=\"0\" $asel2>"._NO."";
    if ($active == 1) {
	echo "<br><br><b>"._CHANGEDATE."</b>"
	    ."<input type=\"radio\" name=\"chng_date\" value=\"1\">"._YES." "
	    ."<input type=\"radio\" name=\"chng_date\" value=\"0\" checked>"._NO."<br><br>";
    } elseif ($active == 0) {
	echo "<br><font size=\"1\">"._IFYOUACTIVE."</font><br><br>"
	    ."<input type=\"hidden\" name=\"chng_date\" value=\"1\">";
    }
    echo "<b>"._VIEWPRIV."</b> <select name=\"view\">"
	."<option name=\"view\" value=\"1\" $sel1>"._MVALL."</option>"
	."<option name=\"view\" value=\"2\" $sel2>"._MVANON."</option>"
	."<option name=\"view\" value=\"3\" $sel3>"._MVUSERS."</option>"
	."<option name=\"view\" value=\"4\" $sel4>"._MVADMIN."</option>"
	."</select><br><br>"
	."<input type=\"hidden\" name=\"mdate\" value=\"$mdate\">"
	."<input type=\"hidden\" name=\"op\" value=\"savemsg\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\">"
	."</form>";
    CloseTable();
    include ("footer.php");
}

function savemsg($title, $content, $mdate, $expire, $active, $view, $chng_date) {
    global $prefix;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    if ($chng_date == 1) {
	$newdate = time();
    } elseif ($chng_date == 0) {
	$newdate = $mdate;
    }
    $result = mysql_query("update $prefix"._message." set title='$title', content='$content', date='$newdate', expire='$expire', active='$active', view='$view'");
    Header("Location: admin.php?op=messages");
}

switch ($op){

    case "messages":
    messages();
    break;

    case "savemsg":
    savemsg($title, $content, $mdate, $expire, $active, $view, $chng_date);
    break;

}

} else {
    echo "Access Denied";
}

?>