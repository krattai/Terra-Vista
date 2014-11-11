<?
/*********************************************************/
/*********************************************************/
/* Event Calendar                                        */
/* Written by: Rob Sutton                                */
/* http://www.xnettech.com (My Consulting Company)       */
/* http://smart.xnettech.net (My Nuke Site)              */
/*                                                       */
/* This program is opensource so you can do whatever     */
/* you want with it.
/*
/*
/* Modified by Russell Greenwood-russell@optushome.com.au*/
/* prefix aware by duns http://duns-ground.fr.st         */
/*********************************************************/
/*********************************************************/

$module_name = "Calendar";

/**** Specific front display variables */
/*EDIT THESE TO SPECIFY COLORS BELOW*/

$todaycolor = "#000000";
$daycolor = "#000000";
$daybackground = "#99AABB";
$todaybackground = "#FF5555";

/*No need to edit below here now */

global $prefix;

if (isset($newlang)) {
	include("modules/$module_name/language/lang-$newlang.php");
	$language = $newlang;
} elseif (isset($lang)) {
    include("modules/$module_name/language/lang-$lang.php");
    $language = $lang;
} else {
    include("modules/$module_name/language/lang-english.php");
}

function getMonthName2($Date) {
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




$Date = Date("m/d/Y");
$Date_Array = explode("/", $Date);


/**** Get the Day (Integer) for the first day in the month */
$First_Day_of_Month_Date = mktime("", "", "", $Date_Array[0], 1, $Date_Array[2]);
$Date = $First_Day_of_Month_Date;

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
print("\n<center><TABLE border=0 cellspacing=1 cellpadding=3>");
print("\n<TR>\n\t<TH colspan=7><FONT SIZE=1 color=000000><a
href=\"modules.php?op=modload&name=$module_name&file=index&Date=$Date_Array[0]/1/$Date_Array[2]&type=month\">");
print( getMonthName2($Date)." ".$Today_y );
print("</a></FONT></TH>\n</TR>");

/**** Previous Greyed month days */
print("\n<TR>");
if ($Day_of_First_Week != 0)
        print("\n\t<TD colspan=$Day_of_First_Week><font face=verdana size=2 color=000000>&nbsp;</TD>");
$day_of_week = $Day_of_First_Week + 1;
 /**** Build Current Month */
for ($day = 1 ; $day <= $Last_Day ; $day++) {
        if ($day_of_week == 1) {
                print("\n<TR>");
        }
	$result = mysql_query("SELECT eid,title,eventDate,endDate,startTime,endTime,barcolor FROM $prefix"._events." WHERE (eventDate <= '$Date_Array[2]-$Date_Array[0]-$day' AND endDate >= '$Date_Array[2]-$Date_Array[0]-$day');");
	if ($day == $Today_d)
                echo "\n\t<TD align=center bgcolor=$todaybackground><b><a href=\"modules.php?op=modload&name=$module_name&file=index&Date=$Date_Array[0]/$day/$Date_Array[2]&type=day\"><FONT FACE=verdana size=\"1\" color=$todaycolor>$day</font></a></b>";
        else
                echo "\n\t<TD align=center bgcolor=$daybackground><a href=\"modules.php?op=modload&name=$module_name&file=index&Date=$Date_Array[0]/$day/$Date_Array[2]&type=day\"><FONT FACE=verdana size=\"1\" color=$daycolor>$day</font></a>";
	if (mysql_num_rows($result) == 0)
	        echo "<br><img src=\"images/calendar/events0.gif\">";
	elseif (mysql_num_rows($result) >= 4)
		echo "<br><img src=\"images/calendar/events4.gif\">";
	elseif (mysql_num_rows($result) >= 3)
		echo "<br><img src=\"images/calendar/events3.gif\">";
	elseif (mysql_num_rows($result) >= 2)
		echo "<br><img src=\"images/calendar/events2.gif\">";
	else
		echo "<br><img src=\"images/calendar/events1.gif\">";
	echo "</TD>";
        if ($day_of_week == 7) {
                $day_of_week = 0;
                print("\n</TR>");
        }

        $day_of_week += 1;
}

/**** Next Greyed month days */
$day = 1;
if ($day_of_week != 1) {
        $tmp = 8 - $day_of_week;
        print("<TD colspan=$tmp><font face=verdana size=1 color=ffffff>.</TD>");
}
print("\n</TR>\n</TABLE>");

?>
