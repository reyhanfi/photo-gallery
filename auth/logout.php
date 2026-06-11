<?php
session_start();
session_unset();
session_destroy();
header('Location: http://localhost/photo-gallery/auth/login.php');
exit;
?>