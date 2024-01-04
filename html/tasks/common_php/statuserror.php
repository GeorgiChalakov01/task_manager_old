<?php
if(isset($_GET['status']))
    echo '
        <p class="status">' . $_GET["status"] . '</p>
    ';
if(isset($_GET['error']))
    echo '
        <p class="error">' . $_GET["error"] . '</p>
    ';
?>