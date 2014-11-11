<?
# modif add dformatorigconf bug d'affichage du format date 20011006 16h05

require "modules/XForum/header.php";

$navigation = "<a href=\"modules.php?op=modload&name=XForum&file=indexnav\">"._TEXTINDEX."</a> &gt; "._TEXTCP;
$html = template("header.html");
eval("echo stripslashes(\"$html\");");

if($status != "Administrator") {
echo _NOTADMIN."</body></html>";
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

if($action == "settings") {
if(!$settingsubmit) {

$langfileselect = "<select name=\"langfilenew\">\n";

$dir = opendir("modules/XForum/lang");
while ($thafile = readdir($dir)) {
if (is_file("modules/XForum/lang/$thafile")) {
$thafile = str_replace(".lang.php", "", $thafile);
if ($thafile == "$langfile") {
$langfileselect .= "<option value=\"$thafile\" selected=\"selected\">$thafile</option>\n";
} 
else {
$langfileselect .= "<option value=\"$thafile\">$thafile</option>\n";
}
}
}

$langfileselect .= "</select>";


//----------------------------------//
//   Fabrication du SELECT themes   //
//----------------------------------//
$XFthemelist = "<select name=\"XFthemenew\">\n";
$query = mysql_query("SELECT name FROM $table_themes") or die(mysql_error());
while($XFthemeinfo = mysql_fetch_array($query)) 
{
  if($XFthemeinfo[name] == $XFtheme) 
  { $XFthemelist .= "<option value=\"$XFthemeinfo[name]\" selected=\"selected\">$XFthemeinfo[name]</option>\n"; }
  else 
  { $XFthemelist .= "<option value=\"$XFthemeinfo[name]\">$XFthemeinfo[name]</option>\n"; }
}
$XFthemelist  .= "</select>";


if($bbstatus == "on")			{ $onselect = "selected=\"selected\"";		} 
else							{ $offselect = "selected=\"selected\"";		} 

if($whosonlinestatus == "on")	{ $whosonlineon = "selected=\"selected\"";	} 
else							{ $whosonlineoff = "selected=\"selected\""; }

if($regstatus == "on")			{ $regon = "selected=\"selected\"";			} 
else							{ $regoff = "selected=\"selected\"";		} 

if($regviewonly == "on")		{ $regonlyon = "selected=\"selected\"";		} 
else							{ $regonlyoff = "selected=\"selected\"";	}

if($catsonly == "on")			{ $catsonlyon = "selected=\"selected\"";	} 
else							{ $catsonlyoff = "selected=\"selected\"";	}

if($hideprivate == "on")		{ $hideon = "selected=\"selected\"";		} 
else							{ $hideoff = "selected=\"selected\"";		}

if($showsort == "on")			{ $sorton = "selected=\"selected\"";		} 
else							{ $sortoff = "selected=\"selected\"";		}

if($emailcheck == "on")			{ $echeckon = "selected=\"selected\"";		} 
else							{ $echeckoff = "selected=\"selected\"";		}

if($bbrules == "on")			{ $ruleson = "selected=\"selected\"";		} 
else							{ $rulesoff = "selected=\"selected\"";		}

if($searchstatus == "on") {
$searchon = "selected=\"selected\"";
} else {
$searchoff = "selected=\"selected\"";
}

if($faqstatus == "on") {
$faqon = "selected=\"selected\"";
} else {
$faqoff = "selected=\"selected\"";
}

if($memliststatus == "on") {
$memliston = "selected=\"selected\"";
} else {
$memlistoff = "selected=\"selected\"";
}

if($piconstatus == "on") {
$piconon = "selected=\"selected\"";
} else {
$piconoff = "selected=\"selected\"";
}

if($avastatus == "on") { 
$avataron = "selected=\"selected\""; 
} else { 
$avataroff = "selected=\"selected\""; 
}

if($noreg == "on") { 
$noregon = "selected=\"selected\""; 
} else { 
$noregoff = "selected=\"selected\""; 
}

if($gzipcompress == "on") { 
$gzipcompresson = "selected=\"selected\""; 
} else { 
$gzipcompressoff = "selected=\"selected\""; 
}

if($coppa == "on") { 
$coppaon = "selected=\"selected\""; 
} else {
$coppaoff = "selected=\"selected\""; 
}

if($timeformat == "24") {
$check24 = "checked=\"checked\"";
} else {
$check12 = "checked=\"checked\"";
}

if($statspage == "on") { 
$statson = "selected=\"selected\""; 
} else {
$statsoff = "selected=\"selected\""; 
}

if($affheader == "on") { 
$affheaderon = "selected=\"selected\""; 
} else {
$affheaderoff = "selected=\"selected\""; 
}

if($afffooter == "on") { 
$afffooteron = "selected=\"selected\""; 
} else {
$afffooteroff = "selected=\"selected\""; 
}

if($sigbbcode == "on") {
$sigbbcodeon = "selected=\"selected\""; 
} else {
$sigbbcodeoff = "selected=\"selected\""; 
}

if($sightml == "on") {
$sightmlon = "selected=\"selected\""; 
} else {
$sightmloff = "selected=\"selected\""; 
}

if($indexstats == "on") {
$instatson = "selected=\"selected\""; 
} else {
$instatsoff = "selected=\"selected\""; 
}

if($reportpost == "on") { 
$reportposton = "selected=\"selected\""; 
} else { 
$reportpostoff = "selected=\"selected\""; 
}

if($showtotaltime != "on") { 
$showtotaltimeoff = "selected=\"selected\""; 
} else { 
$showtotaltimeon = "selected=\"selected\""; 
}

if($showtotaltime != "on") { 
$showtotaltimeoff = "selected=\"selected\""; 
} else { 
$showtotaltimeon = "selected=\"selected\""; 
}

if($dupemail != "on") { 
$dupemailoff = "selected=\"selected\""; 
} else { 
$dupemailon = "selected=\"selected\""; 
}
?>
<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp&action=settings">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr class="header">
<td><?=_TEXTSETTING?></td>
<td><?=_TEXTVALUE?></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBBNAME?></td>
<td><input type="text"  value="<?=$bbname?>" name="bbnamenew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTSITENAME?></td>
<td><input type="text"  value="<?=$sitename?>" name="sitenamenew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTSITEURL?></td>
<td><input type="text"  value="<?=$siteurl?>" name="siteurlnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTBOARDURL?></td>
<td><input type="text"  value="<?=$boardurl?>" name="boardurlnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_ADMINEMAIL?></td>
<td><input type="text"  value="<?=$adminemail?>" name="adminemailnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTLANGUAGE?></td>
<td><?=$langfileselect?></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTTHEME?></td>
<td><?=$XFthemelist?></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTPPP?></td>
<td><input type="text" size="2" value="<?=$postperpage?>" name="postperpagenew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTTPP?></td>
<td><input type="text" size="2" value="<?=$topicperpage?>" name="topicperpagenew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTMPP?></td>
<td><input type="text" size="2" value="<?=$memberperpage?>" name="memberperpagenew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTHOTTOPIC?></td>
<td><input type="text" size="2" value="<?=$hottopic?>" name="hottopicnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_TEXTFLOOD?></td>
<td><input type="text" size="2" value="<?=$floodctrl?>" name="floodctrlnew" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTBSTATUS?></td> 
<td><select name="bbstatusnew"><option value="on" <?=$onselect?>><?=_TEXTON?></option><option value="off" <?=$offselect?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTBBOFFREASON?></td> 
<td><textarea rows="3" name="bboffreasonnew" cols="30"><?=$bboffreason?></textarea></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_WHOSONLINE_ON?></td> 
<td><select name="whos_on"><option value="on" <?=$whosonlineon?>><?=_TEXTON?></option><option value="off" <?=$whosonlineoff?>><?=_TEXTOFF?></option></select></td> 
</tr> 

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_REG_ON?></td> 
<td><select name="reg_on"><option value="on" <?=$regon?>><?=_TEXTON?></option><option value="off" <?=$regoff?>><?=_TEXTOFF?></option></select></td> 
</tr> 

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTREGGEDONLY?></td> 
<td><select name="regviewnew"><option value="on" <?=$regonlyon?>><?=_TEXTON?></option><option value="off" <?=$regonlyoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTCATSONLY?></td> 
<td><select name="catsonlynew"><option value="on" <?=$catsonlyon?>><?=_TEXTON?></option><option value="off" <?=$catsonlyoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTHIDEPRIV?></td> 
<td><select name="hidepriv"><option value="on" <?=$hideon?>><?=_TEXTON?></option><option value="off" <?=$hideoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTSHOWSORT?></td> 
<td><select name="showsortnew"><option value="on" <?=$sorton?>><?=_TEXTON?></option><option value="off" <?=$sortoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_EMAILVERIFY?></td> 
<td><select name="emailchecknew"><option value="on" <?=$echeckon?>><?=_TEXTON?></option><option value="off" <?=$echeckoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTBBRULES?></td> 
<td><select name="bbrulesnew"><option value="on" <?=$ruleson?>><?=_TEXTON?></option><option value="off" <?=$rulesoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTBBRULESTXT?></td> 
<td><textarea rows="3" name="bbrulestxtnew" cols="30"><?=$bbrulestxt?></textarea></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTSEARCHSTATUS?></td> 
<td><select name="searchstatusnew"><option value="on" <?=$searchon?>><?=_TEXTON?></option><option value="off" <?=$searchoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTFAQSTATUS?></td> 
<td><select name="faqstatusnew"><option value="on" <?=$faqon?>><?=_TEXTON?></option><option value="off" <?=$faqoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTMEMLISTSTATUS?></td> 
<td><select name="memliststatusnew"><option value="on" <?=$memliston?>><?=_TEXTON?></option><option value="off" <?=$memlistoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_STATSPAGE?></td> 
<td><select name="statspagenew"><option value="on" <?=$statson?>><?=_TEXTON?></option><option value="off" <?=$statsoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_AFFHEADER?></td> 
<td><select name="affheadernew"><option value="on" <?=$affheaderon?>><?=_TEXTON?></option><option value="off" <?=$affheaderoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_AFFFOOTER?></td> 
<td><select name="afffooternew"><option value="on" <?=$afffooteron?>><?=_TEXTON?></option><option value="off" <?=$afffooteroff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTPICONSTATUS?></td> 
<td><select name="piconstatusnew"><option value="on" <?=$piconon?>><?=_TEXTON?></option><option value="off" <?=$piconoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTAVASTATUS?></td> 
<td><select name="avastatusnew"><option value="on" <?=$avataron?>><?=_TEXTON?></option><option value="off" <?=$avataroff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_REPORTPOSTSTATUS?></td> 
<td><select name="reportpostnew"><option value="on" <?=$reportposton?>><?=_TEXTON?></option><option value="off" <?=$reportpostoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_SHOWTOTALTIME?></td> 
<td><select name="showtotaltimenew"><option value="on" <?=$showtotaltimeon?>><?=_TEXTON?></option><option value="off" <?=$showtotaltimeoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_INDEXSTATSCP?></td> 
<td><select name="indexstatsnew"><option value="on" <?=$instatson?>><?=_TEXTON?></option><option value="off" <?=$instatsoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_NOREG?></td> 
<td><select name="noregnew"><option value="on" <?=$noregon?>><?=_TEXTON?></option><option value="off" <?=$noregoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_GZIPCOMPRESSION?></td> 
<td><select name="gzipcompressnew"><option value="on" <?=$gzipcompresson?>><?=_TEXTON?></option><option value="off" <?=$gzipcompressoff?>><?=_TEXTOFF?></option></select></td> 
</tr> 

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_COPPASTATUS?></td> 
<td><select name="coppanew"><option value="on" <?=$coppaon?>><?=_TEXTON?></option><option value="off" <?=$coppaoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<!--#SUPPR###:. here
<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_DUPEMAILCHECK?></td> 
<td><select name="dupemailnew"><option value="on" <?=$dupemailon?>><?=_TEXTON?></option><option value="off" <?=$dupemailoff?>><?=_TEXTOFF?></option></select></td> 
</tr>
#Fin SUPPR###:. -->
<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_SIGBBCODE?></td> 
<td><select name="sigbbcodenew"><option value="on" <?=$sigbbcodeon?>><?=_TEXTON?></option><option value="off" <?=$sigbbcodeoff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_SIGHTML?></td> 
<td><select name="sightmlnew"><option value="on" <?=$sightmlon?>><?=_TEXTON?></option><option value="off" <?=$sightmloff?>><?=_TEXTOFF?></option></select></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td><?=_TEXTTIMEFORMAT?></td> 
<td><input type="radio" value="24" name="timeformatnew" <?=$check24?>><?=_TEXT24HOUR?> <input type="radio" value="12" name="timeformatnew" <?=$check12?>><?=_TEXT12HOUR?></td> 
</tr>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=_DATEFORMAT?></td>
<td><input type="text"  value="<?=$dformatorigconf?>" name="dateformatnew" /></td>
</tr>

</table>
</td></tr></table>

<center><input type="submit" name="settingsubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
</form>

</td>
</tr>
<!--#DM###:.
</table>
</td></tr></table>
<!--#FM###:.-->
<?
}

if($settingsubmit) {
$bbrulestxtnew = addslashes($bbrulestxtnew);
$bboffreasonnew = addslashes($bboffreasonnew);

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
	$content .= "\$afffooter = \"".$afffooternew."\";\n";
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


echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTSETTINGSUPDATE."</td></tr>";
}
}



if($action == "forum") {
if(!$forumsubmit && !$fdetails) {
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp&action=forum">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr>
<td class="header"><?=_TEXTFORUMOPTS?></td>
</tr>

<?

$queryf = mysql_query("SELECT * FROM $table_forums WHERE type='forum' AND fup='' ORDER BY displayorder") or die(mysql_error());
while($forum = mysql_fetch_array($queryf)) {

if($forum[status] == "on") {
$on = "selected=\"selected\"";
} else {
$off = "selected=\"selected\"";
}

?>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td class="11px"><input type="checkbox" name="delete<?=$forum[fid]?>" value="<?=$forum[fid]?>" /> 
&nbsp;<input type="text" name="name<?=$forum[fid]?>" value="<?=$forum[name]?>" />
&nbsp; <?=_TEXTORDER?> <input type="text" name="displayorder<?=$forum[fid]?>" size="2" value="<?=$forum[displayorder]?>" />
&nbsp; <select name="status<?=$forum[fid]?>">
<option value="on" <?=$on?>><?=_TEXTON?></option><option value="off" <?=$off?>><?=_TEXTOFF?></option></select>
&nbsp; <select name="moveto<?=$forum[fid]?>"><option value="" selected="selected">-<?=_TEXTNONE?>-</option>
<?
$movequery = mysql_query("SELECT * FROM $table_forums WHERE type='group' ORDER BY displayorder") or die(mysql_error());
while($moveforum = mysql_fetch_array($movequery)) {
echo "<option value=\"$moveforum[fid]\">$moveforum[name]</option>";
}
?>
</select>
<a href="modules.php?op=modload&name=XForum&file=cp&action=forum&fdetails=<?=$forum[fid]?>"><?=_TEXTMOREOPTS?></a></td>
</tr>

<?
$querys = mysql_query("SELECT * FROM $table_forums WHERE type='sub' AND fup='$forum[fid]' ORDER BY displayorder") or die(mysql_error());
while($forum = mysql_fetch_array($querys)) {

if($forum[status] == "on") {
$on = "selected=\"selected\"";
} else {
$off = "selected=\"selected\"";
}
?>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td class="11px"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="delete<?=$forum[fid]?>" value="<?=$forum[fid]?>" /> 
&nbsp;<input type="text" name="name<?=$forum[fid]?>" value="<?=$forum[name]?>" />
&nbsp; <?=_TEXTORDER?> <input type="text" name="displayorder<?=$forum[fid]?>" size="2" value="<?=$forum[displayorder]?>" />
&nbsp; <select name="status<?=$forum[fid]?>">
<option value="on" <?=$on?>><?=_TEXTON?></option><option value="off" <?=$off?>><?=_TEXTOFF?></option></select>
&nbsp; <select name="moveto<?=$forum[fid]?>">
<?
$movequery = mysql_query("SELECT * FROM $table_forums WHERE type='forum' ORDER BY displayorder") or die(mysql_error());
while($moveforum = mysql_fetch_array($movequery)) {
if($moveforum[fid] == $forum[fid]) {
echo "<option value=\"$moveforum[fid]\" selected=\"selected\">$moveforum[name]</option>";
} else {
echo "<option value=\"$moveforum[fid]\">$moveforum[name]</option>";
}
}
?>
</select> 
<a href="modules.php?op=modload&name=XForum&file=cp&action=forum&fdetails=<?=$forum[fid]?>"><?=_TEXTMOREOPTS?></a></td>
</tr>

<?
$on = "";
$off = "";
}

$on = "";
$off = "";
}


$queryg = mysql_query("SELECT * FROM $table_forums WHERE type='group' ORDER BY displayorder") or die(mysql_error());
while($group = mysql_fetch_array($queryg)) {

if($group[status] == "on") {
$on = "selected=\"selected\"";
} else {
$off = "selected=\"selected\"";
}
?>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td class="11px"><input type="checkbox" name="delete<?=$group[fid]?>" value="<?=$group[fid]?>" />
 <input type="text" name="name<?=$group[fid]?>" value="<?=$group[name]?>" />
&nbsp; <?=_TEXTORDER?> <input type="text" name="displayorder<?=$group[fid]?>" size="2" value="<?=$group[displayorder]?>" />
&nbsp; <select name="status<?=$group[fid]?>">
<option value="on" <?=$on?>><?=_TEXTON?></option><option value="off" <?=$off?>><?=_TEXTOFF?></option></select>
</td>
</tr>

<?
$queryf = mysql_query("SELECT * FROM $table_forums WHERE type='forum' AND fup='$group[fid]' ORDER BY displayorder") or die(mysql_error());
while($forum = mysql_fetch_array($queryf)) {

if($forum[status] == "on") {
$on = "selected=\"selected\"";
} else {
$off = "selected=\"selected\"";
}
?>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td class="11px"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type="checkbox" name="delete<?=$forum[fid]?>" value="<?=$forum[fid]?>" /> 
&nbsp;<input type="text" name="name<?=$forum[fid]?>" value="<?=$forum[name]?>" />
&nbsp; <?=_TEXTORDER?> <input type="text" name="displayorder<?=$forum[fid]?>" size="2" value="<?=$forum[displayorder]?>" />
&nbsp; <select name="status<?=$forum[fid]?>">
<option value="on" <?=$on?>><?=_TEXTON?></option><option value="off" <?=$off?>><?=_TEXTOFF?></option></select>
&nbsp; <select name="moveto<?=$forum[fid]?>"><option value="">-<?=_TEXTNONE?>-</option>
<?
$movequery = mysql_query("SELECT * FROM $table_forums WHERE type='group' ORDER BY displayorder") or die(mysql_error());
while($moveforum = mysql_fetch_array($movequery)) {
if($moveforum[fid] == $forum[fup]) {
$curgroup = "selected=\"selected\"";
} else { 
$curgroup = "";
}
echo "<option value=\"$moveforum[fid]\" $curgroup>$moveforum[name]</option>";
}
?>
</select>
<a href="modules.php?op=modload&name=XForum&file=cp&action=forum&fdetails=<?=$forum[fid]?>"><?=_TEXTMOREOPTS?></a></td>
</tr>

<?
$querys = mysql_query("SELECT * FROM $table_forums WHERE type='sub' AND fup='$forum[fid]' ORDER BY displayorder") or die(mysql_error());
while($forum = mysql_fetch_array($querys)) {

if($forum[status] == "on") {
$on = "selected=\"selected\"";
} else {
$off = "selected=\"selected\"";
}
?>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td class="11px"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;<input type="checkbox" name="delete<?=$forum[fid]?>" value="<?=$forum[fid]?>" /> 
&nbsp;<input type="text" name="name<?=$forum[fid]?>" value="<?=$forum[name]?>" />
&nbsp; <?=_TEXTORDER?> <input type="text" name="displayorder<?=$forum[fid]?>" size="2" value="<?=$forum[displayorder]?>" />
&nbsp; <select name="status<?=$forum[fid]?>">
<option value="on" <?=$on?>><?=_TEXTON?></option><option value="off" <?=$off?>><?=_TEXTOFF?></option></select>
&nbsp; <select name="moveto<?=$forum[fid]?>">
<?
$movequery = mysql_query("SELECT * FROM $table_forums WHERE type='forum' ORDER BY displayorder") or die(mysql_error());
while($moveforum = mysql_fetch_array($movequery)) {
if($moveforum[fid] == $forum[fup]) {
echo "<option value=\"$moveforum[fid]\" selected=\"selected\">$moveforum[name]</option>";
} else {
echo "<option value=\"$moveforum[fid]\">$moveforum[name]</option>";
}
}
?>
</select>
<a href="modules.php?op=modload&name=XForum&file=cp&action=forum&fdetails=<?=$forum[fid]?>"><?=_TEXTMOREOPTS?></a></td>
</tr>

<?
$on = "";
$off = "";
}

$on = "";
$off = "";
}

$on = "";
$off = "";
}
?>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td class="11px"><input type="text" name="newgname" value="<?=_TEXTNEWGROUP?>" />
&nbsp; <?=_TEXTORDER?> <input type="text" name="newgorder" size="2" />
&nbsp; <select name="newgstatus">
<option value="on"><?=_TEXTON?></option><option value="off"><?=_TEXTOFF?></option></select></td>
</tr>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td class="11px"><input type="text" name="newfname" value="<?=_TEXTNEWFORUM1?>" />
&nbsp; <?=_TEXTORDER?> <input type="text" name="newforder" size="2" />
&nbsp; <select name="newfstatus">
<option value="on"><?=_TEXTON?></option><option value="off"><?=_TEXTOFF?></option></select>
&nbsp; <select name="newffup"><option value="" selected="selected">-<?=_TEXTNONE?>-</option>
<?
$gquery = mysql_query("SELECT * FROM $table_forums WHERE type='group' ORDER BY displayorder") or die(mysql_error());
while($group = mysql_fetch_array($gquery)) {
echo "<option value=\"$group[fid]\">$group[name]</option>";
}
?>
</select>
</td></tr>

<tr bgcolor="<?=$altbg1?>" class="tablerow">
<td class="11px"><input type="text" name="newsubname" value="<?=_TEXTNEWSUBF?>" />
&nbsp; <?=_TEXTORDER?> <input type="text" name="newsuborder" size="2" />
&nbsp; <select name="newsubstatus"><option value="on"><?=_TEXTON?></option><option value="off"><?=_TEXTOFF?></option></select>
&nbsp; <select name="newsubfup">
<?
$fquery = mysql_query("SELECT * FROM $table_forums WHERE type='forum' ORDER BY displayorder") or die(mysql_error());
while($group = mysql_fetch_array($fquery)) {
echo "<option value=\"$group[fid]\">$group[name]</option>";
}
?>
</select>
</td></tr>

</table>
</td></tr></table>
<center><input type="submit" name="forumsubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
</form>

</td>
</tr>

<?
}

if($fdetails && !$forumsubmit) {
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp&action=forum&fdetails=<?=$fdetails?>">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr>
<td class="header" colspan="2"><?=_TEXTFORUMOPTS?></td>
</tr>

<?
$queryg = mysql_query("SELECT * FROM $table_forums WHERE fid='$fdetails'") or die(mysql_error());
$forum = mysql_fetch_array($queryg);

$XFthemelist = "<select name=\"XFthemeforumnew\">";
$querytheme = mysql_query("SELECT name FROM $table_themes") or die(mysql_error());
while($XFtheme = mysql_fetch_array($querytheme)) {
if($XFtheme[name] == $forum[theme]) {
$XFthemelist .= "<option value=\"$XFtheme[name]\" selected=\"selected\">$XFtheme[name]</option>\n";
}
else {
$XFthemelist .= "<option value=\"$XFtheme[name]\">$XFtheme[name]</option>\n";
}
}
$XFthemelist  .= "</select>";


if($forum[private] == "staff") {
$checked1 = "checked=\"checked\"";
} else {
$checked1 = "";
}

if($forum[allowhtml] == "yes") {
$checked2 = "checked=\"checked\"";
} else {
$checked2 = "";
}

if($forum[allowsmilies] == "yes") {
$checked3 = "checked=\"checked\"";
} else {
$checked3 = "";
}

if($forum[allowbbcode] == "yes")		{ $checked4 = "checked=\"checked\""; } 
else									{ $checked4 = ""; }

if($forum[guestposting] == "yes") {
$checked5 = "checked=\"checked\"";
} else {
$checked5 = "";
}

if($forum[allowimgcode] == "yes") { 
$checked6 = "checked=\"checked\""; 
} else { 
$checked6 = ""; 
}

$pperm = explode("|", $forum[postperm]);

if($pperm[0] == "2") {
$type12 = "selected=\"selected\"";
} elseif($pperm[0] == "3") {
$type13 = "selected=\"selected\"";
} elseif($pperm[0] == "4") {
$type14 = "selected=\"selected\"";
} elseif($pperm[0] == "1") {
$type11 = "selected=\"selected\"";
}

if($pperm[1] == "2") {
$type22 = "selected=\"selected\"";
} elseif($pperm[1] == "3") {
$type23 = "selected=\"selected\"";
} elseif($pperm[0] == "4") {
$type24 = "selected=\"selected\"";
} elseif($pperm[1] == "1") {
$type21 = "selected=\"selected\"";
}


$forum[private] = str_replace("pw|", "", $forum[private]);
?>

<tr bgcolor="<?=$altbg2?>">
<td class="tablerow"><?=_TEXTFORUMNAME?></td>
<td><input type="text" name="namenew" value="<?=$forum[name]?>" /></td>
</tr>

<tr bgcolor="<?=$altbg2?>">
<td class="tablerow"><?=_TEXTDESC?></td>
<td><textarea rows="4" cols="30" name="descnew"><?=$forum[description]?></textarea></td>
</tr>

<tr bgcolor="<?=$altbg2?>">
<td class="tablerow"><?=_TEXTALLOW?></td>
<td class="11px"><input type="checkbox" name="allowhtmlnew" value="yes" <?=$checked2?> /><?=_TEXTHTML?><br />
<input type="checkbox" name="allowsmiliesnew" value="yes" <?=$checked3?> /><?=_TEXTSMILIES?><br />
<input type="checkbox" name="allowbbcodenew" value="yes" <?=$checked4?> /><?=_TEXTBBCODE?><br />
<input type="checkbox" name="guestpostingnew" value="yes" <?=$checked5?> /><?=_TEXTGUESTPOSTING?><br />
<input type="checkbox" name="allowimgcodenew" value="yes" <?=$checked6?> /><?=_TEXTIMGCODE?></td>
</tr>

<tr bgcolor="<?=$altbg2?>">
<td class="tablerow"><?=_TEXTTHEME?></td>
<td><?=$XFthemelist?></td>
</tr>

<tr bgcolor="<?=$altbg2?>">
<td class="tablerow"><?=_WHOPOSTOP1?></td>
<td><select name="postperm1">
<option value="1" <?=$type11?>><?=_TEXTPOSTPERMISSION1?></option>
<option value="2" <?=$type12?>><?=_TEXTPOSTPERMISSION2?></option>
<option value="3" <?=$type13?>><?=_TEXTPOSTPERMISSION3?></option>
<option value="4" <?=$type14?>><?=_TEXTPOSTPERMISSION4?></option>
</td>
</tr>

<tr bgcolor="<?=$altbg2?>">
<td class="tablerow"><?=_WHOPOSTOP2?></td>
<td><select name="postperm2">
<option value="1" <?=$type21?>><?=_TEXTPOSTPERMISSION1?></option>
<option value="2" <?=$type22?>><?=_TEXTPOSTPERMISSION2?></option>
<option value="3" <?=$type23?>><?=_TEXTPOSTPERMISSION3?></option>
<option value="4" <?=$type24?>><?=_TEXTPOSTPERMISSION4?></option>
</td>
</tr>

<tr bgcolor="<?=$altbg2?>">
<td class="tablerow"><?=_TEXTUSERLIST?></td>
<td><textarea rows="4" cols="30" name="userlistnew"><?=$forum[userlist]?></textarea></td>
</tr>

<tr bgcolor="<?=$altbg2?>">
<td class="tablerow"><?=_TEXTSTAFFONLY?></td>
<td><input type="checkbox" name="privatenew" value="staff" <?=$checked1?> /></td>
</tr>

<tr bgcolor="<?=$altbg2?>">
<td class="tablerow"><?=_TEXTDELETEQUES?></td>
<td><input type="checkbox" name="delete" value="<?=$forum[fid]?>" /></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="forumsubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
</form>

</td>
</tr>
<?
}

if($forumsubmit) {
if(!$fdetails) {
$queryforum = mysql_query("SELECT fid, type FROM $table_forums WHERE type='forum' OR type='sub'") or die(mysql_error());
while($forum = mysql_fetch_array($queryforum)) {
$displayorder = "displayorder$forum[fid]";
$displayorder = "${$displayorder}";
$name = "name$forum[fid]";
$name = "${$name}";
$status = "status$forum[fid]";
$status = "${$status}";
$delete = "delete$forum[fid]";
$delete = "${$delete}";
$moveto = "moveto$forum[fid]";
$moveto = "${$moveto}";

if($delete != "") {
mysql_query("DELETE FROM $table_forums WHERE (type='forum' OR type='sub') AND fid='$delete'") or die(mysql_error());

$querythread = mysql_query("SELECT * FROM $table_threads WHERE fid='$delete'") or die(mysql_error());
while($thread = mysql_fetch_array($querythread)) {
mysql_query("DELETE FROM $table_threads WHERE tid='$thread[tid]'") or die(mysql_error());
mysql_query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$thread[author]'") or die(mysql_error());

$querypost = mysql_query("SELECT * FROM $table_posts WHERE tid='$thread[tid]'") or die(mysql_error());
while($post = mysql_fetch_array($querypost)) {
mysql_query("DELETE FROM $table_posts WHERE pid='$post[pid]'") or die(mysql_error());
mysql_query("UPDATE $table_members SET postnum=postnum-1 WHERE username='$post[author]'") or die(mysql_error());
}
}
}

mysql_query("UPDATE $table_forums SET name='$name', displayorder='$displayorder', status='$status', fup='$moveto' WHERE fid='$forum[fid]'") or die(mysql_error());
}


$querygroup = mysql_query("SELECT fid FROM $table_forums WHERE type='group'") or die(mysql_error());
while($group = mysql_fetch_array($querygroup)) {
$name = "name$group[fid]";
$name = "${$name}";
$displayorder = "displayorder$group[fid]";
$displayorder = "${$displayorder}";
$status = "status$group[fid]";
$status = "${$status}";
$delete = "delete$group[fid]";
$delete = "${$delete}";

if($delete != "") {
$query = mysql_query("SELECT fid FROM $table_forums WHERE type='forum' AND fup='$delete'") or die(mysql_error());
while($forum = mysql_fetch_array($query)) {
mysql_query("UPDATE $table_forums SET fup='' WHERE type='forum' AND fup='$delete'") or die(mysql_error());
}

mysql_query("DELETE FROM $table_forums WHERE type='group' AND fid='$delete'") or die(mysql_error());
}

mysql_query("UPDATE $table_forums SET name='$name', displayorder='$displayorder', status='$status' WHERE fid='$group[fid]'") or die(mysql_error());
}

if($newfname != _TEXTNEWFORUM1) { 
mysql_query("INSERT INTO $table_forums VALUES ('forum', '', '$newfname', '$newfstatus', '', '', '$newforder', '', '', 'no', 'yes', 'yes', 'no', '', '', '0', '0', '$newffup', '1', 'yes')") or die(mysql_error()); 
}

if($newgname != _TEXTNEWGROUP) { 
mysql_query("INSERT INTO $table_forums VALUES ('group', '', '$newgname', '$newgstatus', '', '', '$newgorder', '', '', '', '', '', '', '', '', '0', '0', '', '', '')") or die(mysql_error()); 
}

if($newsubname != _TEXTNEWSUBF) { 
mysql_query("INSERT INTO $table_forums VALUES ('sub', '', '$newsubname', '$newsubstatus', '', '', '$newsuborder', '', '', 'no', 'yes', 'yes', 'no', '', '', '0', '0', '$newsubfup', '1', 'yes')") or die(mysql_error()); 
}

echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTFORUMUPDATE."</td></tr>";
}
else {
mysql_query("UPDATE $table_forums SET name='$namenew', description='$descnew', allowhtml='$allowhtmlnew', allowsmilies='$allowsmiliesnew', allowbbcode='$allowbbcodenew', guestposting='$guestpostingnew', theme='$XFthemeforumnew', userlist='$userlistnew', private='$privatenew', postperm='$postperm1|$postperm2', allowimgcode='$allowimgcodenew' WHERE fid='$fdetails'") or die(mysql_error());
if($delete != "") {
mysql_query("DELETE FROM $table_forums WHERE fid='$delete'") or die(mysql_error());
}

echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTFORUMUPDATE."</td></tr>";
}
}
}



if($action == "mods") {
if(!$modsubmit) {
?>

<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />
<form method="post" action="modules.php?op=modload&name=XForum&file=cp&action=mods">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr class="header">
<td><?=_TEXTFORUM?></td>
<td><?=_TEXTMODERATOR?></td>
</tr>

<?
$queryf = mysql_query("SELECT name, moderator, fid FROM $table_forums WHERE type='forum'") or die(mysql_error());
while($mod = mysql_fetch_array($queryf)) {
?>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><?=$mod[name]?></td>
<td><input type="text" name="mod<?=$mod[fid]?>" value="<?=$mod[moderator]?>" /></td>
</tr>

<?

$querys = mysql_query("SELECT name, moderator, fid FROM $table_forums WHERE type='sub' AND fup='$mod[fid]' ORDER BY displayorder") or die(mysql_error());
while($mod = mysql_fetch_array($querys)) {
?>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <?=$mod[name]?></td>
<td> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type="text" name="mod<?=$mod[fid]?>" value="<?=$mod[moderator]?>" /></td>
</tr>

<?
}

}
?>

</table>
</td></tr></table>
<span class="11px"><?=_MULTMODNOTE?></span>
<center><input type="submit" name="modsubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
</form>

</td>
</tr>

<?
}

if($modsubmit) {
$queryforum = mysql_query("SELECT fid FROM $table_forums") or die(mysql_error());

while($forum = mysql_fetch_array($queryforum)) {
$mod = "mod$forum[fid]";
$mod = "${$mod}";
mysql_query("UPDATE $table_forums SET moderator='$mod' WHERE fid='$forum[fid]'") or die(mysql_error());


$modz = explode(", ", $mod);
for($num = 0; $num < count($modz); $num++) {

if($modz[$num] != "") {
$query = mysql_query("SELECT status FROM $table_members WHERE username='$modz[$num]'") or die(mysql_error());
$userinfo = mysql_fetch_array($query);

if($userinfo[status] != "Administrator" && $userinfo[status] != "Super Moderator") {
mysql_query("UPDATE $table_members SET status='Moderator' WHERE username='$modz[$num]'") or die(mysql_error());
}
else {
echo "";
}
}

}
}

echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTMODUPDATE."</td></tr>";
}
}



if($action == "members") {
if(!$membersubmit) {
?>
<tr bgcolor="<?=$altbg2?>">
<td align="center">
<br />

<?
if(!$members) {
?>

<form method="post" action="modules.php?op=modload&name=XForum&file=cp&action=members&members=search">
<span class="12px"><?=_TEXTSRCHUSR?></span> <input type="text" name="srchmem"><br />
<span class="12px"><?=_TEXTWITHSTATUS?></span> 

<select name="srchstatus">
<option value="0"><?=_ANYSTATUS?></option>
<option value="Administrator"><?=_TEXTADMIN?></option>
<option value="Super Moderator"><?=_TEXTSUPERMOD?></option>
<option value="Moderator"><?=_TEXTMOD?></option>
<option value="Member"><?=_TEXTMEM?></option>
<option value="Banned"><?=_TEXTBANNED?></option>
</select><br />
<input type="submit" value="<?=_TEXTGO?>" />
</form>
</td></tr>

<?
}

if($members == "search") {
?>
<form method="post" action="modules.php?op=modload&name=XForum&file=cp&action=members">
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr class="header">
<td><?=_TEXTDELETEQUES?></td>
<td><?=_TEXTUSERNAME?></td>
<td><?=_TEXTPASSWORD?></td>
<td><?=_TEXTPOSTS?></td>
<td><?=_TEXTSTATUS?></td>
<td><?=_TEXTCUSSTATUS?></td>	
</tr>

<?
if($srchstatus == "0") {
$query = mysql_query("SELECT * FROM $table_members WHERE username LIKE '%$srchmem%' ORDER BY username") or die(mysql_error());
} else {
$query = mysql_query("SELECT * FROM $table_members WHERE username LIKE '%$srchmem%' AND status='$srchstatus' ORDER BY username") or die(mysql_error());
}
while($member = mysql_fetch_array($query)) {

if($member[status] == "Administrator") { 
$adminselect = "selected=\"selected\"";
}

if($member[status] == "Super Moderator") { 
$smodselect = "selected=\"selected\"";
} 

if($member[status] == "Moderator") { 
$modselect = "selected=\"selected\"";
} 

if($member[status] == "Member") { 
$memselect = "selected=\"selected\"";
} 

if($member[status] == "Banned") { 
$banselect = "selected=\"selected\"";
}
?>

<tr bgcolor="<?=$altbg2?>" class="tablerow">
<td><input type="checkbox" name="delete<?=$member[uid]?>" value="<?=$member[uid]?>" /></td>
<td><a href="modules.php?op=modload&name=XForum&file=member&action=editpro&username=<?=$member[username]?>&password=<?=$member[password]?>&editlogsubmit=Edit%20Profile"><?=$member[username]?></a></td>
<td><input type="text" size="12" name="pw<?=$member[uid]?>" value="<?=$member[password]?>" /></td>
<td><?=$member[postnum]?></td>
<td><select name="status<?=$member[uid]?>">
<option value="Administrator" <?=$adminselect?>><?=_TEXTADMIN?></option>
<option value="Super Moderator" <?=$smodselect?>><?=_TEXTSUPERMOD?></option>
<option value="Moderator" <?=$modselect?>><?=_TEXTMOD?></option>
<option value="Member" <?=$memselect?>><?=_TEXTMEM?></option>
<option value="Banned" <?=$banselect?>><?=_TEXTBANNED?></option>
</select></td>
<td><input type="text" size="20" name="cusstatus<?=$member[uid]?>" value="<?=$member[customstatus]?>" /></td>
</tr>

<?
$adminselect = "";
$smodselect = "";
$modselect = "";
$memselect = "";
$banselect = "";
}
?>

</table>
</td></tr></table>
<center><input type="submit" name="membersubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center>
<input type="hidden" name="srchmem" value="<?=$srchmem?>">
<input type="hidden" name="srchstatus" value="<?=$srchstatus?>">
</form>

</td>
</tr>

<?
}
}

if($membersubmit) {
if($srchstatus == "0") {
$query = mysql_query("SELECT uid, username FROM $table_members WHERE username LIKE '%$srchmem%'") or die(mysql_error());
} else {
$query = mysql_query("SELECT uid, username FROM $table_members WHERE username LIKE '%$srchmem%' AND status='$srchstatus'") or die(mysql_error());
}

while($mem = mysql_fetch_array($query)) {
$status = "status$mem[uid]";
$status = "${$status}";
$cusstatus = "cusstatus$mem[uid]";
$cusstatus = "${$cusstatus}";
$pw = "pw$mem[uid]";
$pw = "${$pw}";
$delete = "delete$mem[uid]";
$delete = "${$delete}";

if($delete != "") {
mysql_query("DELETE FROM $table_members WHERE uid='$delete'") or die(mysql_error());
}
else {
if(ereg('"', $pw) || ereg("'", $pw)) 
{
 define("_textmembersupdate", "$mem[username]: "._TEXTPWINCORRECT."");
} else {
	$newcustom = addslashes($cusstatus);
mysql_query("UPDATE $table_members SET status='$status', customstatus='$newcustom', password='$pw' WHERE uid='$mem[uid]'") or die(mysql_error());
}
}
}


echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._TEXTMEMBERSUPDATE."</td></tr>";
}
}



if($action == "ipban") { 
if(!$ipbansubmit) { 
?> 

<tr bgcolor="<?=$altbg2?>"> 
<td align="center"> 
<br /> 
<form method="post" action="modules.php?op=modload&name=XForum&file=cp&action=ipban"> 
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center"> 
<tr><td bgcolor="<?=$bordercolor?>"> 

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"> 
<tr> 
<td class="header"><?=_TEXTDELETEQUES?></td> 
<td class="header"><?=_TEXTIP?>:</td> 
<td class="header"><?=_TEXTADDED?></td> 
</tr> 

<? 
$query = mysql_query("SELECT * FROM $table_banned ORDER BY dateline") or die(mysql_error()); 
while($ipaddress = mysql_fetch_array($query)) { 

for($i=1; $i<=4; ++$i) { 
$j = "ip" . $i; 
if ($ipaddress[$j] == -1) $ipaddress[$j] = "*"; 
} 

$ipdate = date("n/j/y", $ipaddress[dateline] + ($timeoffset * 3600)) . " "._TEXTAT." " . date("$timecode", $ipaddress[dateline] + ($timeoffset * 3600)); 
$theip = "$ipaddress[ip1].$ipaddress[ip2].$ipaddress[ip3].$ipaddress[ip4]";
?> 

<tr bgcolor="<?=$altbg2?>"> 
<td class="tablerow"><input type="checkbox" name="delete<?=$ipaddress[id]?>" value="<?=$ipaddress[id]?>" /></td> 
<td class="tablerow"><?=$theip?></td> 
<td class="tablerow"><?=$ipdate?></td> 
</tr> 

<? 
} 
$query = mysql_query("SELECT id FROM $table_banned WHERE (ip1='$ips[0]' OR ip1='-1') AND (ip2='$ips[1]' OR ip2='-1') AND (ip3='$ips[2]' OR ip3='-1') AND (ip4='$ips[3]' OR ip4='-1')") or die(mysql_error()); 
$result = mysql_fetch_array($query); 
if ($result) $warning = _IPWARNING; 
?> 
<tr bgcolor="<?=$altbg2?>"><td colspan="3"> </td></tr> 
<tr bgcolor="<?=$altbg1?>"> 
<td colspan="3" class="tablerow"><?=_TEXTNEWIP?>   
<input type="text" name="newip1" size="3" maxlength="3" />.<input type="text" name="newip2" size="3" maxlength="3" />.<input type="text" name="newip3" size="3" maxlength="3" />.<input type="text" name="newip4" size="3" maxlength="3" /></td> 
</tr> 

</table> 
</td></tr></table> 
<span class="11px"><?=_CURRENTIP?> <b><?=$onlineip?></b><?=$warning?><br /><?=_MULTIPNOTE?></span> 
<center><input type="submit" name="ipbansubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center> 
</form> 

</td> 
</tr> 

<? 
} 

if($ipbansubmit) { 
$queryip = mysql_query("SELECT id FROM $table_banned") or die(mysql_error()); 
$newid = 1; 
while($ip = mysql_fetch_array($queryip)) { 
$delete = "delete$ip[id]"; 
$delete = "${$delete}";

if($delete != "") { 
$query = mysql_query("DELETE FROM $table_banned WHERE id='$delete'") or die(mysql_error()); 
} 
elseif($ip[id] > $newid) { 
$query = mysql_query("UPDATE $table_banned SET id='$newid' WHERE id='$ip[id]'") or die(mysql_error()); 
} 
$newid++;
}

$status = _TEXTIPUPDATE; 

if($newip1 != "" || $newip2 != "" || $newip3 != "" || $newip4 != "") { 

$invalid = 0;

for($i=1;$i<=4 && !$invalid;++$i) { 
$newip = "newip$i"; 
$newip = "${$newip}"; 
$newip = trim($newip); 
if ($newip == "*") $ip[$i] = -1; 
elseif (ereg("^[0-9]+$", $newip)) $ip[$i] = $newip; 
else $invalid = 1; 
} 

if ($invalid) $status = _INVALIDIP; 
else { 
$query = mysql_query("SELECT id FROM $table_banned WHERE (ip1='$ip[1]' OR ip1='-1') AND (ip2='$ip[2]' OR ip2='-1') AND (ip3='$ip[3]' OR ip3='-1') AND (ip4='$ip[4]' OR ip4='-1')") or die(mysql_error()); 
$result = mysql_fetch_array($query); 
if ($result) $status = _EXISTINGIP; 
else $query = mysql_query("INSERT INTO $table_banned VALUES ('$ip[1]', '$ip[2]', '$ip[3]', '$ip[4]', '$onlinetime', '$newid')") or die(mysql_error()); 
} 
} 

echo "<tr bgcolor=\"$altbg2\"><td align=\"center\" class=\"tablerow\">$status</td></tr>";
} 
}



if($action == "upgrade") { 
if($upgradesubmit) { 

$explode = explode(";", $upgrade); 
$count = sizeof($explode); 

for($num=0;$num<$count;$num++) { 
$explode[$num]=stripslashes($explode[$num]); 
if($explode[$num] != "") { 
mysql_query("$explode[$num]") or die(mysql_error()); 
} 
}
echo "<tr bgcolor=\"$altbg2\" class=\"tablerow\"><td align=\"center\">"._UPGRADESUCCESS." </td></tr>"; 
} 
if(!$upgradesubmit) { 

?> 

<tr bgcolor="<?=$altbg2?>"> 
<td align="center"> 
<br /> 
<form method="post" action="modules.php?op=modload&name=XForum&file=cp&action=upgrade"> 
<table cellspacing="0" cellpadding="0" border="0" width="93%" align="center"> 
<tr><td bgcolor="<?=$bordercolor?>"> 

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"> 

<tr class="header"> 
<td colspan=2><?=_TEXTUPGRADE?></td> 
</tr> 

<tr bgcolor="<?=$altbg1?>" class="tablerow"> 
<td valign="top"><?=_UPGRADE?><br /><textarea cols="35" rows="7" name="upgrade"></textarea><br /><?=_UPGRADENOTE?></td> 
</tr> 
</table> 
</td></tr></table> 
<center><input type="submit" name="upgradesubmit" value="<?=_TEXTSUBMITCHANGES?>" /></center> 
</form> 

</td> 
</tr> 

<? 
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