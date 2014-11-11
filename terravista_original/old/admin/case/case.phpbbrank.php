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

    case "RankForumAdmin":
    include ("admin/modules/phpbbrank.php");
    RankForumAdmin();
    break;
		
    case "RankForumEdit":
    include ("admin/modules/phpbbrank.php");
    RankForumEdit($rank_id);
    break;

    case "RankForumDel":
    include ("admin/modules/phpbbrank.php");
    RankForumDel($rank_id, $ok);
    break;

    case "RankForumAdd":
    include ("admin/modules/phpbbrank.php");
    RankForumAdd($rank_title,$rank_min,$rank_max,$rank_special);
    break;			

    case "RankForumSave":
    include ("admin/modules/phpbbrank.php");
    RankForumSave($rank_id, $rank_title, $rank_min, $rank_max, $rank_special);
    break;

}

?>