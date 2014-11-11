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

$footer = 1;

function footmsg() {
    include("config.php");
    echo "
    <center><font size=1>\n
    $foot1<br>\n
    $foot2<br>\n
    $foot3<br>\n
    $foot4<br>\n
    </font></center>\n
    ";
}

function foot() {
    global $index;
    if (!isset($index)) {
	include("config.php");
	global $user, $cookie;
    } else {
	global $storynum, $user, $cookie, $Default_Theme, $foot1, $foot2, $foot3, $foot4;
    }
    themefooter();
    echo "
    </body>\n
    </html>";

}

foot();

?>