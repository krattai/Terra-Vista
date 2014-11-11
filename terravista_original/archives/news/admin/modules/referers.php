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
$hlpfile = "manual/referer.html";
$result = mysql_query("select radminsuper from $prefix"._authors." where aid='$aid'");
list($radminsuper) = mysql_fetch_row($result);
if ($radminsuper==1) {

/*********************************************************/
/* Referer Functions to know who links us                */
/*********************************************************/

function hreferer() {
    global $hlpfile, $bgcolor2, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._HTTPREFERERS."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><b>"._WHOLINKS."</b></center><br><br>"
	."<table border=\"0\" width=\"100%\">";
    $hresult = mysql_query("select rid, url from $prefix"._referer."");
    while(list($rid, $url) = mysql_fetch_row($hresult)) {
	echo "<tr><td bgcolor=\"$bgcolor2\"><font size=\"2\">$rid</td>"
	    ."<td bgcolor=\"$bgcolor2\"><font size=\"2\"><a target=\"_blank\" href=\"$url\">$url</a></td></tr>";
    }
    echo "</table>"
	."<form action=\"admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"op\" value=\"delreferer\">"
	."<center><input type=\"submit\" value=\""._DELETEREFERERS."\"></center>";
    CloseTable();
    include ("footer.php");
}

function delreferer() {
    global $prefix;
    mysql_query("delete from $prefix"._referer."");
    Header("Location: admin.php?op=adminMain");
}

switch($op) {

    case "hreferer":
    hreferer();
    break;

    case "delreferer":
    delreferer();
    break;

}

} else {
    echo "Access Denied";
}
?>