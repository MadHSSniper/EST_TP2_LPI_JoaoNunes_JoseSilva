<!DOCTYPE html>
<html lang="pt">
<head>
	<title> Membros </title>
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
	
	if(strcmp(obterDescricaoUtilizador($_SESSION['numC']),'Presidente')!=0){
		echo '
			<div class="panel panel-danger">
				<div class="panel-heading">Não possui permissões para aceder a esta página!!</div>
			</div>
		';
		exit();
	}
	
	?>

	<div class="container">
		<h2>  Membros </h2>          
		<table class="table table-hover">
			<thead>
			  <?php

			  echo("
				<tr>
				<th>Id</th>
				<th>Tipo</th>
				<th>Nome</th>
				<th>CC/BI</th>
				<th>Carta Caçador</th>
				<th>Morada</th>
				<th>Email</th>
				<th>Telefone</th>
				<th>Opções</th>
				</tr>
				");
				?>
			</thead>
			<tbody>
			  <?php
			  $retval = getMembros();

			  while($row = mysqli_fetch_array( $retval )){
				echo "<tr><td>".$row['numC']."</td>";
				echo "<td>".obterDescricaoUtilizador($row['numC'])."</td>";
				echo "<td>".$row['nome']."</td>";
				echo "<td>".$row['cc']."</td>";
				echo "<td>".$row['numCarta']."</td>";
				echo "<td>".$row['morada']."</td>";
				echo "<td>".$row['email']."</td>";
				echo "<td>".$row['telef']."</td>";
				echo ('<th><form class="btn-group" method="GET" type="button" action="user.php" >
					<button type="submit" name="select" value="'.$row['numC'].'" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> Edit</button>
				</form>');
				echo ('<form class="btn-group" method="POST" type="button" action="removerUtilizador.php" >
					<button type="submit" name="numC" value="'.$row['numC'].'" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-remove"></span> Remove</button>
				</form></th>');
				echo "</tr>";
			  }
			  ?>
			</tbody> 
		</table>
	</div>

</body>

</html>

<?php mysqli_close($conn); unsetSessionVars(); ?>
