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

if(!IsSet($mainfile)) { include ("mainfile.php"); }
$index = 1;
automatednews();

function theindex() {
    global $storyhome, $httpref, $httprefmax, $topicname, $topicimage, $topictext, $datetime, $user, $cookie, $nukeurl, $prefix;
    include("header.php");
    MessageBox();
    if (isset($cookie[3])) {
	$storynum = $cookie[3];
    } else {
	$storynum = $storyhome;
    }
    $result = mysql_query("SELECT sid, catid, aid, title, time, hometext, bodytext, comments, counter, topic, informant, notes FROM $prefix"._stories." WHERE (ihome='0' OR catid='0') ORDER BY sid DESC limit $storynum");
    if(!$result) {
	echo mysql_errno(). ": ".mysql_error(). "<br>"; exit();
    }
    while (list($s_sid, $catid, $aid, $title, $time, $hometext, $bodytext, $comments, $counter, $topic, $informant, $notes) = mysql_fetch_row($result)) {
	if ($catid > 0) {
	    list($cattitle) = mysql_fetch_row(mysql_query("select title from $prefix"._stories."_cat where catid='$catid'"));
	}
	$printP = "<a href=\"print.php?sid=$s_sid\"><img src=\"images/print.gif\" border=0 Alt=\""._PRINTER."\" width=\"15\" height=\"11\"></a>&nbsp;";
	$sendF = "<a href=\"friend.php?op=FriendSend&amp;sid=$s_sid\"><img src=\"images/friend.gif\" border=0 Alt=\""._FRIEND."\" width=\"15\" height=\"11\"></a>";
	getTopics($s_sid);
	formatTimestamp($time);
	$subject = stripslashes($subject);
	$hometext = stripslashes($hometext);
	$notes = stripslashes($notes);
	$introcount = strlen($hometext);
	$fullcount = strlen($bodytext);
	$totalcount = $introcount + $fullcount;
	$morelink = "( ";
	if ($fullcount > 1) {
	    $morelink .= "<a href=\"article.php?sid=$s_sid";
	    if (isset($cookie[4])) { $morelink .= "&amp;mode=$cookie[4]"; } else { $morelink .= "&amp;mode=thread"; }
	    if (isset($cookie[5])) { $morelink .= "&amp;order=$cookie[5]"; } else { $morelink .= "&amp;order=0"; }
	    $morelink .= "\"><b>"._READMORE."</b></a> | $totalcount "._BYTESMORE." | "; }
	    $count = $comments;
	    $morelink .= "<a href=\"article.php?sid=$s_sid";
	    if (isset($cookie[4])) { $morelink .= "&amp;mode=$cookie[4]"; } else { $morelink .= "&amp;mode=thread"; }
	    if (isset($cookie[5])) { $morelink .= "&amp;order=$cookie[5]"; } else { $morelink .= "&amp;order=0"; }
	    if (isset($cookie[6])) { $morelink .= "&amp;thold=$cookie[6]"; } else { $morelink .= "&amp;thold=0"; }
	    $morelink2 = "<a href=\"article.php?sid=$s_sid";
	    if (isset($cookie[4])) { $morelink2 .= "&amp;mode=$cookie[4]"; } else { $morelink2 .= "&amp;mode=thread"; }
	    if (isset($cookie[5])) { $morelink2 .= "&amp;order=$cookie[5]"; } else { $morelink2 .= "&amp;order=0"; }
	    if (isset($cookie[6])) { $morelink2 .= "&amp;thold=$cookie[6]"; } else { $morelink2 .= "&amp;thold=0"; }
	    if(($count==0)) {
		if ($catid > 0) {
		    $morelink .= "\">"._COMMENTSQ."</a> | $printP $sendF | <a href=\"categories.php?op=newindex&amp;catid=$catid\">$cattitle</a> )";
		} else {
		    $morelink .= "\">"._COMMENTSQ."</a> | $printP $sendF )";
		}
	} else {
	    if (($fullcount<1)) {
		if(($count==1)) {
		    if ($catid > 0) {
			$morelink .= "\"><b>"._READMORE."</b></a> | $morelink2\">$count "._COMMENT."</a> | $printP $sendF | <a href=\"categories.php?op=newindex&amp;catid=$catid\">$cattitle</a>)";
		    } else {
			$morelink .= "\"><b>"._READMORE."</b></a> | $morelink2\">$count "._COMMENT."</a> | $printP $sendF )";
		    }
		} else {
		    if ($catid > 0) {
			$morelink .= "\"><b>"._READMORE."</b></a> | $morelink2\">$count "._COMMENTS."</a> | $printP $sendF | <a href=\"categories.php?op=newindex&amp;catid=$catid\">$cattitle</a>)";
		    } else {
			$morelink .= "\"><b>"._READMORE."</b></a> | $morelink2\">$count "._COMMENTS."</a> | $printP $sendF )";
		    }
		}
	    } else {
		if(($count==1)) {
		    if ($catid > 0) {
			$morelink .= "\">$count "._COMMENT."</a> | $printP $sendF | <a href=\"categories.php?op=newindex&amp;catid=$catid\">$cattitle</a>)";
		    } else {
			$morelink .= "\">$count "._COMMENT."</a> | $printP $sendF )";
		    }
		} else {
		    if ($catid > 0) {
			$morelink .= "\">$count "._COMMENTS."</a> | $printP $sendF | <a href=\"categories.php?op=newindex&amp;catid=$catid\">$cattitle</a>)";
		    } else {
			$morelink .= "\">$count "._COMMENTS."</a> | $printP $sendF )";
		    }
		}
	    }
	}
	$sid = $s_sid;
	if ($catid != 0) {
	    $resultm = mysql_query("select title from $prefix"._stories."_cat where catid='$catid'");
	    list($title1) = mysql_fetch_row($resultm);
	    $title = "<a href=\"categories.php?op=newindex&amp;catid=$catid\">$title1</a>: $title";
	}
	themeindex($aid, $informant, $datetime, $title, $counter, $topic, $hometext, $notes, $morelink, $topicname, $topicimage, $topictext);
    }
    mysql_free_result($result);
    if ($httpref==1) {
	$referer = getenv("HTTP_REFERER");
	if ($referer=="" OR eregi("^unknown", $referer) OR substr("$referer",0,strlen($nukeurl))==$nukeurl OR eregi("^bookmark",$referer)) {
	} else {
    	    mysql_query("insert into $prefix"._referer." values (NULL, '$referer')");
	}
	$result = mysql_query("select * from $prefix"._referer."");
	$numrows = mysql_num_rows($result);
	if($numrows>=$httprefmax) {
    	    mysql_query("delete from $prefix"._referer."");
	}
    }
    include("footer.php");
}

switch ($op) {

    default:
    theindex();

}

?>