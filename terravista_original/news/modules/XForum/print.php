<?php

include_once 'mainfile.php';
global $ModName;

if ( (!isset($fid)) || (!isset($tid)) ) { exit(); }
$ModName = basename( dirname( __FILE__ ) );
#require "modules/XForum/header.php";

if (!isset($mainfile)) { include("mainfile.php"); }
$index=0;

require "modules/XForum/settings.php";
require "modules/XForum/functions.php";

$SYSADMIN = is_admin($admin);	# User is Admin
$USER = is_user($user);

# postnuke
if(!is_array($admin)) 
  {
    $admin = base64_decode($admin);
    $admin = explode(":", $admin);
    $aid = "$admin[0]";
    $pwd = "$admin[1]";
  } 
else 
  {
    $aid = "$admin[0]";
    $pwd = "$admin[1]";
  }

$M_ucook = cookiedecode($user);
$M_username = $M_ucook[1];
$M_userpass = $M_ucook[2];

if ($SYSADMIN) 
  {
	#echo "admin $thisuser connecté";
	$thisuser=$aid;
    $thispw=$pwd;	
  }
elseif ($USER) 
  {
	#echo "Nuke user: $thisuser connecté";
	$thisuser=$M_username;
    $thispw=$M_userpass;
  }
else
  {
	$thisuser="Anonymous";
    $thispw="Anonymous";
	#echo "ni admin, ni user connecté";
  } 


if($thisuser && $thisuser != '') 
{
  $query = mysql_query("SELECT langfile, timeoffset, status, theme, tpp, ppp, timeformat, dateformat, password FROM $table_members WHERE username='$thisuser'") or die(mysql_error());
  $this = mysql_fetch_array($query);
  if($this[langfile] != "")		{ $langfile = $this[langfile]; }
  $timeoffset = $this[timeoffset];
  $status = $this[status];
  $XFthemeuser = $this[theme];
  $tpp = $this[tpp];
  $ppp = $this[ppp];
  $memtime = $this[timeformat];
  $memdate = $this[dateformat];

  if($this[password] == $thispw)	{ $thisuser = $thisuser; } 
  else								{ $thisuser = ""; }
}

require "modules/XForum/lang/$langfile.lang.php";

if($memtime == "") 
{
  if($timeformat == "24")	{ $timecode = "H:i"; } 
  else						{ $timecode = "h:i A"; }
} 
else 
{
  if($memtime == "24")		{ $timecode = "H:i"; } 
  else						{ $timecode = "h:i A"; }
}

$dformatorigconf = $dateformat;
if($memdate == "")			{ $dateformat = $dateformat;	} 
else						{ $dateformat = $memdate;		}
$dformatorig = $dateformat;

$dateformat = preg_replace("/mm/i", "n", $dateformat);
$dateformat = preg_replace("/dd/i", "j", $dateformat);
$dateformat = preg_replace("/yyyy/i", "Y", $dateformat);
$dateformat = preg_replace("/yy/i", "y", $dateformat);





	// Post-Nuke: following is used to grab the stylesheet.
	if(is_user($user)) {
        $user2 = base64_decode($user);
        $cookie = explode(":", $user2);
        if($cookie[9]=="") $cookie[9]=$Default_Theme;
        if(isset($theme)) $cookie[9]=$theme;
        if(!$file=@opendir("themes/$cookie[9]")) {
        $ThemeSel = $Default_Theme;
        } else {
        $ThemeSel = $cookie[9];
        }
    	} else {
        $ThemeSel = $Default_Theme;
    	}
	// Post-Nuke: end

mysql_query("UPDATE $table_threads SET views=views+1 WHERE tid='$tid'") or die(mysql_error()); 
$query = mysql_query("SELECT * FROM $table_threads WHERE fid='$fid' AND tid='$tid'") or die(mysql_error());
$thread = mysql_fetch_array($query);

$date = gmdate("$dateformat",$thread[dateline] + ($timeoffset * 3600));
$time = gmdate("$timecode",$thread[dateline] + ($timeoffset * 3600));
$poston = "$date "._TEXTAT." $time";
$thread[message] = stripslashes($thread[message]);

$bbcodeoff = $thread[bbcodeoff]; 
$smileyoff = $thread[smileyoff]; 
$thread[message] = postify($thread[message], $smileyoff, $bbcodeoff, $fid, $bordercolor, "", "", $table_words, $table_forums, $table_smilies); 
$thread[subject] = stripslashes($thread[subject]);


echo "<html>"
    ."<head><title>$sitename</title></head>"
    ."<body bgcolor=\"#ffffff\" text=\"#000000\">"
		."<link rel=\"stylesheet\" type=\"text/css\" href=\"themes/$ThemeSel/style/style.css\">"
		."<center>"
		."<table border=\"0\"><tr><td>"
    ."<table border=\"0\" width=\"640\" cellpadding=\"0\" cellspacing=\"1\" bgcolor=\"$bgcolor2\">"
		."<tr><td>"
    ."<table border=\"0\" width=\"640\" cellpadding=\"20\" cellspacing=\"1\" bgcolor=\"FFFFFF\">"
		."<tr><td align=\"left\">"
    ."<img src=\"images/$site_logo\" border=\"0\" alt=\"\">"
		."<br><br><br><font size=\"2\"><b>"
   ._THREADNAME."</b></font> $thread[subject]"
	// Post-Nuke fix for entire article being centered
		."</td></tr>"
		."<tr><td>";
	// End Fix

?>

<hr>
<font size="2"><b><?=$thread[author]?></b></font> - <font size="1"><?=$poston?></font>
<p><?=$thread[message]?></p>

<?
$querypost = mysql_query("SELECT * FROM $table_posts WHERE fid='$fid' AND tid='$tid' ORDER BY dateline") or die(mysql_error());
while($post = mysql_fetch_array($querypost)) {

$date = gmdate("$dateformat",$post[dateline] + ($timeoffset * 3600));
$time = gmdate("$timecode",$post[dateline] + ($timeoffset * 3600));
$poston = "$date "._TEXTAT." $time";
$post[message] = stripslashes($post[message]);

$bbcodeoff = $post[bbcodeoff]; 
$smileyoff = $post[smileyoff]; 
$post[message] = postify($post[message], $smileyoff, $bbcodeoff, $fid, $bordercolor, "", "", $table_words, $table_forums, $table_smilies);
?>

<hr>
<font size="2"><b><?=$post[author]?></b></font> - <font size="1"><?=$poston?></font>
<p><?=$post[message]?></p>
<?
}
?>
<hr>
<?

echo "</font>"
    ."</td></tr>"

		# Post-Nuke: changed to display footer message inside white table

		."<tr><td align=\"center\">"
    ."<font class=\"pn-normal\">"
    .""._COMEFROM." $sitename<br>"
    ."<a class=\"pn-normal\" href=\"$nukeurl\">"
		."$nukeurl"
		."</a>"
		."<br><br>"
    .""._URLOFTHISSITE.""
		."<br>"
    ."<a class=\"pn-normal\" href=\"".$nukeurl."/modules.php?op=modload&name=".$ModName."&file=print&fid=$fid&tid=$tid\">".$nukeurl."/modules.php?op=modload&name=".$ModName."&file=print&fid=$fid&tid=$tid</a>"
    ."</font>"
		."</td></tr>"
		."</table></td></tr></table>"

		# end of fix

    ."</td>"
		."</tr>"
		."</table>"
    ."</center>"
		."</body>"
    ."</html>";


?>


