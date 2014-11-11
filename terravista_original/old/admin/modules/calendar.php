<?php
/********************************************************/
/********************************************************/
/* Event Calendar                                       */
/* Written by: Rob Sutton                               */
/* http://www.xnettech.com (My Consulting Company)      */
/* http://smart.xnettech.net (My Nuke Site)             */
/*                                                      */
/* This program is opensource so you can do whatever    */
/* you want with it.                                    */
/********************************************************/
/********************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$result = mysql_query("select radminarticle, radminsuper from nuke_authors where aid='$aid'");
list($radminarticle, $radminsuper) = mysql_fetch_row($result);

$module_name = "Calendar";  // CHANGE THIS IF YOU CHANGE THE MODULES FOLDER NAME

include("modules/$module_name/config.php");

if (($radminCalendarAdmin==1) OR ($radminsuper==1)) {

/********************************************************/


function buildMonthSelect($month) {
        for ($i = 1; $i <= 12; $i++) {
                if ($i == $month) $sel = "SELECTED";
                else $sel = "";
                echo "<option $sel value=\"$i\">$i</option>\n";
        }
}

function buildDaySelect($day) {
        for ($i = 1; $i <= 31; $i++) {
                if ($i == $day) $sel = "SELECTED";
                else $sel = "";
                echo "<option $sel value=\"$i\">$i</option>\n";
        }
}

function buildYearSelect($year) {
        for ($i = 2001; $i <= 2030; $i++) {
                if ($i == $year) $sel = "SELECTED";
                else $sel = "";
                echo "<option $sel value=\"$i\">$i</option>\n";
        }
}

function buildHourSelect($hour) {
        global $time24Hour;
	if (!($time24Hour)) {
        	for ($i = 1; $i <= 12; $i++) {
                	if ($i == $hour) $sel = "SELECTED";
                	else $sel = "";
                	echo "<option $sel value=\"$i\">$i</option>\n";
        	}
	} else {
		for ($i = 0; $i <= 23; $i++) {
                	if ($i == $hour) $sel = "SELECTED";
                	else $sel = "";
                	echo "<option $sel value=\"$i\">";
			if ($i<10) echo "0";
			echo "$i</option>\n";
        	}
	}
}

function buildMinSelect($min) {
        for ($i = 0; $i <= 45;) {
                echo $i;
                if (($i == $min) | (($i == 0) & ($min == "00"))) $sel = "SELECTED";
                else $sel = "";
                if ($i == 0) echo "<option $sel value=\"00\">:00</option>\n";
                else echo "<option $sel value=\"$i\">:$i</option>\n";
                $i = $i + 15;
        }
}

function buildAMPMSelect($ampm) {
        if ($ampm == "AM") $sel = "SELECTED";
        else $sel = "";
        echo "<option $sel value=\"AM\">AM</option>\n";
        if ($ampm == "PM") $sel = "SELECTED";
        else $sel = "";
        echo "<option $sel value=\"PM\">PM</option>\n";
}

function getTime($time) {
        $time_Array = explode(":",$time);
        $min = $time_Array[1];
        if ($time_Array[0] == "00") {
                $hour = "12";
                $ampm = "AM";
        } elseif ($time_Array[0] == "12") {
                $hour = "12";
                $ampm = "PM";
        } elseif ($time_Array[0] > 12) {
                $hour = $time_Array[0] - 12;
                $ampm = "PM";
        } else {
                $hour = $time_Array[0];
                $ampm = "AM";
        }
        $time = "$hour:$min:$ampm";
        return $time;
}

function getTimeFormat($hour,$min,$ampm) {
        if (($ampm == "AM") & ($hour == "12")) {
                $hour = "00";
        } elseif (($ampm == "PM") & ($hour != "12")) {
                $hour = 12 + $hour;
        }
        $time = "$hour:$min:00";
        return $time;
}

function CalendarDeleteStory($qid) {
    $result = mysql_query("delete from nuke_events_queue where qid=$qid");
    if (!$result) {
	echo mysql_errno(). ": ".mysql_error(). "<br>";
	return;
    }
    Header("Location: admin.php?op=CalendarAdmin");
}

function printJavaScript() {
        echo "<script>
          function verify() {
                var msg = \""._CALVALIDERRORMSG."\\n__________________________________________________\\n\\n\";
                var errors = \"FALSE\";
                var starthour;
                var endhour;
                var startampm;
                var endampm;
                eventDate = new Date(document.calendar.month.options[document.calendar.month.selectedIndex].value +\"/\"+ document.calendar.day.options[document.calendar.day.selectedIndex].value+\"/\"+document.calendar.year.options[document.calendar.year.selectedIndex].value);
                endDate = new Date(document.calendar.endmonth.options[document.calendar.endmonth.selectedIndex].value + \"/\" + document.calendar.endday.options[document.calendar.endday.selectedIndex].value + \"/\" + document.calendar.endyear.options[document.calendar.endyear.selectedIndex].value);

                if (document.calendar.subject.value == \"\") {
                        errors = \"TRUE\";
                        msg += \""._CALVALIDSUBJECT."\\n\";
                }
                if (eventDate.getMonth()+1 != document.calendar.month.options[document.calendar.month.selectedIndex].value) {
                        errors = \"TRUE\";
                        msg += \"** "._CALVALIDEVENTDATE."\\n\";
                }
                if (endDate.getMonth()+1 != document.calendar.endmonth.options[document.calendar.endmonth.selectedIndex].value) {
                        errors = \"TRUE\";
                        msg += \"** "._CALVALIDENDDATE."\\n\";
                }
                if (endDate.getTime() < eventDate.getTime()) {
                        errors = \"TRUE\";
                        msg += \"** "._CALVALIDDATES."\\n\";
                }
                if (errors == \"TRUE\") {
                        msg += \"__________________________________________________\\n\\n"._CALVALIDFIXMSG."\\n\";
                        alert(msg);
                        return false;
                }
          }
          </script>";
}

function CalendarDisplayStory($qid) {
    global $user, $subject, $story, $tipath, $bgcolor1, $bgcolor2, $anonymous,$time24Hour,$useInternationalDates;
    include ('header.php');
    GraphicAdmin($hlpfile);
    printJavaScript();
    OpenTable();
    echo "<center><font size=\"4\"><b>"._CALSUBMISSIONSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $ttmon = $today[mon];
    if ($ttmon < 10){
	$ttmon = "0$ttmon";
    }
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    $result = mysql_query("SELECT qid, uid, uname, title, story, topic, eventDate, endDate, startTime, endTime, alldayevent, barcolor FROM nuke_events_queue where qid=$qid");
    list($qid, $uid, $uname, $subject, $story, $topic, $eventDate, $endDate, $startTime, $endTime, $alldayevent, $barcolor) = mysql_fetch_row($result);
    mysql_free_result($result);
    $subject = stripslashes($subject);
    $story = stripslashes($story);
    OpenTable();
    if($topic=="") {
        $topic = 1;
    }
    $result = mysql_query("select topicimage from nuke_topics where topicid=$topic");
    list($topicimage) = mysql_fetch_row($result);
    echo "<table border=\"0\" width=\"70%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>"
	."<table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"$bgcolor1\"><tr><td>"
	."<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\" alt=\"\">";
    themepreview($subject, $story);
    echo "</td></tr></table></td></tr></table><hr noshade size=\"1\" width=\"99%\"><br><br>";
    echo "<br><font size=\"3\">"
	."<form onSubmit=\"return verify();\" action=\"admin.php\" method=\"post\" NAME=\"calendar\">"
	."<b>"._CALNAMEFIELD."</b><br>"
	."<input type=\"text\" NAME=\"author\" size=\"25\" value=\"$uname\">";
    if ($uname != $anonymous) {
	$res = mysql_query("select email from nuke_users where uname='$uname'");
	list($email) = mysql_fetch_row($res);
	echo "&nbsp;&nbsp;<font size=\"2\">[ <a href=\"mailto:$email\">Email User</a> | <a href=\"replypmsg.php?send=1&amp;uname=$uname\">Send Private Message</a> ]</font>";
    }
    echo "<br><br><b>"._CALSUBTITLE."</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"><br><br>"
	."<br><b>"._CALTOPIC."</b> <select name=\"topic\">";
    $toplist = mysql_query("select topicid, topictext from nuke_topics order by topictext");
    echo "<option value=\"\">"._CALSELECTTOPIC."</option>\n";
    while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
        if ($topicid==$topic) {
	    $sel = "selected ";
	}
        echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    $Date_Array = explode("-",$eventDate);
    $Date_Array2 = explode("-",$endDate);
    if ($time24Hour) {
	$time_Array = explode(":",$startTime);
	$startTime = $time_Array[0].":".$time_Array[1];	
	$time_Array = explode(":",$endTime);
	$endTime = $time_Array[0].":".$time_Array[1];	
    } else {
	$startTime = getTime($startTime);
        $endTime = getTime($endTime);
    }
    $start_Array = explode(":",$startTime);
    $end_Array = explode(":",$endTime);
    echo "</select>
          <br><br>
          <b>"._CALEVENTDATETEXT."</b>:&nbsp;";
    if ($useInternationalDates) {
	echo "<select name=\"day\">";
        buildDaySelect($Date_Array[2]);
    	echo "</select>
          <select name=\"month\">";
        buildMonthSelect($Date_Array[1]);
    	echo "</select>";
    } else {
    	echo "<select name=\"month\">";
        buildMonthSelect($Date_Array[1]);
    	echo "</select>
          <select name=\"day\">";
        buildDaySelect($Date_Array[2]);
    	echo "</select>";
    }
    echo "<select name=\"year\">";
          buildYearSelect($Date_Array[0]);
    echo "</select>";
    echo "<br>
          <b>"._CALENDDATETEXT."</b>:&nbsp;";
    if ($useInternationalDates) {
    	echo "<select name=\"endday\">";
        buildDaySelect($Date_Array2[2]);
    	echo "</select>
          <select name=\"endmonth\">";
        buildMonthSelect($Date_Array2[1]);
    	echo "</select>";
    } else {
    	echo "<select name=\"endmonth\">";
        buildMonthSelect($Date_Array2[1]);
    	echo "</select>
          <select name=\"endday\">";
        buildDaySelect($Date_Array2[2]);
    	echo "</select>";
    }
    echo "<select name=\"endyear\">";
          buildYearSelect($Date_Array2[0]);
    echo "</select>";

    echo "<br><br><b>"._CALSTARTTIME."</b>:&nbsp;
          <select name=\"startHour\">";
    buildHourSelect($start_Array[0]);
    echo "</select>
          <select name=\"startMin\">";
    buildMinSelect($start_Array[1]);
    echo "</select>";
    if (!($time24Hour)) {
    	echo "<select name=\"startampm\">";
    	buildAMPMSelect($start_Array[2]);
    	echo "</select>";
    }
    echo "<br><b>"._CALENDTIME."</b>:
          <select name=\"endHour\">";
    buildHourSelect($end_Array[0]);
    echo "</select>
          <select name=\"endMin\">";
    buildMinSelect($end_Array[1]);
    echo "</select>";
    if (!($time24Hour)) {
    	echo "<select name=\"endampm\">";
    	buildAMPMSelect($end_Array[2]);
    	echo "</select>";
    }
    $check = "";
    if ($alldayevent == "1") {
        $check = "CHECKED=TRUE";
    }
    echo "<br><input name=\"alldayevent\" $check type=\"checkbox\" value=\"1\">&nbsp;<b>"._CALALLDAYEVENT."</b><br>
          <font size=1>("._CALTIMEIGNORED.")</font>";
    echo "<br><br><b>"._CALBARCOLORTEXT."</b>:<br>";
    if ($barcolor=="r") $sel="CHECKED"; else $sel="";
    echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"r\">Red<img src=\"images/calendar/ballr.gif\">&nbsp;&nbsp;";
    if ($barcolor=="g") $sel="CHECKED"; else $sel="";
    echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"g\">Green<img src=\"images/calendar/ballg.gif\">&nbsp;&nbsp;";
    if ($barcolor=="b") $sel="CHECKED"; else $sel="";
    echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"b\">Blue<img src=\"images/calendar/ballb.gif\">&nbsp;&nbsp;";
    if ($barcolor=="w") $sel="CHECKED"; else $sel="";
    echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"w\">White<img src=\"images/calendar/ballw.gif\">&nbsp;&nbsp;";
    if ($barcolor=="y") $sel="CHECKED"; else $sel="";
    echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"y\">Yellow<img src=\"images/calendar/bally.gif\">
          <br>";
    echo "<br><br><b>"._CALARTICLETEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$story</textarea><br><br>"
	."<input type=\"hidden\" NAME=\"qid\" size=\"50\" value=\"$qid\">"
	."<input type=\"hidden\" NAME=\"uid\" size=\"50\" value=\"$uid\">";
    echo "<br><br>
	  <select name=\"op\">
	  <option value=\"CalendarDeleteStory\">"._CALDELETESTORY."</option>
	  <option value=\"CalendarPreviewAgain\" selected>"._CALPREVIEWSTORY."</option>
	  <option value=\"CalendarPostStory\">"._CALPOSTSTORY."</option>
	  </select>
          <input type=\"submit\" value=\""._OK."\">
	  </form>";
    CloseTable();
    include ('footer.php');
}

function CalendarPreviewStory($qid, $uid, $author, $subject, $hometext, $topic, $day, $month, $year, $endday, $endmonth, $endyear, $startHour, $startMin, $startampm, $endHour, $endMin, $endampm, $alldayevent, $barcolor) {
    global $user, $boxstuff, $tipath, $anonymous, $bgcolor1, $bgcolor2,$time24Hour,$useInternationalDates;
    include ('header.php');
    GraphicAdmin($hlpfile);
    printJavaScript();
    OpenTable();
    echo "<center><font size=\"4\"><b>"._CALSUBMISSIONSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $today = getdate();
    $tday = $today[mday];
    if ($tday < 10){
	$tday = "0$tday";
    }
    $tmonth = $today[month];
    $tyear = $today[year];
    $thour = $today[hours];
    if ($thour < 10){
	$thour = "0$thour";
    }
    $tmin = $today[minutes];
    if ($tmin < 10){
	$tmin = "0$tmin";
    }
    $tsec = $today[seconds];
    if ($tsec < 10){
	$tsec = "0$tsec";
    }
    $date = "$tmonth $tday, $tyear @ $thour:$tmin:$tsec";
    $subject = stripslashes($subject);
    $hometext = stripslashes($hometext);
    OpenTable();

    $result = mysql_query("select topicimage from nuke_topics where topicid=$topic");
    list($topicimage) = mysql_fetch_row($result);
    echo "<table width=\"70%\" bgcolor=\"$bgcolor2\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\"align=\"center\"><tr><td>"
	."<table width=\"100%\" bgcolor=\"$bgcolor1\" cellpadding=\"8\" cellspacing=\"1\" border=\"0\"><tr><td>"
	."<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\">";
    themepreview($subject, $hometext);
    echo "</td></tr></table></td></tr></table><hr noshade size=\"1\" width=\"99%\"><br><br>"
	."<br>";
    echo "<font size=\"3\">"
	."<form onSubmit=\"return verify();\" action=\"admin.php\" method=\"post\" NAME=\"calendar\">"
	."<b>"._NAME."</b><br>"
	."<input type=\"text\" name=\"author\" size=\"25\" value=\"$author\">";
    if ($author != $anonymous) {
	$res = mysql_query("select email from nuke_users where uname='$author'");
	list($email) = mysql_fetch_row($res);
	echo "&nbsp;&nbsp;<font size=\"2\">[ <a href=\"mailto:$email\">Email User</a> | <a href=\"replypmsg.php?send=1&amp;uname=$author\">Send Private Message</a> ]</font>";
    }
    echo "<br><br><b>"._CALSUBTITLE."</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"><br><br>";

    echo "<b>"._CALTOPIC."</b> <select name=\"topic\">";
    $toplist = mysql_query("select topicid, topictext from nuke_topics order by topictext");
    echo "<option value=\"\">"._CALSELECTTOPIC."</option>\n";
    while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
        if ($topicid==$topic) {
	    $sel = "selected ";
	}
        echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    echo "</select>
          <br><br>
          <b>"._CALEVENTDATETEXT."</b>:&nbsp;";
    if ($useInternationalDates) {
    	echo "<select name=\"day\">";
        buildDaySelect($day);
    	echo "</select>
          <select name=\"month\">";
        buildMonthSelect($month);
    	echo "</select>";
    } else {
    	echo "<select name=\"month\">";
        buildMonthSelect($month);
    	echo "</select>
          <select name=\"day\">";
        buildDaySelect($day);
    	echo "</select>";
    }
    echo "<select name=\"year\">";
          buildYearSelect($year);
    echo "</select>";
    echo "<br>
          <b>"._CALENDDATETEXT."</b>:&nbsp;";
    if ($useInternationalDates) {
    	echo "<select name=\"endday\">";
        buildDaySelect($endday);
    	echo "</select>
          <select name=\"endmonth\">";
        buildMonthSelect($endmonth);
    	echo "</select>";
    } else {
    	echo "<select name=\"endmonth\">";
        buildMonthSelect($endmonth);
    	echo "</select>
          <select name=\"endday\">";
        buildDaySelect($endday);
    	echo "</select>";
    }
    echo "<select name=\"endyear\">";
          buildYearSelect($endyear);
    echo "</select>";

    echo "<br><br><b>"._CALSTARTTIME."</b>:&nbsp;
          <select name=\"startHour\">";
    buildHourSelect($startHour);
    echo "</select>
          <select name=\"startMin\">";
    buildMinSelect($startMin);
    echo "</select>";
    if (!($time24Hour)) {
    	echo "<select name=\"startampm\">";
    	buildAMPMSelect($startampm);
    	echo "</select>";
    }
    echo "<br><b>"._CALENDTIME."</b>:
          <select name=\"endHour\">";
    buildHourSelect($endHour);
    echo "</select>
          <select name=\"endMin\">";
    buildMinSelect($endMin);
    echo "</select>";
    if (!($time24Hour)) {
        echo "<select name=\"endampm\">";
    	buildAMPMSelect($endampm);
    	echo "</select>";
    }
    $check = "";
    if ($alldayevent == "1") {
        $check = "CHECKED=TRUE";
    }
    echo "<br><input name=\"alldayevent\" $check type=\"checkbox\" value=\"1\">&nbsp;<b>"._CALALLDAYEVENT."</b><br>
          <font size=1>("._CALTIMEIGNORED.")</font>";
    echo "<br><br><b>"._CALBARCOLORTEXT."</b>:<br>";
    if ($barcolor=="r") $sel="CHECKED"; else $sel="";
    echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"r\">Red<img src=\"images/calendar/ballr.gif\">&nbsp;&nbsp;";
    if ($barcolor=="g") $sel="CHECKED"; else $sel="";
    echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"g\">Green<img src=\"images/calendar/ballg.gif\">&nbsp;&nbsp;";
    if ($barcolor=="b") $sel="CHECKED"; else $sel="";
    echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"b\">Blue<img src=\"images/calendar/ballb.gif\">&nbsp;&nbsp;";
    if ($barcolor=="w") $sel="CHECKED"; else $sel="";
    echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"w\">White<img src=\"images/calendar/ballw.gif\">&nbsp;&nbsp;";
    if ($barcolor=="y") $sel="CHECKED"; else $sel="";
    echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"y\">Yellow<img src=\"images/calendar/bally.gif\">
          <br>";
    echo "<br><br><b>"._CALARTICLETEXT."</b><br>"
	."<textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$hometext</textarea><br><br>"
	."<input type=\"hidden\" NAME=\"qid\" size=\"50\" value=\"$qid\">"
	."<input type=\"hidden\" NAME=\"uid\" size=\"50\" value=\"$uid\">";

    echo "<br><br>"
	."<select name=\"op\">"
	."<option value=\"CalendarDeleteStory\">"._CALDELETESTORY."</option>"
	."<option value=\"CalendarPreviewAgain\" selected>"._CALPREVIEWSTORY."</option>"
	."<option value=\"CalendarPostStory\">"._CALPOSTSTORY."</option>"
	."</select>"
	."<input type=\"submit\" value=\""._OK."\">"
	."</form>";
    CloseTable();
    include ('footer.php');
}

function CalendarPostStory($qid, $uid, $author, $subject, $hometext, $topic, $day, $month, $year, $endday, $endmonth, $endyear, $startHour, $startMin, $startampm, $endHour, $endMin, $endampm, $alldayevent, $barcolor) {
    global $aid, $time24Hour;
    if ($uid == 1) $author = "Anonymous";
    $subject = stripslashes(FixQuotes($subject));
    $hometext = stripslashes(FixQuotes($hometext));
    $newDate = "$year-$month-$day";
    $endDate = "$endyear-$endmonth-$endday";
    if ($time24Hour) {
    	$startTime = "$startHour:$startMin:00";
    	$endTime = "$endHour:$endMin:00";
    } else {
    	$startTime = getTimeFormat($startHour,$startMin,$startampm);
    	$endTime = getTimeFormat($endHour,$endMin,$endampm);
    }
    $result = mysql_query("insert into nuke_events values (NULL, '$aid', '$subject', now(), '$hometext', '0', '0', '$topic','$author','$newDate','$endDate','$startTime','$endTime', '$alldayevent', '$barcolor')");
    if (!$result) {
        echo mysql_errno(). ": ".mysql_error(). "<br>";
        return;
    }
    if ($uid == 1) {
    } else {
            mysql_query("update nuke_users set counter=counter+1 where uid='$uid'");
    }
    mysql_query("update nuke_authors set counter=counter+1 where aid='$aid'");
    CalendarDeleteStory($qid);
}

function CalendarEditStory($eid) {
    global $user, $tipath, $bgcolor1, $bgcolor2, $aid, $time24Hour,$useInternationalDates;
    $result = mysql_query("select radminarticle, radminsuper from nuke_authors where aid='$aid'");
    list($radminarticle, $radminsuper) = mysql_fetch_row($result);
    $result2 = mysql_query("select aid from nuke_events where eid='$eid'");
    list($aaid) = mysql_fetch_row($result2);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
	include ('header.php');
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._CALSUBMISSIONSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	$result = mysql_query("SELECT aid, title, hometext, topic, informant, eventDate, endDate, startTime, endTime, alldayevent, barcolor FROM nuke_events where eid=$eid");
        list($aid, $subject, $story, $topic, $author, $eventDate, $endDate, $startTime, $endTime, $alldayevent, $barcolor) = mysql_fetch_row($result);
        mysql_free_result($result);
        $subject = stripslashes($subject);
        $story = stripslashes($story);
        if ($author == "") $author="Anonynous";
        OpenTable();
        if($topic=="") {
                $topic = 1;
        }
        $result = mysql_query("select topicimage from nuke_topics where topicid=$topic");
        list($topicimage) = mysql_fetch_row($result);
        echo "<table border=\"0\" width=\"70%\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>"
                ."<table border=\"0\" width=\"100%\" cellpadding=\"8\" cellspacing=\"1\" bgcolor=\"$bgcolor1\"><tr><td>"
                ."<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\" alt=\"\">";
        themepreview($subject, $story);
        echo "</td></tr></table></td></tr></table><hr noshade size=\"1\" width=\"99%\"><br><br>";
        echo "<br><font size=\"3\">"
                ."<form onSubmit=\"return verify();\" action=\"admin.php\" method=\"post\" NAME=\"calendar\">"
                ."<b>"._CALNAMEFIELD."</b><br>"
                ."<input type=\"text\" NAME=\"author\" size=\"25\" value=\"$author\">";
        if ($uname != $anonymous) {
                $res = mysql_query("select email from nuke_users where uname='$uname'");
                list($email) = mysql_fetch_row($res);
                echo "&nbsp;&nbsp;<font size=\"2\">[ <a href=\"mailto:$email\">Email User</a> | <a href=\"replypmsg.php?send=1&amp;uname=$uname\">Send Private Message</a> ]</font>";
        }
        echo "<br><br><b>"._CALSUBTITLE."</b><br>"
                ."<input type=\"text\" name=\"subject\" size=\"50\" value=\"$subject\"><br><br>"
                ."<br><b>"._CALTOPIC."</b> <select name=\"topic\">";
        $toplist = mysql_query("select topicid, topictext from nuke_topics order by topictext");
        echo "<option value=\"\">"._CALSELECTTOPIC."</option>\n";
        while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
                if ($topicid==$topic) {
                $sel = "selected ";
                }
                echo "<option $sel value=\"$topicid\">$topics</option>\n";
                $sel = "";
        }
        $Date_Array = explode("-",$eventDate);
        $Date_Array2 = explode("-",$endDate);
        if ($time24Hour) {
		$time_Array = explode(":",$startTime);
		$startTime = $time_Array[0].":".$time_Array[1];	
		$time_Array = explode(":",$endTime);
		$endTime = $time_Array[0].":".$time_Array[1];	
	} else {
		$startTime = getTime($startTime);
        	$endTime = getTime($endTime);
        }
        $start_Array = explode(":",$startTime);
        $end_Array = explode(":",$endTime);
        echo "</select>
                <br><br>
                <b>"._CALEVENTDATETEXT."</b>:&nbsp;";
	if ($useInternationalDates) {
        	echo "<select name=\"day\">";
                buildDaySelect($Date_Array[2]);
        	echo "</select>
                  <select name=\"month\">";
                buildMonthSelect($Date_Array[1]);
        	echo "</select>";
	} else {
		echo "<select name=\"month\">";
                buildMonthSelect($Date_Array[1]);
        	echo "</select>
                  <select name=\"day\">";
                buildDaySelect($Date_Array[2]);
        	echo "</select>";	
	}
        echo "<select name=\"year\">";
                buildYearSelect($Date_Array[0]);
        echo "</select>";
        echo "<br>
                <b>"._CALENDDATETEXT."</b>:&nbsp;";
	if ($useInternationalDates) {
        	echo "<select name=\"endday\">";
                buildDaySelect($Date_Array2[2]);
        	echo "</select>
                  <select name=\"endmonth\">";
                buildMonthSelect($Date_Array2[1]);
        	echo "</select>";
	} else {
		echo "<select name=\"endmonth\">";
                buildMonthSelect($Date_Array2[1]);
        	echo "</select>
                  <select name=\"endday\">";
                buildDaySelect($Date_Array2[2]);
        	echo "</select>";
	}
        echo "<select name=\"endyear\">";
                buildYearSelect($Date_Array2[0]);
        echo "</select>";

        echo "<br><br><b>"._CALSTARTTIME."</b>:&nbsp;
                <select name=\"startHour\">";
        buildHourSelect($start_Array[0]);
        echo "</select>
                <select name=\"startMin\">";
        buildMinSelect($start_Array[1]);
        echo "</select>";
	if (!($time24Hour)) {
        	echo "<select name=\"startampm\">";
        	buildAMPMSelect($start_Array[2]);
        	echo "</select>";
	}
        echo "<br><b>"._CALENDTIME."</b>:
                <select name=\"endHour\">";
        buildHourSelect($end_Array[0]);
        echo "</select>
                <select name=\"endMin\">";
        buildMinSelect($end_Array[1]);
        echo "</select>";
	if (!($time24Hour)) {
                echo "<select name=\"endampm\">";
        	buildAMPMSelect($end_Array[2]);
        	echo "</select>";
	}
        $check = "";
        if ($alldayevent == "1") {
                $check = "CHECKED=TRUE";
        }
        echo "<br><input name=\"alldayevent\" $check type=\"checkbox\" value=\"1\">&nbsp;<b>"._CALALLDAYEVENT."</b><br>
                <font size=1>("._CALTIMEIGNORED.")</font>";
        echo "<br><br><b>"._CALBARCOLORTEXT."</b>:<br>";
        if ($barcolor=="r") $sel="CHECKED"; else $sel="";
        echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"r\">Red<img src=\"images/calendar/ballr.gif\">&nbsp;&nbsp;";
        if ($barcolor=="g") $sel="CHECKED"; else $sel="";
        echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"g\">Green<img src=\"images/calendar/ballg.gif\">&nbsp;&nbsp;";
        if ($barcolor=="b") $sel="CHECKED"; else $sel="";
        echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"b\">Blue<img src=\"images/calendar/ballb.gif\">&nbsp;&nbsp;";
        if ($barcolor=="w") $sel="CHECKED"; else $sel="";
        echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"w\">White<img src=\"images/calendar/ballw.gif\">&nbsp;&nbsp;";
        if ($barcolor=="y") $sel="CHECKED"; else $sel="";
        echo "<input type=\"radio\" $sel name=\"barcolor\" value=\"y\">Yellow<img src=\"images/calendar/bally.gif\">
                <br>";
        echo "<br><br><b>"._CALARTICLETEXT."</b><br>"
                ."<textarea wrap=\"virtual\" cols=\"50\" rows=\"7\" name=\"hometext\">$story</textarea><br><br>";
        echo "<br><br>
                <input type=hidden value=\"$eid\" name=\"eid\">
                <input type=hidden value=\"CalendarChangeStory\" name=\"op\">
                <input type=\"submit\" value=\""._CALPOSTSTORY."\">
                </form>";
	CloseTable();
	include("footer.php");
    }
}

function CalendarRemoveStory($eid, $ok=0) {
    global $module_name, $aid;
    $result = mysql_query("select counter, radminarticle, radminsuper from nuke_authors where aid='$aid'");
    list($counter, $radminarticle, $radminsuper) = mysql_fetch_row($result);
    $result2 = mysql_query("select aid from nuke_events where eid='$eid'");
    list($aaid) = mysql_fetch_row($result2);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
	if($ok) {
	    $counter--;
    	    mysql_query("DELETE FROM nuke_events where eid=$eid");
	    $result = mysql_query("update nuke_authors set counter='$counter' where aid='$aid'");
	    Header("Location: modules.php?op=modload&name=$module_name&file=index");
	} else {
	    include("header.php");
	    GraphicAdmin($hlpfile);
	    OpenTable();
	    echo "<center><font size=\"4\"><b>"._CALSUBMISSIONSADMIN."</b></font></center>";
	    CloseTable();
	    echo "<br>";
	    OpenTable();
	    echo "<center>"._CALREMOVETEST." $eid<br>";
	    echo "<br>[ <a href=\"modules.php?op=modload&name=$module_name&file=index\">"._CALNO."</a> | <a href=\"admin.php?op=CalendarRemoveStory&eid=$eid&ok=1\">"._CALYES."</a> ]</center>";
    	    CloseTable();
	    include("footer.php");
	}
    } else {
	include ('header.php');
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._CALSUBMISSIONSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<center><b>"._CALNOTAUTHORIZED1."</b><br><br>"
	    .""._CALNOTAUTHORIZED2."<br><br>";
	CloseTable();
	include("footer.php");
    }
}


function CalendarChangeStory($eid, $uid, $author, $subject, $hometext, $topic, $day, $month, $year, $endday, $endmonth, $endyear, $startHour, $startMin, $startampm, $endHour, $endMin, $endampm, $alldayevent, $barcolor) {
    global $aid, $module_name,$time24Hour;
    $result = mysql_query("select radminarticle, radminsuper from nuke_authors where aid='$aid'");
    list($radminarticle, $radminsuper) = mysql_fetch_row($result);
    $result2 = mysql_query("select aid from nuke_events where eid='$eid'");
    list($aaid) = mysql_fetch_row($result2);
    if (($radminarticle == 1) AND ($aaid == $aid) OR ($radminsuper == 1)) {
	$subject = stripslashes(FixQuotes($subject));
	$hometext = stripslashes(FixQuotes($hometext));
	if ($time24Hour) {
    		$startTime = "$startHour:$startMin:00";
    		$endTime = "$endHour:$endMin:00";
    	} else {
    		$startTime = getTimeFormat($startHour,$startMin,$startampm);
    		$endTime = getTimeFormat($endHour,$endMin,$endampm);
    	}
	mysql_query("update nuke_events set title='$subject', hometext='$hometext', topic='$topic', informant='$author', eventDate='$year-$month-$day', endDate='$endyear-$endmonth-$endday', startTime='$startTime', endTime='$endTime', alldayevent='$alldayevent', barcolor='$barcolor' where eid=$eid");
	Header("Location: modules.php?op=modload&name=$module_name&file=index");
    }
}

function CalendarSubmissions() {
    global $hlpfile, $admin, $bgcolor1, $bgcolor2;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._CALSUBMISSIONSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $result = mysql_query("SELECT qid, title, timestamp FROM nuke_events_queue order by timestamp");
    if(mysql_num_rows($result) == 0) {
        echo "<table width=\"100%\"><tr><td bgcolor=\"$bgcolor1\" align=\"center\"><b>"._CALNOSUBMISSIONS."</b></td></tr></table>\n";
    } else {
        echo "<center><font size=\"2\"><b>"._CALNEWSUBMISSIONS."</b></font><table width=\"100%\" border=\"1\" bgcolor=\"$bgcolor2\">\n";
        while (list($qid, $subject, $timestamp) = mysql_fetch_row($result)) {
                echo "<tr>\n"
                        ."<td align=\"center\" width=60><font size=\"2\">&nbsp;[<a href=\"admin.php?op=CalendarDeleteStory&amp;qid=$qid\">"._CALDELETE."</a>]&nbsp;</td>\n"
                        ."<td width=100%><font size=3>\n";
                if ($subject == "") {
                        echo "&nbsp;<a href=\"admin.php?op=CalendarDisplayStory&amp;qid=$qid\">"._CALNOSUBJECT."</a></font>\n";
                } else {
                        echo "&nbsp;<a href=\"admin.php?op=CalendarDisplayStory&amp;qid=$qid\">$subject</a></font>\n";
                }
                $timestamp = ereg_replace(" ", "@", $timestamp);
                echo "</td><td align=\"right\"><font size=\"2\">&nbsp;$timestamp&nbsp;</font></td></tr>\n";
        }
        echo "</table>\n";
    }
    CloseTable();
    include ("footer.php");
}

switch($op) {

    case "CalendarDisplayStory":
    CalendarDisplayStory($qid);
    break;

    case "CalendarPreviewAgain":
    CalendarPreviewStory($qid, $uid, $author, $subject, $hometext, $topic, $day, $month, $year, $endday, $endmonth, $endyear, $startHour, $startMin, $startampm, $endHour, $endMin, $endampm, $alldayevent, $barcolor);
    break;

    case "CalendarPostStory":
    CalendarPostStory($qid, $uid, $author, $subject, $hometext, $topic, $day, $month, $year, $endday, $endmonth, $endyear, $startHour, $startMin, $startampm, $endHour, $endMin, $endampm, $alldayevent, $barcolor);
    break;

    case "CalendarEditStory":
    CalendarEditStory($eid);
    break;

    case "CalendarRemoveStory":
    CalendarRemoveStory($eid, $ok);
    break;

    case "CalendarChangeStory":
    CalendarChangeStory($eid, $uid, $author, $subject, $hometext, $topic, $day, $month, $year, $endday, $endmonth, $endyear, $startHour, $startMin, $startampm, $endHour, $endMin, $endampm, $alldayevent, $barcolor);
    break;

    case "CalendarDeleteStory":
    CalendarDeleteStory($qid);
    break;

    case "CalendarAdmin":
    CalendarSubmissions();
    break;

}

} else {
    echo "Access Denied";
}

?>
