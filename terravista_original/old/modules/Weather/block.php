<?

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
$accid="WIII"; # Your city location code
$cache_file = "cache/weather.cache"; # Your cache directory
$cache_time	=	3600;

# Do not edit anything beyond this point on
################################################

$time	=	split(" ", microtime());
srand((double)microtime()*1000000);
$cache_time_rnd	=	300 - rand(0, 600);
    
if ( (!(file_exists($cache_file))) || ((filectime($cache_file) + $cache_time - $time[1]) + $cache_time_rnd < 0) || (!(filesize($cache_file))) ) {
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

	  
	    $fpwrite = fopen($cache_file, 'w');
		if(!$fpwrite) {
				echo "The system fail to write files. Please check your /cache directory CHMOD";
			} else {
				$boxtitle = "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"1\">\n";
				$boxtitle .= "<tr>\n";
				$boxtitle .= "<td>\n";
				$boxtitle .="<font size=\"2\"><b>$v_City</b><font>\n";
				$boxtitle .="</td>\n";
				$boxtitle .="</tr>\n";
				$boxtitle .="<tr>\n";
				$boxtitle .="<td valign=\"top\">\n";
				$boxtitle .="<img src=\"images/weather/".$v_CIcon."_w.gif\">\n";
				$boxtitle .="</td></tr>\n";
				$boxtitle .="<tr><td><font size=\"2\">\n";
				$boxtitle .="<b>Temperature:</b> $v_Temp<br>\n";
				$boxtitle .="<b>Humidity:</b> $v_Humid%<br>\n";
				$boxtitle .="<b>Barometer:</b> $v_Baro<br>\n";
				$boxtitle .="<b>Wind:</b> $v_WindD at $v_WindS mph<br>\n";
				$boxtitle .="<b>Real Feel:</b> $v_Real&deg;<br>\n";
				$boxtitle .="<b>UV:</b> $v_UV<br>\n";
				$boxtitle .="<b>Visibility:</b> $v_Vis<br><br>\n";
				$boxtitle .="</font></td></tr>\n";
				$boxtitle .="<tr>\n";
				$boxtitle .="<td align=\"right\">\n";				
				$boxtitle .="<font size=\"1\"><a href=\"modules.php?op=modload&amp;name=".$module_name."&amp;file=index\">More</a>\n";
				$boxtitle .="</font></td></tr>\n";
				$boxtitle .="</table>";
				fputs($fpwrite, "$boxtitle");
			}
		fclose($fpwrite);
		}
fclose($fa);
}		
	if (file_exists($cache_file)) {
	include($cache_file);
}

?>    