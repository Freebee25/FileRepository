<?php
session_start();
session_destroy();
header("Location: ../resource/views/login.php");
exit;
?>
