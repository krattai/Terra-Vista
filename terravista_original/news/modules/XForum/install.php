<?
if (!isset($mainfile)) { include("mainfile.php"); }
include("auth.inc.php");
$SYSADMIN = is_admin($admin);	# User is Admin
if (!$SYSADMIN) { die ("Access Denied. You must be administrator of your system to install this module."); }

$ModName="XForum";
if (! isset($instlang)) { $instlang=="en"; } # Langue pae défaut


#$tablepre = $prefix."_xmb_";
$xfprefix = "xmb";
$nukesystem="phpnuke5x";

define("_MODINST_PREFIX","modules/".$ModName."/");
define("_MODINST_URL_PREFIX","modules.php?op=modload&name=$ModName&file=");

#define("_PREFIX_TBL",$prefix."_xmb_");

define("DBNAME", $dbname);
define("DBUSER", $dbuname);
define("DBPW", $dbpass);
define("DBHOST", $dbhost);
define("_ACTION", $act);

if ( ($language=="french") or ($instlang=="fr") )
{

if ( _ACTION == "suppr" ) 
  {
	define("_TITRE","Désinstallation de XForum module<br> pour P(hp&ost)Nuke by Trollix");
	define("_CREATE_TABLES","Supression des tables de la base");
  }
elseif ( _ACTION == "upgrade" ) 
  {
	define("_TITRE","Mise à jour de XForum  module<br> pour P(hp&ost)Nuke by Trollix");
	define("_CREATE_TABLES","Mise à jour des tables de la base");
  }
else 
  {
	define("_TITRE","Installation de XForum  module<br> pour P(hp&ost)Nuke by Trollix");
	define("_CREATE_TABLES","Cr&eacute;ation des tables de la base");
  }

define("_CH_OPERATION","Type d'Opération");
define("_INSTALLFOR","Type de Nuke");
define("_CH_INSTALL","Installation");
define("_CH_POSTNUKE6X","PostNuke 0.6x");
define("_CH_POSTNUKE64","PostNuke 0.64");
define("_CH_PHPNUKE5X","PhpNuke 5.x");
define("_CH_NUKESYSTEM","Type de Nuke");
define("_CH_UNINSTALL","Désinstallation");
define("_CH_UPGRADE","Upgrade Alpha,B1,B2 -> Beta3");
define("_CH_LANGUE","Langue");
define("_CH_ENGLISH","Anglais");
define("_CH_FRENCH","Français");

define("_STRUCT_DB_INST","La structure de votre base de donn&eacute;es est install&eacute;e.");
define("_NEXT_STEP","Vous pouvez passer &agrave; l'&eacute;tape suivante.");
define("_OP_FAILED","L'op&eacute;ration a &eacute;chou&eacute;.");
define("_PREV_NEW_SELECT","Retournez &agrave; la page pr&eacute;c&eacute;dente, s&eacute;lectionnez une autre base ou cr&eacute;ez-en une nouvelle. V&eacute;rifiez les informations fournies par votre h&eacute;bergeur.");
define("_PREV_NEW_VERIFYOURINFO","Retournez &agrave; la page pr&eacute;c&eacute;dente et v&eacute;rifiez les informations que vous avez fournies.");
define("_STEP3","Troisi&egrave;me &eacute;tape");
define("_STEP2","Deuxi&egrave;me &eacute;tape");
define("_STEP1","Premi&egrave;re &eacute;tape");
define("_NEXT_STEP","Etape suivante");
define("_CAN_NEXT_STEP","Vous pouvez passer &agrave; l'&eacute;tape suivante");
define("_NEXT2","Suivant");
define("_FAILED","Echec");
define("_MAJ_OK","Ok - Mise à jour effectuée");
define("_TRY_BASE_CONNECT","Essai de connexion &agrave; la base");
define("_CONNECT_OK","La connexion a r&eacute;ussi.");
define("_CONNECT_FAILED","La connexion a &eacute;chou&eacute;.");
define("_ASK_DB_ACTIVATION","Sur de nombreux serveurs, vous devez <B>demander</B> l'activation de votre acc&egrave;s &agrave; la base mySQL avant de pouvoir l'utiliser. Si vous ne pouvez vous connecter, v&eacute;rifiez que vous avez effectu&eacute; cette d&eacute;marche.");
define("_FILE_NOT_WRITEN","Attention, ce fichier suivant situé dans le répertoire \"modules/$ModName/ n'est pas accessible en écriture.<br><br>Tentative de la mise à jour de ces droits: ");
define("_MANUAL_RIGHTS","Vous devrez mettre ces droits manuellement avant de continuer.");
define("_INSTALL_PARAM","Les paramètres de votre installation");

define("_TXT_CONSULT_PROVIDER","Consultez les informations fournies par votre h&eacute;bergeur&nbsp;: vous devez y trouver, si votre h&eacute;bergeur supporte mySQL, les codes de connexion au serveur mySQL.");

define("_TXT_DB_ADRESS","Adresse de la base de donn&eacute;es");
define("_TXT_DB_ADRESS_EXPL","(Souvent cette adresse correspond &agrave; celle de votre site, parfois elle correspond &agrave; la mention &laquo;localhost&raquo;, parfois elle est laiss&eacute;e totalement vide.)");
define("_TXT_DB_LOGIN","Le login de connexion");
define("_TXT_DB_LOGIN_EXPL","(Correspond parfois &agrave; votre login d'acc&egrave;s au FTP; parfois laiss&eacute; vide)");
define("_TXT_DB_PASSWORD","Le mot de passe de connexion");
define("_TXT_DB_PASSWORD_EXPL","(Correspond parfois &agrave; votre mot de passe pour le FTP; parfois laiss&eacute; vide)");
define("_TXT_INFO_VALDEF","Ici vont apparaître les valeurs par défaut de votre installation Nuke:");
define("_TXT_NUKE_PREFIX","Le préfixe de votre Nuke");
define("_TXT_NUKE_PREFIX_EXPL","(C'est la valeur que vous avez dans votre config.php. Il est conseillé de laisser cette valeur par défaut sauf si vous savez ce que vous faites)");
define("_TXT_XF_PREFIX","Le préfixe de XForum");
define("_TXT_XF_PREFIX_EXPL","(C'est la valeur par defaut que l'on met pour le Forum. Il est conseillé de laisser cette valeur par défaut sauf si vous savez ce que vous faites, par exemple installer plusieurs forums sur le même site -> pas encore possible)");
define("_TXT_NUKE_DBASE","Le nom de votre base");
define("_TXT_NUKE_DBASE_EXPL","(C'est la valeur de votre base surlaquelle votre Nuke est installée. C'est la valeur que vous avez dans votre config.php. Il est conseillé de laisser cette valeur par défaut sauf si vous savez ce que vous faites)");
define("_TXT_XMB_LANG","La langue du forum par défaut");
define("_TXT_XMB_LANG_EXPL","(Correspond à la langue qui sera affectée par défaut à tous les utilisateurs. Nota: ils pourront changer cette valeur ensuite)");
define("_TXT_XMB_THEME","Le thème du forum par défaut");
define("_TXT_XMB_THEME_EXPL","(Correspond au thème du forum qui sera appliqué par défaut à tous les utilisateurs lors de leur inscription. Il est fortement conseillé de laisser le défaut à gray pour une première installation. Nota: Cette valeur pourra être modifiée ensuite par l'utilisateur)");
define("_DB_SUPPR_OK","Base de Donnée XForum Supprimée avec succès");
define("_PLEASECOPY","Veuillez coller ceci en entete de votre fichier settings.php avant de continuer");
}

else 
{
if ( _ACTION == "suppr" ) 
  {
	define("_TITRE","XForum module for P(hp&ost)Nuke<br> Uninstall by Trollix");
	define("_CREATE_TABLES","Databases Tables Suppression");
  }
elseif ( _ACTION == "upgrade" ) 
  {
	define("_TITRE","XForum module for P(hp&ost)Nuke<br> Upgrade by Trollix");
	define("_CREATE_TABLES","Databases Tables Upgrade");
  }
else 
  {
	define("_TITRE","XForum module for P(hp&ost)Nuke<br> Installation by Trollix");
	define("_CREATE_TABLES","Databases Tables Creation");
  }

define("_CH_OPERATION","Operation:");
define("_CH_INSTALL","Install");
define("_CH_UNINSTALL","Uninstall");
define("_INSTALLFOR","Nuke System");
define("_CH_POSTNUKE6X","PostNuke 0.6x");
define("_CH_POSTNUKE64","PostNuke 0.64");
define("_CH_PHPNUKE5X","PhpNuke 5.x");
define("_CH_NUKESYSTEM","Nuke System Type");
define("_CH_UPGRADE","Upgrade Alpha,B1,B2 -> Beta3");
define("_CH_LANGUE","Language");
define("_CH_ENGLISH","English");
define("_CH_FRENCH","French");

define("_STRUCT_DB_INST","Database Structure is installed.");
define("_NEXT_STEP","You can go to the next step.");
define("_OP_FAILED","Operation failed.");
define("_PREV_NEW_SELECT","Return to the previous page, select another database or create a new one. Please check-up informations given by your host provider.");
define("_PREV_NEW_VERIFYOURINFO","Return to the previous page and please chek-up your informations.");
define("_STEP3","Step Three");
define("_STEP2","Step Two");
define("_STEP1","Step One");
define("_NEXT_STEP","Next Step");
define("_CAN_NEXT_STEP","You can to the next step");
define("_NEXT2","Next");
define("_FAILED","Failed");
define("_MAJ_OK","Update ok");
define("_TRY_BASE_CONNECT","Try to connect to the Database");
define("_CONNECT_OK","Connection Ok.");
define("_CONNECT_FAILED","Connection Failed.");
define("_ASK_DB_ACTIVATION","On many servers, you must <b>ask</b> the provider, activation of your mySQL Database before use it. Please check-up you have done that operation.");
define("_FILE_NOT_WRITEN","Be Carefull, this file which is in \"modules/$ModName/ directory is not writable.<br><br>Try to write rights: ");
define("_MANUAL_RIGHTS","You must give this rights manually before going on.");
define("_INSTALL_PARAM","Your Installation Parameters:");

define("_TXT_CONSULT_PROVIDER","Please consult informations given by your host provider: you should find, if it supports mySQL Database, login/password/server adress of your system.");

define("_TXT_DB_ADRESS","Database Adress");
define("_TXT_DB_ADRESS_EXPL","(Sometimes, this adress is the one of your site, often it is &laquo;localhost&raquo;, so it could be empty too.)");
define("_TXT_DB_LOGIN","Connection Login");
define("_TXT_DB_LOGIN_EXPL","(Sometimes, it is the same that your FTP login, but it could be empty too.)");
define("_TXT_DB_PASSWORD","Connection Password");
define("_TXT_DB_PASSWORD_EXPL","(Sometimes, it is the same that your FTP password, but it could be empty too.)");
define("_TXT_INFO_VALDEF","Here you'll have your default parameters of your Nuke system:");
define("_TXT_NUKE_PREFIX","Nuke prefix");
define("_TXT_NUKE_PREFIX_EXPL","(It is the same value then your Nuke prefix, don't change it if you do not know what you do.)");
define("_TXT_XF_PREFIX","XForum prefix");
define("_TXT_XF_PREFIX_EXPL","(It's default value for your XForum, don't change it if you do not know what you do, for example install two forum on the same site -> not implemented)");
define("_TXT_NUKE_DBASE","Database Name");
define("_TXT_NUKE_DBASE_EXPL","(It 's the DataBase Name where your Nuyke is installed on. This value is the same one than in your Nuke config.php, don't change it if you do not know what you do.)");
define("_TXT_XMB_LANG","Default language for the Forum");
define("_TXT_XMB_LANG_EXPL","(It is the default language value affected to a new user connected to your Nuke system. Nota: it could be changed later)");
define("_TXT_XMB_THEME","Default theme of your forum");
define("_TXT_XMB_THEME_EXPL","(It is the default theme value affected to a new user connected to your Nuke system. Nota: it could be changed later)");
define("_DB_SUPPR_OK","Database deleted with success");
define("_PLEASECOPY","Please copy this following lines in the head of file: settings.php before next step");
}


#-------------------------------------------------------------------------------
# FONCTIONS D'INSTALL
#-------------------------------------------------------------------------------

// Debut html
function debut_html($tit=_TITRE) 
  {
?>
<HTML>

<HTML>
<HEAD>
<TITLE><? echo $titre; ?></TITLE>

<style>
<!--
	a {text-decoration: none; }
	A:Hover {color:#FF9900; text-decoration: underline;}
	.forml {width: 100%; background-color: #FFCC66; background-position: center bottom; float: none; color: #000000}
	.formo {width: 100%; background-color: #9999ff; background-position: center bottom; float: none; color: #FFFFFF; font-weight : bold}
		.fondo {background-color: #9999ff; background-position: center bottom; float: none; color: #FFFFFF; font-weight : bold}
	.fondf {background-color: #FFFFFF; border-style: solid ; border-width: 1; border-color: #E86519; color: #E86519}
	.soustitre { font-family : Verdana, Arial, Helvetica, sans-serif; font-size : 14px; color : #000000 }
	.expl { font-family : Verdana, Arial, Helvetica, sans-serif; font-size : 11px; color : #000000 }

}
-->
</style>
</HEAD>

<body bgcolor="#FFFFFF" text="#000000" link="#E86519" vlink="#6E003A" alink="#FF9900" TOPMARGIN="0" LEFTMARGIN="0" MARGINWIDTH="0" MARGINHEIGHT="0">

<BR><BR><BR>
<CENTER>
<TABLE WIDTH=520>
<TR><TD WIDTH=520>
<FONT FACE="Verdana,Arial,Helvetica,sans-serif" SIZE=4 COLOR="#970038"><B><center><? echo $tit; ?></center></B></FONT>
<FONT FACE="Georgia,Garamond,Times,serif" SIZE="2">

<?

}

function fin_html() {

echo '
	</FONT>
	</TD></TR></TABLE>
	</CENTER>
	</BODY>
	</HTML>
';

}


#-------------------------------------------------------------------------------
# FONCTIONS DE CONFIGURATION
#-------------------------------------------------------------------------------


function savesettings() 
  {
	global $ModName;


	$file = fopen("modules/$ModName/settings.php", "w");
    $line = "######################################################################\n";
    $content = "<?php\n\n";
    $content .= "$line";
    $content .= "# PHP-NUKE: Web Portal System\n";
    $content .= "# ===========================\n";
    $content .= "#\n";
    $content .= "# Copyright (c) 2000 by Francisco Burzi (fbc@mandrakesoft.com)\n";
    $content .= "# http://phpnuke.org\n";
    $content .= "#\n";
    $content .= "# This file is to configure XForum module options for your site\n";
    $content .= "# Original Forum  by XMB Dev Team http://www.xmbforum.com\n";
    $content .= "#\n";
    $content .= "# XForum module by Trollix (trollix@hacknuke.com)\n";
    $content .= "# C. Deltheil http://www.hacknuke.com\n";
    $content .= "#\n";
    $content .= "#\n";
    $content .= "# This program is free software. You can redistribute it and/or modify\n";
    $content .= "# it under the terms of the GNU General Public License as published by\n";
    $content .= "# the Free Software Foundation; either version 2 of the License.\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "$line";
	$content .= "\n";
	$content .= "\n";
	$content .= "\$nukesystem = \""._XMBDEF_NUKESYSTEM."\";\n";
	$content .= "\n";

	$content .= "\$tablepre = \""._PREFIX_TBL."\";\n";

	$content .= "\$table_banned = \""._PREFIX_TBL."banned\";\n";
	$content .= "\$table_forums = \""._PREFIX_TBL."forums\";\n";
	$content .= "\$table_members = \""._PREFIX_TBL."members\";\n";
	$content .= "\$table_posts = \""._PREFIX_TBL."posts\";\n";
	$content .= "\$table_ranks = \""._PREFIX_TBL."ranks\";\n";
	$content .= "\$table_smilies = \""._PREFIX_TBL."smilies\";\n";
	$content .= "\$table_themes = \""._PREFIX_TBL."themes\";\n";
	$content .= "\$table_threads = \""._PREFIX_TBL."threads\";\n";
	$content .= "\$table_u2u = \""._PREFIX_TBL."u2u\";\n";
	$content .= "\$table_whosonline = \""._PREFIX_TBL."whosonline\";\n";
	$content .= "\$table_words = \""._PREFIX_TBL."words\";\n";
	
	$content .= "\$langfile = \""._XMBDEFLANG."\";\n";
	$content .= "\$bbname = \"Forums\";\n";

	$content .= "\$postperpage = \"25\";\n";
	$content .= "\$topicperpage = \"30\";\n";
	$content .= "\$hottopic = \"20\";\n";
	$content .= "\$XFtheme = \""._XMBDEFTHEME."\";\n";
	$content .= "\$bbstatus = \"on\";\n";
	$content .= "\$whosonlinestatus = \"on\";\n";
	$content .= "\$regstatus = \"on\";\n";
	$content .= "\$bboffreason = \"\";\n";
	$content .= "\$regviewonly = \"off\";\n";
	$content .= "\$floodctrl = \"5\";\n";
	$content .= "\$memberperpage = \"45\";\n";
	$content .= "\$catsonly = \"off\";\n";
	$content .= "\$hideprivate = \"on\";\n";
	$content .= "\$showsort = \"on\";\n";
	$content .= "\$emailcheck = \"off\";\n";
	$content .= "\$bbrules = \"off\";\n";
	$content .= "\$bbrulestxt = \"Your rules go here\";\n";
	$content .= "\$searchstatus = \"on\";\n";
	$content .= "\$faqstatus = \"on\";\n";
	$content .= "\$memliststatus = \"on\";\n";
	$content .= "\$piconstatus = \"on\";\n";
	$content .= "\$sitename = \"HackNuke.com\";\n";
	$content .= "\$siteurl = \"http://www.hacknuke.com/\";\n";
	$content .= "\$avastatus = \"on\";\n";
	$content .= "\$noreg = \"off\";\n";
	$content .= "\$gzipcompress = \"off\";\n";
	$content .= "\$boardurl = \"http://www.hacknuke.com/modules.php?op=modload&name=XForum&file=index\";\n";
	$content .= "\$coppa = \"off\";\n";
	$content .= "\$timeformat = \"24\";\n";
	$content .= "\$adminemail = \"trollix@hacknuke.com\";\n";
	$content .= "\$dateformat = \"dd/mm/yyyy\";\n";
	$content .= "\$statspage = \"on\";\n";
	$content .= "\$affheader = \"on\";\n";
	$content .= "\$afffooter = \"on\";\n";
	$content .= "\$sigbbcode = \"on\";\n";
	$content .= "\$sightml = \"off\";\n";
	$content .= "\$indexstats = \"on\";\n";
	$content .= "\$reportpost = \"on\";\n";
	$content .= "\$showtotaltime = \"off\";\n";

	$content .= "\$dbuser = \"".DBUSER."\";\n";
	$content .= "\$dbpw = \"".DBPW."\";\n";
	$content .= "\$dbhost = \"".DBHOST."\";\n";
	$content .= "\n";
    $content .= "?>";				#<? pour la couleur syntaxique
    $r = fwrite($file, $content);
    fclose($file);
	return $r;	
}

function update_setings() 
  {
	global $ModName;
	
	include("modules/$ModName/settings.php");

	$file = fopen("modules/$ModName/settings.php", "w");
    $line = "######################################################################\n";
    $content = "<?php\n\n";
    $content .= "$line";
    $content .= "# PHP-NUKE: Web Portal System\n";
    $content .= "# ===========================\n";
    $content .= "#\n";
    $content .= "# Copyright (c) 2000 by Francisco Burzi (fbc@mandrakesoft.com)\n";
    $content .= "# http://phpnuke.org\n";
    $content .= "#\n";
    $content .= "# This file is to configure XForum module options for your site\n";
    $content .= "# Original Forum  by XMB Dev Team http://www.xmbforum.com\n";
    $content .= "#\n";
    $content .= "# XForum module by Trollix (trollix@hacknuke.com)\n";
    $content .= "# C. Deltheil http://www.hacknuke.com\n";
    $content .= "#\n";
    $content .= "#\n";
    $content .= "# This program is free software. You can redistribute it and/or modify\n";
    $content .= "# it under the terms of the GNU General Public License as published by\n";
    $content .= "# the Free Software Foundation; either version 2 of the License.\n";
    $content .= "$line";
    $content .= "\n";
    $content .= "$line";
	$content .= "\n";
	$content .= "\n";
	$content .= "\$nukesystem = \""._XMBDEF_NUKESYSTEM."\";\n";
	$content .= "\n";

	$content .= "\$tablepre = \"".$tablepre."\";\n";

	$content .= "\$table_banned = \"".$tablepre."banned\";\n";
	$content .= "\$table_forums = \"".$tablepre."forums\";\n";
	$content .= "\$table_members = \"".$tablepre."members\";\n";
	$content .= "\$table_posts = \"".$tablepre."posts\";\n";
	$content .= "\$table_ranks = \"".$tablepre."ranks\";\n";
	$content .= "\$table_smilies = \"".$tablepre."smilies\";\n";
	$content .= "\$table_themes = \"".$tablepre."themes\";\n";
	$content .= "\$table_threads = \"".$tablepre."threads\";\n";
	$content .= "\$table_u2u = \"".$tablepre."u2u\";\n";
	$content .= "\$table_whosonline = \"".$tablepre."whosonline\";\n";
	$content .= "\$table_words = \"".$tablepre."words\";\n";
	
	$content .= "\n";
	$content .= "\$langfile = \""._XMBDEFLANG."\";\n";
	$content .= "\$bbname = \"".$bbname."\";\n";

	$content .= "\$postperpage = \"".$postperpage."\";\n";
	$content .= "\$topicperpage = \"".$topicperpage."\";\n";
	$content .= "\$hottopic = \"".$hottopic."\";\n";
	$content .= "\$XFtheme = \""._XMBDEFTHEME."\";\n";
	$content .= "\$bbstatus = \"".$bbstatus."\";\n";
	$content .= "\$whosonlinestatus = \"".$whosonlinestatus."\";\n";
	$content .= "\$regstatus = \"".$regstatus."\";\n";
	$content .= "\$bboffreason = \"".$bboffreason."\";\n";
	$content .= "\$regviewonly = \"".$regviewonly."\";\n";
	$content .= "\$floodctrl = \"".$floodctrl."\";\n";
	$content .= "\$memberperpage = \"".$memberperpage."\";\n";
	$content .= "\$catsonly = \"".$catsonly."\";\n";
	$content .= "\$hideprivate = \"".$hideprivate."\";\n";
	$content .= "\$showsort = \"".$showsort."\";\n";
	$content .= "\$emailcheck = \"".$emailcheck."\";\n";
	$content .= "\$bbrules = \"".$bbrules."\";\n";
	$content .= "\$bbrulestxt = \"".$bbrulestxt."\";\n";
	$content .= "\$searchstatus = \"".$searchstatus."\";\n";
	$content .= "\$faqstatus = \"".$faqstatus."\";\n";
	$content .= "\$memliststatus = \"".$memliststatus."\";\n";
	$content .= "\$piconstatus = \"".$piconstatus."\";\n";
	$content .= "\$sitename = \"".$sitename."\";\n";
	$content .= "\$siteurl = \"".$siteurl."\";\n";
	$content .= "\$avastatus = \"".$avastatus."\";\n";
	$content .= "\$noreg = \"".$noreg."\";\n";
	$content .= "\$gzipcompress = \"".$gzipcompress."\";\n";
	$content .= "\$boardurl = \"".$boardurl."\";\n";
	$content .= "\$coppa = \"".$coppa."\";\n";
	$content .= "\$timeformat = \"".$timeformat."\";\n";
	$content .= "\$adminemail = \"".$adminemail."\";\n";
	$content .= "\$dateformat = \"".$dateformat."\";\n";
	$content .= "\$statspage = \"".$statspage."\";\n";
	$content .= "\$affheader = \"on\";\n";
	$content .= "\$afffooter = \"on\";\n";
	$content .= "\$sigbbcode = \"".$sigbbcode."\";\n";
	$content .= "\$sightml = \"".$sightml."\";\n";
	$content .= "\$indexstats = \"".$indexstats."\";\n";
	$content .= "\$reportpost = \"".$reportpost."\";\n";
	$content .= "\$showtotaltime = \"".$showtotaltime."\";\n";

	$content .= "\$dbuser = \"".DBUSER."\";\n";
	$content .= "\$dbpw = \"".DBPW."\";\n";
	$content .= "\$dbhost = \"".DBHOST."\";\n";

	$content .= "\n";
    $content .= "?>";				#<? pour la couleur syntaxique
    $r = fwrite($file, $content);
    fclose($file);

	return $r;	
	
}


#-------------------------------------------------------------------------------
# FONCTIONS DE VERSION
#-------------------------------------------------------------------------------

function creer_base() 
  {

$table_banned		= _PREFIX_TBL."banned";
$table_forums		= _PREFIX_TBL."forums";
$table_members		= _PREFIX_TBL."members";
$table_posts		= _PREFIX_TBL."posts";
$table_ranks		= _PREFIX_TBL."ranks";
$table_smilies		= _PREFIX_TBL."smilies";
$table_themes		= _PREFIX_TBL."themes";
$table_threads		= _PREFIX_TBL."threads";
$table_whosonline	= _PREFIX_TBL."whosonline";
$table_words		= _PREFIX_TBL."words";

echo _PREFIX_TBL;
mysql_query("CREATE TABLE $table_banned (
ip1 smallint(3) NOT NULL,
ip2 smallint(3) NOT NULL,
ip3 smallint(3) NOT NULL,
ip4 smallint(3) NOT NULL,
dateline BIGINT(30) NOT NULL,
id SMALLINT(6) NOT NULL,
PRIMARY KEY(id)
);") or die(mysql_error());

mysql_query("CREATE TABLE $table_forums (
type varchar(15) NOT NULL,
fid smallint(6) NOT NULL auto_increment,
name varchar(50) NOT NULL,
status varchar(15) NOT NULL,
lastpost varchar(30) NOT NULL,
moderator varchar(100) NOT NULL,
displayorder smallint(6) NOT NULL,
private varchar(30),
description text,
allowhtml varchar(3) NOT NULL,
allowsmilies varchar(3) NOT NULL,
allowbbcode varchar(3) NOT NULL,
guestposting varchar(3) NOT NULL,
userlist text NOT NULL,
theme varchar(30) NOT NULL,
posts int(100) NOT NULL,
threads int(100) NOT NULL,
fup smallint(6) NOT NULL,
postperm varchar(3) NOT NULL,
allowimgcode varchar(3) NOT NULL,
PRIMARY KEY(fid)
);") or die(mysql_error());

mysql_query("CREATE TABLE $table_members (
uid smallint(6) NOT NULL auto_increment,
username varchar(25) NOT NULL,
password varchar(18) NOT NULL,
regdate bigint(30) NOT NULL,
postnum smallint(6) NOT NULL,
email varchar(60),
site varchar(75),
aim varchar(40),
status varchar(35) NOT NULL,
location varchar(50),
bio text,
sig text,
showemail varchar(15) NOT NULL,
timeoffset int(5) NOT NULL,
icq varchar(30) NOT NULL,
avatar varchar(90),
yahoo varchar(40) NOT NULL,
customstatus varchar(100) NOT NULL,
theme varchar(30) NOT NULL,
bday varchar(50),
langfile varchar(40) NOT NULL,
tpp smallint(6) NOT NULL,
ppp smallint(6) NOT NULL,
newsletter varchar(3) NOT NULL,
regip varchar(40) NOT NULL,
timeformat int(5) NOT NULL,
msn varchar(40) NOT NULL,
dateformat varchar(10) NOT NULL,
ignoreu2u text,
lastvisit BIGINT(30),
PRIMARY KEY(uid)
);") or die(mysql_error());

mysql_query("CREATE TABLE $table_posts (
fid smallint(6) NOT NULL,
tid smallint(6) NOT NULL,
pid smallint(8) NOT NULL auto_increment,
author varchar(40) NOT NULL,
message text NOT NULL,
dateline BIGINT(30) NOT NULL,
icon varchar(50),
usesig varchar(15) NOT NULL,
useip varchar(40) NOT NULL,
bbcodeoff varchar(15) NOT NULL,
smileyoff varchar(15) NOT NULL,
emailnotify varchar(15) NOT NULL,
PRIMARY KEY(pid)
);") or die(mysql_error());

mysql_query("CREATE TABLE $table_ranks (
title varchar(40) NOT NULL,
posts smallint(6) NOT NULL,
id smallint(6) NOT NULL auto_increment,
stars smallint(6) NOT NULL,
allowavatars varchar(3) NOT NULL,
avatarrank varchar(90),
PRIMARY KEY(id)
);") or die(mysql_error());

mysql_query("CREATE TABLE $table_smilies (
type varchar(15) NOT NULL,
code varchar(40) NOT NULL,
url varchar(40) NOT NULL,
id smallint(6) NOT NULL auto_increment,
PRIMARY KEY(id)
);") or die(mysql_error());

mysql_query("CREATE TABLE $table_themes (
name varchar(30) NOT NULL,
bgcolor varchar(15) NOT NULL,
altbg1 varchar(15) NOT NULL,
altbg2 varchar(15) NOT NULL,
link varchar(15) NOT NULL,
bordercolor varchar(15) NOT NULL,
header varchar(15) NOT NULL,
headertext varchar(15) NOT NULL,
top varchar(15) NOT NULL,
catcolor varchar(15) NOT NULL,
tabletext varchar(15) NOT NULL,
text varchar(15) NOT NULL,
borderwidth varchar(15) NOT NULL,
tablewidth varchar(15) NOT NULL,
tablespace varchar(15) NOT NULL,
font varchar(40) NOT NULL,
fontsize varchar(40) NOT NULL,
altfont varchar(40) NOT NULL,
altfontsize varchar(40) NOT NULL,
replyimg varchar(50),
newtopicimg varchar(50),
boardimg varchar(50),
postscol varchar(5) NOT NULL
);") or die(mysql_error());

mysql_query("CREATE TABLE $table_threads (
tid smallint(6) NOT NULL auto_increment,
fid smallint(6) NOT NULL,
subject varchar(100) NOT NULL,
lastpost varchar(30) NOT NULL,
views int(100) NOT NULL,
replies int(100) NOT NULL,
author varchar(40) NOT NULL,
message text NOT NULL,
dateline BIGINT(30) NOT NULL,
icon varchar(50),
usesig varchar(15) NOT NULL,
closed varchar(15) NOT NULL,
topped smallint(6) NOT NULL,
useip varchar(40) NOT NULL,
bbcodeoff varchar(15) NOT NULL,
smileyoff varchar(15) NOT NULL,
emailnotify varchar(15) NOT NULL,
PRIMARY KEY(tid)
);") or die(mysql_error());

mysql_query("CREATE TABLE $table_whosonline (
username varchar(40) NOT NULL,
ip varchar(40) NOT NULL,
time BIGINT(40) NOT NULL,
location varchar(150) NOT NULL
);") or die(mysql_error());

mysql_query("CREATE TABLE $table_words (
find varchar(60) NOT NULL,
replace1 varchar(60) NOT NULL,
id smallint(6) NOT NULL auto_increment,
PRIMARY KEY(id)
);") or die(mysql_error());

mysql_query("INSERT INTO $table_themes VALUES ( 'gray', '#ffffff', '#dededf', '#eeeeee', '#333399', '#778899', '#778899', '#ffffff', '#eeeeee', '#dcdcde', '#000000', '#000000', '1', '97%', '6', 'Arial', '12px', 'Verdana', '10px', '', '', 'images/logo1.gif', '2col')") or die(mysql_error());
mysql_query("INSERT INTO $table_themes VALUES ( 'blue', '#ffffff', '#b0c0d0', '#d0e0f0', '#cc6600', '#000000', '#e0f0f9', '#000000', '#d0e0f0', '#b0c0d4', '#000000', '#000000', '1', '97%', '6', 'Arial', '12px', 'Verdana', '10px', '', '', 'images/logo1.gif', '2col')") or die(mysql_error());
mysql_query("INSERT INTO $table_themes VALUES ( 'hacknuke', '#2a2f79', '#2a2f79', '#2a2f79', '#ff9900', '#000000', '#ff9900', '#000000', '#000000', '#2a2f79', '#ffffff', '#000000', '1', '97%', '2', 'Arial', '12px', 'Verdana', '10px', '', '', 'images/logo1.gif', '2col')") or die(mysql_error());
mysql_query("INSERT INTO $table_themes VALUES ( 'coursapied', '#ffffff', '#dededf', '#eeeeee', '#333399', '#9999ff', '#9999ff', '#ffffff', '#eeeeee', '#dcdcde', '#000000', '#000000', '1', '97%', '6', 'Arial', '12px', 'Arial', '10px', '', '', '', '2col')") or die(mysql_error());
mysql_query("INSERT INTO $table_themes VALUES ( 'postnuke', '#f6f7eb', '#e1e4ce', '#f6f7eb', '#000000', '#b1b78b', '#d9dcc2', '#000000', '#b1b78b', '#e1e4ce', '#000000', '#000000', '1', '97%', '4', 'Arial', '12px', 'Verdana', '10px', '', '', '', '2col')") or die(mysql_error());
mysql_query("INSERT INTO $table_themes VALUES ( 'default', '#ffffff', '#ffffff', '#ffffff', '#000000', '#000000', '#ffffff', '#000000', '#ffffff', '#ffffff', '#000000', '#000000', '1', '97%', '4', 'Arial', '12px', 'Verdana', '10px', '', '', '', '2col')") or die(mysql_error());
mysql_query("INSERT INTO $table_themes VALUES ( 'XMB2', '#ffffff', '#e3e3ea', '#eeeef6', '#404060', '#ffffff', '#505070', '#ffffff', '#ffffff', '#dedee6', '#000033', '#000022', '1', '90%', '4', 'Arial', '12px', 'Verdana', '10px', '', '', '', '2col')") or die(mysql_error());



mysql_query("INSERT INTO $table_whosonline VALUES ('onlinerecord', '-1', '', '')") or die(mysql_error());

mysql_query("INSERT INTO $table_smilies VALUES ( 'smiley', ':)', 'smilies/smile.gif', '1')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'smiley', ':(', 'smilies/sad.gif', '2')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'smiley', ':D', 'smilies/bigsmile.gif', '3')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'smiley', ';)', 'smilies/wink.gif', '4')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'smiley', ':cool:', 'smilies/cool.gif', '5')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'smiley', ':mad:', 'smilies/mad.gif', '6')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'smiley', ':o', 'smilies/shocked.gif', '7')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'smiley', ':P', 'smilies/tongue.gif', '8')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/smile.gif', '9')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/sad.gif', '10')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/bigsmile.gif', '11')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/wink.gif', '12')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/cool.gif', '13')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/mad.gif', '14')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/shocked.gif', '15')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/tongue.gif', '16')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/exclamation.gif', '17')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/question.gif', '18')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/thumbup.gif', '19')") or die(mysql_error());
mysql_query("INSERT INTO $table_smilies VALUES ( 'picon', '', 'smilies/thumbdown.gif', '20')") or die(mysql_error());

mysql_query("INSERT INTO $table_words VALUES ('damn', '****', '')") or die(mysql_error());
mysql_query("INSERT INTO $table_words VALUES ('shit', '****', '')") or die(mysql_error());
mysql_query("INSERT INTO $table_words VALUES ('fuck', '****', '')") or die(mysql_error());
mysql_query("INSERT INTO $table_words VALUES ('bitch', '*****', '')") or die(mysql_error());
mysql_query("INSERT INTO $table_words VALUES ('ass', '***', '')") or die(mysql_error());

mysql_query("INSERT INTO $table_ranks VALUES ('Newbie', '1', '', '1', 'yes', '')") or die(mysql_error());
mysql_query("INSERT INTO $table_ranks VALUES ('Junior Member', '2', '', '2', 'yes', '')") or die(mysql_error());
mysql_query("INSERT INTO $table_ranks VALUES ('Member', '100', '', '3', 'yes', '')") or die(mysql_error());
mysql_query("INSERT INTO $table_ranks VALUES ('Senior Member', '500', '', '4', 'yes', '')") or die(mysql_error());
mysql_query("INSERT INTO $table_ranks VALUES ('Posting Freak', '1000', '', '5', 'yes', '')") or die(mysql_error());

}

function maj_base() 
  {
  }

function maj_users() 
  {
/*	global $prefix;

	$query = mysql_query("select * from ".$prefix."_users order by uid");
	$num = mysql_result($query,0);

	echo "<h4>Utilisateurs</h4>";
	while ($member = mysql_fetch_array($query)) 
	  {
	$xmb_username = $member[uname];
	if ( $user_viewemail == "1" ) $xmb_showemail = "yes"; else $xmb_showemail = "no";
	$xmb_avatar = "images/avatar/".$member[user_avatar];
	$xmb_password = $member[pass];
	$xmb_regdate = time();
	$xmb_postnum = "0";
	$xmb_email = $member[email];
	$xmb_site = $member[url];
	$xmb_aim = $member[user_aim];
	$xmb_status = "Member";
	$xmb_location = $member[user_from];
	$xmb_bio = $member[user_occ];
	$xmb_sig = $member[user_sig];
	$xmb_icq = $member[user_icq];
	$xmb_yahoo = $member[user_yim];
	$xmb_customstatus = "";
	$xmb_theme = "";
	$xmb_bday="";
	$xmb_langfile="";
	$xmb_tpp = "25";
	$xmb_ppp = "30";
	$xmb_newsletter = "no";
	$xmb_timeoffset = "";
	$xmb_regip = "";
	$xmb_timeformat = "24";
	$xmb_msn = $member[user_msnm];
	$xmb_dateformat = "dd/mm/yyyy";
	$xmb_lastvisit = "";

	$result = mysql_query("INSERT INTO "._PREFIX_TBL."members VALUES ('', '$xmb_username', '$xmb_password', '$xmb_regdate', '$xmb_postnum', '$xmb_email', '$xmb_site', '$xmb_aim', '$xmb_status', '$xmb_location', '$xmb_bio', '$xmb_sig', '$xmb_showemail', '$xmb_timeoffset', '$xmb_icq', '$xmb_avatar', '$xmb_yahoo', '$xmb_customstatus', '$xmb_theme', '$xmb_bday', '$xmb_langfile', '$xmb_tpp', '$xmb_ppp', '$xmb_newsletter', '$xmb_regip', '$xmb_timeformat', '$xmb_msn', '$xmb_dateformat', '$xmb_ignoreu2u', '$xmb_lastvisit')");
	
	if (!$result) echo "Nok<br>";
	print $member[uname]." ".$member[pass]."Ok<br>";

  }
echo "<br>";

$query = mysql_query("select * from ".$prefix."_authors order by aid");
echo "<h4>Administrateurs</h4>";
while ($member = mysql_fetch_array($query)) 
  {
	print $member[aid]." ".$member[pwd]."<br>";

	$xmb_username = $member[aid];
	$xmb_showemail = "yes";
	$xmb_avatar = "images/avatar/001.gif";
	$xmb_password = $member[pwd];
	$xmb_regdate = time();
	$xmb_postnum = "0";
	$xmb_email = $member[email];
	$xmb_site = $member[url];
	$xmb_aim = "";
	$xmb_status = "Administrator";
	$xmb_location = "";
	$xmb_bio = "";
	$xmb_sig = "";
	$xmb_icq = "";
	$xmb_yahoo = "";
	$xmb_customstatus = "";
	$xmb_theme = "";
	$xmb_bday="";
	$xmb_langfile="";
	$xmb_tpp = "25";
	$xmb_ppp = "30";
	$xmb_newsletter = "no";
	$xmb_timeoffset = "";
	$xmb_regip = "";
	$xmb_timeformat = "24";
	$xmb_msn = "";
	$xmb_dateformat = "dd/mm/yyyy";
	$xmb_ignoreu2u = "";
	$xmb_lastvisit = "";

	mysql_query("INSERT INTO "._PREFIX_TBL."members VALUES ('', '$xmb_username', '$xmb_password', '$xmb_regdate', '$xmb_postnum', '$xmb_email', '$xmb_site', '$xmb_aim', '$xmb_status', '$xmb_location', '$xmb_bio', '$xmb_sig', '$xmb_showemail', '$xmb_timeoffset', '$xmb_icq', '$xmb_avatar', '$xmb_yahoo', '$xmb_customstatus', '$xmb_theme', '$xmb_bday', '$xmb_langfile', '$xmb_tpp', '$xmb_ppp', '$xmb_newsletter', '$xmb_regip', '$xmb_timeformat', '$xmb_msn', '$xmb_dateformat', '$xmb_ignoreu2u', '$xmb_lastvisit')") or die(mysql_error());
  }
*/
  }

function suppr_base() 
  {
	global $prefix;
	
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."announce");
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."banned") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."forums") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."members") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."posts") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."ranks") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."smilies") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."themes") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."threads") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."u2u");
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."words") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."whosonline") or die(mysql_error());
  }

  function maj_base_alphaversall_echo() 
  {
	global $databasename;

	$content .= "\$nukesystem = \""._XMBDEF_NUKESYSTEM."\";\n";
	$content .= "\n";
	$content .= "\n // Init tables // \n";
	$content .= "\$tablepre = \""._PREFIX_TBL."\";\n";
	$content .= "\$tables = array('announce','banned','forums', 'members', 'posts', 'ranks', 'smilies', 'themes', 'threads', 'whosonline', 'words');\n";
	$content .= "foreach(\$tables as \$name) { \${'table_'.\$name} = \$tablepre.\$name; }\n";
	$content .= "\n";



	return $content;
  }

  function maj_base_alphaversall() 
  {
	global $databasename;

mysql_query("ALTER TABLE `"._PREFIX_TBL."banned` CHANGE `dateline` `dateline` BIGINT (30) DEFAULT '0' not null") or die(mysql_error());
mysql_query("ALTER TABLE `"._PREFIX_TBL."members` CHANGE `regdate` `regdate` BIGINT (30) DEFAULT '0' not null ") or die(mysql_error());
mysql_query("ALTER TABLE `"._PREFIX_TBL."members` CHANGE `lastvisit` `lastvisit` BIGINT (30) DEFAULT '0' not null ") or die(mysql_error());
mysql_query("ALTER TABLE `"._PREFIX_TBL."posts` CHANGE `dateline` `dateline` BIGINT (30) DEFAULT '0' not null ") or die(mysql_error());
mysql_query("ALTER TABLE `"._PREFIX_TBL."threads`  CHANGE `dateline` `dateline` BIGINT (30) DEFAULT '0' not null") or die(mysql_error());
mysql_query("ALTER TABLE `"._PREFIX_TBL."whosonline` CHANGE `time` `time` BIGINT (40) DEFAULT '0' not null ") or die(mysql_error());

mysql_query("DELETE FROM "._PREFIX_TBL."themes WHERE name = 'postnuke' ")  or die(mysql_error());
mysql_query("INSERT INTO "._PREFIX_TBL."themes VALUES ( 'postnuke', '#f6f7eb', '#e1e4ce', '#f6f7eb', '#000000', '#b1b78b', '#d9dcc2', '#000000', '#b1b78b', '#e1e4ce', '#000000', '#000000', '1', '97%', '4', 'Arial', '12px', 'Verdana', '10px', '', '', '', '2col')") or die(mysql_error());

mysql_query("DELETE FROM "._PREFIX_TBL."themes  WHERE name = 'default' ") or die(mysql_error());
mysql_query("INSERT INTO "._PREFIX_TBL."themes VALUES ( 'default', '#ffffff', '#ffffff', '#ffffff', '#000000', '#000000', '#ffffff', '#000000', '#ffffff', '#ffffff', '#000000', '#000000', '1', '97%', '4', 'Arial', '12px', 'Verdana', '10px', '', '', '', '2col')") or die(mysql_error());

mysql_query("DELETE FROM "._PREFIX_TBL."themes  WHERE name = 'XMB2' ") or die(mysql_error());
mysql_query("INSERT INTO "._PREFIX_TBL."themes VALUES ( 'XMB2', '#ffffff', '#e3e3ea', '#eeeef6', '#404060', '#ffffff', '#505070', '#ffffff', '#ffffff', '#dedee6', '#000033', '#000022', '1', '90%', '4', 'Arial', '12px', 'Verdana', '10px', '', '', '', '2col')") or die(mysql_error());


  }


#-------------------------------------------------------------------------------
# TEST DES VERSIONS
#-------------------------------------------------------------------------------

function strfileperms( $filename )
{
	if (is_string($filename)) 
	  { 
		if (is_dir($filename)) { $str = "d"; } 
		else { $str = "-"; }
  
		$perms = fileperms($filename); 
		$perms = $perms & 0777; 
  
		$mask = 0700; 
  
		for ($i = 0;$i<3;$i++) 
		  { 
			$droits = $perms & $mask; 
			if ($i == 0) {  $droits = $droits >> 6; } 
			elseif ($i == 1) { $droits = $droits >> 3; } 

			if ($droits & 04) { $str .= "r"; } 
			else { $str .= "-"; } 
	
			if ($droits & 02) { $str .= "w"; } 
			else { $str .= "-"; } 

			if ($droits & 01) { $str .= "x"; } 
			else { $str .= "-"; } 
			
			$mask = $mask >> 3; 
		  } 
		} 
	return $str;
}

function strfileoctalperms( $filename )
{
	if (is_string($filename)) 
	  { 
		$perms = fileperms($filename) ;
		$perms = sprintf("%o",$perms-32768);
	  }
  	return $perms;
}


function tester_upload() {
	global $hebergeur;
	$test_upload = true;
	if ($hebergeur == 'multimania') $test_upload = false;
	return $test_upload;
}

function tester_accesdistant() {
	global $hebergeur;
	$test_acces = true;
	if ($hebergeur == 'multimania') $test_acces = false;
	return $test_acces;
}


//
// Infos sur l'hebergeur
//

if (ereg('multimania\.(com|net|fr)$', $HTTP_X_HOST))
	$hebergeur = 'multimania';
else if (ereg('altern\.com$', $SERVER_NAME))
	$hebergeur = 'altern';
else if (ereg('^/[^.]*\.free.fr/', $REQUEST_URI))
	$hebergeur = 'free';

if (eregi('\(Win', $SERVER_SOFTWARE))
	$os_serveur = 'windows';

//
// Infos sur le fichier courant
//

// Compatibilite avec serveurs ne fournissant pas $REQUEST_URI
if (!$REQUEST_URI) {
	$REQUEST_URI = $PHP_SELF;
	if ($QUERY_STRING) $REQUEST_URI .= '?'.$QUERY_STRING;
}


//
// Infos de version PHP
//

$php_version = explode('.', phpversion());
$php_version_maj = (int) $php_version[0];
$php_version_med = (int) $php_version[1];
if (ereg('([0-9]+)', $php_version[2], $match)) $php_version_min = (int) $match[1];

$flag_function_exists = ($php_version_maj > 3 OR $php_version_min >= 7);
$flag_ignore_user_abort = ($php_version_maj > 3 OR $php_version_min >= 7);
$flag_levenshtein = ($php_version_maj >= 4);
$flag_mt_rand = ($php_version_maj > 3 OR $php_version_min >= 6);
$flag_str_replace = ($php_version_maj > 3 OR $php_version_min >= 8);
$flag_strpos_3 = (@strpos('baba', 'a', 2) == 3);

if ($flag_function_exists) {
	$gz_exists = function_exists("gzopen");
	$flag_preg_replace = function_exists("preg_replace");
	$flag_crypt = function_exists("crypt");
}
else {
	$gz_exists = false;
	$flag_preg_replace = false;
	$flag_crypt = true; // la non-existence de crypt est une exception
}


//
// Début du script par étapes
//

if ($connect)
	  {
echo "connect";
	  }


	#
	################################## ETAPE 3 #################################################################
	#

	elseif($etape3)
	  {
		debut_html();
		if ( count($tableprefix) == 0)	
		{ 
			if (count($tablexfprefix)==0) { $pfxtbl = ""; }
			else { $pfxtbl = $tablexfprefix."_"; }
		}
		else 
		{ 
			if (count($tablexfprefix)==0) { $pfxtbl = $tableprefix."_"; }
			else { $pfxtbl = $tableprefix."_".$tablexfprefix."_"; }
		}			
		
		define("_PREFIX_TBL",$pfxtbl);
		define("_XMBDEFLANG",$xmbdeflang);
		define("_XMBDEFTHEME",$xmbdeftheme);
		define("_XMBDEF_NUKESYSTEM",$nukesystem);
		
		global $databasename;

		echo "<BR><div class=\"soustitre\">"._STEP3." : <B> "._CREATE_TABLES." : </B>("._PREFIX_TBL.")</div>";
		echo "<P>";

		$link = mysql_connect("$adresse_db", "$login_db", "$pass_db");

		echo "<!-- ";

		if ($act=="suppr") 
		  {
			suppr_base();
			echo "<B>"._DB_SUPPR_OK."</B>";
			fin_html();
			exit();
		  }
		elseif ($act=="upgrade")
		  {
			maj_base_alphaversall();
			$acopier = maj_base_alphaversall_echo();
			echo "-->";
			echo "<br>";
			update_setings();
			#echo _PLEASECOPY."<br>\n";
			#echo "<pre>";
			#echo $acopier;
			#echo "</pre>";

			echo "<FORM ACTION='"._MODINST_URL_PREFIX."index' METHOD='post'>";
			echo "<DIV align='right'><INPUT TYPE='submit' CLASS='fondl' NAME='Valider' VALUE='"._NEXT2." >>'>";
			echo "</FORM>";

			echo "<!--";
			#maj_base();
			#maj_users();
			exit();
		  }
		else
		  {
			creer_base();
			#maj_base();
			#maj_users();
		  }

		$query = "SELECT COUNT(*) FROM "._PREFIX_TBL."words";
		$result = mysql_query($query);
		$result_ok_mysql = (mysql_num_rows($result) > 0);
		
		# ici on ne sauve plus le config.php
		/*
		$result_ok1 = saveconf();
		if (! $result_ok1) echo "nok fwrite config.php";
		*/

		$result_ok2 = savesettings() ;
		if (! $result_ok2) echo "nok fwrite settings.php";

		echo " -->";

		
		if ($result_ok_mysql)
		  {
			echo "<B>"._STRUCT_DB_INST."</B><P>"._NEXT_STEP;

			echo "<FORM ACTION='"._MODINST_URL_PREFIX."index' METHOD='post'>";
			echo "<DIV align='right'><INPUT TYPE='submit' CLASS='fondl' NAME='Valider' VALUE='"._NEXT2." >>'>";
			echo "</FORM>";
		  } 
		else {  echo "<B>"._OP_FAILED."</B> "._PREV_NEW_SELECT;  }

		fin_html();
	  }
	#
	################################## ETAPE 2 #################################################################
	#
	elseif($etape2)
	{
		debut_html();

		echo "<BR><div class=\"soustitre\">"._STEP2." : <B>"._TRY_BASE_CONNECT."</B></FONT>";
	
		echo "<!--";
		$link=mysql_connect("$adresse_db","$login_db","$pass_db");
		$db_connect=mysql_errno();
		echo "-->";
		echo "<P>";
		
		if (($db_connect=="0") && $link)
		  {
			echo "<B>"._CONNECT_OK."</B><P> "._CAN_NEXT_STEP;

			echo "<FORM ACTION='"._MODINST_URL_PREFIX."install&etape3=oui' METHOD='post'>";
			echo "<INPUT TYPE='hidden' NAME='adresse_db'  VALUE=\"$adresse_db\" SIZE='40'>";
			echo "<INPUT TYPE='hidden' NAME='login_db' VALUE=\"$login_db\">";
			echo "<INPUT TYPE='hidden' NAME='pass_db' VALUE=\"$pass_db\"><P>";
			echo "<INPUT TYPE='hidden' NAME='tableprefix' VALUE=\"$tableprefix\"><P>";
			echo "<INPUT TYPE='hidden' NAME='tablexfprefix' VALUE=\"$tablexfprefix\"><P>";
			echo "<INPUT TYPE='hidden' NAME='nukesystem' VALUE=\"$nukesystem\"><P>";
			echo "<INPUT TYPE='hidden' NAME='databasename' VALUE=\"$databasename\"><P>";
			echo "<INPUT TYPE='hidden' NAME='xmbdeflang' VALUE=\"$xmbdeflang\"><P>";
			echo "<INPUT TYPE='hidden' NAME='xmbdeftheme' VALUE=\"$xmbdeftheme\"><P>";
			echo "<INPUT TYPE='hidden' NAME='instlang' VALUE=\"$instlang\"><P>";
			echo "<INPUT TYPE='hidden' NAME='databasename' VALUE=\"$databasename\"><P>";
			echo "<INPUT TYPE='hidden' NAME='act' VALUE=\"$act\">";
			echo "<DIV align='right'><INPUT TYPE='submit' CLASS='fondl' NAME='Valider' VALUE='"._NEXT2." >>'>";
			echo "</FORM>";
		  }
		else
		  {
			echo "<B>"._CONNECT_FAILED."</B>";
			echo "<P>"._PREV_NEW_VERIFYOURINFO;
			echo "<P><FONT SIZE=2><B>N.B.</B> "._ASK_BD_ACTIVATION."</FONT>";
		  }

		fin_html();
			
	}

	#
	################################## ETAPE 1 #################################################################
	#
	elseif($etape1)
	  {
		debut_html();
		
		/* Dans le nouveau install pas de config.php
		$fichconfigperms = strfileperms("modules/$ModName/config.php");
		if (! preg_match("/-rw[-|x]rw[-|x]rw[-|x]/i", $fichconfigperms, $parts))
		  {
			echo "<br><br><b>\"config.php\":</b> "._FILE_NOT_WRITEN;
			
			echo "<!--";
			$chmod = chmod( "modules/$ModName/config.php", 0755 );
			echo "-->";

			if ( $chmod ) { echo _MAJ_OK."<br>"; }
			else 
			  { 
				echo _FAILED.".<br><Br>"._MANUAL_RIGHTS."<br>";

				echo "<FORM ACTION='"._MODINST_URL_PREFIX."index' METHOD='post'>";
				echo "<DIV align='right'><INPUT TYPE='submit' CLASS='fondl' NAME='Valider' VALUE='"._NEXT2." >>'>";
				echo "</FORM>";
				fin_html();
				exit();
			  }
		  }
		*/
		if ( ( _ACTION == "upgrade" ) or ( _ACTION == "suppr" ))
		{ 
			include("modules/$ModName/settings.php");  
			$tabspl = split("_", $tablepre);
			$xfprefix = $tabspl[1];
		}

		$fichconfigperms = strfileperms("modules/$ModName/settings.php");
		if (! preg_match("/-rw[-|x]rw[-|x]rw[-|x]/i", $fichconfigperms, $parts))
		  {
			echo "<br><br><b>\"settings.php\":</b> "._FILE_NOT_WRITEN;
			
			echo "<!--";
			$chmod = chmod( "modules/$ModName/settings.php", 0755 );
			echo "-->";

			if ( $chmod ) { echo _MAJ_OK."<br>"; }
			else 
			  { 
				echo _FAILED.".<br><Br>"._MANUAL_RIGHTS."<br>";

				#echo "<FORM ACTION='"._MODINST_URL_PREFIX."index' METHOD='post'>";
				#echo "<DIV align='right'><INPUT TYPE='submit' CLASS='fondl' NAME='Valider' VALUE='"._NEXT2." >>'>";
				#echo "</FORM>";
				fin_html();
				exit();
			  }
		  }


		echo "<BR><div class=\"soustitre\">"._STEP1." : <B>"._INSTALL_PARAM."</B></FONT>";

		echo "<BR><br><div class=\"soustitre\">"._INSTALLFOR;
		if ($nukesystem == "postnuke6x" ) { echo " : <B>"._CH_POSTNUKE6X."</B></FONT>"; }
		if ($nukesystem == "phpnuke5x" )  { echo " : <B>"._CH_PHPNUKE5X."</B></FONT>"; }
		echo "<br>";

		echo "<P class=\"expl\">"._TXT_CONSULT_PROVIDER;

		echo "<FORM ACTION='"._MODINST_URL_PREFIX."install&etape2=oui' METHOD='post'>";
		echo "<INPUT TYPE='hidden' NAME='act' VALUE=\"$act\">";

		echo "<fieldset class=\"expl\"><legend><B>"._TXT_DB_ADRESS."</B><BR></legend>";
		echo _TXT_DB_ADRESS_EXPL."<BR>";
		echo "<INPUT TYPE='text' NAME='adresse_db' CLASS='formo' VALUE=\"$dbhost\" SIZE='40'></fieldset><P>";

		echo "<fieldset class=\"expl\"><legend><B>"._TXT_DB_LOGIN."</B><BR></legend>";
		echo _TXT_DB_LOGIN_EXPL."<BR>";
		echo "<INPUT TYPE='text' NAME='login_db' CLASS='formo' VALUE=\"$dbuname\" SIZE='40'></fieldset><P>";

		echo "<fieldset class=\"expl\"><legend><B>"._TXT_DB_PASSWORD."</B><BR></legend>";
		echo _TXT_DB_PASSWORD_EXPL."<BR>";
		echo "<INPUT TYPE='text' NAME='pass_db' CLASS='formo' VALUE=\"$dbpass\" SIZE='40'></fieldset><P>";

		echo "<P class=\"expl\">"._TXT_INFO_VALDEF."</p>";

		echo "<fieldset class=\"expl\"><legend><B>"._TXT_NUKE_PREFIX."</B><BR></legend>";
		echo _TXT_NUKE_PREFIX_EXPL."<BR>";
		echo "<INPUT TYPE='text' NAME='tableprefix' CLASS='formo' VALUE=\"$prefix\" SIZE='40'></fieldset><P>";

		echo "<fieldset class=\"expl\"><legend><B>"._TXT_XF_PREFIX."</B><BR></legend>";
		echo _TXT_XF_PREFIX_EXPL."<BR>";
		echo "<INPUT TYPE='text' NAME='tablexfprefix' CLASS='formo' VALUE=\"$xfprefix\" SIZE='40'></fieldset><P>";

		echo "<fieldset class=\"expl\"><legend><B>"._TXT_NUKE_DBASE."</B><BR></legend>";
		echo _TXT_NUKE_DBASE_EXPL."<BR>";
		echo "<INPUT TYPE='text' NAME='databasename' CLASS='formo' VALUE=\"$dbname\" SIZE='40'></fieldset><P>";

		echo "<fieldset class=\"expl\"><legend><B>"._TXT_XMB_LANG."</B><BR></legend>";
		echo _TXT_XMB_LANG_EXPL."<BR>";
		if ($language=="french") $selected_fra="selected";
		elseif ($language=="english") $selected_eng="selected";
		echo "<select name=\"xmbdeflang\">"
			."<option value=\"french\" $selected_fra>Fran&ccedil;ais</option>"
			."<option value=\"english\" $selected_eng>English</option>"
			."</select>"
			."</fieldset><P>";

		echo "<fieldset class=\"expl\"><legend><B>"._TXT_XMB_THEME."</b><BR></legend>";
		echo _TXT_XMB_THEME_EXPL."<BR>";
		echo "<select name=\"xmbdeftheme\">"
			."<option value=\"default\">Default</option>"
			."<option value=\"gray\">Gray</option>"
			."<option value=\"postnuke\">PostNuke</option>"
			."<option value=\"blue\">Blue</option>"
			."<option value=\"hacknuke\">Hacknuke</option>"
			."<option value=\"coursapied\">Coursapied</option>"
			."<option value=\"coursapied\">XMB2</option>"
			."</select>"
			."</fieldset><P>";
		
		echo "<INPUT TYPE='hidden' NAME='instlang' VALUE=\"$instlang\"><P>";
		echo "<INPUT TYPE='hidden' NAME='nukesystem' VALUE=\"$nukesystem\"><P>";
		echo "<DIV align='right'><INPUT TYPE='submit' CLASS='fondl' NAME='Valider' VALUE='"._NEXT2." >>'>";


		echo "</FORM>";

		fin_html();
		
	  }
	else 
	  {
		  debut_html();
		  
		?>
			<script language="javascript">
			function Refresh(s)
			{		
			  var Item = (s.options[s.selectedIndex].value);
			  window.top.location.href = Item;
			}
			</script>

			<form>
			  <table>
				<tr>
				  <td width="120"><? echo _CH_LANGUE ?></td>
				  <!--<td width="150"><? echo _CH_NUKESYSTEM ?></td>-->
				  <td width="180"><? echo _CH_OPERATION ?></td>
				</tr>
				<tr><td width="120">
					<select name="instlang" ONCHANGE="Refresh(this);" LANGUAGE="JavaScript">
					<option value="<?=_MODINST_URL_PREFIX."install"; ?>"><? echo _CH_LANGUE ?></option>
					<option value="<?=_MODINST_URL_PREFIX."install&instlang=fr&nukesystem=$nukesystem"; ?>" <? if ($instlang=="fr") {echo" selected";} ?> ><? echo _CH_FRENCH ?></option>
					<option value="<? echo _MODINST_URL_PREFIX."install&instlang=en&nukesystem=$nukesystem"; ?>" <? if ($instlang=="en") {echo" selected";} ?> ><? echo _CH_ENGLISH ?></option>
					</select>
				</td>
				<!--<td width="150">
					<select name="nukesystem" ONCHANGE="Refresh(this);" LANGUAGE="JavaScript">
					<option value="<?=_MODINST_URL_PREFIX."install"; ?>"><?=_CH_NUKESYSTEM ?></option>
					<option value="<?=_MODINST_URL_PREFIX."install&instlang=$instlang&nukesystem=postnuke6123"; ?>" <? if ($nukesystem=="postnuke6123") {echo" selected";} ?> ><?=_CH_POSTNUKE6123 ?></option>
					<option value="<?=_MODINST_URL_PREFIX."install&instlang=fr&nukesystem=postnuke64"; ?>" <? if ($nukesystem=="postnuke64") {echo" selected";} ?> ><?=_CH_POSTNUKE64 ?></option>
					<option value="<?=_MODINST_URL_PREFIX."install&instlang=fr&nukesystem=phpnuke5012"; ?>" <? if ($nukesystem=="phpnuke5012") {echo" selected";} ?> ><?=_CH_PHPNUKE5012 ?></option>
					</select>
				</td>-->
				<td width="180">
					<select name="Navigate1" ONCHANGE="Refresh(this);" LANGUAGE="JavaScript">
					<option value="<?=_MODINST_URL_PREFIX."install"; ?>"><?=_CH_OPERATION ?></option>
					<option value="<?=_MODINST_URL_PREFIX."install&etape1=oui&instlang=$instlang&nukesystem=$nukesystem"; ?>"><?=_CH_INSTALL ?></option>
					<option value="<?=_MODINST_URL_PREFIX."install&etape1=oui&act=suppr&instlang=$instlang&nukesystem=$nukesystem"; ?>"><?=_CH_UNINSTALL ?></option>
					<option value="<?=_MODINST_URL_PREFIX."install&etape1=oui&act=upgrade&instlang=$instlang&nukesystem=$nukesystem"; ?>"><?=_CH_UPGRADE ?></option>
					</select>
				</td>
				</tr>
			  </table>
			</form>
		<?
  

		#header("Location: "._MODINST_URL_PREFIX."install&etape1=oui&act=$act&instlang=$instlang");
	  }



?>
</FONT>
</TD></TR></TABLE>
</CENTER>
</BODY>


</HTML>
