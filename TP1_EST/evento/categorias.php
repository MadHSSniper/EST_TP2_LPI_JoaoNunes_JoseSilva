<!DOCTYPE html>
<html lang="pt">
<head>
	<title> Categorias </title>
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
	desenhaTopoBarra( "evento" );
	
	if(strcmp(obterDescricaoUtilizador($_SESSION['numC']),'Presidente')!=0){
		echo '
			<div class="panel panel-danger">
				<div class="panel-heading">Não possui permissões para aceder a esta página!!</div>
			</div>
		';
		exit();
	}
	
	echo '
	<div class="panel panel-warning">
		<div class="panel-heading">ATENÇÃO! Eliminar uma categoria elimina também todos os eventos, inscrições e álbuns dependentes da mesma!</div>
	</div>
	';
	?>

	<div class="container">
		<h2>  Categorias </h2>
		<table class="table table-hover">
			<thead>
			  <?php

			  echo("
				<tr>
				<th>idTipo</th>
				<th>Descrição/Nome</th>
				<th>Opções</th>
				</tr>
				");
				?>
			</thead>
			<tbody>
			  <?php
			  $retval = getCategorias();

			  while($row = mysqli_fetch_array( $retval )){
				echo "<tr><td>".$row['idTipo']."</td>";
				echo "<td>".$row['descricao']."</td>";
				echo ('<th><form class="btn-group" method="POST" type="button" action="editarCategoria.php" >
					<button type="submit" name="idTipo" value="'.$row['idTipo'].'" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</button>
				</form>');
				echo ('<form class="btn-group" method="POST" type="button" action="removerCategoria.php" >
					<button type="submit" name="idTipo" value="'.$row['idTipo'].'" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-remove"></span> Remove</button>
				</form></th>');
				echo "</tr>";
			  }
			  ?>
			</tbody> 
		</table>
		
		<?php
		if(strcmp(obterDescricaoUtilizador($_SESSION['numC']),'Presidente')==0){
			echo('
				<div class="container">
				<form method="GET" action="editarCategoria.php" >
					<button class="btn btn-default" type="submit">Adicionar Categoria</button>
				</form>
				</div>
				');
		}
		?>
	</div>

</body>

</html>

<?php mysqli_close($conn); unsetSessionVars(); ?>
