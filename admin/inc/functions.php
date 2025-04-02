<?php
// Função para verificar se o usuário está logado

function verificarSessao() {
    // Verifica se a variável de sessão 'user_id' está definida
    // if ((!isset($_SESSION['user_id'])) && $_SESSION['user_perfil']!=1) {  - versão original
    if ( !isset($_SESSION['user_id']) ) { // versão para resolver o problema de redirecionamento
        // Se não estiver definida, redireciona para a página de login
        session_unset();
        session_destroy();
        header('Location: /admin/');
        exit();
    } else {
        if ( (!isset($_SESSION['evento_selecionado'])) || $_SESSION['evento_selecionado']==0) {
            header('Location: locked');
            exit();
        }
    }
}
?>
