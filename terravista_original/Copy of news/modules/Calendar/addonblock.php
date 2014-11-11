<?

########################################################################
# PHP-NUKE Add-On 5.0 : Calendar Box AddOn
# ===========================================
#
#	Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#
#						The Teams:
#                       Hutdik Hermawan AKA hotFix (hutdik76@hotmail.com)
# 						Max Demian AKA Max (Max@Wackowoh.com)
# 						Istrigo (TheBix.com)
# 						drgbows (ecomjunk.com)
# 						Sivaprasad R.L (netlogger.net)
# 						Rob Sutton (smart.xnettech.net)
#
# http://www.nukeaddon.com
#
# Inspired by Calendar AddOn made by Rob Sutton
# prefix aware by duns http://duns-ground.fr.st
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################

$module_name = "Calendar";
global $language,$bgcolor1,$bgcolor2,$bgcolor3,$bgcolor4,$textcolor1,$textcolor2,$Date, $prefix;

if (isset($newlang)) {
	include("modules/$module_name/language/lang-$newlang.php");
	$language = $newlang;
} elseif (isset($lang)) {
    include("modules/$module_name/language/lang-$lang.php");
    $language = $lang;
} else {
    include("modules/$module_name/language/lang-$language.php");
}

if (!$Date) {
	        $Date = Date("m/d/Y");
}

        $Date_Array = explode("/", $Date);
        if ($Date_Array[2] < 1970 || $Date_Array[2] > 2037)
               echo "ERROR! System can not interpret dates before 01/01/1970 or after 01/01/2037";
        else {
                $Date = mktime("", "", "", $Date_Array[0], $Date_Array[1], $Date_Array[2]);

                /**** Header */
                $Prev_Month = mktime("", "", "", $Date_Array[0] - 1, 1, $Date_Array[2]);
                $Prev_Date = Date("m/d/Y",$Prev_Month);
                $Next_Month = mktime("", "", "", $Date_Array[0] + 1, 1, $Date_Array[2]);
                $Next_Date = Date("m/d/Y",$Next_Month);

                echo "\n<center><TABLE><TR>";

                /**** Print Previous Month Button */
                echo "\n<TD><FONT SIZE=2><a href=\"?Date=$Prev_Date\"><<</a></TD>";
                echo "\n<TD align=center><b><FONT size=2>";
                echo "\n<a href=\"modules.php?op=modload&name=$module_name&file=index&Date=$Date_Array[0]/1/$Date_Array[2]&type=month\">";
                /**** Print Month Name and Year */
                echo getMonthRev($Date)." ".Date("Y",$Date);
                echo "</a></FONT></b></TD>";

                /**** Print Next Month Button */
                echo "\n<TD align=right><FONT SIZE=2><a href=\"?Date=$Next_Date\">>></a></TD>";
                echo "</TR></TABLE>";

                /**** Get the Day (Integer) for the first day in the month */
                $First_Day_of_Month_Date = mktime("", "", "", $Date_Array[0], 1, $Date_Array[2]);
                $Day_of_First_Week = Date("w",$First_Day_of_Month_Date);

                /**** Find the last day of the month */
                $Month = Date("m",$Date);
                $day = 27;
                do {
                        $End_of_Month_Date = mktime("", "", "", $Date_Array[0], $day, $Date_Array[2]);
                        $Test_Month = Date("m",$End_of_Month_Date);
                        $day += 1;
                } while ( $Month == $Test_Month );
                $Last_Day = $day - 2;

                /**** Get todays date */
                $Today_d = Date("d");
                $Today_m = Date("m");
                $Today_y = Date("Y");


                /**** Build Month */
                echo "\n<TABLE border=\"0\" cellpadding=\"2\" cellspacing=\"0\">";
                $day_of_week = 1;

                echo "<TR bgcolor=\"$bgcolor2\">
                        <TD width=\"20\" align=\"center\"><FONT color=\"$textcolor1\" SIZE=\"1\"><b>S</b></font></TD>
                        <TD width=\"20\" align=\"center\"><FONT color=\"$textcolor1\" SIZE=\"1\"><b>M</b></font></TD>
                        <TD width=\"20\" align=\"center\"><FONT color=\"$textcolor1\" SIZE=\"1\"><b>T</b></font></TD>
                        <TD width=\"20\" align=\"center\"><FONT color=\"$textcolor1\" SIZE=\"1\"><b>W</b></font></TD>
                        <TD width=\"20\" align=\"center\"><FONT color=\"$textcolor1\" SIZE=\"1\"><b>T</b></font></TD>
                        <TD width=\"20\" align=\"center\"><FONT color=\"$textcolor1\" SIZE=\"1\"><b>F</b></font></TD>
                        <TD width=\"20\" align=\"center\"><FONT color=\"$textcolor1\" SIZE=\"1\"><b>S</b></font></TD>
                      </TR>";

                /**** Previous Greyed month days */
                While ($day_of_week < ($Day_of_First_Week + 1)) {
                        if ($day_of_week == 1) {
                                echo "\n<TR>";
                                }
                        if ($day_of_week == 1)
						echo "<TD valign=top><TABLE width=100% cellpadding=0 cellspacing=0 bgcolor=$bgcolor4 border=0><TR><TD align=center><FONT SIZE=2><b>&nbsp;</b></TR></TABLE></td>";
                        else
						echo "<TD valign=top><TABLE width=100% cellpadding=0 cellspacing=0 border=0><TR><TD align=center><FONT SIZE=2><b>&nbsp;</b></TR></TABLE></td>";
                        $day_of_week += 1;
                }
                $usedcount = 0;
                $cellcount = 0;
                /**** Build Current Month */
                for ($day = 1 ; $day <= $Last_Day ; $day++) {
                        if ($day_of_week == 1) {
                                echo "\n<TR>";
                                }
	$result = mysql_query("SELECT eid,title,eventDate,endDate,startTime,endTime,barcolor FROM $prefix"._events." WHERE (eventDate <= '$Date_Array[2]-$Date_Array[0]-$day' AND endDate >= '$Date_Array[2]-$Date_Array[0]-$day');");
                        if (($day == $Today_d) && ($Date_Array[0] == $Today_m) && ($Date_Array[2] == $Today_y))
                                echo "\n\t<TD valign=top><TABLE width=100% cellpadding=0 cellspacing=0 bgcolor=$bgcolor2 border=0><TR><TD align=center><b><a href=\"modules.php?op=modload&name=$module_name&file=index&Date=$Date_Array[0]/$day/$Date_Array[2]&type=day\"><FONT color=red SIZE=2>$day</font></a></b>";
						elseif ($day_of_week == 1)
                                echo "\n\t<TD valign=top><TABLE width=100% cellpadding=0 cellspacing=0 bgcolor=$bgcolor4 border=0><TR><TD align=center><FONT SIZE=2><b><a href=\"modules.php?op=modload&name=$module_name&file=index&Date=$Date_Array[0]/$day/$Date_Array[2]&type=day\">$day</a></b>";
                        else
                                echo "\n\t<TD valign=top><TABLE width=100% cellpadding=0 cellspacing=0 border=0><TR><TD align=center><FONT SIZE=2><b><a href=\"modules.php?op=modload&name=$module_name&file=index&Date=$Date_Array[0]/$day/$Date_Array[2]&type=day\">$day</a></b></font>";
	if (mysql_num_rows($result) == 0)
	        echo "<br><img src=\"images/calendar/events0.gif\"></TR></TABLE>";
	elseif (mysql_num_rows($result) >= 4)
		echo "<br><img src=\"images/calendar/events4.gif\"></TR></TABLE>";
	elseif (mysql_num_rows($result) >= 3)
		echo "<br><img src=\"images/calendar/events3.gif\"></TR></TABLE>";
	elseif (mysql_num_rows($result) >= 2)
		echo "<br><img src=\"images/calendar/events2.gif\"></TR></TABLE>";
	else
		echo "<br><img src=\"images/calendar/events1.gif\"></TR></TABLE>";

                        echo "</td>";
                        if ($day_of_week == 7) {
                                $day_of_week = 0;
                                echo "\n</TR>";
                        }
                        $day_of_week += 1;
                }

                /**** Next Greyed month days */
                $day = 1;
                While (($day_of_week <= 7) && ($day_of_week != 1)) {
                        echo "<TD align=center valign=top><FONT SIZE=2 color=$monthshadedtextcolor>&nbsp;</TD>";
                        $day_of_week += 1;
                        $day += 1;
                }
                echo "\n</TR>\n</TABLE></center>\n";
        }

function getMonthRev($Date) {
        $month = Date("m",$Date);
        if ($month == "01") {
                $monthname = _CALJAN;
        } elseif ($month == "02") {
                $monthname = _CALFEB;
        } elseif ($month == "03") {
                $monthname = _CALMAR;
        } elseif ($month == "04") {
                $monthname = _CALAPR;
        } elseif ($month == "05") {
                $monthname = _CALMAY;
        } elseif ($month == "06") {
                $monthname = _CALJUN;
        } elseif ($month == "07") {
                $monthname = _CALJUL;
        } elseif ($month == "08") {
                $monthname = _CALAUG;
        } elseif ($month == "09") {
                $monthname = _CALSEP;
        } elseif ($month == "10") {
                $monthname = _CALOCT;
        } elseif ($month == "11") {
                $monthname = _CALNOV;
        } elseif ($month == "12") {
                $monthname = _CALDEC;
        }
        return $monthname;
}

?>