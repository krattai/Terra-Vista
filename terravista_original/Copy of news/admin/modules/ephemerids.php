<?PHP

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
$hlpfile = "manual/ephem.html";
$result = mysql_query("select radminephem, radminsuper from $prefix"._authors." where aid='$aid'");
list($radminephem, $radminsuper) = mysql_fetch_row($result);
if (($radminephem==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Ephemerids Functions to have a Historic Ephemerids    */
/*********************************************************/

function Ephemerids() {
    global $hlpfile, $admin;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._EPHEMADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._ADDEPHEM."</b></font></center><br>"
	."<form action=\"admin.php\" method=\"post\">";
    $nday = "1";
    echo ""._DAY.": <select name=\"did\">";
    while ($nday<=31) {
	echo "<option name=\"did\">$nday</option>";
	$nday++;
    }
    echo "</select>";
    $nmonth = "1";
    echo ""._UMONTH.": <select name=\"mid\">";
    while ($nmonth<=12) {
	echo "<option name=\"mid\">$nmonth</option>";
	$nmonth++;
    }
    echo "</select>"._YEAR.": <input type=\"text\" name=\"yid\" maxlength=\"4\" size=\"5\"><br><br>"
	."<b>"._EPHEMDESC.":</b><br>"
	."<textarea name=\"content\" cols=\"60\" rows=\"10\"></textarea><br><br>"
	."<input type=\"hidden\" name=\"op\" value=\"Ephemeridsadd\">"
	."<input type=\"submit\" value=\""._OK."\">"
	."</form>";
    CloseTable();
    echo "<br>";
    OpenTable();
	echo "<center><font size=\"3\"><b>"._EPHEMMAINT."</b></font></center><br>"
	."<center><form action=\"admin.php\" method=\"post\">";
    $nday = "1";
    echo ""._DAY.": <select name=\"did\">";
    while ($nday<=31) {
	echo "<option name=\"did\">$nday</option>";
	$nday++;
    }
    echo "</select>";
    $nmonth = "1";
    echo ""._UMONTH.": <select name=\"mid\">";
    while ($nmonth<=12) {
	echo "<option name=\"mid\">$nmonth</option>";
	$nmonth++;
    }
    echo "</select>"
	."<input type=\"hidden\" name=\"op\" value=\"Ephemeridsmaintenance\">"
	."<input type=\"submit\" value=\""._EDIT."\">"
	."</form></center>";
    CloseTable();
    include ("footer.php");
}

function Ephemeridsadd($did, $mid, $yid, $content) {
    global $prefix;
    mysql_query("insert into $prefix"._ephem." values (NULL, '$did', '$mid', '$yid', '$content')");
    Header("Location: admin.php?op=Ephemerids");    
}

function Ephemeridsmaintenance($did, $mid) {
    global $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._EPHEMADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._EPHEMMAINT."</b></font></center><br>";
    $result=mysql_query("select eid, did, mid, yid, content from $prefix"._ephem." where did=$did AND mid=$mid");
    while(list($eid, $did, $mid, $yid, $content) = mysql_fetch_row($result)) {
    echo "<font size=\"2\"><b>$yid</b> [ <a href=\"admin.php?op=Ephemeridsedit&amp;eid=$eid&amp;did=$did&amp;mid=$mid\">"._EDIT."</a> | <a href=\"admin.php?op=Ephemeridsdel&amp;eid=$eid&amp;did=$did&amp;mid=$mid\">"._DELETE."</a> ]<br>"
	."<font size=\"1\">$content<br><br><br>";
    }
    CloseTable();
    include ('footer.php');
}

function Ephemeridsdel($eid, $did, $mid) {
    global $prefix;
    mysql_query("delete from $prefix"._ephem." where eid=$eid");
    Header("Location: admin.php?op=Ephemeridsmaintenance&did=$did&mid=$mid");
}

function Ephemeridsedit($eid, $did, $mid) {
    global $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._EPHEMADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result=mysql_query("select yid, content from $prefix"._ephem." where eid=$eid");
    list($yid, $content) = mysql_fetch_row($result);
    OpenTable();
    echo "<center><font size=4><b>"._EPHEMEDIT."</b></font></center><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<b>"._YEAR.":</b> <input type=\"text\" name=\"yid\" value=\"$yid\" maxlength=\"4\" size=\"5\"><br><br>"
	."<b>"._EPHEMDESC."</b><br>"
	."<textarea name=\"content\" cols=\"60\" rows=\"10\">$content</textarea><br><br>"
	."<input type=\"hidden\" name=\"did\" value=\"$did\">"
	."<input type=\"hidden\" name=\"mid\" value=\"$mid\">"
	."<input type=\"hidden\" name=\"eid\" value=\"$eid\">"
	."<input type=\"hidden\" name=\"op\" value=\"Ephemeridschange\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\">"
	."</form>";
    CloseTable();
    include ('footer.php');
}

function Ephemeridschange($eid, $did, $mid, $yid, $content) {
    global $prefix;
    $content = stripslashes(FixQuotes($content));
    mysql_query("update $prefix"._ephem." set yid='$yid', content='$content' where eid=$eid");
    Header("Location: admin.php?op=Ephemeridsmaintenance&did=$did&mid=$mid");    
}

switch($op) {

    case "Ephemeridsedit":
    Ephemeridsedit($eid, $did, $mid);
    break;
	
    case "Ephemeridschange":
    Ephemeridschange($eid, $did, $mid, $yid, $content);
    break;
			
    case "Ephemeridsdel":
    Ephemeridsdel($eid, $did, $mid);
    break;
			
    case "Ephemeridsmaintenance":
    Ephemeridsmaintenance($did, $mid);
    break;
			
    case "Ephemeridsadd":
    Ephemeridsadd($did, $mid, $yid, $content);
    break;
			
    case "Ephemerids":
    Ephemerids();
    break;

}

} else {
    echo "Access Denied";
}

?>