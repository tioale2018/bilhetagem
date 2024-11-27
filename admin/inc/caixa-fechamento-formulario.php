
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
        <?php /*
        <tr>
            <th>Valor de retirada em espécie</th>
            <td><input type="text" name="fval_retirada" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_retirada'], 2, ',', '.'); ?>"></td>
        </tr>
        */ ?>
        <tr>
            <th>Valor total (total bruto dinheiro menos despesas menos depósitos)</th>
            <td><input type="text" name="fval_total" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_total'], 2, ',', '.'); ?>"></td>
        </tr>
        <tr>
            <th>Valor extra (+)</th>
            <td><input type="text" name="fval_extra" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_extra'], 2, ',', '.'); ?>"><div id="mostraval"></div></td>
            
        </tr>
        <tr>
            <th>Valor final</th>
            <td><input type="text" name="fval_final" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_final'], 2, ',', '.'); ?>"></td>
        </tr>
        <tr>
            <th>Observações</th>
            <td><textarea name="fobservacoes" class="form-control form-caixa" rows="5" maxlength="2000"><?= $row_caixaformulario['observacoes'] ?></textarea></td>
        </tr>
    </tbody>
</table>

<input type="hidden" name="codcaixaform" value="<?= $row_caixaformulario['id'] ?>">

