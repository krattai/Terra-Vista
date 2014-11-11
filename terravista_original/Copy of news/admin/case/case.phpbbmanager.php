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

   		case "ForumManager":
		    include ("admin/modules/phpbbmanager.php");
			ForumManagerAdmin();
			break;
		
		case "ForumSmiliesEdit":
		    include ("admin/modules/phpbbmanager.php");
			ForumSmiliesEdit($id);
			break;

		case "ForumSmiliesSave":
		    include ("admin/modules/phpbbmanager.php");
			ForumSmiliesSave($id,$code,$smile_url,$emotion,$active);
			break;

		case "ForumSmiliesAdd":
		    include ("admin/modules/phpbbmanager.php");
			ForumSmiliesAdd($code,$smile_url,$emotion,$active);
			break;

		case "ForumSmiliesDel":
		    include ("admin/modules/phpbbmanager.php");
			ForumSmiliesDel($id,$ok);
			break;

}

?>