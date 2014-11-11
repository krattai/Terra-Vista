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
# PHP-NUKE Add-On 5.0.RC1 : PHPBB Forum AddOn
# ============================================
# 
# Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#						Hutdik Hermawan AKA hotFix (hutdik76@hotmail.com)
#
# http://www.nukeaddon.com
#
#
#######################################################################

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$hlpfile = "manual/forumconfig.html";
$result = mysql_query("select radminforum, radminsuper from $prefix"._authors." where aid='$aid'");
list($radminforum, $radminsuper) = mysql_fetch_row($result);
if (($radminforum==1) OR ($radminsuper==1)) {

function ForumConfigAdmin() {
    global $hlpfile, $admin;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    $result = mysql_query("select * from $prefix"._config."");
    list($allow_html,$allow_bbcode,$allow_sig,$posts_per_page,$hot_threshold,$topics_per_page) = mysql_fetch_row($result);
    echo "
    <center><font size=4><b>".translate("Forum Configuration")."</b></center><br><br>
    <font size=2>
    <form action=admin.php method=post>
    <table border=0 width=100%><tr><td>
    ".translate("Allow HTML:")." </td><td>";
    if ($allow_html==1) {
    ?>
    <INPUT TYPE="RADIO" NAME="allow_html" VALUE="1" CHECKED> Yes <INPUT TYPE="RADIO" NAME="allow_html" VALUE="0" > No
    <?php
    } else {
    ?>
    <INPUT TYPE="RADIO" NAME="allow_html" VALUE="1"> Yes <INPUT TYPE="RADIO" NAME="allow_html" VALUE="0" CHECKED> No
    <?php
    }
    echo"</td></tr>
    <tr><td>
    ".translate("Allow BBCODE:")." </td><td>";
    if ($allow_bbcode==1) {
    ?>
    <INPUT TYPE="RADIO" NAME="allow_bbcode" VALUE="1" CHECKED> Yes <INPUT TYPE="RADIO" NAME="allow_bbcode" VALUE="0" > No
    <?php
    } else {
    ?>
    <INPUT TYPE="RADIO" NAME="allow_bbcode" VALUE="1"> Yes <INPUT TYPE="RADIO" NAME="allow_bbcode" VALUE="0" CHECKED> No
    <?php
    }
    echo"</td></tr>
    <tr><td>
    ".translate("Allow Signature:")." </td><td>";
    if ($allow_sig==1) {
    ?>
    <INPUT TYPE="RADIO" NAME="allow_sig" VALUE="1" CHECKED> Yes <INPUT TYPE="RADIO" NAME="allow_sig" VALUE="0" > No
    <?php
    } else {
    ?>
    <INPUT TYPE="RADIO" NAME="allow_sig" VALUE="1"> Yes <INPUT TYPE="RADIO" NAME="allow_sig" VALUE="0" CHECKED> No
    <?php
    }
    echo "</td></tr><tr><td>
    ".translate("Hot Topic Threshold:")." </td><td><input type=text name=hot_threshold size=4 value=\"$hot_threshold\"></td></tr><tr><td>
    ".translate("Posts per Page:")." </td><td><input type=text name=posts_per_page size=4 value=$posts_per_page></td></tr>
    <tr><td colspan=2><small><i>".translate("(This is the number of posts per topic that will be displayed per page of a topic)")."</i></small></td></tr><tr><td>
    ".translate("Topics per Forum:")." </td><td><input type=text name=topics_per_page size=4 value=$topics_per_page></td></tr>
    <tr><td colspan=2><small><i>".translate("(This is the number of topics per forum that will be displayed per page of a forum)")."</i></small>
    </td></tr><tr><td>
    </td></tr></table>
    <input type=hidden name=op value=ForumConfigChange>
    <input type=submit value=".translate("Change").">
    </form>
    </td></tr></table></td></tr></table>";
    include("footer.php");
}

function ForumConfigChange($allow_html,$allow_bbcode,$allow_sig,$posts_per_page,$hot_threshold,$topics_per_page) {
	global $prefix;
    mysql_query("update $prefix"._config." set allow_html='$allow_html', allow_bbcode='$allow_bbcode', allow_sig='$allow_sig', posts_per_page='$posts_per_page', hot_threshold='$hot_threshold', topics_per_page='$topics_per_page'");
    Header("Location: admin.php?op=ForumConfigAdmin");
}

} else {
    echo "Access Denied";
}

?>