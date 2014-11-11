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

switch($op) {

    case "CalendarAdmin":
    include("admin/modules/calendar.php");
    break;

    case "CalendarDisplayStory":
    include("admin/modules/calendar.php");
    break;

    case "CalendarPreviewAgain":
    include("admin/modules/calendar.php");
    break;

    case "CalendarPostStory":
    include("admin/modules/calendar.php");
    break;

    case "CalendarEditStory":
    include("admin/modules/calendar.php");
    break;

    case "CalendarRemoveStory":
    include("admin/modules/calendar.php");
    break;

    case "CalendarChangeStory":
    include("admin/modules/calendar.php");
    break;

    case "CalendarDeleteStory":
    include("admin/modules/calendar.php");
    break;
}

?>