<?php
session_start();
session_destroy();
echo $_POST['i'];
?>