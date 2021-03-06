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

if(!isset($mainfile)) { include("mainfile.php"); }

function listsections() {
    global $sitename, $prefix;
    include ('header.php');
    $result = mysql_query("select secid, secname, image from $prefix"._sections." order by secname");
    OpenTable();
    echo "
    <center>"._SECWELCOME." $sitename.<br><br>
    "._YOUCANFIND."</center><br><br>
    <table border=\"0\" align=\"center\">";
    $count = 0;
    while (list($secid, $secname, $image) = mysql_fetch_row($result)) {
        if ($count==2) {
        echo "<tr>";
        $count = 0;
        }
        echo "<td><a href=\"sections.php?op=listarticles&secid=$secid\"><img src=\"images/sections/$image\" border=\"0\" Alt=\"$secname\"></a>";
        $count++;
        if ($count==2) {
        echo "</tr>";
        }
        echo "</td>";
    }
    mysql_free_result($result);
    echo "</table></center>";
    CloseTable();
    include ('footer.php');
}

function listarticles($secid) {
    global $prefix;
    include ('header.php');
    $result = mysql_query("select secname from $prefix"._sections." where secid=$secid");
    list($secname) = mysql_fetch_row($result);
    mysql_free_result($result);
    $result = mysql_query("select artid, secid, title, content, counter from $prefix"._seccont." where secid=$secid");
    OpenTable();
    $result2 = mysql_query("select image from $prefix"._sections." where secid=$secid");
    list($image) = mysql_fetch_row($result2);
    echo "<center><img src=\"images/sections/$image\" border=\"0\" alt=\"\"><br><br>
    <font size=\"3\">
    "._THISISSEC." <b>$secname</b>.<br>"._FOLLOWINGART."</font></center><br><br>
    <table border=\"0\" align=\"center\">";
    while (list($artid, $secid, $title, $content, $counter) = mysql_fetch_row($result)) {
        echo "
        <tr><td align=\"left\" nowrap><font size=\"2\">
        <li><a href=\"sections.php?op=viewarticle&artid=$artid\">$title</a> ($counter "._READS.")
        <a href=\"sections.php?op=printpage&artid=$artid\"><img src=\"images/print.gif\" border=\"0\" Alt=\""._PRINTER."\" width=\"15\" height=\"11\"></a>
        </td></tr>
        ";    
    }
    echo "</table>
    <br><br><br><center>
    [ <a href=\"sections.php\">"._SECRETURN."</a> ]</center>";
    CloseTable();
    mysql_free_result($result);
    include ('footer.php');
}

function viewarticle($artid, $page) {
    global $prefix;
    include("header.php");
    if (($page == 1) OR ($page == "")) {
	mysql_query("update $prefix"._seccont." set counter=counter+1 where artid='$artid'");
    }
    $result = mysql_query("select artid, secid, title, content, counter from $prefix"._seccont." where artid=$artid");
    list($artid, $secid, $title, $content, $counter) = mysql_fetch_row($result);
        
    $result2 = mysql_query("select secid, secname from $prefix"._sections." where secid=$secid");
    list($secid, $secname) = mysql_fetch_row($result2);
    $words = sizeof(explode(" ", $content));
    echo "<center>";
    OpenTable();
    $contentpages = explode( "<!--pagebreak-->", $content );
    $pageno = count($contentpages);
    if ( $page=="" || $page < 1 )
	$page = 1;
    if ( $page > $pageno )
	$page = $pageno;
    $arrayelement = (int)$page;
    $arrayelement --;
    echo "<font size=\"3\"><b>$title</b></font><br><br><font size=\"2\">";
    if ($pageno > 1) {
	echo ""._PAGE.": $page/$pageno<br>";
    }
    echo "($words "._TOTALWORDS.")<br>"
	."($counter "._READS.") &nbsp;&nbsp;"
	."<a href=\"sections.php?op=printpage&amp;artid=$artid\"><img src=\"images/print.gif\" border=\"0\" Alt=\""._PRINTER."\" width=\"15\" height=\"11\"></a>"
	."</font><br><br><br><br>
";
    echo "$contentpages[$arrayelement]";
    if($page >= $pageno)
 {
	  $next_page = "";
    } else {
	$next_pagenumber = $page + 1;
	if ($page != 1) {
	    $next_page .= "<img src=\"images/blackpixel.gif\" width=\"10\" height=\"2\" border=\"0\" alt=\"\"> &nbsp;&nbsp; ";
	}
	$next_page .= "<a href=\"sections.php?op=viewarticle&amp;artid=$artid&amp;page=$next_pagenumber\">"._NEXT." ($next_pagenumber/$pageno)</a> <a href=\"sections.php?op=viewarticle&artid=$artid&page=$next_pagenumber\"><img src=\"images/download/right.gif\" border=\"0\" alt=\""._NEXT."\"></a>";
    }

    if($page <= 1)
 {
	$previous_page = "";
    } else {
	$previous_pagenumber = $page - 1;
	$previous_page = "<a href=\"sections.php?op=viewarticle&amp;artid=$artid&amp;page=$previous_pagenumber\"><img src=\"images/download/left.gif\" border=\"0\" alt=\""._PREVIOUS."\"></a> <a href=\"sections.php?op=viewarticle&artid=$artid&page=$previous_pagenumber\">"._PREVIOUS." ($previous_pagenumber/$pageno)</a>";
    }
    echo "</td></tr>"
	."<tr><td align=\"center\">"
	."$previous_page &nbsp;&nbsp; $next_page<br><br>"
	."[ <a href=\"sections.php?op=listarticles&amp;secid=$secid\">"._BACKTO." $secname</a> | "
	."<a href=\"sections.php\">"._SECINDEX."</a> ]";
    CloseTable();
    echo "</center>";
    mysql_free_result($result);
    mysql_free_result($result2);
    include ('footer.php');
}

function PrintSecPage($artid) {
    global $site_logo, $nukeurl, $sitename, $datetime, $prefix;
    $result=mysql_query("select title, content from $prefix"._seccont." where artid=$artid");
    list($title, $content) = mysql_fetch_row($result);
    echo "
    <html>
    <head><title>$sitename</title></head>
    <body bgcolor=\"#FFFFFF\" text=\"#000000\">
    <table border=\"0\"><tr><td>
    <table border=\"0\" width=\"640\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"#000000\"><tr><td>
    <table border=\"0\" width=\"640\" cellpadding=\"20\" cellspacing=\"1\" bgcolor=\"#FFFFFF\"><tr><td>
    <center>
    <img src=\"images/$site_logo\" border=\"0\" alt=\"\"><br><br>
    <font size=\"+2\">
    <b>$title</b></font><br>
    </center><font size=\"2\">
    $content<br><br>";
    echo "</td></tr></table></td></tr></table>
    <br><br><center>
    <font size=\"2\">
    "._COMESFROM." $sitename<br>
    <a href=\"$nukeurl\">$nukeurl</a><br><br>
    "._THEURL."<br>
    <a href=\"$nukeurl/sections.php?artid=$artid\">$nukeurl/sections.php?artid=$artid</a></font></center>
    </td></tr></table>
    </body>
    </html>
    ";
}

switch($op) {

    case "viewarticle":
    viewarticle($artid, $page);
    break;

    case "listarticles":
    listarticles($secid);
    break;

    case "printpage":
    PrintSecPage($artid);
    break;
        
    default:
    listsections();
    break;

}

?>