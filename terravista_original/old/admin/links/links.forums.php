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

if (($radminsuper==1) OR ($radminforum==1)) {
    adminmenu("admin.php?op=ForumAdmin", ""._FORUMMANAGER."", "forum.gif");
    adminmenu("admin.php?op=RankForumAdmin", ""._FORUMRANKS."", "forum.gif");
    adminmenu("admin.php?op=ForumConfigAdmin", ""._FORUMCONFIG."", "forum.gif");
	adminmenu("admin.php?op=ForumManager", "Forum Icons", "forum.gif");
}

?>