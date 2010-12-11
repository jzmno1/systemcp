<?php

session_start();
if (!isset($_SESSION['myusername'])) {
    header("location:index.html");
}

include ('include/header.php');
$password = $_POST['password_input'];
$password = stripslashes($password);

$encrypt_pass = shell_exec("echo " . $password . " | md5sum | sed -e 's/-//' | tr -d [:space:]");

echo '<strong>Password MD5 Hash</strong>';
echo '<div align="left"><pre>';
echo $encrypt_pass;
echo '</pre></div></strong>';
unset($password);
unset($encrypt_pass);
echo '<center><a href="panel.php?p=utilities">Return to Panel</a></center>';
?>
