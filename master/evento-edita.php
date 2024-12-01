<?php
if ( (!isset($_GET['id'])) || (!is_numeric($_GET['id'])) ) {
    header('Location: ./');
}
include('./inc/head.php') ?>
<!-- <link rel="stylesheet" href="./assets/plugins/editor-js/css/froala_editor.min.css"> -->
<link rel="stylesheet" href="./editor/prettify.min.css">
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
                        <h3>Dados básicos do evento</h3>
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
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="titulo" class="form-label">Data de início</label>                               
                                    <input type="date" class="form-control" placeholder="dd/mm/aaaa"  value="<?= date('Y-m-d',$row_busca_evento[0]['inicio']); ?>" name="inicio" min="<?= date('Y-m-d', time()) ?>"  />
                                </div>
                            </div>
                            <div class="col-md-4">
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
                    </div>
                </div>
            </div>
        </div>

        <div>
        <div class="btn-toolbar editor" data-role="editor-toolbar" data-target="#editor-one">
									<div class="btn-group">
										<a class="btn dropdown-toggle" data-toggle="dropdown" title="Font"><i class="fa fa-font"></i><b class="caret"></b></a>
										<ul class="dropdown-menu">
										</ul>
									</div>

									<div class="btn-group">
										<a class="btn dropdown-toggle" data-toggle="dropdown" title="Font Size"><i class="fa fa-text-height"></i>&nbsp;<b class="caret"></b></a>
										<ul class="dropdown-menu">
											<li>
												<a data-edit="fontSize 5">
													<p style="font-size:17px">Huge</p>
												</a>
											</li>
											<li>
												<a data-edit="fontSize 3">
													<p style="font-size:14px">Normal</p>
												</a>
											</li>
											<li>
												<a data-edit="fontSize 1">
													<p style="font-size:11px">Small</p>
												</a>
											</li>
										</ul>
									</div>

									<div class="btn-group">
										<a class="btn" data-edit="bold" title="Bold (Ctrl/Cmd+B)"><i class="fa fa-bold"></i></a>
										<a class="btn" data-edit="italic" title="Italic (Ctrl/Cmd+I)"><i class="fa fa-italic"></i></a>
										<a class="btn" data-edit="strikethrough" title="Strikethrough"><i class="fa fa-strikethrough"></i></a>
										<a class="btn" data-edit="underline" title="Underline (Ctrl/Cmd+U)"><i class="fa fa-underline"></i></a>
									</div>

									<div class="btn-group">
										<a class="btn" data-edit="insertunorderedlist" title="Bullet list"><i class="fa fa-list-ul"></i></a>
										<a class="btn" data-edit="insertorderedlist" title="Number list"><i class="fa fa-list-ol"></i></a>
										<a class="btn" data-edit="outdent" title="Reduce indent (Shift+Tab)"><i class="fa fa-dedent"></i></a>
										<a class="btn" data-edit="indent" title="Indent (Tab)"><i class="fa fa-indent"></i></a>
									</div>

									<div class="btn-group">
										<a class="btn" data-edit="justifyleft" title="Align Left (Ctrl/Cmd+L)"><i class="fa fa-align-left"></i></a>
										<a class="btn" data-edit="justifycenter" title="Center (Ctrl/Cmd+E)"><i class="fa fa-align-center"></i></a>
										<a class="btn" data-edit="justifyright" title="Align Right (Ctrl/Cmd+R)"><i class="fa fa-align-right"></i></a>
										<a class="btn" data-edit="justifyfull" title="Justify (Ctrl/Cmd+J)"><i class="fa fa-align-justify"></i></a>
									</div>

									<div class="btn-group">
										<a class="btn dropdown-toggle" data-toggle="dropdown" title="Hyperlink"><i class="fa fa-link"></i></a>
										<div class="dropdown-menu input-append">
											<input class="span2" placeholder="URL" type="text" data-edit="createLink" />
											<button class="btn" type="button">Add</button>
										</div>
										<a class="btn" data-edit="unlink" title="Remove Hyperlink"><i class="fa fa-cut"></i></a>
									</div>

									<div class="btn-group">
										<a class="btn" title="Insert picture (or just drag & drop)" id="pictureBtn"><i class="fa fa-picture-o"></i></a>
										<input type="file" data-role="magic-overlay" data-target="#pictureBtn" data-edit="insertImage" />
									</div>

									<div class="btn-group">
										<a class="btn" data-edit="undo" title="Undo (Ctrl/Cmd+Z)"><i class="fa fa-undo"></i></a>
										<a class="btn" data-edit="redo" title="Redo (Ctrl/Cmd+Y)"><i class="fa fa-repeat"></i></a>
									</div>
								</div>

								<div id="editor-one" class="editor-wrapper"></div>

								<textarea name="descr" id="descr" style="display:none;"></textarea>

								<br />
        </div>

        <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <div class="card">
                    <div class="body">
                    <!-- <div class="body">  -->
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">HOME</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#cadastro">cadastro</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#parque">parque</a></li>                        
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#reserva">reserva</a></li>                        
                    </ul>                        
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane in active" id="home"> 
                            <h6>Home Content</h6>
                            <textarea name="" class="form-control" id="ckeditor"><?= $row_busca_evento[0]['regras_home'] ?></textarea>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="cadastro"> <b>cadastro Content</b>
                            <p>It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like).</p>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="parque"> <b>Message Content</b>
                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary</p>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="reserva"> <b>Reserva Content</b>
                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text. All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary</p>
                        </div>
                    </div>

                    <div>
                        <button type="button" class="btn btn-primary btn-round waves-effect">Salvar</button>
                    </div>
                <!-- </div> -->
                    </div>
                </div>
            </div>
        </div>
                    
    </div>
</section>

<?php include('./inc/javascript.php'); ?>

<script src="./editor/bootstrap-wysiwyg.min.js"></script>

</body>
</html>