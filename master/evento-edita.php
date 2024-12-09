<?php
if ( (!isset($_GET['id'])) || (!is_numeric($_GET['id'])) ) {
    header('Location: ./');
}
include('./inc/head.php') ?>
<!-- <link rel="stylesheet" href="./assets/plugins/editor-js/css/froala_editor.min.css"> -->
<!-- <link rel="stylesheet" href="./editor/prettify.min.css"> -->

<style>
.switch {
  position: relative;
  display: inline-block;
  width: 60px;
  height: 34px;
}

.switch input { 
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #2196F3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

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

        <?php  include('./inc/evento-basico.php');  ?>
        <?php  include('./inc/evento-pacotes.php');  ?>
        <?php  include('./inc/evento-sessao.php');  ?>
        <?php  include('./inc/evento-hash.php');  ?>
        <?php  include('./inc/evento-mensagens.php'); ?>
        <?php  include('./inc/evento-termo-edita.php'); ?>
           
    </div>
</section>

<?php include('./inc/javascript.php'); ?>

<script src="https://cdn.tiny.cloud/1/f1mnu6j314gmx71dhaq7h7heaetxhp1img0gez40709sy1x8/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>

<script>

    function aplicaTiny() {
        tinymce.init({
                selector: 'textarea',  // change this value according to the HTML
                menubar: '',
                plugins: 'code, lists, wordcount',
                toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify | numlist bullist | code wordcount'
            });

    }

    $(document).ready(function() {    
            aplicaTiny();
    });
</script>


</body>
</html>