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

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }

switch($op) {

    case "mod_users":
    include("admin/modules/users.php");
    break;

    case "modifyUser":
    include("admin/modules/users.php");
    break;

    case "updateUser":
    include("admin/modules/users.php");
    break;

    case "delUser":
    include("admin/modules/users.php");        
    break;

    case "delUserConf":
    include("admin/modules/users.php");
    break;

    case "addUser":
    include("admin/modules/users.php");
    break;

}

?>