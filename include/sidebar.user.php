<?php
    echo '<html>
          <div id="sidebar" align="left">
             <strong>Simple</strong>
             <br /><br />
             <a href="users.php?p=add">Create a new UNIX account</a>
             <br />
             <a href="users.php?p=delete">Delete a UNIX account</a>
             <br /><br />
             <strong>Advanced</strong>
             <br /><br />
             <a href="users.php?p=add&adv=true">Create a new UNIX account</a>
             <br />
             <a href="users.php?p=modify_user&ref=users&adv">Modify an existing UNIX account</a>
             <br /><br />
         </div>';       
?>