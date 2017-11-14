<?php 
session_start();
unset($_SESSION);
session_destroy();
session_write_close();
setcookie("username", "", 1);
header('Location: index.php', true, 301);
exit;
?>