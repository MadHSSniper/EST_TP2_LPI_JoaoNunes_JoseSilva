<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>Associação Caça EST</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link href="css/blog-home.css" rel="stylesheet">

</head>

<body>

    <?php
        include("funcoesBD.php");
        desenhaTopoBarra( "contacto" );
    ?>
    </br>
    </br>
    </br>

    <!-- Page Content -->
    <div class="container">

        <!-- Introduction Row -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Acerca de Nós
                    <small>Grupo de caçadores</small>
                </h1>
                <p>Grupo de Caçadores da EST</p>
            </div>
        </div>

        <!-- Team Members Row -->
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header">Dirigentes</h2>
            </div>
            <div class="col-lg-4 col-sm-6 text-center">
                <img class="img-circle img-responsive img-center" src="img/perfil/1.png" alt="">
                <h3>Sampaio
                    <small>Presidente</small>
                </h3>
                <p>Licenciado na EST</p>
            </div>
            <div class="col-lg-4 col-sm-6 text-center">
                <img class="img-circle img-responsive img-center" src="img/perfil/7.jpg" alt="">
                <h3>Fonseca
                    <small>Vice-Presidente</small>
                </h3>
                 <p>Licenciado na ESALD</p>
            </div>
            <div class="col-lg-4 col-sm-6 text-center">
                <img class="img-circle img-responsive img-center" src="img/perfil/5.jpg" alt="">
                <h3>Silvina
                    <small>Secretária</small>
                </h3>
                 <p>Licenciada na ESART</p>
            </div>
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
