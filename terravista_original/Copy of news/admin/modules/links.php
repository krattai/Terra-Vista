<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* Based on Journey Links Hack                                          */
/* Copyright (c) 2000 by James Knickelbein                              */
/* Journey Milwaukee (http://www.journeymilwaukee.com)                  */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/

if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$hlpfile = "manual/weblinks.html";
$result = mysql_query("select radminlink, radminsuper from $prefix"._authors." where aid='$aid'");
list($radminlink, $radminsuper) = mysql_fetch_row($result);
if (($radminlink==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Links Modified Web Links                              */
/*********************************************************/

function links() {
    global $hlpfile, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><a href=\"links.php\"><img src=\"images/links/web.gif\" border=\"0\" alt=\"\"></a><br><br>";
    $result=mysql_query("select * from $prefix"._links_links."");
    $numrows = mysql_num_rows($result);
    echo "<font size=\"2\">"._THEREARE." <b>$numrows</b> "._LINKSINDB."</font></center>";
    CloseTable();
    echo "<br>";
    
/* Temporarily 'homeless' links functions (to be revised in admin.php breakup) */

    $result = mysql_query("select * from $prefix"._links_modrequest." where brokenlink=1");
    $totalbrokenlinks = mysql_num_rows($result);
    $result2 = mysql_query("select * from $prefix"._links_modrequest." where brokenlink=0");
    $totalmodrequests = mysql_num_rows($result2);

/* List Links waiting for validation */

    $result = mysql_query("select lid, cid, sid, title, url, description, name, email, submitter from $prefix"._links_newlink." order by lid");
    $numrows = mysql_num_rows($result);
    if ($numrows>0) {
	OpenTable();
	echo "<center><font size=\"3\"><b>"._LINKSWAITINGVAL."</b></font></center><br><br>";
	while(list($lid, $cid, $sid, $title, $url, $description, $name, $email, $submitter) = mysql_fetch_row($result)) {
	    if ($submitter == "") {
		$submitter = _NONE;
	    }
    	    echo "<form action=\"admin.php\" method=\"post\">"
		."<b>"._LINKID.": $lid</b><br><br>"
		.""._SUBMITTER.":  $submitter<br>"
		.""._PAGETITLE.": <input type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\"><br>"
		.""._PAGEURL.": <input type=\"text\" name=\"url\" value=\"$url\" size=\"50\" maxlength=\"100\">&nbsp;[ <a target=\"_blank\" href=\"$url\">"._VISIT."</a> ]<br>"
		.""._DESCRIPTION.": <br><textarea name=\"description\" cols=\"60\" rows=\"10\">$description</textarea><br>"
		.""._NAME.": <input type=\"text\" name=\"name\" size=\"20\" maxlength=\"100\" value=\"$name\">&nbsp;&nbsp;"
		.""._EMAIL.": <input type=\"text\" name=\"email\" size=\"20\" maxlength=\"100\" value=\"$email\"><br>";
	    $result2=mysql_query("select cid, title from $prefix"._links_categories." order by title");
	    echo "<input type=\"hidden\" name=\"new\" value=\"1\">";
	    echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">";
	    echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">";
	    echo ""._CATEGORY.": <select name=\"cat\">";
	    while(list($ccid, $ctitle) = mysql_fetch_row($result2)) {
		$sel = "";
		if ($cid==$ccid AND $sid==0) {
		    $sel = "selected";
		}
		echo "<option value=\"$ccid\" $sel>$ctitle</option>";
		$result3=mysql_query("select sid, title from $prefix"._links_subcategories." where cid=$ccid order by title");
		while(list($ssid, $stitle) = mysql_fetch_row($result3)) {
    	    	    $sel = "";
		    if ($sid == $ssid) {
			$sel = "selected";
		    }
		    echo "<option value=\"$ccid-$ssid\" $sel>$ctitle / $stitle</option>";
		}
	    
	    }
	    echo "<input type=\"hidden\" name=\"submitter\" value=\"$submitter\">";
	    echo "</select><input type=\"hidden\" name=\"op\" value=\"LinksAddLink\"><input type=\"submit\" value="._ADD."> [ <a href=\"admin.php?op=LinksDelNew&amp;lid=$lid\">"._DELETE."</a> ]</form><br><hr noshade><br>";
	}
	CloseTable();
	echo "<br>";
    } else {
    }

/* Add a New Main Category */

    OpenTable();
    echo "<center><font size=\"2\">[ <a href=\"admin.php?op=LinksCleanVotes\">"._CLEANLINKSDB."</a> | "
	."<a href=\"admin.php?op=LinksListBrokenLinks\">"._BROKENLINKSREP." ($totalbrokenlinks)</a> | "
	."<a href=\"admin.php?op=LinksListModRequests\">"._LINKMODREQUEST." ($totalmodrequests)</a> | "
	."<a href=\"admin.php?op=LinksLinkCheck\">"._VALIDATELINKS."</a> ]</font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<form method=\"post\" action=\"admin.php\">"
	."<font size=\"3\"><b>"._ADDMAINCATEGORY."</b><br><br>"
	.""._NAME.": <input type=\"text\" name=\"title\" size=\"30\" maxlength=\"100\"><br>"
	.""._DESCRIPTION.":<br><textarea name=\"cdescription\" cols=\"60\" rows=\"10\"></textarea><br>"
	."<input type=\"hidden\" name=\"op\" value=\"LinksAddCat\">"
	."<input type=\"submit\" value=\""._ADD."\"><br>"
	."</form>";
    CloseTable();
    echo "<br>";

// Add a New Sub-Category

    $result = mysql_query("select * from $prefix"._links_categories."");
    $numrows = mysql_num_rows($result);
    if ($numrows>0) {
	OpenTable();
	echo "<form method=\"post\" action=\"admin.php\">"
	    ."<font size=\"3\"><b>"._ADDSUBCATEGORY."</b></font><br><br>"
	    .""._NAME.": <input type=\"text\" name=\"title\" size=\"30\" maxlength=\"100\">&nbsp;"._IN."&nbsp;";
	$result=mysql_query("select cid, title from $prefix"._links_categories." order by title");
	echo "<select name=\"cid\">";
	while(list($ccid, $ctitle) = mysql_fetch_row($result)) {
	    echo "<option value=\"$ccid\">$ctitle</option>";
	}
	echo "</select>"
	    ."<input type=\"hidden\" name=\"op\" value=\"LinksAddSubCat\">"
	    ."<input type=\"submit\" value=\""._ADD."\"><br>"
	    ."</form>";
	CloseTable();
	echo "<br>";
    } else {
    }

// Add a New Link to Database

    $result = mysql_query("select cid, title from $prefix"._links_categories."");
    $numrows = mysql_num_rows($result);
    if ($numrows>0) {
	OpenTable();
	echo "<form method=\"post\" action=\"admin.php\">"
	    ."<font size=\"3\"><b>"._ADDNEWLINK."</b><br><br>"
	    .""._PAGETITLE.": <input type=\"text\" name=\"title\" size=\"50\" maxlength=\"100\"><br>"
	    .""._PAGEURL.": <input type=\"text\" name=\"url\" size=\"50\" maxlength=\"100\" value=\"http://\"><br>";
	$result=mysql_query("select cid, title from $prefix"._links_categories." order by title");
	echo ""._CATEGORY.": <select name=\"cat\">";
	while(list($cid, $title) = mysql_fetch_row($result)) {
	    echo "<option value=\"$cid\">$title</option>";
	    $result2=mysql_query("select sid, title from $prefix"._links_subcategories." where cid=$cid order by title");
	    while(list($sid, $stitle) = mysql_fetch_row($result2)) {
    		echo "<option value=\"$cid-$sid\">$title / $stitle</option>";
	    }
	}
	echo "</select><br><br><br>"
	    .""._DESCRIPTION255."<br><textarea name=\"description\" cols=\"60\" rows=\"5\"></textarea><br><br><br>"
	    .""._NAME.": <input type=\"text\" name=\"name\" size=\"30\" maxlength=\"60\"><br>"
	    .""._EMAIL.": <input type=\"text\" name=\"email\" size=\"30\" maxlength=\"60\"><br><br>"
	    ."<input type=\"hidden\" name=\"op\" value=\"LinksAddLink\">"
    	    ."<input type=\"hidden\" name=\"new\" value=\"0\">"
	    ."<input type=\"hidden\" name=\"lid\" value=\"0\">"
	    ."<center><input type=\"submit\" value=\""._ADDURL."\"><br>"
	    ."</form>";
	CloseTable();
	echo "<br>";
    } else {
    }

// Modify Category

    $result = mysql_query("select * from $prefix"._links_categories."");
    $numrows = mysql_num_rows($result);
    if ($numrows>0) {
	OpenTable();
	echo "<form method=\"post\" action=\"admin.php\">"
	    ."<font size=\"3\"><b>"._MODCATEGORY."</b></font><br><br>";
	$result=mysql_query("select cid, title from $prefix"._links_categories." order by title");
	echo ""._CATEGORY.": <select name=\"cat\">";
	while(list($cid, $title) = mysql_fetch_row($result)) {
	    echo "<option value=\"$cid\">$title</option>";
	    $result2=mysql_query("select sid, title from $prefix"._links_subcategories." where cid=$cid order by title");
	    while(list($sid, $stitle) = mysql_fetch_row($result2)) {
    		echo "<option value=\"$cid-$sid\">$title / $stitle</option>";
	    }
	}
	echo "</select>"
	    ."<input type=\"hidden\" name=\"op\" value=\"LinksModCat\">"
	    ."<input type=\"submit\" value=\""._MODIFY."\">"
	    ."</form>";
	CloseTable();
	echo "<br>";
    } else {
    }

// Modify Links

    $result = mysql_query("select * from $prefix"._links_links."");
    $numrows = mysql_num_rows($result);
    if ($numrows>0) {
    OpenTable();
    echo "<form method=\"post\" action=\"admin.php\">"
	."<font size=\"3\"><b>"._MODLINK."</b><br><br>"
	.""._LINKID.": <input type=\"text\" name=\"lid\" size=\"12\" maxlength=\"11\">&nbsp;&nbsp;"
	."<input type=\"hidden\" name=\"op\" value=\"LinksModLink\">"
	."<input type=\"submit\" value=\""._MODIFY."\">"
	."</form>";
    CloseTable();
    echo "<br>";
    } else {
    }
    include ("footer.php");
}

function LinksModLink($lid) {
    global $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    global $anonymous;
    $result = mysql_query("select cid, sid, title, url, description, name, email, hits from $prefix"._links_links." where lid=$lid");
    OpenTable();
    echo "<center><font size=\"4\"><b>"._WEBLINKSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._MODLINK."</b></font></center><br><br>";
    while(list($cid, $sid, $title, $url, $description, $name, $email, $hits) = mysql_fetch_row($result)) {
    	$title = stripslashes($title); $description = stripslashes($description);
    	echo "<form action=admin.php method=post>"
	    .""._LINKID.": <b>$lid</b><br>"
	    .""._PAGETITLE.": <input type=\"text\" name=\"title\" value=\"$title\" size=\"50\" maxlength=\"100\"><br>"
	    .""._PAGEURL.": <input type=\"text\" name=\"url\" value=\"$url\" size=\"50\" maxlength=\"100\">&nbsp;[ <a href=\"$url\">Visit</a> ]<br>"
	    .""._DESCRIPTION.":<br><textarea name=\"description\" cols=\"60\" rows=\"10\">$description</textarea><br>"
	    .""._NAME.": <input type=\"text\" name=\"name\" size=\"50\" maxlength=\"100\" value=\"$name\"><br>"
	    .""._EMAIL.": <input type=\"text\" name=\"email\" size=\"50\" maxlength=\"100\" value=\"$email\"><br>"
	    .""._HITS.": <input type=\"text\" name=\"hits\" value=\"$hits\" size=\"12\" maxlength=\"11\"><br>";
	$result2=mysql_query("select cid, title from $prefix"._links_categories." order by title");
	echo "<input type=\"hidden\" name=\"lid\" value=\"$lid\">"
	    .""._CATEGORY.": <select name=\"cat\">";
	while(list($ccid, $ctitle) = mysql_fetch_row($result2)) {
	    $sel = "";
	    if ($cid==$ccid AND $sid==0) {
		$sel = "selected";
	    }
	    echo "<option value=\"$ccid\" $sel>$ctitle</option>";
	    $result3=mysql_query("select sid, title from $prefix"._links_subcategories." where cid=$ccid order by title");
	    while(list($ssid, $stitle) = mysql_fetch_row($result3)) {
    	        $sel = "";
		if ($sid==$ssid) {
		    $sel = "selected";
		}
		echo "<option value=\"$ccid-$ssid\" $sel>$ctitle / $stitle</option>";
	    }
	}
    
    echo "</select>"
	."<input type=\"hidden\" name=\"op\" value=\"LinksModLinkS\">"
	."<input type=\"submit\" value=\""._MODIFY."\"> [ <a href=\"admin.php?op=LinksDelLink&amp;lid=$lid\">"._DELETE."</a> ]</form><br>";
    CloseTable();
    echo "<br>";    
    /* Modify or Add Editorial */
        
        $resulted2 = mysql_query("select adminid, editorialtimestamp, editorialtext, editorialtitle from $prefix"._links_editorials." where linkid=$lid");
        $recordexist = Mysql_num_rows($resulted2);
	OpenTable();
    /* if returns 'bad query' status 0 (add editorial) */
    	if ($recordexist == 0) {
    	    echo "<center><font size=\"3\"><b>"._ADDEDITORIAL."</b></font></center><br><br>"
    		."<form action=\"admin.php\" method=\"post\">"
    		."<input type=\"hidden\" name=\"linkid\" value=\"$lid\">"
    		.""._EDITORIALTITLE.":<br><input type=\"text\" name=\"editorialtitle\" value=\"$editorialtitle\" size=\"50\" maxlength=\"100\"><br>"
    		.""._EDITORIALTEXT.":<br><textarea name=\"editorialtext\" cols=\"60\" rows=\"10\">$editorialtext</textarea><br>"
        	."</select><input type=\"hidden\" name=\"op\" value=\"LinksAddEditorial\"><input type=\"submit\" value=\"Add\">";
        } else {
    /* if returns 'cool' then status 1 (modify editorial) */
        	while(list($adminid, $editorialtimestamp, $editorialtext, $editorialtitle) = mysql_fetch_row($resulted2)) {
        	$editorialtitle = stripslashes($editorialtitle); $editorialtext = stripslashes($editorialtext);
    		ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $editorialtimestamp, $editorialtime);
		$editorialtime = strftime("%F",mktime($editorialtime[4],$editorialtime[5],$editorialtime[6],$editorialtime[2],$editorialtime[3],$editorialtime[1]));
		$date_array = explode("-", $editorialtime); 
		$timestamp = mktime(0, 0, 0, $date_array["1"], $date_array["2"], $date_array["0"]); 
       		$formatted_date = date("F j, Y", $timestamp);         	
        	echo "<center><font size=\"3\"><b>Modify Editorial</b></font></center><br><br>"
        	    ."<form action=\"admin.php\" method=\"post\">"
        	    .""._AUTHOR.": $adminid<br>"
        	    .""._DATEWRITTEN.": $formatted_date<br><br>"
        	    ."<input type=\"hidden\" name=\"linkid\" value=\"$lid\">"
        	    .""._EDITORIALTITLE.":<br><input type=\"text\" name=\"editorialtitle\" value=\"$editorialtitle\" size=\"50\" maxlength=\"100\"><br>"
        	    .""._EDITORIALTEXT.":<br><textarea name=\"editorialtext\" cols=\"60\" rows=\"10\">$editorialtext</textarea><br>"
            	    ."</select><input type=\"hidden\" name=\"op\" value=\"LinksModEditorial\"><input type=\"submit\" value=\""._MODIFY."\"> [ <a href=\"admin.php?op=LinksDelEditorial&amp;linkid=$lid\">"._DELETE."</a> ]";
                }
    	} 
    CloseTable();
    echo "<br>";
    OpenTable();
    /* Show Comments */
    $result5=mysql_query("SELECT ratingdbid, ratinguser, ratingcomments, ratingtimestamp FROM $prefix"._links_votedata." WHERE ratinglid = $lid AND ratingcomments != '' ORDER BY ratingtimestamp DESC");
    $totalcomments = Mysql_num_rows($result5);  
    echo "<table valign=top width=100%>";
    echo "<tr><td colspan=7><b>Link Comments (total comments: $totalcomments)</b><br><br></td></tr>";    
    echo "<tr><td width=20 colspan=1><b>User  </b></td><td colspan=5><b>Comment  </b></td><td><b><center>Delete</center></b></td><br></tr>";
    if ($totalcomments == 0) echo "<tr><td colspan=7><center><font color=cccccc>No Comments<br></font></center></td></tr>";
    $x=0;
    $colorswitch="dddddd";
    while(list($ratingdbid, $ratinguser, $ratingcomments, $ratingtimestamp)=mysql_fetch_row($result5)) {
    	$ratingcomments = stripslashes($ratingcomments);
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
    	$ratingtime = strftime("%F",mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]));
    	$date_array = explode("-", $ratingtime); 
    	$timestamp = mktime(0, 0, 0, $date_array["1"], $date_array["2"], $date_array["0"]); 
            $formatted_date = date("F j, Y", $timestamp);
            echo "<tr><td valign=top bgcolor=$colorswitch>$ratinguser</td><td valign=top colspan=5 bgcolor=$colorswitch>$ratingcomments</td><td bgcolor=$colorswitch><center><b><a href=admin.php?op=LinksDelComment&lid=$lid&rid=$ratingdbid>X</a></b></center></td><br></tr>";                       
    	$x++;
    	if ($colorswitch=="dddddd") $colorswitch="ffffff";
    		else $colorswitch="dddddd";    	
        }    

    	    
    // Show Registered Users Votes
    $result5=mysql_query("SELECT ratingdbid, ratinguser, rating, ratinghostname, ratingtimestamp FROM $prefix"._links_votedata." WHERE ratinglid = $lid AND ratinguser != 'outside' AND ratinguser != '$anonymous' ORDER BY ratingtimestamp DESC");
    $totalvotes = Mysql_num_rows($result5);  
    echo "<tr><td colspan=7><br><br><b>Registered User Votes (total votes: $totalvotes)</b><br><br></td></tr>";
    echo "<tr><td><b>User  </b></td><td><b>IP Address  </b></td><td><b>Rating  </b></td><td><b>User AVG Rating  </b></td><td><b>Total Ratings  </b></td><td><b>Date  </b></td></font></b><td><b><center>Delete</center></b></td><br></tr>";
    if ($totalvotes == 0) echo "<tr><td colspan=7><center><font color=cccccc>No Registered User Votes<br></font></center></td></tr>";
    $x=0;
    $colorswitch="dddddd";
    while(list($ratingdbid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp)=mysql_fetch_row($result5)) {
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
    	$ratingtime = strftime("%F",mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]));
    	$date_array = explode("-", $ratingtime); 
    	$timestamp = mktime(0, 0, 0, $date_array["1"], $date_array["2"], $date_array["0"]); 
            $formatted_date = date("F j, Y", $timestamp); 
    	
    	//Individual user information
    	$result2=mysql_query("SELECT rating FROM $prefix"._links_votedata." WHERE ratinguser = '$ratinguser'");
            $usertotalcomments = Mysql_num_rows($result2);
            $useravgrating = 0;
            while(list($rating2)=mysql_fetch_row($result2))	$useravgrating = $useravgrating + $rating2;
            $useravgrating = $useravgrating / $usertotalcomments;
            $useravgrating = number_format($useravgrating, 1);
            echo "<tr><td bgcolor=$colorswitch>$ratinguser</td><td bgcolor=$colorswitch>$ratinghostname</td><td bgcolor=$colorswitch>$rating</td><td bgcolor=$colorswitch>$useravgrating</td><td bgcolor=$colorswitch>$usertotalcomments</td><td bgcolor=$colorswitch>$formatted_date  </font></b></td><td bgcolor=$colorswitch><center><b><a href=admin.php?op=LinksDelVote&lid=$lid&rid=$ratingdbid>X</a></b></center></td></tr><br>";
    	$x++;
    	if ($colorswitch=="dddddd") $colorswitch="ffffff";
    		else $colorswitch="dddddd";    	
        }    
        
    // Show Unregistered Users Votes
    $result5=mysql_query("SELECT ratingdbid, rating, ratinghostname, ratingtimestamp FROM $prefix"._links_votedata." WHERE ratinglid = $lid AND ratinguser = '$anonymous' ORDER BY ratingtimestamp DESC");
    $totalvotes = Mysql_num_rows($result5);  
    echo "<tr><td colspan=7><b><br><br>Unregistered User Votes (total votes: $totalvotes)</b><br><br></td></tr>";
    echo "<tr><td colspan=2><b>IP Address  </b></td><td colspan=3><b>Rating  </b></td><td><b>Date  </b></font></td><td><b><center>Delete</center></b></td><br></tr>";
    if ($totalvotes == 0) echo "<tr><td colspan=7><center><font color=cccccc>No Unregistered User Votes<br></font></center></td></tr>";
    $x=0;
    $colorswitch="dddddd";
    while(list($ratingdbid, $rating, $ratinghostname, $ratingtimestamp)=mysql_fetch_row($result5)) {
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
    	$ratingtime = strftime("%F",mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]));
    	$date_array = explode("-", $ratingtime); 
    	$timestamp = mktime(0, 0, 0, $date_array["1"], $date_array["2"], $date_array["0"]); 
        $formatted_date = date("F j, Y", $timestamp); 
        echo "<td colspan=2 bgcolor=$colorswitch>$ratinghostname</td><td colspan=3 bgcolor=$colorswitch>$rating</td><td bgcolor=$colorswitch>$formatted_date  </font></b></td><td bgcolor=$colorswitch><center><b><a href=admin.php?op=LinksDelVote&lid=$lid&rid=$ratingdbid>X</a></b></center></td></tr><br>";           
    	$x++;
    	if ($colorswitch=="dddddd") $colorswitch="ffffff";
    		else $colorswitch="dddddd";    	
        }  
        
    // Show Outside Users Votes
    $result5=mysql_query("SELECT ratingdbid, rating, ratinghostname, ratingtimestamp FROM $prefix"._links_votedata." WHERE ratinglid = $lid AND ratinguser = 'outside' ORDER BY ratingtimestamp DESC");
    $totalvotes = Mysql_num_rows($result5);  
    echo "<tr><td colspan=7><b><br><br>Outside User Votes (total votes: $totalvotes)</b><br><br></td></tr>";
    echo "<tr><td colspan=2><b>IP Address  </b></td><td colspan=3><b>Rating  </b></td><td><b>Date  </b></td></font></b><td><b><center>Delete</center></b></td><br></tr>";
    if ($totalvotes == 0) echo "<tr><td colspan=7><center><font color=cccccc>No Votes from Outside $sitename<br></font></center></td></tr>";
    $x=0;
    $colorswitch="dddddd"; 
    while(list($ratingdbid, $rating, $ratinghostname, $ratingtimestamp)=mysql_fetch_row($result5)) {
        ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $ratingtimestamp, $ratingtime);
    	$ratingtime = strftime("%F",mktime($ratingtime[4],$ratingtime[5],$ratingtime[6],$ratingtime[2],$ratingtime[3],$ratingtime[1]));
    	$date_array = explode("-", $ratingtime); 
    	$timestamp = mktime(0, 0, 0, $date_array["1"], $date_array["2"], $date_array["0"]); 
        $formatted_date = date("F j, Y", $timestamp); 
        echo "<tr><td colspan=2 bgcolor=$colorswitch>$ratinghostname</td><td colspan=3 bgcolor=$colorswitch>$rating</td><td bgcolor=$colorswitch>$formatted_date  </font></b></td><td bgcolor=$colorswitch><center><b><a href=admin.php?op=LinksDelVote&lid=$lid&rid=$ratingdbid>X</a></b></center></td></tr><br>";           
    	$x++;
    	if ($colorswitch=="dddddd") $colorswitch="ffffff";
    		else $colorswitch="dddddd";
        }            

    echo "<tr><td colspan=6><br></td></tr>";	    
    echo "</table>";
    
    }
    echo "</form>";
    CloseTable();
    echo "<br>";
    include ("footer.php");
}

function LinksDelComment($lid, $rid) {
    global $prefix;
    mysql_query("UPDATE $prefix"._links_votedata." SET ratingcomments='' WHERE ratingdbid = $rid");
    mysql_query("UPDATE $prefix"._links_links." SET totalcomments = (totalcomments - 1) WHERE lid = $lid");
    Header("Location: admin.php?op=LinksModLink&lid=$lid");
    
}

function LinksDelVote($lid, $rid) {
    global $prefix;
    mysql_query("delete from $prefix"._links_votedata." where ratingdbid=$rid");
    $voteresult = mysql_query("select rating, ratinguser, ratingcomments FROM $prefix"._links_votedata." WHERE ratinglid = $lid");
    $totalvotesDB = mysql_num_rows($voteresult);
    include ("voteinclude.php");
    mysql_query("UPDATE $prefix"._links_links." SET linkratingsummary=$finalrating,totalvotes=$totalvotesDB,totalcomments=$truecomments WHERE lid = $lid");
    Header("Location: admin.php?op=LinksModLink&lid=$lid");
}

function LinksListBrokenLinks() {
    global $bgcolor1, $bgcolor2, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._WEBLINKSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $result = mysql_query("select requestid, lid, modifysubmitter from $prefix"._links_modrequest." where brokenlink=1 order by requestid");
    $totalbrokenlinks = mysql_num_rows($result);
    echo "<center><font size=\"3\"><b>"._USERREPBROKEN." ($totalbrokenlinks)</b></font></center><br><br><center>"
	.""._IGNOREINFO."<br>"
	.""._DELETEINFO."</center><br><br><br>"
	."<table align=\"center\" width=\"450\">";
    if ($totalbrokenlinks==0) {
	echo "<center><font size=\"3\">"._NOREPORTEDBROKEN."</font></center><br><br><br>";
    } else {
        $colorswitch = $bgcolor2;
        echo "<tr>"
            ."<td><b>"._LINK."</b></td>"
            ."<td><b>"._SUBMITTER."</b></td>"
            ."<td><b>"._LINKOWNER."</b></td>"
            ."<td><b>"._IGNORE."</b></td>"
            ."<td><b>"._DELETE."</b></td>"
    	    ."</tr>";
        while(list($requestid, $lid, $modifysubmitter)=mysql_fetch_row($result)) {
	    $result2 = mysql_query("select title, url, submitter from $prefix"._links_links." where lid=$lid");
	    if ($modifysubmitter != '$anonymous') {
		$result3 = mysql_query("select email from $prefix"._users." where uname='$modifysubmitter'");
		list($email)=mysql_fetch_row($result3);
	    }
    	    list($title, $url, $owner)=mysql_fetch_row($result2);
    	    $result4 = mysql_query("select email from $prefix"._users." where uname='$owner'");
    	    list($owneremail)=mysql_fetch_row($result4);
    	    echo "<tr>"
    		."<td bgcolor=\"$colorswitch\"><a href=\"$url\">$title</a>"
    		."</td>";
    	    if ($email=='') {
		echo "<td bgcolor=\"$colorswitch\">$modifysubmitter";
	    } else {
		echo "<td bgcolor=\"$colorswitch\"><a href=\"mailto:$email\">$modifysubmitter</a>";
	    }
    	    echo "</td>";
    	    if ($owneremail=='') {
		echo "<td bgcolor=\"$colorswitch\">$owner";
	    } else {
		echo "<td bgcolor=\"$colorswitch\"><a href=\"mailto:$owneremail\">$owner</a>";
	    }
    	    echo "</td>"
    		."<td bgcolor=\"$colorswitch\"><center><a href=\"admin.php?op=LinksIgnoreBrokenLinks&amp;lid=$lid\">X</a></center>"
    		."</td>"
    		."<td bgcolor=\"$colorswitch\"><center><a href=\"admin.php?op=LinksDelBrokenLinks&amp;lid=$lid\">X</a></center>"
    		."</td>"
    		."</tr>";
    	    if ($colorswitch == $bgcolor2) {
		$colorswitch = $bgcolor1;
       	    } else {
		$colorswitch = $bgcolor2;
	    }
    	}
    }
    echo "</table>";
    CloseTable();
    include ("footer.php");
}

function LinksDelBrokenLinks($lid) {
    global $prefix;
    mysql_query("delete from $prefix"._links_modrequest." where lid=$lid");
    mysql_query("delete from $prefix"._links_links." where lid=$lid");
    Header("Location: admin.php?op=LinksListBrokenLinks");
}

function LinksIgnoreBrokenLinks($lid) {
    global $prefix;
    mysql_query("delete from $prefix"._links_modrequest." where lid=$lid and brokenlink=1");
    Header("Location: admin.php?op=LinksListBrokenLinks");
}

function LinksListModRequests() {
    global $bgcolor2, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._WEBLINKSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $result = mysql_query("select requestid, lid, cid, sid, title, url, description, modifysubmitter from $prefix"._links_modrequest." where brokenlink=0 order by requestid");
    $totalmodrequests = mysql_num_rows($result);	
    echo "<center><font size=\"3\"><b>"._USERMODREQUEST." ($totalmodrequests)</b></font></center><br><br><br>";
    echo "<table width=\"95%\"><tr><td>";
    while(list($requestid, $lid, $cid, $sid, $title, $url, $description, $modifysubmitter)=mysql_fetch_row($result)) {
	$result2 = mysql_query("select cid, sid, title, url, description, submitter from $prefix"._links_links." where lid=$lid");
	list($origcid, $origsid, $origtitle, $origurl, $origdescription, $owner)=mysql_fetch_row($result2);
	$result3 = mysql_query("select title from $prefix"._links_categories." where cid=$cid");
	$result4 = mysql_query("select title from $prefix"._links_subcategories." where cid=$cid and sid=$sid");
	$result5 = mysql_query("select title from $prefix"._links_categories." where cid=$origcid");
	$result6 = mysql_query("select title from $prefix"._links_subcategories." where cid=$origcid and sid=$origsid");
	$result7 = mysql_query("select email from $prefix"._users." where uname='$modifysubmitter'");
	$result8 = mysql_query("select email from $prefix"._users." where uname='$owner'");
	list($cidtitle)=mysql_fetch_row($result3);
	list($sidtitle)=mysql_fetch_row($result4);
	list($origcidtitle)=mysql_fetch_row($result5);
	list($origsidtitle)=mysql_fetch_row($result6);
	list($modifysubmitteremail)=mysql_fetch_row($result7);
	list($owneremail)=mysql_fetch_row($result8);    
    	$title = stripslashes($title);
    	$description = stripslashes($description);
    	if ($owner=="") {
	    $owner="administration";
	}
    	if ($origsidtitle=="") {
	    $origsidtitle= "-----";
	}
    	if ($sidtitle=="") {
	    $sidtitle= "-----";
	}
    	echo "<table border=\"1\" bordercolor=\"black\" cellpadding=\"5\" cellspacing=\"0\" align=\"center\" width=\"450\">"
    	    ."<tr>"
    	    ."<td>"
    	    ."<table width=\"100%\" bgcolor=\"$bgcolor2\">"
    	    ."<tr>"
    	    ."<td valign=\"top\" width=\"45%\"><b>"._ORIGINAL."</b></td>"
    	    ."<td rowspan=\"5\" valign=\"top\" align=\"left\"><font size=\"1\"><br>"._DESCRIPTION.":<br>$origdescription</font></td>"
    	    ."</tr>"
    	    ."<tr><td valign=\"top\" width=\"45%\"><font size=\"1\">"._TITLE.": $origtitle</td></tr>"
    	    ."<tr><td valign=\"top\" width=\"45%\"><font size=\"1\">"._URL.": <a href=\"$origurl\">$origurl</a></td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font size=\"1\">"._CATEGORY.": $origcidtitle</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font size=\"1\">"._SUBCATEGORY.": $origsidtitle</td></tr>"
    	    ."</table>"
    	    ."</td>"
    	    ."</tr>"
    	    ."<tr>"
    	    ."<td>"
    	    ."<table width=\"100%\">"
    	    ."<tr>"
    	    ."<td valign=\"top\" width=\"45%\"><b>"._PROPOSED."</b></td>"
    	    ."<td rowspan=\"5\" valign=\"top\" align=\"left\"><font size=\"1\"><br>"._DESCRIPTION.":<br>$description</font></td>"
    	    ."</tr>"
    	    ."<tr><td valign=\"top\" width=\"45%\"><font size=\"1\">"._TITLE.": $title</td></tr>"
    	    ."<tr><td valign=\"top\" width=\"45%\"><font size=\"1\">"._URL.": <a href=\"$url\">$url</a></td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font size=\"1\">"._CATEGORY.": $cidtitle</td></tr>"
	    ."<tr><td valign=\"top\" width=\"45%\"><font size=\"1\">"._SUBCATEGORY.": $sidtitle</td></tr>"
    	    ."</table>"
    	    ."</td>"
    	    ."</tr>"
    	    ."</table>"
    	    ."<table align=\"center\" width=\"450\">"
    	    ."<tr>";
    	if ($modifysubmitteremail=="") {
	    echo "<td align=\"left\"><font size=\"1\">"._SUBMITTER.":  $modifysubmitter</font></td>";
	} else {
	    echo "<td align=\"left\"><font size=\"1\">"._SUBMITTER.":  <a href=\"mailto:$modifysubmitteremail\">$modifysubmitter</a></font></td>";
	}
    	if ($owneremail=="") {
	    echo "<td align=\"center\"><font size=\"1\">"._OWNER.":  $owner</font></td>";
	} else {
	    echo "<td align=\"center\"><font size=\"1\">"._OWNER.": <a href=\"mailto:$owneremail\">$owner</a></font></td>";
	}
    	echo "<td align=\"right\"><font size=\"1\">( <a href=\"admin.php?op=LinksChangeModRequests&amp;requestid=$requestid\">"._ACCEPT."</a> / <a href=\"admin.php?op=LinksChangeIgnoreRequests&amp;requestid=$requestid\">"._IGNORE."</a> )</font></td></tr></table>";
    }
    if ($totalmodrequests == 0) {
	echo "<center>"._NOMODREQUESTS."</center><br><br>";
    }
    echo "</td></tr></table>";
    CloseTable();
    include ("footer.php");
}

function LinksChangeModRequests($requestid) {
    global $prefix;
    $result = mysql_query("select requestid, lid, cid, sid, title, url, description from $prefix"._links_modrequest." where requestid=$requestid");
    while(list($requestid, $lid, $cid, $sid, $title, $url, $description)=mysql_fetch_row($result)) {
	$title = stripslashes($title);
    	$description = stripslashes($description);
    	mysql_query("UPDATE $prefix"._links_links." SET cid=$cid, sid=$sid, title='$title', url='$url', description='$description' WHERE lid = $lid");	
    }
    mysql_query("delete from $prefix"._links_modrequest." where requestid=$requestid");
    Header("Location: admin.php?op=LinksListModRequests");
}

function LinksChangeIgnoreRequests($requestid) {
    global $prefix;
    mysql_query("delete from $prefix"._links_modrequest." where requestid=$requestid");
    Header("Location: admin.php?op=LinksListModRequests");
}

function LinksCleanVotes() {
    global $prefix;
    $totalvoteresult = mysql_query("select distinct ratinglid FROM $prefix"._links_votedata."");	
    while(list($lid)=mysql_fetch_row($totalvoteresult)) {
	$voteresult = mysql_query("select rating, ratinguser, ratingcomments FROM $prefix"._links_votedata." WHERE ratinglid = $lid");
	$totalvotesDB = mysql_num_rows($voteresult);	
	include ("voteinclude.php");
        mysql_query("UPDATE $prefix"._links_links." SET linkratingsummary=$finalrating,totalvotes=$totalvotesDB,totalcomments=$truecomments WHERE lid = $lid");            
    }
    Header("Location: admin.php?op=links");
}

function LinksModLinkS($lid, $title, $url, $description, $name, $email, $hits, $cat) {
    global $prefix;
    $cat = explode("-", $cat);
    if ($cat[1]=="") {
        $cat[1] = 0;
    }
    $title = stripslashes(FixQuotes($title));
    $url = stripslashes(FixQuotes($url));
    $description = stripslashes(FixQuotes($description));
    $name = stripslashes(FixQuotes($name));
    $email = stripslashes(FixQuotes($email));
    mysql_query("update $prefix"._links_links." set cid='$cat[0]', sid='$cat[1]', title='$title', url='$url', description='$description', name='$name', email='$email', hits='$hits' where lid=$lid");
    Header("Location: admin.php?op=links");
}

function LinksDelLink($lid) {
    global $prefix;
    mysql_query("delete from $prefix"._links_links." where lid=$lid");
    Header("Location: admin.php?op=links");
}

function LinksModCat($cat) {
    global $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._WEBLINKSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $cat = explode("-", $cat);
    if ($cat[1]=="") {
        $cat[1] = 0;
    }
    OpenTable();
    echo "<center><font size=\"3\"><b>"._MODCATEGORY."</b></font></center><br><br>";
    if ($cat[1]==0) {
	$result=mysql_query("select title, cdescription from $prefix"._links_categories." where cid=$cat[0]");
	list($title,$cdescription) = mysql_fetch_row($result);
	$cdescription = stripslashes($cdescription);
	echo "<form action=\"admin.php\" method=\"get\">"
	    .""._NAME.": <input type=\"text\" name=\"title\" value=\"$title\" size=\"51\" maxlength=\"50\"><br>"
	    .""._DESCRIPTION.":<br><textarea name=\"cdescription\" cols=\"60\" rows=\"10\">$cdescription</textarea><br>"
	    ."<input type=\"hidden\" name=\"sub\" value=\"0\">"
	    ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"LinksModCatS\">"
	    ."<table border=\"0\"><tr><td>"
	    ."<input type=\"submit\" value=\""._SAVECHANGES."\"></form></td><td>"
	    ."<form action=\"admin.php\" method=\"get\">"
	    ."<input type=\"hidden\" name=\"sub\" value=\"0\">"
	    ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"LinksDelCat\">"
	    ."<input type=\"submit\" value=\""._DELETE."\"></form></td></tr></table>";
    } else {
	$result=mysql_query("select title from $prefix"._links_categories." where cid=$cat[0]");
	list($ctitle) = mysql_fetch_row($result);
	$result2=mysql_query("select title from $prefix"._links_subcategories." where sid=$cat[1]");
	list($stitle) = mysql_fetch_row($result2);
	echo "<form action=\"admin.php\" method=\"get\">"
	    .""._CATEGORY.": $ctitle<br>"
	    .""._SUBCATEGORY.": <input type=\"text\" name=\"title\" value=\"$stitle\" size=\"51\" maxlength=\"50\"><br>"
	    ."<input type=\"hidden\" name=\"sub\" value=\"1\">"
	    ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\">"
	    ."<input type=\"hidden\" name=\"sid\" value=\"$cat[1]\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"LinksModCatS\">"
	    ."<table border=\"0\"><tr><td>"
	    ."<input type=\"submit\" value=\""._SAVECHANGES."\"></form></td><td>"
	    ."<form action=\"admin.php\" method=\"get\">"
	    ."<input type=\"hidden\" name=\"sub\" value=\"1\">"
	    ."<input type=\"hidden\" name=\"cid\" value=\"$cat[0]\">"
	    ."<input type=\"hidden\" name=\"sid\" value=\"$cat[1]\">"
	    ."<input type=\"hidden\" name=\"op\" value=\"LinksDelCat\">"
	    ."<input type=\"submit\" value=\""._DELETE."\"></form></td></tr></table>";
    }
    CloseTable();
    include("footer.php");
}

function LinksModCatS($cid, $sid, $sub, $title, $cdescription) {
    global $prefix;
    if ($sub==0) {
	mysql_query("update $prefix"._links_categories." set title='$title', cdescription='$cdescription' where cid=$cid");
    } else {
	mysql_query("update $prefix"._links_subcategories." set title='$title' where sid=$sid");
    }
    Header("Location: admin.php?op=links");
}

function LinksDelCat($cid, $sid, $sub, $ok=0) {
    global $prefix;
    if($ok==1) {
	if ($sub>0) {
    	    mysql_query("delete from $prefix"._links_subcategories." where sid=$sid");
	    mysql_query("delete from $prefix"._links_links." where sid=$sid");
	} else {
	    mysql_query("delete from $prefix"._links_categories." where cid=$cid");
	    mysql_query("delete from $prefix"._links_subcategories." where cid=$cid");
	    mysql_query("delete from $prefix"._links_links." where cid=$cid AND sid=0");
	}
	Header("Location: admin.php?op=links");    
    } else {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<br><center><font size=\"3\">";
	echo "<b>"._DELCATWARNING."</b><br><br>";
    }
	echo "[ <a href=\"admin.php?op=LinksDelCat&amp;cid=$cid&amp;sid=$sid&amp;sub=$sub&amp;ok=1\">"._YES."</a> | <a href=\"admin.php?op=links\">"._NO."</a> ]<br><br>";
	CloseTable();
	include("footer.php");	
}

function LinksDelNew($lid) {
    global $prefix;
    mysql_query("delete from $prefix"._links_newlink." where lid=$lid");
    Header("Location: admin.php?op=links");
}

function LinksAddCat($title, $cdescription) {
    global $prefix;
    $result = mysql_query("select cid from $prefix"._links_categories." where title='$title'");
    $numrows = mysql_num_rows($result);
    if ($numrows>0) {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<br><center><font size=\"3\">"
	    ."<b>"._ERRORTHECATEGORY." $title "._ALREADYEXIST."</b><br><br>"
	    .""._GOBACK."<br><br>";
	CloseTable();
	include("footer.php");
    } else {
	mysql_query("insert into $prefix"._links_categories." values (NULL, '$title', '$cdescription')");
	Header("Location: admin.php?op=links");
    }
}

function LinksAddSubCat($cid, $title) {
    global $prefix;
    $result = mysql_query("select cid from $prefix"._links_subcategories." where title='$title' AND cid='$cid'");
    $numrows = mysql_num_rows($result);
    if ($numrows>0) {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<br><center>";
	echo "<font size=\"3\">"
	    ."<b>"._ERRORTHESUBCATEGORY." $title "._ALREADYEXIST."</b><br><br>"
	    .""._GOBACK."<br><br>";
	include("footer.php");
    } else {
	mysql_query("insert into $prefix"._links_subcategories." values (NULL, '$cid', '$title')");
	Header("Location: admin.php?op=links");
    }
}

function LinksAddEditorial($linkid, $editorialtitle, $editorialtext) {
    global $aid, $prefix;
    $editorialtext = stripslashes(FixQuotes($editorialtext));
    mysql_query("insert into $prefix"._links_editorials." values ($linkid, '$aid', now(), '$editorialtext', '$editorialtitle')");
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><br>"
	."<font size=3>"
	.""._EDITORIALADDED."<br><br>"
	."[ <a href=\"admin.php?op=links\">"._WEBLINKSADMIN."</a> ]<br><br>";
    echo "$linkid  $adminid, $editorialtitle, $editorialtext";
    CloseTable();
    include("footer.php");
}

function LinksModEditorial($linkid, $editorialtitle, $editorialtext) {
    global $prefix;
    $editorialtext = stripslashes(FixQuotes($editorialtext));
    mysql_query("update $prefix"._links_editorials." set editorialtext='$editorialtext', editorialtitle='$editorialtitle' where linkid=$linkid");
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<br><center>"
	."<font size=\"3\">"
	.""._EDITORIALMODIFIED."<br><br>"
	."[ <a href=\"admin.php?op=links\">"._WEBLINKSADMIN."</a> ]<br><br>";
    CloseTable();
    include("footer.php");    
}

function LinksDelEditorial($linkid) {
    global $prefix;
    mysql_query("delete from $prefix"._links_editorials." where linkid=$linkid");
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<br><center>"
	."<font size=\"3\">"
	.""._EDITORIALREMOVED."<br><br>"
	."[ <a href=\"admin.php?op=links\">"._WEBLINKSADMIN."</a> ]<br><br>";
    CloseTable();
    include("footer.php");
}

function LinksLinkCheck() {
    global $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._WEBLINKSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._LINKVALIDATION."</b></font></center><br>"
	."<table width=\"100%\" align=\"center\"><tr><td colspan=\"2\" align=\"center\">"
	."<a href=\"admin.php?op=LinksValidate&amp;cid=0&amp;sid=0\">"._CHECKALLLINKS."</a><br><br></td></tr>"
	."<tr><td valign=\"top\"><center><b>"._CHECKCATEGORIES."</b><br>"._INCLUDESUBCATEGORIES."<br><br><font size=\"1\">";
    $result = mysql_query("select cid, title from $prefix"._links_categories." order by title");
    while(list($cid, $title) = mysql_fetch_row($result)) {
        $transfertitle = str_replace (" ", "_", $title);
    	echo "<a href=\"admin.php?op=LinksValidate&amp;cid=$cid&amp;sid=0&amp;ttitle=$transfertitle\">$title</a><br>";
    }
    echo "</font></center></td>";
    echo "<td valign=\"top\"><center><b>"._CHECKSUBCATEGORIES."</b><br><br><br><font size=\"1\">";
    $result = mysql_query("select sid, cid, title from $prefix"._links_subcategories." order by title");    
    while(list($sid, $cid, $title) = mysql_fetch_row($result)) {
        $transfertitle = str_replace (" ", "_", $title);
    	$result2 = mysql_query("select title from $prefix"._links_categories." where cid = $cid");
    	while(list($ctitle) = mysql_fetch_row($result2)) {
	    echo "<a href=\"admin.php?op=LinksValidate&amp;cid=0&amp;sid=$sid&amp;ttitle=$transfertitle\">$ctitle</a>";
	}
    echo " / <a href=\"admin.php?op=LinksValidate&amp;cid=0&amp;sid=$sid&amp;ttitle=$transfertitle\">$title</a><br>";
    }
    echo "</font></center></td></tr></table>";
    CloseTable();
    include ("footer.php");
}

function LinksValidate($cid, $sid, $ttitle) {
    global $bgcolor2, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._WEBLINKSADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    $transfertitle = str_replace ("_", "", $ttitle);
    /* Check ALL Links */
    echo "<table width=100% border=0>";
    if ($cid==0 && $sid==0) {
	echo "<tr><td colspan=\"3\"><center><b>"._CHECKALLLINKS."</b><br>"._BEPATIENT."</center><br><br></td></tr>";
	$result = mysql_query("select lid, title, url, name, email, submitter from $prefix"._links_links." order by title");    
    }   	
    /* Check Categories & Subcategories */
    if ($cid!=0 && $sid==0) {
	echo "<tr><td colspan=\"3\"><center><b>"._VALIDATINGCAT.": $transfertitle</b><br>"._BEPATIENT."</center><br><br></td></tr>";
	$result = mysql_query("select lid, title, url, name, email, submitter from $prefix"._links_links." where cid=$cid order by title");    
    }
    /* Check Only Subcategory */
    if ($cid==0 && $sid!=0) {
   	echo "<tr><td colspan=\"3\"><center><b>"._VALIDATINGSUBCAT.": $transfertitle</b><br>"._BEPATIENT."</center><br><br></td></tr>";
   	$result = mysql_query("select lid, title, url, name, email, submitter from $prefix"._links_links." where sid=$sid order by title");
    }
    echo "<tr><td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._STATUS."</b></td><td bgcolor=\"$bgcolor2\" width=\"100%\"><b>"._LINKTITLE."</b></td><td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._FUNCTIONS."</b></td></tr>";
    while(list($lid, $title, $url, $name, $email, $submitter) = mysql_fetch_row($result)) {
	$vurl = parse_url($url);
	$fp = fsockopen($vurl['host'], 80, $errno, $errstr, 15);
	if (!$fp){ 
	    echo "<tr><td align=\"center\"><b>&nbsp;&nbsp;"._FAILED."&nbsp;&nbsp;</b></td>"
		."<td>&nbsp;&nbsp;<a href=\"$url\" target=\"new\">$title</a>&nbsp;&nbsp;</td>"
		."<td align=\"center\"><font size=\"2\">&nbsp;&nbsp;[ <a href=\"admin.php?op=LinksModLink&amp;lid=$lid\">"._EDIT."</a> | <a href=\"admin.php?op=LinksDelLink&amp;lid=$lid\">"._DELETE."</a> ]&nbsp;&nbsp;</font>"
		."</td></tr>";
	}		
	if ($fp){ 
	    echo "<tr><td align=\"center\">&nbsp;&nbsp;"._OK."&nbsp;&nbsp;</td>"
		."<td>&nbsp;&nbsp;<a href=\"$url\" target=\"new\">$title</a>&nbsp;&nbsp;</td>"
		."<td align=\"center\"><font size=\"2\">&nbsp;&nbsp;"._NONE."&nbsp;&nbsp;</font>"
		."</td></tr>";
	} 
    }
    echo "</table>";
    CloseTable();   	
    include ("footer.php");
}

function LinksAddLink($new, $lid, $title, $url, $cat, $description, $name, $email, $submitter) {
    global $prefix;
    $result = mysql_query("select url from $prefix"._links_links." where url='$url'");
    $numrows = mysql_num_rows($result);
    if ($numrows>0) {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._WEBLINKSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center>"
	    ."<font size=\"3\">"
	    ."<b>"._ERRORURLEXIST."</b><br><br>"
	    .""._GOBACK."<br><br>";
	CloseTable();
	include("footer.php");
    } else {
/* Check if Title exist */
    if ($title=="") {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._WEBLINKSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center>"
	    ."<font size=\"3\">"
	    ."<b>"._ERRORNOTITLE."</b><br><br>"
	    .""._GOBACK."<br><br>";
	CloseTable();
	include("footer.php");
    }
/* Check if URL exist */
    if ($url=="") {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._WEBLINKSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center>"
	    ."<font size=\"3\">"
	    ."<b>"._ERRORNOURL."</b><br><br>"
	    .""._GOBACK."<br><br>";
	CloseTable();
	include("footer.php");
    }
// Check if Description exist
    if ($description=="") {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._WEBLINKSADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center>"
	    ."<font size=\"3\">"
	    ."<b>"._ERRORNODESCRIPTION."</b><br><br>"
	    .""._GOBACK."<br><br>";
	CloseTable();
	include("footer.php");
    }
    $cat = explode("-", $cat);
    if ($cat[1]=="") {
	$cat[1] = 0;
    }
    $title = stripslashes(FixQuotes($title));
    $url = stripslashes(FixQuotes($url));
    $description = stripslashes(FixQuotes($description));
    $name = stripslashes(FixQuotes($name));
    $email = stripslashes(FixQuotes($email));
    mysql_query("insert into $prefix"._links_links." values (NULL, '$cat[0]', '$cat[1]', '$title', '$url', '$description', now(), '$name', '$email', '0','$submitter',0,0,0)");
    global $nukeurl, $sitename;
    include("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<br><center>";
    echo "<font size=\"3\">";
    echo ""._NEWLINKADDED."<br><br>";
    echo "[ <a href=\"admin.php?op=links\">"._WEBLINKSADMIN."</a> ]</center><br><br>";
    CloseTable();
    if ($new==1) {
	mysql_query("delete from $prefix"._links_newlink." where lid=$lid");
	if ($email=="") {
	} else {
	    $subject = ""._YOULINKAT." $sitename";
	    $message = ""._HELLO." $name:\n\n"._LINKAPPROVEDMSG."\n\n"._LINKTITLE.": $title\n"._URL.": $url\n"._DESCRIPTION.": $description\n\n\n"._YOUCANBROWSEUS." $nukeurl/links.php\n\n"._THANKS4YOURSUBMISSION."\n\n$sitename "._TEAM."";
	    $from = "$sitename";
	    mail($email, $subject, $message, "From: $from\nX-Mailer: PHP/" . phpversion());
	}
    }
    include("footer.php");
    }
}

switch ($op) {
			
    case "links":
    links();
    break;

    case "LinksDelNew":
    LinksDelNew($lid);
    break;

    case "LinksAddCat":
    LinksAddCat($title, $cdescription);
    break;

    case "LinksAddSubCat":
    LinksAddSubCat($cid, $title);
    break;

    case "LinksAddLink":
    LinksAddLink($new, $lid, $title, $url, $cat, $description, $name, $email, $submitter);
    break;
			
    case "LinksAddEditorial":
    LinksAddEditorial($linkid, $editorialtitle, $editorialtext);
    break;			
			
    case "LinksModEditorial":
    LinksModEditorial($linkid, $editorialtitle, $editorialtext);
    break;	
			
    case "LinksLinkCheck":
    LinksLinkCheck();
    break;	
		
    case "LinksValidate":
    LinksValidate($cid, $sid, $ttitle);
    break;

    case "LinksDelEditorial":
    LinksDelEditorial($linkid);
    break;						

    case "LinksCleanVotes":
    LinksCleanVotes();
    break;	
		
    case "LinksListBrokenLinks":
    LinksListBrokenLinks();
    break;

    case "LinksDelBrokenLinks":
    LinksDelBrokenLinks($lid);
    break;
			
    case "LinksIgnoreBrokenLinks":
    LinksIgnoreBrokenLinks($lid);
    break;			
			
    case "LinksListModRequests":
    LinksListModRequests();
    break;		
			
    case "LinksChangeModRequests":
    LinksChangeModRequests($requestid);
    break;	
			
    case "LinksChangeIgnoreRequests":
    LinksChangeIgnoreRequests($requestid);
    break;
			
    case "LinksDelCat":
    LinksDelCat($cid, $sid, $sub, $ok);
    break;

    case "LinksModCat":
    LinksModCat($cat);
    break;

    case "LinksModCatS":
    LinksModCatS($cid, $sid, $sub, $title, $cdescription);
    break;

    case "LinksModLink":
    LinksModLink($lid);
    break;

    case "LinksModLinkS":
    LinksModLinkS($lid, $title, $url, $description, $name, $email, $hits, $cat);
    break;

    case "LinksDelLink":
    LinksDelLink($lid);
    break;

    case "LinksDelVote":
    LinksDelVote($lid, $rid);
    break;			

    case "LinksDelComment":
    LinksDelComment($lid, $rid);
    break;

}

} else {
    echo "Access Denied";
}

?>