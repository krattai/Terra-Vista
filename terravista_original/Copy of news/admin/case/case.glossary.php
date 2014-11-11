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
# PHP-NUKE 5.0: Glossary Add-On
# =============================
#
#	Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji (rtirtadji@hotmail.com)
#
# http://www.nukeaddon.com
#
######################################################################

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }

switch($op) {

    	case "GlossarySave":
		    include ("admin/modules/glossary.php");
			GlossarySave($gid, $gterm, $gdefinition);
			break;

		case "GlossaryAdd":
		    include ("admin/modules/glossary.php");
			GlossaryAdd($gterm,$gdefinition);
			break;
			
		case "GlossaryEdit":
		    include ("admin/modules/glossary.php");
			GlossaryEdit($gid);
			break;

		case "GlossaryDel":
		    include ("admin/modules/glossary.php");
			GlossaryDel($gid, $ok);
			break;

		case "GlossaryAdmin":
		    include ("admin/modules/glossary.php");
			GlossaryAdmin();
			break;
}

?>