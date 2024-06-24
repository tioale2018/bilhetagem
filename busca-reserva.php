<?php
if ($_SERVER['REQUEST_METHOD']!="POST") {
    header(':', true, 404);
    header('X-PHP-Response-Code: 404', true, 404);
    // __halt_compiler();
    die(0);
}
session_start();
include_once("./inc/cad-participantes-regras.php");

?>


<script>
    // history.replaceState(null, null, 'cadastro.php');
    location.replace('cadastro');
</script>