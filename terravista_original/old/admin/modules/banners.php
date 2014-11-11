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
$hlpfile = "manual/banners.html";
$result = mysql_query("select radminsuper from $prefix"._authors." where aid='$aid'");
list($radminsuper) = mysql_fetch_row($result);
if ($radminsuper==1) {

/*********************************************************/
/* Banners Administration Functions                      */
/*********************************************************/

function BannersAdmin() {
    global $hlpfile, $prefix, $bgcolor2, $banners;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._BANNERSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
/* Check if Banners variable is active, if not then print a message */
    if ($banners == 0) {
	OpenTable();
	echo "<center><br><i><b>"._IMPORTANTNOTE."</b></i><br><br>"
	    .""._BANNERSNOTACTIVE."<br>"
	    .""._TOACTIVATE."<br><br></center>";
	CloseTable();
	echo "<br>";
    }
/* Banners List */
    echo "<a name=\"top\">";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._ACTIVEBANNERS."</b></font></center><br>"
	."<table width=100% border=0><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._ID."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._IMPRESSIONS."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._IMPLEFT."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CLICKS."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CLICKSPERCENT."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CLIENTNAME."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._FUNCTIONS."</b></td><tr>";
    $result = mysql_query("select bid, cid, imptotal, impmade, clicks, date from $prefix"._banner." order by bid");
    while(list($bid, $cid, $imptotal, $impmade, $clicks, $date) = mysql_fetch_row($result)) {
        $result2 = mysql_query("select cid, name from $prefix"._banner."client where cid=$cid");
        list($cid, $name) = mysql_fetch_row($result2);
	if($impmade==0) {
	    $percent = 0;
	} else {
	    $percent = substr(100 * $clicks / $impmade, 0, 5);
	}
	if($imptotal==0) {
	    $left = _UNLIMITED;
	} else {
	    $left = $imptotal-$impmade;
	}
	echo "<td bgcolor=\"$bgcolor2\" align=center>$bid</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=center>$impmade</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=center>$left</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=center>$clicks</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=center>$percent%</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=center>$name</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=center><a href=\"admin.php?op=BannerEdit&amp;bid=$bid\">"._EDIT."</a> | <a href=\"admin.php?op=BannerDelete&amp;bid=$bid&amp;ok=0\">"._DELETE."</a></td><tr>";
    }
    echo "</td></tr></table>";
    CloseTable();
    echo "<br>";
/* Finished Banners List */
    echo "<a name=top>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._FINISHEDBANNERS."</b></font></center><br>"
	."<table width=\"100%\" border=\"0\"><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._ID."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._IMP."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CLICKS."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CLICKSPERCENT."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._DATESTARTED."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._DATEENDED."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CLIENTNAME."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._FUNCTIONS."</b></td><tr>";
    $result = mysql_query("select bid, cid, impressions, clicks, datestart, dateend from $prefix"._banner."finish order by bid");
    while(list($bid, $cid, $impressions, $clicks, $datestart, $dateend) = mysql_fetch_row($result)) {
        $result2 = mysql_query("select cid, name from $prefix"._banner."client where cid=$cid");
	list($cid, $name) = mysql_fetch_row($result2);
	$percent = substr(100 * $clicks / $impressions, 0, 5);
	echo "<td bgcolor=\"$bgcolor2\" align=\"center\">$bid</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$impressions</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$clicks</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$percent%</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$datestart</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$dateend</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$name</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\"><a href=\"admin.php?op=BannerFinishDelete&amp;bid=$bid\">"._DELETE."</a></td><tr>";
    }
    echo "</td></tr></table>";
    CloseTable();
    echo "<br>";
/* Clients List */
    OpenTable();
    echo "<center><font size=\"3\"><b>"._ADVERTISINGCLIENTS."</b></font></center><br>"
	."<table width=\"100%\" border=\"0\"><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._ID."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CLIENTNAME."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._ACTIVEBANNERS2."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CONTACTNAME."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CONTACTEMAIL."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._FUNCTIONS."</b></td><tr>";
    $result = mysql_query("select cid, name, contact, email from $prefix"._banner."client  order by cid");
    while(list($cid, $name, $contact, $email) = mysql_fetch_row($result)) {
        $result2 = mysql_query("select cid from $prefix"._banner." where cid=$cid");
	$numrows = mysql_num_rows($result2);
	echo "<td bgcolor=\"$bgcolor2\" align=\"center\">$cid</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$name</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$numrows</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$contact</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$email</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\"><a href=\"admin.php?op=BannerClientEdit&amp;cid=$cid\">"._EDIT."</a> | <a href=\"admin.php?op=BannerClientDelete&amp;cid=$cid\">"._DELETE."</a></td><tr>";
    }
    echo "</td></tr></table>";
    CloseTable();
    echo "<br>";
/* Add Banner */
    $result = mysql_query("select * from $prefix"._banner."client");
    $numrows = mysql_num_rows($result);
    if($numrows>0) {
	OpenTable();
	echo "<font size=\"3\"><b>"._ADDNEWBANNER."</b></font></center><br><br>"
	    ."<form action=\"admin.php?op=BannersAdd\" method=\"post\">"
	    .""._CLIENTNAME.":"
	    ."<select name=\"cid\">";
	$result = mysql_query("select cid, name from $prefix"._banner."client");
	while(list($cid, $name) = mysql_fetch_row($result)) {
	    echo "<option value=\"$cid\">$name</option>";
	}
	echo "</select><br>"
    	    .""._PURCHASEDIMPRESSIONS.": <input type=\"text\" name=\"imptotal\" size=\"12\" maxlength=\"11\"> 0 = "._UNLIMITED."<br>"
	    .""._IMAGEURL.": <input type=\"text\" name=\"imageurl\" size=\"50\" maxlength=\"100\"><br>"
	    .""._CLICKURL.": <input type=\"text\" name=\"clickurl\" size=\"50\" maxlength=\"200\"><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"BannersAdd\">"
	    ."<input type=\"submit\" value=\""._ADDBANNER."\">"
	    ."</form>";
	CloseTable();
    }
/* Add Client */
    OpenTable();
    echo"<font size=\"3\"><b>"._ADDCLIENT."</b></center><br><br>
	<form action=\"admin.php?op=BannersAddClient\" method=\"post\">
	"._CLIENTNAME.": <input type=\"text\" name=\"name\" size=\"30\" maxlength=\"60\"><br>
	"._CONTACTNAME.": <input type=\"text\" name=\"contact\" size=\"30\" maxlength=\"60\"><br>
	"._CONTACTEMAIL.": <input type=\"text\" name=\"email\" size=\"30\" maxlength=\"60\"><br>
	"._CLIENTLOGIN.": <input type=\"text\" name=\"login\" size=\"12\" maxlength=\"10\"><br>
	"._CLIENTPASSWD.": <input type=\"text\" name=\"passwd\" size=\"12\" maxlength=\"10\"><br><br>
	"._EXTRAINFO.":<br><textarea name=\"extrainfo\" cols=\"60\" rows=\"10\"></textarea><br>
	<input type=\"hidden\" name=\"op\" value=\"BannerAddClient\">
	<input type=\"submit\" value=\""._ADDCLIENT2."\">
	</form>";
    CloseTable();
    include ("footer.php");
}

function BannersAdd($name, $cid, $imptotal, $imageurl, $clickurl) {
    global $prefix;
    mysql_query("insert into $prefix"._banner." values (NULL, '$cid', '$imptotal', '1', '0', '$imageurl', '$clickurl', now())");
    Header("Location: admin.php?op=BannersAdmin#top");
}

function BannerAddClient($name, $contact, $email, $login, $passwd, $extrainfo) {
    global $prefix;
    mysql_query("insert into $prefix"._banner."client values (NULL, '$name', '$contact', '$email', '$login', '$passwd', '$extrainfo')");
    Header("Location: admin.php?op=BannersAdmin#top");
}

function BannerFinishDelete($bid) {
    global $prefix;
    mysql_query("delete from $prefix"._banner."finish where bid=$bid");
    Header("Location: admin.php?op=BannersAdmin#top");
}

function BannerDelete($bid, $ok=0) {
    global $prefix;
    if ($ok==1) {
        mysql_query("delete from $prefix"._banner." where bid='$bid'");
	Header("Location: admin.php?op=BannersAdmin#top");
    } else {
        include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._BANNERSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	$result=mysql_query("select cid, imptotal, impmade, clicks, imageurl, clickurl from $prefix"._banner." where bid=$bid");
	list($cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl) = mysql_fetch_row($result);
	OpenTable();
	echo "<center><b>"._DELETEBANNER."</b><br><br>"
	    ."<a href=\"$clickurl\"><img src=\"$imageurl\" border=\"1\" alt=\"\"></a><br>"
	    ."<a href=\"$clickurl\">$clickurl</a><br><br>"
	    ."<table width=\"100%\" border=\"0\"><tr>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._ID."<b></td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._IMPRESSIONS."<b></td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._IMPLEFT."<b></td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CLICKS."<b></td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CLICKSPERCENT."<b></td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CLIENTNAME."<b></td><tr>";
	$result2 = mysql_query("select cid, name from $prefix"._banner."client where cid=$cid");
	list($cid, $name) = mysql_fetch_row($result2);
	$percent = substr(100 * $clicks / $impmade, 0, 5);
	if($imptotal==0) {
	    $left = _UNLIMITED;
	} else {
	    $left = $imptotal-$impmade;
	}
	echo "<td bgcolor=\"$bgcolor2\" align=\"center\">$bid</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$impmade</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$left</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$clicks</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$percent%</td>"
	    ."<td bgcolor=\"$bgcolor2\" align=\"center\">$name</td><tr>";
    }
    echo "</td></tr></table><br>"
	.""._SURETODELBANNER."<br><br>"
	."[ <a href=\"admin.php?op=BannersAdmin#top\">"._NO."</a> | <a href=\"admin.php?op=BannerDelete&amp;bid=$bid&amp;ok=1\">"._YES."</a> ]</center><br><br>";
    CloseTable();
    include("footer.php");
}

function BannerEdit($bid) {
    global $prefix;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._BANNERSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result=mysql_query("select cid, imptotal, impmade, clicks, imageurl, clickurl from $prefix"._banner." where bid=$bid");
    list($cid, $imptotal, $impmade, $clicks, $imageurl, $clickurl) = mysql_fetch_row($result);
    OpenTable();
    echo"<font size=\"3\">"
	."<center><b>"._EDITBANNER."</b><br><br>"
	."<img src=\"$imageurl\" border=\"1\" alt=\"\"></center><br><br>"
	."<form action=\"admin.php?op=BannerChange\" method=\"post\">"
	.""._CLIENTNAME.": "
	."<select name=\"cid\">";
    $result = mysql_query("select cid, name from $prefix"._banner."client where cid=$cid");
    list($cid, $name) = mysql_fetch_row($result);
    echo "<option value=\"$cid\" selected>$name</option>";
    $result = mysql_query("select cid, name from $prefix"._banner."client");
    while(list($ccid, $name) = mysql_fetch_row($result)) {
	if($cid!=$ccid) {
	    echo "<option value=\"$ccid\">$name</option>";
	}
    }
    echo "</select><br>";
    if($imptotal==0) {
        $impressions = _UNLIMITED;
    } else {
        $impressions = $imptotal;
    }
    echo ""._ADDIMPRESSIONS.": <input type=\"text\" name=\"impadded\" size=\"12\" maxlength=\"11\"> "._PURCHASED."<b>$impressions</b> "._MADE.": <b>$impmade</b><br>"
	.""._IMAGEURL.": <input type=\"text\" name=\"imageurl\" size=\"50\" maxlength=\"60\" value=\"$imageurl\"><br>"
	.""._CLICKURL.": <input type=\"text\" name=\"clickurl\" size=\"50\" maxlength=\"100\" value=\"$clickurl\"><br>"
	."<input type=\"hidden\" name=\"bid\" value=\"$bid\">"
	."<input type=\"hidden\" name=\"imptotal\" value=\"$imptotal\">"
	."<input type=\"hidden\" name=\"op\" value=\"BannerChange\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function BannerChange($bid, $cid, $imptotal, $impadded, $imageurl, $clickurl) {
    global $prefix;
    $imp = $imptotal+$impadded;
    mysql_query("update $prefix"._banner." set cid='$cid', imptotal='$imp', imageurl='$imageurl', clickurl='$clickurl' where bid=$bid");
    Header("Location: admin.php?op=BannersAdmin#top");
}

function BannerClientDelete($cid, $ok=0) {
    global $prefix;
    if ($ok==1) {
        mysql_query("delete from $prefix"._banner." where cid='$cid'");
	mysql_query("delete from $prefix"._banner."client where cid='$cid'");
	Header("Location: admin.php?op=BannersAdmin#top");
    } else {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._BANNERSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	$result=mysql_query("select cid, name from $prefix"._banner."client where cid=$cid");
	list($cid, $name) = mysql_fetch_row($result);
	OpenTable();
	echo "<center><b>"._DELETECLIENT.": $name</b><br><br>
	    "._SURETODELCLIENT."<br><br>";
	$result2 = mysql_query("select imageurl, clickurl from $prefix"._banner." where cid=$cid");
	$numrows = mysql_num_rows($result2);
	if($numrows==0) {
	    echo ""._CLIENTWITHOUTBANNERS."<br><br>";
	} else {
	    echo "<b>"._WARNING."!!!</b><br>
		"._DELCLIENTHASBANNERS.":<br><br>";
	}
	while(list($imageurl, $clickurl) = mysql_fetch_row($result2)) {
	    echo "<a href=\"$clickurl\"><img src=\"$imageurl\" border=\"1\" alt=\"\"></a><br>
		<a href=\"$clickurl\">$clickurl</a><br><br>";
	}
    }
    echo ""._SURETODELCLIENT."<br><br>
	[ <a href=\"admin.php?op=BannersAdmin#top\">"._NO."</a> | <a href=\"admin.php?op=BannerClientDelete&amp;cid=$cid&amp;ok=1\">"._YES."</a> ]</center><br><br></center>";
    CloseTable();
    include("footer.php");
}

function BannerClientEdit($cid) {
    global $prefix;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._BANNERSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = mysql_query("select name, contact, email, login, passwd, extrainfo from $prefix"._banner."client where cid=$cid");
    list($name, $contact, $email, $login, $passwd, $extrainfo) = mysql_fetch_row($result);
    OpenTable();
    echo "<center><font size=\"3\"><b>"._EDITCLIENT."</b></font></center><br><br>"
	."<form action=\"admin.php?op=BannerClientChange\" method=\"post\">"
	.""._CLIENTNAME.": <input type=\"text\" name=\"name\" value=\"$name\" size=\"30\" maxlength=\"60\"><br>"
	.""._CONTACTNAME.": <input type=\"text\" name=\"contact\" value=\"$contact\" size=\"30\" maxlength=\"60\"><br>"
	.""._CONTACTEMAIL.": <input type=\"text\" name=\"email\" size=30 maxlength=\"60\" value=\"$email\"><br>"
	.""._CLIENTLOGIN.": <input type=\"text\" name=\"login\" size=12 maxlength=\"10\" value=\"$login\"><br>"
	.""._CLIENTPASSWD.": <input type=\"text\" name=\"passwd\" size=12 maxlength=\"10\" value=\"$passwd\"><br><br>"
	.""._EXTRAINFO."<br><textarea name=\"extrainfo\" cols=\"60\" rows=\"10\">$extrainfo</textarea><br>"
	."<input type=\"hidden\" name=\"cid\" value=\"$cid\">"
	."<input type=\"hidden\" name=\"op\" value=\"BannerClientChange\">"
	."<input type=\"submit\" value=\""._SAVECHANGES."\">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function BannerClientChange($cid, $name, $contact, $email, $extrainfo, $login, $passwd) {
    global $prefix;
    mysql_query("update $prefix"._banner."client set name='$name', contact='$contact', email='$email', login='$login', passwd='$passwd' where cid=$cid");
    Header("Location: admin.php?op=BannersAdmin#top");
}

switch($op) {

    case "BannersAdmin":
    BannersAdmin();
    break;

    case "BannersAdd":
    BannersAdd($name, $cid, $imptotal, $imageurl, $clickurl);
    break;

    case "BannerAddClient":
    BannerAddClient($name, $contact, $email, $login, $passwd, $extrainfo);
    break;

    case "BannerFinishDelete":
    BannerFinishDelete($bid);
    break;

    case "BannerDelete":
    BannerDelete($bid, $ok);
    break;

    case "BannerEdit":
    BannerEdit($bid);
    break;
		
    case "BannerChange":
    BannerChange($bid, $cid, $imptotal, $impadded, $imageurl, $clickurl);
    break;

    case "BannerClientDelete":
    BannerClientDelete($cid, $ok);
    break;

    case "BannerClientEdit":
    BannerClientEdit($cid);
    break;

    case "BannerClientChange":
    BannerClientChange($cid, $name, $contact, $email, $extrainfo, $login, $passwd);
    break;

}

} else {
    echo "Access Denied";
}

?>