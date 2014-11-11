<?php

######################################################################
# PHP-NUKE Add-On 5.0 : Weather AddOn
# ============================================
# 
# Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#
# http://www.nukeaddon.com
#
#
#######################################################################
#
# Original code based on METEO live v1.0
# By Martin Bolduc at martin.bolduc@littera.com
# License : Free, do what you want, but please, let my name in reference.
# 
#######################################################################

$module_name = "Weather";  // CHANGE THIS IF YOU CHANGE THE MODULES FOLDER NAME
include("modules/$module_name/config.php");

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

$index = 0;

function WeatherIndex($tp) {
global $module_name, $accid;
include("header.php");
OpenTable();

$fa = fsockopen("www.msnbc.com", 80, &$num_error, &$str_error, 30);
if(!$fa)
   { print "Weather is is not available: $str_error ($num_error)\n"; }
else
{
  fputs($fa,"GET /m/chnk/d/weather_d_src.asp?acid=$accid HTTP/1.0\n\n");
  $answer=fgets($fa,128);

  $v_City    = "";
  $v_SubDiv  = "";
  $v_Country = "";
  $v_Region  = "";
  $v_Temp    = "";
  $v_CIcon   = "";
  $v_WindS   = "";
  $v_WindD   = "";
  $v_Baro    = "";
  $v_Humid   = "";
  $v_Real    = "";
  $v_UV      = "";
  $v_Vis     = "";
  $v_LastUp  = "";
  $v_Fore    = "";
  $v_Acid    = "";


  while (!feof($fa))
     {
     $grabline = fgets($fa, 4096);
     $grabline= trim($grabline) . "\n";
     if (substr($grabline,7,4) == "City")    { $v_City    = substr($grabline,15,20); }
     if (substr($grabline,7,6) == "SubDiv")  { $v_SubDiv  = substr($grabline,17,20); }
     if (substr($grabline,7,7) == "Country") { $v_Country = substr($grabline,18,20); }
     if (substr($grabline,7,6) == "Region")  { $v_Region  = substr($grabline,17,20); }
     if (substr($grabline,7,4) == "Temp")    { $v_Temp    = substr($grabline,15,20); }
     if (substr($grabline,7,5) == "CIcon")   { $v_CIcon   = substr($grabline,16,20); }
     if (substr($grabline,7,5) == "WindS")   { $v_WindS   = substr($grabline,16,20); }
     if (substr($grabline,7,5) == "WindD")   { $v_WindD   = substr($grabline,16,20); }
     if (substr($grabline,7,4) == "Baro")    { $v_Baro    = substr($grabline,15,20); }
     if (substr($grabline,7,5) == "Humid")   { $v_Humid   = substr($grabline,16,20); }
     if (substr($grabline,7,4) == "Real")    { $v_Real    = substr($grabline,15,20); }
     if (substr($grabline,7,2) == "UV")      { $v_UV      = substr($grabline,13,20); }
     if (substr($grabline,7,3) == "Vis")     { $v_Vis     = substr($grabline,14,20); }
     if (substr($grabline,7,5) == "LastUp")  { $v_LastUp  = substr($grabline,16,20); }
     if (substr($grabline,7,4) == "Fore")    { $v_Fore    = substr($grabline,15,200); }
     if (substr($grabline,7,4) == "Acid")    { $v_Acid    = substr($grabline,15,20); }
//     print $grabline . "\n";
     }

  $v_City    = substr($v_City,0,strlen($v_City)-3);
  $v_SubDiv  = substr($v_SubDiv,0,strlen($v_SubDiv)-3);
  $v_Country = substr($v_Country,0,strlen($v_Country)-3);
  $v_Region  = substr($v_Region,0,strlen($v_Region)-3);
  $v_Temp    = substr($v_Temp,0,strlen($v_Temp)-3);
  $v_CIcon   = substr($v_CIcon,0,strlen($v_CIcon)-3);
  $v_WindS   = substr($v_WindS,0,strlen($v_WindS)-3);
  $v_WindD   = substr($v_WindD,0,strlen($v_WindD)-3);
  $v_Baro    = substr($v_Baro,0,strlen($v_Baro)-3);
  $v_Humid   = substr($v_Humid,0,strlen($v_Humid)-3);
  $v_Real    = substr($v_Real,0,strlen($v_Real)-3);
  $v_UV      = substr($v_UV,0,strlen($v_UV)-3);
  $v_Vis     = substr($v_Vis,0,strlen($v_Vis)-3);
  $v_LastUp  = substr($v_LastUp,0,strlen($v_LastUp)-3);
  $v_Fore    = substr($v_Fore,0,strlen($v_Fore)-3);
  $v_Acid    = substr($v_Acid,0,strlen($v_Acid)-3);


$v_Fore = explode("|", $v_Fore);


echo "<div align=\"right\"><small>"._ALLTEMP.": ";
echo "<a href=\"modules.php?op=modload&amp;name=".$module_name."&amp;file=index\">"._FAHREN."</a> "._OR." ";
echo "<a href=\"modules.php?op=modload&amp;name=".$module_name."&amp;file=index&amp;tp=C\">"._CELCIUS."</a>";
echo "</div>";

echo ""._REG.": $v_Region<br>";
echo "$v_City, $v_Country<hr>";
?>

<table cellpadding="4" cellspacing="0" border="0">
<tr><td valign="top">
<img src="images/weather/current_cond.gif" align="top" alt="<?echo ""._CURCOND."";?>"><font size="4" face="Arial" color="red">

<?
if ($tp == "C") {
	echo CelNumber($v_Temp);
} else {
	echo " $v_Temp&deg;";
}
echo "</font> <img src=\"images/weather/".$v_CIcon . "_w.gif\" align=\"center\"><br>";
?>

</td><td>

<?
echo ""._WIND.": $v_WindD $v_WindS mph  "._BARO.": $v_Baro   "._HUMID.": $v_Humid%<hr>";
echo ""._UV.": $v_UV  "._REFE.": $v_Real&deg;   "._VIS.": $v_Vis";
?>

</td></tr>
</table>
<table cellpadding="4" cellspacing="0" border="0">
<tr><td valign="top" align="center">
<img src="images/weather/forecast.gif" alt="<? echo ""._FOREC."";?>">
</td><td>&nbsp;</td><td>

<?
echo Fore($v_Fore[0]);
echo "<br><img src=\"images/weather/".$v_Fore[10]."_w.gif\"></td><td>";
echo Fore($v_Fore[1]);
echo "<br><img src=\"images/weather/".$v_Fore[11]."_w.gif\"></td><td>";
echo Fore($v_Fore[2]);
echo "<br><img src=\"images/weather/".$v_Fore[12]."_w.gif\"></td><td>";
echo Fore($v_Fore[3]);
echo "<br><img src=\"images/weather/".$v_Fore[13]."_w.gif\"></td><td>";
echo Fore($v_Fore[4]);
echo "<br><img src=\"images/weather/".$v_Fore[14]."_w.gif\">";
?>

</td></tr>
<tr><td>&nbsp;</td><td colspan="6"><hr></td></tr>
<tr><td>&nbsp;</td><td><font face="Arial" size="3" color="red"><?echo ""._WHIGH."";?>:</font></td><td>

<?
if ($tp == "C") {
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo CelNumber($v_Fore[15]);
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo CelNumber($v_Fore[16]);
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo CelNumber($v_Fore[17]);
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo CelNumber($v_Fore[18]);
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo CelNumber($v_Fore[19]);
?>
</font>
</td></tr>
<tr><td>&nbsp;</td><td colspan="6"><hr></td></tr>
<tr><td>&nbsp;</td><td><?echo ""._WLOW."";?>:</td><td>

<?
echo CelNumber($v_Fore[25]);
echo "</td><td>";
echo CelNumber($v_Fore[26]);
echo "</td><td>";
echo CelNumber($v_Fore[27]);
echo "</td><td>";
echo CelNumber($v_Fore[28]);
echo "</td><td>";
echo CelNumber($v_Fore[29]);
?>

</td></tr></table>
<hr>

<?
} else {

echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo "$v_Fore[15]&deg;";
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo "$v_Fore[16]&deg;";
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo "$v_Fore[17]&deg;";
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo "$v_Fore[18]&deg;";
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo "$v_Fore[19]&deg;";
?>
</font>
</td></tr>
<tr><td>&nbsp;</td><td colspan="6"><hr></td></tr>
<tr><td>&nbsp;</td><td><?echo ""._WLOW."";?>:</td><td>

<?
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo "$v_Fore[25]&deg;";
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo "$v_Fore[26]&deg;";
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo "$v_Fore[27]&deg;";
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo "$v_Fore[28]&deg;";
echo "</font>";
echo "</td><td>";
echo "<font face=\"Arial\" size=\"3\" color=\"red\">";
echo "$v_Fore[29]&deg;";
}
?>

</td></tr></table>
<hr>

<?
fclose($fa);
}
?>


<div align="right"><small>Powered by: Weather AddOn Modules<br>
© 2001 Copyright <A href="http://www.nukeaddon.com">NukeAddOn.com</a><br>
PHP Nuke AddOn Weather is provided by MSNBC Weather</small></div>

<?
CloseTable();
include ("footer.php");
}


function CelNumber($number) {
	$number = $number-32;
	$number = $number * 5;
	$number = $number / 9;
	$number = round ($number);
	echo "$number&deg;";
}

function Fore($numbers) {
	if ($numbers == "1") {
		$date=""._WSUN."";
		echo "$date";
	}
	elseif ($numbers == "2") {
		$date=""._WMON."";
		echo "$date";
	}
	elseif ($numbers == "3") {
		$date=""._WTUE."";
		echo "$date";
	}
	elseif ($numbers == "4") {
		$date=""._WWED."";
		echo "$date";
	}
	elseif ($numbers == "5") {
		$date=""._WTHU."";
		echo "$date";
	}
	elseif ($numbers == "6") {
		$date=""._WFRI."";
		echo "$date";
	} else {
		$date=""._WSAT."";
		echo "$date";
	}
}				

# End AddOn Modules

switch($func) {

    default:
    WeatherIndex($tp);
    break;
}

?>