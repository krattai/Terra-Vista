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
# PHP-NUKE Add-On 5.0 : 
# ==========================
#
#              Email Users Add-On base on ThatWare admin script
# 			   Copyright (C) 2000  Thatware Development Team
# 			   http://atthat.com/
# 
# Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#	
# http://www.nukeaddon.com
#
#
#######################################################################
# Copyright (C) 2000  Thatware Development Team
# http://atthat.com/
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
###############################################################################

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }

switch($op) {

   	case "send_email_to_user":
		include("admin/modules/send_email.php");
		send_email_to_user($username, $fromname, $from, $subject, $message, $all);
	break;

	case "email_user":
		include("admin/modules/send_email.php");
		EmailUser();
	break;

}

?>