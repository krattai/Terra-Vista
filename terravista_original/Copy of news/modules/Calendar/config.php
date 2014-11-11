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

$textEvents = 0;   // 0 = Image Bars for multiday events, 1 = text for multiday events
$netscapeFriendlyMonthView = 0;  // If you have users using Netscape set this to 1.
                                 // The above $textEvents variable is ignored if this is 1 because
                                 // the Netscape option is a simpler text based month view.
                                 // It seems Netscapes HTML parser can not handle the number of nested
                                 // tables plus images required by the more graphical views.
                                 // Simply setting the $textEvents variable to 1 is not enough because the
                                 // there are still many nested tables in that view.  This was done because of an
                                 // extremely slow load time in Netscape. THIS DOES NOT COMPLETELY FIX IT
                                 // BECAUSE ITS STILL SLOW FOR SOME F'IN REASON.  ANY HELP WITH THIS WOULD BE
                                 // GREATLY VALUED. I'm really starting to dislike Netscape.

$time24Hour = 0;	// 1 = 24 hour time... 0 = AM/PM time
$eventsopeninnewwindow = 0;  // When you click to view an event it will open into a new window if value = 1
$times = array("08:00:00",
		"09:00:00",
		"10:00:00",
		"11:00:00",
		"12:00:00",
		"13:00:00",
		"14:00:00",
		"15:00:00",
		"16:00:00",
		"17:00:00",
		"18:00:00");	//The time ranges on the day view

$useInternationalDates = 0; 	//0 = mm/dd/yyyy, 1 = dd/mm/yyyy

/**** Specific month config variables */
$monthshadedbgcolor = "#AABBCC";
$monthshadedtextcolor = "#778899";
$monthbgcolor = "#CCDDEE";
$monthtableborder = 1;
$monthtablecellspacing = 0; // if you change this the bars will not look right only use textEvents if you change these
$monthtablecellpadding = 0; // if you change this the bars will not look right only use textEvents if you change these

/**** Specific year config variables */
$yeartableborder = 0;
$yeartablecellspacing = 2;
$yeartablecellpadding = 3;    // this will make the month blocks larger or smaller depending on value
$yearbgcolor = "#AABBCC";
$yeartextcolor = "#333333";

$trimbgcolor = "#667788";     // Used in all views
$trimtextcolor = "#000000";   // Used in all views
$trimbgcolor2 = "#8899AA";    // Used in the day view
$trimtextcolor2 = "#000000";  // Used in the day view
$selecteddaycolor = "#FF4444"; // Used in the month and year view
$daybgcolor = "#EEEEEE";
$daytextcolor = "#778899";

if(!IsSet($mainfile)) { include ("mainfile.php"); }

if (isset($newlang)) {
	include("modules/$module_name/language/lang-$newlang.php");
	$language = $newlang;
} elseif (isset($lang)) {
    include("modules/$module_name/language/lang-$lang.php");
    $language = $lang;
} else {
    include("modules/$module_name/language/lang-$language.php");
}
?>
