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

    case "ForumConfigAdmin":
    include ("admin/modules/phpbbconfig.php");
    ForumConfigAdmin();
    break;

    case "ForumConfigChange":
    include ("admin/modules/phpbbconfig.php");
    ForumConfigChange($allow_html,$allow_bbcode,$allow_sig,$posts_per_page,$hot_threshold,$topics_per_page);
    break;

}

?>