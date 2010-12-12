<?php

// MySQL connection block
// MAKE SURE TO EDIT THIS OR YOUR CONTROL PANEL WILL NOT WORK
$host = "localhost";
$username = "system_cp";
$password = "r0mLaboJHJOl5aajbLaR";
$db_name = "system_cp";
$tbl_name = "main";

// next three lines connects to your db, and if the connection fails, dies and gives a reason
$db = new mysqli($host, $username, $password, $db_name);
if (mysqli_connect_errno()) { 
	die("error: mysqli(): " . mysql_connect_error());
}

// this is for restricting user permissions
// format is:
//	  $restrict_users = array('username');
// you can put more than one or two users, and the array can look like
// this:
//	  $restrict_users = array('username', 'other_username', 'next_user');
// make sure that the username is in single quotes (') and
// each separate element is comma-separated

$restrict_users = array('test-session');

// this next setting is for GLOBAL user restriction.
// i don't see why you would EVER want to enable this, it COMPLETELY
// disables access for users in the above array, all they can see
// is the home page.

$global_restrictions = 'off';

?>