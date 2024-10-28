<?php
$evento = 1;
$sql_busca_evento = "select * from tbevento where id_evento=$evento";

$pre_busca_evento = $connPDO->prepare($sql_busca_evento);
$pre_busca_evento->execute();

$rows_busca_evento = $pre_busca_evento->fetchAll(PDO::FETCH_ASSOC);
?>