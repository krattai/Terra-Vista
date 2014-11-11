<?
# modif 20010925 19h05
# modif suppr mysql_resul t 20011001 13h11
# bug remplacement !eregi 20011108 puis 20011122

require "modules/XForum/header.php";

$query = mysql_query("SELECT moderator, name, private, fid, userlist, threads, type, fup FROM $table_forums WHERE fid='$fid'") or die(mysql_error());
$forum = mysql_fetch_array($query);

if($forum[type] != "forum" && $forum[type] != "sub") { 
$notexist = _TEXTNOFORUM; 
}

if($catsonly == "on" && $forum[type] == "sub") {
$query = mysql_query("SELECT fup FROM $table_forums WHERE fid='$forum[fup]'") or die(mysql_error());
$forum1 = mysql_fetch_array($query);
$query = mysql_query("SELECT fid, name FROM $table_forums WHERE fid='$forum1[fup]'") or die(mysql_error());
$cat = mysql_fetch_array($query);
} elseif($catsonly == "on" && $forum[type] == "forum") {
$query = mysql_query("SELECT fid, name FROM $table_forums WHERE fid='$forum[fup]'") or die(mysql_error());
$cat = mysql_fetch_array($query);
}

if($catsonly == "on") {
$navigation = "<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; <a href=\"modules.php?op=modload&name=XForum&file=index&gid=$cat[fid]\">$cat[name]</a> &gt; ";
} else {
$navigation = "<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; ";
}

if($forum[type] == "forum") {
$navigation .= $forum[name];
} else {
$query = mysql_query("SELECT name, fid FROM $table_forums WHERE fid='$forum[fup]'") or die(mysql_error());
$fup = mysql_fetch_array($query);
$navigation .= "<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fup[fid]\">$fup[name]</a> &gt; $forum[name]";
}

$html = template("header.html");
eval("echo stripslashes(\"$html\");");

$query = mysql_query("SELECT name FROM $table_forums WHERE type='sub' AND fup='$fid'") or die(mysql_error());
$sub = mysql_fetch_array($query);

if($sub[name] != "") {
?>

<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center"> 
<tr><td bgcolor="<?=$bordercolor?>"> 

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"> 
<tr>
<td width="3%" class="header">&nbsp;</td> 
<td width="58%" class="header"><?=_TEXTFORUM?></td> 
<td width="6%" class="header"><?=_TEXTTOPICS?></td>
<td width="6%" class="header"><?=_TEXTPOSTS?></td>
<td width="19%" class="header"><?=_TEXTLASTPOST?></td>
<td width="3%" class="header">&nbsp;</td>
</tr>

<?
$fulist = $forum[userlist];
$querys = mysql_query("SELECT * FROM $table_forums WHERE type='sub' AND fup='$fid'") or die(mysql_error());

while($forum = mysql_fetch_array($querys)) 
{
  // En attendant une modif de la table threads
  $sql1 = mysql_query("SELECT IF(LENGTH(LEFT(lastpost, INSTR(lastpost, '|')-1))<10, CONCAT('0',lastpost), lastpost) as  lastpost, tid, dateline FROM $table_threads where fid='".$forum[fid]."' ORDER BY lastpost DESC limit 0,1");
  $res = mysql_fetch_array($sql1);
  $sql2 = mysql_query("SELECT tid,dateline FROM $table_posts where dateline='".$res[lastpost]."'");
  $res2 = mysql_fetch_array($sql1);
  if ($res[dateline]>$res2[dateline]) { $res3=$res[tid]; } else { $res3=$res2[tid]; }

  echo Ligneforum($forum[lastpost], $timeoffset, $forum[moderator], $lastvisit2, $hideprivate, $status, $forum[private], $forum[posts], $forum[threads], $altbg1, $altbg2, $forum[name], $forum[fid], $forum[description], $timecode, $dateformat, $thisuser, $forum[userlist], $res3);
}
$forum[userlist] = $fulist;
?>
</table>
</td></tr></table><br>
<?
}
if($notexist != _TEXTNOFORUM) 
{ 

  if($newtopicimg != "") 
  {
	$newtopiclink = "<a href=\"modules.php?op=modload&name=XForum&file=post&action=newtopic&fid=$fid\"><img src=\"modules/XForum/$newtopicimg\" border=\"0\"></a>";
  } 
  else 
  {
	$newtopiclink = "<a href=\"modules.php?op=modload&name=XForum&file=post&action=newtopic&fid=$fid\">"._TEXTNEWTOPIC."</a>";
  }

}

if($piconstatus == "on") {
$picon1 = "<td width=\"4%\" class=\"header\">&nbsp;</td>";
}


if(!$tpp || $tpp == '') { 
$tpp = $topicperpage; 
} 

if ($page) {
$start_limit = ($page-1) *$tpp;
} else {
$start_limit = 0;
$page = 1;
}

if($cusdate != 0) {
$cusdate = time() - $cusdate;
$cusdate = "AND lastpost >= '$cusdate'";
}
elseif($cusdate == 0) {
$cusdate = "";
}

if(!$ascdesc) {
$ascdesc = "DESC";
}

$querytop = mysql_query("SELECT *, (substring_index(lastpost, '|',1)+1) lastpostd FROM $table_threads WHERE fid='$fid' $cusdate ORDER BY topped $ascdesc,lastpostd $ascdesc LIMIT $start_limit, $tpp") or die(mysql_error());

#DM###:.
/*
$query = mysql_query("SELECT count(tid) FROM $table_threads WHERE fid='$fid'") or die(mysql_error());
$topicsnum = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT count(tid) as nbsites FROM $table_threads WHERE fid='$fid'") or die(mysql_error());
$row = mysql_fetch_array($query);
$topicsnum = $row[nbsites];
#FM###:.

if ($topicsnum  > $tpp) {
$pages = $topicsnum  / $tpp;
$pages = ceil($pages);

if ($page == $pages) {
$to = $pages;
} elseif ($page == $pages-1) {
$to = $page+1;
} elseif ($page == $pages-2) {
$to = $page+2;
} else {
$to = $page+3;
}

if ($page == 1 || $page == 2 || $page == 3) {
$from = 1;
} else {
$from = $page-3;
}
$fwd_back .= "<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fid&page=1\"><<</a>";

for ($i = $from; $i <= $to; $i++) {
if($i != $page) {
$fwd_back .= "&nbsp;&nbsp;<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fid&page=$i\">$i</a>&nbsp;&nbsp;";
} else {
$fwd_back .= "&nbsp;&nbsp;<u><b>$i</b></u>&nbsp;&nbsp;";
}
}

$fwd_back .= "<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fid&page=$pages\">>></a>";
$multipage = $fwd_back;
}
?>

<!-- Affichage de nouveau topics et des pages -->
<table width="<?=$tablewidth?>" cellspacing="0" cellpadding="0" align="center">
<tr height="30">
  <td class="multi" align="left"><?=$multipage?></td>
  <td class="post"  align="right"><?=$newtopiclink?><br></td>
</tr>
</table>
<!-- /Affichage de nouveau topics et des pages -->

<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr>

<td width="4%" class="header">&nbsp;</td>
<?=$picon1?>
<td width="47%" class="header"><?=_TEXTSUBJECT?></td>
<td width="14%" class="header"><?=_TEXTAUTHOR?></td>
<td width="6%" class="header"><?=_TEXTREPLIES?></td>
<td width="6%" class="header"><?=_TEXTVIEWS?></td>
<td width="19%" class="header"><?=_TEXTLASTPOST?></td>
<td width="3%" class="header">&nbsp;</td>
</tr>
<?

if($forum[private] == "staff" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator") { 
echo "<tr class=\"tablerow\"><td bgcolor=\"$altbg1\" colspan=\"8\">"._PRIVFORUMMSG."</td></tr>"; 
exit; 
}

if($forum[userlist] != "") 
{
  if($thisuser == "")	{ $thisuser = "blalaguestman123frzq"; }

#DM###:.
//M if(!e regi($thisuser."(,|$)", $forum[userlist])) {
if(! preg_match("#".$thisuser."(,|$)"."#i", $forum[userlist])) {
#FM###:.

echo "<tr class=\"tablerow\"><td bgcolor=\"$altbg1\" colspan=\"8\">"._PRIVFORUMMSG."</td></tr>"; 
exit;
}

if($thisuser == "blalaguestman123fr") {
$thisuser = "";
}
}

if($subf[private] == "staff" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator") { 
echo "<tr class=\"tablerow\"><td bgcolor=\"$altbg1\" colspan=\"8\">"._PRIVFORUMMSG."</td></tr>"; 
exit; 
}


while($thread = mysql_fetch_array($querytop)) 
{
  $lastpost = explode("|", $thread[lastpost]);
  $dalast = $lastpost[0];

  if($lastpost[1] == _TEXTGUEST) { $lastpost[1] = $lastpost[1]; } 
  else {
$lastpost[1] = "<a href=\"modules.php?op=modload&name=XForum&file=member&action=viewpro&member=".rawurlencode($lastpost[1])."\">$lastpost[1]</a>";
}

$lastreplydate = gmdate($dateformat, $lastpost[0] + ($timeoffset * 3600));
$lastreplytime = gmdate($timecode, $lastpost[0] + ($timeoffset * 3600));
$lastpost = "$lastreplydate "._TEXTAT." $lastreplytime<br>"._TEXTBY." $lastpost[1]";

if($thread[icon] != "") {
$thread[icon] = "<img src=\"modules/XForum/images/$thread[icon]\" />";
} else {
$thread[icon] = "&nbsp;";
}

if($thread[replies] >= $hottopic) {
$folder = "<img src=\"modules/XForum/images/hot_folder.gif\" alt=\"Hot Topic\" />";
} else {
$folder = "<img src=\"modules/XForum/images/folder.gif\" alt=\"Topic\" />";
}

$lastvisit2 -= 540;
if($thread[replies] >= $hottopic && $lastvisit2 < $dalast) {
$folder = "<img src=\"modules/XForum/images/hot_red_folder.gif\">";
}
elseif($lastvisit2 < $dalast) {
$folder = "<img src=\"modules/XForum/images/red_folder.gif\">";
}
else {
$folder = $folder;
}
$lastvisit2 += 540;

if($thread[closed] == "yes") {
$folder = "<img src=\"modules/XForum/images/lock_folder.gif\" alt=\"Closed Topic\" />";
}

if($thread[topped] == 1) { 
$XFprefix = "<span class=\"11px\">("._TOPPEDPREFIX.")</span>"; 
} else {
$XFprefix = "";
}

$thread[subject] = stripslashes($thread[subject]);

$threadlink = "<a href=\"modules.php?op=modload&name=XForum&file=viewthread&tid=$thread[tid]\">$thread[subject]</a>";

if($thread[author] == _TEXTGUEST) {
$authorlink = $thread[author];
}
else {
$authorlink = "<a href=\"modules.php?op=modload&name=XForum&file=member&action=viewpro&member=".rawurlencode($thread[author])."\">$thread[author]</a>";
}

if(!$ppp || $ppp == '') { 
$ppp = $postperpage; 
}

if($thread[replies]  > $ppp) {
$posts = $thread[replies];
$posts++;
$topicpages = $posts / $ppp;
$topicpages = ceil($topicpages);
for ($i = 1; $i <= $topicpages; $i++) { 
$pagelinks .= " <a href=\"modules.php?op=modload&name=XForum&file=viewthread&tid=$thread[tid]&page=$i\">$i</a> "; 
}
$multipage2 = "(<small>Pages: $pagelinks</small>)";
$pagelinks = "";
} else {
$multipage2 = "";
}

if($piconstatus == "on") {
$picon2 = "<td bgcolor=\"$altbg1\" align=\"center\" class=\"tablerow\">$thread[icon]</td>
<td bgcolor=\"$altbg2\" class=\"tablerow\"><font class=\"12px\">$threadlink $XFprefix $multipage2</font></td>
<td bgcolor=\"$altbg1\" class=\"tablerow\">$authorlink</td>";

$ratecol2 = "<td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\"><font class=\"12px\">$thread[replies]</font></td>
<td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\"><font class=\"12px\">$thread[views]</font></td>
<td bgcolor=\"$altbg1\" class=\"tablerow\"><font size=\"1\" face=\"verdana\">$lastpost</font></td>
<td bgcolor=\"$altbg2\" class=\"tablerow\"><a href=\"modules.php?op=modload&name=XForum&file=print&fid=$fid&tid=$thread[tid]\" target=\"_blank\"><img src=\"modules/XForum/images/print.gif\" border=\"0\"></a></td>";


} else {
$picon2 = "<td bgcolor=\"$altbg1\" class=\"tablerow\"><font class=\"12px\">$threadlink $XFprefix $multipage</font></td>
<td bgcolor=\"$altbg2\" class=\"tablerow\">$authorlink</td>";

$ratecol2 = "<td bgcolor=\"$altbg1\" class=\"tablerow\" align=\"center\"><font class=\"12px\">$thread[replies]</font></td>
<td bgcolor=\"$altbg2\" class=\"tablerow\" align=\"center\"><font class=\"12px\">$thread[views]</font></td>
<td bgcolor=\"$altbg1\" class=\"tablerow\"><font size=\"1\" face=\"verdana\">$lastpost</font></td>
<td bgcolor=\"$altbg2\" class=\"tablerow\"><a href=\"modules.php?op=modload&name=XForum&file=print&fid=$fid&tid=$thread[tid]\" target=\"_blank\"><img src=\"modules/XForum/images/print.gif\" border=\"0\"></a></td>";

}
?>

<tr>
<td bgcolor="<?=$altbg2?>" align="center" class="tablerow"><?=$folder?></td>
<?=$picon2?>
<?=$ratecol2?>
</tr>

<?
}
if($notexist) { 
echo "<tr class=\"tablerow\"><td colspan=\"8\" bgcolor=\"$altbg1\">$notexist</td></tr>"; 
} 

if($topicsnum == 0 && !$notexist) {
echo "<tr class=\"tablerow\"><td colspan=\"8\" bgcolor=\"$altbg1\">"._NOPOSTS."</td></tr>";
}
?>

</table>
</td></tr></table>

<!-- Affichage de nouveau topics et des pages -->
<table width="<?=$tablewidth?>" cellspacing="0" cellpadding="0" align="center">
<tr height="30">
  <td class="multi" align="left"><?=$multipage?></td>
  <td class="post"  align="right"><?=$newtopiclink?><br></td>
</tr>
</table>
<!-- /Affichage de nouveau topics et des pages -->

<!--
<table width="<?=$tablewidth?>" cellspacing="0" cellpadding="0" align="center">
<tr>
<td bgcolor="<?=$bgcolor?>" class="multi"><?=$multipage?></td>
<td bgcolor="<?=$bgcolor?>" class="post" align="right">
<?=$newtopiclink?></td></tr>
-->
<?
if($showsort == "on") 
{
  if	($cusdate == "86400")				{ $check1 = "selected=\"selected\""; } 
  elseif($cusdate == "432000")				{ $check5 = "selected=\"selected\""; } 
  elseif($cusdate == "1296000")				{ $check15 = "selected=\"selected\"";} 
  elseif($cusdate == "2592000")				{ $check30 = "selected=\"selected\"";} 
  elseif($cusdate == "5184000")				{ $check60 = "selected=\"selected\"";} 
  elseif($cusdate == "8640000")				{ $check100 = "selected=\"selected\"";} 
  elseif($cusdate == "31536000")			{$checkyear = "selected=\"selected\"";} 
  elseif($cusdate == "0" || $cusdate == "") { $checkall = "selected=\"selected\""; }
?>

<table width="<?=$tablewidth?>" cellspacing="0" cellpadding="0" align="center"><tr><td align="center">
<form method="post" action="modules.php?op=modload&name=XForum&file=forumdisplay&fid=<?=$fid?>">
<span class="11px"><?=_SHOWTOPICS?></span>
<select name="cusdate">
<option value="86400" <?=$check1?>><?=_DAY1?></option>
<option value="432000" <?=$check5?>><?=_DAY5?></option>
<option value="1296000" <?=$check15?>><?=_DAY15?></option>
<option value="2592000" <?=$check30?>><?=_DAY30?></option>
<option value="5184000" <?=$check60?>><?=_DAY60?></option>
<option value="8640000" <?=$check100?>><?=_DAY100?></option>
<option value="31536000" <?=$checkyear?>><?=_LASTYEAR?></option>
<option value="0" <?=$checkall?>><?=_BEGINNING?></option>
</select>

<span class="11px"><?=_SORTBY?></span>
<select name="ascdesc">
<option value="ASC"><?=_ASC?></option>
<option value="DESC" selected="selected"><?=_DESC?></option>
</select>

<input type="submit" value="<?=_TEXTGO?>">
</form>
</td></tr></table>




<?
}

$multipage = "<div align=\"right\">$multipage</div>";
$foldernote = "<img src=\"modules/XForum/images/red_folder.gif\" alt=\"Topic\" /> "._OPENNEW." (<img src=\"modules/XForum/images/hot_red_folder.gif\" alt=\"Hot Topic\" /> "._HOTTOPIC.")<br><img src=\"modules/XForum/images/folder.gif\" alt=\"Topic\" /> "._OPENTOPIC." (<img src=\"modules/XForum/images/hot_folder.gif\" alt=\"Hot Topic\" /> "._HOTTOPIC.")<br><img src=\"modules/XForum/images/lock_folder.gif\" alt=\"Closed Topic\" /> "._LOCKTOPIC;

if($showtotaltime != "off") 
{ 
  $mtime2 = explode(" ", microtime());
  $endtime = $mtime2[1] + $mtime2[0];

  $totaltime = ($endtime - $starttime); 
  $totaltime = number_format($totaltime, 7); 
}


$html = template("footer.html");
eval("echo stripslashes(\"$html\");");
#DM###:.
if ($afffooter == "off") { ob_start(); include("footer.php"); ob_end_clean(); }
else {include("footer.php");}
#FM###:.
?>
