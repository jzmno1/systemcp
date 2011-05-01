<?php

session_start();
include ('include/config.php');

// username and password sent from form 
$myusername = $_POST['myusername'];
$mypassword = $_POST['mypassword'];

// To protect from MySQL injection
$myusername = stripslashes($myusername);
$mypassword = stripslashes($mypassword);
$myusername = $db->real_escape_string($myusername);
$mypassword = $db->real_escape_string($mypassword);

// encrypt password
$encrypted_mypassword = md5($mypassword);

// query
$result = $db->query("SELECT * FROM `$tbl_name` WHERE `username`='$myusername' and `password`='$encrypted_mypassword'");

// this is to setup for user permission checking later on
$permission = $result->fetch_assoc();
$permission = $permission['permissions'];

// count the rows to make sure login is good
$count = $result->num_rows;
// If result matched $myusername and $mypassword, row count must be 1

if ($count == 1) {
    $_SESSION['myusername'] = $myusername;
    $_SESSION['mypassword'] = $encrypted_mypassword;
    $_SESSION['permission'] = $permission;
    $result->close();
    $db->close();
    header('location:logging.php?function=write&request_uri=' . $_SERVER['REQUEST_URI'] . '&login_status=logged_in_as_' . $_SESSION['myusername']);
} else {
    header('location:logging.php?function=write&request_uri=' . $_SERVER['REQUEST_URI']);
}
?>
