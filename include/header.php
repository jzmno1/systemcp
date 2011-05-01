<?php

if ($_GET['error'] == "access_denied") {
    if ($_GET['sql_err'] != "") {
        $mysql_error = $_GET['sql_err'];
    }
    echo '
    <html>
        <head>
            <title>
                SystemCP
            </title>
	<div align="center">
        <a href="panel.php?p=home"><img src="style/img/logo.png"></a>
    </head>
    </div>
    <div align="left">
    <body>
    <link href="style/body.css" rel="stylesheet" type="text/css">
    <br /><br />
    <strong>Access Denied</strong>
    <br /><pre>';
    if ($_GET['sql_err'] != "") {
        echo $mysql_error;
    }
    echo '
    </pre>
    <br />
    <br />
    <a href="index.html">Back to Login</a>
    </div>
    </html>';
} else {

    if (isset($_GET['ref'])) {
        $ref = $_GET['ref'];
        $title = $ref;
        $title = ucwords(strtolower($title));
    } elseif (!isset($_GET['ref'])) {
        $title = $_GET['p'];
        $title = ucwords(strtolower($title));
    }

    echo '
    <html>
        <head>
            <title>
                System CP - ' . $title;
    echo ' </title>
	<div align="center">
    ';
    echo '      <script type="text/javascript" src="http://code.jquery.com/jquery-1.4.4.js"></script>
        	<script type="text/javascript">
		
		$(document).ready(function(){
 			$("#showSimpleModal").click(function() {
 				$("div#simpleModal").addClass("show");
 				return false;
 			});
 			$("#showFancyModal").click(function() {
 				$("div#fancyModal").addClass("show");
 				return false;
 			});

 			$("#closeSimple").click(function() {
 				$("div#simpleModal").removeClass("show");
 				return false;
 			});
 			$("#closeFancy").click(function() {
 				$("div#fancyModal").removeClass("show");
 				return false;
 			});
		});
	</script>';
    echo '
        <div id="fancyModal" class="modal"><div align="left">
        <strong><font color="black">User Options</font></strong><br><br>
        <a href="options.php?page=general" class="medium awesome blue">Preferences</a>
        <br><br>';
    if ($_SESSION['permission'] == "administrator") {
        echo '<a href="panel.php?p=admin" class="medium awesome orange">Administration</a><br><br>';
    }
    echo ' <a href="panel.php?funct=logout" class="medium awesome yellow">Logout</a>
        <br><br><div align="right"><a href="" class="medium awesome magenta" id="closeFancy">Close</a></div>
        </div></div>
        <div align="left"><a href="" class="medium awesome" id="showFancyModal">' . $_SESSION['myusername'] . '</a></div> </div>
        <div align="center"><a href="panel.php?p=home"><img src="style/img/logo.png"></a>
    </head>
    <body>
    <link href="style/body.css" rel="stylesheet" type="text/css">
    <link href="style/style.css" rel="stylesheet" type=text/css">
    </div><div align="left">
    <br />
    <a class="medium awesome magenta" href="panel.php?p=processes">Processes</a> -
    <a class="medium awesome blue" href="panel.php?p=status">System Status</a> -
    <a class="medium awesome magenta" href="panel.php?p=power">System Power</a> -
    <a class="medium awesome blue" href="panel.php?p=initscripts">Initscripts</a> -
    <a class="medium awesome magenta" href="users.php?ref=users">User Management</a> -
    <a class="medium awesome blue" href="panel.php?p=utilities">Utilities</a> -
    <a class="medium awesome magenta" href="panel.php?p=customize">Customize</a> -
    <a class="medium awesome yellow" href="logging.php?page=view&ref=logging">Logging</a>
    </div>
    <br />
    </html>';

    include ('include/config.php');
    if ($global_restrictions == "on") {
        include ('restrictions.php');
    }
}
?>
