<?php
session_start();
session_destroy();
header("Location: indexM1.html?logout=success");
exit;
?>
