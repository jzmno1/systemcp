<?php

session_start();

date_default_timezone_set('America/Chicago');

if (!isset($_SESSION['myusername'])) {
    header("location:index.html");
}

if ($_GET['page'] == "view") {
    $fname = 'logs/' . date('Ymd') . '.log';
    include ('include/header.php');
    include ('restrictions.php');
    $handle = fopen($fname, 'c+');
    $logs = fread($handle, filesize($fname));
    echo '<html><div align="left"><strong>Log Filename: logs/' . date("Ymd") . '</strong><br /><br />';
    $logs = nl2br($logs);
    $logs = preg_replace('/IP:/', '<strong>IP:</strong>', $logs);
    $logs = preg_replace('/Request URI:/', '<strong>Request URI:</strong>', $logs);
    $logs = preg_replace('/Login Status:/', '<strong>Login Status:</strong>', $logs);
    $logs = preg_replace('/Date\/Time:/', '<strong>Date/Time:</strong>', $logs);
    echo $logs;
    echo '</div>';
    echo '<br /><br />
    	    <form name="log_actions" method="post" action="logging.php?function=log_operations">
    	    <input class="medium awesome blue" value="View Raw Log" name="log_rv" id="log_rv" type="submit">
    	    <input class="medium awesome blue" value="List Logs" name="log_li" id="log_li" type="submit">
    	    <input class="medium awesome yellow" value="Clear this log" name="log_cl" id="log_cl" type="submit">
    	    <input class="medium awesome orange" value="Purge Todays Logfile" name="log_dl" id="log_dl" type="submit">
    	    <input class="medium awesome red" value="Purge All Logfiles" name="log_adl" id="log_adl" type="submit">
    	    </form>';
    
    fclose($handle);
} elseif ($_GET['function'] == "write") {
    if (!isset($_GET['login_status'])) {
    	$login_status = "failed";
    } else {
    	$login_status = $_GET['login_status'];
    }
    $fname = 'logs/'. date('Ymd') . '.log';
    touch($fname);
    $handle = fopen($fname, 'a');
    $logs_str = "IP: " . $_SERVER['REMOTE_ADDR'] . " || Request URI: " . $_GET['request_uri'] . " || Login Status: " . $login_status . " || Date/Time: " . date('g:i:s a|Y/m/d') . "\n\n";
    fwrite($handle, $logs_str);
    fclose($handle);
    if ($login_status == "failed") {
        header("location:include/header.php?error=access_denied&sql_err=" . mysqli_connect_error());
    } else {
        header("location:panel.php?p=home");
    }
} elseif ($_GET['function'] == "log_operations") {
    include ('restrictions.php');
    
    if (isset($_POST['log_rv'])) {
        header("location:logs/" . date('Ymd'));
    } elseif (isset($_POST['log_cl'])) {
        $handle = fopen('logs/' . date('Ymd'), 'r+');
        ftruncate($handle, 0);
        fclose($handle);
        header("location:logging.php?page=view");
    } elseif (isset($_POST['log_dl'])) {
        shell_exec('rm -f logs/' . date('Ymd'));
        header("location:logging.php?page=view");
    } elseif (isset($_POST['log_adl'])) {
    	shell_exec('rm -f logs/*.log');
        header("location:logging.php?page=view");
    } elseif (isset($_POST['log_li'])) {
        header("location:logging.php?page=list&ref=logging");
    }
} elseif ($_GET['page'] == "list") {
    include ('include/header.php');
    include ('restrictions.php');
    
    exec('ls logs/', $log_list);
    
    echo '<strong><br />Stored Logs<br /><br /></strong>';
    
    foreach($log_list as $log) {
    	echo '<a class="small awesome green" href="logs/' . $log . '">logs/' . $log . '</a>';
    	echo '<br /><br />';
    }
    
    echo '<a class="medium awesome orange" href="logging.php?page=view&ref=logging">Back to Logging</a>';
    
}

?>