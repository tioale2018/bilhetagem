<!-- Jquery Core Js -->
<script src="../assets/bundles/vendorscripts.bundle.js"></script>
<script src="../assets/bundles/mainscripts.bundle.js"></script>

 <!-- slimscroll, waves Scripts Plugin Js -->
<!-- <script src="../assets/bundles/vendorscripts.bundle.js"></script>  -->

<!-- Jquery Core Js --> 
<script src="../assets/bundles/datatablescripts.bundle.js"></script>
<script src="../assets/js/pages/tables/jquery-datatable.js"></script>

<!-- Jquery Knob-->
<!-- <script src="../assets/bundles/knob.bundle.js"></script>  -->
<!-- sparkline Plugin Js -->
<script src="../assets/bundles/sparkline.bundle.js"></script> 
<!-- <script src="../assets/plugins/chartjs/Chart.bundle.js"></script> -->
 <!-- Chart Plugins Js --> 
<!-- <script src="../assets/plugins/chartjs/polar_area_chart.js"></script> -->
<!-- Polar Area Chart Js --> 

<!-- <script src="../assets/js/pages/index.js"></script> -->
<!-- <script src="../assets/js/pages/charts/polar_area_chart.js"></script> -->

<script src="../assets/plugins/momentjs/moment.js"></script> <!-- Moment Plugin Js --> 

<!-- Bootstrap Material Datetime Picker Plugin Js --> 
<script src="../assets/plugins/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script> 
<script src="../assets/js/pages/forms/basic-form-elements.js"></script> 
<script src="../assets/plugins/sweetalert/sweetalert.min.js"></script> <!-- SweetAlert Plugin Js --> 
<script src="../assets/js/pages/ui/dialogs.js?i=<?= filemtime('../assets/js/pages/ui/dialogs.js'); ?>"></script>
<script src="../assets/plugins/bootstrap-select/js/bootstrap-select.js"></script>

<script>
    // Função para aplicar a máscara de CPF
    function aplicarMascaraCPF(cpf) {
        return cpf
            .replace(/\D/g, '') // Remove caracteres não numéricos
            .replace(/(\d{3})(\d)/, "$1.$2")
            .replace(/(\d{3})(\d)/, "$1.$2")
            .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
    }

    // Validação do CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/\D/g, ''); // Remove caracteres não numéricos
        
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) {
            return false; // Verifica se o CPF tem 11 dígitos e não é uma sequência de números iguais
        }

        let soma = 0;
        let resto;

        for (let i = 1; i <= 9; i++) {
            soma += parseInt(cpf.charAt(i - 1)) * (11 - i);
        }
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) {
            resto = 0;
        }
        if (resto !== parseInt(cpf.charAt(9))) {
            return false;
        }

        soma = 0;
        for (let i = 1; i <= 10; i++) {
            soma += parseInt(cpf.charAt(i - 1)) * (12 - i);
        }
        resto = (soma * 10) % 11;
        if ((resto === 10) || (resto === 11)) {
            resto = 0;
        }
        return resto === parseInt(cpf.charAt(10));
    }
</script>


<?php 

include_once('./inc/conta-tempo.php');

?>