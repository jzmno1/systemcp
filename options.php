<?php

session_start();
if (!isset($_SESSION['myusername'])) {
	header ("location:index.html");
}

if ($_GET['page'] == "general") {
	include ('include/header.php');	
			
	echo '<div align="left"><strong>User Preferences</strong></div>
                <br><br>
                <a class="large awesome magenta">Change Password</a>
                <form name="change_pass" method="post" action="options.php?post=userpass">
                  Old Password: <input name="opass" id="opass" type="password">
                  New Password: <input name="chpass" id="chpass" type="password">
                  <input type="submit">
                </form>';
                
}

?>