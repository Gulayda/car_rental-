<?php
session_start();
session_unset();  // Sessiyani tozalash
session_destroy();  // Sessiyani yo'q qilish

header("Location: index.php");  
exit();
?>
