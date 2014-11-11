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

mysql_connect($dbhost, $dbuname, $dbpass);
@mysql_select_db("$dbname") or die ("Unable to select database");

function Upgrade50Old($addglossary,$addguestbook,$addchatbox,$addforum,$addim,$addcalendar,$addweather) {
global $prefix;

if ($addglossary) {

$sql = "CREATE TABLE ".$prefix."_glossary (
   gid int(4) NOT NULL auto_increment,
   gterm varchar(255),
   gdefinition varchar(255),
   PRIMARY KEY (gid)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_glossary has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_glossary</font><br>";

$sql = mysql_query("INSERT INTO ".$prefix."_glossary VALUES ( '1', 'PHP Nuke', 'WebPortal System created by Francisco Burzi which is now one of the most popular PHP application in the worlds.')");
$sql = mysql_query("INSERT INTO ".$prefix."_glossary VALUES ( '2', 'Nuke AddOn', 'Special addon for PHP Nuke made by many developers from all over the worlds.')");
$sql = mysql_query("INSERT INTO ".$prefix."_glossary VALUES ( '3', 'NOM', 'Nuke Operations Manual which developed by many developer around the globe. The project currently lead by SuperUser.')");
$sql = mysql_query("INSERT INTO ".$prefix."_glossary VALUES ( '4', 'NAOM', 'Nuke AddOn Operations Manual which developed by many developer around the globe. The project currently lead by KingRichard.')");

}


if ($addguestbook) {

$sql = "CREATE TABLE ".$prefix."_guestbook (
   id_msg int(11) DEFAULT '0' NOT NULL auto_increment,
   name varchar(255) NOT NULL,
   email varchar(255),
   ip varchar(255) NOT NULL,
   message text NOT NULL,
   icq int(11),
   homepage varchar(255),
   bdate datetime DEFAULT '0000-00-00' NOT NULL,
   members int(2) DEFAULT '0' NOT NULL,
   private int(2) DEFAULT '0' NOT NULL,   
   PRIMARY KEY (id_msg)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_guestbook has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_guestbook</font><br>";

}

if ($addchatbox) {

$sql = "CREATE TABLE ".$prefix."_chatbox (
   username text,
   ip varchar(50),
   message text,
   date int(15),
   id int(10),
   dbname tinyint(4)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_chatbox has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_chatbox</font><br>";

$sql = mysql_query("INSERT INTO ".$prefix."_blocks VALUES ( NULL, '', 'ChatBox', 'modules/NS-ChatBox/block.php', '', 'l', '10', '1', '3600', '')");

}

if ($addforum) {

$sql= "ALTER TABLE ".$prefix."_authors ADD radminforum tinyint(2) DEFAULT '0' NOT NULL AFTER radmindownload";
if (mysql_query($sql)) echo "Table ".$prefix."_authors has been altered<br>";
else echo "<B>Can not altered table ".$prefix."_authors</B><br>";

mysql_query("ALTER TABLE users_status RENAME ".$prefix."_users_status");
mysql_query("ALTER TABLE catagories RENAME ".$prefix."_catagories");
mysql_query("ALTER TABLE forums RENAME ".$prefix."_forums");
mysql_query("ALTER TABLE posts RENAME ".$prefix."_posts");
mysql_query("ALTER TABLE forumtopics RENAME ".$prefix."_forumtopics");
mysql_query("ALTER TABLE ranks RENAME ".$prefix."_ranks");
mysql_query("ALTER TABLE config RENAME ".$prefix."_config");
mysql_query("ALTER TABLE access RENAME ".$prefix."_access");

$sql = "CREATE TABLE ".$prefix."_smiles (
   id int(10) NOT NULL auto_increment,
   code varchar(50),
   smile_url varchar(100),
   emotion varchar(75),
   active tinyint(2) DEFAULT '0',
   PRIMARY KEY (id)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_smiles has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_smiles</font><br>";

$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '1', ':D', 'icon_biggrin.gif', 'Very Happy', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '2', ':-D', 'icon_biggrin.gif', 'Very Happy', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '3', ':grin:', 'icon_biggrin.gif', 'Very Happy', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '4', ':)', 'icon_smile.gif', 'Smile', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '5', ':-)', 'icon_smile.gif', 'Smile', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '6', ':smile:', 'icon_smile.gif', 'Smile', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '7', ':(', 'icon_frown.gif', 'Sad', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '8', ':-(', 'icon_frown.gif', 'Sad', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '9', ':sad:', 'icon_frown.gif', 'Sad', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '10', ':o', 'icon_eek.gif', 'Surprised', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '11', ':-o', 'icon_eek.gif', 'Surprised', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '12', ':eek:', 'icon_eek.gif', 'Suprised', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '13', ':-?', 'icon_confused.gif', 'Confused', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '14', ':???:', 'icon_confused.gif', 'Confused', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '15', '8)', 'icon_cool.gif', 'Cool', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '16', '8-)', 'icon_cool.gif', 'Cool', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '17', ':cool:', 'icon_cool.gif', 'Cool', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '18', ':lol:', 'icon_lol.gif', 'Laughing', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '19', ':x', 'icon_mad.gif', 'Mad', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '20', ':-x', 'icon_mad.gif', 'Mad', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '21', ':mad:', 'icon_mad.gif', 'Mad', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '22', ':P', 'icon_razz.gif', 'Razz', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '23', ':-P', 'icon_razz.gif', 'Razz', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '24', ':razz:', 'icon_razz.gif', 'Razz', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '25', ':oops:', 'icon_redface.gif', 'Embaressed', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '26', ':cry:', 'icon_cry.gif', 'Crying (very sad)', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '27', ':evil:', 'icon_evil.gif', 'Evil or Very Mad', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '28', ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '29', ':wink:', 'icon_wink.gif', 'Wink', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '30', ';)', 'icon_wink.gif', 'Wink', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '31', ';-)', 'icon_wink.gif', 'Wink', '1')");

$sql = mysql_query("INSERT INTO ".$prefix."_blocks VALUES ( NULL, '', 'Current Forum', 'modules/Forum/block.php', '', 'r', '10', '1', '3600', '')");

}

if ($addim) {

$sql = "CREATE TABLE ".$prefix."_im (
   username_to varchar(255) NOT NULL,
   username_from varchar(255) NOT NULL,
   time int(10) unsigned DEFAULT '0' NOT NULL,
   subject varchar(255) NOT NULL,
   message text NOT NULL,
   IMID int(10) NOT NULL auto_increment,
   PRIMARY KEY (IMID)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_im has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_im</font><br>";

$sql = mysql_query("INSERT INTO ".$prefix."_blocks VALUES ( NULL, '', 'Instant Messaging', 'modules/NS-IM/block.php', '', 'l', '9', '1', '3600', '')");

}

if ($addcalendar) {

$sql = "CREATE TABLE ".$prefix."_events_queue (
  qid BIGINT NOT NULL auto_increment,
  uid mediumint(9) NOT NULL default '0',
  uname varchar(40) NOT NULL default '',
  title varchar(150) NOT NULL default '',
  story BLOB,
  timestamp datetime NOT NULL default '0000-00-00 00:00:00',
  topic varchar(20) NOT NULL,
  eventDate DATE NOT NULL,
  endDate DATE NOT NULL,
  startTime TIME,
  endTime TIME,
  alldayevent INT(1) NOT NULL default '0',
  barcolor VARCHAR(1),
  PRIMARY KEY  (qid)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_events_queue has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_events_queue</font><br>";

$sql = "CREATE TABLE ".$prefix."_events (
  eid BIGINT NOT NULL auto_increment,
  aid varchar(30) NOT NULL default '',
  title varchar(150) default NULL,
  time datetime default NULL,
  hometext BLOB,
  comments int(11) default '0',
  counter mediumint(8) unsigned default NULL,
  topic int(3) NOT NULL default '1',
  informant varchar(20) NOT NULL default '',
  eventDate DATE NOT NULL,
  endDate DATE NOT NULL,
  startTime TIME,
  endTime TIME,
  alldayevent INT(1) NOT NULL default '0',
  barcolor VARCHAR(1),
  PRIMARY KEY  (eid)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_events has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_events</font><br>";

$sql = mysql_query("INSERT INTO ".$prefix."_blocks VALUES ( NULL, '', 'Calendar', 'modules/Calendar/addonblock.php', '', 'l', '8', '1', '3600', '')");

}

if ($addweather) {
$sql = mysql_query("INSERT INTO ".$prefix."_blocks VALUES ( NULL, '', 'Weathers', 'modules/Weather/block.php', '', 'r', '11', '1', '3600', '')");
}

}

function Upgrade50Full($addglossary,$addguestbook,$addchatbox,$addforum,$addim,$addcalendar,$addweather) {
global $prefix;

if ($addglossary) {

$sql = "CREATE TABLE ".$prefix."_glossary (
   gid int(4) NOT NULL auto_increment,
   gterm varchar(255),
   gdefinition varchar(255),
   PRIMARY KEY (gid)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_glossary has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_glossary</font><br>";

$sql = mysql_query("INSERT INTO ".$prefix."_glossary VALUES ( '1', 'PHP Nuke', 'WebPortal System created by Francisco Burzi which is now one of the most popular PHP application in the worlds.')");
$sql = mysql_query("INSERT INTO ".$prefix."_glossary VALUES ( '2', 'Nuke AddOn', 'Special addon for PHP Nuke made by many developers from all over the worlds.')");
$sql = mysql_query("INSERT INTO ".$prefix."_glossary VALUES ( '3', 'NOM', 'Nuke Operations Manual which developed by many developer around the globe. The project currently lead by SuperUser.')");
$sql = mysql_query("INSERT INTO ".$prefix."_glossary VALUES ( '4', 'NAOM', 'Nuke AddOn Operations Manual which developed by many developer around the globe. The project currently lead by KingRichard.')");

}


if ($addguestbook) {

$sql = "CREATE TABLE ".$prefix."_guestbook (
   id_msg int(11) DEFAULT '0' NOT NULL auto_increment,
   name varchar(255) NOT NULL,
   email varchar(255),
   ip varchar(255) NOT NULL,
   message text NOT NULL,
   icq int(11),
   homepage varchar(255),
   bdate datetime DEFAULT '0000-00-00' NOT NULL,
   members int(2) DEFAULT '0' NOT NULL,
   private int(2) DEFAULT '0' NOT NULL,   
   PRIMARY KEY (id_msg)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_guestbook has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_guestbook</font><br>";

}

if ($addchatbox) {

$sql = "CREATE TABLE ".$prefix."_chatbox (
   username text,
   ip varchar(50),
   message text,
   date int(15),
   id int(10),
   dbname tinyint(4)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_chatbox has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_chatbox</font><br>";

$sql = mysql_query("INSERT INTO ".$prefix."_blocks VALUES ( NULL, '', 'ChatBox', 'modules/NS-ChatBox/block.php', '', 'l', '10', '1', '3600', '')");

}

if ($addforum) {

$sql= "ALTER TABLE ".$prefix."_authors ADD radminforum tinyint(2) DEFAULT '0' NOT NULL AFTER radmindownload";
if (mysql_query($sql)) echo "Table ".$prefix."_authors has been altered<br>";
else echo "<B>Can not altered table ".$prefix."_authors</B><br>";

$sql = "CREATE TABLE ".$prefix."_users_status (uid int(11) DEFAULT '0' NOT NULL auto_increment, posts int(10) DEFAULT '0', attachsig int(2) DEFAULT '0', rank int(10) DEFAULT '0', level int(10) DEFAULT '1', PRIMARY KEY (uid))";
if (mysql_query($sql)) echo "Table ".$prefix."_users_status has been created<br>";
else echo "<B>Can not create table ".$prefix."_users_status</B><br>";

$result=mysql_query("SELECT uid FROM ".$prefix."_users");
if (mysql_num_rows($result)) {
	while ($row = mysql_fetch_array($result)) {
	mysql_query("INSERT INTO ".$prefix."_users_status VALUES ('$row[uid]', '0', '0', '0', '1')");
	}
	echo "Synchronization user has been done<br>";
}
else echo "<B>You don't have any users</B><br>";

$sql="CREATE TABLE ".$prefix."_catagories (cat_id int(10) DEFAULT '0' NOT NULL auto_increment, cat_title varchar(100), PRIMARY KEY (cat_id))";
if (mysql_query($sql)) echo "Table ".$prefix."_catagories has been created<br>";
else echo "<B>Can not create table ".$prefix."_catagories</B><br>";

$sql="CREATE TABLE ".$prefix."_forums (forum_id int(10) DEFAULT '0' NOT NULL auto_increment, forum_name varchar(150), forum_desc text, forum_access int(10) DEFAULT '1', forum_moderator int(10), cat_id int(10), forum_type int(10) DEFAULT '0', forum_pass varchar(60), PRIMARY KEY (forum_id))";
if (mysql_query($sql)) echo "Table ".$prefix."_forums has been created<br>";
else "<B>Can not create table ".$prefix."_forums</B><br>";

$sql="CREATE TABLE ".$prefix."_posts ( post_id int(10) DEFAULT '0' NOT NULL auto_increment, image varchar(100) NOT NULL, topic_id int(10) DEFAULT '0' NOT NULL, forum_id int(10) DEFAULT '0' NOT NULL, poster_id int(10), post_text text, post_time varchar(20), poster_ip varchar(16), PRIMARY KEY (post_id))";
if (mysql_query($sql)) echo "Table ".$prefix."_posts has been created<br>";
else echo "<B>Can not create table ".$prefix."_posts</B><BR>";

$sql="CREATE TABLE ".$prefix."_forumtopics ( topic_id int(10) DEFAULT '0' NOT NULL auto_increment, topic_title varchar(100), topic_poster int(10), topic_time varchar(20), topic_views int(10) DEFAULT '0' NOT NULL, forum_id int(10), topic_status int(10) DEFAULT '0' NOT NULL, topic_notify int(2) DEFAULT '0', PRIMARY KEY (topic_id))";
if (mysql_query($sql)) echo "Table ".$prefix."_forumtopics has been created<br>";
else echo "<B>Can not create table ".$prefix."_forumtopics</B><br>";

$sql="CREATE TABLE ".$prefix."_ranks ( rank_id int(10) DEFAULT '0' NOT NULL auto_increment, rank_title varchar(50) NOT NULL, rank_min int(10) DEFAULT '0' NOT NULL, rank_max int(10) DEFAULT '0' NOT NULL, rank_special int(2) DEFAULT '0', PRIMARY KEY (rank_id), KEY rank_min (rank_min), KEY rank_max (rank_max))";
if (mysql_query($sql)) echo "Table ".$prefix."_ranks has been created<br>";
else echo "<B>Can not create table ".$prefix."_ranks</B><br>";

$sql="CREATE TABLE ".$prefix."_config ( allow_html int(2), allow_bbcode int(2), allow_sig int(2), posts_per_page int(10), hot_threshold int(10), topics_per_page int(10))";
if (mysql_query($sql)) echo "Table ".$prefix."_config has been created<br>";
else echo "<B>Can not create table ".$prefix."_config</B><br>";

$sql="INSERT INTO ".$prefix."_config VALUES('1','1','1','10','10','10')";
if (mysql_query($sql)) echo "Default board forums's configuration has been added<br>";
else echo "<B>Can not add default board forum's configuration</B><br>";

$sql="CREATE TABLE ".$prefix."_access ( access_id int(10) DEFAULT '0' NOT NULL auto_increment, access_title varchar(20), PRIMARY KEY (access_id))";
if (mysql_query($sql)) echo "Table ".$prefix."_access has been created<br>";
else echo "<B>Can not create table ".$prefix."_access</B><br>";

mysql_query("INSERT INTO ".$prefix."_access VALUES ( '1', 'User')");
mysql_query("INSERT INTO ".$prefix."_access VALUES ( '2', 'Moderator')");
mysql_query("INSERT INTO ".$prefix."_access VALUES ( '3', 'Super Moderator')");

$sql = "CREATE TABLE ".$prefix."_smiles (
   id int(10) NOT NULL auto_increment,
   code varchar(50),
   smile_url varchar(100),
   emotion varchar(75),
   active tinyint(2) DEFAULT '0',
   PRIMARY KEY (id)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_smiles has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_smiles</font><br>";

$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '1', ':D', 'icon_biggrin.gif', 'Very Happy', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '2', ':-D', 'icon_biggrin.gif', 'Very Happy', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '3', ':grin:', 'icon_biggrin.gif', 'Very Happy', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '4', ':)', 'icon_smile.gif', 'Smile', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '5', ':-)', 'icon_smile.gif', 'Smile', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '6', ':smile:', 'icon_smile.gif', 'Smile', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '7', ':(', 'icon_frown.gif', 'Sad', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '8', ':-(', 'icon_frown.gif', 'Sad', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '9', ':sad:', 'icon_frown.gif', 'Sad', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '10', ':o', 'icon_eek.gif', 'Surprised', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '11', ':-o', 'icon_eek.gif', 'Surprised', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '12', ':eek:', 'icon_eek.gif', 'Suprised', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '13', ':-?', 'icon_confused.gif', 'Confused', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '14', ':???:', 'icon_confused.gif', 'Confused', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '15', '8)', 'icon_cool.gif', 'Cool', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '16', '8-)', 'icon_cool.gif', 'Cool', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '17', ':cool:', 'icon_cool.gif', 'Cool', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '18', ':lol:', 'icon_lol.gif', 'Laughing', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '19', ':x', 'icon_mad.gif', 'Mad', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '20', ':-x', 'icon_mad.gif', 'Mad', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '21', ':mad:', 'icon_mad.gif', 'Mad', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '22', ':P', 'icon_razz.gif', 'Razz', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '23', ':-P', 'icon_razz.gif', 'Razz', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '24', ':razz:', 'icon_razz.gif', 'Razz', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '25', ':oops:', 'icon_redface.gif', 'Embaressed', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '26', ':cry:', 'icon_cry.gif', 'Crying (very sad)', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '27', ':evil:', 'icon_evil.gif', 'Evil or Very Mad', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '28', ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes', '1')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '29', ':wink:', 'icon_wink.gif', 'Wink', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '30', ';)', 'icon_wink.gif', 'Wink', '0')");
$sql = mysql_query("INSERT INTO ".$prefix."_smiles VALUES ( '31', ';-)', 'icon_wink.gif', 'Wink', '1')");

$sql = mysql_query("INSERT INTO ".$prefix."_blocks VALUES ( NULL, '', 'Current Forum', 'modules/Forum/block.php', '', 'r', '10', '1', '3600', '')");

}

if ($addim) {

$sql = "CREATE TABLE ".$prefix."_im (
   username_to varchar(255) NOT NULL,
   username_from varchar(255) NOT NULL,
   time int(10) unsigned DEFAULT '0' NOT NULL,
   subject varchar(255) NOT NULL,
   message text NOT NULL,
   IMID int(10) NOT NULL auto_increment,
   PRIMARY KEY (IMID)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_im has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_im</font><br>";

$sql = mysql_query("INSERT INTO ".$prefix."_blocks VALUES ( NULL, '', 'Instant Messaging', 'modules/NS-IM/block.php', '', 'l', '9', '1', '3600', '')");

}

if ($addcalendar) {

$sql = "CREATE TABLE ".$prefix."_events_queue (
  qid BIGINT NOT NULL auto_increment,
  uid mediumint(9) NOT NULL default '0',
  uname varchar(40) NOT NULL default '',
  title varchar(150) NOT NULL default '',
  story BLOB,
  timestamp datetime NOT NULL default '0000-00-00 00:00:00',
  topic varchar(20) NOT NULL,
  eventDate DATE NOT NULL,
  endDate DATE NOT NULL,
  startTime TIME,
  endTime TIME,
  alldayevent INT(1) NOT NULL default '0',
  barcolor VARCHAR(1),
  PRIMARY KEY  (qid)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_events_queue has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_events_queue</font><br>";

$sql = "CREATE TABLE ".$prefix."_events (
  eid BIGINT NOT NULL auto_increment,
  aid varchar(30) NOT NULL default '',
  title varchar(150) default NULL,
  time datetime default NULL,
  hometext BLOB,
  comments int(11) default '0',
  counter mediumint(8) unsigned default NULL,
  topic int(3) NOT NULL default '1',
  informant varchar(20) NOT NULL default '',
  eventDate DATE NOT NULL,
  endDate DATE NOT NULL,
  startTime TIME,
  endTime TIME,
  alldayevent INT(1) NOT NULL default '0',
  barcolor VARCHAR(1),
  PRIMARY KEY  (eid)
)";
if (mysql_query($sql)) echo "Table ".$prefix."_events has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_events</font><br>";

$sql = mysql_query("INSERT INTO ".$prefix."_blocks VALUES ( NULL, '', 'Calendar', 'modules/Calendar/addonblock.php', '', 'l', '8', '1', '3600', '')");

}

if ($addweather) {
$sql = mysql_query("INSERT INTO ".$prefix."_blocks VALUES ( NULL, '', 'Weathers', 'modules/Weather/block.php', '', 'r', '11', '1', '3600', '')");
}

}

function Upgrade441To50() {
global $prefix;

$sql = "CREATE TABLE ".$prefix."_poll_check (
	ip VARCHAR (20) not null , 
	time VARCHAR (14) not null 
)";

if (mysql_query($sql)) echo "Table ".$prefix."_poll_check has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_poll_check</font><br>";

$sql = "CREATE TABLE ".$prefix."_blocks (
	bid INT (10) DEFAULT '0' not null AUTO_INCREMENT, 
	bkey VARCHAR (15) not null , 
	title VARCHAR (60) not null , 
	content TEXT not null , 
	url VARCHAR (200) not null , 
	position VARCHAR (1) not null , 
	weight INT (10) DEFAULT '1' not null , 
	active INT (1) DEFAULT '1' not null , 
	refresh INT (10) DEFAULT '0' not null , 
	time VARCHAR (14) DEFAULT '0' not null , 
	PRIMARY KEY (bid)
)";

if (mysql_query($sql)) echo "Table ".$prefix."_blocks has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_blocks</font><br>";


$result = mysql_query("select title, content from mainblock");
	list($title, $content) = mysql_fetch_row($result);
$result = mysql_query("insert into ".$prefix."_blocks values (NULL, 'main', '$title', '$content', '', 'l', '1', '1', '', '')");
$result = mysql_query("insert into ".$prefix."_blocks values (NULL, 'online', 'Who\'s Online', '', '', 'l', '2', '1', '', '')");


$result = mysql_query("select title, content from adminblock");
	list($title, $content) = mysql_fetch_row($result);
$result = mysql_query("insert into blocks values (NULL, 'admin', '$title', '$content', '', 'l', '3', '1', '', '')");
mysql_query("DROP TABLE adminblock");


$result = mysql_query("insert into blocks values (NULL, 'search', 'Search Box', '', '', 'l', '4', '0', '', '')");
$result = mysql_query("insert into blocks values (NULL, 'ephem', 'Ephemerids', '', '', 'l', '5', '0', '', '')");
$result = mysql_query("insert into blocks values (NULL, 'thelang', 'Languages', '', '', 'l', '6', '1', '', '')");


$result = mysql_query("select title, content from lblocks");
$count = 7;
while(list($title, $content) = mysql_fetch_row($result)) {
    mysql_query("insert into blocks values (NULL, '', '$title', '$content', '', 'l', '$count', '1', '', '')");
    $count++;
}

mysql_query("insert into blocks values (NULL, 'userbox', 'User\'s Custom Box', '', '', 'r', '1', '1', '', '')");
mysql_query("insert into blocks values (NULL, 'category', 'Categories Menu', '', '', 'r', '2', '1', '', '')");
mysql_query("insert into blocks values (NULL, 'random', 'Random Headlines', '', '', 'r', '3', '0', '', '')");
mysql_query("insert into blocks values (NULL, 'poll', 'Surveys', '', '', 'r', '4', '1', '', '')");
mysql_query("insert into blocks values (NULL, 'big', 'Today\'s Big Story', '', '', 'r', '5', '1', '', '')");
mysql_query("insert into blocks values (NULL, 'login', 'User\'s Login', '', '', 'r', '6', '1', '', '')");
mysql_query("insert into blocks values (NULL, 'past', 'Past Articles', '', '', 'r', '7', '1', '', '')");


$result = mysql_query("select title, content from rblocks");
$count = 8;
while(list($title, $content) = mysql_fetch_row($result)) {
    mysql_query("insert into blocks values (NULL, '', '$title', '$content', '', 'r', '$count', '1', '', '')");
    $count++;
}

mysql_query("ALTER TABLE authors DROP radminleft");
mysql_query("ALTER TABLE authors DROP radminright");
mysql_query("ALTER TABLE authors DROP radminmain");
mysql_query("ALTER TABLE authors DROP radminhead");
mysql_query("ALTER TABLE authors DROP radminforum");

mysql_query("ALTER TABLE headlines DROP url");
mysql_query("ALTER TABLE headlines DROP status");


$sql = "CREATE TABLE ".$prefix."_message (
	title VARCHAR (100) not null , 
	content TEXT not null , 
	date VARCHAR (14) not null , 
	expire INT (7) not null , 
	active INT (1) DEFAULT '1' not null , 
	view INT (1) DEFAULT '1' not null 
)";

if (mysql_query($sql)) echo "Table ".$prefix."_message has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_message</font><br>";

mysql_query("ALTER TABLE reviews CHANGE email email VARCHAR (60)");
mysql_query("ALTER TABLE reviews_add CHANGE email email VARCHAR (60)");

mysql_query("ALTER TABLE downloads DROP privs"); /* Does this make any sense? nahhh */

$sql = "CREATE TABLE ".$prefix."_downloads_categories (
  cid int(11) NOT NULL auto_increment,
  title varchar(50) NOT NULL default '',
  cdescription text NOT NULL,
  PRIMARY KEY  (cid)
)";

if (mysql_query($sql)) echo "Table ".$prefix."_downloads_categories has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_downloads_categories</font><br>";

$sql = "CREATE TABLE ".$prefix."_downloads_editorials (
  downloadid int(11) NOT NULL default '0',
  adminid varchar(60) NOT NULL default '',
  editorialtimestamp datetime NOT NULL default '0000-00-00 00:00:00',
  editorialtext text NOT NULL,
  editorialtitle varchar(100) NOT NULL default '',
  PRIMARY KEY  (downloadid)
)";

if (mysql_query($sql)) echo "Table ".$prefix."_downloads_editorials has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_downloads_editorials</font><br>";

$sql = "CREATE TABLE ".$prefix."_downloads_downloads (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  date datetime default NULL,
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  hits int(11) NOT NULL default '0',
  submitter varchar(60) NOT NULL default '',
  downloadratingsummary double(6,4) NOT NULL default '0.0000',
  totalvotes int(11) NOT NULL default '0',
  totalcomments int(11) NOT NULL default '0',
  filesize int(11) NOT NULL default '0',
  version varchar(10) NOT NULL default '0',
  homepage varchar(200) NOT NULL default '',
  PRIMARY KEY  (lid)
)";

if (mysql_query($sql)) echo "Table ".$prefix."_downloads_downloads has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_downloads_downloads</font><br>";

$sql = "CREATE TABLE ".$prefix."_downloads_modrequest (
  requestid int(11) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  modifysubmitter varchar(60) NOT NULL default '',
  brokendownload int(3) NOT NULL default '0',
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  filesize int(11) NOT NULL default '0',
  version varchar(10) NOT NULL default '0',
  homepage varchar(200) NOT NULL default '',
  PRIMARY KEY  (requestid),
  UNIQUE KEY requestid (requestid)
)";

if (mysql_query($sql)) echo "Table ".$prefix."_downloads_modrequest has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_downloads_modrequest</font><br>";

$sql = "CREATE TABLE ".$prefix."_downloads_newdownload (
  lid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  sid int(11) NOT NULL default '0',
  title varchar(100) NOT NULL default '',
  url varchar(100) NOT NULL default '',
  description text NOT NULL,
  name varchar(100) NOT NULL default '',
  email varchar(100) NOT NULL default '',
  submitter varchar(60) NOT NULL default '',
  filesize int(11) NOT NULL default '0',
  version varchar(10) NOT NULL default '0',
  homepage varchar(200) NOT NULL default '',
  PRIMARY KEY  (lid)
)";

if (mysql_query($sql)) echo "Table ".$prefix."_downloads_newdownload has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_downloads_newdownload</font><br>";

$sql = "CREATE TABLE ".$prefix."_downloads_subcategories (
  sid int(11) NOT NULL auto_increment,
  cid int(11) NOT NULL default '0',
  title varchar(50) NOT NULL default '',
  PRIMARY KEY  (sid)
)";

if (mysql_query($sql)) echo "Table ".$prefix."_downloads_subcategories has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_downloads_subcategories</font><br>";

$sql = "CREATE TABLE ".$prefix."_downloads_votedata (
  ratingdbid int(11) NOT NULL auto_increment,
  ratinglid int(11) NOT NULL default '0',
  ratinguser varchar(60) NOT NULL default '',
  rating int(11) NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingcomments text NOT NULL,
  ratingtimestamp datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (ratingdbid)
)";

if (mysql_query($sql)) echo "Table ".$prefix."_downloads_votedata has been created<br>";
else echo "<font color=\"red\">Can not create table ".$prefix."_downloads_votedata</font><br>";


mysql_query("ALTER TABLE authors RENAME ".$prefix."_authors");
mysql_query("ALTER TABLE autonews RENAME ".$prefix."_autonews");
mysql_query("ALTER TABLE banner RENAME ".$prefix."_banner");
mysql_query("ALTER TABLE bannerclient RENAME ".$prefix."_bannerclient");
mysql_query("ALTER TABLE bannerfinish RENAME ".$prefix."_bannerfinish");
mysql_query("ALTER TABLE comments RENAME ".$prefix."_comments");
mysql_query("ALTER TABLE counter RENAME ".$prefix."_counter");
mysql_query("ALTER TABLE ephem RENAME ".$prefix."_ephem");
mysql_query("ALTER TABLE faqAnswer RENAME ".$prefix."_faqAnswer");
mysql_query("ALTER TABLE faqCategories RENAME ".$prefix."_faqCategories");
mysql_query("ALTER TABLE headlines RENAME ".$prefix."_headlines");
mysql_query("ALTER TABLE links_categories RENAME ".$prefix."_links_categories");
mysql_query("ALTER TABLE links_subcategories RENAME ".$prefix."_links_subcategories");
mysql_query("ALTER TABLE links_editorials RENAME ".$prefix."_links_editorials");
mysql_query("ALTER TABLE links_links RENAME ".$prefix."_links_links");
mysql_query("ALTER TABLE links_modrequest RENAME ".$prefix."_links_modrequest");
mysql_query("ALTER TABLE links_newlink RENAME ".$prefix."_links_newlink");
mysql_query("ALTER TABLE links_votedata RENAME ".$prefix."_links_votedata");
mysql_query("ALTER TABLE poll_data RENAME ".$prefix."_poll_data");
mysql_query("ALTER TABLE poll_desc RENAME ".$prefix."_poll_desc");
mysql_query("ALTER TABLE pollcomments RENAME ".$prefix."_pollcomments");
mysql_query("ALTER TABLE priv_msgs RENAME ".$prefix."_priv_msgs");
mysql_query("ALTER TABLE queue RENAME ".$prefix."_queue");
mysql_query("ALTER TABLE quotes RENAME ".$prefix."_quotes");
mysql_query("ALTER TABLE referer RENAME ".$prefix."_referer");
mysql_query("ALTER TABLE related RENAME ".$prefix."_related");
mysql_query("ALTER TABLE reviews RENAME ".$prefix."_reviews");
mysql_query("ALTER TABLE reviews_add RENAME ".$prefix."_reviews_add");
mysql_query("ALTER TABLE reviews_comments RENAME ".$prefix."_reviews_comments");
mysql_query("ALTER TABLE reviews_main RENAME ".$prefix."_reviews_main");
mysql_query("ALTER TABLE seccont RENAME ".$prefix."_seccont");
mysql_query("ALTER TABLE sections RENAME ".$prefix."_sections");
mysql_query("ALTER TABLE session RENAME ".$prefix."_session");
mysql_query("ALTER TABLE stories RENAME ".$prefix."_stories");
mysql_query("ALTER TABLE stories_cat RENAME ".$prefix."_stories_cat");
mysql_query("ALTER TABLE topics RENAME ".$prefix."_topics");
mysql_query("ALTER TABLE users RENAME ".$prefix."_users");
mysql_query("ALTER TABLE ".$prefix."_links_links CHANGE email email VARCHAR (100) not null");
mysql_query("ALTER TABLE ".$prefix."_links_links CHANGE name name VARCHAR (100) not null");
mysql_query("ALTER TABLE ".$prefix."_links_newlink CHANGE email email VARCHAR (100) not null");
mysql_query("ALTER TABLE ".$prefix."_links_newlink CHANGE name name VARCHAR (100) not null");
mysql_query("ALTER TABLE ".$prefix."_reviews CHANGE reviewer reviewer VARCHAR (40)");
mysql_query("DELETE FROM ".$prefix."_counter WHERE type = 'browser' AND var = 'WebTV'");
}

function DataOldCheck() {
CheckingDataOld("access",$start);
CheckingDataOld("adminblock",$start);
CheckingDataOld("authors",$start);
CheckingDataOld("autonews",$start);
CheckingDataOld("banner",$start);
CheckingDataOld("bannerclient",$start);
CheckingDataOld("bannerfinish",$start);
CheckingDataOld("catagories",$start);
CheckingDataOld("comments",$start);
CheckingDataOld("config",$start);
CheckingDataOld("counter",$start);
CheckingDataOld("downloads",$start);
CheckingDataOld("ephem",$start);
CheckingDataOld("faqAnswer",$start);
CheckingDataOld("faqCategories",$start);
CheckingDataOld("forums",$start);
CheckingDataOld("forumtopics",$start);
CheckingDataOld("headlines",$start);
CheckingDataOld("lblocks",$start);
CheckingDataOld("links_categories",$start);
CheckingDataOld("links_editorials",$start);
CheckingDataOld("links_links",$start);
CheckingDataOld("links_modrequest",$start);
CheckingDataOld("links_newlink",$start);
CheckingDataOld("links_subcategories",$start);
CheckingDataOld("links_votedata",$start);
CheckingDataOld("mainblock",$start);
CheckingDataOld("poll_data",$start);
CheckingDataOld("poll_desc",$start);
CheckingDataOld("pollcomments",$start);
CheckingDataOld("posts",$start);
CheckingDataOld("priv_msgs",$start);
CheckingDataOld("queue",$start);
CheckingDataOld("quotes",$start);
CheckingDataOld("ranks",$start);
CheckingDataOld("rblocks",$start);
CheckingDataOld("referer",$start);
CheckingDataOld("related",$start);
CheckingDataOld("reviews",$start);
CheckingDataOld("reviews_add",$start);
CheckingDataOld("reviews_comments",$start);
CheckingDataOld("reviews_main",$start);
CheckingDataOld("seccont",$start);
CheckingDataOld("sections",$start);
CheckingDataOld("session",$start);
CheckingDataOld("stories",$start);
CheckingDataOld("stories_cat",$start);
CheckingDataOld("topics",$start);
CheckingDataOld("users",$start);
CheckingDataOld("users_status",$start);
}

function DataNewCheck() {
CheckingData("authors",$startnew);
CheckingData("autonews",$startnew);
CheckingData("banner",$startnew);
CheckingData("bannerclient",$startnew);
CheckingData("bannerfinish",$startnew);
CheckingData("blocks",$startnew);
CheckingData("comments",$startnew);
CheckingData("counter",$startnew);
CheckingData("downloads_categories",$startnew);
CheckingData("downloads_downloads",$startnew);
CheckingData("downloads_editorials",$startnew);
CheckingData("downloads_modrequest",$startnew);
CheckingData("downloads_newdownload",$startnew);
CheckingData("downloads_subcategories",$startnew);
CheckingData("downloads_votedata",$startnew);
CheckingData("ephem",$startnew);
CheckingData("faqAnswer",$startnew);
CheckingData("faqCategories",$startnew);
CheckingData("headlines",$startnew);
CheckingData("links_categories",$startnew);
CheckingData("links_editorials",$startnew);
CheckingData("links_links",$startnew);
CheckingData("links_modrequest",$startnew);
CheckingData("links_newlink",$startnew);
CheckingData("links_subcategories",$startnew);
CheckingData("links_votedata",$startnew);
CheckingData("message",$startnew);
CheckingData("poll_check",$startnew);
CheckingData("poll_data",$startnew);
CheckingData("poll_desc",$startnew);
CheckingData("pollcomments",$startnew);
CheckingData("priv_msgs",$startnew);
CheckingData("queue",$startnew);
CheckingData("quotes",$startnew);
CheckingData("referer",$startnew);
CheckingData("related",$startnew);
CheckingData("reviews",$startnew);
CheckingData("reviews_add",$startnew);
CheckingData("reviews_comments",$startnew);
CheckingData("reviews_main",$startnew);
CheckingData("seccont",$startnew);
CheckingData("sections",$startnew);
CheckingData("session",$startnew);
CheckingData("stories",$startnew);
CheckingData("stories_cat",$startnew);
CheckingData("topics",$startnew);
CheckingData("users",$startnew);
CheckingDataNew("access",$startnew);
CheckingDataNew("catagories",$startnew);
CheckingDataNew("config",$startnew);
CheckingDataNew("downloads",$startnew);
CheckingDataNew("forums",$startnew);
CheckingDataNew("forumtopics",$startnew);
CheckingDataNew("lblocks",$startnew);
CheckingDataNew("mainblock",$startnew);
CheckingDataNew("posts",$startnew);
CheckingDataNew("ranks",$startnew);
CheckingDataNew("rblocks",$startnew);
CheckingDataNew("users_status",$startnew);
}

function DataAddOnCheck() {
CheckingAddData("glossary",$startadd);
CheckingAddData("guestbook",$startadd);
CheckingAddData("chatbox",$startadd);
CheckingAddData("forums",$startadd);
CheckingAddData("im",$startadd);
CheckingAddData("events",$startadd);
}

function CheckingAddData($data,$startadd) {
global $prefix,$startadd;
$sql = "SELECT * FROM $prefix"._."$data";
	if (mysql_query($sql)) {
		$startadd=$startadd+1;
		echo ".";
	} else {
		echo "<font color=\"red\">.</font>";
	}
}

function CheckingDataNew($data,$startnew) {
global $startnew;
$sql = "SELECT * FROM $data";
	if (mysql_query($sql)) {
		$startnew=$startnew+1;
		echo ".";
	} else {
		echo "<font color=\"red\">.</font>";
	}
}

function CheckingDataOld($data,$start) {
global $start;
$sql = "SELECT * FROM $data";
	if (mysql_query($sql)) {
		$start=$start+1;
		echo ".";
	} else {
		echo "<font color=\"red\">.</font>";
	}
}

function CheckingData($data,$startnew) {
global $prefix,$startnew;

$sql = "SELECT * FROM $prefix"._."$data";
	if (mysql_query($sql)) {
		$startnew=$startnew+1;
		echo ".";
	} else {
		echo "<font color=\"red\">.</font>";
	}
}

function HeaderInstaller($ourstep) {
global $sitename;
?>
<html>
<head>
<title>Installation for <? echo "$sitename"; ?></title>
</head>
<body>

<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td bgcolor="#778899"><h1><font face="arial">&nbsp;&nbsp;Welcome to NukeAddOn Installation</font></h1></td></tr>
<tr><td bgcolor="#333333"><font size="3" color="#ffffff" face="arial">&nbsp;&nbsp;AddOn for the future</font></td></tr>
</table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td valign="top" bgcolor="gray" width="25%"><br>
<table cellpadding="3" cellspacing="0" border="0" width="100%">
<tr><td width="100%" valign="top">

<?
switch($ourstep) {
    case 'language':
	echo "<font color=\"#FFFFFF\"><b>"._STEPONE."</b></font><br>";
	echo ""._STEPTWO."<br>";
	echo ""._STEPTHREE."<br>";
	echo ""._STEPFOUR."<br>";
	echo ""._STEPFIVE."<br>";	
	break;
	
    case 'startout':
	echo "<b>"._STEPONE."</b><br>";
	echo "<font color=\"#FFFFFF\"><b>"._STEPTWO."</b></font><br>";
	echo ""._STEPTHREE."<br>";
	echo ""._STEPFOUR."<br>";
	echo ""._STEPFIVE."<br>";	
	break;

    case 'secondpagesup':
	echo "<b>"._STEPONE."</b><br>";
	echo "<b>"._STEPTWO."</b><br>";
	echo "<font color=\"#FFFFFF\"><b>"._STEPTHREE."</b></font><br>";
	echo ""._STEPFOUR."<br>";
	echo ""._STEPFIVE."<br>";	
	break;

	case 'secondpagesadd':
	echo "<b>"._STEPONE."</b><br>";
	echo "<b>"._STEPTWO."</b><br>";
	echo "<b>"._STEPTHREE."</b><br>";
	echo "<font color=\"#FFFFFF\"><b>"._STEPFOUR."</b></font><br>";
	echo ""._STEPFIVE."<br>";
	break;

	case 'finish':
	echo "<b>"._STEPONE."</b><br>";
	echo "<b>"._STEPTWO."</b><br>";
	echo "<b>"._STEPTHREE."</b><br>";
	echo "<b>"._STEPFOUR."</b><br>";
	echo "<font color=\"#FFFFFF\"><b>"._STEPFIVE."</b></font><br>";
	break;

}
?>

</td></tr>
</table>

</td><td>&nbsp;</td><td width="79%"><br>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td>
<?
}

function FooterInstaller() {
?>
</td></tr></table>
</td></tr></table>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tr><td align="center"><hr><small>
Installer version 1.01.beta<br>
Copyright © 2001 <A href="http://www.nukeaddon.com">NukeAddOn.com</a></small></td></tr>
</table>
</body></html>
<?
}

?>