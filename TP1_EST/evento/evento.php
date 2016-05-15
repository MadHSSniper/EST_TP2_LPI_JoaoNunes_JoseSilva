<!DOCTYPE html>
<html lang="pt">
<head>
	<title> Eventos </title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	

	<link href="../css/bootstrap.min.css" rel="stylesheet">
	<script type="text/javascript" src="../js/jquery.min.js"></script>
	<script type="text/javascript" src="../js/bootstrap.min.js"></script>
    <link href="../css/blog-home.css" rel="stylesheet">
	
</head>
<body data-spy="scroll" data-target="#myScrollspy" data-offset="20">

</body>
<body>

	<?php
	include('../funcoesBD.php');
	desenhaTopoBarra( "evento" );
	?>

	<?php
	if( hasLoggedIn() ){
		$tipoUser = obterDescricaoUtilizador($_SESSION['numC']);
	}
	?>

	<div class="container">
		<h2>Eventos</h2>
		<p>Eventos disponibilizados</p>            
	</div>

	<?php

	$retCatg = getCategorias();
	
	echo('
		<div class="container dropdown">
		<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Categorias
		<span class="caret"></span></button>
		<ul class="dropdown-menu">
		<li><a href="?cat=Todos">Ver todos</a></li>
		');

	while($rowCatg = mysqli_fetch_array($retCatg) ){
		echo('
			<li><a href="?cat='.$rowCatg['descricao'].'">'.$rowCatg['descricao'].'</a></li>
			');
	}
	echo('
		</ul>
		</div>
		');

		?>


		<?php
		$opcaoCategoria = "Todos";
		if( isset($_GET['cat']) && $_GET['cat']!=null){
			$retval = obterEventosDescricao( $_GET['cat'] );
			$opcaoCategoria = $_GET['cat'];
		}else{
			$retval = obterEventosDescricao( "Todos" );
		}

		

		if( $retval!=null ){
			echo('
				<br>
				<div class="container" >
				<div class="panel panel-info">
				<div class="panel-heading">'.$opcaoCategoria.'</div>
				</div>
				</div>
				');

			echo('
				<div class="container">
				<table class="table table-hover">
				<thead>
				<tr>
				<th>Nome</th>
				<th>Data</th>
				<th>Descrição</th>
				</tr>
				</thead>

				<tbody>
				');
			while($row = mysqli_fetch_array( $retval )){
				$idEvento = $row['idEvento'];
				echo('


					<tr>
					<td>'.$row['nome'].'</td>
					<td>'.$row['data'].'</td>
					<td>'.$row['descricao'].'</td>

					<td>
					<div>
					<form method="POST" action="verEvento.php" >
					<button class="btn btn-default" type="submit" name="id" value="'.$row['idEvento'].'" >Ver Evento</button>
					</form>
					</div>
					</td>
					');
				if( isset($tipoUser) && $tipoUser == "Presidente"){
					echo ('

						<td>
						<form method="GET" action="../inscricao/verInscritos.php" >
						<button class="btn btn-default" type="submit" name="id" value="'.$row['idEvento'].'" >Ver Incritos
						<span class="badge">'.numInscritos( $idEvento ).'</span></button>	
						</form>						
						</td>
						');
				}

				echo('
					</tr>
					');

			}
			echo('
				</table>
				</div>
				');

		}
		else{
			// sem eventos para mostrar
			echo('
				<br>
				<div class="container" >
				<div class="panel panel-danger">
				<div class="panel-heading">Sem Eventos</div>
				</div>
				</div>
				');

		}

		if( isset($tipoUser) && strcmp($tipoUser,"Presidente")==0 ){
			echo('
				<div class="container">
				<form method="POST" action="../evento/editarEvento.php" >
					<button class="btn btn-default" type="submit" name="code" value = "newEvento" >Adicionar Evento</button>
					<button class="btn btn-default" type="input" formaction="categorias.php">Editar Categorias</button>
				</form>
				</div>
				');
		}


		?>
	</tbody>
</table>
</div>

<?php desenharFundoBarra("evento"); ?>

</body>

</html>

<?php mysqli_close($conn); unsetSessionVars(); ?>