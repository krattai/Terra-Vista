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
/* prefix aware by duns http://duns-ground.fr.st        */
/********************************************************/
/********************************************************/

$index = 1;
$allowanonymous = 1;
$module_name = "Calendar";  // CHANGE THIS IF YOU CHANGE THE MODULES FOLDER NAME

include("modules/$module_name/config.php");

/********************************************************/

if (!eregi("modules.php", $PHP_SELF)) {
    die ("classement:You can't access this file directly...");
}

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

function getTimeFormat($hour,$min,$ampm) {
        if (($ampm == "AM") & ($hour == "12")) {
                $hour = "00";
        } elseif (($ampm == "PM") & ($hour != "12")) {
                $hour = 12 + $hour;
        }
        $time = "$hour:$min:00";
        return $time;
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


function defaultDisplay($type) {
    global $AllowableHTML,$module_name,$time24Hour,$useInternationalDates, $prefix;
    include ('header.php');
    global $user, $cookie, $anonymous;
    printJavaScript();
    OpenTable();
    echo "<center><font size=\"4\"><b>"._CALSUBMITNAME."</b></font><br><br>";
    echo "<font size=\"2\"><i>"._CALSUBMITADVICE."</i></font></center><br><br>";
    CloseTable();
    echo "<br>";
    OpenTable();
    if (is_user($user)) getusrinfo($user);
    include("functions.php");
    echo "<p><form onSubmit=\"return verify();\" action=\"modules.php?op=modload&name=".$module_name."&file=submit\" method=\"post\" NAME=\"calendar\">";
    echo "<b>"._CALYOURNAME.":</b> ";
    if (is_user($user)) {
	cookiedecode($user);
	echo "<a href=\"user.php\">$cookie[1]</a> <font size=2>[ <a href=\"user.php?op=logout\">"._CALLOGOUT."</a> ]</font>";
    } else {
    	echo "$anonymous <font size=2>[ <a href=\"user.php\">"._NEWUSER."</a> ]</font>";
    }
    echo "<br><br>"
        ."<b>"._CALSUBTITLE."</b> "
        ."("._CALBEDESCRIPTIVE.")<br>"
        ."<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"80\"><br><font size=\"2\"></font>"
        ."<br><br>"
        ."<b>"._CALTOPIC.":</b> <select name=\"topic\">";
    $toplist = mysql_query("select topicid, topictext from $prefix"._topics." order by topictext");
    echo "<option value=\"\">"._CALSELECTTOPIC."</option>\n";
    while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
        if ($topicid==$topic) {
	    $sel = "selected ";
	}
    	echo "<option $sel value=\"$topicid\">$topics</option>\n";
	$sel = "";
    }
    $Date = Date("m/d/Y");
    $Date_Array = explode("/", $Date);

    echo "</select>
          <br><br>
          <b>"._CALEVENTDATETEXT."</b>:&nbsp;";
    if ($useInternationalDates) {
    	echo "<select name=\"day\">";
        buildDaySelect($Date_Array[1]);
    	echo "</select>
        	<select name=\"month\">";
        buildMonthSelect($Date_Array[0]);
    	echo "</select>";
    } else {
    	echo "<select name=\"month\">";
        buildMonthSelect($Date_Array[0]);
    	echo "</select>
        	<select name=\"day\">";
        buildDaySelect($Date_Array[1]);
    	echo "</select>";
    }
    echo "<select name=\"year\">";
    buildYearSelect($Date_Array[2]);
    echo "</select>";
    echo "<br>
          <b>"._CALENDDATETEXT."</b>:&nbsp;";
    if ($useInternationalDates) {
    	echo "<select name=\"endday\">";
        buildDaySelect($Date_Array[1]);
    	echo "</select>
          <select name=\"endmonth\">";
        buildMonthSelect($Date_Array[0]);
    	echo "</select>";
    } else {
    	echo "<select name=\"endmonth\">";
        buildMonthSelect($Date_Array[0]);
    	echo "</select>
          <select name=\"endday\">";
        buildDaySelect($Date_Array[1]);
    	echo "</select>";
    }
    echo "<select name=\"endyear\">";
          buildYearSelect($Date_Array[2]);
    echo "</select>";

    echo "<br><br><b>"._CALSTARTTIME."</b>:&nbsp;
          <select name=\"startHour\">";
    buildHourSelect(9);
    echo "</select>
          <select name=\"startMin\">";
    buildMinSelect(0);
    echo "</select>";
    if (!($time24Hour)) {
    	echo "<select name=\"startampm\">";
    	buildAMPMSelect("AM");
    	echo "</select>";
    }

    echo "<br><b>"._CALENDTIME."</b>:
          <select name=\"endHour\">";
    buildHourSelect(11);
    echo "</select>
          <select name=\"endMin\">";
    buildMinSelect(0);
    echo "</select>";
    if (!($time24Hour)) {
    	echo "<select name=\"endampm\">";
    	buildAMPMSelect("AM");
    	echo "</select>";
    }
    echo "<br><input name=\"alldayevent\" type=\"checkbox\" value=\"1\">&nbsp;<b>"._CALALLDAYEVENT."</b><br>
          <font size=1>("._CALTIMEIGNORED.")</font>";
    echo "<br><br><b>"._CALBARCOLORTEXT."</b>:<br>
          <input type=\"radio\" name=\"barcolor\" value=\"r\">Red<img src=\"images/calendar/ballr.gif\">&nbsp;&nbsp;
          <input type=\"radio\" CHECKED name=\"barcolor\" value=\"g\">Green<img src=\"images/calendar/ballg.gif\">&nbsp;&nbsp;
          <input type=\"radio\" name=\"barcolor\" value=\"b\">Blue<img src=\"images/calendar/ballb.gif\">&nbsp;&nbsp;
          <input type=\"radio\" name=\"barcolor\" value=\"w\">White<img src=\"images/calendar/ballw.gif\">&nbsp;&nbsp;
          <input type=\"radio\" name=\"barcolor\" value=\"y\">Yellow<img src=\"images/calendar/bally.gif\">
          <br>";
    echo "<br><br>
          <b>"._CALARTICLETEXT."</b>:
          ("._CALHTMLISFINE.")<br>
          <textarea cols=\"50\" rows=\"12\" name=\"story\"></textarea><br>
          <font size=\"2\">"._CALALLOWEDHTML."<br>";
    while (list($key,) = each($AllowableHTML)) echo " &lt;".$key."&gt;";
    echo "<br>("._CALAREYOUSURE.")</font><br><br>
          <input type=\"hidden\" name=\"type\" value=\"$type\">
          <input type=\"submit\" name=\"op2\" value=\"Preview_Event\"> ("._CALSUBPREVIEW.")</form>";
    CloseTable();
    include ('footer.php');
}

function PreviewStory($name, $address, $subject, $day, $month, $year, $endday, $endmonth, $endyear, $story, $topic, $type, $startHour, $startMin, $startampm, $endHour, $endMin, $endampm, $alldayevent, $barcolor) {
    global $user, $cookie, $tipath, $bgcolor1, $bgcolor2, $anonymous,$module_name,$time24Hour,$useInternationalDates, $prefix;
    include ('header.php');
    $subject = stripslashes(check_html($subject, "nohtml"));
    $story = stripslashes(check_html($story, ""));
    printJavaScript();
    OpenTable();
    echo "<center><font size=\"4\"><b>"._CALNEWSUBPREVIEW."</b></font>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><i>"._CALSTORYLOOK."</i></center><br><br>";
    echo "<table width=\"70%\" bgcolor=\"$bgcolor2\" cellpadding=\"0\" cellspacing=\"1\" border=\"0\"align=\"center\"><tr><td>"
	."<table width=\"100%\" bgcolor=\"$bgcolor1\" cellpadding=\"8\" cellspacing=\"1\" border=\"0\"><tr><td>";
    if ($topic=="") {
        $topicimage="AllTopics.gif";
        $warning = "<center><blink><b>"._CALTOPICERROR."</b></blink></center>";
    } else {
        $warning = "";
        $result = mysql_query("select topicimage from $prefix"._topics." where topicid='$topic'");
        list($topicimage) = mysql_fetch_row($result);
    }
    echo "<img src=\"$tipath$topicimage\" border=\"0\" align=\"right\">
          <b>"._CALEVENTDATEPREVIEW."</b>
          $month/$day/$year<br><br><br>";
    themepreview($subject, nl2br($story));
    echo "$warning"
	."</td></tr></table></td></tr></table>"
	."<br><br><center><font size=\"1\">"._CALCHECKSTORY."</font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<p><form onSubmit=\"return verify();\" action=\"modules.php?op=modload&name=".$module_name."&file=submit\" method=\"post\" NAME=\"calendar\">"
	."<b>"._CALYOURNAME.":</b> ";
    if (is_user($user)) {
    	cookiedecode($user);
	echo "<a href=\"user.php\">$cookie[1]</a> <font size=\"2\">[ <a href=\"user.php?op=logout\">"._CALLOGOUT."</a> ]</font>";
    } else {
	echo "$anonymous";
    }
    echo "<br><br><b>"._CALSUBTITLE.":</b><br>"
	."<input type=\"text\" name=\"subject\" size=\"50\" maxlength=\"80\" value=\"$subject\">"
	."<br><br><b>"._CALTOPIC.": </b><select name=\"topic\">";
    $toplist = mysql_query("select topicid, topictext from $prefix"._topics." order by topictext");
    echo "<OPTION VALUE=\"\">"._CALSELECTTOPIC."</option>\n";
    while(list($topicid, $topics) = mysql_fetch_row($toplist)) {
        if ($topicid==$topic) { $sel = "selected "; }
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
    echo "<br><br>
          <br><br><b>"._CALARTICLETEXT."</b>:
          ("._CALHTMLISFINE.")<br>
          <textarea cols=\"50\" rows=\"12\" name=\"story\">$story</textarea><br>
          <font size=\"2\">("._CALAREYOUSURE.")</font><br><br>
          <input type=\"hidden\" name=\"type\" value=\"$type\">
          <input type=\"submit\" name=\"op2\" value=\"Preview_Event\"> <input type=\"submit\" name=\"op2\" value=\"Submit_Event\">
	  </form>";
    CloseTable();
    include ('footer.php');
}

function SubmitStory($name, $address, $subject, $day, $month, $year, $endday, $endmonth, $endyear, $story, $topic, $type, $startHour, $startMin, $startampm, $endHour, $endMin, $endampm, $alldayevent, $barcolor) {
    global $user, $EditedMessage, $cookie, $anonymous, $notify, $notify_email, $notify_subject, $notify_message,
    $notify_from,$module_name,$time24Hour, $prefix;
    if (is_user($user)) {
    	cookiedecode($user);
	$uid = $cookie[0];
	$name = $cookie[1];
    } else {
    	$uid = 1;
	$name = "$anonymous";
    }
    $subject = stripslashes(FixQuotes(check_html($subject, "nohtml")));
    $story = nl2br(stripslashes(FixQuotes(check_html($story, ""))));
    $newDate = "$year-$month-$day";
    $endDate = "$endyear-$endmonth-$endday";
    if ($time24Hour) {
    	$startTime = "$startHour:$startMin:00";
    	$endTime = "$endHour:$endMin:00";
    } else {
    	$startTime = getTimeFormat($startHour,$startMin,$startampm);
    	$endTime = getTimeFormat($endHour,$endMin,$endampm);
    }
    $result = mysql_query("insert into $prefix"._events_queue." values (NULL, '$uid', '$name', '$subject', '$story', now(), '$topic', '$newDate','$endDate','$startTime','$endTime','$alldayevent', '$barcolor')");
    if(!$result) {
    	echo mysql_errno(). ": ".mysql_error(). "<br>";
	exit();
    }

    include ('header.php');
    OpenTable();
    $result = mysql_query("select * from $prefix"._events_queue."");
    $waiting = mysql_num_rows($result);
    echo "<center><font size=\"4\">"._CALSUBSENT."</font><br><br>"
	."<font size=\"3\"><b>"._CALTHANKSSUB."</b><br><br>"
	.""._CALSUBTEXT.""
	."<br>"._CALWEHAVESUB." $waiting "._CALWAITING."";
    CloseTable();

        OpenTable();
    /**** Get todays date */
    $Today_d = Date("d");
    $Today_m = Date("m");
    $Today_y = Date("Y");
    echo "<center><table border=0 cellpadding=0 cellspacing=0 width=100%>
        <tr>
                <td rowspan=3 width=10 valign=\"top\"><img src=\"images/calendar/admin/calendar.gif\"></td>
        </tr>
        <tr>
                <td><table border=0 cellpadding=0 cellspacing=0 width=100%>
                        <tr>
                                <td>&nbsp;&nbsp;<font size=4><b>"._CALNAME."</b></font></td>
                                <td></td>
                        </tr>
                        </table>
                </td>
        </tr>
        <tr>
                <td align=\"center\">
                        <table border=0 width=95% cellpadding=0 cellspacing=0>
                        <tr>
                                <td width=20% valign=\"top\">
                                        <strong><big>·</big></strong> <a href=\"modules.php?op=modload&name=$module_name&file=submit\">"._CALEVENTLINK."</a><br>
                                        <strong><big>·</big></strong> <a href=\"modules.php?op=modload&name=$module_name&file=index&type=day\">"._CALDAYLINK."</a>
                                </td>
                                <td width=20% valign=\"top\">
                                        <strong><big>·</big></strong> <a href=\"modules.php?op=modload&name=$module_name&file=index&type=month\">"._CALMONTHLINK."</a><br>
                                        <strong><big>·</big></strong> <a href=\"modules.php?op=modload&name=$module_name&file=index&type=year\">"._CALYEARLINK."</a>
                                        </td>
                                <td width=60% valign=\"top\">
                                        <form name=\"jump\">";
                                        echo "<b>"._CALJUMPTOTEXT."</b>:<br>
                                              <select name=\"jumpmonth\">";
                                                buildMonthSelect($Today_m);
                                        echo "</select>
                                              <select name=\"jumpday\">";
                                                buildDaySelect($Today_d);
                                        echo "</select>
                                              <select name=\"jumpyear\">";
                                                buildYearSelect($Today_y);
                                        echo "</select>
                                              <select name=\"view\">
                                                <option value=\"day\">Day View</option>
                                                <option SELECTED value=\"month\">Month View</option>
                                                <option value=\"year\">Year View</option>
                                              </select>
                                              &nbsp;<input type=button value=\""._CALJUMPBUTTON."\" onClick=\"location.href='modules.php?op=modload&name=$module_name&file=index&type=' + document.jump.view.options[document.jump.view.options.selectedIndex].value + '&Date=' + document.jump.jumpmonth.options[document.jump.jumpmonth.options.selectedIndex].value + '/' + document.jump.jumpday.options[document.jump.jumpday.options.selectedIndex].value + '/' + document.jump.jumpyear.options[document.jump.jumpyear.options.selectedIndex].value;\"></form></td>";
                   echo"</tr>
                        </table>
                </td>
        </tr>
        </table></center>";
    CloseTable();

    include ('footer.php');
}

switch($op2) {

    case "Preview_Event":
	PreviewStory($name, $address, $subject, $day, $month, $year, $endday, $endmonth, $endyear, $story, $topic, $type, $startHour, $startMin, $startampm, $endHour, $endMin, $endampm, $alldayevent, $barcolor);
	break;

    case "Submit_Event":
	SubmitStory($name, $address, $subject, $day, $month, $year, $endday, $endmonth, $endyear, $story, $topic, $type, $startHour, $startMin, $startampm, $endHour, $endMin, $endampm, $alldayevent, $barcolor);
	break;

    default:
	defaultDisplay($type);
	break;

}

?>
