<?php

######################################################################
# PHP-NUKE: Web Portal System
# ===========================
#
# Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)
# http://phpnuke.org
#
# This modules is the main administration part
#
# This program is free software. You can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License.
######################################################################
# PHP-NUKE Add-On 5.0 : PHPBB Forum AddOn
# ============================================
# 
# Brought to you by PHPNuke Add-On Team
#
# Copyright (c) 2001 by Richard Tirtadji AKA King Richard (rtirtadji@hotmail.com)
#						Hutdik Hermawan AKA hotFix (hutdik76@hotmail.com)
#
# http://www.nukeaddon.com
#
#
#######################################################################

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

$index = 0;

#$addterm,$sortby,$adv,

function SearchBB($addterms,$sortby,$adv,$term,$forum,$username,$sortby,$from) {
$index = 0;
global $user,$REMOTE_ADDR,$HTTP_COOKIE_VARS,$bgcolor1,$bgcolor2,$bgcolor3,$textcolor1,$textcolor2,$prefix;
include('config.php');
include('functions.php');
include('auth.php');
include('header.php');

?>
<FORM NAME="Search" ACTION="<?php echo $PHP_SELF; ?>" METHOD="POST">
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="0"  VALIGN="TOP" WIDTH="100%">
<TR>
	<TD  BGCOLOR="<?php echo $table_bgcolor?>" ALIGN="LEFT">
	<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%" BGCOLOR="<?php echo $bgcolor1; ?>">
	<TR>
	<TD BGCOLOR="<?php echo $bgcolor1?>" WIDTH="20%" ALIGN="RIGHT">
		<b><?php echo translate("Keyword");?></b>:&nbsp;
	</TD>
	<TD BGCOLOR="<?php echo $bgcolor3?>" WIDTH="80%">
		<INPUT CLASS="textbox" TYPE="text" name="term">
	</TD>
	</TR>
	<TR>
	<TD BGCOLOR="<?php echo $bgcolor1?>" WIDTH="20%">&nbsp;</TD>
	<TD BGCOLOR="<?php echo $bgcolor3?>" WIDTH="80%">
		<INPUT TYPE="radio" name="addterms" value="any" CHECKED>
		<font size=-2><?php echo translate("Search for ANY of the terms (Default)"); ?>
	</TD>
	</TR>
	<TR>
	<TD BGCOLOR="<?php echo $bgcolor1?>" WIDTH="20%">&nbsp;</TD>
	<TD BGCOLOR="<?php echo $bgcolor3?>" WIDTH="80%">
		<INPUT TYPE="radio" name="addterms" value="all">
		<font size=-2><?php echo translate("Search for ALL of the terms"); ?>
	</TD>
	</TR>
	<TR>
	<TD BGCOLOR="<?php echo $bgcolor1?>" WIDTH="20%" ALIGN="RIGHT">
		<b><?php echo translate("Forum");?></b>:&nbsp;
	</TD>
	<TD BGCOLOR="<?php echo $bgcolor3?>" WIDTH="80%">
		<select name="forum">
		<option value="all"><?php echo translate("Search All Forums");?></option>
		<?php
			$query = "SELECT forum_name,forum_id FROM $prefix"._forums."";
			if(!$result = mysql_query($query,$db))
			{
			    forumerror("0015");
			}
			while($row = @mysql_fetch_array($result))
			{
				echo "<option value=$row[forum_id]>$row[forum_name]</option>";
			}
		?>
		</select>
	</TD>
	</TR>
	<TR>
	<TD BGCOLOR="<?php echo $bgcolor1?>" WIDTH="20%" ALIGN="RIGHT">
		<b><?php echo translate("Author's Name");?></b>:
	</TD>
	<TD BGCOLOR="<?php echo $bgcolor3?>" WIDTH="80%">
		<INPUT TYPE="text" CLASS="textbox" name="username">
	</TD>
	</TR>
	<TR>
	<TD BGCOLOR="<?php echo $bgcolor1?>" WIDTH="20%" ALIGN="RIGHT">
		<b><?php echo translate("Sort by");?></b>:
	</TD>
	<TD BGCOLOR="<?php echo $bgcolor3?>" WIDTH="80%">
	<?php //All values are the fields used to search the database - a table must be specified for each field ?>
		<INPUT TYPE="radio" name="sortby" value="p.post_time" CHECKED><?php echo translate("Post Time");?>
		&nbsp;&nbsp;
		<INPUT TYPE="radio" name="sortby" value="t.topic_title"><?php echo translate("Topics");?>
		&nbsp;&nbsp;
		<INPUT TYPE="radio" name="sortby" value="f.forum_name"><?php echo translate("Forum");?>
		&nbsp;&nbsp;
		<INPUT TYPE="radio" name="sortby" value="u.uname"><?php echo translate("Nickname");?>
		&nbsp;&nbsp;
	</TD>
	<TR>
	<TD colspan=2 align=center>
	<INPUT TYPE="HIDDEN" NAME="op" VALUE="modload">
	<INPUT TYPE="HIDDEN" NAME="name" VALUE="Forum">
	<INPUT TYPE="HIDDEN" NAME="file" VALUE="searchbb">	
	<INPUT TYPE="Submit" Name="submit" Value="Search!">&nbsp;&nbsp;<INPUT TYPE="reset" Name="reset" Value="Clear!">
	</TD></TR>
	</TABLE>

	</TD>
</TR>
</TABLE>
</FORM>

<?php

/**********
 Sept 6.
 $query is the basis of the query
 $addquery is all the additional search fields - necessary because of the WHERE clause in SQL
**********/

$query = "SELECT u.uid,f.forum_id,p.topic_id, u.uname, p.post_time,t.topic_title,f.forum_name FROM $prefix"._posts." p, $prefix"._users." u, $prefix"._forums." f, $prefix"._forumtopics." t";
if(isset($term)&&$term!="")
{
	$terms = split(" ",$term);				// Get all the words into an array
	$addquery .= "(p.post_text LIKE '%$terms[0]%'";		
	if($addterms=="any")					// AND/OR relates to the ANY or ALL on Search Page
		$andor = "OR";
	else
		$andor = "AND";
	$size = sizeof($terms);
	for($i=1;$i<$size;$i++)
		$addquery.=" $andor p.post_text LIKE '%$terms[$i]%'";	
	$addquery.=")";
}

if(isset($forum)&&$forum!="all")
{
	if(isset($addquery))
		$addquery.=" AND p.forum_id=$forum AND f.forum_id=$forum";
	else
		$addquery.=" p.forum_id=$forum AND f.forum_id=$forum";
}
if(isset($username)&&$username!="")
{
	if(!$result = mysql_query("SELECT uid FROM $prefix"._users." WHERE uname='$username'",$db))
	{
	    forumerror("0001");
	}
	list($userid) = mysql_fetch_row($result);
	if(!$userid)
	    forumerror("0016");
	if(isset($addquery))
		$addquery.=" AND p.poster_id=$userid AND u.uname='$username'";
	else
		$addquery.=" p.poster_id=$userid AND u.uname='$username'";
}

if(isset($addquery))
	$query.=" WHERE $addquery AND  ";
else
	$query.=" WHERE ";
$query.=" p.topic_id = t.topic_id AND p.forum_id = f.forum_id AND p.poster_id = u.uid ORDER BY $sortby";

	if(!$result = mysql_query($query,$db))
	{
	    forumerror("0001");
	}

	if(!$row = @mysql_fetch_array($result))
	{
		if ($adv != 1){
		    echo "<div><center>".translate("No records match that query. Please broaden your search.")."</center></div>";
		}
	}
?>
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="0" ALIGN="CENTER" VALIGN="TOP" WIDTH="100%"><TR><TD>
<TABLE BORDER="0" CELLPADDING="1" CELLSPACING="1" WIDTH="100%">
<TR BGCOLOR="<?php echo $bgcolor2?>" ALIGN="LEFT">
        <TD ALIGN="CENTER" WIDTH="30%"><FONT SIZE=1 COLOR="<?php echo $textcolor2?>"><B><?php echo translate("Forum");?></B></font></TD>
        <TD ALIGN="CENTER" WIDTH="30%"><FONT SIZE=1 COLOR="<?php echo $textcolor2?>"><B><?php echo translate("Topic");?></B></font></TD>
        <TD ALIGN="CENTER" WIDTH="25%"><FONT SIZE=1 COLOR="<?php echo $textcolor2?>"><B><?php echo translate("Author");?></B></font></TD>
        <TD ALIGN="CENTER" WIDTH="15%"><FONT SIZE=1 COLOR="<?php echo $textcolor2?>"><B><?php echo translate("Posted");?></B></font></TD>
</TR>
<?php
	$count=0;
	do {
	if (($count%2)!=0) $color=$bgcolor3;
	else $color=$bgcolor1;
	
		echo "<TR BGCOLOR=\"$color\">";
		echo "<TD ALIGN=\"CENTER\" WIDTH=\"30%\" ><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewforum&amp;forum=$row[forum_id]\">". stripslashes($row[forum_name]) . "</a></TD>";
		echo "<TD ALIGN=\"CENTER\" WIDTH=\"30%\" ><a href=\"modules.php?op=modload&amp;name=Forum&amp;file=viewtopic&amp;topic=$row[topic_id]&amp;forum=$row[forum_id]\">". stripslashes($row[topic_title]) . "</a></TD>";
		echo "<TD ALIGN=\"CENTER\" WIDTH=\"25%\" ><a href=\"user.php?op=userinfo&uname=$row[uname]\">$row[uname]</a></TD>";
		echo "<TD ALIGN=\"CENTER\" WIDTH=\"15%\" ><FONT SIZE=1>$row[post_time]<FONT SIZE=1></TD>";
		echo "</TR>";
	$count++;
	}while($row=@mysql_fetch_array($result));
?>	

</TABLE>
</TR>
</TR>
</TABLE>
<?php
include ("footer.php");
}

# End AddOn Modules

switch($func) {

    default:
    SearchBB($addterms,$sortby,$adv,$term,$forum,$username,$sortby,$from);
    break;
}

?>