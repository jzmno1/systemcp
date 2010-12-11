<?php

session_start();
if (!isset($_SESSION['myusername'])) {
    header("location:index.html");
}

if (!isset($_GET['p']) && !isset($_GET['do'])) {
    include ('include/header.php');
    include ('include/sidebar.user.php');

} elseif ($_GET['p'] == "add") {
    include ('include/header.php');
    include ('restrictions.php');
    
    if (isset($_GET['adv'])) {
    	echo '<form name="adv_adduser" method="post" action="users.php?do=useradd&adv&ref=users" align="left">
    		  <table>
    		  <tr>
    		       <td>Account Name: <input name="username" type="text" id="username" width="120"></td>
    		       <tr></tr>
    		       <td>Home Directory: <input name="homedir" type="text" id="homedir" width="120"></td>
    		       <tr><tr>
    		       <td>Shell: <input name="shell" type="text" id="shell" value="/bin/bash" width="120"></td>
    		       <tr></tr>
    		       <td>UID: <input name="target_uid" type="text" id="target_uid" width="120" value="250"></td>
    		       <tr></tr>
    		       <td>Group: <input name="group" type="text" id="group" value="users" width="120"></td>
    		       <tr></tr>
    		       <td>Do not create a homedir: <input name="homedir_bool" type="checkbox" id="homedir_bool"></td>
    		       <tr></tr>
    		       <td><input name="create_acnt" type="submit" id="create_acnt" value="Create Account:"</td>
                  </tr>
              </table></form>';
     } else {
     	echo '<form name="adduser" method="post" action="users.php?do=useradd&ref=users">
     	          <table>
     	          <tr>
     	              <td>Account name: <input name="acnt_name" type="text" id="acnt_name" width="120"></td>
     	              <tr></tr>
     	              <td>Home directory: <input name="home_dir" type="text" id="home_dir" width="120"></td>
     	              <tr></tr>
     	              <td><input name="create_acnt" type="submit" id="create_acnt" value="Create Account"></td>
     	          </tr>
     	      </table></form>';
     }
} elseif ($_GET['do'] == "useradd") {
    include ('include/header.php');
    include ('restrictions.php');
    
    if (isset ($_GET['adv'])) {
    	$acnt_name = $_POST['username'];
    	$shell = $_POST['shell'];
    	$home_dir = $_POST['homedir'];
    	$uid = $_POST['target_uid'];
    	$group = $_POST['group'];
    	$homedir_bool = $_POST['homedir_bool'];
    	
    	if (!($homedir_bool == NULL)) {
    	     $return = shell_exec('useradd --shell=' . $shell . ' -o -u ' . $uid . ' --gid=' . $group . ' $acnt_name 2>&1');
    	     if ($return == NULL) {
    	         echo '<pre><br />' . 'Success!' . '</pre>';
    	     } else {
    	         echo '<pre><br />' . $return . '</pre>';
    	     }
    	} else {
    	     $return = shell_exec('useradd --shell=' . $shell . ' -m --home-dir=' . $home_dir . ' -o -u ' . $uid . ' --gid=' . $group . ' $acnt_name 2>&1');
    	     if ($return) {
    	        echo '<pre><br />' . 'Success!' . '</pre>';
    	     } else {
    	        echo '<pre><br />' . $return . '</pre>';
    	     }
    	}
    } else {
        $acnt_name = $_POST['acnt_name'];
        $home_dir = $_POST['home_dir'];
        
        $return = shell_exec('useradd -m --home-dir=' . $home_dir . ' ' . $acnt_name . ' 2>&1');
   
        if ($return == NULL) {
            echo '<pre><br />' . 'Success!' . '</pre>';
        } else {
            echo '<pre><br />' . $return . '</pre>';
        }
    }
} elseif ($_GET['p'] == "delete") {
    include ('include/header.php');
    include ('restrictions.php');
    
    echo '<form name="delete_user_form" method="post" action="users.php?do=userdel&ref=users">
    	    <table>
    	    <tr>
    	    	<td>Username: <input name="target_p" type="text" id="target_p"></td>
    	    	<tr></tr>
    	    	<td>Delete user\'s homedir and mail spool? <input name="del_home" type="checkbox" id="del_home" checked></td>
    	    	<tr></tr>
    	    	<td><input name="target_sub" type="submit" id="target_sub" value="Delete Account"></td>
    	    </tr>
    	  </table></form>';
} elseif ($_GET['do'] == "userdel") {
	include ('include/header.php');
	include ('restrictions.php');
	
	$acnt_name = $_POST['target_p'];
	$del_homedir_bool = $_POST['del_home'];
	
	if (isset($del_homedir_bool)) {
	    $return = shell_exec('userdel -r -f ' . $acnt_name . ' 2>&1');
	    if ($return == NULL) {
		    echo "<pre>Success!</pre>";
		} else {
			echo '<pre>' . $return . '</pre>';
		}
	} elseif (!isset($del_homedir_bool)) {
		$return = shell_exec('userdel ' . $acnt_name . ' 2>&1');
		if ($return == NULL) {
			echo "<pre>Success!</pre>";
		} else {
			echo '<pre>' . $return . '</pre>';
		}
	}
} elseif ($_GET['p'] == "modify_user" && isset($_GET['adv'])) {
	include ('include/header.php');
	include ('restrictions.php');
	
	echo '<form name="modify_user" method="post" action="users.php?do=usermod&adv">
		     <table>
		     <tr>
		         <td>Username: <input name="username" type="text" id="username"></td>
		         <tr></tr>
		         <td><strong>	Changes</strong></td>
		         <tr></tr>';
		         $changes_avail = array('username', 'UID', 'GID', 'GECOS', 'expiry');
		         foreach($changes_avail as $change) {
		         	echo '<td>	' . ucwords(strtolower($change)) . ': <input name="' . $change . '" type="text" id="' . $change . '"></td>';
		         	echo '<tr><tr/>';
		         }
		         echo '<td> Account Lock (check to lock account, uncheck to unlock) <br>
		                 <input name="lock_acnt" type="radio" id="lock_acnt_true">Locked</input><br>
		         		 <input name="lock_acnt" type="radio" id="lock_acnt_false">Unlocked</input><br><br>
		         	   </td>';
		         echo '<td><br><input name="submit" id="submit" type="submit" value="Submit Changes"><br></td>';
		         	   
	echo '	</tr>
		    </table>
		  </form>';
}
?>