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

if (eregi("meta.php",$PHP_SELF)) {
    Header("Location: ../index.php");
    die();
}

##################################################
# Include for Meta Tags generation               #
##################################################

echo "<META HTTP-EQUIV=\"Content-Type\" CONTENT=\"text/html; charset="._CHARSET."\">\n";
echo "<META HTTP-EQUIV=\"EXPIRES\" CONTENT=\"0\">\n";
echo "<META NAME=\"RESOURCE-TYPE\" CONTENT=\"DOCUMENT\">\n";
echo "<META NAME=\"DISTRIBUTION\" CONTENT=\"GLOBAL\">\n";
echo "<META NAME=\"AUTHOR\" CONTENT=\"$sitename\">\n";
echo "<META NAME=\"COPYRIGHT\" CONTENT=\"Copyright (c) 2001 - 2002 by $sitename\">\n";
echo "<META NAME=\"KEYWORDS\" CONTENT=\"X3D, x3d, VR, vr, VRML, vrml, Virtual Reality, virutal reality, 3D, 3d, Web3D, web3d, 3D Web, 3d web, 3dweb, virtual reality modelling language, immersion, Immersion, haptics, Haptics, telepresence, Telepresence, Fractal, fractal, telepresence, Telepresence, teleconference, Teleconference, telecommute, Telecommute, News, news, New, New, Technology, technology, Headlines, headlines, Software, software, Download, download, Downloads, downloads, Free, FREE, free, Community, community, Forum, forum, Forums, forums, Bulletin, bulletin, Board, board, Boards, boards, Survey, survey, Comment, comment, Comments, comments, Portal, portal, Open, open, Open Source, OpenSource, Opensource, opensource, open source, Free Software, FreeSoftware, Freesoftware, free software, GNU, gnu, GPL, gpl, License, license, Enlightenment, enlightenment, Intercative, interactive, Web Site, web site, Weblog, WebLog, weblog, Guru, GURU, guru\">\n";
echo "<META NAME=\"DESCRIPTION\" CONTENT=\"$slogan\">\n";
echo "<META NAME=\"ROBOTS\" CONTENT=\"INDEX, FOLLOW\">\n";
echo "<META NAME=\"REVISIT-AFTER\" CONTENT=\"10 DAYS\">\n";
echo "<META NAME=\"RATING\" CONTENT=\"GENERAL\">\n";
echo "<META NAME=\"GENERATOR\" CONTENT=\"PHP-Nuke $Version_Num - http://phpnuke.org\">\n";

?>