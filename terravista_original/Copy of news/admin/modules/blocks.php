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
$hlpfile = "manual/adminblock.html";
$result = mysql_query("select radminsuper from $prefix"._authors." where aid='$aid'");
list($radminsuper) = mysql_fetch_row($result);
if ($radminsuper==1) {

/*********************************************************/
/* Blocks Functions                                      */
/*********************************************************/

function BlocksAdmin() {
	global $hlpfile, $bgcolor2, $bgcolor4, $prefix;
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._BLOCKSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	/* Fixed blocks */
	OpenTable();
	echo "<center><font size=\"3\"><b>"._FIXEDBLOCKS."</b></font></center><br><br>";
	echo "<table border=\"1\" width=\"100%\"><tr>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._TITLE."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._POSITION."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\" colspan=\"2\"><b>"._WEIGHT."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._STATUS."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._FUNCTIONS."</b></tr>";
	$result = mysql_query("select bid, title, position, weight, active from $prefix"._blocks." where bkey!='' order by position, weight");
	while(list($bid, $title, $position, $weight, $active) = mysql_fetch_row($result)) {
	    $weight1 = $weight - 1;
	    $weight3 = $weight + 1;
	    $res = mysql_query("select bid from $prefix"._blocks." where weight='$weight1' AND position='$position'");
	    list ($bid1) = mysql_fetch_array($res);
	    $con1 = "$bid1"; 
	    $res2 = mysql_query("select bid from $prefix"._blocks." where weight='$weight3' AND position='$position'");
	    list ($bid2) = mysql_fetch_array($res2);
	    $con2 = "$bid2";
	    echo "<tr>"
		."<td align=\"center\">$title</td>";
	    if ($position == "l") {
		$position = "<img src=\"images/download/left.gif\" border=\"0\" alt=\""._LEFTBLOCK."\" hspace=\"5\"> "._LEFT."";
	    } elseif ($position == "r") {
		$position = ""._RIGHT." <img src=\"images/download/right.gif\" border=\"0\" alt=\""._RIGHTBLOCK."\" hspace=\"5\">";
	    }
	    echo "<td align=\"center\">$position</td>"
		."<td align=\"center\">"
		."&nbsp;$weight&nbsp;</td><td align=\"center\">";
	    if ($con1) {
		echo"<a href=\"admin.php?op=BlockOrder&amp;weight=$weight&amp;bidori=$bid&amp;weightrep=$weight1&amp;bidrep=$con1\"><img src=\"images/up.gif\" alt=\""._BLOCKUP."\" border=\"0\" hspace=\"3\"></a>";
	    }
	    if ($con2) {
		echo "<a href=\"admin.php?op=BlockOrder&amp;weight=$weight&amp;bidori=$bid&amp;weightrep=$weight3&amp;bidrep=$con2\"><img src=\"images/down.gif\" alt=\""._BLOCKDOWN."\" border=\"0\" hspace=\"3\"></a>";
	    }
	    echo"</td>";
	    if ($active == 1) {
		$active = _ACTIVE;
		$change = _DEACTIVATE;
	    } elseif ($active == 0) {
		$active = "<i>"._INACTIVE."</i>";
		$change = _ACTIVATE;
	    }
	    echo "<td align=\"center\">$active</td>"
		."<td align=\"center\"><font size=\"2\">[ <a href=\"admin.php?op=BlocksEditFixed&amp;bid=$bid\">"._EDIT."</a> | <a href=\"admin.php?op=ChangeStatus&amp;bid=$bid\">$change</a> ]</font></td></tr>";
	}
	echo "</table>";
	CloseTable();
        echo "<br>";
        OpenTable();
	echo "<center><font size=\"3\"><b>"._USERBLOCKS."</b></font></center><br><br>";
    /* Non Fixed blocks */
	echo "<table border=\"1\" width=\"100%\"><tr>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._TITLE."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._POSITION."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\" colspan=\"2\"><b>"._WEIGHT."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._TYPE."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._STATUS."</b></td>"
	    ."<td align=\"center\" bgcolor=\"$bgcolor2\"><b>"._FUNCTIONS."</b></tr>";
	$result = mysql_query("select bid, title, url, position, weight, active from $prefix"._blocks." where bkey='' order by position, weight");
	while(list($bid, $title, $url, $position, $weight, $active) = mysql_fetch_row($result)) {
	    $weight1 = $weight - 1;
	    $weight3 = $weight + 1;
	    $res = mysql_query("select bid from $prefix"._blocks." where weight='$weight1' AND position='$position'");
	    list ($bid1) = mysql_fetch_array($res);
	    $con1 = "$bid1"; 
	    $res2 = mysql_query("select bid from $prefix"._blocks." where weight='$weight3' AND position='$position'");
	    list ($bid2) = mysql_fetch_array($res2);
	    $con2 = "$bid2";
	    echo "<tr>"
		."<td align=\"center\">$title</td>";
	    if ($position == "l") {
		$position = "<img src=\"images/download/left.gif\" border=\"0\" alt=\""._LEFTBLOCK."\" hspace=\"5\"> "._LEFT."";
	    } elseif ($position == "r") {
		$position = ""._RIGHT." <img src=\"images/download/right.gif\" border=\"0\" alt=\""._RIGHTBLOCK."\" hspace=\"5\">";
	    }
	    echo "<td align=\"center\">$position</td>"
		."<td align=\"center\">"
		."&nbsp;$weight&nbsp;</td><td align=\"center\">";
	    if ($con1) {
		echo"<a href=\"admin.php?op=BlockOrder&amp;weight=$weight&amp;bidori=$bid&amp;weightrep=$weight1&amp;bidrep=$con1\"><img src=\"images/up.gif\" alt=\""._BLOCKUP."\" border=\"0\" hspace=\"3\"></a>";
	    }
	    if ($con2) {
		echo "<a href=\"admin.php?op=BlockOrder&amp;weight=$weight&amp;bidori=$bid&amp;weightrep=$weight3&amp;bidrep=$con2\"><img src=\"images/down.gif\" alt=\""._BLOCKDOWN."\" border=\"0\" hspace=\"3\"></a>";
	    }
	    echo"</td>";
	    if ($url == "") {
		$type = "HTML";
	    } else {
		$type = "RSS/RDF";
	    }
	    echo "<td align=\"center\">$type</td>";
	    if ($active == 1) {
		$active = _ACTIVE;
		$change = _DEACTIVATE;
	    } elseif ($active == 0) {
		$active = "<i>"._INACTIVE."</i>";
		$change = _ACTIVATE;
	    }
	    echo "<td align=\"center\">$active</td>"
		."<td align=\"center\"><font size=\"2\">[ <a href=\"admin.php?op=BlocksEdit&amp;bid=$bid\">"._EDIT."</a> | <a href=\"admin.php?op=ChangeStatus&amp;bid=$bid\">$change</a> | <a href=\"admin.php?op=BlocksDelete&amp;bid=$bid\">"._DELETE."</a> ]</font></td></tr>";
	}
	echo "</table>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><font size=\"3\"><b>"._ADDNEWBLOCK."</b></font></center><br><br>"
	    ."<form action=\"admin.php\" method=\"post\">"
	    ."<table border=\"0\" width=\"100%\">"
	    ."<tr><td>"._TITLE.":</td><td><input type=\"text\" name=\"title\" size=\"30\" maxlength=\"60\"></td></tr>"
	    ."<tr><td>"._RSSFILE.":</td><td><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"200\">&nbsp;&nbsp;"
	    ."<select name=\"headline\">"
	    ."<option name=\"headline\" value=\"0\" selected>"._CUSTOM."</option>";
	$res = mysql_query("select hid, sitename from $prefix"._headlines."");
	while(list($hid, $htitle) = mysql_fetch_row($res)) {
	    echo "<option name=\"headline\" value=\"$hid\">$htitle</option>";
	}
	echo "</select>&nbsp;[ <a href=\"admin.php?op=HeadlinesAdmin\">Setup</a> ]<br><font size=\"1\">";
	echo ""._SETUPHEADLINES."</font></td></tr>"
	    ."<tr><td>"._CONTENT.":</td><td><textarea name=\"content\" cols=\"50\" rows=\"10\"></textarea><br><font size=\"1\">"._IFRSSWARNING."</font></td></tr>"
	    ."<tr><td>"._POSITION.":</td><td><select name=\"position\"><option name=\"position\" value=\"l\">"._LEFT."</option>"
	    ."<option name=\"position\" value=\"r\">"._RIGHT."</option></select></td></tr>"
	    ."<tr><td>"._ACTIVATE2."</td><td><input type=\"radio\" name=\"active\" value=\"1\" checked>"._YES." &nbsp;&nbsp;"
	    ."<input type=\"radio\" name=\"active\" value=\"0\">"._NO."</td></tr>"
	    ."<tr><td>"._REFRESHTIME.":</td><td><select name=\"refresh\"><option name=\"refresh\" value=\"1800\">1/2 "._HOUR."</option>"
	    ."<option name=\"refresh\" value=\"3600\" selected>1 "._HOUR."</option>"
	    ."<option name=\"refresh\" value=\"18000\">5 "._HOURS."</option>"
	    ."<option name=\"refresh\" value=\"36000\">10 "._HOURS."</option>"
	    ."<option name=\"refresh\" value=\"86400\">24 "._HOURS."</option></select>&nbsp;<font size=\"1\">"._ONLYHEADLINES."</font>"
	    ."</td></tr></table>"
	    ."<input type=\"hidden\" name=\"op\" value=\"BlocksAdd\">"
	    ."<input type=\"submit\" value=\""._CREATEBLOCK."\"></form>";
	CloseTable();
	include("footer.php");
}

function BlockOrder ($weightrep,$weight,$bidrep,$bidori) {
    global $prefix;
    $result = mysql_query("update $prefix"._blocks." set weight='$weight' where bid='$bidrep'");
    $result2 = mysql_query("update $prefix"._blocks." set weight='$weightrep' where bid='$bidori'");
    Header("Location: admin.php?op=BlocksAdmin");
}

function rssfail() {
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._BLOCKSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>"._RSSFAIL."</b><br><br>"
	.""._RSSTRYAGAIN."<br><br>"
	.""._GOBACK."</center>";
    CloseTable();
    include("footer.php");
}

function BlocksAdd($title, $content, $url, $position, $active, $refresh, $headline) {
    global $prefix;
    if ($headline != 0) {
	$result = mysql_query("select sitename, headlinesurl from $prefix"._headlines." where hid='$headline'");
	list ($title, $url) = mysql_fetch_row($result);
    }
    $result = mysql_query("SELECT weight FROM $prefix"._blocks." WHERE position='$position' ORDER BY weight DESC");
    list ($weight) = mysql_fetch_array($result);
    $weight++;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    $bkey = "";
    $btime = "";
    if ($url != "") {
	$btime = time();
	if (!ereg("http://",$url)) {
	    $url = "http://$url";
	}
	$rdf = parse_url($url);
	$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
	if (!$fp) {
	    rssfail();
	    exit;
	}
	if ($fp) {
	    fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTTP/1.0\r\n");
	    fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
	    $string	= "";
	    while(!feof($fp)) {
	    	$pagetext = fgets($fp,300);
	    	$string .= chop($pagetext);
	    }
	    fputs($fp,"Connection: close\r\n\r\n");
	    fclose($fp);
	    $items = explode("</item>",$string);
	    $content = "<font size=\"2\">";
	    for ($i=0;$i<10;$i++) {
		$link = ereg_replace(".*<link>","",$items[$i]);
		$link = ereg_replace("</link>.*","",$link);
		$title2 = ereg_replace(".*<title>","",$items[$i]);
		$title2 = ereg_replace("</title>.*","",$title2);
		if ($items[$i] == "") {
		    $content = "<font size=\"2\">"._RSSPROBLEM."</font>";
		}
		if (strcmp($link,$title2)) {
		    $content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br>\n";
		}
	    }
	    if (($link) AND ($title2)) {
		$siteurl = ereg_replace("http://","",$url);
		$siteurl = explode("/",$siteurl);
		$content .= "<br><a href=\"http://$siteurl[0]\" target=\"blank\"><b>"._HREADMORE."</b></a></font>";
	    }
	}
    }
    if ($content == "") {
	rssfail();
    } else {
	$result = mysql_query("insert into $prefix"._blocks." values (NULL, '$bkey', '$title', '$content', '$url', '$position', '$weight', '$active', '$refresh', '$btime')");
	Header("Location: admin.php?op=BlocksAdmin");
    }
}

function BlocksEdit($bid) {
    global $hlpfile, $bgcolor2, $bgcolor4, $prefix;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._EDITBLOCK."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = mysql_query("select bkey, title, content, url, position, weight, active, refresh from $prefix"._blocks." where bid='$bid'");
    list($bkey, $title, $content, $url, $position, $weight, $active, $refresh) = mysql_fetch_row($result);
    if ($url != "") {
	$type = ""._RSSCONTENT."";
    }
    OpenTable();
    echo "<center><font size=\"3\"><b>"._BLOCK.": $title $type</b></font></center><br><br>"
        ."<form action=\"admin.php\" method=\"post\">"
        ."<table border=\"0\" width=\"100%\">"
        ."<tr><td>"._TITLE.":</td><td><input type=\"text\" name=\"title\" size=\"30\" maxlength=\"60\" value=\"$title\"></td></tr>"
        ."<tr><td>"._RSSFILE.":</td><td><input type=\"text\" name=\"url\" size=\"30\" maxlength=\"200\" value=\"$url\">&nbsp;&nbsp;<font size=\"1\">"._ONLYHEADLINES."</font></td></tr>";
    if ($url == "") {
	echo "<tr><td>"._CONTENT.":</td><td><textarea name=\"content\" cols=\"50\" rows=\"10\">$content</textarea></td></tr>";
    }
    $oldposition = $position;
    echo "<input type=\"hidden\" name=\"oldposition\" value=\"$oldposition\">";
    if ($position == "l") {
	$sel1 = "selected";
	$sel2 = "";
    } elseif ($position == "r") {
	$sel1 = "";
	$sel2 = "selected";
    }
    echo "<tr><td>"._POSITION.":</td><td><select name=\"position\"><option name=\"position\" value=\"l\" $sel1>"._LEFT."</option>"
        ."<option name=\"position\" value=\"r\" $sel2>"._RIGHT."</option></select></td></tr>";

    if ($active == 1) {
	$sel1 = "checked";
	$sel2 = "";
    } elseif ($active == 0) {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<tr><td>"._ACTIVATE2."</td><td><input type=\"radio\" name=\"active\" value=\"1\" $sel1>"._YES." &nbsp;&nbsp;"
        ."<input type=\"radio\" name=\"active\" value=\"0\" $sel2>"._NO."</td></tr>";
    if ($refresh == 1800) {
	$sel1 = "selected";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "";
	$sel5 = "";
    } elseif ($refresh == 3600) {
	$sel1 = "";
	$sel2 = "selected";
	$sel3 = "";
	$sel4 = "";
	$sel5 = "";
    } elseif ($refresh == 18000) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "selected";
	$sel4 = "";
	$sel5 = "";
    } elseif ($refresh == 36000) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "selected";
	$sel5 = "";
    } elseif ($refresh == 86400) {
	$sel1 = "";
	$sel2 = "";
	$sel3 = "";
	$sel4 = "";
	$sel5 = "selected";
    }
    echo "<tr><td>"._REFRESHTIME.":</td><td><select name=\"refresh\"><option name=\"refresh\" value=\"1800\" $sel1>1/2 "._HOUR."</option>"
        ."<option name=\"refresh\" value=\"3600\" $sel2>1 "._HOUR."</option>"
        ."<option name=\"refresh\" value=\"18000\" $sel3>5 "._HOURS."</option>"
        ."<option name=\"refresh\" value=\"36000\" $sel4>10 "._HOURS."</option>"
        ."<option name=\"refresh\" value=\"86400\" $sel5>24 "._HOURS."</option></select>&nbsp;<font size=\"1\">"._ONLYHEADLINES."</font>"
        ."</td></tr></table>"
	."<input type=\"hidden\" name=\"bid\" value=\"$bid\">"
	."<input type=\"hidden\" name=\"bkey\" value=\"$bkey\">"
	."<input type=\"hidden\" name=\"weight\" value=\"$weight\">"
        ."<input type=\"hidden\" name=\"op\" value=\"BlocksEditSave\">"
        ."<input type=\"submit\" value=\""._SAVEBLOCK."\"></form>";
    CloseTable();
    include("footer.php");
}

function BlocksEditFixed($bid) {
    global $hlpfile, $bgcolor2, $bgcolor4, $prefix;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._EDITFIXEDBLOCK."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = mysql_query("select bkey, title, content, url, position, weight, active, refresh from $prefix"._blocks." where bid='$bid'");
    list($bkey, $title, $content, $url, $position, $weight, $active, $refresh) = mysql_fetch_row($result);
    OpenTable();
    echo "<center><font size=\"3\"><b>"._BLOCK.": $title</b></font></center><br><br>"
        ."<form action=\"admin.php\" method=\"post\">"
        ."<table border=\"0\" width=\"100%\">"
        ."<tr><td>"._TITLE.":</td><td><input type=\"text\" name=\"title\" size=\"30\" maxlength=\"60\" value=\"$title\"></td></tr>";
    if (($bkey == main) OR ($bkey == admin)) {
	echo "<tr><td>"._CONTENT.":</td><td><textarea name=\"content\" cols=\"50\" rows=\"10\">$content</textarea></td></tr>";
    } else {
	echo "<input type=\"hidden\" name=\"content\" value=\"$content\">";
    }
    $oldposition = $position;
    echo "<input type=\"hidden\" name=\"oldposition\" value=\"$oldposition\">";
    if ($position == "l") {
	$sel1 = "selected";
	$sel2 = "";
    } elseif ($position == "r") {
	$sel1 = "";
	$sel2 = "selected";
    }
    echo "<tr><td>"._POSITION.":</td><td><select name=\"position\"><option name=\"position\" value=\"l\" $sel1>"._LEFT."</option>"
        ."<option name=\"position\" value=\"r\" $sel2>"._RIGHT."</option></select></td></tr>";

    if ($active == 1) {
	$sel1 = "checked";
	$sel2 = "";
    } elseif ($active == 0) {
	$sel1 = "";
	$sel2 = "checked";
    }
    echo "<tr><td>"._ACTIVATE2."</td><td><input type=\"radio\" name=\"active\" value=\"1\" $sel1>"._YES." &nbsp;&nbsp;"
        ."<input type=\"radio\" name=\"active\" value=\"0\" $sel2>"._NO.""
        ."</td></tr></table>"
	."<input type=\"hidden\" name=\"bid\" value=\"$bid\">"
	."<input type=\"hidden\" name=\"weight\" value=\"$weight\">"
        ."<input type=\"hidden\" name=\"op\" value=\"BlocksEditSaveFixed\">"
        ."<input type=\"submit\" value=\""._SAVEBLOCK."\"></form>";
    CloseTable();
    include("footer.php");
}

function SortWeight($position) {
    global $prefix;
    $numbers = 1;
    $number_two = 1;
    $result = mysql_query("SELECT bid,weight FROM $prefix"._blocks." WHERE position='$position' ORDER BY weight");
    while (list ($bid,$weight) = mysql_fetch_row($result)){
	$result2 = mysql_query("update $prefix"._blocks." set weight='$numbers' where bid='$bid'");
	$numbers++;
    }
    if ($position == l) {
	$position_two = "r";
    } else {
	$position_two = "l";
    }
    $result_two = mysql_query("SELECT bid,weight FROM $prefix"._blocks." WHERE position='$position_two' ORDER BY weight");
    while (list ($bid2,$weight) = mysql_fetch_row($result_two)){
	$result_two2 = mysql_query("update $prefix"._blocks." set weight='$number_two' where bid='$bid2'");
	$number_two++;		
    }
    return $numbers;
}

function BlocksEditSaveFixed($bid, $title, $content, $oldposition, $position, $active, $refresh, $weight) {
    global $prefix;
    $title = stripslashes(FixQuotes($title));
    $content = stripslashes(FixQuotes($content));
    if ($oldposition != $position) {
	$result = mysql_query("select bid from $prefix"._blocks." where weight>='$weight' AND position='$position'");
	$fweight = $weight;
	$oweight = $weight;
	while (list($nbid) = mysql_fetch_row($result)) {
	    $weight++;
	    mysql_query("update $prefix"._blocks." set weight='$weight' where bid='$nbid'");
	}
	$result = mysql_query("select bid from $prefix"._blocks." where weight>'$oweight' AND position='$oldposition'");
	while (list($obid) = mysql_fetch_row($result)) {
	    mysql_query("update $prefix"._blocks." set weight='$oweight' where bid='$obid'");
	    $oweight++;
	}
	mysql_query("update $prefix"._blocks." set title='$title', content='$content', position='$position', weight='$fweight', active='$active', refresh='$refresh' where bid='$bid'");
    } else {
	$result = mysql_query("update $prefix"._blocks." set title='$title', content='$content', position='$position', weight='$weight', active='$active', refresh='$refresh' where bid='$bid'");
    }
    Header("Location: admin.php?op=BlocksAdmin"); 
}

function BlocksEditSave($bid, $bkey, $title, $content, $url, $oldposition, $position, $active, $refresh, $weight) {
    global $prefix;
    if ($url != "") {
	$bkey = "";
	$btime = time();
	if (!ereg("http://",$url)) {
	    $url = "http://$url";
	}
	$rdf = parse_url($url);
	$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
	if (!$fp) {
    	    rssfail();
    	    exit;
	}
	if ($fp) {
    	    fputs($fp, "GET " . $rdf['path'] . "?" . $rdf['query'] . " HTML/1.0\r\n");
    	    fputs($fp, "HOST: " . $rdf['host'] . "\r\n\r\n");
    	    $string	= "";
    	    while(!feof($fp)) {
    		$pagetext = fgets($fp,300);
		$string .= chop($pagetext);
	    }
	    fputs($fp,"Connection: close\r\n\r\n");
	    fclose($fp);
	    $items = explode("</item>",$string);
	    $content = "<font size=\"2\">";
	    for ($i=0;$i<10;$i++) {
		$link = ereg_replace(".*<link>","",$items[$i]);
		$link = ereg_replace("</link>.*","",$link);
		$title2 = ereg_replace(".*<title>","",$items[$i]);
		$title2 = ereg_replace("</title>.*","",$title2);
		if ($items[$i] == "") {
		    $content = "<font size=\"2\">"._RSSPROBLEM."</font>";
		}
		if (strcmp($link,$title2)) {
		    $content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br>\n";
		}
	    }
	    if (($link) AND ($title2)) {
		$siteurl = ereg_replace("http://","",$url);
		$siteurl = explode("/",$siteurl);
		$content .= "<br><a href=\"http://$siteurl[0]\" target=\"blank\"><b>"._HREADMORE."</b></a></font>";
	    }
	}
	if ($oldposition != $position) {
	    $result = mysql_query("select bid from $prefix"._blocks." where weight>='$weight' AND position='$position'");
	    $fweight = $weight;
	    $oweight = $weight;
	    while (list($nbid) = mysql_fetch_row($result)) {
		$weight++;
		mysql_query("update $prefix"._blocks." set weight='$weight' where bid='$nbid'");
	    }
	    $result = mysql_query("select bid from $prefix"._blocks." where weight>'$oweight' AND position='$oldposition'");
	    while (list($obid) = mysql_fetch_row($result)) {
		mysql_query("update $prefix"._blocks." set weight='$oweight' where bid='$obid'");
		$oweight++;
	    }
	    mysql_query("update $prefix"._blocks." set title='$title', content='$content', position='$position', weight='$fweight', active='$active', refresh='$refresh' where bid='$bid'");	    
	} else {
	    $result = mysql_query("update $prefix"._blocks." set bkey='$bkey', title='$title', content='$content', url='$url', position='$position', weight='$weight', active='$active', refresh='$refresh' where bid='$bid'");
	}    
	Header("Location: admin.php?op=BlocksAdmin");
    } else {
	$title = stripslashes(FixQuotes($title));
	$content = stripslashes(FixQuotes($content));
	if ($oldsposition != $position) {
	    $result = mysql_query("select bid from $prefix"._blocks." where weight>='$weight' AND position='$position'");
	    $fweight = $weight;
	    $oweight = $weight;
	    while (list($nbid) = mysql_fetch_row($result)) {
		$weight++;
		mysql_query("update $prefix"._blocks." set weight='$weight' where bid='$nbid'");
	    }
	    $result = mysql_query("select bid from $prefix"._blocks." where weight>'$oweight' AND position='$oldposition'");
	    while (list($obid) = mysql_fetch_row($result)) {
		mysql_query("update $prefix"._blocks." set weight='$oweight' where bid='$obid'");
		$oweight++;
	    }
	    mysql_query("update $prefix"._blocks." set title='$title', content='$content', position='$position', weight='$fweight', active='$active', refresh='$refresh' where bid='$bid'");	    	    
	} else {
	    $result = mysql_query("update $prefix"._blocks." set bkey='$bkey', title='$title', content='$content', url='$url', position='$position', weight='$weight', active='$active', refresh='$refresh' where bid='$bid'");
	}
	Header("Location: admin.php?op=BlocksAdmin");
    }
}

function ChangeStatus($bid, $ok=0) {
    global $prefix;
    $result = mysql_query("select active from $prefix"._blocks." where bid='$bid'");
    list($active) = mysql_fetch_row($result);
    if (($ok) OR ($active == 1)) {
	if ($active == 0) {
	    $active = 1;
	} elseif ($active == 1) {
	    $active = 0;
	}
	$result = mysql_query("update $prefix"._blocks." set active='$active' where bid='$bid'");
	Header("Location: admin.php?op=BlocksAdmin");
    } else {
	$result = mysql_query("select title, content from $prefix"._blocks." where bid='$bid'");
	list($title, $content) = mysql_fetch_row($result);
	include("header.php");
	GraphicAdmin($hlpfile);
	echo "<br>";
	OpenTable();
	echo "<center><font size=\"3\"><b>"._BLOCKACTIVATION."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	if ($content != "") {
	    echo "<center>"._BLOCKPREVIEW." <i>$title</i><br><br>";
	    themesidebox($title, $content);
	} else {
	    echo "<center><i>$title</i><br><br>";
	}
	echo "<br>"._WANT2ACTIVATE."<br><br>"
	    ."[ <a href=\"admin.php?op=Blocks\">"._NO."</a> | <a href=\"admin.php?op=ChangeStatus&amp;bid=$bid&amp;ok=1\">"._YES."</a> ]"
	    ."</center>";
	CloseTable();
	include("footer.php");
    }
}

function BlocksDelete($bid, $ok=0) {
    global $prefix;
    if ($ok) {
	$result = mysql_query("select position, weight from $prefix"._blocks." where bid='$bid'");
	list($position, $weight) = mysql_fetch_row($result);
	$result = mysql_query("select bid from $prefix"._blocks." where weight>'$weight' AND position='$position'");
	while (list($nbid) = mysql_fetch_row($result)) {
	    mysql_query("update $prefix"._blocks." set weight='$weight' where bid='$nbid'");
	    $weight++;
	}
	mysql_query("delete from $prefix"._blocks." where bid='$bid'");
	Header("Location: admin.php?op=BlocksAdmin");
    } else {
        $result = mysql_query("select title from $prefix"._blocks." where bid='$bid'");
	list($title) = mysql_fetch_row($result);
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._BLOCKSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center>"._ARESUREDELBLOCK." <i>$title</i>?";
	echo "<br><br>[ <a href=\"admin.php?op=BlocksAdmin\">"._NO."</a> | <a href=\"admin.php?op=BlocksDelete&amp;bid=$bid&amp;ok=1\">"._YES."</a> ]</center>";
	CloseTable();
	include("footer.php");
    }
}

function HeadlinesAdmin() {
    global $hlpfile, $bgcolor1, $bgcolor2, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._HEADLINESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<form action=\"admin.php\" method=\"post\">"
	."<table border=\"1\" width=\"100%\" align=\"center\"><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._SITENAME."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._URL."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._FUNCTIONS."</b></td><tr>";
    $result = mysql_query("select hid, sitename, headlinesurl from $prefix"._headlines." order by hid");
    while(list($hid, $sitename, $headlinesurl) = mysql_fetch_row($result)) {
	echo "<td bgcolor=\"$bgcolor1\" align=\"center\">$sitename</td>"
	    ."<td bgcolor=\"$bgcolor1\" align=\"center\"><a href=\"$headlinesurl\" target=\"new\">$headlinesurl</a></td>"
	    ."<td bgcolor=\"$bgcolor1\" align=\"center\">[ <a href=\"admin.php?op=HeadlinesEdit&amp;hid=$hid\">"._EDIT."</a> | <a href=\"admin.php?op=HeadlinesDel&amp;hid=$hid&amp;ok=0\">"._DELETE."</a> ]</td><tr>";
    }
    echo "</form></td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<font size=\"3\"><b>"._ADDHEADLINE."</b></font><br><br>"
	."<font size=\"2\">"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\" width=\"100%\"><tr><td>"
	.""._SITENAME.":</td><td><input type=\"text\" name=\"xsitename\" size=\"31\" maxlength=\"30\"></td></tr><tr><td>"
	.""._RSSFILE.":</td><td><input type=\"text\" name=\"headlinesurl\" size=\"50\" maxlength=\"200\"></td></tr><tr><td>"
	."</td></tr></table>"
	."<input type=\"hidden\" name=\"op\" value=\"HeadlinesAdd\">"
	."<input type=\"submit\" value=\""._ADD."\">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function HeadlinesEdit($hid) {
    global $hlpfile, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._HEADLINESADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = mysql_query("select sitename, headlinesurl from $prefix"._headlines." where hid='$hid'");
    list($xsitename, $headlinesurl) = mysql_fetch_row($result);
    OpenTable();
    echo "<center><font size=\"3\"><b>"._EDITHEADLINE."</b></font></center>
	<form action=\"admin.php\" method=\"post\">
	<input type=\"hidden\" name=\"hid\" value=\"$hid\">
	<table border=\"0\" width=\"100%\"><tr><td>
	"._SITENAME.":</td><td><input type=\"text\" name=\"xsitename\" size=\"31\" maxlength=\"30\" value=\"$xsitename\"></td></tr><tr><td>
	"._RSSFILE.":</td><td><input type=\"text\" name=\"headlinesurl\" size=\"50\" maxlength=\"200\" value=\"$headlinesurl\"></td></tr><tr><td>
	</select></td></tr></table>
	<input type=\"hidden\" name=\"op\" value=\"HeadlinesSave\">
	<input type=\"submit\" value=\""._SAVECHANGES."\">
	</form>";
    CloseTable();
    include("footer.php");
}

function HeadlinesSave($hid, $xsitename, $headlinesurl) {
    global $prefix;
    $xsitename = ereg_replace(" ", "", $xsitename);
    mysql_query("update $prefix"._headlines." set sitename='$xsitename', headlinesurl='$headlinesurl' where hid='$hid'");
    Header("Location: admin.php?op=HeadlinesAdmin");
}

function HeadlinesAdd($xsitename, $headlinesurl) {
    global $prefix;
    $xsitename = ereg_replace(" ", "", $xsitename);
    mysql_query("insert into $prefix"._headlines." values (NULL, '$xsitename', '$headlinesurl')");
    Header("Location: admin.php?op=HeadlinesAdmin");
}

function HeadlinesDel($hid, $ok=0) {
    global $prefix;
    if($ok==1) {
	mysql_query("delete from $prefix"._headlines." where hid=$hid");
	Header("Location: admin.php?op=HeadlinesAdmin");
    } else {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><br>";
	echo "<font size=\"3\">";
	echo "<b>"._SURE2DELHEADLINE."</b></font><br><br>";
    }
    echo "[ <a href=\"admin.php?op=HeadlinesDel&amp;hid=$hid&amp;ok=1\">"._YES."</a> | <a href=\"admin.php?op=HeadlinesAdmin\">"._NO."</a> ]<br><br>";
    CloseTable();
    include("footer.php");	
}

switch($op) {

    case "BlocksAdmin":
    BlocksAdmin();
    break;

    case "BlocksAdd":
    BlocksAdd($title, $content, $url, $position, $active, $refresh, $headline);
    break;

    case "BlocksEdit":
    BlocksEdit($bid);
    break;

    case "BlocksEditFixed":
    BlocksEditFixed($bid);
    break;

    case "BlocksEditSave":
    BlocksEditSave($bid, $bkey, $title, $content, $url, $oldposition, $position, $active, $refresh, $weight);
    break;

    case "BlocksEditSaveFixed":
    BlocksEditSaveFixed($bid, $title, $content, $oldposition, $position, $active, $refresh, $weight);
    break;

    case "ChangeStatus":
    ChangeStatus($bid, $ok, $de);
    break;

    case "BlocksDelete":
    BlocksDelete($bid, $ok);
    break;
    
    case "BlockOrder":    
    BlockOrder ($weightrep,$weight,$bidrep,$bidori);
    break;

    case "HeadlinesDel":
    HeadlinesDel($hid, $ok);
    break;
	
    case "HeadlinesAdd":
    HeadlinesAdd($xsitename, $headlinesurl);
    break;
	
    case "HeadlinesSave":
    HeadlinesSave($hid, $xsitename, $headlinesurl);
    break;
	
    case "HeadlinesAdmin":
    HeadlinesAdmin();
    break;
		
    case "HeadlinesEdit":
    HeadlinesEdit($hid);
    break;

}

} else {
    echo "Access Denied";
}

?>