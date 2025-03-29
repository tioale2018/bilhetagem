<?php
session_start();
session_destroy();
echo htmlspecialchars($_POST['i']);
?>