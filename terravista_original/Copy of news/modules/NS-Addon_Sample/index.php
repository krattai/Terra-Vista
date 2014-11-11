<?php

if (!eregi("modules.php", $PHP_SELF)) {
    die ("You can't access this file directly...");
}

$index = 1;

function one() {
    include("header.php");
    OpenTable();
    echo "Addon Sample File (index.php) function \"one\"<br><br>";
    echo "<ul>";
    echo "<li><a href=\"modules.php?op=modload&amp;name=NS-Addon_Sample&amp;file=index\">Go to index.php</a>";
    echo "</ul>";
    CloseTable();
    include("footer.php");

}

function two() {
    include("header.php");
    OpenTable();
    echo "Addon Sample File (index.php) function \"two\"";
    echo "<ul>";
    echo "<li><a href=\"modules.php?op=modload&amp;name=NS-Addon_Sample&amp;file=index\">Go to index.php</a>";
    echo "</ul>";
    CloseTable();
    include("footer.php");

}


function AddonSample() {
    include("header.php");
    OpenTable();
    echo "Addon Sample File (index.php)<br><br>";
    echo "<ul>";
    echo "<li><a href=\"modules.php?op=modload&amp;name=NS-Addon_Sample&amp;file=index&amp;func=one\">Function One</a>";
    echo "<li><a href=\"modules.php?op=modload&amp;name=NS-Addon_Sample&amp;file=index&amp;func=two\">Function Two</a>";
    echo "<li><a href=\"modules.php?op=modload&amp;name=NS-Addon_Sample&amp;file=f2\">Call to file f2.php</a>";
    echo "</ul>";
    echo "This Addon is Hidden from the Main Menu block. If you want your addon link on the Main Menu block just "
         ."rename the directory name from <i>NS-Addon_Sample</i> to <i>Addon_Sample</i> and a new link to "
	 ."this addon will be added automaticaly.";
    CloseTable();
    include("footer.php");
}

switch($func) {

    default:
    AddonSample();
    break;
    
    case "one":
    one();
    break;

    case "two":
    two();
    break;

}

?>