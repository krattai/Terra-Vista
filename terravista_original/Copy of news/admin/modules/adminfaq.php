<?php

/************************************************************************/
/* PHP-NUKE: Web Portal System                                          */
/* ===========================                                          */
/*                                                                      */
/* Copyright (c) 2001 by Francisco Burzi (fbc@mandrakesoft.com)         */
/* http://phpnuke.org                                                   */
/*                                                                      */
/* ========================                                             */
/* Based on PHP-Nuke Add-On                                             */
/* Copyright (c) 2001 by Richard Tirtadji AKA King Richard              */
/*                       (rtirtadji@hotmail.com)                        */
/*                       Hutdik Hermawan AKA hotFix                     */
/*                       (hutdik76@hotmail.com)                         */
/* http://www.nukeaddon.com                                             */
/*                                                                      */
/* This program is free software. You can redistribute it and/or modify */
/* it under the terms of the GNU General Public License as published by */
/* the Free Software Foundation; either version 2 of the License.       */
/************************************************************************/


if (!eregi("admin.php", $PHP_SELF)) { die ("Access Denied"); }
$hlpfile = "manual/faqs.html";
$result = mysql_query("select radminfaq, radminsuper from $prefix"._authors." where aid='$aid'");
list($radminfaq, $radminsuper) = mysql_fetch_row($result);
if (($radminfaq==1) OR ($radminsuper==1)) {

/*********************************************************/
/* Faq Admin Function                                    */
/*********************************************************/

function FaqAdmin() {
    global $hlpfile, $admin, $bgcolor2, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._FAQADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._ACTIVEFAQS."</b></font></center><br>"
	."<table border=\"1\" width=\"100%\" align=\"center\"><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._ID."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._CATEGORIES."</b></td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\"><b>"._FUNCTIONS."</b></td></tr><tr>";
    $result = mysql_query("select id_cat, categories from $prefix"._faqCategories." order by id_cat");
    while(list($id_cat, $categories) = mysql_fetch_row($result)) {
	echo "<td align=\"center\">$id_cat</td>"
	    ."<td align=\"center\">$categories</td>"
	    ."<td align=\"center\">[ <a href=\"admin.php?op=FaqCatGo&amp;id_cat=$id_cat\">"._CONTENT."</a> | <a href=\"admin.php?op=FaqCatEdit&amp;id_cat=$id_cat\">"._EDIT."</a> | <a href=\"admin.php?op=FaqCatDel&amp;id_cat=$id_cat&amp;ok=0\">"._DELETE."</a> ]</td><tr>";
    }
    echo "</td></tr></table>";
    CloseTable();
    echo "<br>";
    OpenTable();    
    echo "<center><font size=\"3\"><b>"._ADDCATEGORY."</b></font></center><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\" width=\"100%\"><tr><td>"
	.""._CATEGORIES.":</td><td><input type=\"text\" name=\"categories\" size=\"30\">"
	."</td></tr></table>"
	."<input type=\"hidden\" name=\"op\" value=\"FaqCatAdd\">"
	."<input type=\"submit\" value="._SAVE.">"
	."</form>";
    CloseTable();
    include("footer.php");
}

function FaqCatGo($id_cat) {
    global $hlpfile, $admin, $bgcolor2, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._FAQADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._QUESTIONS."</b></font></center><br>"
	."<table border=1 width=100% align=\"center\"><tr>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\">"._CONTENT."</td>"
	."<td bgcolor=\"$bgcolor2\" align=\"center\">"._FUNCTIONS."</td></tr>";
    $result = mysql_query("select id, question, answer from $prefix"._faqAnswer." where id_cat='$id_cat' order by id");
    while(list($id, $question, $answer) = mysql_fetch_row($result)) {
	echo "<tr><td align=\"center\"><i>$question</i><br><br>$answer"
	    ."</td><td align=\"center\">[ <a href=\"admin.php?op=FaqCatGoEdit&amp;id=$id\">"._EDIT."</a> | <a href=\"admin.php?op=FaqCatGoDel&amp;id=$id&amp;ok=0\">"._DELETE."</a> ]</td></tr>"
	    ."</td></tr>";
    }
    echo "</table>";
    CloseTable();
    echo "<br>";
    OpenTable();
    echo "<center><font size=\"3\"><b>"._ADDQUESTION."</b></center><br>"
	."<form action=\"admin.php\" method=\"post\">"
	."<table border=\"0\" width=\"100%\"><tr><td>"
	.""._QUESTION.":</td><td><input type=\"text\" name=\"question\" size=\"40\"></td></tr><tr><td>"
	.""._ANSWER." </td><td><textarea name=\"answer\" cols=\"60\" rows=\"10\"></textarea>"
	."</td></tr></table>"
	."<input type=\"hidden\" name=\"id_cat\" value=\"$id_cat\">"
	."<input type=\"hidden\" name=\"op\" value=\"FaqCatGoAdd\">"
	."<input type=\"submit\" value="._SAVE."> "._GOBACK.""
	."</form>";
    CloseTable();
    include("footer.php");
}

function FaqCatEdit($id_cat) {
    global $hlpfile, $admin;
    include ("config.php");
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._FAQADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = mysql_query("select categories from $prefix"._faqCategories." where id_cat='$id_cat'");
    list($categories) = mysql_fetch_row($result);
    OpenTable();
    echo "<center><font size=\"3\"><b>"._EDITCATEGORY."</b></font></center>"
	."<form action=\"admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"id_cat\" value=\"$id_cat\">"
	."<table border=\"0\" width=\"100%\"><tr><td>"
	.""._CATEGORIES.":</td><td><input type=\"text\" name=\"categories\" size=\"31\" value=\"$categories\">"
	."</td></tr></table>"
	."<input type=\"hidden\" name=\"op\" value=\"FaqCatSave\">"
	."<input type=\"submit\" value=\""._SAVE."\"> "._GOBACK.""
	."</form>";
    CloseTable();
    include("footer.php");
}

function FaqCatGoEdit($id) {
    global $hlpfile, $admin, $bgcolor2, $prefix;
    include ("header.php");
    GraphicAdmin($hlpfile);
    OpenTable();
    echo "<center><font size=\"4\"><b>"._FAQADMIN."</b></font></center>";
    CloseTable();
    echo "<br>";
    $result = mysql_query("select question, answer from $prefix"._faqAnswer." where id='$id'");
    list($question, $answer) = mysql_fetch_row($result);
    OpenTable();
    echo "<center><font size=\"3\"><b>"._EDITQUESTIONS."</b></font></center>"
	."<form action=\"admin.php\" method=\"post\">"
	."<input type=\"hidden\" name=\"id\" value=\"$id\">"
	."<table border=\"0\" width=\"100%\"><tr><td>"
	.""._QUESTION.":</td><td><input type=\"text\" name=\"question\" size=\"31\" value=\"$question\"></td></tr><tr><td>"
	.""._ANSWER.":</td><td><textarea name=\"answer\" cols=60 rows=5>$answer</textarea>"
	."</td></tr></table>"
	."<input type=\"hidden\" name=\"op\" value=\"FaqCatGoSave\">"
	."<input type=\"submit\" value="._SAVE."> "._GOBACK.""
	."</form>";
    CloseTable();
    include("footer.php");
}


function FaqCatSave($id_cat, $categories) {
    global $prefix;
    $categories = stripslashes(FixQuotes($categories));
    mysql_query("update $prefix"._faqCategories." set categories='$categories' where id_cat='$id_cat'");
    Header("Location: admin.php?op=FaqAdmin");
}

function FaqCatGoSave($id, $question, $answer) {
    global $prefix;
    $question = stripslashes(FixQuotes($question));
    $answer = stripslashes(FixQuotes($answer));	
    mysql_query("update $prefix"._faqAnswer." set question='$question', answer='$answer' where id='$id'");
    Header("Location: admin.php?op=FaqAdmin");
}

function FaqCatAdd($categories) {
    global $prefix;
    $categories = stripslashes(FixQuotes($categories));
    mysql_query("insert into $prefix"._faqCategories." values (NULL, '$categories')");
    Header("Location: admin.php?op=FaqAdmin");
}

function FaqCatGoAdd($id_cat, $question, $answer) {
    global $prefix;
    $question = stripslashes(FixQuotes($question));
    $answer = stripslashes(FixQuotes($answer));	
    mysql_query("insert into $prefix"._faqAnswer." values (NULL, '$id_cat', '$question', '$answer')");
    Header("Location: admin.php?op=FaqCatGo&id_cat=$id_cat");
}

function FaqCatDel($id_cat, $ok=0) {
    global $prefix;
    if($ok==1) {
	mysql_query("delete from $prefix"._faqCategories." where id_cat=$id_cat");
	mysql_query("delete from $prefix"._faqAnswer." where id_cat=$id_cat");
	Header("Location: admin.php?op=FaqAdmin");
    } else {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._FAQADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center><b>"._FAQDELWARNING."</b><br><br>";
    }
	echo "[ <a href=\"admin.php?op=FaqCatDel&amp;id_cat=$id_cat&amp;ok=1\">"._YES."</a> | <a href=\"admin.php?op=FaqAdmin\">"._NO."</a> ]</center><br><br>";
	CloseTable();
	include("footer.php");	
}

function FaqCatGoDel($id, $ok=0) {
    global $prefix;
    if($ok==1) {
	mysql_query("delete from $prefix"._faqAnswer." where id=$id");
	Header("Location: admin.php?op=FaqAdmin");
    } else {
	include("header.php");
	GraphicAdmin($hlpfile);
	OpenTable();
	echo "<center><font size=\"4\"><b>"._FAQADMIN."</b></font></center>";
	CloseTable();
	echo "<br>";
	OpenTable();
	echo "<br><center><b>"._QUESTIONDEL."</b><br><br>";
    }
	echo "[ <a href=\"admin.php?op=FaqCatGoDel&amp;id=$id&amp;ok=1\">"._YES."</a> | <a href=\"admin.php?op=FaqAdmin\">"._NO."</a> ]</center><br><br>";
	CloseTable();
	include("footer.php");	
}

} else {
    echo "Access Denied";
}

?>