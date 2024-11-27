
<table class="table">
    <tbody>
        
        <tr>
            <th width="40%">Valor de abertura de caixa</th>
            <td width="60%">
                <table class="table">
                    <tr>
                    <td><input type="text" name="fval_abrecaixa" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_abrecaixa'], 2, ',', '.'); ?>"></td>
                        <td>R$ 0,00 (Saldo de 09/09/2024)</td>
                        
                    </tr>
                    
                </table>
                
            </td>
        </tr>
        <tr>
            <th>Valor de tickets vendidos (em espécie)</th>
            <td>
                <input type="text" name="fval_dinheiro" class="form-control form-caixa money" value="<?= number_format($dinheiro, 2, ',', '.'); ?>" readonly>
            </td>
        </tr>
        <tr>
            <th>Total entradas (Abert. caixa + tickets vendidos)</th>
            <td>
                <input type="text" name="fval_entradas" class="form-control form-caixa money" value="<?= number_format($total_entradas, 2, ',', '.'); ?>" readonly>
            </td>

        </tr>
        
        <tr>
            <th>Total de saídas</th>
            <td>
                <table class="table">
                    <tr>
                    <?php if($row_caixaformulario['val_despesas']>0) { ?>
                        <td>
                        
                            <table class="table">
                                <?php foreach ($row_buscaMovimento as $key => $value) { ?>
                                    
                                <tr>
                                    <td><?= $value['descricao'] ?></td>
                                    <td class="text-right">R$ <?= number_format($value['total'], 2, ',', '.'); ?></td>
                                </tr>
                                
                                <?php } ?>
                            </table>
                        
                        </td>
                        <?php } ?>
                        <td>
                            <input type="text" name="fval_despesas" class="form-control form-caixa money despesas" value="<?= number_format($row_caixaformulario['val_despesas'], 2, ',', '.'); ?>" readonly>
                        </td>
                    </tr>
                </table>
                
                
                
                
            </td>
        </tr>
        
        <?php /*
        <tr>
            <th>Valor de retirada em espécie</th>
            <td><input type="text" name="fval_retirada" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_retirada'], 2, ',', '.'); ?>"></td>
        </tr>
        */ ?>
        <tr>
            <th>Saldo (Total entradas - Total saídas)</th>
            <td><input type="text" name="fval_total" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_total'], 2, ',', '.'); ?>" readonly></td>
        </tr>
        <tr>
            <th>Sangria</th>
            <td><input type="text" name="fval_final" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_final'], 2, ',', '.'); ?>"></td>
        </tr>
        <tr>
            <th><span id="rotuloExtra">Valor extra <span id="sinalExtra"></span></span></th>
            <td><input type="text" name="fval_extra" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_extra'], 2, ',', '.'); ?>" readonly><div id="mostraval"></div></td>
        </tr>
        <tr>
            <th width="30%">Total de tickets do dia</th>
            <td width="70%">
                <p>Total de tickets no sistema: <?= $sis_total_tickets ?></p>
                <input type="text" name="ftickets" class="form-control form-caixa" value="<?= $total_tickets ?>">
            </td>
        </tr>
        <tr>
            <th width="30%">Conferência de valores</th>
            <td>
            <table>
                    <thead>
                        <tr>
                            <!-- <th>Dinheiro</th> -->
                            <th>Cartão</th>
                            <th>PIX</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- <td>R$ <span><?= number_format($dinheiro, 2, ',', '.'); ?></span></td> -->
                            <td>R$ <?= number_format($cartao, 2, ',', '.'); ?></td>
                            <td>R$ <?= number_format($pix, 2, ',', '.'); ?></td>
                        </tr>
                        <tr>
                            <!-- <td><input type="text" name="fdinheiro" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_vendadin'], 2, ',', '.'); ?>"></td> -->
                            <td><input type="text" name="fcartao" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_vendacar'], 2, ',', '.'); ?>"></td>
                            <td><input type="text" name="fpix" class="form-control form-caixa money" value="<?= number_format($row_caixaformulario['val_vendapix'], 2, ',', '.'); ?>"></td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div><b>Observações (2500 caracteres)</b></div>
                <textarea name="fobs" maxlength="2500" rows="5" class="form-control form-caixa"><?= $row_caixaformulario['observacoes'] ?></textarea>
            
            </td>
            
        </tr>
    </tbody>
</table>

<input type="hidden" name="codcaixaform" value="<?= $row_caixaformulario['id'] ?>">

