<?php

include('../include/config.php');
$db->close();

echo '<html>
    <center><img src="../style/img/logo.png"></center>
    <link href="../style/body.css" rel="stylesheet" type="text/css">';

if ($_GET['page'] == "user") {
    echo '
        <form name="user_conf" method="post" action="index.php?page=finish">
        Username: <input name="username" id="username" type="text" />
        Password: <input name="password" id="password" type="password" />
        <input name="finish" id="finish" value="Finish" type="submit" />
        </form>
        ';
    die();
} elseif ($_GET['page'] == "finish") {
    echo 'Connecting to database...';
    $db = new mysqli($host, $username, $password, $db_name);
    if (mysqli_connect_errno()) {
        echo mysqli_connect_error();
        die();
    } else {
        $username = $_POST['username'];
        $password = $_POST['password'];
        echo 'Writing user to database...';
        $db->query('INSERT INTO `'. $db_name .'`.`'. $tbl_name .'`(`username`,`password`,`permissions`) VALUES ("' . $username . '",MD5("' . $password . '"),"administrator");');
        if (mysqli_errno($db)) {
            echo mysqli_error($db) . '<br>';
            die();
        }
        echo 'User inserted.';
        $db->close();
        echo '<br>
            <form name="finish" method="post" action="../index.html"><input type="submit" id="finish" name="finish" value="Finish" /></form>';
        die();
    }
}
if ($host == '') {
    echo 'Please adjust ../include/config.php before attempting to install.';
    die();
}

echo '<strong> Testing MySQLi Connection... </strong><br>';
$db = new mysqli($host, $username, $password, $db_name);
if (mysqli_connect_errno()) {
    echo mysqli_connect_error();
    die();
} else {
    echo 'Successfully connected to database.<br>';
}

echo 'Creating table...<br>';

$db->query('CREATE TABLE IF NOT EXISTS `' . $db_name .'`.`' . $tbl_name . '` (`username` varchar(30) NOT NULL, `password` varchar(32) NOT NULL, `permissions` varchar(30) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1;');
if (mysqli_errno($db)) {
    echo mysqli_error($db) . '<br>';
    die();
}
echo 'Table created.<br>';

$db->close();

echo '
    <form method="post" name="continue" action="index.php?page=user">
    <input type="submit" name="continue" id="continue" value="Continue Installation" />
    </form>';

?>