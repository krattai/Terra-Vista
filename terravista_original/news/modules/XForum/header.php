<?
# modif 25/09/01 18:30
# modif suppr mysql_resul t 20011001 13h11
# modif  20011001 16h20 ligne 414 suppr u 2 u
# modif index<-synchro indexnav<-passynchro 20011003 11h29
# modif suppr jump 20011003 15h11
# modif theme default 20011005 23h30
# modif add dformatorigconf bug d'affichage du format date 20011006 16h05
# modif req sql suppr (*) 20011106 23h30

$mtime1 = explode(" ", microtime());
$starttime = $mtime1[1] + $mtime1[0];
$currtime1 = time() + (86400*365);
$currtime2 = time() + 600;
setcookie("lastvisita", time(), $currtime1);
if($lastvisitb)		{ $thetime = $lastvisitb; } 
else				{ $thetime = $lastvisita; }
setcookie("lastvisitb", $thetime, $currtime2);
$lastvisit = $thetime;
$lastvisit2 = $lastvisit;

require "modules/XForum/functions.php";
require "modules/XForum/settings.php";

$version = "1.0B3b";

if ($affheader == "off") { ob_start(); include("header.php"); ob_end_clean(); }
else {include("header.php");}

echo "<br>";

define("_BASEMOD", "modules.php?op=modload&name=XForum&file=");

if (!isset($mainfile)) { include("mainfile.php"); }
$index=0;

$SYSADMIN = is_admin($admin);	# User is Admin
$USER = is_user($user);

if ($SYSADMIN) 
{
  #echo "admin $thisuser connecté";
  if(!is_array($admin)) 
  {
	$XFadmin = base64_decode($admin);
	$XFadmin = explode(":", $XFadmin);
	$XFthisuser = "$XFadmin[0]";
	$XFthispw = "$XFadmin[1]";
  } 
  else 
  {
	$XFthisuser = "$admin[0]";
    $XFthispw = "$admin[1]";
  }
}
elseif ($USER) 
  {
	#echo "Nuke user: $thisuser connecté";
	$XFuser_cook = cookiedecode($user);
	$XFthisuser = $XFuser_cook[1];
	$XFthispw = $XFuser_cook[2];
  }
else
  {
	$XFthisuser="Anonymous";
    $XFthispw="Anonymous";
	#echo "ni admin, ni user connecté";
  } 

$thisuser = $XFthisuser;
$thispw=$XFthispw;

$bblang = $langfile;
if($thisuser && $thisuser != '') 
{
  $query = mysql_query("SELECT langfile, timeoffset, status, theme, tpp, ppp, timeformat, dateformat, password FROM $table_members WHERE username='$thisuser'") or die(mysql_error());
  $this = mysql_fetch_array($query);
  if($this[langfile] != "")		{ $langfile = $this[langfile]; }
  $timeoffset = $this[timeoffset];
  $status = $this[status];
  $XFthemeuser = $this[theme];
  $tpp = $this[tpp];
  $ppp = $this[ppp];
  $memtime = $this[timeformat];
  $memdate = $this[dateformat];

  if($this[password] == $thispw)	{ $thisuser = $thisuser; } 
  else								{ $thisuser = ""; }
}

require "modules/XForum/lang/$langfile.lang.php";

if(!$thisuser || !$thispw) 
{
  $thisuser = "";
  $status = "";
}

if($thisuser && $thisuser != '') 
{ 
  $time = time(); 
  mysql_query("UPDATE $table_members SET lastvisit='$time' WHERE username='$thisuser'") or die(mysql_error()); 
}


if($regstatus == "on" && $noreg != "on") 
{ 
  if($coppa == "on") 
  { 
	$reglink = "<a href=\""._BASEMOD."member&action=coppa\"><span class=\"navtd\">"._TEXTREGISTER."</span></a>"; 
  } 
  else 
  { 
	$reglink = "<a href=\"user.php\"><span class=\"navtd\">"._TEXTREGISTER."</span></a>"; 
  } 

  $proreg = $reglink; 
} 


if($thisuser && $thisuser != '') 
{ 
  $notify = _LOGGEDIN." $thisuser"; 
  #$ loginout = "<a href=\""._BASEMOD."misc&action=logout\"><span class=\"navtd\">"._TEXTLOGOUT."</span></a>"; 
  $proreg = "<a href=\""._BASEMOD."member&action=editpro\"><span class=\"navtd\">"._TEXTPROFILE."</span></a>"; 
  $onlineuser = $thisuser; 
} 
else 
{ 
  $notify = _NOTLOGGEDIN; 
  #$ loginout = "<a href=\""._BASEMOD."misc&action=login\"><span class=\"navtd\">"._TEXTLOGIN."</span></a>"; 
  $onlineuser = "xguest123"; 
} 

if($memtime == "") 
{
  if($timeformat == "24")	{ $timecode = "H:i"; } 
  else						{ $timecode = "h:i A"; }
} 
else 
{
  if($memtime == "24")		{ $timecode = "H:i"; } 
  else						{ $timecode = "h:i A"; }
}

$dformatorigconf = $dateformat; # rajouté : bug affichage des pref de l'admin au lieu de celui de settings.php
if($memdate == "")			{ $dateformat = $dateformat;	} 
else						{ $dateformat = $memdate;		}
$dformatorig = $dateformat;

#DR###:.
/*
$dateformat = e regi_replace("mm", "n", $dateformat);
$dateformat = e regi_replace("dd", "j", $dateformat);
$dateformat = e regi_replace("yyyy", "Y", $dateformat);
$dateformat = e regi_replace("yy", "y", $dateformat);
*/
$dateformat = preg_replace("/mm/i", "n", $dateformat);
$dateformat = preg_replace("/dd/i", "j", $dateformat);
$dateformat = preg_replace("/yyyy/i", "Y", $dateformat);
$dateformat = preg_replace("/yy/i", "y", $dateformat);
#FR###:.


if		(getenv(HTTP_CLIENT_IP))		{ $onlineip = getenv(HTTP_CLIENT_IP); } 
elseif	(getenv(HTTP_X_FORWARDED_FOR))	{ $onlineip = getenv(HTTP_X_FORWARDED_FOR); } 
else									{ $onlineip = getenv(REMOTE_ADDR); } 
$onlinetime = time();

if($tid != "")
{
$sql = mysql_query("SELECT fid,subject FROM $table_threads WHERE tid='$tid'");  
$locate2 = mysql_fetch_array($sql);
$fid = $locate2[fid];
}

if($fid != "")
{
	$query = mysql_query("SELECT name, private, theme FROM $table_forums WHERE fid='$fid'") or die(mysql_error());
	$locate = mysql_fetch_array($query);
}

if($fid != "" && $tid == "" && $locate[private] != "staff")
{
	$location = "<a href=\""._BASEMOD."forumdisplay&fid=$fid\">$locate[name]</a>";
} 
elseif($fid != "" && $tid != "" && $locate[private] != "staff")
{
	$location = "<a href=\""._BASEMOD."forumdisplay&fid=$fid\">$locate[name]</a>: <a href=\""._BASEMOD."viewthread&tid=$tid\">$locate2[subject]</a>";
} 
elseif($locate[private] == "staff") { $location = _TEXTPRIV; } 
elseif($action == "list")	{ $location = "<a href=\""._BASEMOD."member&action=list\">"._TEXTMEMBERLIST."</a>"; } 
elseif($action == "search") { $location = "<a href=\""._BASEMOD."misc&action=search\">"._TEXTSEARCH."</a>"; } 
elseif($action == "faq")	{ $location = _TEXTFAQ; } 
elseif($action == "online")	{ define("_whosonline", "".addslashes(_whosonline).""); $location = _WHOSONLINE; } elseif($action == "stats")	{ $location = "<a href=\""._BASEMOD."misc&action=stats\">"._TEXTSTATS."</a>"; } 
else						{ $location = "<a href=\""._BASEMOD."indexnav\">"._TEXTINDEX."</a>"; }

//**************************************************//
// BETA 2
//***********lignes a mettre en IF     *************//
if($whosonlinestatus == "on") 
{
  mysql_query("DELETE FROM $table_whosonline WHERE ip='$onlineip' AND username !='$thisuser'");

  if(!$thisuser) { mysql_query("DELETE FROM $table_whosonline WHERE ip='$onlineip'"); } elseif($thisuser && !$anonlog) { mysql_query("DELETE FROM $table_whosonline WHERE username='$thisuser'"); }

  mysql_query("INSERT INTO $table_whosonline VALUES('$onlineuser', '$onlineip', '$onlinetime', '$location')");
}

$XFthemedef = $XFtheme;
if($locate[2] != "" && $XFthemeuser == "$XFtheme"){
$XFtheme = $locate[2];
} elseif($XFthemeuser != "") {
$XFtheme = $XFthemeuser;
} else {
$XFtheme = $XFtheme;
}

if ( $XFtheme != "default" )
{
  $query = mysql_query("SELECT * FROM $table_themes WHERE name='$XFtheme'");
  foreach(mysql_fetch_array($query) as $key => $val) 
  {
	if($key != "name") { $$key = $val; }
  }
}
else
{
  global $textcolor1, $bgcolor1, $textcolor2, $bgcolor2, $textcolor3, $bgcolor3, $textcolor4, $bgcolor4;

  if ( preg_match("/phpnuke/", $nukesystem))
  {
	$bgcolor = $bgcolor1; 
	$altbg1 = $bgcolor1; 
	$altbg2 = $bgcolor1; 
	$header = $bgcolor1; 
	$headertext = $textcolor1; 
	$top = $bgcolor1; 
	$bordercolor = $textcolor1; 
	$tabletext = $textcolor1; 
	$catcolor = $bgcolor1;
  }
  else
  {
	$bgcolor = $bgcolor1;
	$altbg1 = $bgcolor1;
	$altbg2 = $bgcolor1;
	$header = $bgcolor1;
	$headertext = $textcolor1;
	$top = $bgcolor1;
	$bordercolor = $textcolor1;
	$tabletext = $textcolor1;
	$catcolor = $bgcolor1;
  }

  $link = $textcolor1;
  $text = $textcolor1;
  $borderwidth = "1";
  $tablewidth = "97%";
  $tablespace = "4";
  $font = "Arial";
  $fontsize = "12px";
  $altfont = "Verdana";
  $altfontsize = "10px";
  $replyimg = "";
  $newtopicimg = "";
  $boardimg = "";
  $postcol = "2col";
}

$font1 = $fontsize-1;
$font2 = $fontsize+1;
$font3 = $fontsize+3;

if($status == "Administrator") { $cplink = "| <a href=\""._BASEMOD."cp\"><span class=\"navtd\">"._TEXTCP."</span></a>"; }

if($lastvisit) 
{
  $lastdate = gmdate("$dateformat",$lastvisita + ($timeoffset * 3600));
  $lasttime = gmdate("$timecode",$lastvisita + ($timeoffset * 3600));
  $lastvisittext = _LASTACTIVE." $lastdate "._TEXTAT." $lasttime";
}

// potentiellement à cacher: je le supprime: ça sert à rien
/*
$jump = "<select name=\"fid\">";

$queryfor = mysql_query("SELECT * FROM $table_forums WHERE fup='' AND type='forum' ORDER BY displayorder") or die(mysql_error());
while($forum = mysql_fetch_array($queryfor)) {
$authorization = privfcheck($hideprivate, $status, $forum[private], $thisuser, $forum[userlist]);
if($authorization == "true") { 
$jump .= "<option value=\"$forum[fid]\"> &nbsp; &gt; $forum[name]</option>";

$querysub = mysql_query("SELECT * FROM $table_forums WHERE fup='$forum[fid]' AND type='sub' ORDER BY displayorder") or die(mysql_error());
while($sub = mysql_fetch_array($querysub)) {
$authorization = privfcheck($hideprivate, $status, $sub[private], $thisuser, $sub[userlist]);
if($authorization == "true") { 
$jump .= "<option value=\"$sub[fid]\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &gt; $sub[name]</option>";
}
}
}
$jump .= "<option value=\"\"> </option>";
}

$querygrp = mysql_query("SELECT name, fid FROM $table_forums WHERE type='group' ORDER BY displayorder") or die(mysql_error());
while($group = mysql_fetch_array($querygrp)) {
$jump .= "<option value=\"\">$group[name]</option>";
$jump .= "<option value=\"\">--------------------</option>";

$queryfor = mysql_query("SELECT * FROM $table_forums WHERE fup='$group[fid]' AND type='forum' ORDER BY displayorder") or die(mysql_error());
while($forum = mysql_fetch_array($queryfor)) {
$authorization = privfcheck($hideprivate, $status, $forum[private], $thisuser, $forum[userlist]);
if($authorization == "true") { 
$jump .= "<option value=\"$forum[fid]\"> &nbsp; &gt; $forum[name]</option>";

$querysub = mysql_query("SELECT * FROM $table_forums WHERE fup='$forum[fid]' AND type='sub' ORDER BY displayorder") or die(mysql_error());
while($sub = mysql_fetch_array($querysub)) {
$authorization = privfcheck($hideprivate, $status, $sub[private], $thisuser, $sub[userlist]);
if($authorization == "true") { 
$jump .= "<option value=\"$sub[fid]\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &gt; $sub[name]</option>";
}
}
}
}
$jump .= "<option value=\"\"> </option>";
}
$jump .="</select>&nbsp;<input type=\"submit\" value=\"_textgo]\">";
*/
//**** <--- FIN $jump -----

#DM###:. --> Suppr ->
/*
$query = mysql_query("SELECT * FROM $table_u 2 u WHERE msgto='$thisuser' ORDER BY dateline DESC LIMIT 1") or die(mysql_error()); 
$u 2 u = mysql_fetch_array($query); 
if($lastvisita < $u 2 u[dateline]) { 
$u 2 upopup = "onLoad=\"Popup(\'misc2.php?action1=u 2 u\', \'Window\', 550, 450);\""; 
} else { 
$u 2 upopup = ""; 
}
*/
#FM###:.

//** Gestion du Forum si le forum est fermé et que l'user n'est pas admin
if($bbstatus == "off" && $status != "Administrator") 
{
  $html = template("header.html");
  eval("echo stripslashes(\"$html\");");
  echo "$action";
?>
  <table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
  <tr><td class="12px"><?=_TEXTBBOFFNOTE?> <?=$bboffreason?></td></tr></table>
<?
  exit;
}


$ips = explode(".", $onlineip); 
$query = mysql_query("SELECT id FROM $table_banned WHERE (ip1='$ips[0]' OR ip1='-1') AND (ip2='$ips[1]' OR ip2='-1') AND (ip3='$ips[2]' OR ip3='-1') AND (ip4='$ips[3]' OR ip4='-1')") or die(mysql_error()); 
$result = mysql_fetch_array($query); 
if($status == "Banned" || ($result && (!$status || $status=="Member"))) { 
echo _BANNEDMESSAGE; 
exit; 
}


if($regviewonly == "on") # A revoir ici
{
  if($onlineuser == "xguest123" && $action != "reg" && $action != "login" && $action != "lostpw") 
  {
	$html = template("header.html");
	eval("echo stripslashes(\"$html\");");
	echo "$action";
?>
	<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
	<tr><td class="12px">
	<?=_REGGEDONLY?> <a href="user.php"><?=_TEXTREGISTER?></a>
	</td></tr></table>
<?
	exit;
  }
}

if($whosonlinestatus == "on") 
{
  #DM###:.
/*
$query = mysql_query("SELECT COUNT(*) FROM $table_whosonline WHERE username!='onlinerecord'") or die(mysql_error()); 
$count = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
  $query = mysql_query("SELECT COUNT(time) as nbsites FROM $table_whosonline WHERE username!='onlinerecord'") or die(mysql_error()); 
$row = mysql_fetch_array($query);
$count = $row[nbsites];
#FM###:.

  $query = mysql_query("SELECT * FROM $table_whosonline WHERE username='onlinerecord'") or die(mysql_error()); 
  $onlinerecord = mysql_fetch_array($query); 
  if ($count > $onlinerecord[ip]) 
  { mysql_query("UPDATE $table_whosonline SET ip='$count' WHERE username='onlinerecord'") or die(mysql_error()); }
}


$bbrulestxt = stripslashes(stripslashes($bbrulestxt));
$bboffreason = stripslashes(stripslashes($bboffreason));

if($gzipcompress == "on")		{ ob_start("ob_gzhandler"); }

if($searchstatus == "on")		{ $searchlink = "| <a href=\""._BASEMOD."misc&action=search\"><span class=\"navtd\">"._TEXTSEARCH."</span></a>"; } 

if($faqstatus == "on")			{ $faqlink = "| <a href=\""._BASEMOD."misc&action=faq\"><span class=\"navtd\">"._TEXTFAQ."</span></a>"; } 

if($memliststatus == "on")		{ $memlistlink = "| <a href=\""._BASEMOD."misc&action=list\"><span class=\"navtd\">"._TEXTMEMBERLIST."</span></a>"; } 

if($boardimg != "")				{ $logo = "<tr><td><a href=\""._BASEMOD."indexnav\"><img src=\"modules/XForum/$boardimg\" alt=\"Board logo\" border=\"0\" /></a></td><td> </td></tr>"; }

if($statspage == "on")			{ $statslink = "| <a href=\""._BASEMOD."misc&action=stats\"><span class=\"navtd\">"._TEXTSTATS."</span></a>"; }

?>