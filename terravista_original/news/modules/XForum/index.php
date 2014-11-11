<?
# modif suppr mysql_resul t 20011001 13h16
# modif index<-synchro indexnav<-passynchro 20011003 11h29
# modif strtemp 20011107 00h44
# modif L39-> suppr select * 20011107 01h07

require "modules/XForum/headersynchro.php"; 

if(!$gid) { $navigation = _TEXTINDEX; }
else 
{
  $query = mysql_query("SELECT name FROM $table_forums WHERE fid='$gid' AND type='group'") or die(mysql_error());
  $cat = mysql_fetch_array($query);
  $navigation ="<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; $cat[name]";
}

$html = template("header.html");
eval("echo stripslashes(\"$html\");");
?>

<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center"> 
<tr><td bgcolor="<?=$bordercolor?>"> 

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"> 
<tr>
<td width="4%" class="header">&nbsp;</td> 
<td width="58%" class="header"><?=_TEXTFORUM?></td> 
<td width="6%" class="header"><?=_TEXTTOPICS?></td>
<td width="6%" class="header"><?=_TEXTPOSTS?></td> 
<td width="19%" class="header"><?=_TEXTLASTPOST?></td> 
</tr>
<?

if(!$gid) 
{
  $query = mysql_query("SELECT fid, lastpost, moderator, private, posts, threads, name, description, userlist FROM $table_forums WHERE type='forum' AND status='on' AND fup='' ORDER BY displayorder") or die(mysql_error());

  // Boucle d'affichage des lignes de tableau du forum
  $strtemp = "";
  while($forum = mysql_fetch_array($query)) 
  {
	if($announcestatus == "on" && !$gid) 
	{
		$strtemp .= "<tr><td colspan=\"6\" class=\"category\"><a href=\"#\"><b>"._TEXTTOPLEVEL."</b></a></td></tr>";
	}

	$sql1 = mysql_query("SELECT IF(LENGTH(LEFT(lastpost, INSTR(lastpost, '|')-1))<10, CONCAT('0',lastpost), lastpost) as  lastpost, tid FROM $table_threads where fid='".$forum[fid]."' ORDER BY lastpost DESC limit 0,1");
	$res = mysql_fetch_array($sql1);

	$strtemp .= Ligneforum($forum[lastpost], $timeoffset, $forum[moderator], $lastvisit2, $hideprivate, $status, $forum[private], $forum[posts], $forum[threads], $altbg1, $altbg2, $forum[name], $forum[fid], $forum[description], $timecode, $dateformat, $thisuser, $forum[userlist], $res[tid]);
  }

  echo $strtemp;
  // Fin de cette boucle


  #DM###:. remplacement de "*" par "fid,name" dans les 2 requêtes suivantes
  $queryg = mysql_query("SELECT fid,name FROM $table_forums WHERE type='group' AND status='on' ORDER BY displayorder") or die(mysql_error());
}

else 
{
  $queryg = mysql_query("SELECT fid,name FROM $table_forums WHERE type='group' AND fid='$gid' AND status='on' ORDER BY displayorder") or die(mysql_error());
}

while($group = mysql_fetch_array($queryg)) 
{
  ?>
	<tr> 
	<td colspan="6" class="category"><a href="modules.php?op=modload&name=XForum&file=indexnav&gid=<?=$group[fid]?>"><b><?=$group[name]?></b></a></td> 
	</tr> 
  <?

  if($catsonly != "on" || $gid) 
  {
	$query = mysql_query("SELECT userlist, description, fid, lastpost, moderator, private, posts, threads, name FROM $table_forums WHERE type='forum' AND status='on' AND fup='$group[fid]' ORDER BY displayorder") or die(mysql_error());
	while($forum = mysql_fetch_array($query)) 
	{
	
      // En attendant une modif de la table threads car ya bug sur sous forum
	  $sql1 = mysql_query("SELECT IF(LENGTH(LEFT(lastpost, INSTR(lastpost, '|')-1))<10, CONCAT('0',lastpost), lastpost) as  lastpost, tid, dateline FROM $table_threads where fid='".$forum[fid]."' ORDER BY lastpost DESC limit 0,1");
	  $res = mysql_fetch_array($sql1);
	  $sql2 = mysql_query("SELECT tid,dateline FROM $table_posts where dateline='".$res[lastpost]."'");
	  $res2 = mysql_fetch_array($sql1);
	  if ($res[dateline]>$res2[dateline]) { $res3=$res[tid]; } else { $res3=$res2[tid]; }

	  echo Ligneforum($forum[lastpost], $timeoffset, $forum[moderator], $lastvisit2, $hideprivate, $status, $forum[private], $forum[posts], $forum[threads], $altbg1, $altbg2, $forum[name], $forum[fid], $forum[description], $timecode, $dateformat, $thisuser, $forum[userlist], $res3);
	}
  }

}

$query = mysql_query("SELECT username FROM $table_members ORDER BY regdate DESC") or die(mysql_error());
$lastmem = mysql_fetch_array($query);
$lastmember = $lastmem[username];
$members = mysql_num_rows($query);

#DM###:.
/*
$query = mysql_query("SELECT COUNT(*) FROM $table_threads") or die(mysql_error());
$threads = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par --> suppr de "*" par tid
$query = mysql_query("SELECT COUNT(tid) as nbsites FROM $table_threads") or die(mysql_error());
$row = mysql_fetch_array($query);
$threads = $row[nbsites];
#FM###:.

#DM###:.
/*
$query = mysql_query("SELECT COUNT(*) FROM $table_posts") or die(mysql_error());
$posts = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par --> suppr de "*" par pid
$query = mysql_query("SELECT COUNT(pid) as nbsites FROM $table_posts") or die(mysql_error());
$row = mysql_fetch_array($query);
$posts = $row[nbsites];
#FM###:.


$posts = $threads + $posts;
$memhtml = "<a href=\"modules.php?op=modload&name=XForum&file=member&action=viewpro&member=".rawurlencode($lastmember)."\"><b>$lastmember</b></a>.";
eval(_EVALINDEXSTATS);

if($members == "0") { $memhtml = "<b>"._TEXTNOONE."</b>"; }

if($indexstats == "on") { $stats = _INDEXSTATS."<br> "._STATS4." $memhtml"; }
$stats .= "<br><br><img src=\"modules/XForum/images/red_folder.gif\"> = "._NEWPOSTS."<br><img src=\"modules/XForum/images/folder.gif\"> = "._NONEWPOSTS;


//********************************************************************************//
// Affichage de qui est en ligne whosonline
//********************************************************************************//

if(!$gid) 
{
  if($whosonlinestatus == "on") 
  { 
	$time = time(); 
	$newtime = $time - 450;

	mysql_query("DELETE FROM $table_whosonline WHERE time<'$newtime' AND username != 'onlinerecord'"); 
	#DM###:.
	/*
	$query = mysql_query("SELECT COUNT(*) FROM $table_whosonline WHERE username='xguest123'") or 	die(mysql_error()); 
	$guestcount = mysql_resul t($query, 0);
	*/
	#DR###:. Remplacé par -->
	$query = mysql_query("SELECT COUNT(*) as nbsites FROM $table_whosonline WHERE 	username='xguest123'") or die(mysql_error()); 
	$row = mysql_fetch_array($query);
	$guestcount = $row[nbsites];
	#FM###:.

	#DM###:.
	/*
	$query = mysql_query("SELECT COUNT(*) FROM $table_whosonline WHERE username != 'xguest123' 	AND username != 'onlinerecord'") or die(mysql_error()); 
	$membercount = mysql_resul t($query, 0);
	*/
	#DR###:. Remplacé par --> on garde "*" car whosonline est vide
	$query = mysql_query("SELECT COUNT(*) as nbsites FROM $table_whosonline WHERE username != 'xguest123' AND username != 'onlinerecord'") or die(mysql_error()); 
	$row = mysql_fetch_array($query);
	$membercount = $row[nbsites];
	#FM###:.

	$query = mysql_query("SELECT * FROM $table_whosonline WHERE username = 'onlinerecord'") or die(mysql_error()); 
	$record = mysql_fetch_array($query);

	eval(_EVALWHOSONLINE);
	$memonmsg = "<span class=\"11px\">"._WHOSONLINE1." $guestcount "._WHOSONLINE2." $membercount "._WHOSONLINE3." "._WHOSONLINE4."</span>"; 

	$queryonline = mysql_query("SELECT * FROM $table_whosonline WHERE username!='xguest123' AND username!='onlinerecord'") or die(mysql_error()); 

	$memtally = ""; 
	$num = 1; 
	while ($online = mysql_fetch_array($queryonline)) 
	{ 
	  if($num < $membercount) { $memtally .= "<a href=\"modules.php?op=modload&name=XForum&file=member&action=viewpro&member=".rawurlencode($online[username])."\">$online[username]</a>, "; } 
	  else { $memtally .= "<a href=\"modules.php?op=modload&name=XForum&file=member&action=viewpro&member=".rawurlencode($online[username])."\">$online[username]</a>";  } 
	  $num++; 
	}

	if($memtally == "") { $memtally = "&nbsp;"; }
?>

	<tr>
	<td colspan="6" class="category"><b><font color="<?=$link?>"><a href="modules.php?op=modload&name=XForum&file=misc&action=online"><?=_WHOSONLINE?></a> - <?=$memonmsg?></font></b></td>
	</tr>
	<tr>
	<td bgcolor="<?=$altbg1?>" align="center" class="tablerow"><img src="modules/XForum/images/online.gif"></td>
	<td bgcolor="<?=$altbg2?>" colspan="5" class="12px"><?=$memtally?></td>
	</tr>
<?
} 
}

//***************************************************************************//
// Affichage du temps d'execution du script									 //
//***************************************************************************//
if($showtotaltime != "off") 
{ 
  $mtime2 = explode(" ", microtime());
  $endtime = $mtime2[1] + $mtime2[0];

  $totaltime = ($endtime - $starttime); 
  $totaltime = number_format($totaltime, 7); 
}

//***************************************************************************//
// Templates et fin de page													 //
//***************************************************************************//

#DM###:.
echo "</table></td></tr></table>";
#FM###:.
$html = template("footer.html");

eval("echo stripslashes(\"$html\");");

#DM###:.
if ($afffooter == "off") { ob_start(); include("footer.php"); ob_end_clean(); }
else {include("footer.php");}
#FM###:.

?>
