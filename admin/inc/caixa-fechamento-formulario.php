
<table class="table table-hover">
    <tbody>
        <tr>
            <th width="30%">Total de tickets do dia</th>
            <td width="70%">
                <p>Total de tickets no sistema: <?= $sis_total_tickets ?></p>
                <input type="text" name="ftickets" class="form-control form-caixa" value="<?= $total_tickets ?>">
            </td>
        </tr>
        <tr>
            <th>Valor de tickets vendidos</th>
            <td>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Dinheiro</th>
                            <th>Cartão</th>
                            <th>PIX</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>R$ <?= number_format($dinheiro, 2, ',', '.'); ?></td>
                            <td>R$ <?= number_format($cartao, 2, ',', '.'); ?></td>
                            <td>R$ <?= number_format($pix, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <td><input type="text" name="fdinheiro" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_vendadin'], 2, ',', '.'); ?>"></td>
                            <td><input type="text" name="fcartao" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_vendacar'], 2, ',', '.'); ?>"></td>
                            <td><input type="text" name="fpix" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_vendapix'], 2, ',', '.'); ?>"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <th>Valor de abertura de caixa</th>
            <td><input type="text" name="fval_abrecaixa" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_abrecaixa'], 2, ',', '.'); ?>"></td>
        </tr>
        <tr>
            <th>Valor de despesas</th>
            <td><input type="text" name="fval_despesas" class="form-control form-caixa money despesas" value="<?= number_format($row_caixaformulario['val_despesas'], 2, ',', '.'); ?>" readonly></td>
        </tr>
        <tr>
            <th>Valor dos depósitos</th>
            <td><input type="text" name="fval_depositos" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_depositos'], 2, ',', '.'); ?>"></td>
        </tr>
        <tr>
            <th>Valor de retirada em espécie</th>
            <td><input type="text" name="fval_retirada" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_retirada'], 2, ',', '.'); ?>"></td>
        </tr>
        <tr>
            <th>Valor total (total bruto dinheiro menos despesas menos depósitos)</th>
            <td><input type="text" name="fval_total" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_total'], 2, ',', '.'); ?>"></td>
        </tr>
        <tr>
            <th>Valor extra (+)</th>
            <td><input type="text" name="fval_extra" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_extra'], 2, ',', '.'); ?>"></td>
        </tr>
        <tr>
            <th>Valor final</th>
            <td><input type="text" name="fval_final" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_final'], 2, ',', '.'); ?>"></td>
        </tr>
    </tbody>
</table>

<input type="hidden" name="codcaixaform" value="<?= $row_caixaformulario['id'] ?>">

<script>
    $(document).ready(function() {
        $('.despesas').click(function() {
            location.href = "./caixa-movimento?d=<?= $_GET['d'] ?>"
        })
    });
</script>



<script>
    $(document).ready(function () {
    // Inicializar a máscara nos campos com a classe "money"
    

    // Função para obter o valor de um campo formatado com máscara
    function getInputValue(name) {
        const input = $(`input[name="${name}"]`);
        if (!input.length) return 0;

        // Remove a formatação de máscara e converte para número
        const value = input.val().replace(/\./g, '').replace(',', '.');
        return parseFloat(value) || 0;
    }

    // Função para atualizar os cálculos
    function updateCalculations() {
        // Obter valores dos campos
        const dinheiro = getInputValue('fdinheiro');
        const cartao = getInputValue('fcartao');
        const pix = getInputValue('fpix');
        const aberturaCaixa = getInputValue('fval_abrecaixa');
        const despesas = getInputValue('fval_despesas');
        const depositos = getInputValue('fval_depositos');
        const especie = getInputValue('fval_retirada');
        const extra = getInputValue('fval_extra');

        // Realizar os cálculos
        const total = (dinheiro + cartao + pix + aberturaCaixa) - (despesas + depositos + especie);
        const final = total + extra;

        // Atualizar os campos de saída
        $('input[name="fval_total"]').val(total.toFixed(2).replace('.', ','));
        $('input[name="fval_final"]').val(final.toFixed(2).replace('.', ','));

        // Reaplicar a máscara nos campos calculados
       
    }

    // Monitorar alterações nos campos de entrada
    $('.form-caixa').on('input', function () {
        updateCalculations();
    });

    // Garantir cálculo inicial quando a página carregar
    updateCalculations();

    $('.money').mask('#.##0,00', {
        reverse: true,
        onKeyPress: function (value, e, field) {
            // Remove zeros à esquerda quando o campo perde o foco
            field.val(value.replace(/^0+(?![,])/g, ''));
        }
    });
});

</script>