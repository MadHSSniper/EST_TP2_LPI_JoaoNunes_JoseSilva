<!DOCTYPE html>
<html lang="pt">
<head>
	<title>Registo Evento</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <link href="../css/blog-home.css" rel="stylesheet">
    
	<?php
	include("../resources/header.php");
	?> 
</head>

<body>

	<?php
	include("../funcoesBD.php");
	desenhaTopoBarra( "inscricao" );
	echo("</br></br></br>");

	$idSessao = -1;
	$idEvento = -1;
	if( hasLoggedIn() ){
		$idSessao = $_SESSION['numC'];
		$tipoUser = obterDescricaoUtilizador($_SESSION['numC']);
	}
	?>
	<?php

	if( isset($_GET['id']) && $_GET['id']!="" ){
		$idEvento = $_GET['id'];
	} else{
		echo('
			<div class="panel panel-danger">
			<div class="panel-heading">Evento não selecionado</div>
			</div>

			');
		desenharFundoBarra( "inscricao" );
		exit();
	}


	?>

	<?php


	if( !hasLoggedIn() ){
		$_SESSION['idEvento'] = $idEvento;
		header("Location: inscreverEvento.php");
	}
	if( existeIdInscricao($idSessao, $idEvento) != false ){
		echo('
			<div class="panel panel-danger">
			<div class="panel-heading">Este utilizador já esta inscrito</div>
			</div>
		');
	}
	else if( !existemVagas($idEvento) ){
		echo('
			<div class="panel panel-info">
			<div class="panel-heading">Ja não existem vagas para o evento</div>
			</div>
		');
	}else{// Proceder à inscricao
		$numPorta = getPorta($idEvento);
		registar($idSessao,$idEvento,$numPorta);
		echo('
			<div class="panel panel-info">
			<div class="panel-heading">Registo Efetuado com sucesso</div>
			</div>
		');
	}

	?>






	<?php desenharFundoBarra( "inscricao" ); ?>

</body>
</html>

<?php mysqli_close($conn); unsetSessionVars(); ?>
