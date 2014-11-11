<?
# modifié 20010925 19h00
# modif suppr mysql_resul t 20011001 13h11
# bug remplacement !eregi 20011108 puis 20011122

require "modules/XForum/header.php";

$query = mysql_query("SELECT * FROM $table_threads WHERE tid='$tid'") or die(mysql_error());
$thread = mysql_fetch_array($query);
$fid = $thread[fid];

if($thread[tid] != $tid) { 
$notexist = _TEXTNOTHREAD; 
}

$closed = $thread[closed];
$topped = $thread[topped];

$query = mysql_query("SELECT name, private, userlist, type, fup, fid FROM $table_forums WHERE fid='$fid'") or die(mysql_error());
$forum = mysql_fetch_array($query);

if($forum[type] != "forum" && $forum[type] != "sub" && $forum[fid] != $fid) { 
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
$navigation = "<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; <a href=\"modules.php?op=modload&name=XForum&file=indexnav&gid=$cat[fid]\">$cat[name]</a> &gt; ";
} else {
$navigation = "<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; ";
}

if($forum[type] == "forum") {
$navigation .= "<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fid\"> $forum[name]</a> &gt; $thread[subject]";
} else {
$query = mysql_query("SELECT name, fid FROM $table_forums WHERE fid='$forum[fup]'") or die(mysql_error());
$fup = mysql_fetch_array($query);
$navigation .= "<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fup[fid]\">$fup[name]</a> &gt; <a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fid\"> $forum[name]</a> &gt; $thread[subject]";
}


if(!$action) {
$html = template("header.html");
eval("echo stripslashes(\"$html\");");

if($closed == "yes") {
$replylink = "";
$closeopen = "<a href=\"modules.php?op=modload&name=XForum&file=topicadmin&action=close&fid=$fid&tid=$tid\">"._TEXTOPENTHREAD."</a>";
$repquote = "";
}
else {
$closeopen = "<a href=\"modules.php?op=modload&name=XForum&file=topicadmin&action=close&fid=$fid&tid=$tid\">"._TEXTCLOSETHREAD."</a>";
$repquote = "<a href=\"modules.php?op=modload&name=XForum&file=post&action=reply&fid=$fid&tid=$tid&repquote=t|$tid\"><img src=\"modules/XForum/images/quote.gif\" border=\"0\" alt=\"Reply With Quote\" /></a> ";

if($replyimg != "") {
$replylink = " &nbsp;<a href=\"modules.php?op=modload&name=XForum&file=post&action=reply&fid=$fid&tid=$tid\"><img src=\"modules/XForum/$replyimg\"  border=\"0\"></a>";
} else {
$replylink = " &nbsp;<a href=\"modules.php?op=modload&name=XForum&file=post&action=reply&fid=$fid&tid=$tid\">"._TEXTPOSTREPLY."</a>";
}
}

if($topped == "1") {
$topuntop = "<a href=\"modules.php?op=modload&name=XForum&file=topicadmin&action=top&fid=$fid&tid=$tid\">"._TEXTUNTOPTHREAD."</a>";
}
else {
$topuntop = "<a href=\"modules.php?op=modload&name=XForum&file=topicadmin&action=top&fid=$fid&tid=$tid\">"._TEXTTOPTHREAD."</a>";
}

if($newtopicimg != "") 
{
  $newtopiclink = "<a href=\"modules.php?op=modload&name=XForum&file=post&action=newtopic&fid=$fid\"><img src=\"modules/XForum/$newtopicimg\" border=\"0\"></a>";
} 
else 
{
  $newtopiclink = "<a href=\"modules.php?op=modload&name=XForum&file=post&action=newtopic&fid=$fid\">"._TEXTNEWTOPIC."</a>";
}



$querynext = mysql_query("SELECT * FROM $table_threads WHERE lastpost > '$thread[lastpost]' AND fid='$fid' ORDER BY lastpost") or die(mysql_error()); 
$gotothread = mysql_fetch_array($querynext); 
if ($gotothread[tid] != "") { 
$next = " <a href=\"modules.php?op=modload&name=XForum&file=viewthread&fid=$fid&tid=$gotothread[tid]\">"._NEXTTHREAD." &gt;</a>"; 
} 

$querylast = mysql_query("SELECT * FROM $table_threads WHERE lastpost < '$thread[lastpost]' AND fid='$fid' ORDER BY lastpost DESC") or die(mysql_error()); 
$goto2 = mysql_fetch_array($querylast); 

if ($goto2[tid] != "") { 
$prev = "<a href=\"modules.php?op=modload&name=XForum&file=viewthread&fid=$fid&tid=$goto2[tid]\">&lt; "._LASTTHREAD."</a> &nbsp;"; 
} 


if(!$ppp || $ppp == '') { 
$ppp = $postperpage; 
}

if($page) {
$start_limit = ($page-1) * $ppp;
} else {
$start_limit = 0;
$page = 1;
}

if($page != 1) {
$start_limit -= 1;
}

mysql_query("UPDATE $table_threads SET views=views+1 WHERE tid='$tid'") or die(mysql_error()); 
#DM###:.
/*
$query = mysql_query("SELECT count(pid) FROM $table_posts WHERE fid='$fid' AND tid='$tid'") or die(mysql_error()); 
$num = mysql_resul t($query,0); 
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT count(pid) as nbsites FROM $table_posts WHERE fid='$fid' AND tid='$tid'") or die(mysql_error()); 
$row = mysql_fetch_array($query);
$num = $row[nbsites];
#FM###:.


if ($num >= $ppp) {
$num++;
$pages = $num / $ppp;
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
$fwd_back .= "<a href=\"modules.php?op=modload&name=XForum&file=viewthread&tid=$tid&page=1\"><<</a>";

for ($i = $from; $i <= $to; $i++) {
if($i != $page) {
$fwd_back .= "&nbsp;&nbsp;<a href=\"modules.php?op=modload&name=XForum&file=viewthread&fid=$fid&tid=$tid&page=$i\">$i</a>&nbsp;&nbsp;";
} else {
$fwd_back .= "&nbsp;&nbsp;<u><b>$i</b></u>&nbsp;&nbsp;";
}
}

$fwd_back .= "<a href=\"modules.php?op=modload&name=XForum&file=viewthread&tid=$tid&page=$pages\">>></a>";
$multipage = "$fwd_back";
}
?>

<!-- Affichage de nouveau topics et des pages -->
<table width="<?=$tablewidth?>" cellspacing="0" cellpadding="0" align="center">
<tr height="30">
	<td><a href="modules.php?op=modload&name=XForum&file=print&fid=<?=$fid?>&tid=<?=$tid?>" target="_blank"><img src="modules/XForum/images/printer.gif" border="0"></a></td>
  <td class="multi"><span style="font-family:arial; font-size: 11px;"><?=$prev?><?=$next?><?=$multipage?></span></td> 
  <td class="post" align="right"><?=$newtopiclink?><?=$replylink?>
  </td>
</tr>
</table>



<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">

<?
if($forums[private] == "staff" && $status != "Administrator" && $status != "Moderator") {
echo "<tr class=\"tablerow\"><td>"._PRIVFORUMMSG."</td></tr>";
exit;
}

if($forum[userlist] != "") {
if($thisuser == "") {
$thisuser = "blalaguestman123frzq";
}

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
?>
<tr><td bgcolor="<?=$bordercolor?>">

<?
if($page == 1) {
$date = gmdate("$dateformat",$thread[dateline] + ($timeoffset * 3600));
$time = gmdate("$timecode",$thread[dateline] + ($timeoffset * 3600));
$poston = _TEXTPOSTON." $date "._TEXTAT." $time";

if($thread[icon] != "") {
$thread[icon] = "<img src=\"modules/XForum/images/$thread[icon]\" />";
}

$query = mysql_query("SELECT * FROM $table_members WHERE username='$thread[author]'") or die(mysql_error());
$member = mysql_fetch_array($query);

$info[$thread[author]] = $member;

if($member[email] != "" && $member[showemail] == "yes") {
$email = "<a href=\"mailto:$member[email]\"><img src=\"modules/XForum/images/email.gif\" border=\"0\" alt=\"E-Mail User\" /></a> ";
} else {
$email = "";
}

$member[site] = str_replace("http://", "", $member[site]);
$member[site] = "http://$member[site]";

if($member[site] == "http://") {
$site = "";
} else {
$site = "<a href=\"$member[site]\" target=\"_blank\"><img src=\"modules/XForum/images/site.gif\" border=\"0\" alt=\"Visit User's Homepage\" /></a> ";
}

$query = mysql_query("SELECT * FROM $table_whosonline WHERE username='$thread[author]'"); 
$onlineinfo = mysql_fetch_array($query);

$ol[$thread[author]] = $onlineinfo;

if ($onlineinfo[username] == $thread[author]) { 
$onlinestatus = _TEXTSTATUS." <span class=\"11px\"><b>"._TEXTONLINE."</b></span>"; 
} else { 
$onlinestatus = _TEXTSTATUS." <span class=\"11px\"><b>"._TEXTOFFLINE."</b></span>"; 
}

$edit = "<a href=\"modules.php?op=modload&name=XForum&file=post&action=edit&fid=$fid&tid=$tid\"><img src=\"modules/XForum/images/edit.gif\" border=\"0\" alt=\"Edit Post\" /></a> ";
$search = "<a href=\"modules.php?op=modload&name=XForum&file=misc&action=search&srchuname=".rawurlencode($thread[author])."&searchsubmit=a&srchfid=all\"><img src=\"modules/XForum/images/find.gif\" border=\"0\"></a> ";
$profile = "<a href=\"modules.php?op=modload&name=XForum&file=member&action=viewpro&member=".rawurlencode($thread[author])."\"><img src=\"modules/XForum/images/profile.gif\" border=\"0\"></a> ";

if($status != "Administrator" && $status != "Moderator") {
$ip = "";
} else {
$ip = "<a href=\"modules.php?op=modload&name=XForum&file=topicadmin&action=getip&fid=$fid&tid=$tid\"><img src=\"modules/XForum/images/ip.gif\" border=\"0\" alt=\"Get IP\" /></a>";
}

}

$showtitle = $member[status];
$queryrank = mysql_query("SELECT * FROM $table_ranks") or die(mysql_error());
while($rank = mysql_fetch_array($queryrank)) 
{
  if($member[postnum] >= $rank[posts]) 
  {
	$allowavatars = $rank[allowavatars];
	$showtitle = $rank[title];
	$stars = "";
	for($i = 0; $i < $rank[stars]; $i++) 
	{
	  $stars .= "<img src=\"modules/XForum/images/star.gif\">";
	}

if($rank[avatarrank] != "") {
$avarank = $rank[avatarrank];
}
} else {
$showtitle = $showtitle;
$stars = $stars;
}
}

if($member[status] == "Administrator" || $member[status] == "Super Moderator" || $member[status] == "Moderator" || $member[status] == "Banned") {
#DM###:.
/*
$query = mysql_query("SELECT stars FROM $table_ranks ORDER BY stars DESC") or die(mysql_error());
$staffstar = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT stars FROM $table_ranks ORDER BY stars DESC") or die(mysql_error());
$row = mysql_fetch_array($query);
$staffstar = $row[stars];
#FM###:.

$stars = "";
for($i = 0; $i < $staffstar; $i++) {
$stars .= "<img src=\"modules/XForum/images/star.gif\">";
}

if($member[status] == "Administrator"){
$showtitle = _TEXTADMIN;
$stars .= "<img src=\"modules/XForum/images/star.gif\"><img src=\"modules/XForum/images/star.gif\"><img src=\"modules/XForum/images/star.gif\">";
$allowavatars = "yes";
} elseif($member[status] == "Super Moderator"){
$showtitle = _TEXTSUPERMOD;
$stars .= "<img src=\"modules/XForum/images/star.gif\"><img src=\"modules/XForum/images/star.gif\">";
$allowavatars = "yes";
} elseif($member[status] == "Moderator"){
$showtitle = _TEXTMOD;
$stars .= "<img src=\"modules/XForum/images/star.gif\">";
$allowavatars = "yes";
}
}

if($member[status] == "Banned"){
$showtitle = _TEXTBANNED;
$stars = "";
}

if($member[customstatus] != "") {
$cusstatus = $member[customstatus];
$showtitle = $cusstatus;
} else {
$showtitle = $showtitle;
}

$tharegdate = gmdate("$dateformat", $member[regdate] + ($timeoffset * 3600));
$miscinfo = "<br>"._TEXTPOSTS." $member[postnum]<br>"._TEXTREGISTERED." $tharegdate";

$showtitle .= "<br>";
$stars .= "<br>";

if($allowavatars == "yes") {
if($avarank != "" && $member[avatar] == "") {
$avatar = "<img src=\"modules/XForum/$avarank\">";
} elseif($member[avatar] != "") {
#DM###:. orig-> $avatar = "<img src=\"modules/XForum/$member[avatar]\">";
/*// M if ( (e regi("http://", $member[avatar])) or (e regi("/images", $member[avatar])) ) 
if ( (preg_match("/http\:\/\//i", $member[avatar])) or (preg_match("/\/images/i", $member[avatar])) ) 
{
	$avatar = "<img height=90 width=70 src=\"$member[avatar]\">";	#DM###:. Ici il ne faut pas le root de images pour avatars #FM###:.
}*/
$avatar = "<img src=\"modules/XForum/images/avatar/$member[avatar]\">";   # modif du 20011001
#else { $avatar = "<img src=\"modules/XForum/$member[avatar]\">"; }# modif du 20011001
} else {
$avatar = "";
}
}


if($avastatus != "on") {
$avatar = "&nbsp;";
}

if($member[username] == "" || $thread[username] == ""._textguest) {
$showtitle = _UNREG;
$miscinfo = "";
$profile = "";
$email = "";
$onlinestatus = "";
$stars = "";
$search = "";
$warn = "";
$site = "";

if($noreg == "on") {
$showtitle = "";
}
}

if($thisuser != "" && $reportpost != "off") { 
$reportlink = " | <a href=\"modules.php?op=modload&name=XForum&file=topicadmin&action=report&fid=$fid&tid=$tid\">"._TEXTREPORTPOST."</a>"; 
} else { 
$reportlink = ""; 
} 

$userstuff = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\"><tr><td class=\"11px\">$profile$email$edit$repquote$site$search$reportlink</td></tr></table>";
$adminstuff = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\"><tr><td>$ip</td></tr></table>";

$thread[subject] = stripslashes($thread[subject]);
?>
<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr>
<td width="18%" class="header"><?=_TEXTAUTHOR?> </td>
<td class="header"><?=_TEXTSUBJECT." $thread[subject]"; ?></td>
</tr>
<?
if($forum[private] == "staff" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator") { 
echo "<tr class=\"tablerow\"><td bgcolor=\"$altbg1\" colspan=\"2\">"._PRIVFORUMMSG."</td></tr>"; 
exit; 
}

if($sub[private] == "staff" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator") { 
echo "<tr class=\"tablerow\"><td bgcolor=\"$altbg1\" colspan=\"2\">"._PRIVFORUMMSG."</td></tr>"; 
exit; 
}

if($page == 1) {
if(!$notexist) {
$bbcodeoff = $thread[bbcodeoff];
$smileyoff = $thread[smileyoff];
$thread[message] = stripslashes($thread[message]);
$thread[message] = postify($thread[message], $smileyoff, $bbcodeoff, $fid, $bordercolor, "", "", $table_words, $table_forums, $table_smilies);

if($thread[usesig] == "yes") {
$member[sig] = postify($member[sig], "no", "", "", $bordercolor, $sigbbcode, $sightml, $table_words, $table_forums, $table_smilies);
#DM###:. si ya des erreurs on peut mettre pas d'images en preg_replace
$member[sig] = preg_replace("/<img src=(.*)>/i","<img width=100 height=50 src=\\1>",$member[sig]);
#FM###:.
$thread[message] .= "<p>&nbsp;</p>____________________<br>$member[sig]";
}
?>
<tr bgcolor="<?=$altbg1?>">
<td rowspan="3" valign="top" class="tablerow"><span class="postauthor"><?=$thread[author]?></span><br>
<div class="11px"><?=$showtitle?><?=$stars?><br><?=$avatar?><br><?=$miscinfo?><br><?=$onlinestatus?></div><br></td>
<td valign="top" class="11px" class="tablerow"><?=$thread[icon]?> &nbsp; <?=$poston?></td></tr>
<tr bgcolor="<?=$altbg1?>"><td height="120" valign="top"><font class="12px"><?=$thread[message]?></font><br>&nbsp;</td></tr>
<tr bgcolor="<?=$altbg1?>"><td valign="top"><?=$userstuff?> <?=$adminstuff?></td></tr>

<? 
$ppp -= 1; 
} else { 
echo "<tr class=\"tablerow\"><td colspan=\"8\" bgcolor=\"$altbg1\">$notexist</td></tr>"; 
} 
} 
$querypost = mysql_query("SELECT * FROM $table_posts WHERE fid='$fid' AND tid='$tid' ORDER BY dateline LIMIT $start_limit, $ppp") or die(mysql_error()); 
$thisbg = $altbg2; 

while($post = mysql_fetch_array($querypost)) {

$date = gmdate("$dateformat", $post[dateline] + ($timeoffset * 3600));
$time = gmdate("$timecode", $post[dateline] + ($timeoffset * 3600));

$poston = _TEXTPOSTON." $date "._TEXTAT." $time";

if($post[icon] != "") {
$post[icon] = "<img src=\"modules/XForum/images/$post[icon]\" />";
}

if($info[$post[author]] && $info[$post[author]] != "") {
$member = $info[$post[author]];
}
else {
$query = mysql_query("SELECT * FROM $table_members WHERE username='$post[author]'") or die(mysql_error());
$member = mysql_fetch_array($query);

$info[$post[author]] = $member;
}

if($member[email] != "" && $member[showemail] == "yes") {
$email = "<a href=\"mailto:$member[email]\"><img src=\"modules/XForum/images/email.gif\" border=\"0\" alt=\"E-Mail User\" /></a> ";
} else {
$email = "";
}
$member[site] = str_replace("http://", "", $member[site]);
$member[site] = "http://$member[site]";

if($member[site] == "http://") {
$site = "";
} else {
$site = "<a href=\"$member[site]\" target=\"_blank\"><img src=\"modules/XForum/images/site.gif\" border=\"0\" alt=\"Visit User's Homepage\" /></a> ";
}

if($ol[$post[author]] && $ol[$post[author]] != '') {
$onlineinfo = $ol[$post[author]];
}
else {
$query = mysql_query("SELECT * FROM $table_whosonline WHERE username='$post[author]'"); 
$onlineinfo = mysql_fetch_array($query); 
$ol[$post[author]] = $onlineinfo;
}
if ($onlineinfo[username] == $post[author]) { 
$onlinestatus = _TEXTSTATUS." <span class=\"11px\"><b>"._TEXTONLINE."</b></span>"; 
} else { 
$onlinestatus = _TEXTSTATUS." <span class=\"11px\"><b>"._TEXTOFFLINE."</b></span>"; 
} 

$edit = "<a href=\"modules.php?op=modload&name=XForum&file=post&action=edit&fid=$fid&tid=$tid&pid=$post[pid]\"><img src=\"modules/XForum/images/edit.gif\" border=\"0\" alt=\"Edit Post\" /></a> ";

if($closed == "yes") {
$repquote = "";
} else {
$repquote = "<a href=\"modules.php?op=modload&name=XForum&file=post&action=reply&fid=$fid&tid=$tid&repquote=r|$post[pid]\"><img src=\"modules/XForum/images/quote.gif\" border=\"0\" alt=\"Reply With Quote\" /></a> ";
}


$search = "<a href=\"modules.php?op=modload&name=XForum&file=misc&action=search&srchuname=".rawurlencode($post[author])."&searchsubmit=a&srchfid=all\"><img src=\"modules/XForum/images/find.gif\" border=\"0\"></a> ";
$profile = "<a href=\"modules.php?op=modload&name=XForum&file=member&action=viewpro&member=".rawurlencode($post[author])."\"><img src=\"modules/XForum/images/profile.gif\" border=\"0\"></a> ";

if($status != "Administrator" && $status != "Moderator" && $status != "Super Moderator") {
$ip = "";
} else{
$ip = "<a href=\"modules.php?op=modload&name=XForum&file=topicadmin&action=getip&fid=$fid&tid=$tid&pid=$post[pid]\"><img src=\"modules/XForum/images/ip.gif\" border=\"0\" alt=\"Get IP\" /></a>";
}

$showtitle = $member[status];
$queryrank = mysql_query("SELECT posts, allowavatars, title, stars, avatarrank FROM $table_ranks") or die(mysql_error());
while($rank = mysql_fetch_array($queryrank)) {
if($member[postnum] >= $rank[posts]) {
$allowavatars = $rank[allowavatars];
$showtitle = $rank[title];
$stars = "";
for($i = 0; $i < $rank[stars]; $i++) {
$stars .= "<img src=\"modules/XForum/images/star.gif\">";
}

if($rank[avatarrank] != "") {
$avarank = $rank[avatarrank];
}
} else {
$showtitle = $showtitle;
$stars = $stars;
}
}

if($member[status] == "Administrator" || $member[status] == "Super Moderator" || $member[status] == "Moderator") {
#DM###:.
/*
$query = mysql_query("SELECT stars FROM $table_ranks ORDER BY stars DESC") or die(mysql_error());
$staffstar = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT stars FROM $table_ranks ORDER BY stars DESC") or die(mysql_error()); 
$row = mysql_fetch_array($query);
$staffstar = $row[stars];
#FM###:.

$stars = "";
for($i = 0; $i < $staffstar; $i++) {
$stars .= "<img src=\"modules/XForum/images/star.gif\">";
}

if($member[status] == "Administrator"){
$showtitle = _TEXTADMIN;
$stars .= "<img src=\"modules/XForum/images/star.gif\"><img src=\"modules/XForum/images/star.gif\"><img src=\"modules/XForum/images/star.gif\">";
$allowavatars = "yes";
} elseif($member[status] == "Super Moderator"){
$showtitle = _TEXTSUPERMOD;
$stars .= "<img src=\"modules/XForum/images/star.gif\"><img src=\"modules/XForum/images/star.gif\">";
$allowavatars = "yes";
} elseif($member[status] == "Moderator"){
$showtitle = _TEXTMOD;
$stars .= "<img src=\"modules/XForum/images/star.gif\">";
$allowavatars = "yes";
} 
}

if($member[status] == "Banned"){
$showtitle = textbanned;
$stars = "";
}

if($member[customstatus] != "") {
$showtitle = $member[customstatus];
} else {
$showtitle = $showtitle;
}

$tharegdate = gmdate("$dateformat", $member[regdate] + ($timeoffset * 3600));
$miscinfo = "<br>"._TEXTPOSTS." $member[postnum]<br>"._TEXTREGISTERED." $tharegdate";

$showtitle .= "<br>";
$stars .= "<br>";

if($allowavatars == "yes") {
if($avarank != "" && $member[avatar] == "" && $member[status] != "Administrator" && $member[status] != "Super Moderator" && $member[status] != "Moderator") {
$avatar = "<img src=\"modules/XForum/$avarank\">";
} elseif($member[avatar] != "") {
#DM###:. orig-> $avatar = "<img src=\"modules/XForum/$member[avatar]\">";
// M if ( (e regi("http://", $member[avatar])) or (e regi("/images", $member[avatar])) ) 
/*if ( (preg_match("/http\:\/\//i", $member[avatar])) or (preg_match("/\/images/i", $member[avatar])) ) 
{
	$avatar = "<img height=90 width=70 src=\"$member[avatar]\">";	#DM###:. Ici il ne faut pas le root de images pour avatars #FM###:.
$avatar = "<img src=\"modules/XForum/images/$member[avatar]\">";	
}
else {*/ 
$avatar = "<img src=\"modules/XForum/images/avatar/$member[avatar]\">"; # modif de 20011001}  

} else {
$avatar = "";
}
}

if($avastatus != "on") { $avatar = "&nbsp;"; }

if($member[username] == "" || $thread[username] == ""._textguest) 
{
	$showtitle = _UNREG;
	$miscinfo = "";
	$profile = "";
$email = "";
$onlinestatus = "";
$stars = "";
$search = "";
$warn = "";
$site = "";

if($noreg == "on") {
$showtitle = "";
}
}

if($thisuser != "" && $reportpost != "off") { 
$reportlink = " | <a href=\"modules.php?op=modload&name=XForum&file=topicadmin&action=report&fid=$fid&tid=$tid&pid=$post[pid]\">"._TEXTREPORTPOST."</a>"; 
} else { 
$reportlink = ""; 
} 

$userstuff = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"left\"><tr><td class=\"11px\">$profile$email$edit$repquote$site$search$reportlink</td></tr></table>";
$adminstuff = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" align=\"right\"><tr><td>$ip</td></tr></table>";
$bbcodeoff = $post[bbcodeoff];
$smileyoff = $post[smileyoff];
$post[message] = stripslashes($post[message]);
$post[message] = postify($post[message], $smileyoff, $bbcodeoff, $fid, $bordercolor, "", "", $table_words, $table_forums, $table_smilies);

if($post[usesig] == "yes") {
$member[sig] = postify($member[sig], "no", "", "", $bordercolor, $sigbbcode, $sightml, $table_words, $table_forums, $table_smilies);
#DM###:.
$member[sig] = preg_replace("/<img src=(.*)>/i","<img width=100 height=50 src=\\1>",$member[sig]);
#FM###:.
$post[message] .= "<p>&nbsp;</p>____________________<br>$member[sig]";
}

if(!$notexist) { 
?>

<tr bgcolor="<?=$thisbg?>">
<td rowspan="3" valign="top" class="tablerow" width="18%"><a name="pid<?=$post[pid]?>"></a><span class="postauthor"><?=$post[author]?> </span><br>
<div class="11px"><?=$showtitle?><?=$stars?><?=$avatar?><br><?=$miscinfo?><br><?=$onlinestatus?></div><br></td>
<td valign="top" class="11px" class="tablerow" width="82%"><?=$post[icon]?> &nbsp; <?=$poston?></td></tr>
<tr bgcolor="<?=$thisbg?>"><td height="120" valign="top"><font class="12px"><?=$post[message]?></font><br>&nbsp;</td></tr>
<tr bgcolor="<?=$thisbg?>"><td valign="top" class="tablerow"><?=$userstuff?> <?=$adminstuff?></td></tr>

<?
} else {
echo "<tr class=\"tablerow\"><td colspan=\"8\" bgcolor=\"$altbg1\">$notexist</td></tr>"; 
}

if($thisbg == $altbg2) {
$thisbg = $altbg1;
} 
else {
$thisbg = $altbg2;
}

}

?>
</table>
</td></tr></table>


<!-- Affichage de nouveau topics et des pages -->
<table width="<?=$tablewidth?>" cellspacing="0" cellpadding="0" align="center">
<tr height="30">
  <td class="multi"><?=$multipage?></td>
  <td class="post" align="right"><?=$newtopiclink?> &nbsp;<?=$replylink?></td>
</tr>

<tr><td class="11px" colspan="2">
<br>

<?
if($status == "Administrator" || $status == "Super Moderator" || $status == "Moderator") { 
?>
<span style="font-family:arial; font-size: 11px;">
<a href="modules.php?op=modload&name=XForum&file=topicadmin&action=delete&fid=<?=$fid?>&tid=<?=$tid?>"><?=_TEXTDELETETHREAD?></a> - <?=$closeopen?> - 
<a href="modules.php?op=modload&name=XForum&file=topicadmin&action=move&fid=<?=$fid?>&tid=<?=$tid?>"><?=_TEXTMOVETHREAD?></a><br>
<?=$topuntop?> - <a href="modules.php?op=modload&name=XForum&file=topicadmin&action=bump&fid=<?=$fid?>&tid=<?=$tid?>"><?=_TEXTBUMPTHREAD?></a></span>
<? 
} 
?>

</td></tr>

<?
$mtime2 = explode(" ", microtime());
$endtime = $mtime2[1] + $mtime2[0];
if($showtotaltime != "off") { 
$totaltime = ($endtime - $starttime); 
$totaltime = number_format($totaltime, 7); 
}


$html = template("footer.html");
eval("echo stripslashes(\"$html\");");
}


#DM###:.
if ($afffooter == "off") { ob_start(); include("footer.php"); ob_end_clean(); }
else {include("footer.php");}
#FM###:.
?>