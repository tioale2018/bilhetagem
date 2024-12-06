<?php 
$variables = [
    'responsavelnome' => $row_participante['responsavelnome'],
    'responsavelcpf' => $row_participante['cpf'],
    'responsaveltel1' => $row_participante['telefone1'],
    'participantenome' => $row_participante['participantenome'],
    'participantenascimento' => date('d/m/Y', strtotime($row_participante['nascimento'])), 
    'participanteidade' => calculateAge($row_participante['nascimento']),
    'datahoje' => $row_participante['datahora_autoriza']==''?'':formatDate($row_participante['datahora_autoriza']),
    'cidadetermo' => ($row_busca_termo['cidadetermo']==''?'Rio de Janeiro':$row_busca_termo['cidadetermo']),
    'empresatermo' => $row_busca_termo['empresa'],
    'cnpjtermo' => $row_busca_termo['cnpj']
];

?>