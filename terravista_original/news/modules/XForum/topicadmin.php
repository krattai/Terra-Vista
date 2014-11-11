<?
# modif suppr mysql_resul t 20011001 14h09
# modif select * Ligne6 20011108 10h00

require "modules/XForum/header.php";

$query = mysql_query("SELECT type, name, fup, private FROM $table_forums WHERE fid='$fid'") or die(mysql_error());
$forums = mysql_fetch_array($query);

if($tid && $fid) { 
#DM###:.
/*
$query = mysql_query("SELECT subject FROM $table_threads WHERE fid='$fid' AND tid='$tid'") or die(mysql_error());
$threadname = mysql_resul t($query,0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT subject FROM $table_threads WHERE fid='$fid' AND tid='$tid'") or die(mysql_error());
$row = mysql_fetch_array($query);
$threadname = $row[subject];
$threadname = stripslashes($threadname);
#FM###:.

}

if($forums[type] == "forum") {
$postaction = "<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fid\">$forums[name]</a> &gt; <a href=\"modules.php?op=modload&name=XForum&file=viewthread&tid=$tid\">$threadname</a> &gt; ";
} else {
$query = mysql_query("SELECT name, fid FROM $table_forums WHERE fid='$forums[fup]'") or die(mysql_error());
$fup = mysql_fetch_array($query);
$postaction = "<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fup[fid]\">$fup[name]</a> &gt; <a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fid\">$forums[name]</a> &gt; <a href=\"modules.php?op=modload&name=XForum&file=viewthread&tid=$tid\">$threadname</a> &gt; ";
}

if($action == "delete") {
$postaction .= _TEXTDELETETHREAD;
} elseif($action == "top") { 
$postaction .= _TEXTTOPTHREAD; 
} elseif($action == "close") {
$postaction .= _TEXTCLOSETHREAD;
} elseif($action == "move") {
$postaction .= _TEXTMOVETHREAD;
} elseif($action == "getip") {
$postaction .= _TEXTGETIP;
} elseif($action == "bump") { 
$postaction .= _TEXTBUMPTHREAD; 
} elseif($action == "report") { 
$postaction .= _TEXTREPORTPOST; 
}

if($catsonly == "on" && $forums[type] == "sub") {
$query = mysql_query("SELECT fup FROM $table_forums WHERE fid='$forums[fup]'") or die(mysql_error());
$forum1 = mysql_fetch_array($query);
$query = mysql_query("SELECT fid, name FROM $table_forums WHERE fid='$forum1[fup]'") or die(mysql_error());
$cat = mysql_fetch_array($query);
} elseif($catsonly == "on" && $forums[type] == "forum") {
$query = mysql_query("SELECT fid, name FROM $table_forums WHERE fid='$forums[fup]'") or die(mysql_error());
$cat = mysql_fetch_array($query);
}

if($catsonly == "on") {
$navigation = "<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; <a href=\"modules.php?op=modload&name=XForum&file=indexnav&gid=$cat[fid]\">$cat[name]</a> &gt; $postaction";
} else {
$navigation = "<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; $postaction";
}

$html = template("header.html");
eval("echo stripslashes(\"$html\");");

if($forums[private] == "staff" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator") {
echo "<div class=\"tablerow\">"._PRIVFORUMMSG."</div>";
exit;
}

if($action == "delete") {
if(!$deletesubmit) {
?>

<form method="post" action="modules.php?op=modload&name=XForum&file=topicadmin&action=delete">
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTDELETETHREAD?></td>
</tr>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td width="22%"><?=_TEXTUSERNAME?></td>
<td><input type="text" name="username" size="30" maxlength="40" value="<?=$thisuser?>" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTPASSWORD?></td>
<td><input type="password" name="password" size="25" value="<?=$thispw?>" /></td>
</tr>

</table>
</td></tr></table>
<input type="hidden" name="fid" value="<?=$fid?>" />
<input type="hidden" name="tid" value="<?=$tid?>" />
<center><input type="submit" name="deletesubmit" value="<?=_TEXTDELETETHREAD?>" /></center>
</form>

<?
}

if($deletesubmit) {
$query = mysql_query("SELECT username, password, status FROM $table_members WHERE username='$username'") or die(mysql_error());
$member = mysql_fetch_array($query);
$status = $member[status];

if(!$member[username]) {
echo "<span class=\"12px \">"._BADNAME."</span>";
exit;
}

if($password != $member[password]) {
echo "<span class=\"12px \">"._TEXTPWINCORRECT."</span>";
exit;
}

$status1 = modcheck($status, $username, $fid, $table_forums);

if($status == "Super Moderator") {
$status1 = "Moderator";
}

if($status != "Administrator" && $status1 != "Moderator") {
echo _TEXTNOACTION;
exit;
}
#DM###:.
/*
$count = mysql_query("SELECT COUNT(*) FROM $table_posts WHERE tid='$tid'") or die(mysql_error()); 
$subtract = mysql_resul t($count, 0);
*/
#DR###:. Remplcé par -->
$count = mysql_query("SELECT COUNT(pid) as nbsites FROM $table_posts WHERE tid='$tid'") or die(mysql_error()); 
$row = mysql_fetch_array($query);
$subtract = $row[nbsites];
#FM###:.

$subtract++;

$query = mysql_query("SELECT type, fup FROM $table_forums WHERE fid='$fid'") or die(mysql_error()); 
$for = mysql_fetch_array($query);

$query = mysql_query("SELECT author FROM $table_posts WHERE tid='$tid'") or die(mysql_error());
while ($result = mysql_fetch_array($query)) {
mysql_query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$result[author]'") or die(mysql_error()); 
}
#DM###:.
/*
$query = mysql_query("SELECT author FROM $table_threads WHERE tid='$tid'") or die(mysql_error());
$origauthor = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT author FROM $table_threads WHERE tid='$tid'") or die(mysql_error());
$row = mysql_fetch_array($query);
$origauthor = $row[author];
#FM###:.

mysql_query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$origauthor'") or die(mysql_error()); 
mysql_query("DELETE FROM $table_threads WHERE tid='$tid'") or die(mysql_error()); 
mysql_query("DELETE FROM $table_posts WHERE tid='$tid'") or die(mysql_error()); 
mysql_query("UPDATE $table_forums SET threads=threads-1, posts=posts-'$subtract' WHERE fid='$fid'") or die(mysql_error());

if($for[type] == "sub") {
mysql_query("UPDATE $table_forums SET threads=threads-1, posts=posts-'$subtract' WHERE fid='$for[fup]'") or die(mysql_error());
}

echo "<span class=\"12px \">"._DELETETHREADMSG."</span>";
?>
<script> 
function redirect()
{ 
window.location.replace("modules.php?op=modload&name=XForum&file=forumdisplay&fid=<?=$fid?>"); 
} 
setTimeout("redirect();", 1250); 
</script>
<?
}
}

if($action == "close") {

#DM###:.
/*
$query = mysql_query("SELECT closed FROM $table_threads WHERE fid='$fid' AND tid='$tid'") or die(mysql_error());
$closed = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT closed FROM $table_threads WHERE fid='$fid' AND tid='$tid'") or die(mysql_error());
$row = mysql_fetch_array($query);
$closed = $row[closed];
#FM###:.


if($closed == "yes")	{ define("_textclosethread", "_textopenthread"); }
elseif($closed == "")	{ define("_textclosethread", "_textclosethread");}

if(!$closesubmit) {
?>

<form method="post" action="modules.php?op=modload&name=XForum&file=topicadmin&action=close">
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTCLOSETHREAD?></td>
</tr>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td width="22%"><?=_TEXTUSERNAME?></td>
<td><input type="text" name="username" size="30" maxlength="40" value="<?=$thisuser?>" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTPASSWORD?></td>
<td><input type="password" name="password" size="25" value="<?=$thispw?>" /></td>
</tr>

</table>
</td></tr></table>
<input type="hidden" name="fid" value="<?=$fid?>" />
<input type="hidden" name="tid" value="<?=$tid?>" />
<center><input type="submit" name="closesubmit" value="<?=_TEXTCLOSETHREAD?>" /></center>
</form>

<?
}

if($closesubmit) {
$query = mysql_query("SELECT username, password, status FROM $table_members WHERE username='$username'") or die(mysql_error());
$member = mysql_fetch_array($query);
$status = $member[status];

if(!$member[username]) {
echo "<span class=\"12px \">"._BADNAME."</span>";
exit;
}

if($password != $member[password]) {
echo "<span class=\"12px \">"._TEXTPWINCORRECT."</span>";
exit;
}

$status1 = modcheck($status, $username, $fid, $table_forums);

if($status == "Super Moderator") {
$status1 = "Moderator";
}

if($status != "Administrator" && $status1 != "Moderator") {
echo _TEXTNOACTION;
exit;
}

if($closed == "yes") {
mysql_query("UPDATE $table_threads SET closed='' WHERE tid='$tid' AND fid='$fid'") or die(mysql_error());
}
elseif($closed == "") {
mysql_query("UPDATE $table_threads SET closed='yes' WHERE tid='$tid' AND fid='$fid'") or die(mysql_error());
}

echo "<span class=\"12px \">"._CLOSETHREADMSG."</span>";
?>
<script> 
function redirect()
{ 
window.location.replace("modules.php?op=modload&name=XForum&file=forumdisplay&fid=<?=$fid?>"); 
} 
setTimeout("redirect();", 1250); 
</script>
<?
}
}


if($action == "move") {
if(!$movesubmit) {

$forumselect = "<select name=\"moveto\">\n";
$queryfor = mysql_query("SELECT * FROM $table_forums WHERE fup='' AND type='forum' ORDER BY displayorder") or die(mysql_error());
while($forum = mysql_fetch_array($queryfor)) {
$forumselect .= "<option value=\"$forum[fid]\"> &nbsp; &gt; $forum[name]</option>";

$querysub = mysql_query("SELECT * FROM $table_forums WHERE fup='$forum[fid]' AND type='sub' ORDER BY displayorder") or die(mysql_error());
while($sub = mysql_fetch_array($querysub)) {
$forumselect .= "<option value=\"$sub[fid]\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &gt; $sub[name]</option>";
}
$forumselect .= "<option value=\"\"> </option>";
}

$querygrp = mysql_query("SELECT * FROM $table_forums WHERE type='group' ORDER BY displayorder") or die(mysql_error());
while($group = mysql_fetch_array($querygrp)) {
$forumselect .= "<option value=\"\">$group[name]</option>";
$forumselect .= "<option value=\"\">--------------------</option>";

$queryfor = mysql_query("SELECT * FROM $table_forums WHERE fup='$group[fid]' AND type='forum' ORDER BY displayorder") or die(mysql_error());
while($forum = mysql_fetch_array($queryfor)) {
$forumselect .= "<option value=\"$forum[fid]\"> &nbsp; &gt; $forum[name]</option>";

$querysub = mysql_query("SELECT * FROM $table_forums WHERE fup='$forum[fid]' AND type='sub' ORDER BY displayorder") or die(mysql_error());
while($sub = mysql_fetch_array($querysub)) {
$forumselect .= "<option value=\"$sub[fid]\">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &gt; $sub[name]</option>";
}
}
$forumselect .= "<option value=\"\"> </option>";
}
$forumselect .= "</select>";
?>

<form method="post" action="modules.php?op=modload&name=XForum&file=topicadmin&action=move">
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTMOVETHREAD?></td>
</tr>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td width="22%"><?=_TEXTUSERNAME?></td>
<td><input type="text" name="username" size="30" maxlength="40" value="<?=$thisuser?>" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTPASSWORD?></td>
<td><input type="password" name="password" size="25" value="<?=$thispw?>" /></td>
</tr>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td><?=_TEXTMOVETO?></td>
<td><?=$forumselect?></td>
</tr>

</table>
</td></tr></table>
<input type="hidden" name="fid" value="<?=$fid?>" />
<input type="hidden" name="tid" value="<?=$tid?>" />
<center><input type="submit" name="movesubmit" value="<?=_TEXTMOVETHREAD?>" /></center>
</form>

<?
}

if($movesubmit) {
$query = mysql_query("SELECT username, password, status FROM $table_members WHERE username='$username'") or die(mysql_error());
$member = mysql_fetch_array($query);
$status = $member[status];

if(!$member[username]) {
echo "<span class=\"12px \">"._BADNAME."</span>";
exit;
}

if($password != $member[password]) {
echo "<span class=\"12px \">".textpwincorrect."</span>";
exit;
}

$status1 = modcheck($status, $username, $fid, $table_forums);

if($status == "Super Moderator") {
$status1 = "Moderator";
}

if($status != "Administrator" && $status1 != "Moderator") {
echo _TEXTNOACTION;
exit;
}

$query = mysql_query("SELECT type, fid, fup FROM $table_forums WHERE fid='$moveto'") or die(mysql_error());
$move = mysql_fetch_array($query);

$query = mysql_query("SELECT type, fid, fup FROM $table_forums WHERE fid='$fid'") or die(mysql_error());
$this = mysql_fetch_array($query);

#DM###:.
/*
$query = mysql_query("SELECT COUNT(*) FROM $table_posts WHERE tid='$tid'") or die(mysql_error()); 
$subtract = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT COUNT(pid) as nbsites FROM $table_posts WHERE tid='$tid'") or die(mysql_error()); 
$row = mysql_fetch_array($query);
$subtract = $row[nbsites];
#FM###:.

$subtract++;

mysql_query("UPDATE $table_threads SET fid='$moveto' WHERE tid='$tid' AND fid='$fid'") or die(mysql_error()); 
mysql_query("UPDATE $table_posts SET fid='$moveto' WHERE tid='$tid' AND fid='$fid'") or die(mysql_error());

mysql_query("UPDATE $table_forums SET threads=threads+1, posts=posts+'$subtract' WHERE fid='$move[fid]'") or die(mysql_error());
mysql_query("UPDATE $table_forums SET threads=threads-1, posts=posts-'$subtract' WHERE fid='$this[fid]'") or die(mysql_error());

if($this[type] == "sub" && $move[type] == "forum") {
mysql_query("UPDATE $table_forums SET threads=threads-1, posts=posts-'$subtract' WHERE fid='$this[fup]'") or die(mysql_error());
}

if($this[type] == "forum" && $move[type] == "sub") {
mysql_query("UPDATE $table_forums SET threads=threads+1, posts=posts+'$subtract' WHERE fid='$move[fup]'") or die(mysql_error());
}


echo "<span class=\"12px \">"._MOVETHREADMSG."</span>";
?>
<script> 
function redirect()
{ 
window.location.replace("modules.php?op=modload&name=XForum&file=forumdisplay&fid=<?=$fid?>"); 
} 
setTimeout("redirect();", 1250); 
</script>
<?
}
}

if($action == "top") {

#DM###:.
/*
$query = mysql_query("SELECT topped FROM $table_threads WHERE fid='$fid' AND tid='$tid'") or die(mysql_error());
$topped = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT topped FROM $table_threads WHERE fid='$fid' AND tid='$tid'") or die(mysql_error());
$row = mysql_fetch_array($query);
$topped = $row[topped];
#FM###:.


if($topped == "1")		{ define("_texttopthread", _TEXTUNTOPTHREAD); } 
elseif($topped == "0")	{ define("_texttopthread]", _TEXTTOPTHREAD); }

if(!$topsubmit) { 
?> 

<form method="post" action="modules.php?op=modload&name=XForum&file=topicadmin&action=top"> 
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center"> 
<tr><td bgcolor="<?=$bordercolor?>"> 

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"> 
<tr class="header"> 
<td colspan="2"><?= _TEXTTOPTHREAD?></td> 
</tr> 

<tr bgcolor="<?=$altbg1?>" class="tablerow"> 
<td width="22%"><?=_TEXTUSERNAME?></td> 
<td><input type="text" name="username" size="30" maxlength="40" value="<?=$thisuser?>" /></td> 
</tr> 

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTPASSWORD?></td> 
<td><input type="password" name="password" size="25" value="<?=$thispw?>" /></td> 
</tr> 


</table> 
</td></tr></table> 
<input type="hidden" name="fid" value="<?=$fid?>" /> 
<input type="hidden" name="tid" value="<?=$tid?>" /> 
<center><input type="submit" name="topsubmit" value="<?=_TEXTTOPTHREAD?>" /></center> 
</form> 

<? 
} 
if($topsubmit) { 
$query = mysql_query("SELECT username, password, status FROM $table_members WHERE username='$username'") or die(mysql_error());
$member = mysql_fetch_array($query);
$status = $member[status];

if(!$member[username]) {
echo "<span class=\"12px \">"._BADNAME."</span>";
exit;
}

if($password != $member[password]) {
echo "<span class=\"12px \">"._TEXTPWINCORRECT."</span>";
exit;
}

$status1 = modcheck($status, $username, $fid, $table_forums);

if($status == "Super Moderator") {
$status1 = "Moderator";
}

if($status != "Administrator" && $status1 != "Moderator") {
echo _TEXTNOACTION;
exit;
}

if($topped == "1") {
mysql_query("UPDATE $table_threads SET topped='0' WHERE tid='$tid' AND fid='$fid'");
}
elseif($topped == "0") {
mysql_query("UPDATE $table_threads SET topped='1' WHERE tid='$tid' AND fid='$fid'");
}

echo "<span class=\"12px \">"._TOPTHREADMSG."</span>";
?> 
<script> 
function redirect()
{ 
window.location.replace("modules.php?op=modload&name=XForum&file=forumdisplay&fid=<?=$fid?>"); 
} 
setTimeout("redirect();", 1250); 
</script>
<? 
} 
}

if($action == "getip") { 

if(!$pid) { 
$query = mysql_query("SELECT * FROM $table_threads WHERE tid='$tid'") or die(mysql_error()); 
} 
else { 
$query = mysql_query("SELECT * FROM $table_posts WHERE pid='$pid' AND tid='$tid'") or die(mysql_error()); 
} 

$ipinfo = mysql_fetch_array($query); 

$query = mysql_query("SELECT status FROM $table_members WHERE username='$thisuser'") or die(mysql_error()); 
$status = mysql_fetch_array($query); 
$status = $status[status]; 


$status1 = modcheck($status, $username, $fid, $table_forums);

if($status == "Super Moderator") {
$status1 = "Moderator";
}

if($status != "Administrator" && $status1 != "Moderator") { 
echo "<span class=\"12px \">"._TEXTNOIP."</span>";
} 
else { 

?> 
<table cellspacing="0" cellpadding="0" border="0" width="60%" align="center"> 
<tr><td bgcolor="<?=$bordercolor?>"> 
<form method="post" action="modules.php?op=modload&name=XForum&file=cp&action=ipban"> 
<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"> 

<tr> 
<td class="header" colspan="3"><?=_TEXTGETIP?></td> 
</tr> 
<tr bgcolor="<?=$altbg2?>"> 
<td class="tablerow"><?=_TEXTYESIP?> <b><?=$ipinfo[useip]?></b> 
<? 

if($status == "Administrator") { 

$ip = explode(".", $ipinfo[useip]); 
$query = mysql_query("SELECT * FROM $table_banned WHERE (ip1='$ip[0]' OR ip1='-1') AND (ip2='$ip[1]' OR ip2='-1') AND (ip3='$ip[2]' OR ip3='-1') AND (ip4='$ip[3]' OR ip4='-1')") or die(mysql_error()); 
$result = mysql_fetch_array($query); 

if ($result) { 
$buttontext = _TEXTUNBANIP; 

for($i=1; $i<=4; ++$i) { 
$j = "ip$i"; 
if ($result[$j] == -1) { 
$result[$j] = "*"; 
$foundmask = 1; 
} 
} 
if ($foundmask) { 
$ipmask = "<b>$result[ip1].$result[ip2].$result[ip3].$result[ip4]</b>"; 
eval(_evalipmask); 
echo _BANNEDIPMASK; 
} 

else { 
echo _TEXTBANNEDIP; 
} 

echo "<input type=\"hidden\" name=\"delete$result[id]\" value=\"$result[id]\" />"; 
} 

else { 
$buttontext = _TEXTBANIP; 
for($i=1; $i<=4; ++$i) { 
$j = $i - 1; 
echo "<input type=\"hidden\" name=\"newip$i\" value=\"$ip[$j]\" />"; 
} 

} 
?> 
</td> 
<tr bgcolor="<?=$altbg1?>"><td class="tablerow"> 
<center><input type="submit" name="ipbansubmit" value="<?=$buttontext?>" /></center> 

<? 
} 

echo "</td></tr></table></td></tr></table></form>"; 
} 
}


if($action == "bump") { 
if(!$bumpsubmit) { 
?> 

<form method="post" action="modules.php?op=modload&name=XForum&file=topicadmin&action=bump"> 
<table cellspacing="0" cellpadding="0" border="0" width="90%" align="center"> 
<tr><td bgcolor="<?=$bordercolor?>"> 

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"> 
<tr class ="header"> 
<td colspan="2"><?=_TEXTBUMPTHREAD?></td> 
</tr> 

<tr bgcolor="<?=$altbg1?>" class ="tablerow"> 
<td width="22%"><?=_TEXTUSERNAME?></td> 
<td><input type="text" name="username" size="30" maxlength="40" value="<?=$thisuser?>" /></td> 
</tr> 

<tr bgcolor="<?=$altbg2?>" class ="tablerow"> 
<td><?=_TEXTPASSWORD?></td> 
<td><input type="password" name="password" size="25" value="<?=$thispw?>" /></td> 
</tr> 

</table> 
</td></tr></table> 
<input type="hidden" name="fid" value="<?=$fid?>" /> 
<input type="hidden" name="tid" value="<?=$tid?>" /> 
<center><input type="submit" name="bumpsubmit" value="<?=_TEXTBUMPTHREAD?>" /></center> 
</form> 

<? 
} 

if($bumpsubmit) { 
$query = mysql_query("SELECT username, password, status FROM $table_members WHERE username='$username'") or die(mysql_error()); 
$member = mysql_fetch_array($query); 
$status = $member[status]; 

if(!$member[username]) {
echo "<span class=\"12px \">"._BADNAME."</span>";
exit;
}

if($password != $member[password]) {
echo "<span class=\"12px \">"._TEXTPWINCORRECT."</span>";
exit;
}

$status1 = modcheck($status, $username, $fid, $table_forums);

if($status == "Super Moderator") {
$status1 = "Moderator";
}

if($status != "Administrator" && $status1 != "Moderator") {
echo _TEXTNOACTION;
exit;
}

mysql_query("UPDATE $table_threads SET lastpost='" . time() . "|$username' WHERE tid=$tid AND fid=$fid") or die(mysql_error()); 
mysql_query("UPDATE $table_forums SET lastpost='" . time() . "|$username' WHERE fid=$fid") or die(mysql_error()); 

echo "<span class=\"12px \">"._BUMPTHREADMSG."</span>";
?> 
<script> 
function redirect()
{ 
window.location.replace("modules.php?op=modload&name=XForum&file=forumdisplay&fid=<?=$fid?>"); 
} 
setTimeout("redirect();", 1250); 
</script>
<? 
} 
}


if($action == "report") 
{

  if($reportpost == "off") 
  { 
	echo "<span class=\"12px \">"._REPORTPOSTDISABLED."</span>"; 
	exit; 
  }

  if(!$reportsubmit) 
  {
	
?>

	<form method="post" name="input" action="modules.php?op=modload&name=XForum&file=topicadmin&action=report">
	<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
	<tr><td bgcolor="<?=$bordercolor?>">

	<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
	<tr class="header">
	<td colspan="2"><?=_TEXTREPORTPOST?></td>
	</tr>

	<tr class="tablerow">
	<td bgcolor="<?=$altbg1?>" valign="top" width="19%"><?=_TEXTREASON?></td>
	<td bgcolor="<?=$altbg2?>"><textarea rows="9" cols="45" name="reason"></textarea>
	</tr>

	</table>
	</td></tr></table>
	<input type="hidden" name="tid" value="<?=$tid?>">
	<input type="hidden" name="fid" value="<?=$fid?>">

<?
if($pid) {
?>
<input type="hidden" name="pid" value="<?=$pid?>">
<?
}
?>
<center><input type="submit" name="reportsubmit" value="<?=_TEXTREPORTPOST?>" /></center>
</form>
<?
}


  if($reportsubmit) 
  {
	if($pid) 
	{
	  $posturl = $siteurl . "/modules.php?op=modload&name=XForum&file=viewthread&tid=$tid#pid$pid";
	  $posturl = preg_replace("/\/\/mod/","/mod",$posturl);
	} 
	else 
	{
	  $posturl = $siteurl . "/modules.php?op=modload&name=XForum&file=viewthread&tid=$tid";
	  $posturl = preg_replace("/\/\/mod/","/mod",$posturl);
	}

	$message = addslashes(_REPORTMESSAGE." $posturl \n"._TEXTREASON."\n $reason");
	
	$query  = mysql_query("SELECT email FROM $table_members WHERE username='$thisuser'") or die(mysql_error());
	$resreq = mysql_fetch_array($query);
	$mailfrom = $resreq[email];


	$query = mysql_query("SELECT moderator FROM $table_forums WHERE fid='$fid'") or die(mysql_error());
	$forum = mysql_fetch_array($query);
	
	// Recup liste des moderateurs
	$mods = explode(", ", $forum[moderator]);
	for($num = 0; $num < 10; $num++) 
	{
	  if($mods[$num] != "") 
	  {
		mail("$member[email]", _REPORTSUBJECT, "$message", _TEXTFROM." $mailfrom"); 
		#mysql_query("INSERT INTO $table_u2u VALUES('', '$mods[$num]', '$thisuser', '" . time() . "', _REPORTSUBJECT, '$message', 'inbox')") or die(mysql_error());
	  }
	}

	// Recup liste des administrateurs
	$query = mysql_query("SELECT username, email FROM $table_members WHERE status='Administrator'") or die(mysql_error());
	while($member = mysql_fetch_array($query)) 
	{
	  if($member[username] != "") 
	  {
		$message=stripslashes($message);
		mail("$member[email]", _REPORTSUBJECT, "$message", _TEXTFROM." $mailfrom"); 
		#mysql_query("INSERT INTO $table_u2u VALUES('', '$member[username]', '$thisuser', '" . time() . "', _REPORTSUBJECT, '$message', 'inbox')") or die(mysql_error());
	  }
	}
	echo "<span class=\"12px \">"._REPORTMSG."</span>";
?> 
	<script> 
	function redirect()
	{ 
	  window.location.replace("modules.php?op=modload&name=XForum&file=viewthread&tid=<?=$tid?>"); 
	} 
	setTimeout("redirect();", 1250); 
	</script>
<? 
	}
  }

$mtime2 = explode(" ", microtime());
$endtime = $mtime2[1] + $mtime2[0];
if($showtotaltime != "off") 
{ 
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