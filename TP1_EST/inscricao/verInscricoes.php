<!DOCTYPE html>
<html lang="pt">
<head>
	<title> Inscrições </title>
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
	desenhaTopoBarra( "inscricao" );
	
	$retval = getInscricoes($_SESSION['numC']);
	
	if(!temInscricoes($retval)){
		echo('
		<div class="panel panel-info">
		<div class="panel-heading">Não possui nenhuma inscrição</div>
		</div>');
	}else{
	echo '
	<div class="container">
		<h2>  Inscrições </h2>          
		<table class="table table-hover">
			<thead>
				<tr>
				<th>Nome</th>
				<th>Data</th>
				<th>Descrição Evento</th>
				<th>Porta</th>
				<th>Estado</th>
				<th>Opções</th>
				</tr>
			</thead>
			<tbody>';
				$retval = getInscricoes($_SESSION['numC']);
				while($row = mysqli_fetch_array( $retval )){
					echo "<tr><td>".$row['nome']."</td>";
					echo "<td>".$row['data']."</td>";
					echo "<td>".$row['descricaoEvento']."</td>";
					echo "<td>".$row['porta']."</td>";
					echo "<td>".$row['descricaoInscricao']."</td>";
					echo '<th>
					<form class="btn-group" method="GET" type="button" action="verInscricao.php" >
						<button type="submit" name="idInscricao" value="'.$row['idInscricao'].'" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Ver</button>
					</form>';
					echo '<form class="btn-group" method="POST" type="button" action="removerInscricao.php" >
						<button type="submit" name="idInscricao" value="'.$row['idInscricao'].'" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-remove"></span> Remover</button>
					</form></th>';
					echo "</tr>";
				}
			echo '
			</tbody> 
		</table>
	</div>';
	}
	?>

</body>

</html>

<?php mysqli_close($conn); unsetSessionVars(); ?>
