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
$hlpfile = "manual/surveys.html";
$result = mysql_query("select radminsurvey, radminsuper from $prefix"._authors." where aid='$aid'");
list($radminsurvey, $radminsuper) = mysql_fetch_row($result);
if (($radminsurvey==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Poll/Surveys Functions                                */
/*********************************************************/

function poll_createPoll() {
    global $language, $hlpfile, $admin;
    include ('header.php');
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._POLLSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._CREATEPOLL."</b></font><br><br>"
	."[ <a href=\"admin.php?op=remove\">"._DELETEPOLLS."</a> ]</center><br><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"op\" value=\"createPosted\">"
	.""._POLLTITLE.": <input type=\"text\" name=\"pollTitle\" size=\"50\" maxlength=\"100\"><br><br><br>"
	."<font size=\"2\">"._POLLEACHFIELD."<br>"
	."<table border=\"0\">";
    for($i = 1; $i <= 12; $i++)	{
	echo "<tr>"
	    ."<td>"._OPTION." $i:</td><td><input type=\"text\" name=\"optionText[$i]\" size=\"50\" maxlength=\"50\"></td>"
	    ."</tr>";
    }
    echo "</table><br><br>"
	."<input type=\"submit\" value=\""._CREATEPOLLBUT."\">"
	."</form>";
    CloseTable();
    include ('footer.php');
}

function old_poll_createPosted() {
    global $pollTitle, $optionText, $prefix;
    $timeStamp = time();
    $result = mysql_query("INSERT INTO $prefix"._poll_desc." VALUES (NULL, '$pollTitle', '$timeStamp')");
    if (!$result)	{
	echo mysql_errno(). ": ".mysql_error(). "<br>";
	return;
    }
    mysql_free_result($result);
    /* create option records in data table */
    for($i = 1; $i <= 12; $i++) {
	if($optionText[$i] != "") {
	    $result = mysql_query("INSERT INTO $prefix"._poll_data." VALUES ($id, '$optionText[$i]', 0, $i)");
	}
	if (!result) {
	    echo mysql_errno(). ": ".mysql_error(). "<br>";
	    return;
	}
	mysql_free_result($result);
    }
    Header("Location: admin.php?op=adminMain");
}

function poll_createPosted() {
    global $pollTitle, $optionText, $prefix;
    $timeStamp = time();
    $pollTitle = FixQuotes($pollTitle);
    if(!mysql_query("INSERT INTO $prefix"._poll_desc." VALUES (NULL, '$pollTitle', '$timeStamp', 0)")) {
	echo mysql_errno(). ": ".mysql_error(). "<br>";
	return;
    }
    $object = mysql_fetch_object(mysql_query("SELECT pollID FROM $prefix"._poll_desc." WHERE pollTitle='$pollTitle'"));
    $id = $object->pollID;
    for($i = 1; $i <= sizeof($optionText); $i++) {
	if($optionText[$i] != "") {
	    $optionText[$i] = FixQuotes($optionText[$i]);
	}
	if(!mysql_query("INSERT INTO $prefix"._poll_data." (pollID, optionText, optionCount, voteID) VALUES ($id, '$optionText[$i]', 0, $i)")) {
	    echo mysql_errno(). ": ".mysql_error(). "<br>";
	    return;
	}
    }
    Header("Location: admin.php?op=adminMain");
}

function poll_removePoll() {
    global $hlpfile, $prefix;
    $hlpfile = "manual/surveys.html";
    include ('header.php');
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._POLLSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._REMOVEEXISTING."</b></font><br><br>"
	.""._POLLDELWARNING."</center><br><br>"
	.""._CHOOSEPOLL."<br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"op\" value=\"removePosted\">"
	."<table border=\"0\">";
    $result = mysql_query("SELECT pollID, pollTitle, timeStamp FROM $prefix"._poll_desc." ORDER BY timeStamp"); 
    if(!$result) {
	echo mysql_errno(). ": ".mysql_error(). "<br>";
	return;
    }
    /* cycle through the descriptions until everyone has been fetched */
    while($object = mysql_fetch_object($result)) {
	$pollID = $object->pollID;
	echo "<tr><td><input type=\"radio\" name=\"id\" value=\"".$object->pollID."\">".$object->pollTitle."</td></tr>";
    }
    echo "</table>";
    echo "<input type=\"submit\" value=\""._DELETE."\">";
    echo "</form>";
    CloseTable();
    include ('footer.php');
}

function poll_removePosted() {
    global $id, $prefix;
    mysql_query("DELETE FROM $prefix"._poll_desc." WHERE pollID=$id");
    mysql_query("DELETE FROM $prefix"._poll_data." WHERE pollID=$id");
    Header("Location: admin.php?op=adminMain");
}

switch($op) {

    case "create":
    poll_createPoll();
    break;

    case "createPosted":
    poll_createPosted();
    break;

    case "poll_editPoll":
    poll_editPoll($pollID);
    break;

    case "ChangePoll":
    ChangePoll($pollID, $pollTitle, $optionText, $voteID);
    break;

    case "remove":
    poll_removePoll();
    break;

    case "removePosted":
    poll_removePosted();
    break;

}

} else {
    echo "Access Denied";
}

?>