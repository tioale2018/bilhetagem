<?php
die('<h1>Erro: Acesso não autorizado</h1>');
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    die(0);
}
session_start();
include_once("./inc/cad-participantes-regras.php");

?>


<script>
    location.replace('cadastro');
</script>