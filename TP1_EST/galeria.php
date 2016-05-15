<!DOCTYPE html>
<html lang="pt">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>Galeria</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <link href="css/blog-home.css" rel="stylesheet">

</head>

<body>

    <?php
    include("funcoesBD.php");
    desenhaTopoBarra( "about" );

    
    if( isset($_GET['idEvento']) && $_GET['idEvento']>0 ){
        $resGaleria = obterIdGaleria( $_GET['idEvento'] );
        if($resGaleria == NULL){
            echo('
                <div class="panel panel-info">
                <div class="panel-heading">Não existem fotos!</div>
                </div>
            ');
            exit();
        }
        header("Location:galeria.php?idGaleria=".$resGaleria);
    }



    $idGaleria = -1;
    if( isset($_GET['idGaleria']) && $_GET['idGaleria']>0 ){
        $idGaleria = $_GET['idGaleria'];
    }else{
        echo('
                <div class="panel panel-info">
                <div class="panel-heading">Não existe galeria!</div>
                </div>
            ');
        exit();
    }
    $resGaleria = obterGaleria( $idGaleria );

    if( mysqli_num_rows($resGaleria)==0 ){
        echo('
            <div class="panel panel-info">
            <div class="panel-heading">Não existem fotos!</div>
            </div>
        ');
        exit();
    }

    ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-lg-12">
                <h1 class="page-header">Galeria</h1>
            </div>


            <?php
            while($rowGaleria = mysqli_fetch_array($resGaleria) ){
                echo('
                    <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                    <a class="thumbnail">
                    <img class="img-responsive" src="'.$rowGaleria['source'].'" alt="">
                    </a>
                    </div>
                    ');
            }
            ?>
        </div>

        <hr>

        <!-- Footer -->
        <footer>
            <div class="row">
                <div class="col-lg-12">
                    <p>ESTCB &copy; 2016</p>
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </footer>

    </div>
    <!-- /.container -->

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

</body>

</html>
