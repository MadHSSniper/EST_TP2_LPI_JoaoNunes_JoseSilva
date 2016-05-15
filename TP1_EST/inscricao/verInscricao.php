<!DOCTYPE html>
<html lang="pt">
<head>
	<title>Ver Minha Inscrição</title>
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
	desenhaTopoBarra( "inscricao");

	$idSessao = -1;
	$idEvento = -1;
	
	if( isset($_GET['idInscricao']) && $_GET['idInscricao']!="" ){
		$retval = getInscricao($_GET['idInscricao']);
		$row = mysqli_fetch_array($retval);
		$idEvento = $row["idEvento"];
		$idSessao = $row["idUtilizador"];
		$idEstado = $row["idEstado"];
	}
	
	if( $idSessao == -1 || $idEvento == -1 ){
		echo('
			<div class="panel panel-danger">
			<div class="panel-heading">A inscrição está indisponível</div>
			</div>
			');
		exit();
	}

	?>


	<?php

	$rowUser = mysqli_fetch_array(obterUtilizador($idSessao));
	$rowEvento = mysqli_fetch_array(obterEvento( $idEvento ));


	$estadoInscricao = getDescricaoEstadoInscricao($idEstado);

	$estadoPainel="";
	$msgPainel ="";
	$estadoInvalido=false;
	switch ($estadoInscricao) {
		case "validado":
		$estadoPainel = "success";
		$msgPainel ="A sua inscrição foi validada!";
		break;
		case "porValidar":
		$estadoPainel = "info";
		$msgPainel = "A inscrição encontra-se em espera para ser validada";
		break;
		case "recusado":		
		$estadoPainel = "danger";
		$msgPainel = "A seguinte inscrição foi recusada";
		break;

		default:
		$estadoPainel = "warning";
		$msgPainel ="Informação indisponível";
		$estadoInvalido = true;
		break;
	}
	
	echo('
		<div class="panel panel-'.$estadoPainel.'" >
		<div class="panel-heading">'.$msgPainel.'</div>
		</div>
		');
	if(!$estadoInvalido)
		echo('
			<div class="panel-body">O Caçador <b>'.$rowUser['nome'].'</b> :</div>

			<div class="panel panel-default">
			<div class="panel-body">

			<div class="container">
			<table class="table">
			<thead>
			<tr>
			<th>Nome</th>
			<th>Número do Caçador</th>
			<th>Cartão do Cidadão</th>
			<th>Telefone</th>
			<th>Morada</th>
			</tr>
			</thead>
			<tbody>
			<tr>
			<td>'.$rowUser['nome'].'</td>
			<td>'.$rowUser['numCarta'].'</td>
			<td>'.$rowUser['cc'].'</td>
			<td>'.$rowUser['telef'].'</td>
			<td>'.$rowUser['morada'].'</td>
			</tr>
			</tbody>
			</table>
			</div>

			</div>
			</div>
			<div class="panel panel-default">
			<div class="panel-body">realizou a inscrição no evento <b>'.$rowEvento['nome'].'</b>: </div>

			<div class="container">
			<table class="table">
			<thead>
			<tr>
			<th>Evento</th>
			<th>Data</th>
			<th>Horas</th>
			<th>Local</th>
			</thead>
			<tbody>
			<tr>
			<td>'.$rowEvento['nome'].'</td>
			<td>'.$rowEvento['data'].'</td>
			<td>'."12:00".'</td>
			<td>'.$rowEvento['local'].'</td>
			</tr>
			</tbody>
			</table>

			<table class="table">
			<thead>
			<tr><th>Descrição</th></tr>
			</thead>
			<tbody>
			<tr><td>'.$rowEvento['descricao'].'</td></tr>
			</tbody>
			</table>
			</div>
			</div>

			</div>
			');
	echo('
		</div>
	');
	if(!$estadoInvalido)
	echo('
		<center>
		<div>
		<img src="../'. $rowEvento['imagem'] .'" class="img-thumbnail" width="304" height="236">     
		</div>
		</center>
		');
	
	desenharFundoBarra("inscricao");
	
	?>

	</body>
	</html>
	
	<?php mysqli_close($conn); unsetSessionVars(); ?>
	