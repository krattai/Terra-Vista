<?php

######################################################################
# PHP-NUKE: Web Portal System
# ===========================
#
# Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)
# http://phpnuke.org
#
# This modules is the main administration part
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################
# PHP-NUKE 5.0: Part of phpBB Integration
# ===========================
#
#	Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2000 by Richard Tirtadji (rtirtadji@hotmail.com)
#
# http://www.nukeaddon.com
#
######################################################################

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }

switch($op) {

    case "ForumGoAdd":
    include ("admin/modules/phpbbforum.php");
    ForumGoAdd($forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass);
    break;

    case "ForumGoSave":
    include ("admin/modules/phpbbforum.php");
    ForumGoSave($forum_id, $forum_name, $forum_desc, $forum_access, $forum_mod, $cat_id, $forum_type, $forum_pass);
    break;

    case "ForumCatDel":
    include ("admin/modules/phpbbforum.php");
    ForumCatDel($cat_id, $ok);
    break;

    case "ForumGoDel":
    include ("admin/modules/phpbbforum.php");
    ForumGoDel($forum_id, $ok);
    break;
			
    case "ForumCatSave":
    include ("admin/modules/phpbbforum.php");
    ForumCatSave($cat_id, $cat_title);
    break;

    case "ForumCatEdit":
    include ("admin/modules/phpbbforum.php");
    ForumCatEdit($cat_id);
    break;

    case "ForumGoEdit":
    include ("admin/modules/phpbbforum.php");
    ForumGoEdit($forum_id);
    break;
			
    case "ForumGo":
    include ("admin/modules/phpbbforum.php");
    ForumGo($cat_id,$ctg);
    break;

    case "ForumCatAdd":
    include ("admin/modules/phpbbforum.php");
    ForumCatAdd($catagories);
    break;

    case "ForumAdmin":
    include ("admin/modules/phpbbforum.php");
    ForumAdmin();
    break;

}

?>