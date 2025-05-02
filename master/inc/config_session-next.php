<?php
session_start();
// $inactive = 1800; // 30 minutos
$inactive = (isset($_SESSION['evento']) ? $_SESSION['evento']['tempo_tela'] : 1800);

?>