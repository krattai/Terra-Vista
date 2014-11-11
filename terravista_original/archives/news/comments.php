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

function modone() {
    global $admin, $moderate;
    if(((isset($admin)) && ($moderate == 1)) || ($moderate==2)) echo "<form action=\"comments.php\" method=\"post\">";
}

function modtwo($tid, $score, $reason) {
    global $admin, $user, $moderate, $reasons;
    if((((isset($admin)) && ($moderate == 1)) || ($moderate == 2)) && ($user)) {
	echo " | <select name=dkn$tid>";
	for($i=0; $i<sizeof($reasons); $i++) {
	    echo "<option value=\"$score:$i\">$reasons[$i]</option>\n";
	}
	echo "</select>";
    }
}

function modthree($sid, $mode, $order, $thold=0) {
    global $admin, $user, $moderate, $userimg;
    if((((isset($admin)) && ($moderate == 1)) || ($moderate==2)) && ($user)) echo "<center><input type=hidden name=sid value=$sid><input type=hidden name=mode value=$mode><input type=hidden name=order value=$order><input type=hidden name=thold value=$thold>
    <input type=hidden name=op value=moderate>
    <input type=image src=$userimg/moderate.gif border=0></form></center>";
}

function navbar($sid, $title, $thold, $mode, $order) {
    global $user, $bgcolor1, $bgcolor2, $textcolor1, $textcolor2, $anonpost, $prefix;
    $query = mysql_query("select sid FROM $prefix"._comments." where sid=$sid");
    if(!$query) {
	$count = 0;
    } else {
	$count = mysql_num_rows($query);
    }
    if(!isset($thold)) {
	$thold=0;
    }
    echo "\n\n<!-- COMMENTS NAVIGATION BAR START -->\n\n";
    echo "<table width=\"99%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">\n";
    if($title) {
	echo "<tr><td bgcolor=\"$bgcolor2\" align=\"center\"><font size=\"2\" color=\"$textcolor1\">\"$title\" | ";
	    if(is_user($user)) {
		echo "<a href=\"user.php?op=editcomm\"><font color=\"$textcolor1\">"._CONFIGURE."</font></a>";
	    } else {
		echo "<a href=\"user.php\"><font color=\"$textcolor1\">"._LOGINCREATE."</font></a>";
	    }
	    if(($count==1)) {
	        echo " | <B>$count</B> "._COMMENT."</font></td></tr>\n";
	    } else {
	        echo " | <B>$count</B> "._COMMENTS."</font></td></tr>\n";
	    }
    }
    echo "<tr><td bgcolor=\"$bgcolor1\" align=\"center\" width=\"100%\">\n"
	."<table border=\"0\"><tr><td><font size=\"2\">\n"
	."<form method=\"get\" action=\"article.php\">\n"
	."<font color=\"$textcolor2\">"._THRESHOLD."</font> <select name=\"thold\">\n"
	."<option value=\"-1\"";
    if ($thold == -1) {
	echo " selected";
    }
    echo ">-1</option>\n"
         ."<option value=\"0\"";
    if ($thold == 0) {
	echo " selected";
    }
    echo ">0</option>\n"
	 ."<option value=\"1\"";
    if ($thold == 1) {
	echo " selected";
    }
    echo ">1</option>\n"
	 ."<option value=\"2\"";
    if ($thold == 2) {
	echo " selected";
    }
    echo ">2</option>\n"
	 ."<option value=\"3\"";
    if ($thold == 3) {
	echo " selected";
    }
    echo ">3</option>\n"
	 ."<option value=\"4\"";
    if ($thold == 4) {
	echo " selected";
    }
    echo ">4</option>\n"
	 ."<option value=\"5\"";
    if ($thold == 5) {
	echo " selected";
    }
    echo ">5</option>\n"
	 ."</select> <select name=mode>"
	 ."<option value=\"nocomments\"";
    if ($mode == 'nocomments') {
	echo " selected";
    }
    echo ">"._NOCOMMENTS."</option>\n"
	 ."<option value=\"nested\"";
    if ($mode == 'nested') {
	echo " selected";
    }
    echo ">"._NESTED."</option>\n"
	 ."<option value=\"flat\"";
    if ($mode == 'flat') {
	echo " selected";
    }
    echo ">"._FLAT."</option>\n"
	 ."<option value=\"thread\"";
    if (!isset($mode) || $mode=='thread' || $mode=="") {
	echo " selected";
    }
    echo ">"._THREAD."</option>\n"
	 ."</select> <select name=\"order\">"
	 ."<option value=\"0\"";
    if (!$order) {
	echo " selected";
    }
    echo ">"._OLDEST."</option>\n"
	 ."<option value=\"1\"";
    if ($order==1) {
	echo " selected";
    }
    echo ">"._NEWEST."</option>\n"
    	 ."<option value=\"2\"";
    if ($order==2) {
	echo " selected";
    }
    echo ">"._HIGHEST."</option>\n"
	 ."</select>\n"
	 ."<input type=\"hidden\" name=\"sid\" value=\"$sid\">\n"
	 ."<input type=\"submit\" value=\""._REFRESH."\"></form>\n";
    if ($anonpost==1 OR is_admin($admin) OR is_user($user)) {
	echo "</font></td><td bgcolor=\"$bgcolor1\"><font size=\"2\"><form action=\"comments.php\" method=\"post\">"
	    ."<input type=\"hidden\" name=\"pid\" value=\"$pid\">"
	    ."<input type=\"hidden\" name=\"sid\" value=\"$sid\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"Reply\">"
	    ."&nbsp;&nbsp;<input type=\"submit\" value=\""._REPLYMAIN."\">";
    }	 
    echo "</form></font></td></tr></table>\n"
	."</td></tr>"
	."<tr><td bgcolor=\"$bgcolor2\" align=\"center\"><font size=\"1\">"._COMMENTSWARNING."</font></td></tr>\n"
	."</table>"
	."\n\n<!-- COMMENTS NAVIGATION BAR END -->\n\n";
}

function DisplayKids ($tid, $mode, $order=0, $thold=0, $level=0, $dummy=0, $tblwidth=99) {
    global $datetime, $user, $cookie, $bgcolor1, $reasons, $anonymous, $anonpost, $commentlimit, $prefix;
    $comments = 0;
    cookiedecode($user);
    $result = mysql_query("select tid, pid, sid, date, name, email, url, host_name, subject, comment, score, reason from $prefix"._comments." where pid = $tid order by date, tid");
    if ($mode == 'nested') {
	/* without the tblwidth variable, the tables run of the screen with netscape */
	/* in nested mode in long threads so the text can't be read. */
	while (list($r_tid, $r_pid, $r_sid, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = mysql_fetch_row($result)) {
	    if($r_score >= $thold) {
		if (!isset($level)) {
		} else {
		    if (!$comments) {
			echo "<ul>";
			$tblwidth -= 5;
		    }
		}
		$comments++;
		if (!eregi("[a-z0-9]",$r_name)) $r_name = $anonymous;
		if (!eregi("[a-z0-9]",$r_subject)) $r_subject = "["._NOSUBJECT."]";
		// HIJO enter hex color between first two appostrophe for second alt bgcolor
		$r_bgcolor = ($dummy%2)?"":"#E6E6D2";
		echo "<a name=\"$r_tid\">";
		echo "<table border=\"0\"><tr bgcolor=\"$bgcolor1\"><td>";
		formatTimestamp($r_date);
		if ($r_email) {
		    echo "<b>$r_subject</b> <font size=\"2\">";
		    if(!$cookie[7]) {
			echo "("._SCORE." $r_score";
			if($r_reason>0) echo ", $reasons[$r_reason]";
			echo ")";
		    }
		    echo "<br>"._BY." <a href=\"mailto:$r_email\">$r_name</a> <font size=\"2\"><b>($r_email)</b></font> "._ON." $datetime";
		} else {
		    echo "<b>$r_subject</b> <font size=\"2\">";
		    if(!$cookie[7]) {
			echo "("._SCORE." $r_score";
			if($r_reason>0) echo ", $reasons[$r_reason]";
			echo ")";
		    }
		    echo "<br>"._BY." $r_name "._ON." $datetime";
		}			
		if ($r_name != $anonymous) { echo "<br>(<a href=\"user.php?op=userinfo&amp;uname=$r_name\">"._USERINFO."</a> | <a href=\"replypmsg.php?send=1&amp;uname=$r_name\">"._SENDAMSG."</a>) "; }
		if (eregi("http://",$r_url)) { echo "<a href=\"$r_url\" target=\"window\">$r_url</a> "; }
		echo "</font></td></tr><tr><td>";
		if(($cookie[10]) && (strlen($r_comment) > $cookie[10])) echo substr("$r_comment", 0, $cookie[10])."<br><br><b><a href=\"comments.php?sid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._READREST."</a></b>";
		elseif(strlen($r_comment) > $commentlimit) echo substr("$r_comment", 0, $commentlimit)."<br><br><b><a href=\"comments.php?sid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._READREST."</a></b>";
		else echo $r_comment;
		echo "</td></tr></table><br><br>";
		if ($anonpost==1 OR is_admin($admin) OR is_user($user)) {
		    echo "<font size=\"2\" color=\"$bgcolor2\"> [ <a href=\"comments.php?op=Reply&amp;pid=$r_tid&amp;sid=$r_sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._REPLY."</a>";
		}
		modtwo($r_tid, $r_score, $r_reason);
		echo " ]</font><br><br>";
		DisplayKids($r_tid, $mode, $order, $thold, $level+1, $dummy+1, $tblwidth);
	    }
	}
	} elseif ($mode == 'flat') {
	    while (list($r_tid, $r_pid, $r_sid, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = mysql_fetch_row($result)) {
		if($r_score >= $thold) {
		    if (!eregi("[a-z0-9]",$r_name)) $r_name = $anonymous;
		    if (!eregi("[a-z0-9]",$r_subject)) $r_subject = "["._NOSUBJECT."]";
		    echo "<a name=\"$r_tid\">";
		    echo "<hr><table width=\"99%\" border=\"0\"><tr bgcolor=\"$bgcolor1\"><td>";
		    formatTimestamp($r_date);
		    if ($r_email) {
			echo "<b>$r_subject</b> <font size=\"2\">";
			if(!$cookie[7]) {
			    echo "("._SCORE." $r_score";
			    if($r_reason>0) echo ", $reasons[$r_reason]";
			    echo ")";
			}
			echo "<br>"._BY." <a href=\"mailto:$r_email\">$r_name</a> <font size=\"2\"><b>($r_email)</b></font> "._ON." $datetime";
 		    } else {
			echo "<b>$r_subject</b> <font size=\"2\">";
			if(!$cookie[7]) {
			    echo "("._SCORE." $r_score";
			    if($r_reason>0) echo ", $reasons[$r_reason]";
			    echo ")";
			}
			echo "<br>"._BY." $r_name "._ON." $datetime";
		    }			
		    if ($r_name != $anonymous) { echo "<br>(<a href=\"user.php?op=userinfo&amp;uname=$r_name\">"._USERINFO."</a> | <a href=\"replypmsg.php?send=1&amp;uname=$r_name\">"._SENDAMSG."</a>) "; }
		    if (eregi("http://",$r_url)) { echo "<a href=\"$r_url\" target=\"window\">$r_url</a> "; }
		    echo "</font></td></tr><tr><td>";
		    if(($cookie[10]) && (strlen($r_comment) > $cookie[10])) echo substr("$r_comment", 0, $cookie[10])."<br><br><b><a href=\"comments.php?sid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._READREST."</a></b>";
		    elseif(strlen($r_comment) > $commentlimit) echo substr("$r_comment", 0, $commentlimit)."<br><br><b><a href=\"comments.php?sid=$r_sid&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._READREST."</a></b>";
		    else echo $r_comment;
		    echo "</td></tr></table><br><br>";
		    if ($anonpost==1 OR is_admin($admin) OR is_user($user)) {
			echo "<font size=\"2\" color=\"$bgcolor2\"> [ <a href=\"comments.php?op=Reply&amp;pid=$r_tid&amp;sid=$r_sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._REPLY."</a>";
		    }
		    modtwo($r_tid, $r_score, $r_reason);
		    echo " ]</font><br><br>";
		    DisplayKids($r_tid, $mode, $order, $thold);
		}
	    }
	} else {
	    while (list($r_tid, $r_pid, $r_sid, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = mysql_fetch_row($result)) {
		if($r_score >= $thold) {
		    if (!isset($level)) {
		    } else {
			if (!$comments) {
			    echo "<ul>";
			}
		    }
		    $comments++;
		    if (!eregi("[a-z0-9]",$r_name)) $r_name = $anonymous;
		    if (!eregi("[a-z0-9]",$r_subject)) $r_subject = "["._NOSUBJECT."]";
		    formatTimestamp($r_date);
		    echo "<li><font size=\"2\"><a href=\"comments.php?op=showreply&amp;tid=$r_tid&amp;sid=$r_sid&amp;pid=$r_pid&amp;mode=$mode&amp;order=$order&amp;thold=$thold#$r_tid\">$r_subject</a> "._BY." $r_name "._ON." $datetime</font><br>";
		    DisplayKids($r_tid, $mode, $order, $thold, $level+1, $dummy+1);
		}
	    }
	}
    if ($level && $comments) {
        echo "</ul>";
    }
}

function DisplayBabies ($tid, $level=0, $dummy=0) {
    global $datetime, $anonymous, $prefix;
    $comments = 0;
    $result = mysql_query("select tid, pid, sid, date, name, email, url, host_name, subject, comment, score, reason from $prefix"._comments." where pid = $tid order by date, tid");
    while (list($r_tid, $r_pid, $r_sid, $r_date, $r_name, $r_email, $r_url, $r_host_name, $r_subject, $r_comment, $r_score, $r_reason) = mysql_fetch_row($result)) {
	if (!isset($level)) {
	} else {
	    if (!$comments) {
		echo "<ul>";
	    }
	}
	$comments++;
	if (!eregi("[a-z0-9]",$r_name)) { $r_name = $anonymous; }
	if (!eregi("[a-z0-9]",$r_subject)) { $r_subject = "["._NOSUBJECT."]"; }
	formatTimestamp($r_date);
	echo "<a href=\"comments.php?op=showreply&amp;tid=$r_tid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">$r_subject</a></font><font size=\"2\"> "._BY." $r_name "._ON." $datetime<br>";
	DisplayBabies($r_tid, $level+1, $dummy+1);
    } 
    if ($level && $comments) {
    	echo "</ul>";
    }
}

function DisplayTopic ($sid, $pid=0, $tid=0, $mode="thread", $order=0, $thold=0, $level=0, $nokids=0) {
    global $hr, $user, $datetime, $cookie, $mainfile, $admin, $commentlimit, $anonymous, $reasons, $anonpost, $foot1, $foot2, $foot3, $foot4, $prefix;
    if($mainfile) {
	global $title, $bgcolor1, $bgcolor2, $bgcolor3;
    } else {
	global $title, $bgcolor1, $bgcolor2, $bgcolor3;
	include("mainfile.php");
	include("header.php");
    }
    if ($pid!=0) {
	include("header.php");
    }
    $count_times = 0;
    cookiedecode($user);
    $q = "select tid, pid, sid, date, name, email, url, host_name, subject, comment, score, reason from $prefix"._comments." where sid=$sid and pid=$pid";
    if($thold != "") {
	$q .= " and score>=$thold";
    } else {
	$q .= " and score>=0";
    }
    if ($order==1) $q .= " order by date desc";
    if ($order==2) $q .= " order by score desc";
    $something = mysql_query("$q");
    $num_tid = mysql_num_rows($something);
    navbar($sid, $title, $thold, $mode, $order);
    modone();
    while ($count_times < $num_tid) {
	list($tid, $pid, $sid, $date, $name, $email, $url, $host_name, $subject, $comment, $score, $reason) = mysql_fetch_row($something);
	if ($name == "") { $name = $anonymous; }
	if ($subject == "") { $subject = "["._NOSUBJECT."]"; }	
	echo "<a name=\"$tid\"></a>";
	echo "<table width=\"99%\" border=\"0\"><tr bgcolor=\"$bgcolor1\"><td width=\"500\">";
	formatTimestamp($date);
	if ($email) {
	    echo "<b>$subject</b> <font size=\"2\">";
	    if(!$cookie[7]) {
		echo "("._SCORE." $score";
		if($reason>0) echo ", $reasons[$reason]";
		echo ")";
	    }
	    echo "<br>"._BY." <a href=\"mailto:$email\">$name</a> <b>($email)</b> "._ON." $datetime"; 
	} else {
	    echo "<b>$subject</b> <font size=\"2\">";
	    if(!$cookie[7]) {
		echo "("._SCORE." $score";
		if($reason>0) echo ", $reasons[$reason]";
		echo ")";
	    }
	    echo "<br>"._BY." $name "._ON." $datetime";
	}			
		
    /* If you are admin you can see the Poster IP address (you have this right, no?) */
    /* with this you can see who is flaming you... ha-ha-ha */
		
	if ($name != $anonymous) { echo "<br>(<a href=\"user.php?op=userinfo&amp;uname=$name\">"._USERINFO."</a> | <a href=\"replypmsg.php?send=1&amp;uname=$name\">"._SENDAMSG."</a>) "; }
	if (eregi("http://",$url)) { echo "<a href=\"$url\" target=\"window\">$url</a> "; }

	if(is_admin($admin)) {
	    $result= mysql_query("select host_name from $prefix"._comments." where tid='$tid'");
	    list($host_name) = mysql_fetch_row($result);
	    echo "<br><b>(IP: $host_name)</b>";
	}
	
	echo "</font></td></tr><tr><td>";
	if(($cookie[10]) && (strlen($comment) > $cookie[10])) echo substr("$comment", 0, $cookie[10])."<br><br><b><a href=\"comments.php?sid=$sid&tid=$tid&mode=$mode&order=$order&thold=$thold\">"._READREST."</a></b>";
	elseif(strlen($comment) > $commentlimit) echo substr("$comment", 0, $commentlimit)."<br><br><b><a href=\"comments.php?sid=$sid&tid=$tid&mode=$mode&order=$order&thold=$thold\">"._READREST."</a></b>";
	else echo $comment;
	echo "</td></tr></table><br><br>";
	if ($anonpost==1 OR is_admin($admin) OR is_user($user)) {
	    echo "<font size=\"2\"> [ <a href=\"comments.php?op=Reply&amp;pid=$tid&amp;sid=$sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._REPLY."</a>";
	} else {
	    echo "[ "._NOANONCOMMENTS." ";
	}
	if ($pid != 0) {
	    list($erin) = mysql_fetch_row(mysql_query("select pid from $prefix"._comments." where tid=$pid"));
	    echo " | <a href=\"comments.php?sid=$sid&pid=$erin&mode=$mode&order=$order&thold=$thold\">"._PARENT."</a>";
	}
	modtwo($tid, $score, $reason);
		
	if(is_admin($admin)) {
	    echo " | <a href=\"admin.php?op=RemoveComment&amp;tid=$tid&amp;sid=$sid\">"._DELETE."</a> ]</font><br><br>";
	} else {
	    echo " ]</font><br><br>";
	}
		
	DisplayKids($tid, $mode, $order, $thold, $level);
	echo "</ul>";
	if($hr) echo "<hr noshade size=\"1\">";
	$count_times += 1;
    }
    modthree($sid, $mode, $order, $thold);
    if($pid==0) return array($sid, $pid, $subject);
    else include("footer.php");
}

function singlecomment($tid, $sid, $mode, $order, $thold) {
    global $user, $cookie, $datetime, $bgcolor1, $bgcolor2, $bgcolor3, $bgcolor4, $admin, $anonpost, $prefix;
    //include("mainfile.php");
    include("header.php");
    $result = mysql_query("select date, name, email, url, subject, comment, score, reason from $prefix"._comments." where tid=$tid and sid=$sid");
    list($date, $name, $email, $url, $subject, $comment, $score, $reason) = mysql_fetch_row($result);
    $titlebar = "<b>$subject</b>";
    if($name == "") $name = $anonymous;
    if($subject == "") $subject = "["._NOSUBJECT."]";
    modone();
    echo "<table width=\"99%\" border=\"0\"><tr bgcolor=\"$bgcolor1\"><td width=\"500\">";
    formatTimestamp($date);
    if($email) echo "<b>$subject</b> <font size=\"2\">("._SCORE." $score)<br>"._BY." <a href=\"mailto:$email\"><font color=\"$bgcolor2\">$name</font></a> <font size=2><b>($email)</b></font> "._ON." $datetime";
    else echo "<b>$subject</b> <font size=2>("._SCORE." $score)<br>"._BY." $name "._ON." $datetime";
    echo "</td></tr><tr><td>$comment</td></tr></table><br><br>";
    if ($anonpost==1 OR is_admin($admin) OR is_user($user)) {
	echo "<font size=2> [ <a href=\"comments.php?op=Reply&amp;pid=$tid&amp;sid=$sid&amp;mode=$mode&amp;order=$order&amp;thold=$thold\">"._REPLY."</a> | <a href=\"article.php?sid=$sid&mode=$mode&order=$order&thold=$thold\">"._ROOT."</a>";
    }
    modtwo($tid, $score, $reason);
    echo " ]";
    modthree($sid, $mode, $order, $thold);
    include("footer.php");
}

function reply($pid, $sid, $mode, $order, $thold) {
    include("config.php");
    //include("mainfile.php");
    include("header.php");
    global $user, $cookie, $datetime, $bgcolor1, $bgcolor2, $bgcolor3, $prefix;
    if($pid!=0) {
    	list($date, $name, $email, $url, $subject, $comment, $score) = mysql_fetch_row(mysql_query("select date, name, email, url, subject, comment, score from $prefix"._comments." where tid=$pid"));
    } else {
    	list($date, $subject, $temp_comment, $comment2, $name, $notes) = mysql_fetch_row(mysql_query("select time, title, hometext, bodytext, informant, notes FROM $prefix"._stories." where sid=$sid"));
    }
    if($comment == "") {
	$comment = "$temp_comment<br><br>$comment2";
    }
    OpenTable();
    echo "<center><font size=4><b>"._COMMENTREPLY."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    if($name == "") $name = $anonymous;
    if($subject == "") $subject = "["._NOSUBJECT."]";
    formatTimestamp($date);
    echo "<b>$subject</b> <font size=\"2\">";
    if(!$temp_comment) echo"("._SCORE." $score)";
    if($email) {
	echo "<br>"._BY." <a href=\"mailto:$email\">$name</a> <font size=\"2\"><b>($email)</b></font> "._ON." $datetime";
    } else {
	echo "<br>"._BY." $name "._ON." $datetime";
    }
    echo "<br><br>$comment<br><br>";
    if ($pid == 0) {
	if ($notes != "") {
	    echo "<b>"._NOTE."</b> <i>$notes</i><br><br>";
	} else {
	    echo "";
	}
    }
    if(!isset($pid) || !isset($sid)) { echo "Something is not right. This message is just to keep things from messing up down the road"; exit(); }
    if($pid == 0) {
	list($subject) = mysql_fetch_row(mysql_query("select title from $prefix"._stories." where sid=$sid"));
    } else {
	list($subject) = mysql_fetch_row(mysql_query("select subject from $prefix"._comments." where tid=$pid"));
    }
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<form action=\"comments.php\" method=\"post\">";
    echo "<font size=3><b>"._YOURNAME.":</b></font> ";
    if (is_user($user)) {
	cookiedecode($user);
	echo "<a href=\"user.php\">$cookie[1]</a> <font size=\"2\">[ <a href=\"user.php?op=logout\">"._LOGOUT."</a> ]</font><br><br>";
    } else {
    	echo "<font size=\"2\">$anonymous";
	echo " [ <a href=\"user.php\">"._NEWUSER."</a> ]<br><br>";
    }
    echo "<font size=\"3\"><b>"._SUBJECT.":</b></font><br>";
    if (!eregi("Re:",$subject)) $subject = "Re: ".substr($subject,0,81)."";
    echo "<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"85\" value=\"$subject\"><br><br>";
    echo "<font size=\"3\"><b>"._UCOMMENT.":</b></font><br>"
	 ."<textarea wrap=\"virtual\" cols=\"50\" rows=\"10\" name=\"comment\"></textarea><br>
	   <font size=\"2\">"._ALLOWEDHTML."<br>";
    while (list($key,)= each($AllowableHTML)) echo " &lt;".$key."&gt;";
    echo "<br>";
    if (is_user($user)) { echo "<input type=\"checkbox\" name=\"xanonpost\"> "._POSTANON."<br>"; }
    echo "<input type=\"hidden\" name=\"pid\" value=\"$pid\">\n"
	."<input type=\"hidden\" name=\"sid\" value=\"$sid\">\n"
	."<input type=\"hidden\" name=\"mode\" value=\"$mode\">\n"
	."<input type=\"hidden\" name=\"order\" value=\"$order\">\n"
	."<input type=\"hidden\" name=\"thold\" value=\"$thold\">\n"
	."<input type=\"submit\" name=\"op\" value=\""._PREVIEW."\">\n"
	."<input type=\"submit\" name=\"op\" value=\""._OK."\">\n"
	."<select name=\"posttype\">\n"
	."<option value=\"exttrans\">"._EXTRANS."</option>\n"
	."<option value=\"html\" >"._HTMLFORMATED."</option>\n"
	."<option value=\"plaintext\" selected>"._PLAINTEXT."</option>\n"
	."</select></font></form>\n";
    CloseTable();
    include("footer.php");
}

function replyPreview ($pid, $sid, $subject, $comment, $xanonpost, $mode, $order, $thold, $posttype) {
    //include("mainfile.php");
    include("header.php");
    global $user, $cookie, $AllowableHTML, $anonymous;
    OpenTable();
    echo "<center><font size=\"4\"><b>"._COMREPLYPRE."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    cookiedecode($user);
    $subject = stripslashes($subject);
    $comment = stripslashes($comment);
    if (!isset($pid) || !isset($sid)) {
        echo ""._NOTRIGHT."";
        exit();
    }
    echo "<b>$subject</b>";
    echo "<br><font size=\"2\">"._BY." ";
    if (is_user($user)) {
	echo "$cookie[1]";
    } else {
        echo "$anonymous";
    }
    echo " "._ONN."</font><br><br>";
    if ($posttype=="exttrans") {
        echo nl2br(htmlspecialchars($comment));
    } elseif ($posttype=="plaintext") {
        echo nl2br($comment);
    } else {
        echo $comment;
    }
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<form action=\"comments.php\" method=\"post\"><font size=\"3\"><b>"._YOURNAME.":</b></font> ";
    if (is_user($user)) {
        echo "<a href=\"user.php\">$cookie[1]</a> <font size=\"2\">[ <a href=\"user.php?op=logout\">"._LOGOUT."</a> ]</font><br><br>";
    } else {
        echo "<font size=\"2\">$anonymous<br><br>";
    }
    echo "<font size=\"3\"><b>"._SUBJECT.":</b></font><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"85\" value=\"$subject\"><br><br>"
	."<font size=\"3\"><b>"._UCOMMENT.":</b></font><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"10\" name=\"comment\">$comment</textarea><br>"
	."<font size=\"2\">"._ALLOWEDHTML."<br>";
    while (list($key,) = each($AllowableHTML)) echo " &lt;".$key."&gt;";
    echo "<br>";		
    if ($xanonpost) {
        echo "<input type=\"checkbox\" name=\"xanonpost\" checked> "._POSTANON."<br>";
    } elseif (is_user($user)) {
        echo "<input type=\"checkbox\" name=\"xanonpost\"> "._POSTANON."<br>";
    }
    echo "<input type=\"hidden\" name=\"pid\" value=\"$pid\">"
        ."<input type=\"hidden\" name=\"sid\" value=\"$sid\">"
	."<input type=\"hidden\" name=\"mode\" value=\"$mode\">"
        ."<input type=\"hidden\" name=\"order\" value=\"$order\">"
	."<input type=\"hidden\" name=\"thold\" value=\"$thold\">"
        ."<input type=submit name=op value=\""._PREVIEW."\">"
        ."<input type=submit name=op value=\""._OK."\">\n"
	."<select name=\"posttype\"><option value=\"exttrans\"";
    if ($posttype=="exttrans") {
        echo " selected";
    }
    echo ">"._EXTRANS."</option>\n"
	."<OPTION value=\"html\"";;
    if ($posttype=="html") {
        echo " selected";
    }
    echo ">"._HTMLFORMATED."</option>\n"
	."<OPTION value=\"plaintext\"";
    if (($posttype!="exttrans") && ($posttype!="html")) {
        echo " selected";
    }
    echo ">"._PLAINTEXT."</option></select></font></form>";
    CloseTable();
    include("footer.php");
}

function CreateTopic ($xanonpost, $subject, $comment, $pid, $sid, $host_name, $mode, $order, $thold, $posttype) {
    global $user, $userinfo, $EditedMessage, $cookie, $AllowableHTML, $ultramode, $prefix;
    //include("mainfile.php");
    cookiedecode($user);
    $author = FixQuotes($author);
    $subject = FixQuotes(filter_text($subject, "nohtml"));
    if($posttype=="exttrans") {
    	$comment = FixQuotes(nl2br(htmlspecialchars(check_words($comment))));
    } elseif($posttype=="plaintext") {
    	$comment = FixQuotes(nl2br(filter_text($comment)));
    } else {
	$comment = FixQuotes(filter_text($comment));
    }
    if($user) {
        getusrinfo($user);
    }
    if (($user) && (!$xanonpost)) {
	getusrinfo($user);
	$name = $userinfo[uname];
	$email = $userinfo[femail];
	$url = $userinfo[url];
	$score = 1;
    } else {
	$name = ""; $email = ""; $url = "";
	$score = 0;
    }
    $ip = getenv("REMOTE_ADDR");

/* begin fake thread control */

    list($fake) = mysql_fetch_row(mysql_query("select count(*) from $prefix"._stories." where sid=$sid"));
    mysql_query("LOCK TABLES $prefix"._comments." WRITE");

/* begin duplicate control */

    list($tia) = mysql_fetch_row(mysql_query("select count(*) from $prefix"._comments." where pid='$pid' and sid='$sid' and subject='$subject' and comment='$comment'"));

/* begin troll control */

    if($user) {
    	list($troll) = mysql_fetch_row(mysql_query("select count(*) from $prefix"._comments." where (score=-1) and (name='$userinfo[uname]') and (to_days(now()) - to_days(date) < 3)"));
    } elseif(!$score) {
    	list($troll) = mysql_fetch_row(mysql_query("select count(*) from $prefix"._comments." where (score=-1) and (host_name='$ip') and (to_days(now()) - to_days(date) < 3)"));
    }
    if((!$tia) && ($fake == 1) && ($troll < 6)) {
	mysql_query("insert into $prefix"._comments." values (NULL, '$pid', '$sid', now(), '$name', '$email', '$url', '$ip', '$subject', '$comment', '$score', '0')");
    } else {
	mysql_query("UNLOCK TABLES");
	include("header.php");
	if ($tia) {
	    echo ""._DUPLICATE."<br><br><a href=\"article.php?sid=$sid&mode=$mode&order=$order&thold=$thold\">"._COMMENTSBACK."</a>";
	} elseif($troll > 5) {
	    echo "This account or IP has been temporarily disabled.
	    This means that either this IP, or
	    user account has been moderated down more than 5 times in
	    the last few hours.  If you think this is unfair,
	    you should contact the admin.  If you
	    are being a troll, now is the time for you to either
	    grow up, or change your IP.<br><br><a href=\"article.php?sid=$sid&mode=$mode&order=$order&thold=$thold\">"._COMMENTSBACK."</a>";
	} elseif($fake == 0) {
	    echo "According to my records, the topic you are trying 
	    to reply to does not exist. If you're just trying to be 
	    annoying, well then too bad.";
	}
	include("footer.php");
	exit;
    }
    mysql_query("UNLOCK TABLES");
    mysql_query("update $prefix"._stories." set comments=comments+1 where sid='$sid'");
    if ($ultramode) {
        ultramode();
    }
    if (isset($cookie[4])) { $options .= "&mode=$cookie[4]"; } else { $options .= "&mode=thread"; }
    if (isset($cookie[5])) { $options .= "&order=$cookie[5]"; } else { $options .= "&order=0"; }
    if (isset($cookie[6])) { $options .= "&thold=$cookie[6]"; } else { $options .= "&thold=0"; }
    Header("Location: article.php?sid=$sid$options");
}

switch($op) {

    case "Reply":
	reply($pid, $sid, $mode, $order, $thold);
	break;

    case ""._PREVIEW."":
	replyPreview ($pid, $sid, $subject, $comment, $xanonpost, $mode, $order, $thold, $posttype);
	break;

    case ""._OK."":
	CreateTopic($xanonpost, $subject, $comment, $pid, $sid, $host_name, $mode, $order, $thold, $posttype);
	break;

    case "moderate":
	if(isset($admin)) {
	    include("auth.inc.php");
	} else {
	    include("mainfile.php");	
	}
	if(($admintest==1) || ($moderate==2)) {
	    while(list($tdw, $emp) = each($HTTP_POST_VARS)) {
		if (eregi("dkn",$tdw)) {
		    $emp = explode(":", $emp);
		    if($emp[1] != 0) {
			$tdw = ereg_replace("dkn", "", $tdw);
			$q = "UPDATE $prefix"._comments." SET";
			if(($emp[1] == 9) && ($emp[0]>=0)) { # Overrated
			    $q .= " score=score-1 where tid=$tdw";
			} elseif (($emp[1] == 10) && ($emp[0]<=4)) { # Underrated
			    $q .= " score=score+1 where tid=$tdw";
			} elseif (($emp[1] > 4) && ($emp[0]<=4)) {
			    $q .= " score=score+1, reason=$emp[1] where tid=$tdw";
			} elseif (($emp[1] < 5) && ($emp[0] > -1)) {
			    $q .= " score=score-1, reason=$emp[1] where tid=$tdw";
			} elseif (($emp[0] == -1) || ($emp[0] == 5)) {
			    $q .= " reason=$emp[1] where tid=$tdw";
			}
			if(strlen($q) > 20) mysql_query("$q");
		    }
		}
	    }
	}
	Header("Location: article.php?sid=$sid&mode=$mode&order=$order&thold=$thold");
	break;

    case "showreply":
	DisplayTopic($sid, $pid, $tid, $mode, $order, $thold);
	break;

    default:
	if ((isset($tid)) && (!isset($pid))) {
	    singlecomment($tid, $sid, $mode, $order, $thold);
//	} elseif (($mainfile) xor (($pid==0) || (!isset($pid)))) {
	} elseif (($mainfile) xor (($pid==0) AND (!isset($pid)))) {
	    Header("Location: article.php?sid=$sid&mode=$mode&order=$order&thold=$thold");
	} else {
	    if(!isset($pid)) $pid=0;
	    DisplayTopic($sid, $pid, $tid, $mode, $order, $thold);
	}
	break;

}

?>