<?
# bug XFthemesubmit corrige 20011113 21h03

require "modules/XForum/header.php";

$navigation = "<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; "._TEXTCP;
$html = template("header.html");
eval("echo stripslashes(\"$html\");");

if(!$thisuser || !$thispw) {
echo _NOTADMIN;
exit;
}
?>

<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr class="header">
<td colspan="2"><?=_TEXTCP?></td>
</tr>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td align="center">
<a href="modules.php?op=modload&name=XForum&file=cp&action=settings"><?=_TEXTSETTINGS?></a> - <a href="modules.php?op=modload&name=XForum&file=cp&action=forum"><?=_TEXTFORUMS?></a> - 
<a href="modules.php?op=modload&name=XForum&file=cp&action=mods"><?=_TEXTMODS?></a> - <a href="modules.php?op=modload&name=XForum&file=cp&action=members"><?=_TEXTMEMBERS?></a> - 
<a href="modules.php?op=modload&name=XForum&file=cp&action=ipban"><?=_TEXTIPBAN?></a> - <a href="modules.php?op=modload&name=XForum&file=cp&action=upgrade"><?=_TEXTUPGRADE?></a><br />

<a href="modules.php?op=modload&name=XForum&file=cp2&action=themes"><?=_TEXTTHEMES?></a> - <a href="modules.php?op=modload&name=XForum&file=cp2&action=smilies"><?=_TEXTSMILIES?></a> - 
<a href="modules.php?op=modload&name=XForum&file=cp2&action=censor"><?=_TEXTCENSORS?></a> - <a href="modules.php?op=modload&name=XForum&file=cp2&action=ranks"><?=_TEXTUSERRANKS?></a> - 
<a href="modules.php?op=modload&name=XForum&file=cp2&action=newsletter"><?=_TEXTNEWSLETTER?></a> - <a href="modules.php?op=modload&name=XForum&file=cp2&action=prune"><?=_TEXTPRUNE?></a></td>
</tr>

<?
if(!$action) {
}



if($action == "themes") {
if(!$XFthemesubmit && !$single) {
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp2&action=themes">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr class="header">
<td><?=_TEXTDELETEQUES?></td>
<td><?=_TEXTTHEMENAME?></td>
</tr>

<?
$query = mysql_query("SELECT name FROM $table_themes") or die(mysql_error());
while($XFthemeinfo = mysql_fetch_array($query)) {

if($XFthemeinfo[name] == "$XFtheme") {
$checked = "checked=\"checked\"";
}
?>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><input type="checkbox" name="delete<?=$XFthemeinfo[name]?>" value="<?=$XFthemeinfo[name]?>" /></td>
<td><input type="text" name="name<?=$XFthemeinfo[name]?>" value="<?=$XFthemeinfo[name]?>" /> <a href="modules.php?op=modload&name=XForum&file=cp2&action=themes&single=<?=$XFthemeinfo[name]?>"><?=_TEXTDETAILS?></a></td>
</tr>

<?
$checked = "";
}
?>

<tr bgcolor="<?=$altbg2?>"><td colspan="3"> </td></tr>
<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td colspan="3"><a href="modules.php?op=modload&name=XForum&file=cp2&action=themes&single=anewtheme1"><?=_TEXTNEWTHEME?></a></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="XFthemesubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
</form>

</td>
</tr>

<?
}

if($XFthemesubmit) {
$querytheme = mysql_query("SELECT name FROM $table_themes") or die(mysql_error());
while($XFthemes = mysql_fetch_array($querytheme)) {
$name = "name$XFthemes[name]";
$name = "${$name}";
$delete = "delete$XFthemes[name]";
$delete = "${$delete}";

if($delete != "") {
mysql_query("DELETE FROM $table_themes WHERE name='$delete'") or die(mysql_error());
}


if($XFthemes[name] == $XFthemedef && $name != $XFthemes[name]) 
{

	include("modules/XForum/settings.php");

	$file = fopen("modules/XForum/settings.php", "w");
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
    $content .= "# C. Deltheil http://www.trollix.com\n";
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
	$content .= "\$nukesystem = \"".$nukesystem."\";\n";
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
	$content .= "\$table_whosonline = \"".$tablepre."whosonline\";\n";
	$content .= "\$table_words = \"".$tablepre."words\";\n";

	$content .= "\n";
	$content .= "\$langfile = \"".$langfilenew."\";\n";
	$content .= "\$bbname = \"".$bbnamenew."\";\n";

	$content .= "\$postperpage = \"".$postperpagenew."\";\n";
	$content .= "\$topicperpage = \"".$topicperpagenew."\";\n";
	$content .= "\$hottopic = \"".$hottopicnew."\";\n";
	$content .= "\$XFtheme = \"".$XFthemenew."\";\n";
	$content .= "\$bbstatus = \"".$bbstatusnew."\";\n";
	$content .= "\$whosonlinestatus = \"".$whos_on."\";\n";
	$content .= "\$regstatus = \"".$reg_on."\";\n";
	$content .= "\$bboffreason = \"".$bboffreasonnew."\";\n";
	$content .= "\$regviewonly = \"".$regviewnew."\";\n";
	$content .= "\$floodctrl = \"".$floodctrlnew."\";\n";
	$content .= "\$memberperpage = \"".$memberperpagenew."\";\n";
	$content .= "\$catsonly = \"".$catsonlynew."\";\n";
	$content .= "\$hideprivate = \"".$hidepriv."\";\n";
	$content .= "\$showsort = \"".$showsortnew."\";\n";
	$content .= "\$emailcheck = \"".$emailchecknew."\";\n";
	$content .= "\$bbrules = \"".$bbrulesnew."\";\n";
	$content .= "\$bbrulestxt = \"".$bbrulestxtnew."\";\n";
	$content .= "\$searchstatus = \"".$searchstatusnew."\";\n";
	$content .= "\$faqstatus = \"".$faqstatusnew."\";\n";
	$content .= "\$memliststatus = \"".$memliststatusnew."\";\n";
	$content .= "\$piconstatus = \"".$piconstatusnew."\";\n";
	$content .= "\$sitename = \"".$sitenamenew."\";\n";
	$content .= "\$siteurl = \"".$siteurlnew."\";\n";
	$content .= "\$avastatus = \"".$avastatusnew."\";\n";
	$content .= "\$noreg = \"".$noregnew."\";\n";
	$content .= "\$gzipcompress = \"".$gzipcompressnew."\";\n";
	$content .= "\$boardurl = \"".$boardurlnew."\";\n";
	$content .= "\$coppa = \"".$coppanew."\";\n";
	$content .= "\$timeformat = \"".$timeformatnew."\";\n";
	$content .= "\$adminemail = \"".$adminemailnew."\";\n";
	$content .= "\$dateformat = \"".$dateformatnew."\";\n";
	$content .= "\$statspage = \"".$statspagenew."\";\n";
	$content .= "\$affheader = \"".$affheadernew."\";\n";
	$content .= "\$afffooter = \"".$affooternew."\";\n";
	$content .= "\$sigbbcode = \"".$sigbbcodenew."\";\n";
	$content .= "\$sightml = \"".$sightmlnew."\";\n";
	$content .= "\$indexstats = \"".$indexstatsnew."\";\n";
	$content .= "\$reportpost = \"".$reportpostnew."\";\n";
	$content .= "\$showtotaltime = \"".$showtotaltimenew."\";\n";

	$content .= "\$dbuser = \"".$dbuser."\";\n";
	$content .= "\$dbpw = \"".$dbpw."\";\n";
	$content .= "\$dbhost = \"".$dbhost."\";\n";

	$content .= "\n";
    $content .= "?>";				#<? pour la couleur syntaxique
    $r = fwrite($file, $content);
    fclose($file);

}

if($name != $XFthemes[name]) {
mysql_query("UPDATE $table_members SET theme='$name' WHERE theme='$XFthemes[name]'") or die(mysql_error());
mysql_query("UPDATE $table_forums SET theme='$name' WHERE theme='$XFthemes[name]'") or die(mysql_error());
}

mysql_query("UPDATE $table_themes SET name='$name' WHERE name='$XFthemes[name]'") or die(mysql_error());
}


echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTTHEMEUPDATE."</td></tr>";
} 

if($single && $single != "submit" && $single != "anewtheme1") {
$query = mysql_query("SELECT * FROM $table_themes WHERE name='$single'") or die(mysql_error());
$XFthemestuff = mysql_fetch_array($query);

if($XFthemestuff[postscol] == "2col") {
$pcol2 = "selected=\"selected\"";
} else {
$pcol1 = "selected=\"selected\"";
}
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp2&action=themes&single=submit">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTHEMENAME?></td>
<td colspan="2"><input type="text" name="namenew" value="<?=$XFthemestuff[name]?>" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBGCOLOR?></td>
<td><input type="text" name="bgcolornew" value="<?=$XFthemestuff[bgcolor]?>" /></td>
<td bgcolor="<?=$XFthemestuff[bgcolor]?>">&nbsp;</td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTALTBG1?></td>
<td><input type="text" name="altbg1new" value="<?=$XFthemestuff[altbg1]?>" /></td>
<td bgcolor="<?=$XFthemestuff[altbg1]?>">&nbsp;</td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTALTBG2?></td>
<td><input type="text" name="altbg2new" value="<?=$XFthemestuff[altbg2]?>" /></td>
<td bgcolor="<?=$XFthemestuff[altbg2]?>">&nbsp;</td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTLINK?></td>
<td><input type="text" name="linknew" value="<?=$XFthemestuff[link]?>" /></td>
<td bgcolor="<?=$XFthemestuff[link]?>">&nbsp;</td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBORDER?></td>
<td><input type="text" name="bordercolornew" value="<?=$XFthemestuff[bordercolor]?>" /></td>
<td bgcolor="<?=$XFthemestuff[bordercolor]?>">&nbsp;</td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTHEADER?></td>
<td><input type="text" name="headernew" value="<?=$XFthemestuff[header]?>" /></td>
<td bgcolor="<?=$XFthemestuff[header]?>">&nbsp;</td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTHEADERTEXT?></td>
<td><input type="text" name="headertextnew" value="<?=$XFthemestuff[headertext]?>" /></td>
<td bgcolor="<?=$XFthemestuff[headertext]?>">&nbsp;</td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTTOP?></td>
<td><input type="text" name="topnew" value="<?=$XFthemestuff[top]?>" /></td>
<td bgcolor="<?=$XFthemestuff[top]?>">&nbsp;</td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTCATCOLOR?></td>
<td><input type="text" name="catcolornew" value="<?=$XFthemestuff[catcolor]?>" /></td>
<td bgcolor="<?=$XFthemestuff[catcolor]?>">&nbsp;</td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTTABLETEXT?></td>
<td><input type="text" name="tabletextnew" value="<?=$XFthemestuff[tabletext]?>" /></td>
<td bgcolor="<?=$XFthemestuff[tabletext]?>">&nbsp;</td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTTEXT?></td>
<td><input type="text" name="textnew" value="<?=$XFthemestuff[text]?>" /></td>
<td bgcolor="<?=$XFthemestuff[text]?>">&nbsp;</td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBORDERWIDTH?></td>
<td colspan="2"><input type="text" name="borderwidthnew" value="<?=$XFthemestuff[borderwidth]?>" size="2" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTWIDTH?></td>
<td colspan="2"><input type="text" name="tablewidthnew" value="<?=$XFthemestuff[tablewidth]?>" size="3" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTSPACE?></td>
<td colspan="2"><input type="text" name="tablespacenew" value="<?=$XFthemestuff[tablespace]?>" size="2" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTFONT?></td>
<td colspan="2"><input type="text" name="fnew" value="<?=$XFthemestuff[font]?>" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTALTFONT?></td>
<td colspan="2"><input type="text" name="altfnew" value="<?=$XFthemestuff[altfont]?>" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBIGSIZE?></td>
<td colspan="2"><input type="text" name="fsizenew" value="<?=$XFthemestuff[fontsize]?>" size="4" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTSMALLSIZE?></td>
<td colspan="2"><input type="text" name="altfsizenew" value="<?=$XFthemestuff[altfontsize]?>" size="4" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTREPLYIMG?></td>
<td colspan="2"><input type="text"  value="<?=$XFthemestuff[replyimg]?>" name="replyimgnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTNEWTOPICIMG?></td>
<td colspan="2"><input type="text"  value="<?=$XFthemestuff[newtopicimg]?>" name="newtopicimgnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBOARDLOGO?></td>
<td colspan="2"><input type="text"  value="<?=$XFthemestuff[boardimg]?>" name="boardlogonew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_POSTSCOLUMN?></td>
<td colspan="2"><select name="postscolnew"><option value="2col" <?=$pcol2?>><?=_TEXT2COL?></option><option value="1col" <?=$pcol1?>><?=_TEXT1COL?></option></select></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
<input type="hidden" name="orig" value="<?=$single?>" />
</form>

</td>
</tr>

<?
}

if($single == "anewtheme1") {
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp2&action=themes&single=submit">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTHEMENAME?></td>
<td><input type="text" name="namenew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBGCOLOR?></td>
<td><input type="text" name="bgcolornew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTALTBG1?></td>
<td><input type="text" name="altbg1new" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTALTBG2?></td>
<td><input type="text" name="altbg2new" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTLINK?></td>
<td><input type="text" name="linknew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBORDER?></td>
<td><input type="text" name="bordercolornew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTHEADER?></td>
<td><input type="text" name="headernew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTHEADERTEXT?></td>
<td><input type="text" name="headertextnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTTOP?></td>
<td><input type="text" name="topnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTCATCOLOR?></td>
<td><input type="text" name="catcolornew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTTABLETEXT?></td>
<td><input type="text" name="tabletextnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTTEXT?></td>
<td><input type="text" name="textnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBORDERWIDTH?></td>
<td><input type="text" name="borderwidthnew" size="2" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTWIDTH?></td>
<td><input type="text" name="tablewidthnew" size="3" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTSPACE?></td>
<td><input type="text" name="tablespacenew" size="2" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTFONT?></td>
<td><input type="text" name="fnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTALTFONT?></td>
<td><input type="text" name="altfnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBIGSIZE?></td>
<td><input type="text" name="fsizenew" size="4" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTSMALLSIZE?></td>
<td><input type="text" name="altfsizenew" size="4" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTREPLYIMG?></td>
<td><input type="text"  name="replyimgnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTNEWTOPICIMG?></td>
<td><input type="text" name="newtopicimgnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBOARDLOGO?></td>
<td><input type="text" name="boardlogonew" value="<?=$boardimg?>" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_POSTSCOLUMN?></td>
<td colspan="2"><select name="postscolnew"><option value="2col"><?=_TEXT2COL?></option><option value="1col"><?=_TEXT1COL?></option></select></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
<input type="hidden" name="newtheme" value="<?=$single?>" />
</form>

</td>
</tr>

<?
}


if($single == "submit" && !$newtheme) {
mysql_query("UPDATE $table_themes SET name='$namenew', bgcolor='$bgcolornew', altbg1='$altbg1new', altbg2='$altbg2new', link='$linknew', bordercolor='$bordercolornew', header='$headernew', headertext='$headertextnew', top='$topnew', catcolor='$catcolornew', tabletext='$tabletextnew', text='$textnew', borderwidth='$borderwidthnew', tablewidth='$tablewidthnew', tablespace='$tablespacenew', fontsize='$fsizenew', font='$fnew', altfontsize='$altfsizenew', altfont='$altfnew', replyimg='$replyimgnew', newtopicimg='$newtopicimgnew', boardimg='$boardlogonew', postscol='$postscolnew' WHERE name='$orig'") or die(mysql_error());

echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTTHEMEUPDATE."</td></tr>";
}

if($single == "submit" && $newtheme) {
mysql_query("INSERT INTO $table_themes VALUES('$namenew', '$bgcolornew', '$altbg1new', '$altbg2new', '$linknew', '$bordercolornew', '$headernew', '$headertextnew', '$topnew', '$catcolornew', '$tabletextnew', '$textnew', '$borderwidthnew', '$tablewidthnew', '$tablespacenew','$fnew','$fsizenew','$altfnew','$altfsizenew', '$replyimgnew', '$newtopicimgnew', '$boardlogonew', '$postscolnew')") or die(mysql_error());

echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTTHEMEUPDATE."</td></tr>";
}
}



if($action == "smilies") {
if(!$smiliesubmit) {
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp2&action=smilies">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr class="header">
<td><?=_TEXTDELETEQUES?></td>
<td><?=_TEXTSMILIECODE?></td>
<td><?=_TEXTSMILIEURL?></td>
</tr>

<?
$query = mysql_query("SELECT * FROM $table_smilies WHERE type='smiley' ORDER BY id") or die(mysql_error());
while($smilie = mysql_fetch_array($query)) {
?>

<tr>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="checkbox" name="delete<?=$smilie[id]?>" value="<?=$smilie[id]?>" /></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="code<?=$smilie[id]?>" value="<?=$smilie[code]?>" /></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="url<?=$smilie[id]?>" value="<?=$smilie[url]?>" /></td>
</tr>

<?
}
?>
<tr bgcolor="<?=$altbg2?>"><td colspan="3"> </td></tr>
<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td colspan="2"><?=_TEXTNEWSMILIE?>&nbsp;&nbsp;<input type="text" name="newcode" /></td>
<td><input type="text" name="newurl1" /></td>
</tr>

<tr>
<td colspan="3" class="header"><?=_PICONS?></td>
</tr>

<?
$query = mysql_query("SELECT * FROM $table_smilies WHERE type='picon' ORDER BY id") or die(mysql_error());
while($smilie = mysql_fetch_array($query)) {
?>

<tr>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="checkbox" name="delete<?=$smilie[id]?>" value="<?=$smilie[id]?>" /></td>
<td bgcolor="<?=$altbg2?>" class="tablerow" colspan="2"><input type="text" name="url<?=$smilie[id]?>" value="<?=$smilie[url]?>" /></td></tr>

<?
}
?>

<tr bgcolor="<?=$altbg2?>"><td colspan="3"> </td></tr>
<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td colspan="3"><?=_TEXTNEWPICON?>&nbsp;&nbsp;<input type="text" name="newurl2" /></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="smiliesubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
</form>

</td>
</tr>

<?
}

if($smiliesubmit) {
$querysmilie = mysql_query("SELECT id FROM $table_smilies WHERE type='smiley'") or die(mysql_error());
while($smilie = mysql_fetch_array($querysmilie)) {
$code = "code$smilie[id]";
$code = "${$code}";
$url = "url$smilie[id]";
$url = "${$url}";
$delete = "delete$smilie[id]";
$delete = "${$delete}";

if($delete != "") {
$query = mysql_query("DELETE FROM $table_smilies WHERE id='$delete'") or die(mysql_error());
}

$query = mysql_query("UPDATE $table_smilies SET code='$code', url='$url' WHERE id='$smilie[id]' AND type='smiley'") or die(mysql_error());
}



$querysmilie = mysql_query("SELECT id FROM $table_smilies WHERE type='picon'") or die(mysql_error());
while($picon = mysql_fetch_array($querysmilie)) {
$url = "url$picon[id]";
$url = "${$url}";
$delete = "delete$picon[id]";
$delete = "${$delete}";

if($delete != "") {
$query = mysql_query("DELETE FROM $table_smilies WHERE id='$delete'") or die(mysql_error());
}

$query = mysql_query("UPDATE $table_smilies SET url='$url' WHERE id='$picon[id]' AND type='picon'") or die(mysql_error());
}


if($newcode != "") {
$query = mysql_query("INSERT INTO $table_smilies VALUES ('smiley', '$newcode', '$newurl1', '')") or die(mysql_error());
}

if($newurl2 != "") {
$query = mysql_query("INSERT INTO $table_smilies VALUES ('picon', '', '$newurl2', '')") or die(mysql_error());
}

echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTSMILIEUPDATE."</td></tr>";
}
}



if($action == "censor") {
if(!$censorsubmit) {
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp2&action=censor">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr class="header">
<td><?=_TEXTDELETEQUES?></td>
<td><?=_TEXTCENSORFIND?></td>
<td><?=_TEXTCENSORREPLACE?></td>
</tr>

<?
$query = mysql_query("SELECT * FROM $table_words ORDER BY id") or die(mysql_error());
while($censor = mysql_fetch_array($query)) {

?>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><input type="checkbox" name="delete<?=$censor[id]?>" value="<?=$censor[id]?>" /></td>
<td><input type="text" name="find<?=$censor[id]?>" value="<?=$censor[find]?>" /></td>
<td><input type="text" name="replace<?=$censor[id]?>" value="<?=$censor[replace1]?>" /></td>
</tr>

<?
}
?>

<tr bgcolor="<?=$altbg2?>"><td colspan="3"> </td></tr>
<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td colspan="2"><?=_TEXTNEWCODE?>&nbsp;&nbsp;<input type="text" name="newfind" /></td>
<td><input type="text" name="newreplace" /></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="censorsubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
</form>

</td>
</tr>

<?
}

if($censorsubmit) {
$querycensor = mysql_query("SELECT id FROM $table_words") or die(mysql_error());

while($censor = mysql_fetch_array($querycensor)) {
$find = "find$censor[id]";
$find = "${$find}";
$replace = "replace$censor[id]";
$replace = "${$replace}";
$delete = "delete$censor[id]";
$delete = "${$delete}";

if($delete != "") {
mysql_query("DELETE FROM $table_words WHERE id='$delete'") or die(mysql_error());
}

mysql_query("UPDATE $table_words SET find='$find', replace1='$replace' WHERE id='$censor[id]'") or die(mysql_error());
}

if($newfind != "") {
mysql_query("INSERT INTO $table_words VALUES ('$newfind', '$newreplace', '')") or die(mysql_error());
}

echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTCENSORUPDATE."</td></tr>";
}
}



if($action == "ranks") {
if(!$rankssubmit) {
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp2&action=ranks">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr class="header">
<td><?=_TEXTDELETEQUES?></td>
<td><?=_TEXTCUSSTATUS?></td>
<td><?=_TEXTPOSTS?></td>
<td><?=_TEXTSTARS?></td>
<td><?=_TEXTALLOWAVATARS?></td>
<td><?=_TEXTAVATAR?></td>
</tr>

<?
$query = mysql_query("SELECT * FROM $table_ranks ORDER BY id") or die(mysql_error());
while($rank = mysql_fetch_array($query)) {

if($rank[allowavatars] == "yes") {
$avataryes = "selected=\"selected\"";
}
else {
$avatarno = "selected=\"selected\"";
}
?>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><input type="checkbox" name="delete<?=$rank[id]?>" value="<?=$rank[id]?>" /></td>
<td><input type="text" name="title<?=$rank[id]?>" value="<?=$rank[title]?>" /></td>
<td><input type="text" name="posts<?=$rank[id]?>" value="<?=$rank[posts]?>" size="5" /></td>
<td><input type="text" name="stars<?=$rank[id]?>" value="<?=$rank[stars]?>" size="4" /></td>
<td><select name="allowavatars<?=$rank[id]?>"><option value="yes" <?=$avataryes?>><?=_TEXTON?></option>
<option value="no" <?=$avatarno?>><?=_TEXTOFF?></option></select></td>
<td><input type="text" name="avaurl<?=$rank[id]?>" value="<?=$rank[avatarrank]?>" size="20" /></td>
</tr>

<?
$avataryes = "";
$avatarno = "";
}
?>

<tr bgcolor="<?=$altbg2?>"><td colspan="6"> </td></tr>
<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td colspan="2"><?=_TEXTNEWRANK?>&nbsp;&nbsp;<input type="text" name="newtitle" /></td>
<td><input type="text" name="newposts" size="5" /></td>
<td><input type="text" name="newstars" size="4" /></td>
<td><select name="newallowavatars"><option value="yes"><?=_TEXTON?></option><option value="no"><?=_TEXTOFF?></option></select></td>
<td><input type="text" name="newavaurl" size="20" /></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="rankssubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
</form>

</td>
</tr>

<?
}

if($rankssubmit) {
$query = mysql_query("SELECT id FROM $table_ranks") or die(mysql_error());

while($ranks = mysql_fetch_array($query)) {
$title = "title$ranks[id]";
$title = "${$title}";
$posts = "posts$ranks[id]";
$posts = "${$posts}";
$stars = "stars$ranks[id]";
$stars = "${$stars}";
$allowavatars = "allowavatars$ranks[id]";
$allowavatars = "${$allowavatars}";
$delete = "delete$ranks[id]";
$delete = "${$delete}";
$avaurl = "avaurl$ranks[id]";
$avaurl = "${$avaurl}";

if($delete != "") {
mysql_query("DELETE FROM $table_ranks WHERE id='$delete'") or die(mysql_error());
}

mysql_query("UPDATE $table_ranks SET title='$title', posts='$posts', stars='$stars', allowavatars='$allowavatars', avatarrank='$avaurl' WHERE id='$ranks[id]'") or die(mysql_error());
}

if($newtitle != "") {
mysql_query("INSERT INTO $table_ranks VALUES ('$newtitle', '$newposts', '', '$newstars', '$newallowavatars', '$newavaurl')") or die(mysql_error());
}

echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTRANKINGSUPDATE."</td></tr>";
}
}



if($action == "newsletter") {
if(!$newslettersubmit) {
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp2&action=newsletter">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr class="header">
<td colspan=2><?=_TEXTNEWSLETTER?></td>
</tr>


<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td><?=_TEXTSUBJECT?></td><td><input type="text" name="newssubject" size="45" /></td>
</tr>
<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td valign=top><?=_TEXTMESSAGE?></td><td><textarea cols="35" rows="7" name="newsmessage"></textarea></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="newslettersubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
</form>

</td>
</tr>

<?
}
if($newslettersubmit) {
$query = mysql_query("SELECT * FROM $table_members WHERE newsletter='yes'") or die(mysql_error());
while ($memnews = mysql_fetch_array($query)) {
$newsmessage = stripslashes($newsmessage);
mail("$memnews[email]", "$newssubject", "$newsmessage", _TEXTFROM." $adminemail"); 


}
echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTNEWSLETTERSUBMIT." </td></tr>";
}
}



if($action == "prune") {
if(!$prunesubmit) {

$forumselect = "<select name=\"forumprune\">\n";
$forumselect .= "<option value=\""._TEXTALL."\">"._TEXTALL."</option>\n";
$querycat = mysql_query("SELECT * FROM $table_forums WHERE type='forum' ORDER BY displayorder") or die(mysql_error());
while($forum = mysql_fetch_array($querycat)) {
$forumselect .= "<option value=\"$forum[fid]\">$forum[name]</option>\n";
}
$forumselect .= "</select>";
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp2&action=prune ">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr class="header">
<td colspan="2"><?=_TEXTPRUNE?></td>
</tr>

<tr bgcolor="<?=$altbg1?>">
<td class="tablerow"><?=_PRUNEWHERE?></td>
<td><input type="text" name="days" size="3" /></td>
</tr>

<tr bgcolor="<?=$altbg1?>">
<td class="tablerow"><?=_PRUNEIN?></td>
<td><?=$forumselect?></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="prunesubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
</form>

</td>
</tr>

<?
}

if($prunesubmit) {
$prunedate = time() - (86400*$days);

if($forumprune == _TEXTALL) {
$querythread = mysql_query("SELECT * FROM $table_threads WHERE lastpost <= '$prunedate'") or die(mysql_error());
} else {
$querythread = mysql_query("SELECT * FROM $table_threads WHERE lastpost <= '$prunedate' AND fid='$forumprune'") or die(mysql_error());
}

while($thread = mysql_fetch_array($querythread)) {
mysql_query("DELETE FROM $table_threads WHERE tid='$thread[tid]'") or die(mysql_error());
mysql_query("UPDATE $table_forums SET posts=post-1, threads=threads-1 WHERE fid='$thread[fid]'") or die(mysql_error());
mysql_query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$thread[author]'") or die(mysql_error());

$querypost = mysql_query("SELECT * FROM $table_posts WHERE tid='$thread[tid]'") or die(mysql_error());
while($post = mysql_fetch_array($querypost)) {
mysql_query("DELETE FROM $table_posts WHERE pid='$post[pid]'") or die(mysql_error());
mysql_query("UPDATE $table_forums SET posts=post-1 WHERE fid='$post[fid]'") or die(mysql_error());
mysql_query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$post[author]'") or die(mysql_error());
}
}

}
}


$mtime2 = explode(" ", microtime());
$endtime = $mtime2[1] + $mtime2[0];
if($showtotaltime != "off") { 
$totaltime = ($endtime - $starttime); 
$totaltime = number_format($totaltime, 7); 
}
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