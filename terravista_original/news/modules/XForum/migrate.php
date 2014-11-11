<?
if (!isset($mainfile)) { include("mainfile.php"); }
include("auth.inc.php");
$SYSADMIN = is_admin($admin);	# User is Admin
if (!$SYSADMIN) { die ("Access Denied. You must be administrator of your system"); }

$ModName="XForum";
if (! isset($instlang)) { $instlang=="en"; } # Langue pae défaut

define("_MODINST_PREFIX","modules/".$ModName."/");
define("_MODINST_URL_PREFIX","modules.php?op=modload&name=$ModName&file=");

define("_PREFIX_TBL",$prefix."_xmb_");

define("DBNAME", $dbname);
define("DBUSER", $dbuname);
define("DBPW", $dbpass);
define("DBHOST", $dbhost);
define("_ACTION", $act);

if ( ($language=="french") or ($instlang=="fr") )
{

if ( _ACTION == "suppr" ) 
  {
	define("_TITRE","Désinstallation de XMBForum module pour P(hp&ost)Nuke by Trollix");
	define("_CREATE_TABLES","Supression des tables de la base");
  }
elseif ( _ACTION == "upgrade" ) 
  {
	define("_TITRE","Mise à jour de XMBForum  module pour P(hp&ost)Nuke by Trollix");
	define("_CREATE_TABLES","Mise à jour des tables de la base");
  }
else 
  {
	define("_TITRE","Installation de XMBForum  module pour P(hp&ost)Nuke by Trollix");
	define("_CREATE_TABLES","Cr&eacute;ation des tables de la base");
  }

define("_CH_OPERATION","Type d'Opération");
define("_CH_INSTALL","Installation");
define("_CH_UNINSTALL","Désinstallation");
define("_CH_UPGRADE","Upgrade Alpha->Beta");
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
define("_TXT_NUKE_DBASE","Le nom de votre base");
define("_TXT_NUKE_DBASE_EXPL","(C'est la valeur de votre base surlaquelle votre Nuke est installée. C'est la valeur que vous avez dans votre config.php. Il est conseillé de laisser cette valeur par défaut sauf si vous savez ce que vous faites)");
define("_TXT_XMB_LANG","La langue du forum par défaut");
define("_TXT_XMB_LANG_EXPL","(Correspond à la langue qui sera affectée par défaut à tous les utilisateurs. Nota: ils pourront changer cette valeur ensuite)");
define("_TXT_XMB_THEME","Le thème du forum par défaut");
define("_TXT_XMB_THEME_EXPL","(Correspond au thème du forum qui sera appliqué par défaut à tous les utilisateurs lors de leur inscription. Il est fortement conseillé de laisser le défaut à gray pour une première installation. Nota: Cette valeur pourra être modifiée ensuite par l'utilisateur)");
define("_DB_SUPPR_OK","Base de Donnée XMB Supprimée avec succès");
}

else 
{
if ( _ACTION == "suppr" ) 
  {
	define("_TITRE","XMBForum module for P(hp&ost)Nuke Uninstall by Trollix");
	define("_CREATE_TABLES","Databases Tables Suppression");
  }
elseif ( _ACTION == "upgrade" ) 
  {
	define("_TITRE","XMBForum module for P(hp&ost)Nuke Upgrade by Trollix");
	define("_CREATE_TABLES","Databases Tables Upgrade");
  }
else 
  {
	define("_TITRE","XMBForum module for P(hp&ost)Nuke Installation by Trollix");
	define("_CREATE_TABLES","Databases Tables Creation");
  }

define("_CH_OPERATION","Operation:");
define("_CH_INSTALL","Install");
define("_CH_UNINSTALL","Uninstall");
define("_CH_UPGRADE","Upgrade Alpha->Beta");
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
define("_TXT_NUKE_DBASE","Database Name");
define("_TXT_NUKE_DBASE_EXPL","(It 's the DataBase Name where your Nuyke is installed on. This value is the same one than in your Nuke config.php, don't change it if you do not know what you do.)");
define("_TXT_XMB_LANG","Default language for the Forum");
define("_TXT_XMB_LANG_EXPL","(It is the default language value affected to a new user connected to your Nuke system. Nota: it could be changed later)");
define("_TXT_XMB_THEME","Default theme of your forum");
define("_TXT_XMB_THEME_EXPL","(It is the default theme value affected to a new user connected to your Nuke system. Nota: it could be changed later)");
define("_DB_SUPPR_OK","Database deleted with success");
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
<TABLE WIDTH=450>
<TR><TD WIDTH=450>
<FONT FACE="Verdana,Arial,Helvetica,sans-serif" SIZE=4 COLOR="#970038"><B><? echo $tit; ?></B></FONT>
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

// saveconf
function saveconf() 
  {
	global $ModName;


	$file = fopen("modules/$ModName/config.php", "w");
    $line = "######################################################################\n";
    $content = "<?php\n\n";
    $content .= "$line";
    $content .= "# PHP-NUKE: Web Portal System\n";
    $content .= "# ===========================\n";
    $content .= "#\n";
    $content .= "# Copyright (c) 2000 by Francisco Burzi (fbc@mandrakesoft.com)\n";
    $content .= "# http://phpnuke.org\n";
    $content .= "#\n";
    $content .= "# This file is to configure XMB module options for your site\n";
    $content .= "# XMB v1.05 from Copyright © 2001 XMB Dev Team http://www.xmbforum.com\n";
    $content .= "#\n";
    $content .= "# Nuke Integration and modulization by Trollix (trollix@hacknuke.com)\n";
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
	$content .= "\$dbname = \"".DBNAME."\";\n";
	$content .= "\$dbuser = \"".DBUSER."\";\n";
	$content .= "\$dbpw = \"".DBPW."\";\n";
	$content .= "\$dbhost = \"".DBHOST."\";\n";
	$content .= "\$tablepre = \""._PREFIX_TBL."\";\n";
	$content .= "\n";
	$content .= "\n";

	$content .= "\$tables = array('announce','banned','forums', 'members', 'posts', 'ranks', 'smilies', 'themes', 'threads', 'u2u', 'whosonline', 'words');\n";
	$content .= "foreach(\$tables as \$name) { \${'table_'.\$name} = \$tablepre.\$name; }\n";

	$content .= "\n";
    $content .= "?>";				#<? pour la couleur syntaxique
    $r = fwrite($file, $content);
    fclose($file);
	return $r;
	
}

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
    $content .= "# This file is to configure XMB module options for your site\n";
    $content .= "# XMB v1.05 from Copyright © 2001 XMB Dev Team http://www.xmbforum.com\n";
    $content .= "#\n";
    $content .= "# Nuke Integration and modulization by Trollix (trollix@hacknuke.com)\n";
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
	$content .= "\$u2ustatus = \"off\";\n";
	$content .= "\$searchstatus = \"on\";\n";
	$content .= "\$faqstatus = \"on\";\n";
	$content .= "\$memliststatus = \"on\";\n";
	$content .= "\$piconstatus = \"on\";\n";
	$content .= "\$sitename = \"HackNuke.com\";\n";
	$content .= "\$siteurl = \"http://www.hacknuke.com/\";\n";
	$content .= "\$avastatus = \"on\";\n";
	$content .= "\$u2uquota = \"75\";\n";
	$content .= "\$noreg = \"off\";\n";
	$content .= "\$gzipcompress = \"off\";\n";
	$content .= "\$boardurl = \"http://www.hacknuke.com/modules.php?op=modload&name=XForum&file=index\";\n";
	$content .= "\$coppa = \"off\";\n";
	$content .= "\$timeformat = \"24\";\n";
	$content .= "\$adminemail = \"trollix@hacknuke.com\";\n";
	$content .= "\$dateformat = \"dd/mm/yyyy\";\n";
	$content .= "\$statspage = \"on\";\n";
	$content .= "\$sigbbcode = \"on\";\n";
	$content .= "\$sightml = \"off\";\n";
	$content .= "\$indexstats = \"on\";\n";
	$content .= "\$reportpost = \"on\";\n";
	$content .= "\$showtotaltime = \"off\";\n";

	$content .= "\$dbuser = \"".DBUSER."\";\n";
	$content .= "\$dbpw = \"".DBPW."\";\n";
	$content .= "\$dbhost = \"".DBHOST."\";\n";
	$content .= "\$tablepre = \""._PREFIX_TBL."\";\n";
	$content .= "\n";
    $content .= "?>";				#<? pour la couleur syntaxique
    $r = fwrite($file, $content);
    fclose($file);
	return $r;	
}

#-------------------------------------------------------------------------------
# FONCTIONS DE VERSION
#-------------------------------------------------------------------------------

function update_phpbb() 
  {
	
	global $prefix;
	$tables = array('banned','forums', 'members', 'posts', 'ranks', 'smilies', 'themes', 'threads', 'u2u', 'whosonline', 'words');

	foreach($tables as $name) { ${'table_'.$name} = _PREFIX_TBL.$name; }

	echo "<h5>Maj  $table_members</h5>";

	mysql_query("DROP TABLE IF EXISTS $table_members");
	mysql_query("CREATE TABLE nuke_xmb_members (
	  uid smallint(6) NOT NULL auto_increment,
	  username varchar(25) NOT NULL default '',
	  password varchar(18) NOT NULL default '',
	  regdate bigint(30) NOT NULL default '0',
	  postnum smallint(6) NOT NULL default '0',
	  email varchar(60) default NULL,
	  site varchar(75) default NULL,
	  aim varchar(40) default NULL,
	  status varchar(35) NOT NULL default '',
	  location varchar(50) default NULL,
	  bio text,
	  sig text,
	  showemail varchar(15) NOT NULL default '',
	  timeoffset int(5) NOT NULL default '0',
	  icq varchar(30) NOT NULL default '',
	  avatar varchar(90) default NULL,
	  yahoo varchar(40) NOT NULL default '',
	  customstatus varchar(100) NOT NULL default '',
	  theme varchar(30) NOT NULL default '',
	  bday varchar(50) default NULL,
	  langfile varchar(40) NOT NULL default '',
	  tpp smallint(6) NOT NULL default '0',
	  ppp smallint(6) NOT NULL default '0',
	  newsletter char(3) NOT NULL default '',
	  regip varchar(40) NOT NULL default '',
	  timeformat int(5) NOT NULL default '0',
	  msn varchar(40) NOT NULL default '',
	  dateformat varchar(10) NOT NULL default '',
	  ignoreu2u text,
	  lastvisit bigint(30) NOT NULL default '0',
	  PRIMARY KEY  (uid)
	  ) TYPE=MyISAM");

	$query = mysql_query("select * from ".$prefix."_users order by uid");
	$num = mysql_result($query,0);



	echo "<h4>Utilisateurs</h4>";

	$cpt_users=0;
	while ($member = mysql_fetch_array($query)) 
	  {
		$cpt_users++;

	$xmb_username = $member[uname];
	if ( $user_viewemail == "1" ) $xmb_showemail = "yes"; else $xmb_showemail = "no";
	$xmb_avatar = "images/avatar/".$member[user_avatar];
	$xmb_password = $member[pass];
	$xmb_regdate = time();
	$xmb_postnum = "0";
	$xmb_email = $member[email];
	$xmb_site = addslashes($member[url]);
	$xmb_aim = $member[user_aim];
	$xmb_status = "Member";
	$xmb_location = addslashes($member[user_from]);
	$xmb_bio = addslashes($member[user_occ]);
	$xmb_sig = addslashes($member[user_sig]);
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
	$xmb_ignoreu2u = "";
	$xmb_lastvisit = "";

	$result = mysql_query("INSERT INTO $table_members VALUES ('', '$xmb_username', '$xmb_password', '$xmb_regdate', '$xmb_postnum', '$xmb_email', '$xmb_site', '$xmb_aim', '$xmb_status', '$xmb_location', '$xmb_bio', '$xmb_sig', '$xmb_showemail', '$xmb_timeoffset', '$xmb_icq', '$xmb_avatar', '$xmb_yahoo', '$xmb_customstatus', '$xmb_theme', '$xmb_bday', '$xmb_langfile', '$xmb_tpp', '$xmb_ppp', '$xmb_newsletter', '$xmb_regip', '$xmb_timeformat', '$xmb_msn', '$xmb_dateformat', '$xmb_ignoreu2u', '$xmb_lastvisit')");
	
	if (!$result) 
	{
		echo "<br>id: $member[uid] nom:$member[uname] pass: $member[pass] Nok<br><br>";
	}
	else 
	 {
		#echo "id: $member[uid] nom:$member[uname] pass: $member[pass] Ok<br>";
		# tab_phpBB_XMB_id_member[ N° id member phpBB ] -> ] N° id member Forum XMB
		$tab_phpBB_XMB_id_member[ $member[uid] ] = mysql_insert_id();
		$tab_XMB_username_de_uid[ mysql_insert_id() ] = $xmb_username ;
		#echo "tab_XMB_username_de_uid: ".$tab_XMB_username_de_uid[ mysql_insert_id() ]." Ok<br>";
	}

  }
  mysql_free_result( $result);


echo "<br>";

$query = mysql_query("select * from ".$prefix."_authors order by aid");
echo "<h4>Administrateurs</h4>";
while ($member = mysql_fetch_array($query)) 
  {
	$cpt_users++;
	print $member[aid]." ".$member[pwd]."<br>";

	$xmb_username = $member[aid];
	$xmb_showemail = "yes";
	$xmb_avatar = "images/avatar/001.gif";
	$xmb_password = $member[pwd];
	$xmb_regdate = time();
	$xmb_postnum = "0";
	$xmb_email = $member[email];
	$xmb_site = addslashes($member[url]);
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

	$member_update_req = mysql_query("UPDATE $table_members SET password='$xmb_password', email='$xmb_email', status='$xmb_status', showemail='$xmb_showemail' WHERE username='$xmb_username' ") or die(mysql_error());

	$member_insert_req = "INSERT INTO "._PREFIX_TBL."members VALUES ('', '$xmb_username', '$xmb_password', '$xmb_regdate', '$xmb_postnum', '$xmb_email', '$xmb_site', '$xmb_aim', '$xmb_status', '$xmb_location', '$xmb_bio', '$xmb_sig', '$xmb_showemail', '$xmb_timeoffset', '$xmb_icq', '$xmb_avatar', '$xmb_yahoo', '$xmb_customstatus', '$xmb_theme', '$xmb_bday', '$xmb_langfile', '$xmb_tpp', '$xmb_ppp', '$xmb_newsletter', '$xmb_regip', '$xmb_timeformat', '$xmb_msn', '$xmb_dateformat', '$xmb_ignoreu2u', '$xmb_lastvisit')";
	
	

	if (mysql_affected_rows() <1 )
	{
		mysql_query($member_insert_req);
		$tab_XMB_username_de_uid[ mysql_insert_id() ] = $xmb_username ;
	}
	else
	{	
		$query3 = mysql_query("SELECT uid from $table_members where username='$xmb_username' ") or die(mysql_error());
		list( $uid ) = mysql_fetch_row($query3);
		$tab_XMB_username_de_uid[ $uid ] = $xmb_username ;
		mysql_free_result( $query3 );
	}

  }



	echo "<h5>Maj ".$prefix."_ranks</h5>";

	mysql_query("DROP TABLE IF EXISTS $table_ranks");
	mysql_query("CREATE TABLE $table_ranks (
	title varchar(40) NOT NULL,
	posts smallint(6) NOT NULL,
	id smallint(6) NOT NULL auto_increment,
	stars smallint(6) NOT NULL,
	allowavatars varchar(3) NOT NULL,
	avatarrank varchar(90),
	PRIMARY KEY(id)
	)TYPE=MyISAM;") or die(mysql_error());

	$query = mysql_query("select * from ".$prefix."_ranks ORDER BY `rank_min` ASC ");

	$cpt=0; # etoiles
	while ($item = mysql_fetch_array($query)) 
	  {
		$cpt++;
		$src_rank_title = addslashes($item[rank_title]);
		$src_rank_min = $item[rank_min];

		mysql_query("INSERT INTO $table_ranks (title, posts, stars, allowavatars) VALUES ( '$src_rank_title', '$src_rank_min', '$cpt', 'yes')");
		echo "src_rank_title: ".stripslashes($src_rank_title)." : OK<br>";

	  }
	mysql_free_result($query);



	echo "<h5>Maj ".$prefix."_catagories</h5>";

	mysql_query("DROP TABLE IF EXISTS $table_forums");
	mysql_query("CREATE TABLE $table_forums (
	  type varchar(15) NOT NULL default '',
	  fid smallint(6) NOT NULL auto_increment,
	  name varchar(50) NOT NULL default '',
	  status varchar(15) NOT NULL default '',
	  lastpost varchar(30) NOT NULL default '',
	  moderator varchar(100) NOT NULL default '',
	  displayorder smallint(6) NOT NULL default '0',
	  private varchar(30) default NULL,
	  description text,
	  allowhtml char(3) NOT NULL default '',
	  allowsmilies char(3) NOT NULL default '',
	  allowbbcode char(3) NOT NULL default '',
	  guestposting char(3) NOT NULL default '',
	  userlist text NOT NULL,
	  theme varchar(30) NOT NULL default '',
	  posts int(100) NOT NULL default '0',
	  threads int(100) NOT NULL default '0',
	  fup smallint(6) NOT NULL default '0',
	  postperm char(3) NOT NULL default '',
	  allowimgcode char(3) NOT NULL default '',
	  PRIMARY KEY  (fid)
	  )TYPE=MyISAM;") or die(mysql_error());

	$query = mysql_query("select * from ".$prefix."_catagories");

	while ($item = mysql_fetch_array($query)) 
	  {
		$src_cat_title = addslashes($item[cat_title]);
		$src_cat_id = $item[cat_id];

		mysql_query("INSERT INTO $table_forums (type, fid, name, status, displayorder, fup) VALUES ( 'group', NULL,  '$src_cat_title', 'on', '3', '0')");
		
		$tab_phpBBcat_XMBcat[$src_cat_id]=mysql_insert_id(); # tab_phpBB_XMB[ N° forum phpBB ] -> ] Forum XMB

		echo "src_cat_title: ".stripslashes($src_cat_title)." : OK<br>";

	  }
	mysql_free_result($query);



	#############################################################################################
	#																							#
	#	Table des forums dans XMB / forums dans phpBB											#
	#																							#
	#############################################################################################

	echo "<h5>Maj ".$prefix."_forums</h5>";
	
	$query = mysql_query("select * from ".$prefix."_forums");
	while ($item = mysql_fetch_array($query)) 
	  {
		$src_forum_name = addslashes($item[forum_name]);
		$src_forum_desc = addslashes($item[forum_desc]);
		$src_forum_cat_id = $item[cat_id];
		$src_forum_id = $item[forum_id];

		// Détermination du forum père
		$fup = $tab_phpBBcat_XMBcat[$src_forum_cat_id];

		mysql_query("INSERT INTO $table_forums (type, fid, name, status, displayorder, fup, description, allowhtml,  allowsmilies, allowbbcode, guestposting, postperm, allowimgcode) VALUES ( 'forum', NULL, '$src_forum_name', 'on', '1', '$fup', '$src_forum_desc', 'no', 'yes', 'yes', 'no','1','yes')") or die(mysql_error());

		$tab_phpBB_XMB[$src_forum_id]=mysql_insert_id(); # tab_phpBB_XMB[ N° forum phpBB ] -> ] Forum XMB

		echo "cat_id(phpBB): $src_forum_id <br>";
		echo "cat_id(XMB): $tab_phpBB_XMB[$src_forum_id] <br>";
		echo "src_forum_name: ".stripslashes($src_forum_name)."<br>";
		echo "src_forum_desc: ".stripslashes($src_forum_desc)." : OK<br><br>";
	  }
	mysql_free_result($query);

	#############################################################################################
	#																							#
	#	Table des threads dans XMB / topics dans phpBB											#
	#																							#
	#############################################################################################

	echo "<h5>Maj ".$prefix."_threads</h5>";

	mysql_query("DROP TABLE IF EXISTS $table_threads");
	mysql_query("CREATE TABLE $table_threads (
	  tid smallint(6) NOT NULL auto_increment,
	  fid smallint(6) NOT NULL default '0',
	  subject varchar(100) NOT NULL default '',
	  lastpost varchar(30) NOT NULL default '',
	  views int(100) NOT NULL default '0',
	  replies int(100) NOT NULL default '0',
	  author varchar(40) NOT NULL default '',
	  message text NOT NULL,
	  dateline bigint(30) NOT NULL default '0',
	  icon varchar(50) default NULL,
	  usesig varchar(15) NOT NULL default '',
	  closed varchar(15) NOT NULL default '',
	  topped smallint(6) NOT NULL default '0',
	  useip varchar(40) NOT NULL default '',
	  bbcodeoff varchar(15) NOT NULL default '',
	  smileyoff varchar(15) NOT NULL default '',
	  emailnotify varchar(15) NOT NULL default '',
	  PRIMARY KEY  (tid)
	  )TYPE=MyISAM;") or die(mysql_error());

	$query = mysql_query("select * from ".$prefix."_forumtopics");
	$cpt=0;
	while ($item = mysql_fetch_array($query)) 
	  {
		$cpt++;
		$src_topic_title = addslashes($item[topic_title]);
		$src_topic_id = $item[topic_id];
		$src_forum_id = $item[forum_id];
		$fid_xmb = $tab_phpBB_XMB[$src_forum_id];
		$xmb_views = $item[topic_views];

		// récup du nom du posteur
		$query5 = mysql_query("SELECT uname FROM ".$prefix."_users WHERE uid='".$item[topic_poster]."'");
		list($uname)= mysql_fetch_row($query5);
		$xmb_author = $uname; # id du membre qui poste
		mysql_free_result($query5);	
		
		// récup de la date du topic (qui celle du premier post dans ce topic)
		$query5 = mysql_query("SELECT post_time from ".$prefix."_posts WHERE topic_id='".$src_topic_id."' ORDER BY  post_time asc");
		list($post_time)= mysql_fetch_row($query5);

		$tab_date = explode(" ",$post_time); # $tab_date[0] contient 2001-06-04 et $tab[1] contient 17:01
		$tab_jours = explode("-", $tab_date[0]);
		$tab_heure = explode(":", $tab_date[1]);
		$xmb_dateline = mktime ($tab_heure[0],$tab_heure[1],0,$tab_jours[1], $tab_jours[2],$tab_jours[0]);
		#echo "date du topic: $xmb_dateline <br>";
		mysql_free_result($query5);


		$query4 = mysql_query("SELECT post_text FROM ".$prefix."_posts WHERE topic_id='".$src_topic_id."'");
		list($post_text)= mysql_fetch_row($query4);
		mysql_free_result($query4);

		// récup du last post et du poster du lastpost sous format $last_post."|".$topic_poster_lastpost (ds XMB)
		$query1 = mysql_query("SELECT post_time, poster_id from ".$prefix."_posts WHERE topic_id='".$src_topic_id."' ORDER BY  post_time desc");
		list($post_time, $poster_id)= mysql_fetch_row($query1);
		$count = mysql_num_rows($query1) -1;
		
		$tab_date = explode(" ",$post_time); # $tab_date[0] contient 2001-06-04 et $tab[1] contient 17:01
		$tab_jours = explode("-", $tab_date[0]);
		$tab_heure = explode(":", $tab_date[1]);
		$last_post = mktime ($tab_heure[0],$tab_heure[1],0,$tab_jours[1], $tab_jours[2],$tab_jours[0]);

		$query6 = mysql_query("SELECT uname FROM ".$prefix."_users WHERE uid='$poster_id'");
		list($uname)= mysql_fetch_row($query6);
		$topic_poster_lastpost = $uname; # id du membre qui lastposte

		$last_post = $last_post."|".$topic_poster_lastpost;
		#echo "last_post: $last_post : nb: $count : OK<br>";

		mysql_free_result($query6);	
		mysql_free_result($query1);

		$post_text = eregi_replace("<br>","",$post_text);

		// Mise à jour du nombre de post par membre
		mysql_query("UPDATE $table_members SET postnum=postnum+1 WHERE username='$xmb_author'") or die(mysql_error());
		// Mise à jour du nombre de post pour les forums (chiffre en page de garde)
		mysql_query("UPDATE $table_forums SET posts=posts+1 WHERE fid='$fid_xmb'") or die(mysql_error());
		// Mise à jour du nombre de post pour les forums (chiffre en page de garde)
		mysql_query("UPDATE $table_forums SET threads=threads+1 WHERE fid='$fid_xmb'") or die(mysql_error());

		mysql_query("INSERT INTO $table_threads (tid, fid, subject, lastpost, views, replies, author, message, dateline, icon, usesig, closed, topped, useip, bbcodeoff, smileyoff, emailnotify ) VALUES ( NULL, '$fid_xmb', '$src_topic_title', '$last_post', '$xmb_views', '$count', '$xmb_author', '$post_text', '$xmb_dateline', '', '', '', '0','', '', '', 'no' )");

		# tab_phpBB_XMB_id_thread[ N° id topic phpBB ] -> ] N° id thread Forum XMB
		$tab_phpBB_XMB_id_thread[ $item[topic_id] ] = mysql_insert_id();

		#echo "$cpt:&nbsp; src_topic_title: ".stripslashes($src_topic_title)." : OK<br>";

	  }
	  mysql_free_result($query);

	#############################################################################################
	#																							#
	#	Table des posts																			#
	#																							#
	#############################################################################################

	echo "<h4>Posts: $table_posts</h4>";


	mysql_query("DROP TABLE IF EXISTS $table_posts");
	mysql_query("CREATE TABLE nuke_xmb_posts (
	  fid smallint(6) NOT NULL default '0',
	  tid smallint(6) NOT NULL default '0',
	  pid smallint(8) NOT NULL auto_increment,
	  author varchar(40) NOT NULL default '',
	  message text NOT NULL,
	  dateline bigint(30) NOT NULL default '0',
	  icon varchar(50) default NULL,
	  usesig varchar(15) NOT NULL default '',
	  useip varchar(40) NOT NULL default '',
	  bbcodeoff varchar(15) NOT NULL default '',
	  smileyoff varchar(15) NOT NULL default '',
	  emailnotify varchar(15) NOT NULL default '',
	  PRIMARY KEY  (pid)
	  ) TYPE=MyISAM");

	$query = mysql_query("select * from ".$prefix."_posts"); # $prefix_posts -> Table des posts phpBB dans Nuke

$cpt=0;$cpt1=0;
	while ($item = mysql_fetch_array($query)) 
	  {
		$src_forum_id = $item[forum_id];						#phpBB	: forum_id		
		$dest_forum_id = $tab_phpBB_XMB[ $src_forum_id ] ;		# XMB	: fid
		$src_topic_id = $item[topic_id];
		$dest_topic_id = "";
		$tid_xmb = $tab_phpBB_XMB_id_thread[$src_topic_id] ; # Thread id dans xmb auquel ce post appartient
		$xmb_author_id = $tab_phpBB_XMB_id_member[ $item[poster_id] ]; # auteur de ce post (dans xmb)
		#echo "xmb_author_id: ".$xmb_author_id." : OK<br>";
		$xmb_author = $tab_XMB_username_de_uid[ $xmb_author_id ];

		$dest_post_text = addslashes($item[post_text]);

		// récup de la date du topic
		$src_topic_time = $item[post_time];
		$tab_date = explode(" ",$src_topic_time); # $tab_date[0] contient 2001-06-04 et $tab[1] contient 17:01
		$tab_jours = explode("-", $tab_date[0]);
		$tab_heure = explode(":", $tab_date[1]);
		$dest_post_time = mktime ($tab_heure[0],$tab_heure[1],0,$tab_jours[1], $tab_jours[2],$tab_jours[0]);

		$dest_poster_ip= $item[poster_ip];

		$xmb_views = $item[topic_views];
		
		$query1 = mysql_query("SELECT author, message, dateline FROM $table_threads WHERE tid='$tid_xmb'");
		list( $author, $message, $dateline ) = mysql_fetch_row($query1);
		mysql_free_result($query1);

		$dest_post_text = eregi_replace("<br>","",$dest_post_text);

		$cpt1++;

		if ( ($message != $dest_post_text) and ($dateline != $dest_post_time) )
		{
			// Mise à jour du nombre de post par membre
			mysql_query("UPDATE $table_members SET postnum=postnum+1 WHERE uid='$xmb_author_id'") or die(mysql_error());
			// Mise à jour du nombre de post pour les forums (chiffre en page de garde)
			mysql_query("UPDATE $table_forums SET posts=posts+1 WHERE fid='$dest_forum_id'") or die(mysql_error());
$cpt++;
			mysql_query("INSERT INTO $table_posts (fid, tid, author, message, dateline, icon, usesig, useip, bbcodeoff, smileyoff, emailnotify ) VALUES ('$dest_forum_id', '$tid_xmb', '$xmb_author', '$dest_post_text', '$dest_post_time', '', '', '$dest_poster_ip', '','', 'yes' )") or die(mysql_error());
		}
	  }

echo $cpt." ".$cpt1;

exit();
# tab_phpBB_XMB_idmember[ N° id member phpBB ] -> ] N° id member Forum XMB
		# tab_phpBB_XMB_id_thread[ N° id topic phpBB ] -> ] N° id thread Forum XMB
		

  }


function creer_base() 
  {
	  $tables = array('banned','forums', 'members', 'posts', 'ranks', 'smilies', 'themes', 'threads', 'u2u', 'whosonline', 'words');
foreach($tables as $name) {
${'table_'.$name} = _PREFIX_TBL.$name;
}

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

mysql_query("CREATE TABLE $table_u2u (
u2uid smallint(6) NOT NULL auto_increment,
msgto varchar(40) NOT NULL,
msgfrom varchar(40) NOT NULL,
dateline BIGINT(30) NOT NULL,
subject varchar(75) NOT NULL,
message text NOT NULL,
folder varchar(40) NOT NULL,
block text,
PRIMARY KEY(u2uid)
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
	global $prefix;

	  $tables = array('banned','forums', 'members', 'posts', 'ranks', 'smilies', 'themes', 'threads', 'u2u', 'whosonline', 'words');
foreach($tables as $name) {
${'table_'.$name} = _PREFIX_TBL.$name;
}

	mysql_query("DROP TABLE IF EXISTS $table_members");
	mysql_query("CREATE TABLE nuke_xmb_members (
	  uid smallint(6) NOT NULL auto_increment,
	  username varchar(25) NOT NULL default '',
	  password varchar(18) NOT NULL default '',
	  regdate bigint(30) NOT NULL default '0',
	  postnum smallint(6) NOT NULL default '0',
	  email varchar(60) default NULL,
	  site varchar(75) default NULL,
	  aim varchar(40) default NULL,
	  status varchar(35) NOT NULL default '',
	  location varchar(50) default NULL,
	  bio text,
	  sig text,
	  showemail varchar(15) NOT NULL default '',
	  timeoffset int(5) NOT NULL default '0',
	  icq varchar(30) NOT NULL default '',
	  avatar varchar(90) default NULL,
	  yahoo varchar(40) NOT NULL default '',
	  customstatus varchar(100) NOT NULL default '',
	  theme varchar(30) NOT NULL default '',
	  bday varchar(50) default NULL,
	  langfile varchar(40) NOT NULL default '',
	  tpp smallint(6) NOT NULL default '0',
	  ppp smallint(6) NOT NULL default '0',
	  newsletter char(3) NOT NULL default '',
	  regip varchar(40) NOT NULL default '',
	  timeformat int(5) NOT NULL default '0',
	  msn varchar(40) NOT NULL default '',
	  dateformat varchar(10) NOT NULL default '',
	  ignoreu2u text,
	  lastvisit bigint(30) NOT NULL default '0',
	  PRIMARY KEY  (uid)
	  ) TYPE=MyISAM");
#return("1");

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
	$xmb_ignoreu2u = "";
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

  }

function suppr_base() 
  {
	global $prefix;

	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."banned") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."forums") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."members") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."posts") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."ranks") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."smilies") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."themes") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."threads") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."u2u") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."words") or die(mysql_error());
	mysql_query("DROP TABLE IF EXISTS "._PREFIX_TBL."whosonline") or die(mysql_error());
  }

  function maj_base_alphaversbeta() 
  {
	global $databasename;

mysql_query("ALTER TABLE `"._PREFIX_TBL."banned` CHANGE `dateline` `dateline` BIGINT (30) DEFAULT '0' not null") or die(mysql_error());
mysql_query("ALTER TABLE `"._PREFIX_TBL."members` CHANGE `regdate` `regdate` BIGINT (30) DEFAULT '0' not null ") or die(mysql_error());
mysql_query("ALTER TABLE `"._PREFIX_TBL."members` CHANGE `lastvisit` `lastvisit` BIGINT (30) DEFAULT '0' not null ") or die(mysql_error());
mysql_query("ALTER TABLE `"._PREFIX_TBL."posts` CHANGE `dateline` `dateline` BIGINT (30) DEFAULT '0' not null ") or die(mysql_error());
mysql_query("ALTER TABLE `"._PREFIX_TBL."threads`  CHANGE `dateline` `dateline` BIGINT (30) DEFAULT '0' not null") or die(mysql_error());
mysql_query("ALTER TABLE `"._PREFIX_TBL."u2u` CHANGE `dateline` `dateline` BIGINT (30) DEFAULT '0' not null ") or die(mysql_error());
mysql_query("ALTER TABLE `"._PREFIX_TBL."whosonline` CHANGE `time` `time` BIGINT (40) DEFAULT '0' not null ") or die(mysql_error());


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
		define("_PREFIX_TBL",$tableprefix."_");
		define("_XMBDEFLANG",$xmbdeflang);
		define("_XMBDEFTHEME",$xmbdeftheme);
		
		global $databasename;

		echo "<BR><div class=\"soustitre\">"._STEP3." : <B> "._CREATE_TABLES." : $sel_db</B></div>";
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
			maj_base_alphaversbeta();
			#maj_base();
			#maj_users();
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
		
		$result_ok1 = saveconf();
		if (! $result_ok1) echo "nok fwrite config.php";

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

		$fichconfigperms = strfileperms("modules/$ModName/settings.php");
		if (! preg_match("/-rw[-|x]rw[-|x]rw[-|x]/i", $fichconfigperms, $parts))
		  {
			echo "<br><br><b>\"settings.php\":</b> "._FILE_NOT_WRITEN;
			
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


		echo "<BR><div class=\"soustitre\">"._STEP1." : <B>"._INSTALL_PARAM."</B></FONT>";
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
			."<option value=\"gray\">Gray</option>"
			."<option value=\"blue\">Blue</option>"
			."<option value=\"hacknuke\">Hacknuke</option>"
			."<option value=\"coursapied\">Coursapied</option>"
			."</select>"
			."</fieldset><P>";
		
		echo "<INPUT TYPE='hidden' NAME='instlang' VALUE=\"$instlang\"><P>";
		echo "<DIV align='right'><INPUT TYPE='submit' CLASS='fondl' NAME='Valider' VALUE='"._NEXT2." >>'>";


		echo "</FORM>";

		fin_html();
		
	  }
	else 
	  {
		  debut_html();
		  #maj_users();
		  update_phpbb();
		  exit;
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
				<tr><td width="150"><? echo _CH_LANGUE ?></td><td><? echo _CH_OPERATION ?></td></tr>
				<tr><td>
					<select name="instlang" ONCHANGE="Refresh(this);" LANGUAGE="JavaScript">
					<option value="<? echo _MODINST_URL_PREFIX."install"; ?>"><? echo _CH_LANGUE ?></option>
					<option value="<? echo _MODINST_URL_PREFIX."install&instlang=fr"; ?>" <? if ($instlang=="fr") {echo" selected";} ?> ><? echo _CH_FRENCH ?></option>
					<option value="<? echo _MODINST_URL_PREFIX."install&instlang=en"; ?>" <? if ($instlang=="en") {echo" selected";} ?> ><? echo _CH_ENGLISH ?></option>
					</select>
				</td>
				<td width="150">
					<select name="Navigate1" ONCHANGE="Refresh(this);" LANGUAGE="JavaScript">
					<option value="<? echo _MODINST_URL_PREFIX."install"; ?>"><? echo _CH_OPERATION ?></option>
					<option value="<? echo _MODINST_URL_PREFIX."install&etape1=oui&instlang=$instlang"; ?>"><? echo _CH_INSTALL ?></option>
					<option value="<? echo _MODINST_URL_PREFIX."install&etape1=oui&act=suppr&instlang=$instlang"; ?>"><? echo _CH_UNINSTALL ?></option>
					<option value="<? echo _MODINST_URL_PREFIX."install&etape1=oui&act=upgrade&instlang=$instlang"; ?>"><? echo _CH_UPGRADE ?></option>
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
