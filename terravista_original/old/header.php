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

##################################################
# Include some common header for HTML generation #
##################################################

$header = 1;

function head() {
    global $index;
    if (!isset($index)) {
	include("config.php");
    } else {
	global $slogan, $sitename, $banners, $Default_Theme, $userimg, $nukeurl, $Version_Num;
    }
    global $artpage, $topic, $hlpfile, $user, $hr, $theme, $cookie, $bgcolor1, $bgcolor2, $bgcolor3, $bgcolor4, $textcolor1, $textcolor2, $forumpage;
    if(is_user($user)) {
	$user2 = base64_decode($user);
	$cookie = explode(":", $user2);
	if($cookie[9]=="") $cookie[9]=$Default_Theme;
	if(isset($theme)) $cookie[9]=$theme;
	if(!$file=@opendir("themes/$cookie[9]")) {
	    $ThemeSel = $Default_Theme;
	} else {
	    $ThemeSel = $cookie[9];
	}
    } else {
	$ThemeSel = $Default_Theme;
    }
    include("themes/$ThemeSel/theme.php");
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
    echo "<html>\n<head>\n";
    echo "<title>$sitename</title>\n";
    echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset="._CHARSET."\">\n";
    echo "<META NAME=\"AUTHOR\" CONTENT=\"$sitename\">\n";
    echo "<META NAME=\"COPYRIGHT\" CONTENT=\"Copyright (c) 2001 by $sitename\">\n";
    echo "<META NAME=\"KEYWORDS\" CONTENT=\"News, news, New, New, Technology, technology, Headlines, headlines, Nuke, nuke, PHP-Nuke, phpnuke, php-nuke, Geek, geek, Geeks, geeks, Hacker, hacker, Hackers, hackers, Linux, linux, Windows, windows, Software, software, Download, download, Downloads, downloads, Free, FREE, free, Community, community, MP3, mp3, Forum, forum, Forums, forums, Bulletin, bulletin, Board, board, Boards, boards, PHP, php, Survey, survey, Kernel, kernel, Comment, comment, Comments, comments, Portal, portal, ODP, odp, Open, open, Open Source, OpenSource, Opensource, opensource, open source, Free Software, FreeSoftware, Freesoftware, free software, GNU, gnu, GPL, gpl, License, license, Unix, UNIX, *nix, unix, MySQL, mysql, SQL, sql, Database, DataBase, database, Mandrake, mandrake, Red Hat, RedHat, red hat, Slackware, slackware, SUSE, SuSE, suse, Debian, debian, Gnome, GNOME, gnome, Kde, KDE, kde, Enlightenment, enlightenment, Intercative, interactive, Programming, programming, Extreme, extreme, Game, game, Games, games, Web Site, web site, Weblog, WebLog, weblog, Guru, GURU, guru\">\n";
    echo "<META NAME=\"DESCRIPTION\" CONTENT=\"$slogan\">\n";
    echo "<META NAME=\"GENERATOR\" CONTENT=\"PHP-Nuke $Version_Num - http://phpnuke.org\">\n\n\n";
    echo "<LINK REL=\"StyleSheet\" HREF=\"themes/$ThemeSel/style/style.css\" TYPE=\"text/css\">\n\n\n";
    echo "<SCRIPT type=\"text/javascript\">\n";
    echo "<!--\n";
    echo "function showimage() {\n";
    echo "if (!document.images)\n";
    echo "return\n";
    echo "document.images.avatar.src=\n";
    echo "'$nukeurl/images/forum/avatar/' + document.Register.user_avatar.options[document.Register.user_avatar.selectedIndex].value\n";
    echo "}\n";
    echo "//-->\n";
    echo "</SCRIPT>\n\n";


    echo "<script type=\"text/javascript\">\n";
    echo "<!--\n";
    echo "function openwindow(){\n";
    echo "	window.open (\"$hlpfile\",\"Help\",\"toolbar=no,location=no,directories=no,status=no,scrollbars=yes,resizable=no,copyhistory=no,width=600,height=400\");\n";
    echo "}\n";
    echo "//-->\n";
    echo "</SCRIPT>\n\n";
    echo "</head>\n\n";
    themeheader();
}

head();
include("counter.php");

?>