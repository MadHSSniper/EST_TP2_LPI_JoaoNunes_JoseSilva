<!DOCTYPE html>
<html lang="pt">
<head>
	<title> Editar Utilizador </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <link href="../css/blog-home.css" rel="stylesheet">
	
	<?php include("../resources/header.php"); ?>
	
	<style>
	body {
		position: relative;
	}
	ul.nav-pills {
		top: 20px;
		position: fixed;
	}
	div.col-sm-9 div {
		height: 250px;
		font-size: 28px;
	}
	#section1 {color: #fff; background-color: #1E88E5;}
	#section2 {color: #fff; background-color: #673ab7;}
	#section3 {color: #fff; background-color: #ff9800;}
	#section41 {color: #fff; background-color: #00bcd4;}
	#section42 {color: #fff; background-color: #009688;}

	@media screen and (max-width: 810px) {
		#section1, #section2, #section3, #section41, #section42  {
			margin-left: 150px;
		}
	}
	</style>
</head>
<body data-spy="scroll" data-target="#myScrollspy" data-offset="20">

</body>
<body>

	<?php
	include('../funcoesBD.php');
	desenhaTopoBarra( "session" );
	
	if(isset($_POST['numC'])){
		$numC = $_POST['numC'];
		
		if($numC == $_SESSION["numC"]){
			echo '
				<div class="panel panel-info">
					<div class="panel-heading">Não te podes apagar a ti mesmo!</div>
				</div>
			';
			exit();
		}
		
		$retval = apagarUtilizador($numC);
		
		if($retval){
			echo '
				<div class="panel panel-info">
					<div class="panel-heading">Utilizador eliminado com sucesso!</div>
				</div>
			';
			exit();
		}else{
			echo '
				<div class="panel panel-danger">
					<div class="panel-heading">ERRO! Utilizador não eliminado!</div>
				</div>
			';
			exit();
		}
	
	}else{
		echo '
			<div class="panel panel-danger">
				<div class="panel-heading">ERRO! Utilizador não encontrado!</div>
			</div>
		';
		exit();
	}
	
	?>
	
</body>

</html>

<?php mysqli_close($conn); unsetSessionVars(); ?>
