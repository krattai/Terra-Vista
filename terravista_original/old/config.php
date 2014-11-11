<?php

######################################################################
# PHP-NUKE: Web Portal System
# ===========================
#
# Copyright (c) 2000 by Francisco Burzi (fbc@mandrakesoft.com)
# http://phpnuke.org
#
# This module is to configure the main options for your site
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################

######################################################################
# Database & System Config
#
# dbhost:   MySQL Database Hostname
# dbuname:  MySQL Username
# dbpass:   MySQL Password
# dbname:   MySQL Database Name
# system:   0 for Unix/Linux, 1 for Windows
######################################################################

$dbhost = "localhost";
$dbuname = "user";
$dbpass = "";
$dbname = "tvista";
$system = 1;
$prefix = nuke;

/*********************************************************************/
/* You finished to configure the Database. Now you can change all    */
/* you want in the Administration Section.   To enter just launch    */
/* you web browser pointing to http://yourdomain.com/admin.php       */
/*                                                                   */
/* At the prompt use the following ID to login (case sensitive):     */
/*                                                                   */
/* AdminID: God                                                      */
/* Password: Password                                                */
/*                                                                   */
/* Be sure to change inmediately the God login & password clicking   */
/* on Edit Admin in the Admin menu. After that, click on Preferences */
/* to configure your new site. In that menu you can change all you   */
/* need to change.                                                   */
/*                                                                   */
/* Remember to chmod 666 this file in order to let the system write  */
/* to it properly. If you can't change the permissions you can edit  */
/* the rest of this file by hand.                                    */
/*                                                                   */
/* Congratulations! now you have an automated news portal!           */
/* Thanks for choose PHP-Nuke: The Future of the Web                 */
/*********************************************************************/



######################################################################
# General Site Configuration
#
# $sitename:      Your Site Name
# $nukeurl:      Complete URL for your site (Do not put / at end)
# $site_logo:     Logo for Printer Friendly Page (It's good to have a Black/White graphic)
# $slogan:        Your site's slogan
# $startdate:     Start Date to display in Statistic Page
# $adminmail:     Site Administrator's Email
# $anonpost:      Allow Anonymous to Post Comments? (1=Yes 0=No)
# $Default_Theme: Default Theme for your site (See /themes directory for the complete list, case sensitive!)
# $foot(x):       Messages for all footer pages (Can include HTML code)
# $commentlimit:  Maximum number of bytes for each comment
# $anonymous:     Anonymous users Default Name
# $minpass:       Minimum character for users passwords
# $pollcomm:      Activate comments in Polls? (1=Yes 0=No)
######################################################################

$sitename = "The Official Terra Vista Web Site v.2.0";
$nukeurl = "http://terravista.dhs.org";
$site_logo = "../images/iconlr.jpg";
$slogan = "The Official Terra Vista Web Site v.2.0";
$startdate = "July 2001";
$adminmail = "kevin@uveais.dhis.org";
$anonpost = 1;
$Default_Theme = "beausejour";
$foot1 = "<a href=\"http://phpnuke.org\" target=\"blank\"><img src=\"images/powered/nuke.gif\" border=\"0\" Alt=\"Web site powered by PHP-Nuke\" hspace=\"10\"></a> <a href=\"http://www.php.net\" target=\"blank\"><img src=\"images/powered/php2.gif\" Alt=\"PHP Scripting Language\" border=\"0\" hspace=\"10\"></a><br>";
$foot2 = "All logos and trademarks in this site are property of their respective owner. The comments are property of their posters, all the rest © 2001 by Uvea I. S.";
$foot3 = "";
$foot4 = "You can syndicate our news using the file <a href=\"backend.php\">backend.php</a> or <a href=\"ultramode.txt\">ultramode.txt</a>";
$commentlimit = 4096;
$anonymous = "Anonymous";
$minpass = 5;
$pollcomm = 1;

######################################################################
# General Stories Options
#
# $top:       How many items in Top Page?
# $storyhome: How many stories to display in Home Page?
# $oldnum:    How many stories in Old Articles Box?
# $ultramode: Activate ultramode plain text file backend syndication? (1=Yes 0=No  Need to chmod 666 ultramode.txt file)
######################################################################

$top = 10;
$storyhome = 10;
$oldnum = 30;
$ultramode = 0;

######################################################################
# Banners/Advertising Configuration
#
# $banners: Activate Banners Ads for your site? (1=Yes 0=No)
# $myIP:    Write your IP number to not count impressions, be fair about this!
######################################################################

$banners = 1;
$myIP = "206.45.112.112";

######################################################################
# XML/RDF Backend Configuration
#
# $backend_title:    Backend title, can be your site's name and slogan
# $backend_language: Language format of your site
######################################################################

$backend_title = "PHP-Nuke Powered Site";
$backend_language = "en-us";

######################################################################
# Site Language Preferences
#
# $language: Language of your site (You need to have lang-xxxxxx.php file for your selected language in the /language directory of your site)
# $locale:   Locale configuration to correctly display date with your country format. (See /usr/share/locale)
######################################################################

$language = "english";
$locale = "en_US";

######################################################################
# Web Links Preferences (Some variables are valid also for Downloads)
#
# $perpage:      	    	How many links to show on each page?
# $popular:      	    	How many hits need a link to be listed as popular?
# $newlinks:     	    	How many links to display in the New Links Page?
# $toplinks:     	    	How many links to display in The Best Links Page? (Most Popular)
# $linksresults: 	    	How many links to display on each search result page?
# $links_anonaddlinklock:   	Lock Unregistered users from Suggesting New Links? (1=Yes 0=No)
# $anonwaitdays:        	Number of days anonymous users need to wait to vote on a link
# $outsidewaitdays:     	Number of days outside users need to wait to vote on a link (checks IP)
# $useoutsidevoting:        	Allow Webmasters to put vote links on their site (1=Yes 0=No)
# $anonweight:          	How many Unregistered User vote per 1 Registered User Vote?
# $outsideweight:       	How many Outside User vote per 1 Registered User Vote?
# $detailvotedecimal:       	Let Detailed Vote Summary Decimal out to N places. (no max)
# $mainvotedecimal:     	Let Main Vote Summary Decimal show out to N places. (max 4)
# $toplinkspercentrigger:   	1 to Show Top Links as a Percentage (else # of links)
# $toplinks:            	Either # of links OR percentage to show (percentage as whole number. #/100)
# $mostpoplinkspercentrigger:	1 to Show Most Popular Links as a Percentage (else # of links)
# $mostpoplinks:        	Either # of links OR percentage to show (percentage as whole number. #/100)
# $featurebox:          	1 to Show Feature Link Box on links Main Page? (1=Yes 0=No)
# $linkvotemin:         	Number votes needed to make the 'top 10' list
# $blockunregmodify:        	Block unregistered users from suggesting links changes? (1=Yes 0=No)
######################################################################

$perpage = 10;
$popular = 500;
$newlinks = 10;
$toplinks = 25;
$linksresults = 10;
$links_anonaddlinklock = 0;
$anonwaitdays = 1;
$outsidewaitdays = 1;
$useoutsidevoting = 1;
$anonweight = 10;
$outsideweight = 20;
$detailvotedecimal = 2;
$mainvotedecimal = 1;
$toplinkspercentrigger = 0;
$toplinks = 25;
$mostpoplinkspercentrigger = 0;
$mostpoplinks = 25;
$featurebox = 1;
$linkvotemin = 5;
$blockunregmodify = 0;

######################################################################

# Downloads Preferences
#
# 10:     	    	  How many downloads to display in the New downloads Page?
# 25:     	    	  How many downloads to display in The Best downloads Page? (Most Popular)
# 10: 	    	  How many downloads to display on each search result page?
# 0: Lock Unregistered users from Suggesting New downloads? (1=Yes 0=No)
# 1:		  Let users to add new downloads? (1=Yes 0=No)
# 0:     1 to Show Top downloads as a Percentage (else # of downloads)
# 25:            	  Either # of downloads OR percentage to show (percentage as whole number. #/100)
# 0: 1 to Show Most Popular downloads as a Percentage (else # of downloads)
# 25:        	  Either # of downloads OR percentage to show (percentage as whole number. #/100)
# 5:         	  Number votes needed to make the 'top 10' list
######################################################################


$newdownloads = 10;
$topdownloads = 25;
$downloadsresults = 10;
$downloads_anonadddownloadlock = 0;
$user_adddownload = 1;
$topdownloadspercentrigger = 0;
$topdownloads = 25;
$mostpopdownloadspercentrigger = 0;
$mostpopdownloads = 25;
$downloadvotemin = 5;

######################################################################
# Notification of News Submissions
#
# $notify:         Notify you each time your site receives a news submission? (1=Yes 0=No)
# $notify_email:   Email, address to send the notification
# $notify_subject: Email subject
# $notify_message: Email body, message
# $notify_from:    account name to appear in From field of the Email
######################################################################

$notify = 1;
$notify_email = "uveais@crosswinds.net";
$notify_subject = "NEWS for the Terra Vista site";
$notify_message = "Hey! You got a new submission for your site.";
$notify_from = "uveais@crosswinds.net";

######################################################################
# Moderation Config (not 100% working)
#
# $moderate:   Activate moderation system? (1=Yes 0=No)
# $resons:     List of reasons for the moderation (each reason under quotes and comma separated)
# $badreasons: Number of bad reasons in the reasons list
######################################################################

$moderate = 0;
$reasons = array("As Is",
		    "Offtopic",
		    "Flamebait",
		    "Troll",
		    "Redundant",
		    "Insighful",
		    "Interesting",
		    "Informative",
		    "Funny",
		    "Overrated",
		    "Underrated");
$badreasons = 4;

######################################################################
# Survey/Polls Config
#
# $BarScale:   Scale for the Bar, multiple of 100, You may leave this to 1
# $setCookies: Set cookies to prevent visitors vote twice in a period of 24 hours? (1=Yes 0=No)
######################################################################

$BarScale = 1;
$setCookies = 1;

######################################################################
# Some Graphics Options
#
# $tipath:       Topics images path (put / only at the end, not at the begining)
# $userimg:      User images path (No / at begining and at the end)
# $adminimg:     Administration system images path (put / only at the end, not at the begining)
# $admingraphic: Activate graphic menu for Administration Menu? (1=Yes 0=No)
# $admart:       How many articles to show in the admin section?
######################################################################

$tipath = "images/topics/";
$userimg = "images/menu";
$adminimg = "images/admin/";
$admingraphic = 1;
$admart = 20;

######################################################################
# HTTP Referers Options
#
# $httpref:    Activate HTTP referer logs to know who is linking to our site? (1=Yes 0=No)# $httprefmax: Maximum number of HTTP referers to store in the Database (Try to not set this to a high number, 500 ~ 1000 is Ok)
######################################################################

$httpref = 1;
$httprefmax = 1000;

######################################################################
# Allowable HTML tags
#
# $AllowableHTML: HTML command to allow in the comments
#                  =>2 means accept all qualifiers: <foo bar>
#                  =>1 means accept the tag only: <foo>
######################################################################

$AllowableHTML = array("p"=>2,
		    "b"=>1,
		    "i"=>1,
		    "a"=>2,
		    "em"=>1,
		    "br"=>1,
		    "strong"=>1,
		    "blockquote"=>1,
                    "tt"=>1,
                    "li"=>1,
                    "ol"=>1,
                    "ul"=>1);

######################################################################
# Filters Options
#
# $CensorList:	List of bad word to be replaced on Comments
# $CensorMode:  	0 = No Filtering (leave the bad words)
# 			1 = Exact Match
#			2 = Match Word at the Begining
#			3 = Match String Anywhere in the Text
# $CensorReplace:	String to replace bad words
######################################################################

$CensorList = array("fuck",
		    "cunt",
		    "fucker",
		    "fucking",
		    "pussy",
		    "cock",
		    "c0ck",
		    "cum",
		    "twat",
		    "clit",
		    "bitch",
		    "fuk",
		    "fuking",
		    "motherfucker");
$CensorMode = 1;
$CensorReplace = "*****";

######################################################################
# Do not touch the following options!
######################################################################

$cookieadmtime = 2592000;
$cookiePrefix = "NukePoll";
$Version_Num = "5.0";

?>