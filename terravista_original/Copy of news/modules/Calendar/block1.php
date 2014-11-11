<?
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
global $prefix;

$module_name = "Calendar";

$Date = Date("m/d/Y");
$Date_Array = explode("/", $Date);
//$Last_Day = ($Date_Array[1] - 0) + 7;
//$result = mysql_query("SELECT eid,title,startTime,endTime,alldayevent,barcolor FROM $prefix"._events." WHERE ((eventDate >= '$Date_Array[2]-$Date_Array[0]-$Date_Array[1]' AND eventDate <= '$Date_Array[2]-$Date_Array[0]-$Last_Day') OR (endDate >= '$Date_Array[2]-$Date_Array[0]-$Date_Array[1]' AND endDate <= '$Date_Array[2]-$Date_Array[0]-$Last_Day') OR (endDate >= '$Date_Array[2]-$Date_Array[0]-$Last_Day' AND eventDate <= '$Date_Array[2]-$Date_Array[0]-$Date_Array[1]')) ORDER BY eventDate ASC");
$eventsresult = mysql_query("SELECT eid,title,startTime,endTime,alldayevent,barcolor FROM $prefix"._events." WHERE (eventDate <= '$Date_Array[2]-$Date_Array[0]-$Date_Array[1]' AND endDate >= '$Date_Array[2]-$Date_Array[0]-$Date_Array[1]') ORDER BY alldayevent,startTime, endTime ASC");

if (mysql_num_rows($eventsresult) == 0) {
        echo "<center><font size=\"2\">There are no events today.</font></center>";
}

while(list($eid, $title,$startTime,$endTime,$alldayevent,$barcolor) = mysql_fetch_row($eventsresult)) {
        if ($barcolor == "r") $barcolorchar="r";
        elseif ($barcolor == "g") $barcolorchar="g";
        elseif ($barcolor == "b") $barcolorchar="b";
        elseif ($barcolor == "y") $barcolorchar="y";
        else $barcolorchar="w";
        print("<img src=\"images/calendar/ball$barcolorchar.gif\" border=0>&nbsp;<a href=\"modules.php?op=modload&name=$module_name&file=index&type=view&eid=$eid\">$title</a><br>");
}

?>
