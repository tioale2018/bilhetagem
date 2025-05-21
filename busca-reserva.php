<?php
// die('<h1>Erro: Acesso não autorizado</h1>');
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header('X-PHP-Response-Code: 404', true, 404);
    http_response_code(404);
    exit('Requisição inválida.');
}
session_start();
include_once("./inc/cad-participantes-regras.php");

?>


<script>
    location.replace('cadastro');
</script>