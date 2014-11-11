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

include("config.php");

if (isset($newlang)) {
    if (file_exists("language/lang-$newlang.php")) {
	setcookie("lang",$newlang,time()+31536000);
	include("language/lang-$newlang.php");
    } else {
	setcookie("lang",$language,time()+31536000);
	include("language/lang-$language.php");
    }
} elseif (isset($lang)) {
    include("language/lang-$lang.php");
} else {
    setcookie("lang",$language,time()+31536000);
    include("language/lang-$language.php");
}

$mainfile = 1;
mysql_connect($dbhost, $dbuname, $dbpass);
@mysql_select_db("$dbname") or die ("Unable to select database");

function is_admin($admin) {
    global $prefix;
    if(!is_array($admin)) {
	$admin = base64_decode($admin);
	$admin = explode(":", $admin);
        $aid = "$admin[0]";
	$pwd = "$admin[1]";
    } else {
        $aid = "$admin[0]";
	$pwd = "$admin[1]";
    }
    $result=mysql_query("select pwd from $prefix"._authors." where aid='$aid'");
    list($pass)=mysql_fetch_row($result);
    if($pass == $pwd && $pass != "") {
	return 1;
    }
    return 0;    
}

function is_user($user) {
    global $prefix;
    if(!is_array($user)) {
	$user = base64_decode($user);
	$user = explode(":", $user);
        $uid = "$user[0]";
	$pwd = "$user[2]";
    } else {
        $uid = "$user[0]";
	$pwd = "$user[2]";
    }
    $result=mysql_query("select pass from $prefix"._users." where uid='$uid'");
    list($pass)=mysql_fetch_row($result);
    if($pass == $pwd && $pass != "") {
	return 1;
    }
    return 0;
}

function blocks($side) {
    global $storynum, $prefix;
    if (strtolower($side[0]) == "l") {
	$pos = "l";
    } elseif (strtolower($side[0]) == "r") {
	$pos = "r";
    }
    $result = mysql_query("select bid, bkey, title, content, url from $prefix"._blocks." where position='$pos' AND active='1' order by weight ASC");
    while(list($bid, $bkey, $title, $content, $url) = mysql_fetch_row($result)) {
	if ($bkey == main) {
	    mainblock();
	} elseif ($bkey == online) {
	    online();
	} elseif ($bkey == admin) {
	    adminblock();
	} elseif ($bkey == poll) {
	    pollNewest();
	} elseif ($bkey == past) {
	    oldNews($storynum);
	} elseif ($bkey == big) {
	    bigstory();
	} elseif ($bkey == login) {
	    loginbox();
	} elseif ($bkey == search) {
	    searchbox();
	} elseif ($bkey == category) {
	    category();
	} elseif ($bkey == ephem) {
	    ephemblock();
	} elseif ($bkey == random) {
	    randombox();
	} elseif ($bkey == userbox) {
	    userblock();
	} elseif ($bkey == thelang) {
	    selectlanguage();
	} elseif ($bkey == "") {
	    if ($url == "") {
		themesidebox($title, $content);
	    } else {
		headlines($bid);
	    }
	}
    }
}

function MessageBox() {
    global $bgcolor1, $bgcolor2, $user, $admin, $cookie, $textcolor2, $prefix;
    $result = mysql_query("select title, content, date, expire, view from $prefix"._message." where active='1'");
    if (mysql_num_rows($result) == 0) {
	return;
    } else {
	list($title, $content, $mdate, $expire, $view) = mysql_fetch_row($result);
	if ($title != "" && $content != "") {
	    if ($expire == 0) {
		$remain = _UNLIMITED;
	    } else {
		$etime = (($mdate+$expire)-time())/3600;
		$etime = (int)$etime;
		if ($etime < 1) {
		    $remain = _EXPIRELESSHOUR;
		} else {
		    $remain = ""._EXPIREIN." $etime "._HOURS."";
		}
	    }
	    if ($view == 4 AND is_admin($admin)) {
		echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>\n"
		    ."<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"$bgcolor1\"><tr><td bgcolor=\"$bgcolor2\">\n"
		    ."<center><font size=\"3\" color=\"$textcolor2\"><b>$title</b></font></center></td></tr>\n"
		    ."<tr><td><font size=\"2\">$content</font>"
		    ."<br><br><center><font size=\"2\">[ "._MVIEWADMIN." - $remain - <a href=\"admin.php?op=messages\">"._EDIT."</a> ]</font></center>"
		    ."</td></tr></table></td></tr></table>";
		echo "<br>";
	    } elseif ($view == 3 AND is_user($user) || is_admin($admin)) {
		echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>\n"
		    ."<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"$bgcolor1\"><tr><td bgcolor=\"$bgcolor2\">\n"
		    ."<center><font size=\"3\" color=\"$textcolor2\"><b>$title</b></font></center></td></tr>\n"
		    ."<tr><td><font size=\"2\">$content</font>";
		if (is_admin($admin)) {
		    echo "<br><br><center><font size=\"2\">[ "._MVIEWUSERS." - $remain - <a href=\"admin.php?op=messages\">"._EDIT."</a> ]</font></center>";
		}
    		echo "</td></tr></table></td></tr></table>";
		echo "<br>";
	    } elseif ($view == 2 AND !is_user($user) || is_admin($admin)) {
		echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>\n"
		    ."<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"$bgcolor1\"><tr><td bgcolor=\"$bgcolor2\">\n"
		    ."<center><font size=\"3\" color=\"$textcolor2\"><b>$title</b></font></center></td></tr>\n"
		    ."<tr><td><font size=\"2\">$content</font>";
		if (is_admin($admin)) {
		    echo "<br><br><center><font size=\"2\">[ "._MVIEWANON." - $remain - <a href=\"admin.php?op=messages\">"._EDIT."</a> ]</font></center>";
		}
		echo "</td></tr></table></td></tr></table>";
		echo "<br>";    
	    } elseif ($view == 1) {
		echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>\n"
		    ."<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" bgcolor=\"$bgcolor1\"><tr><td bgcolor=\"$bgcolor2\">\n"
		    ."<center><font size=\"3\" color=\"$textcolor2\"><b>$title</b></font></center></td></tr>\n"
		    ."<tr><td><font size=\"2\">$content</font>";
		if (is_admin($admin)) {
		    echo "<br><br><center><font size=\"2\">[ "._MVIEWALL." - $remain - <a href=\"admin.php?op=messages\">"._EDIT."</a> ]</font></center>";
		}
		echo "</td></tr></table></td></tr></table>";
		echo "<br>";    
	    }
	    if ($expire != 0) {
	    	$past = time()-$expire;
		if ($mdate < $past) {
		    $result = mysql_query("update $prefix"._message." set active='0'");
		}
	    }
	}
    }
}

function selectlanguage() {
    $title = _SELECTLANGUAGE;
    $content = "<center><font size=\"2\">"._SELECTGUILANG."<br><br>";
    $langdir = dir("language");
    while($func=$langdir->read()) {
	if(substr($func, 0, 5) == "lang-") {
    	    $menulist .= "$func ";
	}
    }
    closedir($langdir->handle);
    $menulist = explode(" ", $menulist);
    sort($menulist);
    for ($i=0; $i < sizeof($menulist); $i++) {
      for ($three=0; $three < 3; $three++) {
        if($menulist[$i]!="") {
	    $tl = ereg_replace("lang-","",$menulist[$i]);
	    $tl = ereg_replace(".php","",$tl);
	    $altlang = ucfirst($tl);
	    $content .= "<a href=\"index.php?newlang=$tl\"><img src=\"images/language/flag-$tl.png\" border=\"0\" alt=\"$altlang\" hspace=\"3\" vspace=\"3\"></a>";
	$i++;
	}
    }
	$content .= "<br>";
	}
    $content .= "</font></center>";
    themesidebox($title, $content);
}

function randombox() {
    global $prefix;
    $result = mysql_query("select * from $prefix"._topics."");
    $numrows = mysql_num_rows($result);
    $numrows = $numrows-1;
    mt_srand((double)microtime()*1000000);
    $topic = mt_rand(0, $numrows);
    $result = mysql_query("select sid, title from $prefix"._stories." where topic='$topic' order by sid DESC limit 0,9");
    $content = "<font size=\"2\">";
    $res = mysql_query("select topictext from $prefix"._topics." where topicid='$topic'");
    list($topictext) = mysql_fetch_row($res);
    $title2 = "<a href=\"search.php?topic=$topic\">$topictext</a>";
    while(list($sid, $title) = mysql_fetch_row($result)) {
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"article.php?sid=$sid\">$title</a><br>";
    }
    $content .= "</font>";
    themesidebox($title2, $content);
}

function ultramode() {
    global $prefix;
    $ultra = "ultramode.txt";
    $file = fopen("$ultra", "w");
    fwrite($file, "General purpose self-explanatory file with news headlines\n");
    $rfile=mysql_query("select sid, aid, title, time, comments, topic from $prefix"._stories." order by time DESC limit 0,10");
    while(list($sid, $aid, $title, $time, $comments, $topic) = mysql_fetch_row($rfile)) {
	$rfile2=mysql_query("select topictext, topicimage from $prefix"._topics." where topicid=$topic");
	list($topictext, $topicimage) = mysql_fetch_row($rfile2);
	$content = "%%\n$title\n/article.php?sid=$sid\n$time\n$aid\n$topictext\n$comments\n$topicimage\n";
	fwrite($file, $content);
    }
    fclose($file);
}

function cookiedecode($user) {
    global $cookie, $prefix;
    $user = base64_decode($user);
    $cookie = explode(":", $user);
    $result = mysql_query("select pass from $prefix"._users." where uname='$cookie[1]'");
    list($pass) = mysql_fetch_row($result);
    if ($cookie[2] == $pass && $pass != "") {
	return $cookie;
    } else {
	unset($user);
	unset($cookie);
    }
}

function getusrinfo($user) {
    global $userinfo, $prefix;
    $user2 = base64_decode($user);
    $user3 = explode(":", $user2);
    $result = mysql_query("select uid, name, uname, email, femail, url, user_avatar, user_icq, user_occ, user_from, user_intrest, user_sig, user_viewemail, user_theme, user_aim, user_yim, user_msnm, pass, storynum, umode, uorder, thold, noscore, bio, ublockon, ublock, theme, commentmax from $prefix"._users." where uname='$user3[1]' and pass='$user3[2]'");
    if(mysql_num_rows($result)==1) {
    	$userinfo = mysql_fetch_array($result);
    } else {
        echo "<b>"._MPROBLEM."</b><br>";
    }
    return $userinfo;
}

function searchbox() {
    $title = ""._SEARCH."";
    $content = "<form action=\"search.php\" method=\"get\">";
    $content .= "<br><center><input type=\"text\" name=\"query\" size=\"14\"></center>";
    $content .= "</form>";
    themesidebox($title, $content);
}

function FixQuotes ($what = "") {
	$what = ereg_replace("'","''",$what);
	while (eregi("\\\\'", $what)) {
		$what = ereg_replace("\\\\'","'",$what);
	}
	return $what;
}

/*********************************************************/
/* text filter                                           */
/*********************************************************/

function check_words($Message) {
    global $EditedMessage;
    include("config.php");
    $EditedMessage = $Message;
    if ($CensorMode != 0) {
	
	if (is_array($CensorList)) {
	    $Replace = $CensorReplace;
	    if ($CensorMode == 1) {
		for ($i = 0; $i < count($CensorList); $i++) {
		    $EditedMessage = eregi_replace("$CensorList[$i]([^a-zA-Z0-9])","$Replace\\1",$EditedMessage);
		}
	    } elseif ($CensorMode == 2) {
		for ($i = 0; $i < count($CensorList); $i++) {
		    $EditedMessage = eregi_replace("(^|[^[:alnum:]])$CensorList[$i]","\\1$Replace",$EditedMessage);
		}
	    } elseif ($CensorMode == 3) {
		for ($i = 0; $i < count($CensorList); $i++) {				
		    $EditedMessage = eregi_replace("$CensorList[$i]","$Replace",$EditedMessage);
		}
	    }
	}
    }
    return ($EditedMessage);
}

function delQuotes($string){ 
    /* no recursive function to add quote to an HTML tag if needed */
    /* and delete duplicate spaces between attribs. */
    $tmp="";    # string buffer
    $result=""; # result string
    $i=0;
    $attrib=-1; # Are us in an HTML attrib ?   -1: no attrib   0: name of the attrib   1: value of the atrib
    $quote=0;   # Is a string quote delimited opened ? 0=no, 1=yes
    $len = strlen($string);
    while ($i<$len) {
	switch($string[$i]) { # What car is it in the buffer ?
	    case "\"": #"       # a quote. 
		if ($quote==0) {
		    $quote=1;
		} else {
		    $quote=0;
		    if (($attrib>0) && ($tmp != "")) { $result .= "=\"$tmp\""; }
		    $tmp="";
		    $attrib=-1;
		}
		break;
	    case "=":           # an equal - attrib delimiter
		if ($quote==0) {  # Is it found in a string ?
		    $attrib=1;
		    if ($tmp!="") $result.=" $tmp";
		    $tmp="";
		} else $tmp .= '=';
		break;
	    case " ":           # a blank ?
		if ($attrib>0) {  # add it to the string, if one opened.
		    $tmp .= $string[$i];
		}
		break;
	    default:            # Other
		if ($attrib<0)    # If we weren't in an attrib, set attrib to 0
		$attrib=0;
		$tmp .= $string[$i];
		break;
	}
	$i++;
    }
    if (($quote!=0) && ($tmp != "")) {
	if ($attrib==1) $result .= "="; 
	/* If it is the value of an atrib, add the '=' */
	$result .= "\"$tmp\"";  /* Add quote if needed (the reason of the function ;-) */
    }
    return $result;
}

function check_html ($str, $strip="") {
    /* The core of this code has been lifted from phpslash */
    /* which is licenced under the GPL. */
    include("config.php");
    if ($strip == "nohtml")
    	$AllowableHTML=array('');
	$str = stripslashes($str);
	$str = eregi_replace("<[[:space:]]*([^>]*)[[:space:]]*>",
                         '<\\1>', $str);
               // Delete all spaces from html tags .
	$str = eregi_replace("<a[^>]*href[[:space:]]*=[[:space:]]*\"?[[:space:]]*([^\" >]*)[[:space:]]*\"?[^>]*>",
                         '<a href="\\1">', $str); # "
               // Delete all attribs from Anchor, except an href, double quoted.
	$str = eregi_replace("<img?",
                         '', $str); # "
	$tmp = "";
	while (ereg("<(/?[[:alpha:]]*)[[:space:]]*([^>]*)>",$str,$reg)) {
		$i = strpos($str,$reg[0]);
		$l = strlen($reg[0]);
		if ($reg[1][0] == "/") $tag = strtolower(substr($reg[1],1));
		else $tag = strtolower($reg[1]);  
		if ($a = $AllowableHTML[$tag])
			if ($reg[1][0] == "/") $tag = "</$tag>";
			elseif (($a == 1) || ($reg[2] == "")) $tag = "<$tag>";
			else {
			  # Place here the double quote fix function.
			  $attrb_list=delQuotes($reg[2]);
			  $tag = "<$tag" . $attrb_list . ">";
			} # Attribs in tag allowed
		else $tag = "";
		$tmp .= substr($str,0,$i) . $tag;
		$str = substr($str,$i+$l);
	}
	$str = $tmp . $str;
	return $str;
	exit;
	/* Squash PHP tags unconditionally */
	$str = ereg_replace("<\?","",$str);
	return $str;
}

function filter_text($Message, $strip="") {
    global $EditedMessage;
    check_words($Message);
    $EditedMessage=check_html($EditedMessage, $strip);
    return ($EditedMessage);
}

/*********************************************************/
/* formatting stories                                    */
/*********************************************************/

function formatTimestamp($time) {
    global $datetime, $locale;
    setlocale ("LC_TIME", "$locale");
    ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime);
    $datetime = strftime(""._DATESTRING."", mktime($datetime[4],$datetime[5],$datetime[6],$datetime[2],$datetime[3],$datetime[1]));
    $datetime = ucfirst($datetime);
    return($datetime);
}

function formatAidHeader($aid) {
    global $prefix;
    $holder = mysql_query("SELECT url, email FROM $prefix"._authors." where aid='$aid'");
    if (!$holder) {
	echo mysql_errno(). ": ".mysql_error(). "<br>";
	exit();
    }
    list($url, $email) = mysql_fetch_row($holder);
    if (isset($url)) {
	echo "<a href=\"$url\">$aid</a>";
    } elseif (isset($email)) {
	echo "<a href=\"mailto:$email\">$aid</a>";
    } else {
	echo $aid;
    }
}

function oldNews($storynum) {
    global $locale, $oldnum, $storyhome, $cookie, $categories, $cat, $prefix;
    $storynum = $storyhome;
    $boxstuff = "<font size=\"2\">";
    $boxTitle = _PASTARTICLES;
    if ($categories == 1) {
	$sel = "where catid='$cat'";
    } else {
	$sel = "";
    }
    $result = mysql_query("select sid, title, time, comments from $prefix"._stories." $sel order by time desc limit $storynum, $oldnum");
    $vari = 0;
    if (isset($cookie[4])) {
	$options .= "&amp;mode=$cookie[4]";
    } else {
	$options .= "&amp;mode=thread";
    }
    if (isset($cookie[5])) {
	$options .= "&amp;order=$cookie[5]";
    } else {
	$options .= "&amp;order=0";
    }
    if (isset($cookie[6])) {
	$options .= "&amp;thold=$cookie[6]";
    } else {
	$options .= "&amp;thold=0";
    }
    while(list($sid, $title, $time, $comments) = mysql_fetch_row($result)) {
	$see = 1;
	setlocale ("LC_TIME", "$locale");
	ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $datetime2);
	$datetime2 = strftime(""._DATESTRING2."", mktime($datetime2[4],$datetime2[5],$datetime2[6],$datetime2[2],$datetime2[3],$datetime2[1]));
	$datetime2 = ucfirst($datetime2);
	if($time2==$datetime2) {
	    $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"article.php?sid=$sid$options\">$title</a> ($comments)<br>\n";
	} else {
	    if($a=="") {
		$boxstuff .= "<b>$datetime2</b><br><br><strong><big>&middot;</big></strong>&nbsp;<a href=\"article.php?sid=$sid$options\">$title</a> ($comments)<br>\n";
		$time2 = $datetime2;
		$a = 1;
	    } else {
		$boxstuff .= "<br><br><b>$datetime2</b><br><br><strong><big>&middot;</big></strong>&nbsp;<a href=\"article.php?sid=$sid$options\">$title</a> ($comments)<br>\n";
		$time2 = $datetime2;
	    }
	}
	$vari++;
	if ($vari==$oldnum) {
	    if (isset($cookie[3])) {
		$storynum = $cookie[3];
	    } else {
		$storynum = $storyhome;
	    }
	    $min = $oldnum + $storynum;
	    $boxstuff .= "<br><br><a href=\"search.php?min=$min&amp;type=stories&amp;category=$cat\"><b>"._OLDERARTICLES."</b></a>\n";
	}
    }
    $boxstuff .= "</font>";
    if ($see == 1) {
	themesidebox($boxTitle, $boxstuff);
    }
}

function themepreview($title, $hometext, $bodytext="", $notes="") {
    echo "<b>$title</b><br><br>$hometext";
    if ($bodytext != "") {
	echo "<br><br>$bodytext";
    }
    if ($notes != "") {
	echo "<br><br><b>"._NOTE."</b> <i>$notes</i>";
    }
}

function mainblock() {
    global $prefix;
    $result = mysql_query("select title, content from $prefix"._blocks." where bkey='main'");
    while(list($title, $content) = mysql_fetch_array($result)) {
	$content = "<font size=\"2\">$content";
	if(isset($name)) {
	} else {
	    $handle=opendir('modules');
	    while ($file = readdir($handle)) {
		if ( (!ereg("[.]",$file)) ) {
		    $moduleslist .= "$file ";
		}
	    }
	    closedir($handle);
	    $moduleslist = explode(" ", $moduleslist);
	    sort($moduleslist);
	    for ($i=0; $i < sizeof($moduleslist); $i++) {
		if ($moduleslist[$i]!="") {
		    $dummy = ereg("NS-",$moduleslist[$i]);
		    if ($dummy == "") {
		    $xname = ereg_replace("_", " ", "$moduleslist[$i]");
    		    if ($file == "") {
			$file = "index";
		    }
		    if ($a == 0) {
			$content .= "<br><b>"._OTHEROPTIONS."</b><br>";
		    }
		    $content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"modules.php?op=modload&amp;name=$moduleslist[$i]&amp;file=$file\">$xname</a><br>";
		    $a = 1;
		    }
    		}
	    }
	}
	$content .= "</font>";
	themesidebox($title, $content);
    }
}

function category() {
    global $cat, $language, $prefix;
    $result = mysql_query("select catid, title from $prefix"._stories."_cat order by title");
    $numrows = mysql_num_rows($result);
    if ($numrows == 0) {
	return;
    } else {
	$boxstuff = "<font size=\"2\">";
	while(list($catid, $title) = mysql_fetch_row($result)) {
	    $result2 = mysql_query("select * from $prefix"._stories." where catid='$catid'");
	    $numrows = mysql_num_rows($result2);
	    if ($numrows > 0) {
		$res = mysql_query("select time from $prefix"._stories." where catid='$catid' order by sid DESC limit 0,1");
		list($time) = mysql_fetch_row($res);
		ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $dat);
		if ($cat == $catid) {
		    $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<b>$title</b><br>";
		} else {
		    $boxstuff .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"categories.php?op=newindex&amp;catid=$catid\">$title</a> <font size=1>($dat[2]/$dat[3])</font><br>";
		}
	    }
	}
	$boxstuff .= "</font>";
	$title = _CATEGORIES;
	themesidebox($title, $boxstuff);
    }
}

function adminblock() {
    global $admin, $prefix;
    if (is_admin($admin)) {
	$result = mysql_query("select title, content from $prefix"._blocks." where bkey='admin'");
	while(list($title, $content) = mysql_fetch_array($result)) {
	    $content = "<font size=\"2\">$content</font>";
	    themesidebox($title, $content);
	}
	$title = ""._WAITINGCONT."";
	$result = mysql_query("select * from $prefix"._queue."");
	$num = mysql_num_rows($result);
	$content = "<font size=\"2\">";
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=submissions\">"._SUBMISSIONS."</a>: $num<br>";
	$result = mysql_query("select * from $prefix"._reviews."_add");
	$num = mysql_num_rows($result);
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=reviews\">"._WREVIEWS."</a>: $num<br>";
	$result = mysql_query("select * from $prefix"._links_newlink."");
	$num = mysql_num_rows($result);
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=links\">"._WLINKS."</a>: $num<br>";
	$result = mysql_query("select * from $prefix"._downloads_newdownload."");
	$num = mysql_num_rows($result);
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=downloads\">"._UDOWNLOADS."</a>: $num<br>";
	$result = mysql_query("select * from $prefix"._events_queue."");
	$num = mysql_num_rows($result);
	$content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"admin.php?op=CalendarAdmin\">"._UCALENDARS."</a>: $num<br></font>";
	themesidebox($title, $content);
    }
}

function ephemblock() {
    global $prefix;
    $today = getdate();
    $eday = $today[mday];
    $emonth = $today[mon];
    $result = mysql_query("select yid, content from $prefix"._ephem." where did='$eday' AND mid='$emonth'");
    $title = ""._EPHEMERIDS."";
    $boxstuff = "<b>"._ONEDAY."</b><br>";
    while(list($yid, $content) = mysql_fetch_array($result)) {
        if ($cnt==1) {
    	    $boxstuff .= "<br><br>";
	}
	$boxstuff .= "<b>$yid</b><br>";
    	$boxstuff .= "$content";
	$cnt = 1;
    }
    themesidebox($title, $boxstuff);
}

function loginbox() {
    global $user;
    if (!is_user($user)) {
	$title = _LOGIN;
	$boxstuff = "<form action=\"user.php\" method=\"post\">";
	$boxstuff .= "<center><font size=\"2\">"._NICKNAME."<br>";
	$boxstuff .= "<input type=\"text\" name=\"uname\" size=\"12\" maxlength=\"25\"><br>";
	$boxstuff .= ""._PASSWORD."<br>";
	$boxstuff .= "<input type=\"password\" name=\"pass\" size=\"12\" maxlength=\"20\"><br>";
	$boxstuff .= "<input type=\"hidden\" name=\"op\" value=\"login\">";
	$boxstuff .= "<input type=\"submit\" value=\""._LOGIN."\"></font></center></form>";
	$boxstuff .= "<center><font size=\"2\">"._ASREGISTERED."</font></center>";
	themesidebox($title, $boxstuff);
    }
}

function userblock() {
    global $user, $cookie, $prefix;
    if((is_user($user)) AND ($cookie[8])) {
	$getblock = mysql_query("select ublock from $prefix"._users." where uid='$cookie[0]'");
	$title = ""._MENUFOR." $cookie[1]";
	list($ublock) = mysql_fetch_row($getblock);
	themesidebox($title, $ublock);
    }
}

/*********************************************************/
/* poll functions                                        */
/*********************************************************/

function pollMain($pollID) {
    global $boxTitle, $boxContent, $pollcomm, $user, $cookie, $prefix;
    if(!isset($pollID))
	$pollID = 1;
    if(!isset($url))
	$url = sprintf("pollBooth.php?op=results&amp;pollID=%d", $pollID);
    $boxContent .= "<form action=\"pollBooth.php\" method=\"post\">";
    $boxContent .= "<input type=\"hidden\" name=\"pollID\" value=\"".$pollID."\">";
    $boxContent .= "<input type=\"hidden\" name=\"forwarder\" value=\"".$url."\">";
    $result = mysql_query("SELECT pollTitle, voters FROM $prefix"._poll_desc." WHERE pollID=$pollID");
    list($pollTitle, $voters) = mysql_fetch_row($result);
    $boxTitle = _SURVEY;
    $boxContent .= "<font size=\"2\"><b>$pollTitle</b></font><br><br>\n";
    for($i = 1; $i <= 12; $i++) {
	$result = mysql_query("SELECT pollID, optionText, optionCount, voteID FROM $prefix"._poll_data." WHERE (pollID=$pollID) AND (voteID=$i)");
	$object = mysql_fetch_object($result);
	if(is_object($object)) {
	    $optionText = $object->optionText;
	    if($optionText != "") {
		$boxContent .= "<input type=\"radio\" name=\"voteID\" value=\"".$i."\"> <font size=\"2\">$optionText</font> <br>\n";
	    }
	}
    }
    $boxContent .= "<br><center><font size=\"2\"><input type=\"submit\" value=\""._VOTE."\"></font><br>";

    if (is_user($user)) {
        cookiedecode($user);
    }
    $result = mysql_query("SELECT SUM(optionCount) AS SUM FROM $prefix"._poll_data." WHERE pollID=$pollID");
    $sum = (int)mysql_result($result, 0, "SUM");
    $boxContent .= "<font size=\"2\">[ <a href=\"pollBooth.php?op=results&amp;pollID=$pollID&amp;mode=$cookie[4]&amp;order=$cookie[5]&amp;thold=$cookie[6]\"><b>"._RESULTS."</b></a> | <a href=\"pollBooth.php\"><b>"._POLLS."</b></a> ]<br>";

    if ($pollcomm) {
	list($numcom) = mysql_fetch_row(mysql_query("select count(*) from $prefix"._pollcomments." where pollID=$pollID"));
	$boxContent .= "<br>"._VOTES.": <b>$sum</b> | "._PCOMMENTS." <b>$numcom</b>\n\n";
    } else {
        $boxContent .= "<br>"._VOTES." <b>$sum</b>\n\n";
    }
    $boxContent .= "</font></center></form>\n\n";
    themesidebox($boxTitle, $boxContent);
}

function pollLatest() {
    global $prefix;
    $result = mysql_query("SELECT pollID FROM $prefix"._poll_data." ORDER BY pollID DESC LIMIT 1");
    $pollID = mysql_fetch_row($result);
    return($pollID[0]);
}

function pollNewest() {
    $pollID = pollLatest();
    pollMain($pollID);
}

function pollCollector($pollID, $voteID, $forwarder) {
    global $setCookies, $cookiePrefix, $HTTP_COOKIE_VARS, $prefix;
    /* Fix for lamers that like to cheat on polls */
    $ip = getenv("REMOTE_ADDR");
    $past = time()-1800;
    mysql_query("DELETE FROM $prefix"._poll_check." WHERE time < $past");
    $result = mysql_query("SELECT ip FROM $prefix"._poll_check." WHERE ip='$ip'");
    list($ips) = mysql_fetch_row($result);
    $ctime = time();
    if ($ip == $ips) {
	$voteValid = 0;
    } else {
	mysql_query("INSERT INTO $prefix"._poll_check." (ip, time) VALUES ('$ip', '$ctime')");
	$voteValid = "1";
    }
    /* Fix end */
    
    if($setCookies>0) {
	/* we have to check for cookies, so get timestamp of this poll */
	$result = mysql_query("SELECT timeStamp FROM $prefix"._poll_desc." WHERE pollID=$pollID");
	$object = mysql_fetch_object($result);
	$timeStamp = $object->timeStamp;
	$cookieName = $cookiePrefix.$timeStamp;
	/* check if cookie exists */
	if($HTTP_COOKIE_VARS["$cookieName"] == "1") {
	    /* cookie exists, invalidate this vote */
	    $warn = "You already voted today!";
	    $voteValid = "0";
	} else {
	    /* cookie does not exist yet, set one now */
	    $cvalue = "1";
	    setcookie("$cookieName",$cvalue,time()+86400);
	}
    }
    /* update database if the vote is valid */
    if($voteValid>0) {
        @mysql_query("UPDATE $prefix"._poll_data." SET optionCount=optionCount+1 WHERE (pollID=$pollID) AND (voteID=$voteID)");
        @mysql_query("UPDATE $prefix"._poll_desc." SET voters=voters+1 WHERE pollID=$pollID");
        Header("Location: $forwarder");
    } else {
        Header("Location: $forwarder");
    }
    /* a lot of browsers can't handle it if there's an empty page */
    echo "<html><head></head><body></body></html>";
}

function pollList() {
    global $user, $cookie, $prefix;
    $result = mysql_query("SELECT pollID, pollTitle, timeStamp, voters FROM $prefix"._poll_desc." ORDER BY timeStamp"); 
    $counter = 0;
    OpenTable();
    OpenTable();
    echo "<center><font size=\"4\"><b>"._PASTSURVEYS."</b></font></center>";
    CloseTable();
    echo "<br>";
    echo "<table border=\"0\" cellpadding=\"8\"><tr><td>";
    echo "<font size=\"3\">";
    while($object = mysql_fetch_object($result)) {
	$resultArray[$counter] = array($object->pollID, $object->pollTitle, $object->timeStamp, $object->voters);
	$counter++;
    }
    for ($count = 0; $count < count($resultArray); $count++) {
	$id = $resultArray[$count][0];
	$pollTitle = $resultArray[$count][1];
	$voters = $resultArray[$count][3];
	$result2 = mysql_query("SELECT SUM(optionCount) AS SUM FROM $prefix"._poll_data." WHERE pollID=$id");
	$sum = (int)mysql_result($result2, 0, "SUM");
	echo "<strong><big>&middot;</big></strong>&nbsp;<a href=\"pollBooth.php?pollID=$id\">$pollTitle</a> ";
	echo "(<a href=\"pollBooth.php?op=results&amp;pollID=$id&amp;mode=$cookie[4]&amp;order=$cookie[5]&amp;thold=$cookie[6]\">"._RESULTS."</a> - $sum "._LVOTES.")<br>\n";
    }
    echo "</td></tr></table>";
    CloseTable();
}

function pollResults($pollID) {
    global $BarScale, $resultTableBgColor, $resultBarFile, $setCookies, $Default_Theme, $user, $cookie, $prefix;
    if(!isset($pollID)) $pollID = 1;
    $result = mysql_query("SELECT pollID, pollTitle, timeStamp FROM $prefix"._poll_desc." WHERE pollID=$pollID");
    $holdtitle = mysql_fetch_row($result);
    echo "<br><b>$holdtitle[1]</b><br><br>";
    mysql_free_result($result);
    $result = mysql_query("SELECT SUM(optionCount) AS SUM FROM $prefix"._poll_data." WHERE pollID=$pollID");
    $sum = (int)mysql_result($result, 0, "SUM"); 
    mysql_free_result($result);
    echo "<table border=\"0\">";
    /* cycle through all options */
    for($i = 1; $i <= 12; $i++) {
	/* select next vote option */
	$result = mysql_query("SELECT pollID, optionText, optionCount, voteID FROM $prefix"._poll_data." WHERE (pollID=$pollID) AND (voteID=$i)");
	$object = mysql_fetch_object($result);
	if(is_object($object)) {
	    $optionText = $object->optionText;
	    $optionCount = $object->optionCount;
	    if($optionText != "") {
		echo "<tr><td>";
		echo "$optionText";
		echo "</td>";
		if($sum) {
		    $percent = 100 * $optionCount / $sum;
		} else {
		    $percent = 0;
		}
		echo "<td>";
		$percentInt = (int)$percent * 4 * $BarScale;
		$percent2 = (int)$percent;
		if(is_user($user)) {
		    if($cookie[9]=="") $cookie[9]=$Default_Theme;
		    if(!$file=@opendir("themes/$cookie[9]")) {
			$ThemeSel = $Default_Theme;
		    } else {
			$ThemeSel = $cookie[9];
		    }
		} else {
		    $ThemeSel = $Default_Theme;
		}
		if ($percent > 0) {
		    echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" height=\"15\" width=\"7\" Alt=\"$percent2 %\">";
		    echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"15\" width=\"$percentInt\" Alt=\"$percent2 %\">";
		    echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" height=\"15\" width=\"7\" Alt=\"$percent2 %\">";
		} else {
		    echo "<img src=\"themes/$ThemeSel/images/leftbar.gif\" height=\"15\" width=\"7\" Alt=\"$percent2 %\">";
		    echo "<img src=\"themes/$ThemeSel/images/mainbar.gif\" height=\"15\" width=\"3\" Alt=\"$percent2 %\">";
		    echo "<img src=\"themes/$ThemeSel/images/rightbar.gif\" height=\"15\" width=\"7\" Alt=\"$percent2 %\">";
		}
                printf(" %.2f %% (%d)", $percent, $optionCount);
		echo "</td></tr>";
	    }
	}
	
    }
    echo "</table><br>";
    echo "<center><font size=\"2\">";
    echo "<b>"._TOTALVOTES." $sum</b><br>";
    if($setCookies>0) {
        echo "</font><font size=\"1\">"._ONEPERDAY."</font><font size=\"2\"><br><br>";
    } else {
        echo "<br><br>";
    }
    $booth = $pollID;
    echo("[ <a href=\"pollBooth.php?pollID=$booth\">"._VOTING."</a> | ");
    echo("<a href=\"pollBooth.php\">"._OTHERPOLLS."</a> ]</font></center>");
    return(1);
}

function getTopics($s_sid) {
    global $topicname, $topicimage, $topictext, $prefix;
    $sid = $s_sid;
    $result=mysql_query("SELECT topic FROM $prefix"._stories." where sid=$sid");
    list($topic) = mysql_fetch_row($result);
    $result=mysql_query("SELECT topicid, topicname, topicimage, topictext FROM $prefix"._topics." where topicid=$topic");
    list($topicid, $topicname, $topicimage, $topictext) = mysql_fetch_row($result);
}

function headlines($bid) {
    global $prefix;
    $result = mysql_query("select title, content, url, refresh, time from $prefix"._blocks." where bid='$bid'");
    list($title, $content, $url, $refresh, $otime) = mysql_fetch_row($result);
    $past = time()-$refresh;
    if ($otime < $past) {
	$btime = time();
	$rdf = parse_url($url);
	$fp = fsockopen($rdf['host'], 80, $errno, $errstr, 15);
	if (!$fp) {
	    $content = "<font size=\"2\">"._RSSPROBLEM."</font>";
	    $result = mysql_query("update $prefix"._blocks." set content='$content', time='$btime' where bid='$bid'");
	    themesidebox($title, $content);
	    return;
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
		    $result = mysql_query("update $prefix"._blocks." set content='$content', time='$btime' where bid='$bid'");
		    themesidebox($title, $content);
		    return;
		}
		if (strcmp($link,$title)) {
		    $content .= "<strong><big>&middot;</big></strong>&nbsp;<a href=\"$link\" target=\"new\">$title2</a><br>\n";
		}
	    }
	    $siteurl = ereg_replace("http://","",$url);
	    $siteurl = explode("/",$siteurl);
	    $content .= "<br><a href=\"http://$siteurl[0]\" target=\"blank\"><b>"._HREADMORE."</b></a></font>";
	}
	$result = mysql_query("update $prefix"._blocks." set content='$content', time='$btime' where bid='$bid'");
    }
    themesidebox($title, $content);
}

function online() {
    global $user, $cookie, $prefix;
    cookiedecode($user);
    $ip = getenv("REMOTE_ADDR");
    $username = $cookie[1];
    if (!isset($username)) {
        $username = "$ip";
        $guest = 1;
    }
    $past = time()-900;
    mysql_query("DELETE FROM $prefix"._session." WHERE time < $past");
    $result = mysql_query("SELECT time FROM $prefix"._session." WHERE username='$username'");
    $ctime = time();
    if ($row = mysql_fetch_array($result)) {
	mysql_query("UPDATE $prefix"._session." SET username='$username', time='$ctime', host_addr='$ip', guest='$guest' WHERE username='$username'");
    } else {
	mysql_query("INSERT INTO $prefix"._session." (username, time, host_addr, guest) VALUES ('$username', '$ctime', '$ip', '$guest')");
    }

    $result = mysql_query("SELECT username FROM $prefix"._session." where guest=1");
    $guest_online_num = mysql_num_rows($result);

    $result = mysql_query("SELECT username FROM $prefix"._session." where guest=0");
    $member_online_num = mysql_num_rows($result);

    $who_online_num = $guest_online_num + $member_online_num;
    $who_online = "<center><font size=\"2\">"._CURRENTLY." $guest_online_num "._GUESTS." $member_online_num "._MEMBERS."<br>";
    $title = _WHOSONLINE;
    $content = "$who_online";
    if (is_user($user)) {
	$content .= "<br>"._YOUARELOGGED." <b>$username</b>.<br>";
	$result = mysql_query("select uid from $prefix"._users." where uname='$username'");
	list($uid) = mysql_fetch_row($result);
	$result2 = mysql_query("select to_userid from $prefix"._priv_msgs." where to_userid='$uid'");
	$numrow = mysql_num_rows($result2);
	$content .= ""._YOUHAVE." <a href=\"viewpmsg.php\"><b>$numrow</b></a> "._PRIVATEMSG."</font></center>";
    } else {
	$content .= "<br>"._YOUAREANON."</font></center>";
    }
    themesidebox($title, $content);
}

function bigstory() {
    global $cookie, $prefix;
    $today = getdate();
    $day = $today["mday"];
    if ($day < 10) {
	$day = "0$day";
    }
    $month = $today["mon"];
    if ($month < 10) {
	$month = "0$month";
    }
    $year = $today["year"];
    $tdate = "$year-$month-$day";
    $result = mysql_query("select sid, title from $prefix"._stories." where (time LIKE '%$tdate%') order by counter DESC limit 0,1");
    list($fsid, $ftitle) = mysql_fetch_row($result);
    $content = "<font size=\"2\">";
    if ((!$fsid) AND (!$ftitle)) {
	$content .= ""._NOBIGSTORY."</font>";
    } else {
	$content .= ""._BIGSTORY."<br><br>";
	if (isset($cookie[4])) { $options .= "&amp;mode=$cookie[4]"; } else { $options .= "&amp;mode=thread"; }
	if (isset($cookie[5])) { $options .= "&amp;order=$cookie[5]"; } else { $options .= "&amp;order=0"; }
	if (isset($cookie[6])) { $options .= "&amp;thold=$cookie[6]"; } else { $options .= "&amp;thold=0"; }                       
	$content .= "<a href=\"article.php?sid=$fsid$options\">$ftitle</a></font>";
    }
    $boxtitle = _TODAYBIG;
    themesidebox($boxtitle, $content);
}

function automatednews() {
    global $prefix;
    $today = getdate();
    $day = $today[mday];
    if ($day < 10) {
	$day = "0$day";
    }
    $month = $today[mon];
    if ($month < 10) {
	$month = "0$month";
    }
    $year = $today[year];
    $hour = $today[hours];
    $min = $today[minutes];
    $sec = "00";
    $result = mysql_query("select anid, time from $prefix"._autonews."");
    while(list($anid, $time) = mysql_fetch_row($result)) {
	ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $time, $date);
	if (($date[1] <= $year) AND ($date[2] <= $month) AND ($date[3] <= $day)) {
	    if (($date[4] < $hour) AND ($date[5] >= $min) OR ($date[4] <= $hour) AND ($date[5] <= $min)) {
		$result2 = mysql_query("select catid, aid, title, hometext, bodytext, topic, informant, notes, ihome from $prefix"._autonews." where anid='$anid'");
		while(list($catid, $aid, $title, $hometext, $bodytext, $topic, $author, $notes, $ihome) = mysql_fetch_row($result2)) {
		    $title = stripslashes(FixQuotes($title));
		    $hometext = stripslashes(FixQuotes($hometext));
		    $bodytext = stripslashes(FixQuotes($bodytext));
		    $notes = stripslashes(FixQuotes($notes));
		    mysql_query("insert into $prefix"._stories." values (NULL, '$catid', '$aid', '$title', now(), '$hometext', '$bodytext', '0', '0', '$topic', '$author', '$notes', '$ihome')");
		    mysql_query("delete from $prefix"._autonews." where anid='$anid'");
		}
	    }
	}
    }
}

##########################
# Smilies function       #
##########################
function smile($message) {
   global $prefix;
   if ($getsmiles = mysql_query("SELECT * FROM $prefix"._smiles."")){
      while ($smiles = mysql_fetch_array($getsmiles)) {
	 $message = str_replace($smiles[code], "<IMG SRC=\"images/forum/smilies/$smiles[smile_url]\">", $message);
      }
   }
   return($message);
}
function desmile($message) {
   global $prefix;
   if ($getsmiles = mysql_query("SELECT * FROM $prefix"._smiles."")){
      while ($smiles = mysql_fetch_array($getsmiles)) {
	 $message = str_replace("<IMG SRC=\"images/forum/smilies/$smiles[smile_url]\">", $smiles[code], $message);
      }
   }
   return($message);
}

?>
