
<table class="table table-hover">
    <tbody>
        <tr>
            <th width="30%">Total de tickets do dia</th>
            <td width="70%"><input type="text" class="form-control" value="231"></td>
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
                            <td>R$ 232 </td>
                            <td>R$ 5454</td>
                            <td>R$ 5454</td>
                        </tr>
                        <tr>
                            <td><input type="text" class="form-control money" value="231"></td>
                            <td><input type="text" class="form-control money" value="231"></td>
                            <td><input type="text" class="form-control money" value="231"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <th>Valor de abertura de caixa</th>
            <td><input type="text" class="form-control money" value="231.65"></td>
        </tr>
        <tr>
            <th>Valor de despesas</th>
            <td><input type="text" class="form-control money" value="231"></td>
        </tr>
        <tr>
            <th>Valor dos depósitos</th>
            <td><input type="text" class="form-control money" value="231"></td>
        </tr>
        <tr>
            <th>Valor de retirada em espécie</th>
            <td><input type="text" class="form-control money" value="231"></td>
        </tr>
        <tr>
            <th>Valor de retirada em espécie</th>
            <td><input type="text" class="form-control money" value="231"></td>
        </tr>
        <tr>
            <th>Valor extra (+)</th>
            <td><input type="text" class="form-control money" value="231"></td>
        </tr>
        <tr>
            <th>Valor final</th>
            <td><input type="text" class="form-control money" value="231"></td>
        </tr>
    </tbody>
</table>

<script>
    $(document).ready(function() {
        $('.money').mask('#.##0,00', {reverse: true});

        <?php if ($row_buscadata[0]['status'] == 2) { ?>
            $('.money').prop('readonly', true);
        <?php } ?>
    });
</script>