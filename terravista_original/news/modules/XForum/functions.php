<?
# modif le 20010925 19h00 
# modif le 20011001 14h30 idee delhaye
# modif le 20011001 16h15 suppr e reg(i) puis 20011122
# modif le 20011106 23h30 SELECT find,replace ie * (l.119)
# modif le 20011106 23h30 SELECT fid ie * (l.228)

function template ($file) {
$bordercolor = $GLOBALS["bordercolor"];
return addslashes(implode("",file("modules/XForum/templates/$file")));
}

function postify($message, $smileyoff, $bbcodeoff, $fid, $bordercolor, $sigbbcode, $sightml, $table_words, $table_forums, $table_smilies) 
{
  if($fid != "") 
  {
	$query = mysql_query("SELECT * FROM $table_forums WHERE fid=$fid") or die(mysql_error());
	$forums = mysql_fetch_array($query);
  }
  else 
  {
	$forums[allowsmilies] = "yes";
	$forums[allowhtml] = "yes";
	$forums[allowbbcode] = "yes";
	$forums[allowimgcode] = "yes";
  }

if($sightml == "") {
if($forums[allowhtml] == "yes") {
$sightml = "on";
} else {
$sightml = "off";
}
}

if($sigbbcode == "") {
if($forums[allowbbcode] == "yes") {
$sigbbcode = "on";
} else {
$sigbbcode = "off";
}
}

if($sigimgcode == "") { 
if($forums[allowimgcode] == "yes") { 
$sigimgcode = "on"; 
} else { 
$sigimgcode = "off"; 
} 
}

if($forums[allowhtml] != "yes" &&  $sightml != "on") {
$message = str_replace("<","&lt;",$message);
$message = str_replace(">","&gt;",$message);
}

$message = nl2br($message);

if($smileyoff != "yes" && $forums[allowsmilies] == "yes") {
$querysmilie = mysql_query("SELECT code, url FROM $table_smilies WHERE type='smiley'") or die(mysql_error());
while($smilie = mysql_fetch_array($querysmilie)) {
$message = str_replace("$smilie[code]", "<img src=\"modules/XForum/images/$smilie[url]\" border=0>",$message);
}
}

if($bbcodeoff != "yes" && $forums[allowbbcode] == "yes") {
if($sigbbcode == "on") {
#DM###:. $message = e regi_replace("\\[color=([^\\[]*)\\]([^\\[]*)\\[/color\\]","<font color=\"\\1\">\\2</font>",$message);
$message = preg_replace("/\[color=([^\[]*)\]([^\[]*)\[\/color\]/i", "<font color=\"\\1\">\\2</font>",$message);
#DM###:. $message = e regi_replace("\\[size=([^\\[]*)\\]([^\\[]*)\\[/size\\]","<font size=\"\\1\">\\2</font>",$message);
$message = preg_replace("/\[size=([^\[]*)\]([^\[]*)\[\/size\]/i","<font size=\"\\1\">\\2</font>",$message);
#DM###:. $message = e regi_replace("\\[font=([^\\[]*)\\]([^\\[]*)\\[/font\\]","<font face=\"\\1\">\\2</font>",$message);
$message = preg_replace("/\[font=([^\[]*)\]([^\[]*)\[\/font\]/i","<font face=\"\\1\">\\2</font>",$message);
#DM###:. $message = e regi_replace("\\[align=([^\\[]*)\\]([^\\[]*)\\[/align\\]","<p align=\"\\1\">\\2</p>",$message);
$message = preg_replace("/\[align=([^\[]*)\]([^\[]*)\[\/align\]/i","<p align=\"\\1\">\\2</p>",$message);
$message = str_replace("[b]", "<b>", $message); 
$message = str_replace("[/b]", "</b>", $message); 
$message = str_replace("[i]", "<i>", $message); 
$message = str_replace("[/i]", "</i>", $message); 
$message = str_replace("[u]", "<u>", $message); 
$message = str_replace("[/u]", "</u>", $message); 
#DM###:. $message = e regi_replace("\\[email\\]([^\\[]*)\\[/email\\]", "<a href=\"mailto:\\1\">\\1</a>",$message);
$message = preg_replace("/\[email\]([^\[]*)\[\/email\]/i", "<a href=\"mailto:\\1\">\\1</a>",$message);
#DM###:. $message = e regi_replace("\\[email=([^\\[]*)\\]([^\\[]*)\\[/email\\]", "<a href=\"mailto:\\1\">\\2</a>",$message);
$message = preg_replace("/\[email=([^\[]*)\]([^\[]*)\[\/email\]/i", "<a href=\"mailto:\\1\">\\2</a>",$message);
$message = str_replace("[quote]", "<blockquote><span class=\"12px\">quote:</span><hr>", $message); 
$message = str_replace("[/quote]", "<hr></blockquote>", $message); 
$message=str_replace("[code]","<blockquote><pre><smallfont>code:</smallfont><hr>",$message);
$message=str_replace("[/code]","<hr></pre><normalfont></blockquote>",$message);
#DM###:. $message = e regi_replace("\\[url\\]www.([^\\[]*)\\[/url\\]", "<a href=\"http://www.\\1\" target=_blank>\\1</a>",$message); 
$message = preg_replace("/\[url\]www.([^\[]*)\[\/url\]/i", "<a href=\"http://www.\\1\" target=_blank>\\1</a>",$message); 
#DM###:. $message = e regi_replace("\\[url\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=_blank>\\1</a>",$message); 
$message = preg_replace("/\[url\]([^\[]*)\[\/url\]/i","<a href=\"\\1\" target=_blank>\\1</a>",$message); 
#DM###:. $message = e regi_replace("\\[url=([^\\[]*)\\]([^\\[]*)\\[/url\\]","<a href=\"\\1\" target=_blank>\\2</a>",$message);
$message = preg_replace("/\[url=([^\[]*)\]([^\[]*)\[\/url\]/i","<a href=\"\\1\" target=_blank>\\2</a>",$message);
#DM###:. $message = e regi_replace("(^|[>[:space:]\n])([[:alnum:]]+)://([^[:space:]]*)([[:alnum:]#?/&=])([<[:space:]\n]|$)","\\1<a href=\"\\2://\\3\\4\" target=\"_blank\">\\2://\\3\\4</a>\\5", $message);
$message = preg_replace("/(^|[>[:space:]\n])([[:alnum:]]+):\/\/([^[:space:]]*)([[:alnum:]#?\/&=])([<[:space:]\n]|$)/i","\\1<a href=\"\\2://\\3\\4\" target=\"_blank\">\\2://\\3\\4</a>\\5", $message);
}
}

if($forums[allowimgcode] == "yes") { 
if($sigimgcode == "on") { 
#DM###:.
//$message = e regi_replace("\\[img\\]([^\\[]*)\\[/img\\]","<img src=\"modules/XForum/\\1\" border=0>",$message); 
# suppr de regi --> $message = e regi_replace("\\[img\\]([^\\[]*)\\[/img\\]","<img src=\"\\1\" border=0>",$message); 
$message = preg_replace("/\[img\]([^\[]*)\[\/img\]/i","<img src=\"\\1\" border=0>",$message); 
#FM###:.

#DM###:. $message = e regi_replace("\\[img=([^\\[]*)x([^\\[]*)\\]([^\\[]*)\\[/img\\]","<img width=\"\\1\" height=\"\\2\" src=\"\\3\" border=0>",$message); 
$message = preg_replace("/\[img=([^\[]*)x([^\[]*)\]([^\[]*)\[\/img\]/i","<img width=\"\\1\" height=\"\\2\" src=\"\\3\" border=0>",$message); 
} 
}

$message = CensorMessage($message, $table_words);
return $message;
}


function CensorMessage($message, $table_words) 
{
  $querycensor = mysql_query("SELECT find,replace1 FROM $table_words") or die(mysql_error());
  while($censor = mysql_fetch_array($querycensor)) 
  {
	$pre = "([^[:alpha:]]|^)";
	$suf = "([^[:alpha:]]|$)";
	#DM###:. $message = e regi_replace($pre.$censor[find].$suf,"\\1$censor[replace1]\\2",$message);
	$message = preg_replace("/".$pre.$censor[find].$suf."/i","\\1$censor[replace1]\\2",$message);
  }
return $message;
}

function modcheck($status, $username, $fid, $table_forums) 
{
  if($status == "Moderator") 
  { 
	$query = mysql_query("SELECT fid FROM $table_forums WHERE moderator LIKE '%$username%'") or die(mysql_error());
	while($mod = mysql_fetch_array($query)) 
	{
	  if($mod[fid] == $fid) { $modgood = "yes"; } 
	}

	if($modgood == "yes") { $status1 = "Moderator"; }
  }
  return $status1;
}


function privfcheck($hideprivate, $status, $FORUM_private, $thisuser, $FORUM_userlist) 
{
  if($hideprivate == "on") 
  {
	if($FORUM_private == "staff" && ($status == "Administrator" || $status == "Super Moderator" || $status == "Moderator")) 
	{ 
	  $authorization = "true";
	} 
	elseif($FORUM_private != "staff" && $FORUM_userlist == "") { $authorization = "true"; } 
	elseif($FORUM_userlist != "") {
#DM###:.
//M if(e regi($thisuser."(,|$)", $FORUM_userlist) && $thisuser != "") {
if(preg_match("#".$thisuser."(,|$)"."#i", $FORUM_userlist) && $thisuser != "") {
#FM###:.
$authorization = "true";
} else {
$authorization = "no";
}
} else {
$authorization = "no";
}

} else { 
$authorization = "true"; 
}

return $authorization;
}


function Ligneforum($FORUM_lastpost, $timeoffset, $FORUM_moderator, $lastvisit2, $hideprivate, $status, $FORUM_private, $FORUM_posts, $FORUM_threads, $altbg1, $altbg2 , $FORUM_name, $FORUM_fid, $FORUM_description, $timecode, $dateformat, $thisuser, $FORUM_userlist,$tid) 
{

  //////////require "modules/XForum/lang/$langfile.lang.php";
  #$postscol = $GLOBALS["postscol"];

  if($FORUM_lastpost != "") 
  {
	$lastpost = explode("|", $FORUM_lastpost);
	$dalast = $lastpost[0];

	if($lastpost[1] == _TEXTGUEST) { $lastpost[1] = "$lastpost[1]"; } 
	else { $lastpost[1] = "<a href=\""._BASEMOD."member&action=viewpro&member=".rawurlencode($lastpost[1])."\">$lastpost[1]</a>"; }

	$lastpostdate = gmdate("$dateformat",$lastpost[0] + ($timeoffset * 3600));
	$lastposttime = gmdate("$timecode",$lastpost[0] + ($timeoffset * 3600));
	$lastpost = "$lastpostdate "._TEXTAT." $lastposttime<br>"._TEXTBY." $lastpost[1]";
  }
  else 
  {
	$lastpost = _TEXTNEVER;
  }


  $lastvisit2 -= 540;
  if($lastvisit2 < $dalast) { $folder = "<img src=\"modules/XForum/images/red_folder.gif\">"; } 
  else { $folder = "<img src=\"modules/XForum/images/folder.gif\">"; } 

  if($dalast == "") { $folder = "<img src=\"modules/XForum/images/folder.gif\">"; }
  $lastvisit2 += 540;

  $authorization = privfcheck($hideprivate, $status, $FORUM_private, $thisuser, $FORUM_userlist);
  if($authorization == "true") 
  {
	if($FORUM_moderator != "") 
	{
	  $modz = explode(", ", $FORUM_moderator);
	  $FORUM_moderator = "";
	  for($num = 0; $num < count($modz); $num++) 
	  {
		$thismod = "<a href=\""._BASEMOD."member&action=viewpro&member=$modz[$num]\">$modz[$num]</a>";
		if($num == count($modz) - 1) { $FORUM_moderator .= $thismod; } 
		else { $FORUM_moderator .= "$thismod, "; }
	  }
	  $FORUM_moderator = "("._TEXTMODBY." $FORUM_moderator)";
	} 
	else	{ $FORUM_moderator = ""; }

  $str=""
	  ."<tr>"
	  ."<td bgcolor=\"".$altbg1."\" align=\"center\" class=\"tablerow\">".$folder."</td>"
	  ."<td bgcolor=\"".$altbg2."\" class=\"tablerow\"><font class=\"12px\"><a href=\""._BASEMOD."forumdisplay&fid=".$FORUM_fid."\">".$FORUM_name."</a></font>&nbsp; <font class=\"11px\">".$FORUM_moderator."</font><br><font class=\"11px\">".$FORUM_description."</font></td>"
	  ."<td bgcolor=\"".$altbg1."\" align=\"center\" class=\"tablerow\"><font class=\"12px\">".$FORUM_threads."</font></td>"
	  ."<td bgcolor=\"".$altbg2."\" align=\"center\" class=\"tablerow\"><font class=\"12px\">".$FORUM_posts."</font></td>"
	  ."<td bgcolor=\"".$altbg1."\" class=\"tablerow\">"
	  ."<table width=\"100%\"><tr><td align=\"right\"><font size=\"1\" face=\"verdana\">".$lastpost."</font></td>"
	  ."<td valign=\"middle\" align=\"right\"><a href=\""._BASEMOD."viewthread&tid=".$tid."\"><img src=\"modules/XForum/images/lastpost.gif\" border=\"0\"></a></td></tr></table></td>"
	  ."</tr>";
  } 

$dalast = "";
$fmods = "";
return $str;
}
?>