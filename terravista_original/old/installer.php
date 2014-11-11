<?

######################################################################
# PHP-NUKE Add-On 5.0 : Installer AddOn
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

include("config.php");
$nukeaddver = "1.02.BETA";
include("install/installlib.php");

if (isset($newlang)) {
    if (file_exists("install/language/lang-$newlang.php")) {
	setcookie("lang",$newlang,time()+31536000);
	include("install/language/lang-$newlang.php");
    } else {
	setcookie("lang",$language,time()+31536000);
	include("install/language/lang-$language.php");
    }
} elseif (isset($lang)) {
    include("install/language/lang-$lang.php");
} else {
    setcookie("lang",$language,time()+31536000);
    include("install/language/lang-$language.php");
}


# Choose your language
######################
function LanguageInstallPages() {
HeaderInstaller("language");

echo ""._LANGSEL.":<br>";
$handle=opendir('install/language');
    while ($file = readdir($handle)) {
		if (ereg("^lang\-(.+)\.php", $file, $matches)) {
            $langFound = $matches[1];
            $languageslist .= "$langFound ";
        }
    }
    closedir($handle);
    $languageslist = explode(" ", $languageslist);
    for ($i=0; $i < sizeof($languageslist); $i++) {
		if($languageslist[$i]!="") {
			echo "<a href=\"installer.php?step=open&amp;newlang=$languageslist[$i]\"><img src=\"images/language/flag-$languageslist[$i].png\" border=\"0\"></a>&nbsp;";
		}
	}
FooterInstaller();
}

# Perform Nuke checking
#######################
function FirstInstallPages($newlang) {
global $start, $startnew, $startadd, $lang;
HeaderInstaller("startout");

#echo "<font size=\"4\">"._WARNING."</font><br>\n";
echo "<font size=\"4\">"._ADDHELP."  <a href=\"install/manual/$newlang/index.html\">"._ADDREADME."</a></font><br><br>\n";

echo ""._CHECKING441."<br>\n";

$start = 0;
DataOldCheck();
if ($start < 50) {
	$nukeold = 0;
} else { 
	$nukeold = 1;
}

echo "<br>\n";
echo ""._CHECKING50."<br>\n";
$startnew = 0;
DataNewCheck();
if ($startnew < 47) {
	$nukenew = 0;
}
elseif ($startnew == "47") {
	$nukenew = 1;
}
elseif ($startnew == "59") {
	$nukenew = 2;
}

echo "<br>\n";
echo ""._CHECKINGADD."<br>\n";
$startadd = 0;
DataAddOnCheck();
if ($startadd == 0) {
	$nukeadd = 2;
}
elseif ($startadd > 0 && $startadd < 6) {
	$nukeadd = 1;
} 
elseif ($startadd == 6) { 
	$nukeadd = 0;
}


if ($nukeold) {
	echo "<br>"._RUN441."";
	}
elseif ($nukenew == "1") { 
	echo "<br>"._RUN50."";
	}
elseif ($nukenew == "2") { 
	echo "<br>"._RUN50UP."";
}

if ($nukeadd == "1") {
	echo "<br>"._RUNADDUP."";
	}
elseif ($nukeadd == "2") {
	echo "<br>"._RUNADDNO."";
}
elseif ($nukeadd == "0") {
	echo "<br>"._RUNADDINST."";
}

echo "<form action=\"installer.php\" method=\"post\">";

if ($nukeold) {
	echo "<input type=\"hidden\" name=\"step\" value=\"install\">";
	echo "<input type=\"submit\" value=\""._BUTTON441."\">";
	}
if ($nukenew == "1" && $nukeadd == "1") { 
	echo "<input type=\"hidden\" name=\"step\" value=\"installtwo\">";
	echo "<input type=\"submit\" value=\""._BUTTON50UP."\">";
	}
elseif ($nukenew == "2" && $nukeadd == "1") {
	echo "<input type=\"hidden\" name=\"step\" value=\"installthree\">";
	echo "<input type=\"submit\" value=\""._BUTTON50UP."\">";
	}
elseif ($nukenew == "1" && $nukeadd == "2") {
	echo "<input type=\"hidden\" name=\"step\" value=\"installtwo\">";
	echo "<input type=\"submit\" value=\""._BUTTON50UP."\">";
}
elseif ($nukenew == "2" && $nukeadd == "2") {
	echo "<input type=\"hidden\" name=\"step\" value=\"installthree\">";
	echo "<input type=\"submit\" value=\""._BUTTON50UP."\">";
}

	
echo "</form>";
FooterInstaller();
}

# Start Upgrade
###############
function SecondInstallPages() {
HeaderInstaller("secondpagesup");

echo ""._PERFORMUP50."<br>";
Upgrade441To50();

echo "<br><br>";
echo "<form action=\"installer.php\" method=\"post\">";
echo "<input type=\"hidden\" name=\"step\" value=\"installthree\">";
echo "<input type=\"submit\" value=\""._BUTTON50UP."\">";
echo "</form>";

FooterInstaller();
}

# Start AddOn Full Install
##########################
function ThirdInstallPages() {
HeaderInstaller("secondpagesadd");
echo ""._CHOOSEADD."<br>";
?>

<br>
<table cellpadding="5" cellspacing="0" border="0">
<form action="installer.php" method="post">
<input type="hidden" name="step" value="addontwo">

<tr><td><? echo ""._ADDINSGLOS.""; ?></td><td>
<input type="checkbox" name="addglossary" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSGUES.""; ?></td><td>
<input type="checkbox" name="addguestbook" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSCB.""; ?></td><td>
<input type="checkbox" name="addchatbox" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSFOR.""; ?></td><td>
<input type="checkbox" name="addforum" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSIM.""; ?></td><td>
<input type="checkbox" name="addim" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSCAL.""; ?></td><td>
<input type="checkbox" name="addcalendar" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSWEAT.""; ?></td><td>
<input type="checkbox" name="addweather" value="1" checked><br>
</td></tr>
<tr><td colspan="2">
<input type="submit" value="<? echo ""._BUTTON50UP."";?>">
</td></tr>
</form>
</table>
<?
FooterInstaller();
}

# Start AddOn Partial Install
#############################
function FourthInstallPages() {
HeaderInstaller("secondpagesadd");
echo ""._CHOOSEADD."<br>";
?>

<br>
<table cellpadding="5" cellspacing="0" border="0">
<form action="installer.php" method="post">
<input type="hidden" name="step" value="addon">

<tr><td><? echo ""._ADDINSGLOS.""; ?></td><td>
<input type="checkbox" name="addglossary" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSGUES.""; ?></td><td>
<input type="checkbox" name="addguestbook" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSCB.""; ?></td><td>
<input type="checkbox" name="addchatbox" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSFOR.""; ?></td><td>
<input type="checkbox" name="addforum" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSIM.""; ?></td><td>
<input type="checkbox" name="addim" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSCAL.""; ?></td><td>
<input type="checkbox" name="addcalendar" value="1" checked><br>
</td></tr>
<tr><td><? echo ""._ADDINSWEAT.""; ?></td><td>
<input type="checkbox" name="addweather" value="1" checked><br>
</td></tr>
<tr><td colspan="2">
<input type="submit" value="<? echo ""._BUTTON50UP."";?>">
</td></tr>
</form>
</table>
<?
FooterInstaller();
}

# Start AddOn Install
#####################
function FifthInstallPages($addglossary,$addguestbook,$addchatbox,$addforum,$addim,$addcalendar,$addweather) {
HeaderInstaller("finish");

echo ""._INSTALLINGADD."<br>";
Upgrade50Full($addglossary,$addguestbook,$addchatbox,$addforum,$addim,$addcalendar,$addweather);

echo "<br><br>"._ERRORMSG."<br>";
echo ""._THANKYOUFULL."<br>";

FooterInstaller();
}

# Start AddOn Install
#####################
function SixInstallPages($addglossary,$addguestbook,$addchatbox,$addforum,$addim,$addcalendar,$addweather) {
HeaderInstaller("finish");

echo ""._INSTALLINGADD."<br>";
Upgrade50Old($addglossary,$addguestbook,$addchatbox,$addforum,$addim,$addcalendar,$addweather);

echo "<br><br>"._ERRORMSG."<br>";
echo ""._THANKYOUFULL."<br>";

FooterInstaller();
}

switch($step) {

	default:
		LanguageInstallPages();
	break;

	case "open":
		FirstInstallPages($newlang);
	break;
	
	case "install":
		SecondInstallPages();
	break;

	case "installtwo":
		ThirdInstallPages();
	break;

	case "installthree":
		FourthInstallPages();
	break;

	case "addontwo":
		FifthInstallPages($addglossary,$addguestbook,$addchatbox,$addforum,$addim,$addcalendar,$addweather);
	break;

	case "addon":
		SixInstallPages($addglossary,$addguestbook,$addchatbox,$addforum,$addim,$addcalendar,$addweather);
	break;

}
?>