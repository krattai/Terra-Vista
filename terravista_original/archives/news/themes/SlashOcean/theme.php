<?php

$bgcolor1 = "#FFFFFF";
$bgcolor2 = "#101070";
$bgcolor3 = "#e6e6e6";
$bgcolor4 = "#e6e6e6";
$textcolor1 = "#FFFFFF";
$textcolor2 = "#000000";

function OpenTable() {
    global $bgcolor1, $bgcolor2;
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"$bgcolor2\"><tr><td>\n";
    echo "<table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"8\" bgcolor=\"$bgcolor1\"><tr><td>\n";
}

function CloseTable() {
    echo "</td></tr></table></td></tr></table>\n";
}

function OpenTable2() {
    global $bgcolor1, $bgcolor2;
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"$bgcolor2\" align=\"center\"><tr><td>\n";
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"8\" bgcolor=\"$bgcolor1\"><tr><td>\n";
}

function CloseTable2() {
    echo "</td></tr></table></td></tr></table>\n";
}

function FormatStory($thetext, $notes, $aid, $informant) {
    global $anonymous;
    if ($notes != "") {
	$notes = "<b>"._NOTE."</b> <i>$notes</i>\n";
    } else {
	$notes = "";
    }
    if ("$aid" == "$informant") {
	echo "<font size=\"2\" color=\"#505050\">$thetext<br>$notes</font>\n";
    } else {
	if($informant != "") {
	    $boxstuff = "<a href=\"user.php?op=userinfo&amp;uname=$informant\">$informant</a> ";
	} else {
	    $boxstuff = "$anonymous ";
	}
	$boxstuff .= "".translate("writes")." <i>\"$thetext\"</i> $notes\n";
	echo "<font size=\"2\" color=\"#505050\">$boxstuff</font>\n";
    }
}

function themeheader() {
    global $slogan, $sitename, $banners;
echo "<body bgcolor=FFFFFF text=000000 link=101070 vlink=101070>
<br>";
if ($banners) {
    include("banners.php");
}
echo "<br>
<center>
<table cellpadding=0 cellspacing=0 border=0 width=99% align=center><tr><td align=left>
<a href=$nuke_url><img src=themes/SlashOcean/images/logo.gif Alt=\"".translate("Welcome to")." $sitename\" border=0></a>
</td><td align=right width=100%>
	<form action=search.php method=post>
	<font size=2><input type=text name=query width=20 size=20 length=20>
	</td>
	<td align=right>&nbsp;&nbsp;<input type=submit value=\""._SEARCH."\"></td>
	</form>";
    echo "</td></tr></table><br>";
echo "
<table cellpadding=0 cellspacing=0 border=0 width=99% bgcolor=101070><tr><td>
<table cellpadding=5 cellspacing=1 border=0 width=100% bgcolor=FFFFFF><tr><td>
<font size=2>$slogan</td></tr></table></td></tr></table><P>
<table width=99% align=center cellpadding=0 cellspacing=0 border=0><tr><td valign=top rowspan=15>";

blocks(left);

echo "</td><td>&nbsp;&nbsp;</td><td valign=top width=100%>";
}

function themefooter() {
    global $index;
echo "</td>";
if ($index == 1) {
    echo "<td>&nbsp;&nbsp;</td><td valign=\"top\">";
    blocks(right);
}
echo "</tr></table></td></tr></table>";
footmsg();
}

function themeindex ($aid, $informant, $datetime, $title, $counter, $topic, $thetext, $notes, $morelink, $topicname, $topicimage, $topictext) {
	global $tipath, $anonymous;
	if ("$aid" == "$informant") { ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF"><tr valign="top" bgcolor="#101070">
			<td><img src="themes/SlashOcean/images/cl.gif" width="7" height="10" alt=""><img src="themes/SlashOcean/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font size="3" color="#FFFFFF"><B><?php echo"$title"; ?></B></font>
			</td></tr></table>
			</td><td align="right"><img src="themes/SlashOcean/images/pix.gif" width="4" height="4" alt=""><img src="themes/SlashOcean/images/cr.gif" width="7" height="10" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/SlashOcean/images/gl.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
			<td width="100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
				<td><font size="1"><?php echo translate("Posted by "); ?> <?php formatAidHeader($aid) ?> <?php echo translate("on"); ?> <?php echo"$datetime $timezone"; ?> (<?php echo $counter; ?> <?php echo translate("reads"); ?>)</font></td>
				</tr></table>
			</td>
			<td background="themes/SlashOcean/images/gr.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
		</tr>
		<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/SlashOcean/images/wl.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
			
			<a href="search.php?query=&topic=<?php echo"$topic"; ?>&author="><img src=<?php echo"$tipath$topicimage"; ?> border=0 Alt=<?php echo"\"$topictext\""; ?> align=right hspace=10 vspace=10></a>
			
			<?php echo "<font size=2>$thetext"; ?>
                 </td></tr></table></td>
                 <td background="themes/SlashOcean/images/wr.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
		</tr>
		<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
		</table><table border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor="#ffffff">
			<td background="themes/SlashOcean/images/wl_cccccc.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="5" cellspacing="0"> 
			<tr><td bgcolor="#cccccc"><font size="2"><?php echo"$morelink"; ?></font></td></tr></table>
			</td>
			<td background="themes/SlashOcean/images/wr_cccccc.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
		</tr>
		<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
		</table><BR><BR>
<?php	} else {
		if($informant != "") $boxstuff = "<font size=2><a href=\"user.php?op=userinfo&uname=$informant\">$informant</a> ";
		else $boxstuff = "$anonymous ";
		$boxstuff .= "<font size=2>".translate("writes")." <i>\"$thetext\"</i> $notes";
?>		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF"><tr valign="top" bgcolor="#101070">
			<td><img src="themes/SlashOcean/images/cl.gif" width="7" height="10" alt=""><img src="themes/SlashOcean/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%"><table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font size="3" color="#FFFFFF"><B><?php echo"$title"; ?></B></font>
			</td></tr></table></td>
                 	<td align="right"><img src="themes/SlashOcean/images/pix.gif" width="4" height="4" alt=""><img src="themes/SlashOcean/images/cr.gif" width="7" height="10" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/SlashOcean/images/gl.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
			<td width="100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
				<td><font size="1"><?php echo translate("Posted by "); ?> <?php formatAidHeader($aid) ?> <?php echo translate("on"); ?> <?php echo"$datetime $timezone"; ?> (<?php echo $counter; ?> <?php echo translate("reads"); ?>)</font></td>
				</tr></table>
			</td>
			<td background="themes/SlashOcean/images/gr.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
		</tr>
		<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/SlashOcean/images/wl.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
			<a href="search.php?query=&topic=<?php echo"$topic"; ?>&author="><img src=<?php echo"$tipath$topicimage"; ?> border=0 Alt=<?php echo"\"$topictext\""; ?> align=right hspace=10 vspace=10></a>
			<?php echo "$boxstuff"; ?>
                 </td></tr></table></td>
                 <td background="themes/SlashOcean/images/wr.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
		</tr>
		<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
		</table><table border="0" cellpadding="0" cellspacing="0">
		<tr bgcolor="#ffffff">
			<td background="themes/SlashOcean/images/wl_cccccc.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td bgcolor="#cccccc"><font size="2"><?php echo"$morelink"; ?></font></td></tr></table>
			</td>
			<td background="themes/SlashOcean/images/wr_cccccc.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
		</tr>
		<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
		</table><BR><BR>
<?php	}
}

function themearticle ($aid, $informant, $datetime, $title, $thetext, $topic, $topicname, $topicimage, $topictext) {
	global $admin, $sid, $tipath;
	if ("$aid" == "$informant") { ?>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF"><tr valign="top" bgcolor="#101070">
			<td><img src="themes/SlashOcean/images/cl.gif" width="7" height="10" alt=""><img src="themes/SlashOcean/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font size="3" color="#FFFFFF"><B><?php echo"$title"; ?></B></font>
			</td></tr></table>
			</td><td align="right"><img src="themes/SlashOcean/images/pix.gif" width="4" height="4" alt=""><img src="themes/SlashOcean/images/cr.gif" width="7" height="10" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/SlashOcean/images/gl.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
			<td width="100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
				<td><font size="1"><?php echo translate("Posted by "); ?> <?php formatAidHeader($aid) ?> <?php echo translate("on"); ?> <?php echo"$datetime $timezone"; ?></font>

<?php
if ($admin) {
    echo "&nbsp;&nbsp; <font size=2> [ <a href=admin.php?op=EditStory&sid=$sid>".translate("Edit")."</a> | <a href=admin.php?op=RemoveStory&sid=$sid>".translate("Delete")."</a> ]";
}
?>
				</td>
				</tr></table>
			</td>
			<td background="themes/SlashOcean/images/gr.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
		</tr>
		<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/SlashOcean/images/wl.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
		<?php echo "<a href=search.php?query=&topic=$topic&author=><img src=$tipath$topicimage border=0 Alt=\"$topictext\" align=right hspace=10 vspace=10></a>"; ?>
			<?php echo "<font size=2>$thetext"; ?>
                 </td></tr></table></td>
                 <td background="themes/SlashOcean/images/wr.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
		</tr>
		<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
		</table>
<?php	} else {
		if($informant != "") $boxstuff = "<font size=2><a href=\"user.php?op=userinfo&uname=$informant\">$informant</a> ";
		else $boxstuff = "$anonymous ";
		$boxstuff .= "<font size=2>".translate("writes")." <i>\"$thetext\"</i> $notes";
?>		<table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF"><tr valign="top" bgcolor="#101070">
			<td><img src="themes/SlashOcean/images/cl.gif" width="7" height="10" alt=""><img src="themes/SlashOcean/images/pix.gif" width="4" height="4" alt=""></td>
			<td width="100%">
			<table width="100%" border="0" cellpadding="2" cellspacing="0"><tr><td>
			<font size="3" color="#FFFFFF"><B><?php echo"$title"; ?></B></font>
			</td></tr></table>
			</td><td align="right"><img src="themes/SlashOcean/images/pix.gif" width="4" height="4" alt=""><img src="themes/SlashOcean/images/cr.gif" width="7" height="10" alt=""></td>
         	</tr></table>
		<table border="0" cellpadding="0" cellspacing="0"><tr bgcolor="#e6e6e6">
			<td background="themes/SlashOcean/images/gl.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
			<td width="100%">
				<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr>
				<td><font size="1"><?php echo translate("Posted by "); ?> <?php formatAidHeader($aid) ?> <?php echo translate("on"); ?> <?php echo"$datetime $timezone"; ?></font>
				
<?php
if ($admin) {
    echo "&nbsp;&nbsp; <font size=2> [ <a href=admin.php?op=EditStory&sid=$sid>Editar</a> | <a href=admin.php?op=RemoveStory&sid=$sid>Borrar</a> ]";
}
?>
<br><?php echo "<font size=1"; ?>
<?php echo "".translate("Contributed by ").""; ?> <?php echo "<a href=\"user.php?op=userinfo&uname=$informant\">$informant</a>"; ?>
				</td>
				</tr></table>
			</td>
			<td background="themes/SlashOcean/images/gr.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
		</tr>
		<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
		<tr bgcolor="#ffffff">
			<td background="themes/SlashOcean/images/wl.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
			<td width="100%"><table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td>
		<?php echo "<a href=search.php?query=&topic=$topic&author=><img src=$tipath$topicimage border=0 Alt=\"$topictext\" align=right hspace=10 vspace=10></a>"; ?>
			<?php echo "<font size=2>$thetext"; ?>
                 </td></tr></table></td>
                 <td background="themes/SlashOcean/images/wr.gif"><img src="themes/SlashOcean/images/pix.gif" width="11" height="11" alt=""></td>
		</tr>
		<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
		</table>
<?php	}
}

function themesidebox($title, $content) {
	?>
	<table width="150" border="0" cellpadding="0" cellspacing="0">
	<tr valign="top" bgcolor="#101070">
		<td bgcolor="#FFFFFF"><img src="themes/SlashOcean/images/pix.gif" width="3" height="3" alt=""></td>
		<td><img src="themes/SlashOcean/images/cl.gif" width="7" height="10" alt=""></td>
		<td><font size="1" color="#ffffff"><B><?php echo"$title"; ?></B></font></td>
		<td align="right"><img src="themes/SlashOcean/images/cr.gif" width="7" height="10" alt=""></td>
		<td bgcolor="#FFFFFF" align="right"><img src="themes/SlashOcean/images/pix.gif" width="3" height="3" alt=""></td>
	</tr>
	</table>
	<table width="100%" border="0" cellpadding="0" cellspacing="0">
	<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
	<tr bgcolor="#ffffff">
		<td background="themes/SlashOcean/images/sl.gif"><img src="themes/SlashOcean/images/pix.gif" width="3" height="3" alt=""></td>
		<td width="100%">
		<table width="100%" border="0" cellpadding="5" cellspacing="0"><tr><td><font size=1>
<? 
if (file_exists($content)) {
	$fp = fopen ($content, "r");
	$content = fread($fp, filesize($content));
	fclose ($fp);
	$content = "?>$content<?";
	echo eval($content);
} else {
	echo $content;
}
?>		
		</td></tr></table></td>
		<td background="themes/SlashOcean/images/sr.gif" align="right"><img src="themes/SlashOcean/images/pix.gif" width="3" height="3" alt=""></td>
	</tr>
	<tr bgcolor="#101070"><td colspan="3"><img src="themes/SlashOcean/images/pix.gif" width="1" height="1"></td></tr>
	</table><br><br>
<?php
}

?>
