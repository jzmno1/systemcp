<?php

session_start();
if (!isset($_SESSION['myusername'])) {
    header("location:index.html");
}

if ($_GET['p'] == "processes") {
    include ('include/header.php');
    $ref = $_GET['p'];

    echo '<html>
    <div align="left">
    <form align="left" name="killform" method="post" action="panel.php?funct=killproc&ref=' . $ref . '">
        <td>
            <table width="40%" border="0" cellpadding="4" cellspacing="1">
                <tr>
                    <td width="70"><font color="white">ProcessID to kill</font></td>
                    <td width="40"><input name="PID" type="input-text" id="PID"></td>
                </tr>
		<tr>
		    <td width="70"><font color="white">Signal to Send</font></td>
		    <td width="40"><select name="kill_signal" id="kill_signal">';
                    $signal_array = array('KILL', 'TERM', 'STOP', 'SEGV', 'BUS', 'INT', 'ALRM', 'ILL', 'QUIT', 'CHLD', 'PIPE', 'FPE', 'ABRT', 'SYS', 'TRAP', 'CONT');
                    foreach($signal_array as $signal) {
                    	echo '<option value="' . $signal . '">' . $signal .'</option>';
                    }
                    echo '</select></td>
                </tr>                                                                                                                        
                <tr>
		    <td>&nbsp;</td>
                    <td><input class="small awesome red" id="send_sig" type="submit" name="send_sig" value="Send Signal"></td>
                </tr>
            </table>
        </td>
    </form>
    </div>';

    echo '<div align="right"><form align="right" name="niceform" method="post" action="panel.php?funct=niceproc&ref=' . $ref . '">
        <td>
            <table width="40%" border="0" cellpadding="4" cellspacing="1">
                <tr>
                    <td width="70"><font color="white">ProcessID to Nice</font></td>
                    <td width="40"><input name="PID" type="input-text" id="PID"></td>
                </tr>
		<tr>
		    <td width="70"><font color="white">Nice level</font></td>
		    <td width="40"><select name="nice_level" id="nice_level">';
                    $nice_level_c = array('20 (Lowest Priority)', '19', '18', '17', '16', '15', '14', '13', '12', '11', '10', '9', '8', '7', '6', '5', '4', '3', '2', '1', '0', '-1', '-2', '-3', '-4', '-5', '-6', '-7', '-8', '-9', '-10', '-11', '-12', '-13', '-14', '-15', '-16', '-17', '-18', '-19', '-20 (Highest Priority)');
                    foreach($nice_level_c as $niceness) {
                    	echo '<option value="' . $niceness . '">' . $niceness .'</option>';
                    }
                    echo '</select></td>
                </tr>                                                                                                                        
                <tr>
		    <td>&nbsp;</td>
                    <td><input class="small awesome red" type="submit" name="Renice" id="renice" value="Renice"></td>
                </tr>
            </table>
        </td>
    </form></div>';


    echo '<html><p><pre>';
    $running_procs = shell_exec("ps ax | sed -e 's/S<s/S< s/g'");
    $running_procs = str_replace('S< s', 'S< s', $running_procs);
    echo nl2br($running_procs);
    ' </pre></p><br />';
} elseif ($_GET['p'] == "status") {
    include ('include/header.php');

    echo '<html><p>';
    $uptime = shell_exec("uptime");
    $diskspace = shell_exec("df");
    $users = shell_exec("w");
    $hostname = shell_exec("uname -n");
    $kernel_version = shell_exec("uname -r");
    $free_mem = shell_exec("free -m -l");
    $swaps = shell_exec("cat /proc/swaps");
    $interfaces = shell_exec("sys/inet.sh");

    echo '<br /><br /><strong>Hostname:<h1>';
    echo nl2br($hostname);
    echo '</h1></strong>';
    echo '<br /><br /><strong>Current Running Kernel:</strong>';
    echo '<pre>';
    echo nl2br($kernel_version);
    echo '</pre>';
    echo '<br /><br /><strong>System Uptime and Load Average:</strong>';
    echo '<pre>';
    echo nl2br($uptime);
    echo '</pre>';
    echo '<br /><br /><strong>Users:</strong>';
    echo '<pre>';
    echo nl2br($users);
    echo '</pre>';
    echo '<br /><br /><strong>Disk Space:</strong>';
    echo '<pre>';
    echo nl2br($diskspace);
    echo '</pre>';
    echo '<br /><br /><strong>Swap Partitions:</strong>';
    echo '<pre>';
    echo nl2br($swaps);
    echo '</pre>';
    echo '<br /><br /><strong>Free Memory (in MB):</strong>';
    echo '<pre>';
    echo nl2br($free_mem);
    echo '</pre>';
    echo '<br /><br /><br /><strong>Interfaces:</strong>';
    echo '<pre>';
    echo nl2br($interfaces);
    echo '</pre>';
    echo '</p></html>';
} elseif ($_GET['p'] == "power") {
    include ('restrictions.php');
    include ('include/header.php');

    if ($_GET['type'] == "shutdown") {
        echo '<pre>';
        system("shutdown -h now shutdown commenced by systemcp");
        echo '</pre>';
    } elseif ($_GET['type'] == "reboot") {
    	echo '<pre>';
        system("reboot");
        echo '</pre>';
    } else {
        echo '<html>
    		  <div align="center">
                  <br />
	    	      <a class="large awesome red" href="panel.php?p=power&type=shutdown">Shutdown</a>
    		      <br /><br />
	    	      <a class="large awesome green" href="panel.php?p=power&type=reboot">Reboot</a>
    		      <br />
	    	  </div>
    		  </html>';
    }
} elseif ($_GET['p'] == "utilities") {
    include ('include/header.php');

    // password hasher
    echo '<html>
            <strong>Password Hasher</strong>    
            <form name="pass_hasher" method="post" action="hash.php">

            <table border="0" cellpadding="2">
            <td>Password: <input name="password_input" type="password" id="hashpassword"></td>
            &nbsp;
            <td><input name="submit_pass" type="submit" id="Submit" value="Hash"></td>
            <br />
            </table>
            </form>';
} elseif ($_GET['p'] == "initscripts") {
    include ('restrictions.php');
    include ('include/header.php');
    $initsclist = shell_exec("ls /etc/init.d");

    $initlist = exec("ls /etc/init.d", $initscript_array);

    echo '<html>
		  <div align="right">';
    echo '<form align="right" name="initscript_list" method="post" action="panel.php?funct=initscript">
		  <strong>Initscript List</strong>
		  <br />
			<select name="initscript_selection" id="initscript_selection">';

    foreach ($initscript_array as $initscript) {
        echo '	<option value="' . $initscript . '">' . $initscript . '</option>';
    }

    echo '  </select>
		  <br />';

    $is_act_array = array('start', 'stop', 'restart', 'reload', 'status', 'force-restart', 'force-reload');

    echo '<br />
		  <strong><a>Initscript Actions</strong>
		  <br />
			<select name= "initscript_action" id="initscript_action">';

    foreach ($is_act_array as $is_action) {
        echo '	<option value="' . $is_action . '">' . $is_action . '</option>';
    }

    echo '	</select>
	
		  <td><br><br><input class="medium awesome blue" type="submit" name="initscript_run" value="Run"></a></td> 
		  </form>
		  </div><div align="left">
		  <pre>';
    echo nl2br($initsclist);
    echo '
		  </div></pre>
		  </html>';
} elseif ($_GET['p'] == "customize") {
    include ('include/header.php');

    echo '<html>
            <form enctype="multipart/form-data" name="custom_image" method="post" action="panel.php?funct=customize_image_processor">
            
            <table border="0" cellpadding="2">
            <td><input class="small awesome red" name="image_filename" type="file" id="image_filename"></td>
            &nbsp;
            <td><input class="small awesome orange" name="upload_image" type="submit" id="Upload" value="Upload"></td>
            <br />
            </table>
            </form>
            Filetypes: GIF, JPG/JPEG, PNG';
            
    // now a defaulter   
    echo '<html>
    		<br /><br />
    		<strong>Default to original background</strong>
    	    <form name="defaulter" method="post" action="panel.php?funct=default_background">
    	    
    	    <table border="0" cellpadding="2">
    	    <br />
    	    <td><input class="small awesome blue" name="activate_defaulter" type="submit" value="Switch to default background"></td>
    	    <br />
    	    </table>
    	    </form>';
    	    
    // now a blanker  
    echo '<html>
    		<br /><br />
    		<strong>Disable background</strong>
    	    <form name="disable_background" method="post" action="panel.php?funct=disable_background">
    	    
    	    <table border="0" cellpadding="2">
    	    <br />
    	    <td><input class="small awesome blue" name="disable_bg" type="submit" value="Disable background"></td>
    	    <br />
    	    </table>
    	    </form>';
    // now a font colour editor
    
   echo '<html>
   		<br />
   		<strong>Font Colour</strong><br />
   		Colour must be in hex value (eg., #00FF00)
   	   <form name="font_hex_sub" method="post" action="panel.php?funct=set_font">
   	   
   	   <table border="0" cellpadding="2">
   	   <td><input name="font_box" type="input-text" id="font_box" value="#"></td>
   	   <br />
   	   <td><input class="small awesome blue" name="submit_font" type="submit" id="submit_font" value="Set font colour"></td>
   	   <br />
   	   </table>
   	   </form>';

    // now a background colour editor
    
   echo '<html>
   		<br />
   		<strong>Background Colour</strong><br />
   		Colour must be in hex value (eg., #494843)
   	   <form name="bg_hex_sub" method="post" action="panel.php?funct=set_bg_colour">
   	   
   	   <table border="0" cellpadding="2">
   	   <td><input name="colour_box" type="input-text" id="colour_box" value="#"></td>
   	   <br />
   	   <td><input class="small awesome blue" name="submit_bg" type="submit" id="submit_bg" value="Set background colour"></td>
   	   </table>
   	   </form>';

} elseif ($_GET['p'] == "home") {
    include ('include/header.php');
    include ('include/config.php');
    
    // MySQLi Info
    
    echo '<html>
    <br /><br />
    <div align="left">
    <table align="left">
    	<strong>MySQL Server Status</strong>
	<br />
	<pre>';
	echo 'MySQLi Version: ' . $db->server_info;
	echo '<br />';
	echo 'MySQLi Protocol: ' . $db->protocol_version;
	echo '<br />';
	echo 'MySQLi Host: ' . $db->host_info;
	echo '<br />';
	echo 'MySQLi Statistics: <br /><br />';
	$mysqli_status = str_replace('  ', '<br />	', $db->stat());
	echo '	' . $mysqli_status;
    echo '
    </pre>
    </table>
    </div>';
    
    // PHP Info
    echo '
    <div align="left">
    <table align="left">
        <strong>PHP Info</strong>
        <br />
        <pre>';
        echo 'PHP Version: ' . phpversion() . '<br />';
	echo 'Zend Engine Version: ' . zend_version();
    echo '
    </pre>
    </table>
    </div>';
    
    // Basic system info
    echo '
    <div align="left">
    <table align="left">
        <strong>Basic System Info</strong>
        <br />
        <pre>';
        $bash_version = shell_exec('bash --version');
        $bash_version = preg_replace('/Copyright(.*)/', null, $bash_version);
        echo "Bash version: " . $bash_version;
        echo "Kernel version: " . shell_exec("uname -r");
        echo '
    </pre>
    </table>
    </div>';
} elseif ($_GET['funct'] == "niceproc") {
    include ('include/header.php');
    include ('restrictions.php');

    $pid = $_POST['pid'];
    $nice_inc = $_POST['nice_level'];
    $pid = shell_exec("echo " . $pid . " | tr -d [:alpha:]");
    if ($pid == "") {
        header("location:panel.php?p=processes");
    }
    $return = shell_exec("renice -n " . $nice_inc . " " . $pid);
    $return = $return;
    echo '<html><p><pre>';
    echo 'Returned: ' . $return;

    echo '</pre></p>';
    echo '<a href="panel.php?p=processes">Back to Processes</a>';
} elseif ($_GET['funct'] == "killproc") {
    include ('include/header.php');
    include ('restrictions.php');

    $pid = $_POST['PID'];
    $kill_signal = $_POST['kill_signal'];
    $pid = shell_exec("echo " . $pid . " | tr -d [:alpha:]");
    if ($pid == "") {
        header("location:panel.php?p=processes");
    }
    $return = shell_exec("kill -" . $kill_signal . " " . $pid);
    if ($return == NULL) {
        $return = $kill_signal . "'ED";
    } else {
        $return = $return;
    }
    echo '<html><p><pre>';
    echo 'Returned: ' . $return;

    echo '</pre></p>';
    echo '<a href="panel.php?p=processes">Back to Processes</a>';
    
} elseif ($_GET['funct'] == "customize_image_processor") {
    include ('include/header.php');

    $uploaddir = '/tmp';
    $uploadfile = $_FILES['image_filename']['tmp_name'];
    $final_name = $_FILES['image_filename']['name'];
    $final_path = 'style/img/' . $final_name;
    move_uploaded_file($uploadfile, $final_path);
    list($width, $height, $type, $attr) = getimagesize($final_path);
    switch ($type) {
        case 1:
            $ext = ".gif";
            break;
        case 2:
            $ext = ".jpg";
            break;
        case 3:
            $ext = ".png";
            break;
        default:
            echo "File is not in GIF, JPG/JPEG, or PNG format.";
            echo "<br />";
            exit();
    }

    if (!file_exists('style/img/default.png')) {
    	shell_exec('mv style/img/background.png style/img/default.png');
    }
    
    include ('include/functions/writer.php');
    
    writer('style/body.css', 'a+', '/background-image:url(.*)/', 'background-image:url(img/' . $final_name . ');');

    shell_exec('chmod 755 ' . $final_path);

    echo 'Image uploaded, CSS written.';

} elseif ($_GET['funct'] == "default_background") {
	include ('include/header.php');
	include ('include/functions/writer.php');
	
	if (file_exists('style/img/default.png')) {
	    writer('style/body.css', 'a+', '/background-image:url(.*)/', 'background-image:url(img/default.png);');
	} else {
	    writer('style/body.css', 'a+', '/background-image:url(.*)/', 'background-image:url(img/background.png);');
	}
		
	echo '<br /><br /><strong>CSS updated to default background</strong>';
	
} elseif ($_GET['funct'] == "disable_background") {
	include ('include/header.php');
	include ('include/functions/writer.php');
	
	writer('style/body.css', 'a+', '/background-image:url(.*)/', 'background-image:url();');
	
	echo '<br /><br /><strong>CSS updated</strong>';

} elseif ($_GET['funct'] == "set_font") {
	include ('include/header.php');
	include ('include/functions/writer.php');
	
	$text_colour = $_POST['font_box'];
	
	writer('style/body.css', 'a+', '/color:(.*)} \/\* font \/\*/', 'color: ' . $text_colour . ';} /* font */');
	
	echo '<br /><br /><strong>CSS updated</strong>';
} elseif ($_GET['funct'] == "set_bg_colour") {
	include ('include/header.php');
	include ('include/functions/writer.php');
	
	$bg_colour = $_POST['colour_box'];
	
	writer('style/body.css', 'a+', '/background-color:(.*)/', 'background-color: ' . $bg_colour . ';');
	
	echo '<br /><br /><strong>CSS updated</strong>';
} elseif ($_GET['funct'] == "initscript") {
    include ('include/header.php');

    $initscript = $_POST['initscript_selection'];
    $run_action = $_POST['initscript_action'];

    echo '<html><pre>';
    echo nl2br(passthru('sys/is.sh ' . $initscript . ' ' . $run_action . ''));
    echo '</pre></html>';
} elseif ($_GET['funct'] == "logout") {
    include ('include/header.php');
    session_destroy();
    echo '<html>
          <p>
            You have been logged out. <a class="medium awesome yellow" href="index.html">Click here to return to the login screen.</a>
          </p>
          </html>';
} else {
    header('location:panel.php?p=home');
}
?>
