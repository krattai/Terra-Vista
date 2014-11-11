<?
# modif suppr mysql_resul t 20011001 14h01
# modif ligne 729.. $therurl corrige le bug d'envoi 20011002 11h50
# modif ligne 143.. $piconstaus 20011020 23h50

require "modules/XForum/header.php";

#DM###:.
if ($username != _TEXTGUEST)
{
	$username=$thisuser;
	$password=$thispw;
}
#FM###:.

$query = mysql_query("SELECT * FROM $table_forums WHERE fid='$fid'") or die(mysql_error());
$forums = mysql_fetch_array($query);

if($forums[type] != "forum" && $forums[type] != "sub" && $forums[fid] != $fid) 
  { $posterror = _TEXTNOFORUM; }

if($tid && $fid) { 
#DM###:.
/*
$query = mysql_query("SELECT COUNT(*) FROM $table_whosonline WHERE username!='onlinerecord'") or die(mysql_error()); 
$count = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT COUNT(*) as nbsites FROM $table_whosonline WHERE username!='onlinerecord'") or die(mysql_error()); 
$row = mysql_fetch_array($query);
$count = $row[nbsites];
#FM###:.

#DM###:.
/*
$query = mysql_query("SELECT subject FROM $table_threads WHERE fid='$fid' AND tid='$tid'") or die(mysql_error());
$threadname = mysql_resul t($query,0);
$threadname = stripslashes($threadname);
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

if	  ($action == "reply" && !$previewpost)	{ $postaction .= _TEXTPOSTREPLY; }
elseif($action == "reply" && $previewpost)	{ $postaction .= _TEXTPREVIEW; }
elseif($action == "edit")					{ $postaction .= _TEXTEDITPOST; }
elseif($action == "rate")					{ $postaction .= _RATETHREAD; }

if($action == "newtopic") 
{
  if($forums[type] == "forum") 
  {
	$postaction = "<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fid\">$forums[name]</a> &gt; ";
  } 
  else 
  {
	$query = mysql_query("SELECT name, fid FROM $table_forums WHERE fid='$forums[fup]'") or die(mysql_error());
	$fup = mysql_fetch_array($query);
	$postaction = "<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fup[fid]\">$fup[name]</a> &gt; <a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fid\">$forums[name]</a> &gt; ";
  }
}

if($action == "newtopic" && !$previewpost)		{ $postaction .= _TEXTPOSTNEW; }
elseif($action == "newtopic" && $previewpost)	{ $postaction .= _TEXTPREVIEW; }

if($catsonly == "on" && $forums[type] == "sub") 
{
	$query = mysql_query("SELECT fup FROM $table_forums WHERE fid='$forums[fup]'") or die(mysql_error());
	$forum1 = mysql_fetch_array($query);
	$query = mysql_query("SELECT fid, name FROM $table_forums WHERE fid='$forum1[fup]'") or die(mysql_error());
	$cat = mysql_fetch_array($query);
} 
elseif($catsonly == "on" && $forums[type] == "forum") 
{
  $query = mysql_query("SELECT fid, name FROM $table_forums WHERE fid='$forums[fup]'") or die(mysql_error());
  $cat = mysql_fetch_array($query);
}

if($catsonly == "on") 
  { $navigation = "<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; <a href=\"modules.php?op=modload&name=XForum&file=indexnav&gid=$cat[fid]\">$cat[name]</a> &gt; $postaction"; } 
else 
  { $navigation = "<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; $postaction"; }

$html = template("header.html");
eval("echo stripslashes(\"$html\");");

if($status == "Banned") { echo _BANNEDMESSAGE; }

#DM### Original ->
/*
if($piconstatus == "on") 
{
  $listed_icons = 0; 
  $querysmilie = mysql_query("SELECT url FROM $table_smilies WHERE type='picon'") or die(mysql_error()); 
  while($smilie = mysql_fetch_array($querysmilie)) 
  {
	$icons .= " <input type=\"radio\" name=\"posticon\" value=\"".$smilie[url]."\" /><img src=\"modules/XForum/images/".$smilie[url]."\" />  "; 
	$listed_icons += 1; 
	if($listed_icons == 8) 
	{
	  $icons .= "<br />"; 
	  $listed_icons = 0; 
	}
  }

  $listed_icons = 0; 
  while($smilie = mysql_fetch_array($querysmilie)) 
  {
	if($posticon == $smilie[url]) 
	{
	  $icons1 .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\"checked=\"checked\" /><img src=\"modules/XForum/images/$smilie[url]\" />  ";
	} 
	else 
	{
	  $icons1 .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\" /><img src=\"modules/XForum/images/$smilie[url]\" />  ";
	}
	$listed_icons += 1; 
	if($listed_icons == 8) 
	{
	  $icons1 .= "<br />"; 
	  $listed_icons = 0; 
	}
  }


}
*/

if($piconstatus == "on") 
{
  $listed_icons = 0; 
  $querysmilie = mysql_query("SELECT url FROM $table_smilies WHERE type='picon'") or die(mysql_error()); 
  while($smilie = mysql_fetch_array($querysmilie)) 
  {

	if($posticon == $smilie[url]) 
	{
	  $icons1 .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\"checked=\"checked\" /><img src=\"modules/XForum/images/$smilie[url]\" />  ";
	} 
	else 
	{
	  $icons1 .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\" /><img src=\"modules/XForum/images/$smilie[url]\" />  ";
	  $icons .= " <input type=\"radio\" name=\"posticon\" value=\"".$smilie[url]."\" /><img src=\"modules/XForum/images/".$smilie[url]."\" />  "; 
	}
	$listed_icons += 1; 
	if($listed_icons == 8) 
	{
	  $icons .= "<br />"; 
	  $listed_icons = 0; 
	}
  }

}


/*
if($piconstatus == "on")
{
  $listed_icons = 0; 
  $querysmilie = mysql_query("SELECT * FROM $table_smilies WHERE type='picon'") or die(mysql_error()); 
  while($smilie = mysql_fetch_array($querysmilie)) 
  {
	if($posticon == $smilie[url]) 
	{
	  $icons1 .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\"checked=\"checked\" /><img src=\"modules/XForum/images/$smilie[url]\" />  ";
	} 
	else 
	{
	  $icons1 .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\" /><img src=\"modules/XForum/images/$smilie[url]\" />  ";
	}
	$listed_icons += 1; 
	if($listed_icons == 8) 
	{
	  $icons1 .= "<br />"; 
	  $listed_icons = 0; 
	}
  }
}
*/
if($forums[allowimgcode] == "yes") { 
$allowimgcode = _TEXTON; 
} else { 
$allowimgcode = _TEXTOFF; 
}

if($forums[allowhtml] == "yes") {
$allowhtml = _TEXTON;
} else {
$allowhtml = _TEXTOFF;
}

if($forums[allowsmilies] == "yes") {
$allowsmilies = _TEXTON;
} else {
$allowsmilies = _TEXTOFF;
}

if($forums[allowbbcode] == "yes") {
$allowbbcode = _TEXTON;
} else {
$allowbbcode = _TEXTOFF;
}

if($forums[guestposting] == "yes") { 
if(!$thisuser || $thisuser == "") { 
$bbpostuser = _TEXTGUEST;
} else { 
$bbpostuser = $thisuser;
}
} else { 
$bbpostuser = $thisuser;
}

$pperm = explode("|", $forums[postperm]);

if($pperm[0] == "1") {
$whopost1 = _WHOCANPOST11;
} elseif($pperm[0] == "2") {
$whopost1 = _WHOCANPOST12;
} elseif($pperm[0] == "3") {
$whopost1 = _WHOCANPOST13;
} elseif($pperm[0] == "4") {
$whopost1 = _WHOCANPOST14;
}

if($pperm[1] == "1") {
$whopost2 = _WHOCANPOST21;
} elseif($pperm[1] == "2") {
$whopost2 = _WHOCANPOST22;
} elseif($pperm[1] == "3") {
$whopost2 = _WHOCANPOST23;
} elseif($pperm[1] == "4") {
$whopost2 = _WHOCANPOST24;
}

if($forums[guestposting] == "yes") {
$whopost3 = _WHOCANPOST31;
} else {
$whopost3 = _WHOCANPOST32;
}

if($pperm[0] == "4" && $pperm[1] == "4") {
$whopost3 = _WHOCANPOST32;
}

if($thisuser && $thisuser != '') 
{
  $query = mysql_query("SELECT sig FROM $table_members WHERE username='$thisuser'") or die(mysql_error());
  $this = mysql_fetch_array($query);
  $sig = $this[sig];
  if($sig != "") { $sigcheck = "checked=\"checked\""; }
}

if($forums[private] == "staff" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator") 
{
  echo "<div class=\"tablerow\">"._PRIVFORUMMSG."</div>";
  exit;
}

if($subf[private] == "staff" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator") {
echo "<div class=\"tablerow\">"._PRIVFORUMMSG."</div>";
exit;
}

if($posterror) { 
echo "<div class=\"tablerow\">$posterror</div>"; 
exit;
}


if($action == "newtopic") {
if($previewpost) {

$currtime = time();
$date = gmdate("n/j/y",$currtime + ($timeoffset * 3600));
$time = gmdate("H:i",$currtime + ($timeoffset * 3600));
$poston = _TEXTPOSTON." $date "._TEXTAT." $time";

$subject = stripslashes($subject);
$message = stripslashes($message);
$message1 = postify($message, $smileyoff, $bbcodeoff, $fid, $bordercolor, "", "", $table_words, $table_forums, $table_smilies);

if($smileyoff == "yes") {
$smileoffcheck = "checked=\"checked\"";
}

if($usesig == "yes") {
$usesigcheck = "checked=\"checked\"";
}

if($bbcodeoff == "yes") {
$codeoffcheck = "checked=\"checked\"";
}

if($emailnotify == "yes") {
$notifycheck = "checked=\"checked\"";
}
?>

<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTPREVIEW?></td>
</tr>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td rowspan="2" valign="top" width="19%"><span class="postauthor"><?=$username?></span><br /><br /></td>
<td><?=$thread[icon]?>  <?=$poston?></td></tr>
<tr bgcolor="<?=$altbg1?>" class="tablerow"><td><p><?=$message1?></p><br /></td></tr>

</table>
</td></tr></table>
<br />

<form method="post" name="input" action="modules.php?op=modload&name=XForum&file=post&action=newtopic&fid=<?=$fid?>">
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr class="tablerow">
<td bgcolor="<?=$altbg1?>"><?=_TEXTSUBJECT?></td>
<td bgcolor="<?=$altbg2?>"><input type="text" name="subject" size="45" value="<?=$subject?>" /></td>
</tr>

<?
if($piconstatus == "on") {
?>
<tr class="tablerow"><td bgcolor="<?=$altbg1?>"><?=_TEXTICON?></td><td bgcolor="<?=$altbg2?>"><?=$icons1?></td></tr>
<?
}
?>

<tr class="tablerow">
<td bgcolor="<?=$altbg1?>" valign="top" width="19%"><?=_TEXTMESSAGE?></td>
<td bgcolor="<?=$altbg2?>"><textarea rows="9" cols="45" name="message"><?=$message?></textarea>
<br />
<input type="checkbox" name="smileyoff" value="yes" <?=$smileoffcheck?> /> <?=_TEXTDISSMILEYS?><br />
<input type="checkbox" name="usesig" value="yes" <?=$usesigcheck?> /> <?=_TEXTUSESIG?><br />
<input type="checkbox" name="bbcodeoff" value="yes" <?=$codeoffcheck?> /><?=_BBCODEOFF?><br />
<input type="checkbox" name="emailnotify" value="yes" <?=$notifycheck?> /> <?=_EMAILNOTIFYTOREPLIES?><br />
</td>
</tr>
</table>
</td></tr></table>

<input type="hidden" name="username" value="<?=$username?>">
<input type="hidden" name="password" value="<?=$password?>">
<center><input type="submit" name="topicsubmit" value="<?=_TEXTPOSTNEW?>">
<input type="submit" name="previewpost" value="<?=_TEXTPREVIEW?>" /></center>
</form>

<?
}

if(!$topicsubmit && !$previewpost) {
?>

<form method="post" name="input" action="modules.php?op=modload&name=XForum&file=post&action=newtopic&fid=<?=$fid?>">
<?
	if ( ($username == _TEXTGUEST) || ($username == "") )
	{
		?>
			<input type="hidden" name="username" value="<?=$bbpostuser?>">
		<?
	}
?>
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTPOSTNEW?></td>
</tr>

<tr class="tablerow">
<td bgcolor="<?=$altbg1?>" width="22%"><?=_WHOCANPOST?></td>
<td bgcolor="<?=$altbg2?>"><span class="11px"><?=$whopost1?> <?=$whopost2?> <?=$whopost3?></span></td>
</tr>

<tr class="tablerow">
<td bgcolor="<?=$altbg1?>"><?=_TEXTSUBJECT?></td>
<td bgcolor="<?=$altbg2?>"><input type="text" name="subject" size="45" /></td>
</tr>

<?
if($piconstatus == "on") {
?>
<tr class="tablerow"><td bgcolor="<?=$altbg1?>"><?=_TEXTICON?></td><td bgcolor="<?=$altbg2?>"><?=$icons?></td></tr>
<?
}
?>

<tr class="tablerow">
<td bgcolor="<?=$altbg1?>" valign="top"><?=_TEXTMESSAGE?><br /><span class="11px">
<?=_TEXTHTMLIS?> <?=$allowhtml?><br />
<?=_TEXTSMILIESARE?>  <?=$allowsmilies?><br />
<?=_TEXTBBCODEIS?> <?=$allowbbcode?><br />
<?=_TEXTIMGCODEIS?> <?=$allowimgcode?> 
</span></td>

<td bgcolor="<?=$altbg2?>">
<table width="100%"><tr><td align="left" width="70%" colspan="2">
<a href="javascript:icon('[b] [/b]')"><img src="modules/XForum/images/bb_bold.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[i] [/i]')"><img src="modules/XForum/images/bb_italicize.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[u] [/u]')"><img src="modules/XForum/images/bb_underline.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[email] [/email]')"><img src="modules/XForum/images/bb_email.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[quote] [/quote]')"><img src="modules/XForum/images/bb_quote.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[url] [/url]')"><img src="modules/XForum/images/bb_url.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[img] [/img]')"><img src="modules/XForum/images/bb_image.gif" border="0"></a>&nbsp; &nbsp;
</td></tr>

<tr><td align="left" width="70%"><textarea rows="9" cols="45" name="message"></textarea></td><td>
	
<? 
$querysmilie = mysql_query("SELECT * FROM $table_smilies WHERE type='smiley'") or die(mysql_error()); 
echo "<table border=\"1\" align=\"center\">";
$l = "on";
while($smilie = mysql_fetch_array($querysmilie)) {

if($l == "on") {
echo "<tr><td><a href=\"javascript:icon('$smilie[code]')\"><img src=\"modules/XForum/images/$smilie[url]\" border=\"0\"></a></td>";
} else {
echo "<td><a href=\"javascript:icon('$smilie[code]')\"><img src=\"modules/XForum/images/$smilie[url]\" border=\"0\"></a></td></tr>";
}

if($l == "on") {
$r = "on";
$l = "off";
} else {
$l = "on";
$r = "off";
}
}

if($l == "off") {
echo "<td>&nbsp;</td></tr>";
}

echo "</table>\n";


if($status == "Administrator" || $status == "Super Moderator" || $status == "Moderator") {
$topoption = "<input type=\"checkbox\" name=\"toptopic\" value=\"yes\" />"._TOPMSGQUES."<br />";
}
?>
</td></tr></table>

<br />
<input type="checkbox" name="smileyoff" value="yes" /> <?=_TEXTDISSMILEYS?><br />
<input type="checkbox" name="usesig" value="yes" <?=$sigcheck?> /> <?=_TEXTUSESIG?><br />
<input type="checkbox" name="bbcodeoff" value="yes" /><?=_BBCODEOFF?><br />
<input type="checkbox" name="emailnotify" value="yes" /><?=_EMAILNOTIFYTOREPLIES?><br /> <?=$topoption?></td> 
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="topicsubmit" value="<?=_TEXTPOSTNEW?>" />
<input type="submit" name="previewpost" value="<?=_TEXTPREVIEW?>" />
</center>
</form>

<?
}

if($topicsubmit) {
if($username != _TEXTGUEST) {
if($noreg != "on") {
$query = mysql_query("SELECT username, password, status FROM $table_members WHERE username='$username'") or die(mysql_error());
$member = mysql_fetch_array($query);

if(!$member[username]) {
echo "<span class=\"12px \">"._BADNAME."</span>";
exit;
}

$username = $member[username]; 

if($password != $member[password]) {
echo "<span class=\"12px \">"._TEXTPWINCORRECT."</span>";
exit;
}

if($status == "Banned") {
echo "<span class=\"12px \">"._BANNEDMESSAGE."</span>";
exit;
}
}
}

if($subject == "") {
echo _TEXTNOSUBJECT;
exit;
}

if($noreg == "on" && $username == "") {
echo "<span class=\"12px \">"._BADNAME."</span>";
exit;
}

if($forums[guestposting] != "yes" && $username == _TEXTGUEST) { 
echo "<span class=\"12px \">"._TEXTNOQUESTPOSTING."</span>"; 
exit; 
}

$pperm = explode("|", $forums[postperm]);

if($pperm[0] == "2" && $status != "Administrator") {
echo "<span class=\"12px \">"._POSTPERMERR."</span>"; 
exit;
} elseif($pperm[0] == "3" && $status != "Administrator" && $status != "Moderator" && $status != "Super Moderator") {
echo "<span class=\"12px \">"._POSTPERMERR."</span>"; 
exit; 
} elseif($pperm[0] == "4") {
echo "<span class=\"12px \">"._POSTPERMERR."</span>"; 
exit;
}
 
$query = mysql_query("SELECT lastpost, type, fup FROM $table_forums WHERE fid='$fid'") or die(mysql_error());
$for = mysql_fetch_array($query);

if($for[lastpost] != "") {
$lastpost = explode("|", $for[lastpost]);
$rightnow = time() - $floodctrl;

if($rightnow <= $lastpost[0] && $username == $lastpost[1]) {
$floodlink = "<a href=\"modules.php?op=modload&name=XForum&file=forumdisplay&fid=$fid\">Click here</a>";
echo "<span class=\"12px \">"._FLOODPROTECT." $floodlink "._TOCONT."</span>"; 
exit;
}
}

$subject = str_replace("<", "&lt;", $subject);
$subject = str_replace(">", "&gt;", $subject);

$message = addslashes($message);
$subject = addslashes($subject);

if($usesig != "yes") {
$usesig = "no";
}

if($emailnotify != "yes" || $username == _TEXTGUEST) { 
$emailnotify = "no"; 
}

$thatime = time();
mysql_query("INSERT INTO $table_threads VALUES ('', '$fid', '$subject', '$thatime|$username', '0', '0', '$username', '$message', '$thatime', '$posticon', '$usesig', '', '', '$onlineip', '$bbcodeoff', '$smileyoff', '$emailnotify')") or die(mysql_error());
$tid = mysql_insert_id(); 
mysql_query("UPDATE $table_forums SET lastpost='$thatime|$username', threads=threads+1, posts=posts+1 WHERE fid='$fid'") or die(mysql_error());

if($for[type] == "sub") {
mysql_query("UPDATE $table_forums SET lastpost='$thatime|$username', threads=threads+1, posts=posts+1 WHERE fid='$for[fup]'") or die(mysql_error());
}

if($username != _TEXTGUEST) {
mysql_query("UPDATE $table_members SET postnum=postnum+1 WHERE username like '$username'") or die(mysql_error());
}

if(($status == "Administrator" || $status == "Super Moderator" || $status == "Moderator") && $toptopic == "yes") {
mysql_query("UPDATE $table_threads SET topped='1' WHERE tid='$tid' AND fid='$fid'");
}

echo "<span class=\"12px \">"._POSTMSG."</span>";
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

if($action == "reply") {
if($previewpost) {

$currtime = time();
$date = gmdate("n/j/y",$currtime + ($timeoffset * 3600));
$time = gmdate("H:i",$currtime + ($timeoffset * 3600));
$poston = _TEXTPOSTON." $date "._TEXTAT." $time";

$message = stripslashes($message);
$message1 = postify($message, $smileyoff, $bbcodeoff, $fid, $bordercolor, "", "", $table_words, $table_forums, $table_smilies);

if($smileyoff == "yes") {
$smileoffcheck = "checked=\"checked\"";
}

if($usesig == "yes") {
$usesigcheck = "checked=\"checked\"";
}

if($bbcodeoff == "yes") {
$codeoffcheck = "checked=\"checked\"";
}
?>

<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTPREVIEW?></td>
</tr>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td rowspan="2" valign="top" width="19%"><span class="postauthor"><?=$username?></span><br /><br /></td>
<td><?=$thread[icon]?>  <?=$poston?></td></tr>
<tr bgcolor="<?=$altbg1?>" class="tablerow"><td><p><?=$message1?></p><br /></td></tr>

</table>
</td></tr></table>
<br />

<form method="post" name="input" action="modules.php?op=modload&name=XForum&file=post&action=reply&fid=<?=$fid?>&tid=<?=$tid?>">
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<?
if($piconstatus == "on") {
?>
<tr class="tablerow"><td bgcolor="<?=$altbg1?>"><?=_TEXTICON?></td><td bgcolor="<?=$altbg2?>"><?=$icons1?></td></tr>
<?
}
?>

<tr class="tablerow">
<td bgcolor="<?=$altbg1?>" valign="top" width="19%"><?=_TEXTMESSAGE?></td>
<td bgcolor="<?=$altbg2?>"><textarea rows="9" cols="45" name="message"><?=$message?></textarea>
<br />
<input type="checkbox" name="smileyoff" value="yes" <?=$smileoffcheck?> /> <?=_TEXTDISSMILEYS?><br />
<input type="checkbox" name="usesig" value="yes" <?=$usesigcheck?> /> <?=_TEXTUSESIG?><br />
<input type="checkbox" name="bbcodeoff" value="yes" <?=$codeoffcheck?> /><?=_BBCODEOFF?> </td>
</tr>
</table>
</td></tr></table>

<input type="hidden" name="username" value="<?=$username?>">
<input type="hidden" name="password" value="<?=$password?>">
<center><input type="submit" name="replysubmit" value="<?=_TEXTPOSTREPLY?>" />
<input type="submit" name="previewpost" value="<?=_TEXTPREVIEW?>" /></center>
</form>

<?
}

if($replysubmit) {
if($username != _TEXTGUEST) {
if($noreg != "on") {
$query = mysql_query("SELECT username, password, status FROM $table_members WHERE username='$username'") or die(mysql_error());
$member = mysql_fetch_array($query);

if(!$member[username]) {
echo "<span class=\"12px \">"._BADNAME."</span>";
exit;
}

$username = $member[username]; 

if($password != $member[password]) {
echo "<span class=\"12px \">"._TEXTPWINCORRECT."</span>";
exit;
}

if($status == "Banned") {
echo "<span class=\"12px \">"._BANNEDMESSAGE."</span>";
exit;
}
}
}

if($forums[guestposting] != "yes" && $username == _TEXTGUEST) { 
echo "<span class=\"12px \">"._textnoquestposting; 
exit; 
}

$pperm = explode("|", $forums[postperm]);

if($pperm[1] == "2" && $status != "Administrator") {
echo "<span class=\"12px \">"._POSTPERMERR."</span>"; 
exit;
} elseif($pperm[1] == "3" && $status != "Administrator" && $status != "Moderator" && $status != "Super Moderator") {
echo "<span class=\"12px \">"._POSTPERMERR."</span>"; 
exit; 
} elseif($pperm[1] == "4") {
echo "<span class=\"12px \">"._POSTPERMERR."</span>"; 
exit;
}

#DM###:.
/*
$query = mysql_query("SELECT lastpost FROM $table_forums WHERE fid='$fid'") or die(mysql_error());
$last = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT count(lastpost) as nbsites FROM $table_forums WHERE fid='$fid'") or die(mysql_error()); 
$row = mysql_fetch_array($query);
$last = $row[nbsites];
#FM###:.


if($last != "") {
$lastpost = explode("|", $last);
$rightnow = time() - $floodctrl;

if($rightnow <= $lastpost[0] && $username == $lastpost[1]) {
$floodlink = "<a href=\"modules.php?op=modload&name=XForum&file=viewthread&fid=$fid&tid=$tid\">Click here</a>";
echo "<span class=\"12px \">"._FLOODPROTECT." $floodlink "._TOCONT."</span>"; 
exit;
}
}
$message = addslashes($message);

if($usesig != "yes") {
$usesig = "no";
}

if($emailnotify != "yes" || $username == _TEXTGUEST) { $emailnotify = "no"; }

$query = mysql_query("SELECT closed FROM $table_threads WHERE fid=$fid AND tid=$tid") or die(mysql_error());
$closed1 = mysql_fetch_array($query);
$closed = $closed1[closed];

if($closed != "yes") {
$thatime = time();

$query = mysql_query("SELECT author FROM $table_threads WHERE tid='$tid' AND emailnotify='yes'") or die(mysql_error()); 
$thread = mysql_fetch_array($query); 
if($thread) { 

$getuser = mysql_query("SELECT email FROM $table_members WHERE username='$thread[author]'") or die(mysql_error()); 
$user = mysql_fetch_array($getuser);
#DM###:..
#original -> $theurl = $boardurl . "modules.php?op=modload&name=XForum&file=viewthread&tid=$tid";
$theurl = $siteurl . "/modules.php?op=modload&name=XForum&file=viewthread&tid=$tid";
$theurl = preg_replace("/\/{1,}modules/","/modules",$theurl);
#FM###:..
mail("$user[email]", _EMAILNOTIFYSUBJECT." $threadname", _EMAILNOTIFYINTRO."$threadname"._EMAILNOTIFYINTRO2."\n\n$theurl\n\n"._emailnotifyend, _TEXTFROM." $adminemail"); 
} 
$query = mysql_query("SELECT author FROM $table_posts WHERE tid='$tid' AND emailnotify='yes'") or die(mysql_error()); 
while($post = mysql_fetch_array($query)) { 
if($post) { 
$getuser = mysql_query("SELECT email FROM $table_members WHERE username='$post[author]'") or die(mysql_error()); 
$user = mysql_fetch_array($getuser);
#DM###:..
#original -> $theurl = $boardurl . "modules.php?op=modload&name=XForum&file=viewthread&tid=$tid";
$theurl = $siteurl . "/modules.php?op=modload&name=XForum&file=viewthread&tid=$tid";
$theurl = preg_replace("/\/{1,}modules/","/modules",$theurl);
#FM###:..
mail("$user[email]", _EMAILNOTIFYSUBJECT." $threadname", _EMAILNOTIFYINTRO."$threadname"._EMAILNOTIFYINTRO2."\n\n$theurl\n\n"._emailnotifyend, _TEXTFROM." $adminemail"); 
} 
} 

mysql_query("INSERT INTO $table_posts VALUES ('$fid', '$tid', '', '$username', '$message', '$thatime', '$posticon', '$usesig', '$onlineip', '$bbcodeoff', '$smileyoff', '$emailnotify')") or die(mysql_error()); 

mysql_query("UPDATE $table_threads SET lastpost='$thatime|$username', replies=replies+1 WHERE tid='$tid' AND fid='$fid'") or die(mysql_error()); 
mysql_query("UPDATE $table_forums SET lastpost='$thatime|$username', posts=posts+1 WHERE fid='$fid'") or die(mysql_error());

if($username != _TEXTGUEST) {
mysql_query("UPDATE $table_members SET postnum=postnum+1 WHERE username='$username'") or die(mysql_error());
}

}
else {
echo "<span class=\"12px \">"._CLOSEDMSG."</span>";
exit;
}


echo "<span class=\"12px \">"._REPLYMSG."</span>";
?>
<script> 
function redirect()
{ 
window.location.replace("modules.php?op=modload&name=XForum&file=viewthread&tid=<?=$tid?>"); 
} 
setTimeout("redirect();", 1250); 
</script>
<?
} elseif(!$replysubmit && !$previewpost) {

if($repquote) {
$quote = explode("|", $repquote);

if($quote[0] == 't') {
$query = mysql_query("SELECT message, fid FROM $table_threads WHERE tid='$quote[1]'") or die(mysql_error());
$thaquote = mysql_fetch_array($query);
$quotefid = $thaquote[fid];
$thaquote = $thaquote[message];
}
elseif($quote[0] == 'r') {
$query = mysql_query("SELECT message, fid FROM $table_posts WHERE pid='$quote[1]'") or die(mysql_error());
$thaquote = mysql_fetch_array($query);
$quotefid = $thaquote[fid];
$thaquote = $thaquote[message];
}

$query = mysql_query("SELECT private FROM $table_forums WHERE fid='$quotefid'") or die(mysql_error());
$quoteforum = mysql_fetch_array($query);

if($quoteforum[private] == "staff" && $status != "Administrator" && $status != "Super Moderator" && $status != "Moderator") { 
echo "<tr class=\"tablerow\"><td bgcolor=\"$altbg1\" colspan=\"2\">"._PRIVFORUMMSG."</td></tr>"; 
exit; 
}

$thaquote = stripslashes($thaquote);
$thaquote = "[quote]$thaquote [/quote]";
} 

?>
<form method="post" name="input" action="modules.php?op=modload&name=XForum&file=post&action=reply&fid=<?=$fid?>&tid=<?=$tid?>">
<?
	if ( ($username == _TEXTGUEST) || ($username == "") )
	{
		?>
			<input type="hidden" name="username" value="<?=$bbpostuser?>">
		<?
	}
?>
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTPOSTREPLY?></td>
</tr>

<?
if($piconstatus == "on") {
$icontable = "<tr class=\"tablerow\"><td bgcolor=\"$altbg1\">"._TEXTICON."</td><td bgcolor=\"$altbg2\">$icons</td></tr>";
}
?>

<?=$icontable?>

<tr class="tablerow">
<td bgcolor="<?=$altbg1?>" valign="top"><?=_TEXTMESSAGE?><br /><span class="11px">
<?=_TEXTHTMLIS?> <?=$allowhtml?><br />
<?=_TEXTSMILIESARE?> <?=$allowsmilies?><br />
<?=_TEXTBBCODEIS?> <?=$allowbbcode?><br />
<?=_TEXTIMGCODEIS?> <?=$allowimgcode?> 
</span></td>

<td bgcolor="<?=$altbg2?>">
<table width="100%"><tr><td align="left" width="70%" colspan="2">
<a href="javascript:icon('[b] [/b]')"><img src="modules/XForum/images/bb_bold.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[i] [/i]')"><img src="modules/XForum/images/bb_italicize.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[u] [/u]')"><img src="modules/XForum/images/bb_underline.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[email] [/email]')"><img src="modules/XForum/images/bb_email.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[quote] [/quote]')"><img src="modules/XForum/images/bb_quote.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[url] [/url]')"><img src="modules/XForum/images/bb_url.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[img] [/img]')"><img src="modules/XForum/images/bb_image.gif" border="0"></a>&nbsp; &nbsp;
</td></tr>

<tr><td align="left" width="70%"><textarea rows="9" cols="45" name="message"><?=$thaquote?></textarea></td><td>
	
<? 
$querysmilie = mysql_query("SELECT * FROM $table_smilies WHERE type='smiley'") or die(mysql_error()); 
echo "<table border=\"1\" align=\"center\">";
$l = "on";
while($smilie = mysql_fetch_array($querysmilie)) {

if($l == "on") {
echo "<tr><td><a href=\"javascript:icon('$smilie[code]')\"><img src=\"modules/XForum/images/$smilie[url]\" border=\"0\"></a></td>";
} else {
echo "<td><a href=\"javascript:icon('$smilie[code]')\"><img src=\"modules/XForum/images/$smilie[url]\" border=\"0\"></a></td></tr>";
}

if($l == "on") {
$r = "on";
$l = "off";
} else {
$l = "on";
$r = "off";
}
}

if($l == "off") {
echo "<td>&nbsp;</td></tr>";
}

echo "</table>\n";
?>
</td></tr></table>
	
<br />
<input type="checkbox" name="smileyoff" value="yes" /> <?=_TEXTDISSMILEYS?><br />
<input type="checkbox" name="usesig" value="yes" <?=$sigcheck?> /> <?=_TEXTUSESIG?><br />
<input type="checkbox" name="bbcodeoff" value="yes" /><?=_BBCODEOFF?><br />
	<input type="checkbox" name="emailnotify" value="yes" /><?=_EMAILNOTIFYTOREPLIES?> </td> 
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="replysubmit" value="<?=_TEXTPOSTREPLY?>" />
<input type="submit" name="previewpost" value="<?=_TEXTPREVIEW?>" />
</center>
</form>

<!--#DM###:. 
</td></tr></table>
#FM###:.-->

<br>
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="1" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTTOPICREVIEW?></td>
</tr>
<?

if($thisuser && $thisuser != '') 
{ 
  $query = mysql_query("SELECT ppp FROM $table_members WHERE username='$thisuser'") or die(mysql_error()); 
  $this = mysql_fetch_array($query); 
  $ppp = $this[ppp];
}
if(!$ppp || $ppp == '') { $ppp = $postperpage; }	//ppp:  valeur par defaut si n'est pas connecté


#DM###:.
/*
$querytop = mysql_query("SELECT COUNT(*) FROM $table_posts WHERE tid='$tid'") or die(mysql_error());
$replynum = mysql_resul t($querytop, 0);
*/
#DR###:. Remplcé par --> "*" par "pid"
$querytop = mysql_query("SELECT COUNT(pid) as nbsites FROM $table_posts WHERE tid='$tid'") or die(mysql_error());
$row = mysql_fetch_array($query);
$replynum = $row[nbsites];
#FM###:.

$replynum += 1;
if($replynum >= $ppp) { 
$threadlink = "modules.php?op=modload&name=XForum&file=viewthread&fid=$fid&tid=$tid";
eval(_evaltrevlt);
?>
<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td colspan="2" valign="top" width="19%"><?=_TREVLTMSG?></td></tr>
<?
}
else {
$thisbg = $altbg1;
$query = mysql_query("SELECT * FROM $table_posts WHERE tid='$tid' ORDER BY dateline DESC") or die(mysql_error());
while($reply = mysql_fetch_array($query)) {
$date = gmdate($dateformat, $reply[dateline] + ($timeoffset * 3600));
$time = gmdate($timecode, $reply[dateline] + ($timeoffset * 3600));

$poston = _TEXTPOSTON." $date "._TEXTAT." $time";
if($reply[icon] != "") {
$reply[icon] = "<img src=\"modules/XForum/images/$reply[icon]\" alt=\"Icon depicting mood of post\" />";
}

$bbcodeoff = $reply[bbcodeoff];
$smileyoff = $reply[smileyoff];
$reply[message] = stripslashes($reply[message]);
$reply[message] = postify($reply[message], $smileyoff, $bbcodeoff, $fid, $bordercolor, "", "", $table_words, $table_forums, $table_smilies);
?>
<tr bgcolor="<?=$thisbg?>" class="tablerow">
<td rowspan="2" valign="top" width="19%"><span class="postauthor"><?=$reply[author]?></span><br /><br /></td>
<td><?=$reply[icon]?>  <?=$poston?></td></tr>
<tr bgcolor="<?=$thisbg?>" class="tablerow"><td><p><?=$reply[message]?></p><br /></td></tr>
<?

if($thisbg == $altbg2) {
$thisbg = $altbg1;
} 
else {
$thisbg = $altbg2;
}
}
$query = mysql_query("SELECT * FROM $table_threads WHERE tid='$tid'") or die(mysql_error());
$topic = mysql_fetch_array($query);
$date = gmdate($dateformat, $topic[dateline] + ($timeoffset * 3600));
$time = gmdate("$timecode", $topic[dateline] + ($timeoffset * 3600));

$poston = _TEXTPOSTON." $date "._TEXTAT." $time";
if($topic[icon] != "") {
$topic[icon] = "<img src=\"modules/XForum/images/$topic[icon]\" alt=\"Icon depicting mood of post\" />";
}

$bbcodeoff = $topic[bbcodeoff];
$smileyoff = $topic[smileyoff];
$topic[message] = stripslashes($topic[message]);
$topic[message] = postify($topic[message], $smileyoff, $bbcodeoff, $fid, $bordercolor, "", "", $table_words, $table_forums, $table_smilies);
?>
<tr bgcolor="<?=$thisbg?>" class="tablerow">
<td rowspan="2" valign="top" width="19%"><span class="postauthor"><?=$topic[author]?></span><br /><br /></td>
<td><?=$topic[icon]?>  <?=$poston?></td></tr>
<tr bgcolor="<?=$thisbg?>" class="tablerow"><td><p><?=$topic[message]?></p><br /></td></tr>
<!--#DM###:. Ajout de la ligne suivante-->
</table></td></tr></td></tr></table>
<!--#FM###:.-->
<?
}

}
}

//----------------------------------------------------------------------------//
// Action == Edit  : On édite le message
//----------------------------------------------------------------------------//
if($action == "edit") 
{
  if(!$editsubmit) 
  {
	if($pid) 
	{
	  $query = mysql_query("SELECT * FROM $table_posts WHERE pid='$pid' AND tid='$tid' AND fid='$fid'") or die(mysql_error());
	  $postinfo = mysql_fetch_array($query);
	}
	else 
	{
	  $query = mysql_query("SELECT * FROM $table_threads WHERE tid='$tid'") or die(mysql_error());
	  $postinfo = mysql_fetch_array($query);
	}

	$postinfo[message] = str_replace("<br>", "", $postinfo[message]);

	if($forums[allowsmilies] == "yes") 
	{
	  $querysmilie = mysql_query("SELECT * FROM $table_smilies WHERE type='smiley'") or die(mysql_error());
	  while($smilie = mysql_fetch_array($querysmilie)) 
	  {
		$postinfo[message] = str_replace("<img src=\"modules/XForum/images/$smilie[url]\" border=0>",$smilie[code],$postinfo[message]);
	  }
	}

	if($postinfo[usesig] == "yes") 
	{
	  $checked = "checked=\"checked\"";
	}

	$postinfo[message] = stripslashes($postinfo[message]);

	if($postinfo[bbcodeoff]   == "yes")	{ $offcheck1   = "checked=\"checked\""; }
	if($postinfo[smileyoff]   == "yes")	{ $offcheck2   = "checked=\"checked\""; }
	if($postinfo[usesig]	  == "yes")	{ $offcheck3   = "checked=\"checked\""; }
	if($postinfo[emailnotify] == "yes") { $notifycheck = "checked=\"checked\""; }

	$postinfo[subject] = stripslashes($postinfo[subject]);
	$postinfo[subject] = str_replace('"', "&quot;", $postinfo[subject]);

	$icons = "";
	if($piconstatus == "on") 
	{
	  $listed_icons = 0; 
	  $querysmilie = mysql_query("SELECT * FROM $table_smilies WHERE type='picon'") or die(mysql_error()); 
	  
	  while($smilie = mysql_fetch_array($querysmilie)) 
	  {
		if($postinfo[icon] == $smilie[url]) 
		{
		  $icons .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\"checked=\"checked\" /><img src=\"modules/XForum/images/$smilie[url]\" />  ";
		} 
		else 
		{
		  $icons .= " <input type=\"radio\" name=\"posticon\" value=\"$smilie[url]\" /><img src=\"modules/XForum/images/$smilie[url]\" />  ";
		}
		$listed_icons += 1; 
		if($listed_icons == 8) 
		{ 
		  $icons .= "<br />"; 
		  $listed_icons = 0; 
		}
	  }
	}
?>
<!--#DM###:.-->
<!-- 20011020 -> </table> 
<!--#DM###:.-->


<form method="post" name="input" action="modules.php?op=modload&name=XForum&file=post&action=edit">
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTEDITPOST?></td>
</tr>

<?

if(!$pid) 
{
  $str = "<tr class=\"tablerow\">"
		."<td bgcolor=\"".$altbg1."\" width=\"22%\">"._TEXTSUBJECT."</td>"
		."<td bgcolor=\"".$altbg2."\"><input type=\"text\" name=\"subject\" size=\"45\" value=\"".$postinfo[subject]."\"></td>"
		."</tr>\n";
  echo $str;
}

if($piconstatus == "on") 
{
  $str = "<tr class=\"tablerow\">"
		."<td bgcolor=\"".$altbg1."\">"._TEXTICON."</td>"
		."<td bgcolor=\"".$altbg2."\">".$icons."</td>"
		."</tr>\n";
  echo $str;
}

?>

<tr class="tablerow">
<td bgcolor="<?=$altbg1?>" valign="top"><?=_TEXTMESSAGE?><br /><span class="11px">
<?=_TEXTHTMLIS?> <?=$allowhtml?><br />
<?=_TEXTSMILIESARE?>  <?=$allowsmilies?><br />
<?=_TEXTBBCODEIS?> <?=$allowbbcode?><br />
<?=_TEXTIMGCODEIS?> <?=$allowimgcode?> 
</span></td>

<td bgcolor="<?=$altbg2?>">
<table width="100%"><tr><td align="left" width="70%" colspan="2">
<a href="javascript:icon('[b] [/b]')"><img src="modules/XForum/images/bb_bold.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[i] [/i]')"><img src="modules/XForum/images/bb_italicize.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[u] [/u]')"><img src="modules/XForum/images/bb_underline.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[email] [/email]')"><img src="modules/XForum/images/bb_email.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[quote] [/quote]')"><img src="modules/XForum/images/bb_quote.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[url] [/url]')"><img src="modules/XForum/images/bb_url.gif" border="0"></a>&nbsp; &nbsp;
<a href="javascript:icon('[img] [/img]')"><img src="modules/XForum/images/bb_image.gif" border="0"></a>&nbsp; &nbsp;
</td></tr>

<tr><td align="left" width="70%"><textarea rows="9" cols="45" name="message"><?=$postinfo[message]?></textarea></td><td>
	
<? 

$querysmilie = mysql_query("SELECT url FROM $table_smilies WHERE type='smiley'") or die(mysql_error()); 
echo "<table border=\"1\" align=\"center\">";
$l = "on";
while($smilie = mysql_fetch_array($querysmilie)) 
{
  if($l == "on") 
  {
	echo "<tr><td><a href=\"javascript:icon('".$smilie[code]."')\"><img src=\"modules/XForum/images/".$smilie[url]."\" border=\"0\"></a></td>";
  } 
  else 
  {
	echo "<td><a href=\"javascript:icon('".$smilie[code]."')\"><img src=\"modules/XForum/images/".$smilie[url]."\" border=\"0\"></a></td></tr>";
  }

  if($l == "on") 
  {
	$r = "on";
	$l = "off";
  } 
  else 
  {
	$l = "on";
	$r = "off";
  }
}

if($l == "off") { echo "<td>&nbsp;</td></tr>"; }
echo "</table>\n";

?>
</td></tr></table>
	
<br />
<input type="checkbox" name="smileyoff" value="yes" <?=$offcheck2?> /> <?=_TEXTDISSMILEYS?><br />
<input type="checkbox" name="usesig" value="yes" <?=$offcheck3?> /> <?=_TEXTUSESIG?><br />
<input type="checkbox" name="bbcodeoff" value="yes" <?=$offcheck1?> /><?=_BBCODEOFF?><br />
	<input type="checkbox" name="emailnotify" value="yes" <?=$notifycheck?> /> <?=_EMAILNOTIFYTOREPLIES?><br />
<input type="checkbox" name="delete" value="yes" /> <b><?=_TEXTDELETE?></b></td>
</tr>

</table>
</td></tr></table><br>
<input type="hidden" name="fid" value="<?=$fid?>" />
<input type="hidden" name="tid" value="<?=$tid?>" />
<input type="hidden" name="pid" value="<?=$pid?>" />
<input type="hidden" name="origauthor" value="<?=$postinfo[author]?>" />
<center><input type="submit" name="editsubmit" value="<?=_TEXTEDITPOST?>" /></center>
</form>

<?
}

if($editsubmit) {
$query = mysql_query("SELECT username, password, status FROM $table_members WHERE username='$username'") or die(mysql_error());
$member = mysql_fetch_array($query);
$status = $member[status];

if(!$member[username]) {
echo "<span class=\"12px \">"._BADNAME."</span>";
exit;
}

$username = $member[username]; 

if($password != $member[password]) {
echo "<span class=\"12px \">"._TEXTPWINCORRECT."</span>";
exit;
}

if($status == "Banned") {
echo "<span class=\"12px \">"._BANNEDMESSAGE."</span>";
exit;
}

$date = gmdate($dateformat);
$message .= "["._TEXTEDITON." $date "._TEXTBY." $username]";


if($emailnotify != "yes" || $username == _TEXTGUEST) { 
$emailnotify = "no"; 
} 

$status1 = modcheck($status, $username, $fid, $table_forums);
if($status == "Super Moderator") {
$status1 = "Moderator";
}


if($status == "Administrator" || $status1 == "Moderator" || $username == $origauthor) { 
$message = addslashes($message);
if($pid && $delete != "yes") { 
mysql_query("UPDATE $table_posts SET message='$message', usesig='$usesig', bbcodeoff='$bbcodeoff', smileyoff='$smileyoff', emailnotify='$emailnotify', icon='$posticon' WHERE pid='$pid'") or die(mysql_error()); 
} elseif($delete != "yes") { 
$message = addslashes($message);
mysql_query("UPDATE $table_threads SET message='$message', usesig='$usesig', subject='$subject', bbcodeoff='$bbcodeoff', smileyoff='$smileyoff', emailnotify='$emailnotify', icon='$posticon' WHERE tid='$tid'") or die(mysql_error()); 
} elseif($pid && $delete == "yes") { 
mysql_query("UPDATE $table_forums SET posts=posts-1 WHERE fid='$fid'") or die(mysql_error());
mysql_query("UPDATE $table_threads SET replies=replies-1 WHERE tid='$tid' AND fid='$fid'") or die(mysql_error()); 
mysql_query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$origauthor'") or die(mysql_error()); 
mysql_query("DELETE FROM $table_posts WHERE pid='$pid'") or die(mysql_error()); 
} elseif($delete == "yes") { 
#DM###:.
/*
$query = mysql_query("SELECT COUNT(pid) FROM $table_posts WHERE tid='$tid'") or die(mysql_error()); 
$subtract = mysql_resul t($query, 0);
*/
#DR###:. Remplcé par -->
$query = mysql_query("SELECT COUNT(pid) as nbsites FROM $table_posts WHERE tid='$tid'") or die(mysql_error()); 
$row = mysql_fetch_array($query);
$subtract = $row[nbsites];
#FM###:.

$subtract++;

$count = mysql_query("SELECT type, fup FROM $table_forums WHERE fid='$fid'") or die(mysql_error()); 
$for = mysql_fetch_array($query);

if($status == "Administrator" || $status == "Moderator" || ($username == $origauthor && $subtract == 0)) { 
mysql_query("UPDATE $table_forums SET threads=threads-1, posts=posts-'$subtract' WHERE fid='$fid'") or die(mysql_error());

if($for[type] == "sub") {
mysql_query("UPDATE $table_forums SET threads=threads-1, posts=posts-'$subtract' WHERE fid='$for[fup]'") or die(mysql_error());
}

$query = mysql_query("SELECT author FROM $table_posts WHERE tid='$tid'") or die(mysql_error());
while($result = mysql_fetch_array($query)) {
mysql_query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$result[author]'") or die(mysql_error()); 
}

mysql_query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$origauthor'") or die(mysql_error()); 
mysql_query("DELETE FROM $table_threads WHERE tid='$tid'") or die(mysql_error()); 
mysql_query("DELETE FROM $table_posts WHERE tid='$tid'") or die(mysql_error()); 
} 
}
} else {
echo "<span class=\"12px \">"._NOEDIT."</span>";
exit;
}

echo "<span class=\"12px \">"._EDITPOSTMSG."</span>";
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

