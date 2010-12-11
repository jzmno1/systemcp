<?php

// sycp's easy installer

echo '<html>
<center><img src="../style/img/logo.png"></center>
<link href="../style/body.css" rel="stylesheet" type="text/css">';

if ($_GET['page'] == "config") {
    if ($_GET['act'] == "write") {
        include('../include/functions/writer.php');

        $host = $_POST['host'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $database = $_POST['database'];
        $table = $_POST['table'];
        $adm_u = $_POST['adm_u'];
        $adm_p = $_POST['adm_p'];

        echo '<strong> Testing Connection... </strong>';
        $db = new mysqli($host, $username, $password, $database);
        if (mysqli_connect_errno ()) {
            echo mysqli_connect_error();
            sleep(10);
            header("location:install.php?page=config");
            die;
        } else {
            echo "Confirmed, writing to config.";
            continue;
        }

        writer('../include/config.php', 'a+', '/\$host/', '$host = "' . $host . '";');
        writer('../include/config.php', 'a+', '/\$username/', '$username = "' . $username . '";');
        writer('../include/config.php', 'a+', '/\$password/', '$password = "' . $password . '";');
        writer('../include/config.php', 'a+', '/\$db_name/', '$db_name = "' . $database . '";');
        writer('../include/config.php', 'a+', '/\$tbl_name/', '$tbl_name = "' . $table . '";');

        echo '<strong>config.php has been written</strong>';

        echo '<form name="next" method="post" action="install.php?page=table&tbl_name="' . $table . '">';
        echo '<input name="next" type="submit" id="next" value="Next">';
        echo '</form>';
    } else {
        $database_config = array('host', 'username', 'password', 'database', 'table');

        echo '<form name="db_conf" method="post" action="install.php?page=config&act=write">';
        foreach ($database_config as $config_item) {
            echo '<input name="' . $config_item . '" type="text" id="' . $config_item . '">';
            echo '<br />';
        }
        echo '<br /><br />';
        echo 'Admin Username: <input name="adm_u" type="text" id="adm_u">';
        echo 'Admin Password: <input name="adm_p" type="text" id="adm_p">';
        echo '<input name="next" type="submit" id="next" value="Next"></form>';
    }
} elseif ($_GET['page'] == "table") {
    $adm_u = $_POST['adm_u'];
    $adm_p = $_POST['adm_p'];
    $tabel = $_GET['table'];

    echo 'Creating table...';

    $db->query('CREATE TABLE IF NOT EXISTS `' . $table . '` (
	`username` varchar(30) NOT NULL,
	`password` varchar(32) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;');

    echo 'Table created.';

    echo 'Inserting data...';

    $db->query('INSERT INTO `' . $table . '` (
	`username`,
	`password` )
	VALUES (
	\'' . $adm_u . '\', MD5(' . $adm_p . '));');

    echo 'Data inserted.';

    $db->close();

    echo 'Database connection closed.';

    echo '<form name="next" method="post" action="install.php?page=permissions">';
    echo '<input name="next" type="submit" id="next" value="Next">';
    echo '</form>';
} elseif ($_GET['page'] == "permissions") {
    echo 'Fixing permissions on everything...';

    echo nl2br(passthru('chmod -v 755 ../*.php;chmod -Rv 755 ../sys;chmod -Rv 755 ../style;chmod -Rv 755 ../include;chmod -v 644 ../include/config.php'));

    echo '<strong>Done.';
    echo '<br>Installation is finished.<br>';

    echo '<form name="next" method="post" action="index.html">';
    echo '<input name="next" type="submit" id="next" value="Finish:>';
    echo '</form>';
} else {

    echo '<div align="left">
           <strong> Welcome to the installer for SystemCP </strong>
          </div>';

    sleep(5);
    header("location:install.php?page=config");
}
?>