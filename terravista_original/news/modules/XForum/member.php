<?
# modif suppr mysql_resul t 20011001 13h28
# modif add dformatorigconf bug d'affichage du format date 20011006 16h05
# modif synchronisation signature 20011006 18h05
# modif ajout grade 20011007 14h05

require "modules/XForum/header.php";

if ($action == "reg")		{ $memberaction = _TEXTREGISTER;	}
if ($action == "list")		{ $memberaction = _TEXTMEMBERLIST; }
if ($action == "viewpro")	{ $memberaction = _TEXTVIEWPRO;		}
if ($action == "editpro")	{ $memberaction = _TEXTEDITPRO;		}
if ($action == "coppa")		{ $memberaction = _TEXTCOPPA;		}

$navigation = "<a href=\""._BASEMOD."indexnav\">"._TEXTINDEX."</a> &gt; $memberaction";
$html = template("header.html");
eval("echo stripslashes(\"$html\");");


if($action == "coppa") { 
if($coppasubmit) { 
?> 

<!--#DM###:.
<script> location.href="modules.php?op=modload&name=XForum&file=member&action=reg";</script> 
<!--#FM###:.--> 
<script> location.href="user.php";</script> 
<? 
} else { 
?> 
<!--#DM###:.
<form method="post" action="modules.php?op=modload&name=XForum&file=member&action=reg">
<!--#FM###:.--> 
<form method="post" action="user.php">
<!--#FR###:.--> 
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center"> 
<tr><td bgcolor="<?=$bordercolor?>"> 

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%"> 
<tr class="header"> 
<td colspan="2"><?=_TEXTCOPPA?></td> 
</tr> 

<tr bgcolor="<?=$altbg1?>" class="tablerow"> 
<td><center><?=_TEXTCOPPAWORDING?></center></td> 
</tr> 
<tr bgcolor="<?=$altbg2?>" class="tablerow"> 
<td> 
<center><input type="submit" name="coppasubmit" value="<?=_COPPAAGREE?>" /></center> 
</td> 
</tr> 
</table> 
</td></tr></table> 
</form> 

<? 
} 
}


if ($action == "reg") 
{
  if(($regstatus == "off" || $noreg == "on") && $status != "Administrator") { 
  echo _REGOFF;
  exit;
}

if(!$regsubmit) {

if($bbrules == "on" && !$rulesubmit) {
?>
<!--#DM###:.
<form method="post" action="modules.php?op=modload&name=XForum&file=member&action=reg">
#FM###:.-->
<!--#DR###:. Par ceci-->
<form method="post" action="user.php">
<!--#FR###:.-->

<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr>
<td class="header"><?=_TEXTREGISTER?></td>
</tr>

<tr bgcolor="<?=$altbg1?>">
<td width="22%" class="tablerow"><?=$bbrulestxt?></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="rulesubmit" value="<?=_TEXTAGREE?>" /></center>
</form>
<?
}
else {

$currdate = gmdate("$timecode");
eval(_EVALOFFSET);

$XFthemelist = "<select name=\"XFthememem\">\n";
$query = mysql_query("SELECT name FROM $table_themes") or die(mysql_error());
while($XFthemeinfo = mysql_fetch_array($query)) {
if($XFtheme == $XFthemeinfo[name]) {
$XFthemelist .= "<option value=\"$XFthemeinfo[name]\" selected=\"selected\">$XFthemeinfo[name]</option>\n";
} else {
$XFthemelist .= "<option value=\"$XFthemeinfo[name]\">$XFthemeinfo[name]</option>\n";
}
}
$XFthemelist  .= "</select>";


$langfileselect = "<select name=\"langfile\">\n";
$dir = opendir("modules/XForum/lang");
while ($thafile = readdir($dir)) {
if (is_file("modules/XForum/lang/$thafile")) {
$thafile = str_replace(".lang.php", "", $thafile);
if ($thafile == "$bblang") {
$langfileselect .= "<option value=\"$thafile\" selected=\"selected\">$thafile</option>\n";
} 
else {
$langfileselect .= "<option value=\"$thafile\">$thafile</option>\n";
}
}
}
$langfileselect .= "</select>";


$dayselect = "<select name=\"day\">\n";
$dayselect .= "<option value=\"\">&nbsp;</option>\n";
for($num = 1; $num <= 31; $num++) {
$dayselect .= "<option value=\"$num\">$num</option>\n";
}
$dayselect .= "</select>";

if($sigbbcode == "on") {
$bbcodeis = _TEXTON;
} else {
$bbcodeis = _TEXTOFF;
}

if($sightml == "on") {
$htmlis = _TEXTON;
} else {
$htmlis = _TEXTOFF;
}
?>

<!--#DM###:.
<form method="post" action="modules.php?op=modload&name=XForum&file=member&action=reg">
<!--#FM###:.--> 
<form method="post" action="user.php">
<!--#FR###:.--> 
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr>
<td colspan="2" class="header"><?=_TEXTREGISTER?> - <?=_REQUIRED?></td>
</tr>

<tr>
<td bgcolor="<?=$altbg1?>" width="22%" class="tablerow" ><?=_TEXTUSERNAME?></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="username" size="25" maxlength="25" /></td>
</tr>

<?
if($emailcheck == "on"){
$chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
mt_srand((double)microtime() * 1000000);
for ($get = strlen($chars); $i < 8; ++$i)
$password .= $chars[mt_rand(0, $get)];
} else {
echo "<tr>
<td bgcolor=\"$altbg1\" class=\"tablerow\">"._TEXTPASSWORD."</td>
<td bgcolor=\"$altbg2\"class=\"tablerow\"><input type=\"password\" name=\"password\" size=\"25\" /></td>
</tr>";
}

if($emailcheck == "on"){
echo "<input type=\"hidden\" name=\"password\" value=\"$password\">";
echo "<input type=\"hidden\" name=\"password2\" value=\"$password\">";
} else {
echo"<tr>
<td bgcolor=\"$altbg1\" class=\"tablerow\">"._TEXTRETYPEPW."</td>
<td bgcolor=\"$altbg2\" class=\"tablerow\"><input type=\"password\" name=\"password2\" size=\"25\" /></td>
</tr>";
}
?>

<tr>
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTEMAIL?></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="email" size="25" /></td>
</tr>

<tr>
<td colspan="2" class="header"><?=_TEXTREGISTER?> - <?=_OPTIONAL?></td>
</tr>

<tr>
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTSITE?></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="site" size="25" /></td>
</tr>

<tr>
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTAIM?></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="aim" size="25" /></td>
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTICQ?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="icq" size="25" /></td> 
</tr> 

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTYAHOO?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="yahoo" size="25" /></td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTMSN?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="msn" size="25" /></td> 
</tr> 

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTLOCATION?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="locationnew" size="25" /></td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTBDAY?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><select name="month">
<option value="">&nbsp;</option>
<option value="<?=_TEXTJAN?>"><?=_TEXTJAN?></option>
<option value="<?=_TEXTFEB?>"><?=_TEXTFEB?></option>
<option value="<?=_TEXTMAR?>"><?=_TEXTMAR?></option>
<option value="<?=_TEXTAPR?>"><?=_TEXTAPR?></option>
<option value="<?=_TEXTMAY?>"><?=_TEXTMAY?></option>
<option value="<?=_TEXTJUN?>"><?=_TEXTJUN?></option>
<option value="<?=_TEXTJUL?>"><?=_TEXTJUL?></option>
<option value="<?=_TEXTAUG?>"><?=_TEXTAUG?></option>
<option value="<?=_TEXTSEP?>"><?=_TEXTSEP?></option>
<option value="<?=_TEXTOCT?>"><?=_TEXTOCT?></option>
<option value="<?=_TEXTNOV?>"><?=_TEXTNOV?></option>
<option value="<?=_TEXTDEC?>"><?=_TEXTDEC?></option>
</select>
<?=$dayselect?>
<input type="text" name="year" size="4" />
</td> 
</tr> 

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTBIO?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><textarea rows="5" cols="30" name="bio"></textarea></td> 
</tr> 

<tr>
<td colspan="2" class="header"><?=_TEXTREGISTER?> - <?=_TEXTOPTIONS?></td>
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTTHEME?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><?=$XFthemelist?> </td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTLANGUAGE?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><?=$langfileselect?> </td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTTPP?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="tpp" value="<?=$topicperpage?>" size="4" /></td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTPPP?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="ppp" value="<?=$postperpage?>" size="4" /></td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTTIMEFORMAT?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="radio" value="24" name="timeformatnew"><?=_TEXT24HOUR?> <input type="radio" value="12" name="timeformatnew" checked="checked"><?=_TEXT12HOUR?></td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_DATEFORMAT?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="dateformatnew" size="25" value="<?=$dformatorig?>" /></td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTOPTIONS?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"> 
<input type="checkbox" name="showemail" value="yes" checked="checked" /> <?=_TEXTSHOWEMAIL?><br />
<input type="checkbox" name="newsletter" value="yes" checked="checked" /> <?=_TEXTGETNEWS?><br />
<select name="timeoffset1" size="1">
<option value="-12">-12:00</option>
<option value="-11">-11:00</option>
<option value="-10">-10:00</option>
<option value="-9">-9:00</option>
<option value="-8">-8:00</option>
<option value="-7">-7:00</option>
<option value="-6">-6:00</option>
<option value="-5">-5:00</option>
<option value="-4">-4:00</option>
<option value="-3.5">-3:30</option>
<option value="-3">-3:00</option>
<option value="-2">-2:00</option>
<option value="-1">-1:00</option>
<option value="0" selected>0</option>
<option value="1">+1:00</option>
<option value="2">+2:00</option>
<option value="3">+3:00</option>
<option value="3.5">+3:30</option>
<option value="4">+4:00</option>
<option value="4.5">+4:30</option>
<option value="5">+5:00</option>
<option value="5.5">+5:30</option>
<option value="6">+6:00</option>
<option value="7">+7:00</option>
<option value="8">+8:00</option>
<option value="9">+9:00</option>
<option value="9.5">+9:30</option>
<option value="10">+10:00</option>
<option value="11">+11:00</option>
<option value="12">+12:00</option>
</select> <?=_TEXTOFFSET?><br /></td>
</tr>

<?
if($avastatus == "on") {
?>
<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTAVATAR?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="avatar" size="25" /></td> 
</tr>
<?
}
?>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTSIG?><br /><span class="11px">
<?=_TEXTHTMLIS?> <?=$htmlis?><br />
<?=_TEXTBBCODEIS?> <?=$bbcodeis?></span></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><textarea rows="4" cols="30" name="sig"></textarea></td> 
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="regsubmit" value="<?=_TEXTREGISTER?>" /></center>
</form>

<?
}
}

if($regsubmit) {
$username = trim($username);
$query = mysql_query("SELECT username FROM $table_members WHERE username='$username'") or die(mysql_error()); 

if($member = mysql_fetch_array($query)) { 
echo "<span class=\"12px \">"._ALREADYREG."</span>";
exit; 
}

if($password != $password2) {
echo "<span class=\"12px \">"._PWNOMATCH."</span>";
exit;
}

if($username == "" || $username == "xguest123" || $username == "onlinerecord" || $username == _TEXTGUEST || ereg("'", $username) || ereg('"', $username) || ereg("<", $username) || ereg(",", $username)) { 
echo "<span class=\"12px \">"._BADNAME."</span>";
exit; 
}

if($password == "" || ereg('"', $password)|| ereg("'", $password)) { 
echo "<span class=\"12px \">"._TEXTPWINCORRECT."</span>";
exit; 
}

#DM###:.
/*
$query = mysql_query("SELECT COUNT(uid) FROM $table_members") or die(mysql_error());
$count1 = mysql_resul t($query,0);
*/
#DR###:. Remplcé par --> 
$query = mysql_query("SELECT COUNT(uid) as nbsites FROM $table_members") or die(mysql_error());
$row = mysql_fetch_array($query);
$count1 = $row[nbsites];
#FM###:.


if($count1 != "0") {
#DM###:.
/*
$query = mysql_query("SELECT uid FROM $table_members ORDER BY uid DESC") or die(mysql_error());
$count = mysql_resul t($query,0);
*/
#DR###:. Remplcé par --> 
$query = mysql_query("SELECT count(uid) as nbsites FROM $table_members ORDER BY uid DESC") or die(mysql_error());
$row = mysql_fetch_array($query);
$count = $row[nbsites];
#FM###:.

$status = "Member";
}
else {
$count = $count1;
$status = "Administrator";
}

if($showemail != "yes") {
$showemail = "no";
}

if($newsletter != "yes") {
$newsletter = "no";
}

$bday = "$month $day, $year";

if($month == "" || $day == "" || $year == "") {
$bday = "";
}

$avatar = str_replace("<","&lt;", $avatar);
$avatar = str_replace(">","&gt;", $avatar);
$locationnew = str_replace("<","&lt;", $locationnew);
$locationnew= str_replace(">","&gt;", $locationnew);
$icq = str_replace("<","&lt;", $icq);
$icq = str_replace(">","&gt;", $icq);
$yahoo = str_replace("<","&lt;", $yahoo);
$yahoo = str_replace(">","&gt;", $yahoo);
$aim = str_replace("<","&lt;", $aim);
$aim = str_replace(">","&gt;", $aim);
$email = str_replace("<","&lt;", $email);
$email = str_replace(">","&gt;", $email);
$site = str_replace("<","&lt;", $site);
$site = str_replace(">","&gt;", $site);
$bio = str_replace("<","&lt;", $bio);
$bio = str_replace(">","&gt;", $bio);
$bday = str_replace("<","&lt;", $bday);
$bday = str_replace(">","&gt;", $bday);

mysql_query("INSERT INTO $table_members VALUES ('', '$username', '$password', '" . time() . "', '0', '$email', '$site', '$aim', '$status', '$locationnew', '$bio', '$sig', '$showemail', '$timeoffset1', '$icq', '$avatar', '$yahoo', '', '$XFthememem', '$bday', '$langfile', '$tpp', '$ppp', '$newsletter', '$onlineip', '$timeformatnew', '$msn', '$dateformatnew', '', '')") or die(mysql_error());

if($emailcheck == "on"){
mail("$email", _TEXTYOURPW, _TEXTYOURPWIS." \n\n$username\n$password", _TEXTFROM." $bbname");
echo "<span class=\"12px \">"._EMAILPW."</span>";
}
echo "<span class=\"12px \">"._REGGED."</span>";

?>
<script> 
function redirect()
{ 
window.location.replace("modules.php?op=modload&name=XForum&file=indexnav"); 
} 
setTimeout("redirect();", 1250); 
</script>
<?
} 
}

//***************************************************************************//
// Voir le profil	(action=viewpro)										 //
//***************************************************************************//

if($action == "viewpro") 
{

  if(!$member) { echo _NOMEMBER; }
  else 
  {
	$query = mysql_query("SELECT * FROM $table_members WHERE username='$member'") or die(mysql_error());
	$memberinfo = mysql_fetch_array($query);

	$daysreg = (time() - $memberinfo[regdate]) / (24*60*60); 
	$ppd = $memberinfo[postnum] / $daysreg; 
	$ppd = round($ppd, 2); 

	$memberinfo[regdate] = gmdate("n/j/y",$memberinfo[regdate]);
	$memberinfo[site] = str_replace("http://", "", $memberinfo[site]);
	$memberinfo[site] = "http://$memberinfo[site]";
	if($memberinfo[site] != "http://") { $site = "$memberinfo[site]"; }

	if($memberinfo[email] != "" && $memberinfo[showemail] == "yes") { $email = $memberinfo[email]; }

	if($whosonlinestatus == "on") // si le qui whosonline est activé
	{
	  $query = mysql_query("SELECT * FROM $table_whosonline WHERE username='$member'"); 
	  $onlineinfo = mysql_fetch_array($query); 
	  if ($onlineinfo[username] == $member) { $onlinestatus = _TEXTONLINE;	} 
	  else									{ $onlinestatus = _TEXTOFFLINE; }
	}
	
	$lastvisitdate = gmdate("$dateformat",$memberinfo[lastvisit] + ($timeoffset * 3600)); 
	$lastvisittime = gmdate("$timecode",$memberinfo[lastvisit] + ($timeoffset * 3600)); 
	$lastmembervisittext = "$lastvisitdate "._TEXTAT." $lastvisittime"; 

	#DM###:.
	/*
	$query = mysql_query("SELECT COUNT(pid) FROM $table_posts") or die(mysql_error()); 
	$posts = mysql_resul t($query, 0); 
	*/
	#DR###:. Remplcé par --> suppr de "*" par pid
	$query = mysql_query("SELECT COUNT(pid) as nbsites FROM $table_posts") or die(mysql_error());
	$row = mysql_fetch_array($query);
	$posts = $row[nbsites];
	#FM###:.

	#DM###:.
	/*
	$query = mysql_query("SELECT COUNT(tid) FROM $table_threads") or die(mysql_error()); 
	$threads = mysql_resul t($query, 0); 
	*/
	#DR###:. Remplcé par --> suppr de "*" par tid
	$query = mysql_query("SELECT COUNT(tid) as nbsites FROM $table_threads") or die(mysql_error());
	$row = mysql_fetch_array($query);
	$threads = $row[nbsites];
	#FM###:.

	$posttot = $threads+$posts; 
	if($posttot == 0)	{ $percent = "0"; } 
	else				
	{ 
	  $percent = $memberinfo[postnum]*100/$posttot; 
	  $percent = round($percent, 2); 
	} 

	$memberinfo[bio] = nl2br($memberinfo[bio]);
	$encodeuser = rawurlencode($memberinfo[username]);

	$query = mysql_query("SELECT dateline, tid FROM $table_posts WHERE author='$memberinfo[username]' ORDER BY dateline DESC LIMIT 0, 1") or die(mysql_error());
	$lastrep = mysql_fetch_array($query);

	$query = mysql_query("SELECT dateline, subject, tid FROM $table_threads WHERE author='$memberinfo[username]' ORDER BY dateline DESC LIMIT 0, 1") or die(mysql_error());
	$lasttop = mysql_fetch_array($query);

	if($lastrep[dateline] > $lasttop[dateline]) 
	{
	  $ltoptime = $lastrep[dateline];
	  $query = mysql_query("SELECT subject FROM $table_threads WHERE tid='$lastrep[tid]'") or die(mysql_error());
	  $ltop = mysql_fetch_array($query);
	  $lasttopsub = $ltop[subject];
	  $lttid = $lastrep[tid];
	} 
	else 
	{
	  $ltoptime = $lasttop[dateline];
	  $lasttopsub = $lasttop[subject];
	  $lttid = $lasttop[tid];
	}

	$lasttopdate = gmdate("$dateformat", $ltoptime + ($timeoffset * 3600));
	$lasttoptime = gmdate("$timecode", $ltoptime + ($timeoffset * 3600));
	$lasttopic = "<a href=\""._BASEMOD."viewthread&tid=$lttid\">$lasttopsub</a> "._LASTREPLY1." $lasttopdate "._TEXTAT." $lasttoptime";

	// Gestion des ranks (beta2)
	$showtitle = $memberinfo[status];
	$queryrank = mysql_query("SELECT * FROM $table_ranks") or die(mysql_error());
	while($rank = mysql_fetch_array($queryrank)) 
	{
	  if($memberinfo[postnum] >= $rank[posts]) 
	  {
		$allowavatars = $rank[allowavatars];
		$showtitle = $rank[title];
		$stars = "";
		for($i = 0; $i < $rank[stars]; $i++) { $stars .= "<img src=\"modules/XForum/images/star.gif\">"; }
	
		if($rank[avatarrank] != "") { $avarank = $rank[avatarrank]; }
	  } 
	  else 
	  {
		$showtitle = $showtitle;
		$stars = $stars;
	  }
	}

?>
	<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
	<tr>
	  <td bgcolor="<?=$bordercolor?>">

		<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
		<tr>
		  <td colspan="2" class="header"><?=_TEXTPROFOR?> <?=$member?></td>
		</tr>
		<tr>
		  <td bgcolor="<?=$altbg1?>" width="22%" class="tablerow"><?=_TEXTUSERNAME?></td>
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$memberinfo[username]?>&nbsp;</td>
		</tr>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTREGISTERED?></td>
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$memberinfo[regdate]?> (<?=$ppd?> <?=_TEXTMESPERDAY?>)</td>
		</tr>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTPOSTS?></td>
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$memberinfo[postnum]?> (<?=$percent?>% <?=_TEXTOFTOTPOSTS?>.)</td>
		</tr>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTSTATUS?></td>
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$memberinfo[status]?></td>
		</tr>
		<tr> <!-- Rajouté Beta2 -->
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTSTATUSXF?></td>
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$showtitle?>&nbsp;<?=$stars?></td>
		</tr>
<?
		if($whosonlinestatus == "on") 
		{
?>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_ONSTATUS?></td> 
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$onlinestatus?></td>
		</tr> 
<?		}

		if($memberinfo[lastvisit] != "") 
		{
?> 
		<tr>
		  <td bgcolor="<?=$altbg1?>" valign="top" class="tablerow"><?=_LASTACTIVE?></td> 
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$lastmembervisittext?></td>
		</tr>
<? 		} 

		if($memberinfo[postnum] != "0") 
		{
?> 
		<tr>
		  <td bgcolor="<?=$altbg1?>" valign="top" class="tablerow"><?=_LASTPOSTIN?></td> 
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$lasttopic?></td>
		</tr>
<?		} 

		if($memberinfo[email] != "" && $memberinfo[showemail] == "yes") 
		{ 
?>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTEMAIL?></td> 
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><a href="mailto:<?=$email?>"><?=$email?></a></td>
		</tr>
<?		} 

		if($memberinfo[site] != "http://") 
		{ 
?>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTSITE?></td>
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><a href="<?=$site?>"><?=$site?></a></td>
		</tr>
<?		} 

		if($memberinfo[aim] != "") 
		{ 
?>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTAIM?></td>
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$memberinfo[aim]?></td>
		</tr>
<?		}
		
		if($memberinfo[icq] != "") 
		{ 
?>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTICQ?></td> 
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$memberinfo[icq]?></td>
		</tr>
<?		} 

		if($memberinfo[yahoo] != "") 
		{ 
?>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTYAHOO?></td> 
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$memberinfo[yahoo]?></td>
		</tr>
<?		}

		if($memberinfo[msn] != "") 
		{ 
?>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTMSN?></td> 
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$memberinfo[msn]?></td>
		</tr>
<?		} 
		
		if($memberinfo[location] != "") 
		{ 
?>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTLOCATION?></td> 
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$memberinfo[location]?></td>
		</tr> 
<?		} 
		
		if($memberinfo[bday] != "") 
		{ 
?>
		<tr>
		  <td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTBDAY?></td> 
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$memberinfo[bday]?></td>
		</tr>
<?		} 
		
		if($memberinfo[bio] != "") 
		{ 
?>
		<tr>
		  <td bgcolor="<?=$altbg1?>" valign="top" class="tablerow"><?=_TEXTBIO?></td> 
		  <td bgcolor="<?=$altbg2?>" class="tablerow"><?=$memberinfo[bio]?></td>
		</tr>
<?		} 

?>
		<tr> 
		  <td bgcolor="<?=$altbg1?>" colspan="2" class="tablerow"><?=_SEARCHUSERMSG?></td> 
		</tr>
<?
	}
}

//***************************************************************************//
// Editer le profil	(action=editpro)										 //
//***************************************************************************//

if($action == "editpro") {
#DM###:.
$editlogsubmit="trollix";
$username=$thisuser;
$password=$thispw;
#FM###:.
if(!$editlogsubmit) {
?>

<form method="post" action="modules.php?op=modload&name=XForum&file=member&action=editpro">
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">
<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">
<tr>
<td colspan="2" class="header"><?=_TEXTEDITPRO?></td>
</tr>

<tr>
<td bgcolor="<?=$altbg1?>" width="22%" class="tablerow"><?=_TEXTUSERNAME?></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="username" size="30" maxlength="40" value="<?=$thisuser?>" /> &nbsp;<span class="11px"><!--#DM###:.<a href="modules.php?op=modload&name=XForum&file=member&action=reg">#FM###:.--><a href="user.php"><?=_REGQUES?></a></span></td>
</tr>

<tr>
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTPASSWORD?></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="password" name="password" size="25" value="<?=$thispw?>" /> &nbsp;<span class="11px"><a href="modules.php?op=modload&name=XForum&file=misc&action=lostpw"><?=_FORGOTPW?></a></span></td>
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="editlogsubmit" value="<?=_TEXTEDITPRO?>" /></center>
</form>

<?
}

if($editlogsubmit && $editlogsubmit != 1) {
$query = mysql_query("SELECT * FROM $table_members WHERE username='$username'") or die(mysql_error());
$member = mysql_fetch_array($query);

if(!$member[username]) {
echo _BADNAME;
exit;
}

if($password != $member[password]) {
echo _TEXTPWINCORRECT;
exit;
}

if($member[showemail] == "yes") {
$checked = "checked=\"checked\"";
}

if($member[newsletter] == "yes") {
$newschecked = "checked=\"checked\"";
}

$currdate = gmdate("$timecode");
eval(_EVALOFFSET);

if($member[timeoffset] == "-12") {
$sn12 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-11") {
$sn11 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-10") {
$sn10 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-9") {
$sn9 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-8") {
$sn8 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-7") {
$sn7 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-6") {
$sn6 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-5") {
$sn5 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-4") {
$sn8 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-3.5") {
$sn35 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-3") {
$sn3 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-2") {
$sn2 = "selected=\"selected\"";
} elseif($member[timeoffset] == "-1") {
$sn1 = "selected=\"selected\"";
} elseif($member[timeoffset] == "0") {
$s0 = "selected=\"selected\"";
} elseif($member[timeoffset] == "1") {
$sp1 = "selected=\"selected\"";
} elseif($member[timeoffset] == "2") {
$sp2 = "selected=\"selected\"";
} elseif($member[timeoffset] == "3") {
$sp3 = "selected=\"selected\"";
} elseif($member[timeoffset] == "3.5") {
$sp35 = "selected=\"selected\"";
} elseif($member[timeoffset] == "4") {
$sp4 = "selected=\"selected\"";
} elseif($member[timeoffset] == "4.5") {
$sp45 = "selected=\"selected\"";
} elseif($member[timeoffset] == "5") {
$sp5 = "selected=\"selected\"";
} elseif($member[timeoffset] == "5.5") {
$sp55 = "selected=\"selected\"";
} elseif($member[timeoffset] == "6") {
$sp6 = "selected=\"selected\"";
} elseif($member[timeoffset] == "7") {
$sp7 = "selected=\"selected\"";
} elseif($member[timeoffset] == "8") {
$sp8 = "selected=\"selected\"";
} elseif($member[timeoffset] == "9") {
$sp9 = "selected=\"selected\"";
} elseif($member[timeoffset] == "9.5") {
$sp95 = "selected=\"selected\"";
} elseif($member[timeoffset] == "10") {
$sp10 = "selected=\"selected\"";
} elseif($member[timeoffset] == "11") {
$sp11 = "selected=\"selected\"";
} elseif($member[timeoffset] == "12") {
$sp12 = "selected=\"selected\"";
}

$XFthemelist = "<select name=\"XFthememem\">\n";
$query = mysql_query("SELECT name FROM $table_themes") or die(mysql_error());
while($XFtheme = mysql_fetch_array($query)) {
if($XFtheme[name] == $member[theme]) {
$XFthemelist .= "<option value=\"$XFtheme[name]\" selected=\"selected\">$XFtheme[name]</option>\n";
}
else {
$XFthemelist .= "<option value=\"$XFtheme[name]\">$XFtheme[name]</option>\n";
}
}
$XFthemelist  .= "</select>";


$langfileselect = "<select name=\"langfilenew\">\n";
$dir = opendir("modules/XForum/lang");
while ($thafile = readdir($dir)) {
if(is_file("modules/XForum/lang/$thafile")) {
$thafile = str_replace(".lang.php", "", $thafile);
if($thafile == "$member[langfile]") {
$langfileselect .= "<option value=\"$thafile\" selected=\"selected\">$thafile</option>\n";
} 
else {
$langfileselect .= "<option value=\"$thafile\">$thafile</option>\n";
}
}
}

$langfileselect .= "</select>";


$member[bday] = str_replace(",", "", $member[bday]);
$bday = explode(" ", $member[bday]);

if		($bday[0] == "")		{ $sel0 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTJAN)	{ $sel1 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTFEB)	{ $sel2 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTMAR)	{ $sel3 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTAPR)	{ $sel4 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTMAY)	{ $sel5 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTJUN)	{ $sel6 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTJUL)	{ $sel7 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTAUG)	{ $sel8 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTSEP)	{ $sel9 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTOCT)	{ $sel10 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTNOV)	{ $sel11 = "selected=\"selected\""; } 
elseif	($bday[0] == _TEXTDEC)	{ $sel12 = "selected=\"selected\""; }

$dayselect = "<select name=\"day\">\n";
$dayselect .= "<option value=\"\">&nbsp;</option>\n";
for($num = 1; $num <= 31; $num++) {
if($bday[1] == $num) {
$dayselect .= "<option value=\"$num\" selected=\"selected\">$num</option>\n";
}
else {
$dayselect .= "<option value=\"$num\">$num</option>\n";
}
}
$dayselect .= "</select>";

if($member[timeformat] == "24") {
$check24 = "checked=\"checked\"";
}
else {
$check12 = "checked=\"checked\"";
}

if($sigbbcode == "on") {
$bbcodeis = _TEXTON;
} else {
$bbcodeis = _TEXTOFF;
}

if($sightml == "on") {
$htmlis = _TEXTON;
} else {
$htmlis = _TEXTOFF;
}
?>

<form method="post" action="modules.php?op=modload&name=XForum&file=member&action=editpro&editlogsubmit=1" name="reg">
<table cellspacing="0" cellpadding="0" border="0" width="<?=$tablewidth?>" align="center">

<tr><td bgcolor="<?=$bordercolor?>">

<table border="0" cellspacing="<?=$borderwidth?>" cellpadding="<?=$tablespace?>" width="100%">

<tr>
<td colspan="2" class="header"><?=_TEXTREGISTER?> - <?=_TEXTOPTIONS?></td>
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTTHEME?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><?=$XFthemelist?> </td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTLANGUAGE?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><?=$langfileselect?> </td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTTPP?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="tppnew" size="4" value="<?=$member[tpp]?>" /> </td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTPPP?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="pppnew" size="4" value="<?=$member[ppp]?>" /> </td> 
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTTIMEFORMAT?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="radio" value="24" name="timeformatnew" <?=$check24?>><?=_TEXT24HOUR?> 
<input type="radio" value="12" name="timeformatnew" <?=$check12?>><?=_TEXT12HOUR?></td> 
</tr>

<tr>
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_DATEFORMAT?></td>
<td bgcolor="<?=$altbg2?>" class="tablerow"><input type="text" name="dateformatnew" size="30" value="<?=$member[dateformat]?>" /></td>
</tr>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTOPTIONS?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"> 
<input type="checkbox" name="showemail" value="yes" <?=$checked?> /> <?=_TEXTSHOWEMAIL?><br />
<input type="checkbox" name="newsletter" value="yes" <?=$newschecked?> /> <?=_TEXTGETNEWS?><br /> 
<select name="timeoffset1" size="1">
<option value="-12" <?=$sn12?>>-12:00</option>
<option value="-11" <?=$sn11?>>-11:00</option>
<option value="-10" <?=$sn10?>>-10:00</option>
<option value="-9" <?=$sn9?>>-9:00</option>
<option value="-8" <?=$sn8?>>-8:00</option>
<option value="-7" <?=$sn7?>>-7:00</option>
<option value="-6" <?=$sn6?>>-6:00</option>
<option value="-5" <?=$sn5?>>-5:00</option>
<option value="-4" <?=$sn4?>>-4:00</option>
<option value="-3.5" <?=$sn35?>>-3:30</option>
<option value="-3" <?=$sn3?>>-3:00</option>
<option value="-2" <?=$sn2?>>-2:00</option>
<option value="-1" <?=$sn1?>>-1:00</option>
<option value="0" <?=$s0?>>0</option>
<option value="1" <?=$sp1?>>+1:00</option>
<option value="2" <?=$sp2?>>+2:00</option>
<option value="3" <?=$sp3?>>+3:00</option>
<option value="3.5" <?=$sp35?>>+3:30</option>
<option value="4" <?=$sp4?>>+4:00</option>
<option value="4.5" <?=$sp45?>>+4:30</option>
<option value="5" <?=$sp5?>>+5:00</option>
<option value="5.5" <?=$sp55?>>+5:30</option>
<option value="6" <?=$sp6?>>+6:00</option>
<option value="7" <?=$sp7?>>+7:00</option>
<option value="8" <?=$sp8?>>+8:00</option>
<option value="9" <?=$sp9?>>+9:00</option>
<option value="9.5" <?=$sp95?>>+9:30</option>
<option value="10" <?=$sp10?>>+10:00</option>
<option value="11" <?=$sp11?>>+11:00</option>
<option value="12" <?=$sp12?>>+12:00</option>
</select> <?=_TEXTOFFSET?> 
</td></tr>

<?
if($avastatus == "on") {
?>

<tr> 
<td bgcolor="<?=$altbg1?>" class="tablerow"><?=_TEXTAVATAR?></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow">
	<!--#DM###:. Début ajout gestion des avatars -->
<?
	echo "<SCRIPT type=\"text/javascript\">\n";
    echo "<!--\n";
    echo "function showavatar() {\n";
    echo "if (!document.images)\n";
    echo "return\n";
    echo "document.images.avatar.src=\n";
    echo "'modules/XForum/images/avatar/' + document.reg.avatar.options[document.reg.avatar.selectedIndex].value\n";
    echo "}\n";
    echo "//-->\n";
    echo "</SCRIPT>\n\n";
	# ici mettre reg.avatar (nom form.nom select)
	echo "[ <a href=\"user.php?op=avatarlist\">"._TEXTALLOWAVATARS."</a> ]&nbsp;&nbsp;\n"
            ."<select name=\"avatar\" onChange=\"showavatar()\">\n";
        $direktori = "modules/XForum/images/avatar";
        $handle=opendir($direktori);
        while ($file = readdir($handle)) {
            $filelist[] = $file;
        }
        asort($filelist);
        while (list ($key, $file) = each ($filelist)) {
            ereg(".gif|.jpg",$file);
            if ($file == "." || $file == "..") {
                $a=1;
            } else {
				if ($file==$member[avatar]) { $selected="selected";} else { $selected="";}
                echo "<option value=\"$file\" $selected>$file</option>\n";
            }
        }
        echo "</select>&nbsp;&nbsp;<img src=\"modules/XForum/images/avatar/".$member[avatar]."\" name=\"avatar\" width=\"32\" height=\"32\" alt=\"\">";
?>
<!-- Supprimé 20011001 <input type="text" name="avatar" size="30" value="<?=$member[avatar]?>" /></td> -->
</tr>

<?
}
?>

<tr> 
<td bgcolor="<?=$altbg1?>" width="22%" class="tablerow"><?=_TEXTSIG?><br /><span class="11px">
<?=_TEXTHTMLIS?> <?=$htmlis?><br />
<?=_TEXTBBCODEIS?> <?=$bbcodeis?></span></td> 
<td bgcolor="<?=$altbg2?>" class="tablerow"><textarea rows="4" cols="30" name="sig"><?=$member[sig]?></textarea></td> 
</tr>

</table>
</td></tr></table>
<center><input type="submit" name="editsubmit" value="<?=_TEXTEDITPRO?>" /></center>
<input type="hidden" name="username" value="<?=$username?>">
<input type="hidden" name="password" value="<?=$password?>">
</form>

<?
}

if($editsubmit) {
$query = mysql_query("SELECT * FROM $table_members WHERE username='$username'") or die(mysql_error());
$member = mysql_fetch_array($query);

if(!$member[username]) {
echo "<span class=\"12px \">"._BADNAME."</span>";
exit;
}

if($password != $member[password]) {
echo "<span class=\"12px \">"._TEXTPWINCORRECT."</span>";
exit;
}

if($showemail != "yes") {
$showemail = "no";
}

if($newsletter != "yes") {
$newsletter = "no";
}

$bday = "$month $day, $year";

if($month == "" || $day == "" || $year == "") {
$bday = "";
}

$avatar = str_replace("<","&lt;", $avatar);
$avatar = str_replace(">","&gt;", $avatar);
$memlocation = str_replace("<","&lt;", $memlocation);
$memlocation = str_replace(">","&gt;", $memlocation);
$icq = str_replace("<","&lt;", $icq);
$icq = str_replace(">","&gt;", $icq);
$yahoo = str_replace("<","&lt;", $yahoo);
$yahoo = str_replace(">","&gt;", $yahoo);
$aim = str_replace("<","&lt;", $aim);
$aim = str_replace(">","&gt;", $aim);
$email = str_replace("<","&lt;", $email);
$email = str_replace(">","&gt;", $email);
$site = str_replace("<","&lt;", $site);
$site = str_replace(">","&gt;", $site);
$bio = str_replace("<","&lt;", $bio);
$bio = str_replace(">","&gt;", $bio);
$bday = str_replace("<","&lt;", $bday);
$bday = str_replace(">","&gt;", $bday);

mysql_query("UPDATE $table_members SET email='$email', site='$site', aim='$aim', location='$memlocation', bio='$bio', sig='$sig', showemail='$showemail', timeoffset='$timeoffset1', icq='$icq', avatar='$avatar', yahoo='$yahoo', theme='$XFthememem', bday='$bday', langfile='$langfilenew', tpp='$tppnew', ppp='$pppnew', newsletter='$newsletter', timeformat='$timeformatnew', msn='$msn', dateformat='$dateformatnew' WHERE username='$username'") or die(mysql_error()); 

#DM###:. Particularité comme l'admin n'a pas d'avatar cette requête convient aussi
mysql_query("UPDATE ".$prefix."_users set user_sig='$sig', user_avatar='$avatar' where uname='$username'");
#FM###:.

if($newpassword != "") {
if(ereg('"', $newpassword) || ereg("'", $newpassword)) {
echo _TEXTPWINCORRECT;
exit;
}
mysql_query("UPDATE $table_members SET password='$newpassword' WHERE username='$username'") or die(mysql_error());
}

echo "<span class=\"12px \">"._EDITEDPRO."</span>";
?>
<script> 
function redirect()
{ 
window.location.replace("modules.php?op=modload&name=XForum&file=indexnav"); 
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
