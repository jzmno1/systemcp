<?php

if (!isset($_SESSION['myusername'])) {
    header("location:index.html");
}

include ('include/config.php');

if ($_SESSION['permission'] == "limited") {
        echo '<html>
          <div align="left">
    	    <strong>
    	    You do not have permission to access this resource.
    	    </strong>
	    <br /><br />
    	    <a href="panel.php?p=home">Back to Panel</a>
    	  </div>
    	  </html>';
        exit;
}
?>
