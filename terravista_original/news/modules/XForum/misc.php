<?
# modif suppr mysql_resul t 20011001 13h40
# modif requete stats l:779 20011005 14h12

require "modules/XForum/header.php";


function misctemplate($miscaction, $lastvisita, $thisuser, $cplink, $lastvisittext) {

#require "modules/XForum/lang/$langfile.lang.php";
require "modules/XForum/settings.php";

$bbname = $GLOBALS["bbname"];
$langfile = $GLOBALS["langfile"];
$bgcolor = $GLOBALS["bgcolor"];
$altbg1 = $GLOBALS["altbg1"];
$altbg2 = $GLOBALS["altbg2"];
$link = $GLOBALS["link"];
$header = $GLOBALS["header"];
$headertext = $GLOBALS["headertext"];
$top = $GLOBALS["top"];
$bordercolor = $GLOBALS["bordercolor"];
$tabletext = $GLOBALS["tabletext"];
$boardlogo = $GLOBALS["boardlogo"];
$text = $GLOBALS["text"];
$borderwidth = $GLOBALS["borderwidth"];
$tablewidth = $GLOBALS["tablewidth"];
$tablespace = $GLOBALS["tablespace"];
$font = $GLOBALS["font"];
$fontsize = $GLOBALS["fontsize"];
$altfont = $GLOBALS["altfont"];
$altfontsize = $GLOBALS["altfontsize"];
$replyimg = $GLOBALS["replyimg"];
$newtopicimg = $GLOBALS["newtopicimg"];
$boardimg = $GLOBALS["boardimg"];
#$postscol = $GLOBALS["postscol"];

$font1 = $fontsize-1;
$font2 = $fontsize+1;
$font3 = $fontsize+3;
$font4 = $fontsize+5;

if($regstatus == "on" && $noreg != "on") 
{ 
  if($coppa == "on") 
  { 
	$reglink = "<a href=\"modules.php?op=modload&name=XForum&file=member&action=coppa\"><span class=\"navtd\">"._TEXTREGISTER."</span></a>"; 
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
  #$ loginout = "<a href=\"modules.php?op=modload&name=XForum&file=misc&action=logout\"><span class=\"navtd\">"._TEXTLOGOUT."</span></a>"; 
  $proreg = "<a href=\"modules.php?op=modload&name=XForum&file=member&action=editpro\"><span class=\"navtd\">"._TEXTPROFILE."</span></a>"; 
  $onlineuser = $thisuser; 
} 
else 
{ 
  $notify = _NOTLOGGEDIN; 
  #$ loginout = "<a href=\"modules.php?op=modload&name=XForum&file=user\"><span class=\"navtd\">"._TEXTLOGIN."</span></a>"; 
  $onlineuser = "xguest123"; 
} 


if($searchstatus == "on") { 
$searchlink = "| <a href=\"modules.php?op=modload&name=XForum&file=misc&action=search\"><span class=\"navtd\">"._TEXTSEARCH."</span></a>"; 
} 

if($faqstatus == "on") { 
$faqlink = "| <a href=\"modules.php?op=modload&name=XForum&file=misc&action=faq\"><span class=\"navtd\">"._TEXTFAQ."</span></a>"; 
} 

if($memliststatus == "on") { 
$memlistlink = "| <a href=\"modules.php?op=modload&name=XForum&file=misc&action=list\"><span class=\"navtd\">"._TEXTMEMBERLIST."</span></a>"; 
} 

if($boardimg != "") { 
$logo = "<tr><td><a href=\"modules.php?op=modload&name=XForum&file=index\"><img src=\"modules/XForum/$boardimg\" alt=\"Board logo\" border=\"0\" /></a></td><td> </td></tr>"; 
}

if($statspage == "on") {
$statslink = "| <a href=\"modules.php?op=modload&name=XForum&file=misc&action=stats\"><span class=\"navtd\">"._TEXTSTATS."</span></a>";
}

$navigation = "<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; $miscaction";
$html = template("header.html");
eval("echo stripslashes(\"$html\");");
}

if($action == 'faq') {
$miscaction = _TEXTFAQ;
misctemplate($miscaction, $lastvisita, $thisuser, $cplink, $lastvisittext, $langfile);

if($faqstatus != "on") {
echo _FAQOFF;
exit;
}

$query = mysql_query("SELECT * FROM $table_ranks ORDER BY posts") or die(mysql_error());
while($ranks = mysql_fetch_array($query)) {
for($i = 0; $i < $ranks[stars]; $i++) {
$stars .= "<img src=\"modules/XForum/images/star.gif\">";
}
$allranks .= "<tr><td bgcolor=\"$altbg2\">$ranks[title]</td><td bgcolor=\"$altbg2\">$stars</td><td bgcolor=\"$altbg2\">$ranks[posts] "._MEMPOSTS."</td></tr>\n";
$stars = "";
}
?>

<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTFAQ?></td>
</tr>

<tr><td bgcolor="<?=$altbg2?>" class="tablerow">
<a name="bbcode"></a>
<b><?=_TEXTBBCODE?></b><br>
<?=_BBCODEINFO?><p>&nbsp;</p>

<b><?=_TEXTUSERRANKS?></b><br>
<?=_RANKINFO?><br><br>

<table cellspacing="0" cellpadding="0" border="0" width="25%"> 
<tr><td bgcolor="<?=$bordercolor?>"> 

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="2" width="100%"> 
<!--#DM###:.-->
<tr><td>
<!--#FM###:.-->
<?=$allranks?>
<!--#DM###:.-->
</td></tr>
<!--#FM###:.-->	
</table>
</td></tr></table>
</td></tr>
<!--#DM###:.-->
</table>
</td></tr></table>
<!--#FM###:.-->	

<?
}

if($action == 'search') {
$miscaction = _TEXTSEARCH;
misctemplate($miscaction, $lastvisita, $thisuser, $cplink, $lastvisittext, $langfile);

if($searchstatus != "on") {
echo _SEARCHOFF;
exit;
}

if(!$searchsubmit) {

$forumselect = "<select name=\"srchfid\">\n";
$forumselect .= "<option value=\"all\">"._TEXTALL."</option>\n";
$queryforum = mysql_query("SELECT * FROM $table_forums WHERE type='forum'") or die(mysql_error());
while($forum = mysql_fetch_array($queryforum)) {

$authorization = privfcheck($hideprivate, $status, $forum[private], $thisuser, $forum[userlist]);

if($authorization == "true") { 
$forumselect .= "<option value=\"$forum[fid]\">$forum[name]</option>\n";
}
}
$forumselect .= "</select>";

?>

<form method="post" action="modules.php?op=modload&name=XForum&file=misc&action=search">
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTSEARCH?></td>
</tr>

<tr>
<td bgcolor="<?=$altbg1?>" class="tablerow" width="22%"><?=_TEXTSEARCHFOR?></td>
<td bgcolor="<?=$altbg2?>"><input type="text" name="srchtxt" size="30" maxlength="40" /></td>
</tr>	

<tr>
<td bgcolor="<?=$altbg1?>" class="tablerow" width="22%"><?=_TEXTSRCHUNAME?></td>
<td bgcolor="<?=$altbg2?>"><input type="text" name="srchuname" size="30" maxlength="40" /></td>
</tr>

<tr>
<td bgcolor="<?=$altbg1?>" class="tablerow" width="22%"><?=_SRCHBYFORUM?></td>
<td bgcolor="<?=$altbg2?>"><?=$forumselect?></td>
</tr>

<tr>
<td bgcolor="<?=$altbg1?>" class="tablerow" width="22%"><?=_TEXTLFROM?></td>
<td bgcolor="<?=$altbg2?>"><select name="srchfrom">
<option value="86400"><?=_DAY1?></option>
<option value="604800"><?=_AWEEK?></option>
<option value="2592000"><?=_MONTH1?></option>
<option value="7948800"><?=_MONTH3?></option>
<option value="15897600"><?=_MONTH6?></option>
<option value="31536000"><?=_LASTYEAR?></option>
<option value="0" selected="selected"><?=_BEGINNING?></option>
</select></td>
</tr>

<tr>
<td bgcolor="<?=$altbg1?>" class="tablerow" width="22%"><?=_TEXTSEARCHIN?></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="radio" name="srchin" value="reply"><?=_REPLIESL?> <input type="radio" name="srchin" value="topic"><?=_TOPICSL?> <input type="radio" name="srchin" value="both" checked="checked"><?=_TEXTBOTH?></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="searchsubmit" value="<?=_TEXTSEARCH?>" /></center>
</form>

<?
}

if($searchsubmit) {
?>

<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="3"><?=_TEXTSEARCH?></td>
</tr>

<?
#DM###:.
/*
$sql1 = "SELECT * FROM $table_threads";
$sql2 = "SELECT * FROM $table_posts";
*/
#FM###:. <-- fin suppr et remplacement par -->
$sql1 = "SELECT IF(LENGTH(LEFT(lastpost, INSTR(lastpost, '|')-1))<10, CONCAT('0',lastpost), lastpost) as  lastpost, dateline,subject, fid, tid FROM $table_threads";
$sql2 = "SELECT * FROM $table_posts";
#FM###:.

if($srchfrom == "0") {
$srchfrom = time();
}

$srchfrom = time() - $srchfrom;

if($srchtxt) {
$sql1 .= " WHERE (message LIKE '%$srchtxt%' OR subject LIKE '%$srchtxt%') AND lastpost >= '$srchfrom'";
$sql2 .= " WHERE message LIKE '%$srchtxt%' AND dateline >= '$srchfrom'";
}
elseif($srchtxt == "" && $srchuname == "" && $srchfid != "all" || $srchfid == "") {
$sql1 .= " WHERE fid='$srchfid' AND lastpost >= '$srchfrom'";
$sql2 .= " WHERE fid='$srchfid' AND dateline >= '$srchfrom'";
}   
elseif($srchtxt == "" && $srchuname != "") {
if($srchfid == "all" || $srchfid != "") {
$sql1 .= " WHERE author='$srchuname' AND lastpost >= '$srchfrom'";
$sql2 .= " WHERE author='$srchuname' AND dateline >= '$srchfrom'";
}
}

if($srchfid != "all" && $srchtxt != "" && $srchuname == "") {
$sql1 .= " AND fid='$srchfid' AND lastpost >= '$srchfrom'";
$sql2 .= " AND fid='$srchfid' AND dateline >= '$srchfrom'";
}
elseif($srchuname != "" && $srchfid != "all" && $srchtxt != "") {
$sql1 .= " AND fid='$srchfid' AND lastpost >= '$srchfrom'";
$sql2 .= " AND fid='$srchfid' AND dateline >= '$srchfrom'";
}

if($srchtxt != "" && $srchuname != "") {
$sql1 .= " AND author='$srchuname' AND lastpost >= '$srchfrom'";
$sql2 .= " AND author='$srchuname' AND dateline >= '$srchfrom'";
}

$query1 = mysql_query($sql1) or die(mysql_error());
$query2 = mysql_query($sql2) or die(mysql_error());

if($srchin == "both" || $srchin == "topic") {
$threadcount = mysql_num_rows($query1);
while($thread = mysql_fetch_array($query1)) {
$date = date("$dateformat",$thread[dateline]);
$time = date("$timecode",$thread[dateline]);
$poston = "$date "._TEXTAT." $time";
$thread[subject] = stripslashes($thread[subject]);

$queryforum = mysql_query("SELECT * FROM $table_forums WHERE type='forum' AND fid='$thread[fid]'") or die(mysql_error());
$forum = mysql_fetch_array($queryforum);

if($forum[private] == "staff" && $modmin == "true") { 
$authorization = "true";
}
elseif($forum[private] != "staff") { 
$authorization = "true";
}
elseif($forum[userlist] != "") {

if(eregi("(^|[:space:])".$thisuser."(,|$)", $forum[userlist])) {
$authorization = "true";
}
else {
$authorization = "no";
}

}
else {
$authorization = "no";
}

if($authorization == "true") { 
?>
<tr class="tablerow">
<td bgcolor="<?=$altbg1?>"><a href="modules.php?op=modload&name=XForum&file=viewthread&tid=<?=$thread[tid]?>"><?=$thread[subject]?></a></td>
<td bgcolor="<?=$altbg2?>"><?=_TEXTTOPIC?></td>
<td bgcolor="<?=$altbg1?>"><?=$poston?></td>
</tr>
<?
}
}
}

if($srchin != "both" || $srchin != "reply") {
$postcount = mysql_num_rows($query2);
while($post = mysql_fetch_array($query2)) {
$date = date("$dateformat",$post[dateline]);
$time = date("$timecode",$post[dateline]);
$poston = "$date "._TEXTAT." $time";

$infoquery = mysql_query("SELECT * FROM $table_threads WHERE tid='$post[tid]'") or die(mysql_error());
$threadinfo = mysql_fetch_array($infoquery);
$threadinfo[subject] = stripslashes($threadinfo[subject]);


$queryforum = mysql_query("SELECT * FROM $table_forums WHERE type='forum' AND fid='$post[fid]'") or die(mysql_error());
$forum = mysql_fetch_array($queryforum);

if($forum[private] == "staff" && $modmin == "true") { 
$authorization = "true";
}
elseif($forum[private] != "staff") { 
$authorization = "true";
}
elseif($forum[userlist] != "") {

if(eregi("(^|[:space:])".$thisuser."(,|$)", $forum[userlist])) {
$authorization = "true";
}
else {
$authorization = "no";
}

}
else {
$authorization = "no";
}

if($authorization == "true") { 
?>
<tr class="tablerow">
<td bgcolor="<?=$altbg1?>"><a href="modules.php?op=modload&name=XForum&file=viewthread&tid=<?=$threadinfo[tid]?>#pid<?=$post[pid]?>"><?=$threadinfo[subject]?></a></td>
<td bgcolor="<?=$altbg2?>"><?=_TEXTREPLY?></td>
<td bgcolor="<?=$altbg1?>"><?=$poston?></td>
</tr>
<?
}
}
#DM###:.
echo "</table></td></tr></table>";
#FM###:.
}

if($threadcount == "0" && $postcount == "0") {
echo "<tr><td bgcolor=\"$altbg1\" colspan=\"3\"><span class=\"12px \">"._NORESULTS."</span></td></tr>";
}
     
}
}

if($action == 'online') {

$miscaction = _WHOSONLINE;
misctemplate($miscaction, $lastvisita, $thisuser, $cplink, $lastvisittext, $langfile);
?>
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td><?=_TEXTUSERNAME?></td>
<td><?=_TEXTTIME?></td>
<td><?=_TEXTLOCATION?></td>

<? 
if($status == "Administrator") { 
echo "<td>"._TEXTIPADDRESS."</td>";
} 
?>
</tr>
<?
$query = mysql_query("SELECT * FROM $table_whosonline WHERE username != 'onlinerecord' ORDER BY time DESC") or die(mysql_error());
while ($online = mysql_fetch_array($query)){
$onlinetime = date("$timecode",$online[time] + ($timeoffset * 3600));

$username = str_replace("xguest123", _TEXTGUEST1, $online[username]);

if($online[username] != "xguest123") {
$online[username] = "<a href=\"modules.php?op=modload&name=XForum&file=member&action=viewpro&member=$online[username]\">$username</a>";
}
else {
$online[username] = $username;
}
?>
<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td width="22%"><?=$online[username]?></td>
<td width="28%"><?=$onlinetime?></td>
<td><?=$online[location]?></td>

<? 
if($status == "Administrator") { 
echo "<td>$online[ip]</td>";
} 
?> 
</tr>
<?
}
echo "</table>
</td></tr></table>";
}


if($action == "list") {
$miscaction = _TEXTMEMBERLIST;
misctemplate($miscaction, $lastvisita, $thisuser, $cplink, $lastvisittext, $langfile);

if($memliststatus != "on") {
echo _MEMLISTOFF;
exit;
}
?>
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr>
<td width="20%" class="header"><?=_TEXTUSERNAME?></td>
<td width="10%" class="header"><?=_TEXTEMAIL?></td>
<td width="10%" class="header"><?=_TEXTSITE?></td>
<td class="header"><?=_TEXTLOCATION?></td>
<td class="header"><?=_TEXTREGISTERED?></td>
<td class="header"><?=_TEXTPOSTS?></td>
</tr>

<?
if(!$order) {
$order = "regdate";
}

if($page) {
$start_limit = ($page-1) * $memberperpage;
}
else {
$start_limit = 0;
$page = 1;
}

if($srchmem == "") 
{ 
	$query = mysql_query("SELECT count(uid) as number FROM $table_members") or die(mysql_error()); 
} 
else 
{ 
	$query = mysql_query("SELECT count(uid) as number FROM $table_members WHERE username LIKE '%$srchmem%'") or die(mysql_error());
}
#DM###:.
/*
$num = mysql_resul t($query,0);
*/
#DR###:. Remplcé par -->
$row = mysql_fetch_array($query);
$num = $row[number];
#FM###:.


if($num > $memberperpage) {
$pages = $num / $memberperpage;
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
$fwd_back .= "<a href=\"modules.php?op=modload&name=XForum&file=misc&action=list&page=1\"><<</a>";

for ($i = $from; $i <= $to; $i++) {
if ($i == $page) {
$fwd_back .= "&nbsp;&nbsp;<u><b>$i</b></u>&nbsp;&nbsp;";
} elseif (!$order) {
$fwd_back .= "&nbsp;&nbsp;<a href=\"modules.php?op=modload&name=XForum&file=misc&action=list&page=$i\">$i</a>&nbsp;&nbsp;";
} elseif ($order && !$desc) {
$fwd_back .= "&nbsp;&nbsp;<a href=\"modules.php?op=modload&name=XForum&file=misc&action=list&order=$order&page=$i\">$i</a>&nbsp;&nbsp;";
} elseif ($order && $desc) {
$fwd_back .= "&nbsp;&nbsp;<a href=\"modules.php?op=modload&name=XForum&file=misc&action=list&order=$order&desc=$desc&page=$i\">$i</a>&nbsp;&nbsp;";
}
}

$fwd_back .= "<a href=\"modules.php?op=modload&name=XForum&file=misc&action=list&page=$pages\">>></a>";
$multipage = "$backall $backone $fwd_back $forwardone $forwardall";
}

if($order != "regdate" && $order != "username"&& $order != "postnum") {
$order = "regdate";
}

if($srchmem == "") {
$querymem = mysql_query("SELECT * FROM $table_members ORDER BY $order $desc LIMIT $start_limit, $memberperpage") or die(mysql_error());
} else {
$querymem = mysql_query("SELECT * FROM $table_members WHERE username LIKE '%$srchmem%' ORDER BY $order $desc LIMIT $start_limit, $memberperpage") or die(mysql_error());
}

while ($member = mysql_fetch_array($querymem)) {

$member[regdate] = date("n/j/y",$member[regdate]);

if($member[email] != "" && $member[showemail] == "yes") {
$email = "<a href=\"mailto:$member[email]\"><img src=\"modules/XForum/images/email.gif\" border=\"0\" alt=\"E-mail User\" /></a>";
} else {
$email = "&nbsp;";
}

$member[site] = str_replace("http://", "", $member[site]);
$member[site] = "http://$member[site]";

if($member[site] == "http://") {
$site = "&nbsp;";
}
else {
$site = "<a href=\"$member[site]\"><img src=\"modules/XForum/images/site.gif\" border=\"0\" alt=\"Visit User's Homepage\" /></a>";
}

if($member[location] == "") {
$member[location] = "&nbsp;";
}
?>
<tr>
<td bgcolor="<?=$altbg1?>" class="tablerow"><a href="modules.php?op=modload&name=XForum&file=member&action=viewpro&member=<?=rawurlencode($member[username])?>"><?=$member[username]?></a></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><?=$email?></td>
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=$site?></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><?=$member[location]?></td>
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=$member[regdate]?></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><?=$member[postnum]?></td>
</tr>
<?
}
?>

<form method="post" action="modules.php?op=modload&name=XForum&file=misc&action=list">
<tr class="tablerow">
<td bgcolor="<?=$altbg2?>" colspan="6">
<span class="12px"><?=_TEXTSRCHUSR?></span> <input type="text" size="15" name="srchmem"> <input type="submit" value="<?=_TEXTGO?>" />

&nbsp;&nbsp;&nbsp;&nbsp;<b><?=_TEXTOR?></b>&nbsp;&nbsp;&nbsp;&nbsp;
<?=_TEXTSORTBY?> 
<a href="modules.php?op=modload&name=XForum&file=misc&action=list&order=postnum&desc=desc"><?=_TEXTPOSTNUM?></a> - 
<a href="modules.php?op=modload&name=XForum&file=misc&action=list&order=username"><?=_TEXTALPHA?></a> - 
<a href="modules.php?op=modload&name=XForum&file=misc&action=list"><?=_TEXTREGDATE?></a></td></tr></form>

</table>
</td></tr></table>

<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td align="right" class="multi"><?=$multipage?></td></tr></table>

<?
}


if($action == "stats") {
$miscaction = _TEXTSTATS;
misctemplate($miscaction, $lastvisita, $thisuser, $cplink, $lastvisittext, $langfile);

#DM###:.
/*
$query = mysql_query("SELECT COUNT(*) FROM $table_threads") or die(mysql_error());
$threads = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->  "*" par "uid"
$query = mysql_query("SELECT COUNT(tid) as nbsites, SUM(views) as totalus FROM $table_threads") or die(mysql_error());
$row = mysql_fetch_array($query);
$threads = $row[nbsites];
$totalus = $row[totalus];
#FM###:.

#DM###:.
/*
$query = mysql_query("SELECT COUNT(*) FROM $table_posts") or die(mysql_error());
$posts = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->  "*" par "pid"
$query = mysql_query("SELECT COUNT(pid) as nbsites FROM $table_posts") or die(mysql_error());
$row = mysql_fetch_array($query);
$posts = $row[nbsites];
#FM###:.

#DM###:.
/*
$query = mysql_query("SELECT COUNT(*) FROM $table_forums WHERE type='forum'") or die(mysql_error());
$forums = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->  "*" par "fid"
$query = mysql_query("SELECT COUNT(fid) as nbsites FROM $table_forums WHERE type='forum'") or die(mysql_error());
$row = mysql_fetch_array($query);
$forums = $row[nbsites];
#FM###:.

#DM###:.
/*
$query = mysql_query("SELECT COUNT(*) FROM $table_forums WHERE type='forum' AND status='on'") or die(mysql_error());
$forumsa = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->  "*" par "fid"
$query = mysql_query("SELECT COUNT(fid) as nbsites FROM $table_forums WHERE type='forum' AND status='on'") or die(mysql_error());
$row = mysql_fetch_array($query);
$forumsa = $row[nbsites];
#FM###:.

#DM###:.
/*
$query = mysql_query("SELECT COUNT(*) FROM $table_members") or die(mysql_error());
$members = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->  "*" par "uid"
$query = mysql_query("SELECT COUNT(uid) as nbsites FROM $table_members") or die(mysql_error());
$row = mysql_fetch_array($query);
$members = $row[nbsites];
#FM###:.

#DM###:.
/*
$query = mysql_query("SELECT COUNT(*) FROM $table_members WHERE postnum!='0'") or die(mysql_error());
$membersact = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->  "*" par "uid"
$query = mysql_query("SELECT COUNT(uid) as nbsites FROM $table_members WHERE postnum!='0'") or die(mysql_error());
$row = mysql_fetch_array($query);
$membersact = $row[nbsites];
#FM###:.


if ($members != 0) { $mapercent = $membersact*100/$members; }
else { $mapercent =0; }
$mapercent  = number_format($mapercent, 2);
$mapercent .= "%";


if($status == "Administrator" || $status == "Super Moderator" || $status == "Moderator") {
$privauth = "yes";
} else {
$privauth = "no";
}

$query = mysql_query("SELECT fid, userlist FROM $table_forums WHERE private != '' || userlist != ''") or die(mysql_error());
while($priv = mysql_fetch_array($query)) {

if(eregi($thisuser."(,|$)", $priv[userlist]) && $thisuser != "") {
$auth = "yes";
} elseif($privauth == "yes" && $priv[userlist] == "") {
$auth = "yes";
} else {
$auth = "no";
}

if(!$auth || $auth != "yes") {
if(!$addon || $addon == "") {
$addon = "WHERE fid != '$priv[fid]'";
} else {
$addon .= " && fid != '$priv[fid]'";
}
}
}

$query = mysql_query("SELECT views, tid, subject FROM $table_threads $addon ORDER BY views DESC LIMIT 0, 5") or die(mysql_error());
while($views = mysql_fetch_array($query)) {
$viewmost .= "<a href=\"modules.php?op=modload&name=XForum&file=viewthread&tid=$views[tid]\">$views[subject]</a> ($views[views] "._VIEWSL.")<br>";
}

$query = mysql_query("SELECT replies, tid, subject FROM $table_threads $addon ORDER BY replies DESC LIMIT 0, 5") or die(mysql_error());
while($reply = mysql_fetch_array($query)) {
$replymost .= "<a href=\"modules.php?op=modload&name=XForum&file=viewthread&tid=$reply[tid]\">$reply[subject]</a> ($reply[replies] "._REPLIESL.")<br>";
}

#$query = mysql_query("SELECT lastpost, tid, subject FROM $table_threads $addon ORDER BY lastpost DESC LIMIT 0, 5 ") or die(mysql_error());
# My solution 1 $query = mysql_query("SELECT LEFT(lastpost, INSTR(lastpost, '|')-1)*1 as CHlaspost, lastpost, tid, subject FROM $table_threads $addon ORDER BY CHlaspost DESC LIMIT 0, 5 ") or die(mysql_error());
# My solution 2 ->
$query = mysql_query("SELECT IF(LENGTH(LEFT(lastpost, INSTR(lastpost, '|')-1))<10, CONCAT('0',lastpost), lastpost) as  lastpost, tid, subject FROM $table_threads $addon ORDER BY lastpost DESC LIMIT 0, 5 ") or die(mysql_error());
while($last = mysql_fetch_array($query)) {
$lpdate = date("$dateformat", $last[lastpost] + ($timeoffset * 3600));
$lptime = date("$timecode", $last[lastpost] + ($timeoffset * 3600));
#DM###:. -->$thislast = _LPOSTSTATS." "._LASTREPLY1." $lpdate "._TEXTAT." $lptime";
$thislast = _LASTREPLY1." $lpdate "._TEXTAT." $lptime";
$latest .= "<a href=\"modules.php?op=modload&name=XForum&file=viewthread&tid=$last[tid]\">$last[subject]</a> ($thislast)<br>";
}

$query = mysql_query("SELECT posts, threads, fid, name FROM $table_forums ORDER BY posts DESC LIMIT 0, 1") or die(mysql_error());
$pop = mysql_fetch_array($query);
$popforum = "<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$pop[fid]\">$pop[name]</a>";
$pop[posts] += $pop[threads];
$posts += $threads;


$mempost = 0;
$query = mysql_query("SELECT postnum FROM $table_members") or die(mysql_error());
while ($mem = mysql_fetch_array($query)) {
$mempost += $mem[postnum];
}

if ($members != 0) { $mempost = $mempost / $members; }
else { $mempost=0; }
    
$mempost  = number_format($mempost, 2);


$forumpost = 0;
$query = mysql_query("SELECT posts FROM $table_forums") or die(mysql_error());
while ($forum = mysql_fetch_array($query)) 
{ 
	$forumpost += $forum[posts]; 
}

if ($forums != 0) { $forumpost = $forumpost / $forums; }
else { $forumpost=0; }

$forumpost  = number_format($forumpost, 2);


$threadreply = 0;
$query = mysql_query("SELECT replies FROM $table_threads") or die(mysql_error());
while($thread = mysql_fetch_array($query)) 
{
  $threadreply += $thread[replies];
}

if ($threads != 0) { $threadreply = $threadreply / $threads; }
else { $threadreply=0; }
$threadreply  = number_format($threadreply, 2);

#DM###:.
/*
$query = mysql_query("SELECT dateline FROM $table_threads ORDER BY dateline LIMIT 0, 1") or die(mysql_error());
$postdays = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT dateline FROM $table_threads ORDER BY dateline LIMIT 0, 1") or die(mysql_error());
$row = mysql_fetch_array($query);
$postdays = $row[dateline];
#FM###:.

$postsday = $posts / ((time() - $postdays) / 86400);
$postsday  = number_format($postsday, 2);
#DM###:.
/*
$query = mysql_query("SELECT regdate FROM $table_members ORDER BY regdate LIMIT 0, 1") or die(mysql_error());
$memberdays = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT regdate FROM $table_members ORDER BY regdate LIMIT 0, 1") or die(mysql_error());
$row = mysql_fetch_array($query);
$memberdays = $row[regdate];
#FM###:.

$query = mysql_query("SELECT username FROM $table_members ORDER BY regdate DESC") or die(mysql_error());
$lastmem = mysql_fetch_array($query);
$lastmember = $lastmem[username];
$memhtml = "<a href=\"modules.php?op=modload&name=XForum&file=member&action=viewpro&member=".rawurlencode($lastmember)."\"><b>$lastmember</b></a>.";

$membersday = $members / ((time() - $memberdays) / 86400);
$membersday  = number_format($membersday, 2);

eval(_EVALSTATS1);
eval(_EVALSTATS2);
eval(_EVALSTATS3);
eval(_EVALSTATS4);
eval(_EVALSTATS5);
eval(_EVALSTATS51);
eval(_EVALSTATS6);
eval(_EVALSTATS7);
eval(_EVALSTATS8);
eval(_EVALSTATS9);
eval(_EVALSTATS10);
eval(_EVALSTATS11);
eval(_EVALSTATS12);
eval(_EVALSTATS13);
eval(_EVALSTATS14);
eval(_EVALSTATS15);
?>

<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr><td class="header"><?=_TEXTSTATS?></td></tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"><td>
<b><?=_STATS1?></b><br>
<?=_STATS2?><br>
<?=_STATS3?><br>
<?=_STATS4." ".$memhtml;?><br>
<?=_STATS5?><br>
<?=_STATS51?><br><br>
<?=_STATS6?><br><br>
<?=_STATS7?><br><br>
<?=_STATS14?><br><br>
<?=_STATS8?><br><br>

<b><?=_TEXTAVERAGES?></b></br>
<?=_STATS9?><br>
<?=_STATS10?><br>
<?=_STATS11?><br>
<?=_STATS12?><br>
<?=_STATS13?><br>
<?=_STATS15?><br>
</td></tr>

</table>
</td></tr></table>

<?
}

$mtime2 = explode(" ", microtime());
$endtime = $mtime2[1] + $mtime2[0];
if($showtotaltime != "off") { 
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
