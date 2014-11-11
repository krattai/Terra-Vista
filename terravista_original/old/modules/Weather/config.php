<?

######################################################################
# PHP-NUKE Add-On 5.0 : Weather AddOn
# ============================================
# 
# Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#
# http://www.nukeaddon.com
#
#
#######################################################################


$accid="WIII"; # Your city location code

if(!IsSet($mainfile)) { include ("mainfile.php"); }

if (isset($newlang)) {
	include("modules/$module_name/language/lang-$newlang.php");
	$language = $newlang;
} elseif (isset($lang)) {
    include("modules/$module_name/language/lang-$lang.php");
    $language = $lang;
} else {
    include("modules/$module_name/language/lang-$language.php");
}

?>