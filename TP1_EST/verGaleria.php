<!DOCTYPE html>
<html lang="pt">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>Galerias Disponíveis</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link href="css/blog-home.css" rel="stylesheet">
    <link href="css/1-col-portfolio.css" rel="stylesheet">

</head>

<body>
    <?php
    include("funcoesBD.php");
    desenhaTopoBarra( "about" );
    
    $resGalerias = obterGalerias();
    if($resGalerias == NULL){
        echo('
                <div class="panel panel-info">
                <div class="panel-heading">Não existem galerias disponíveis no momento.</div>
                </div>
            ');
        exit();
    }


    ?>

    <!-- Page Content -->
    <div class="container">

        <!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Galerias Disponíveis
                    <small>Associação EST</small>
                </h1>
            </div>
        </div>
        <!-- /.row -->

        <?php
            while($rowGaleria = mysqli_fetch_array($resGalerias) ){
                
                $resAlbum=obterBreveInfoGalerias($rowGaleria['idGaleria']);
                $rowAlbum=mysqli_fetch_array( $resAlbum );
                
                $resEvento = obterEvento($rowGaleria['idEvento']);
                $rowEvento = mysqli_fetch_array( $resEvento );

                
                echo('
                    <div class="row">
                        <div class="col-md-7">
                            <img class="img-responsive" src="'.$rowAlbum['source'].'" alt="">
                            
                        </div>
                        <div class="col-md-5">
                            <h3>'.$rowEvento['nome'].'</h3>
                            <h4>'.$rowAlbum['data'].'</h4>
                            <p>'.$rowEvento['descricao'].'</p>
                            <a class="btn btn-primary" href="galeria.php?idGaleria='.$rowGaleria['idGaleria'].'">
                                Ver Galeria
                                <span class="glyphicon glyphicon-chevron-right"></span>
                            </a>
                        </div>
                    </div>
                    </br>
                    </br>
                    ');
            }
        ?>

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
