<?php
session_start();
session_destroy();
setcookie('auth_token', '', time() - 3600, '/', '', true, true);
header("Location: /Index.php");
exit;