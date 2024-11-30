<?php
// Inclui a biblioteca Dompdf manualmente
require '../libs/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

// Inicializa o Dompdf
$dompdf = new Dompdf();

// Captura o conteúdo HTML da página
ob_start();
include('termo-exibe.php'); // Inclui o arquivo que contém o HTML
$html = ob_get_clean();

// Carrega o HTML no Dompdf
$dompdf->loadHtml($html);

// Define o tamanho do papel e a orientação (opcional)
$dompdf->setPaper('A4', 'portrait'); // ou 'landscape' para paisagem

// Renderiza o PDF
$dompdf->render();

// Exibe o PDF no navegador
$dompdf->stream('termo-'.$row_participante['cpf'].'-'.$row_participante['participantenome'].'.pdf', ['Attachment' => true]); // false para exibir no navegador
?>
