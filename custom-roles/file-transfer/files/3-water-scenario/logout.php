<?php
    session_start();
    echo '<p>Goodbye :)</p>';
    session_unset();     // remove all session variables
    session_destroy();   // destroy the session entirely
    header("Location: index.php");  // standard redirect
    exit;
?>
