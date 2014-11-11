<?php

########################################################################
# PHP-NUKE Add-On 5.0 : Guestbook AddOn
# =========================================
#
#	Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#	    Original Author Patrick (marsishere)
#
# http://www.nukeaddon.com
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.       
######################################################################

$module_name = "GuestBook";
$nb = 10;  # Number of signature viewed

# Please do not edit anything after this line
###############################################

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can\"t access this file directly...");
}
if (!isset($config)) { include("config.php"); }

if (isset($newlang)) {
	include("modules/$module_name/language/lang-$newlang.php");
	$language = $newlang;
} elseif (isset($lang)) {
    include("modules/$module_name/language/lang-$lang.php");
    $language = $lang;
} else {
    include("modules/$module_name/language/lang-$language.php");
}

function displayDate($string) {
	$date=array(""._MONTHJAN."",""._MONTHFEB."",""._MONTHMAR."",""._MONTHAPR."",""._MONTHMAY."",""._MONTHJUN."",""._MONTHJUL."",""._MONTHAUG."",""._MONTHSEP."",""._MONTHOCT."",""._MONTHNOV."",""._MONTHDEC."");
	$day = substr($string, 8, 2);
	$month_one = substr($string, 5, 1);
	$month_two = substr($string, 6, 1);
	if (!$month_one) {
		$month="$month_two";
	} else { $month="$month_one$month_two"; }
	$year = substr($string, 0, 4);
	$hours = substr($string, 11, 2); 
	$minutes = substr($string, 14, 2);
	$month = $month-1;
	$month = $date[$month];
	return "$month $day, $year - $hours:$minutes"; // March 12, 2001 - 19:16
}

$index=0;
include("header.php");
$result = mysql_query("SELECT * FROM $prefix"._guestbook."");
$guest_num = mysql_num_rows($result);

OpenTable();
echo "<center><b>"._GUESTWELCOME." $sitename "._GUESTBOOK."</b><br><br>";
echo ""._GUESTTHANKS."<br><br>";
echo "<img src=\"modules/$module_name/images/point3.gif\" border=0><font size=1>"._GUESTTOTALREC." $guest_num <img src=\"modules/$module_name/images/point3.gif\" border=0>"._GUESTVIEWEDPAGE." $nb<br>";
echo ""._TODAYIS." ".date("l, Y-m-d")."</font></center>";
CloseTable();
echo "<br>";
OpenTable();
		if ($ipcheck) {
			if ($admin) {
			include ("auth.inc.php");
			$sql=mysql_query("SELECT ip FROM $prefix"._guestbook." WHERE id_msg='$id_msg'");
			list($ip)=mysql_fetch_row($sql);
			echo "<center><b>"._GUESTIP."</b><br>"
			."$ip<br>"
			."<a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=index\">"._GUESTBACK."</a></center>";
			CloseTable();
			include "footer.php";
			die;
			}
		}
		if (!$private) { $private=0; }
		$ip=$REMOTE_ADDR;
		$hote=gethostbyaddr($ip);
		$time = date("Y/m/d H:i");

       		if($gname){
			if($email){
				$email_tmp=ereg("^[_[:alnum:]-]+(\.[_[:alnum:]-]+)*@[[:alnum:]-]+(\.[[:alpha:]]+)+$",$email);
				if($email_tmp==0){
					echo ""._GUESTBADEMAIL."";
					die;
				}
			}
			if(!$message){
				echo ""._GUESTERRMSG."";
				die;
			}
			$query2="INSERT INTO $prefix"._guestbook."(name,email,ip,message,icq,homepage,bdate,members,private) VALUES ('$gname','$email','$ip','$message','$icq','$homepage','$time','$members','$private')";
			$result2=@mysql_query($query2);
		}
 if ($rub=="signer") {
echo "
		<table cellpadding=\"1\" cellspacing=\"0\" border=\"0\" width=\"100%\">		
        <tr><td align=\"right\" colspan=\"2\">
    	     <img src=\"modules/$module_name/images/point3.gif\" border=0><img src=\"modules/$module_name/images/point3.gif\" border=0> <a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=index\">"._GUESTVIEW."</a>
	      </td></tr></table>
 
<table width=\"100%\" cellpadding=\"0\" cellspacing=\"0\" border=\"0\">
  <tr valign=\"top\"> 
    <td>
	<table width=\"100%\" border=\"0\" cellpadding=\"4\" cellspacing=\"0\" bgcolor=\"$bgcolor4\">
        <tr>

            <form name=\"form1\" action=\"modules.php\" method=\"post\">

                  <td width=\"77%\" valign=\"top\">";
                  
					if ($user) {
						$userdata=cookiedecode($user);
                    	echo"
                    	"._GUESTNAME."&nbsp;<b>$userdata[1]</b>
                    	<input type=\"hidden\" name=\"gname\" value=\"$userdata[1]\">
                    	<input type=\"hidden\" name=\"members\" value=\"1\">";
                    }
                    else {
                    echo"
                    "._GUESTNAME."<br>
                    <input type=\"text\" SIZE=\"20\" name=\"gname\">
                    <input type=\"hidden\" name=\"members\" value=\"0\">";
                    }

                    echo"
                    <br>
                    "._GUESTEMAIL."<br>
		    <input type=\"text\" SIZE=\"20\" name=\"email\">
                    <br>
                    "._GUESTUIN."<br>
                    <input type=\"text\" SIZE=\"20\" MAXLENGTH=\"10\" name=\"icq\">
                    <br>
                    "._GUESTWEBSITE."<br>
                    <input type=\"text\" SIZE=\"20\" name=\"homepage\" value=\"http://\">
                    <br>
                    "._GUESTMSG."<br>
                    <textarea name=\"message\" ROWS=\"10\" COLS=\"50\"></textarea>
                    <br>
                    "._GUESTPRIVATE."<br>
                    <input type=\"checkbox\" name=\"private\" value=\"1\">
					<br>
                    <input type=\"hidden\" name=\"date\">
                    <br>
                    <input type=\"hidden\" name=\"op\" value=\"modload\">
					<input type=\"hidden\" name=\"name\" value=\"$module_name\">
                    <input type=\"hidden\" name=\"file\" value=\"index\">					
                    <input type=\"submit\" name=\"Submit\" value=\""._GUESTPOST."\">
                    </td>
                </tr>
              </table>
            </form>
          </td>
        </tr>
      </table>";
      CloseTable();
      include("footer.php");
	  die;
	  } else {
      echo "
		<table cellpadding=\"1\" cellspacing=\"0\" border=\"0\" width=\"100%\">		
        <tr><td align=\"right\" colspan=\"2\">
    	     <img src=\"modules/$module_name/images/point3.gif\" border=0><img src=\"modules/$module_name/images/point3.gif\" border=0> <a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=index&amp;rub=signer\">"._GUESTSIGN."</a>
	      </td></tr></table>";
}
?>
<table cellpadding="1" cellspacing="0" border="0" width="100%" bgcolor="<? echo"$bgcolor4";?>">
<tr><td>
<table cellpadding="4" cellspacing="1" border="0" width="100%">
<tr bgcolor="<? echo"$bgcolor3";?>"><td width="20%"><font color="<? echo"$textcolor1";?>" size="3"><b><? echo""._GUESTSIGNBY."";?></b></font></td><td width="80%"><font color="<? echo"$textcolor1";?>" size="3"><b><? echo""._GUESTCOMMENT."";?></b></font></td></tr>

<?
if(empty($page)) $page = 1;

if(empty($total)){ // Total Number of result
	$sql = "select count(*) as qte from $prefix"._guestbook."";
	$p = @mysql_query($sql);
	$total = @mysql_result($p,"0","qte");
}

$a = 0;
$dcolor_A = "$bgcolor2";
$dcolor_B = "$bgcolor1";

$debut = ($page - 1) * $nb;

$sql = "select * from $prefix"._guestbook." order by id_msg desc LIMIT $debut,$nb";
if($p = @mysql_query($sql)){
while($r = @mysql_fetch_array($p)){

$dcolor = ($a == 0 ? $dcolor_A : $dcolor_B);

echo "<tr valign=\"top\" bgcolor=\"$dcolor\"><td>";

if ($r[members]) {
	echo "<a href=\"user.php?op=userinfo&amp;uname=$r[name]\"><img src=\"modules/$module_name/images/profile.gif\" alt=\"$r[name] Profiles\" border=\"0\"></a>";
} else {
	echo "<img src=\"modules/$module_name/images/profile.gif\" alt=\"No Profiles\" border=\"0\">";
}
	echo "&nbsp;$r[name]</td>";
	echo "<td><img src=\"modules/$module_name/images/posticon.gif\" alt=\"\" border=\"0\">&nbsp;"._GUESTSIGNON." ".displayDate($r[bdate])."<br>";

if ($admin) {
	$r[message]=nl2br($r[message]);
	$r[message]=stripslashes($r[message]);
	$r[message]=smile($r[message]);
}
else {
if ($r[private]) {
$r[message] = ""._GUESTPRV."";
}
	$r[message]=nl2br($r[message]);
	$r[message]=stripslashes($r[message]);
	$r[message]=smile($r[message]);
}


?>

<font size="2" face="arial"><? echo"$r[message]";?></font>
</td></tr>
<tr bgcolor="<? echo"$dcolor";?>"><td align="left">

<?
if($r[icq]>0) {
	echo "<img src=\"http://wwp.icq.com/scripts/online.dll?icq=$r[icq]&amp;img=5\" border=\"0\">";
}
if($r[email]!="") {
	echo "<a href=\"mailto:$r[email]\"><img src=\"modules/$module_name/images/email.gif\" alt=\"email\" border=\"0\"></a>";
}
if($r[homepage]!="") {
	echo "<a href=\"$r[homepage]\" target=\"_blank\"><img src=\"modules/$module_name/images/www_icon.gif\" alt=\"HomePage\" border=\"0\"></a>";
}

if ($admin) {
echo "</td><td align=right><a href=\"modules.php?op=modload&name=$module_name&file=index&id_msg=$r[id_msg]&ipcheck=1\"><img src=\"modules/$module_name/images/ip_logged.gif\" alt=\"IP\" border=\"0\"></a></td></tr>";
}
else { echo "</td><td align=right><img src=\"modules/$module_name/images/ip_logged.gif\" alt=\"Admin Only\" border=\"0\"></td></tr>"; }

$a = ($dcolor == $dcolor_A ? 1 : 0);


	}

}


echo"</table>
</td></tr><tr><td align=\"center\"><font face=\"arial\" size=\"1\">
";

	$nbpages = ceil($total / $nb); 
	echo "[";
	for($i = 1;$i <= $nbpages;$i ++){
		if ($i==1) {
		echo " <a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=index&amp;total=$total\">$i</a> ";
		} else {
		echo " <a href=\"modules.php?op=modload&amp;name=$module_name&amp;file=index&amp;page=$i&amp;total=$total\">$i</a> ";
		}
		if($i < $nbpages) 
			echo " . ";
	}
    	echo " 
	]</font></td>
  	</tr>
  	<tr><td align=\"right\">
  	<font face=\"arial\" size=\"1\">Powered by: Guestbook AddOn Modules<br>
  	© 2001 Copyright <A href=\"http://www.nukeaddon.com\">NukeAddOn.com</a></font>
  	</td></tr>
	</table>";
    	CloseTable();
    	include("footer.php");

?>