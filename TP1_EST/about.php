<!DOCTYPE html>
<html lang="pt">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">

    <title>Associação de Caça EST</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <link href="css/blog-home.css" rel="stylesheet">
    <link href="css/half-slider.css" rel="stylesheet">

</head>

<?php
        include("funcoesBD.php");
        desenhaTopoBarra( "about" );
?>
<body>

    </br>
    </br>
    </br>

    <!-- Half Page Image Background Carousel Header -->
    <header id="myCarousel" class="carousel slide">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
            <li data-target="#myCarousel" data-slide-to="1"></li>
            <li data-target="#myCarousel" data-slide-to="2"></li>
        </ol>

        <!-- Wrapper for Slides -->
        <div class="carousel-inner">
            <div class="item active">
                <!-- Set the first background image using inline CSS below. -->
                <div class="fill" style="background-image:url('img/about1.jpg');"></div>
                <div class="carousel-caption">
                    <h2>Grupo de caçadores</h2>
                </div>
            </div>
            <div class="item">
                <!-- Set the second background image using inline CSS below. -->
                <div class="fill" style="background-image:url('img/about2.jpg');"></div>
                <div class="carousel-caption">
                    <h2>Os melhores eventos de Caça</h2>
                </div>
            </div>
            <div class="item">
                <!-- Set the third background image using inline CSS below. -->
                <div class="fill" style="background-image:url('img/about3.jpg');"></div>
                <div class="carousel-caption">
                    <h2>Realizamos batidas e montarias</h2>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#myCarousel" data-slide="prev">
            <span class="icon-prev"></span>
        </a>
        <a class="right carousel-control" href="#myCarousel" data-slide="next">
            <span class="icon-next"></span>
        </a>

    </header>

    <?php
        $retA = getInfoAssociacao(1);
        $rowA = mysqli_fetch_array($retA);
    ?>
    <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-lg-12">
                <h1>Associação de Caça EST</h1>
                <p><?php echo($rowA['descricao']) ?></p>
                <p>Morada: <?php echo($rowA['morada']) ?></p>
                <p>Telefone: <?php echo($rowA['telefone']) ?></p>
                <p>Telemóvel: <?php echo($rowA['telemovel']) ?></p>
                <p>Fax: <?php echo($rowA['fax']) ?></p>
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

    <!-- Script to Activate the Carousel -->
    <script>
    $('.carousel').carousel({
        interval: 5000 //changes the speed
    })
    </script>

</body>

</html>
