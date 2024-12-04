<?php
if ( (!isset($_GET['id'])) || (!is_numeric($_GET['id'])) ) {
    header('Location: ./');
}
include('./inc/head.php') ?>
<!-- <link rel="stylesheet" href="./assets/plugins/editor-js/css/froala_editor.min.css"> -->
<!-- <link rel="stylesheet" href="./editor/prettify.min.css"> -->
</head>
<body class="theme-black">
<?php //include('./inc/page-loader.php') ?>

<!-- Left Sidebar -->

<?php 
include('./inc/sidebar.php');

//busca dados do evento
$id = $_GET['id'];
$sql_busca_evento = "select * from tbevento where id_evento=:id";
$pre_busca_evento = $connPDO->prepare($sql_busca_evento);
$pre_busca_evento->bindParam(':id', $id, PDO::PARAM_INT);
$pre_busca_evento->execute();

if ($pre_busca_evento->rowCount() < 1) {
    die("<script>alert('Evento inexistente');location.replace('./');</script>");
}

$row_busca_evento = $pre_busca_evento->fetchAll(PDO::FETCH_ASSOC);

// echo var_dump($row_busca_evento);
?>

<!-- Main Content -->
<section class="content home">
    <div class="container-fluid">
        <div class="block-header">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <h2>Edita evento</h2>
                    <hr>
                </div>
            </div>
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="body">
                        <h5>Dados básicos do evento</h5>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="titulo" class="form-label">Título</label>                               
                                    <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['titulo'] ?>" name="titulo"  />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="titulo" class="form-label">Local</label>                               
                                    <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['local'] ?>" name="titulo"  />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="titulo" class="form-label">Cidade</label>                               
                                    <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['cidade'] ?>" name="titulo"  />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="titulo" class="form-label">Data de início</label>                               
                                    <input type="date" class="form-control" placeholder="dd/mm/aaaa"  value="<?= date('Y-m-d',$row_busca_evento[0]['inicio']); ?>" name="inicio" min="<?= date('Y-m-d', time()) ?>"  />
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="titulo" class="form-label">Data de fim (estimada)</label>                               
                                    <input type="date" class="form-control" placeholder="dd/mm/aaaa"  value="<?= $row_busca_evento[0]['fim']==''?'':date('Y-m-d',$row_busca_evento[0]['fim']); ?>" name="inicio" min="<?= date('Y-m-d', time()) ?>"  />
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="titulo" class="form-label">Capacidade</label>                               
                                    <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['capacidade'] ?>" name="titulo"  />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="titulo" class="form-label">Status</label>                               
                                    <!-- <input type="text" class="form-control" placeholder="" value="" name="titulo"  /> -->
                                    <select class="form-control show-tick p-0" name="tipopgto" id="ftipopgto" required>
                                        <option value="1">Em edição</option>
                                        <option value="2">Em execução</option>
                                        <option value="3">Encerrado</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div>
                                <button type="submit" class="btn btn-info btn-round waves-effect ">Salvar</button>
                                <input type="hidden" name="evento" value="<?= $id ?>">
                            </div>
                    </div>
                </div>
            </div>
        </div>

        <?php  include('./inc/termo-pacotes.php');    ?>


        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="body">
                            <h5>Sessão e atualização</h5>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Tempo de sessão (segundos)</label>                               
                                        <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['tempo_tela'] ?>" name=""  />
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="" class="form-label">Mostra tempo de sessão</label>                               
                                        <select name="" class="form-control" id="">
                                            <option value="1" <?= $row_busca_evento[0]['mostra_tempo']==1?'selected':'' ?>>Sim</option>
                                            <option value="2" <?= $row_busca_evento[0]['mostra_tempo']==2?'selected':'' ?>>Nao</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="atualizatab" class="form-label">Atualização da lista de controle (segundos)</label>                               
                                        <input type="text" class="form-control" placeholder="" value="<?= $row_busca_evento[0]['tempo_atualiza'] ?>" name="atualizatab"  />
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-info btn-round waves-effect">Salvar</button>
                                <input type="hidden" name="evento" value="<?= $id ?>">
                            </div>
                    </div>
                </div>
            </div>
        </div>
                 

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="body">
                        <h5>Mensagens ao usuário</h5>
                        <hr>
                        
                        <form action="" method="post" id="form-msg">

                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">Term de uso</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cadastro">Cadastro</a></li>
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#parque">Regras do parque</a></li>                        
                                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reserva">Mensagem final</a></li>                        
                            </ul>                        
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane in active" id="home"> 
                                    <h6>Termo de uso dos dados</h6>
                                    <textarea name="regra_home" class="form-control"  rows="10"><?= $row_busca_evento[0]['regras_home'] ?></textarea>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="cadastro"> 
                                    <h6>Regras de cadastro</h6>
                                    <textarea name="regra_cadastro" class="form-control"  rows="10"><?= $row_busca_evento[0]['regras_cadastro'] ?></textarea>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="parque"> 
                                    <h6>Regras do parque</h6>
                                    <textarea name="regra_parque" class="form-control"  rows="10"><?= $row_busca_evento[0]['regras_parque'] ?></textarea>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="reserva"> 
                                    <h6>Mensagem final de cadastro</h6>
                                    <textarea name="regra_reserva" class="form-control"  rows="10"><?= $row_busca_evento[0]['msg_fimreserva'] ?></textarea>
                                </div>
                            </div>

                            <div>
                                <button type="submit" class="btn btn-info btn-round waves-effect salva-msgs">Salvar</button>
                                <input type="hidden" name="evento" value="<?= $id ?>">
                            </div>

                        </form>
                
                    </div>
                </div>
            </div>
        </div>

        <?php  include('./inc/termo-edita.php');    ?>

           
    </div>
</section>

<?php include('./inc/javascript.php'); ?>

<script>
    $(document).ready(function() {
        $('#form-msg').submit(function(e) {
            e.preventDefault();
            $.post('./blocos/mensagens-salvar.php', $('#form-msg').serialize(), function(data){
                let jsonResponse = JSON.parse(data);
                if (jsonResponse.status == 1) {
                    swal({
                        title: "Mensagens evento",
                        text: "Salvo com sucesso!",
                        type: "success",
                        showCancelButton: false,
                        confirmButtonColor: "#DD6B55",
                        confirmButtonText: "Ok",
                        closeOnConfirm: true
                    });
                }
            });
        })
    });
</script>

</body>
</html>