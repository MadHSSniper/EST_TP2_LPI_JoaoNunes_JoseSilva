<!DOCTYPE html>
<html lang="pt">
	<head>
		<title>Ver Inscritos</title>
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
			$tipoUser = -1;
			
			if( isset($_GET['id']) && $_GET['id']!="" ){
				$idEvento = $_GET['id'];
			}
			
		?>
		
		<?php
			
			$res_inscritos = obterInscritos($idEvento);
			
			
			if( numInscritos( $idEvento ) > 0 ){
				$rowEvento = mysqli_fetch_array(obterEvento( $idEvento ));
				//echo '<div class="col-md-5">';
				echo '<div class="container"><h2>  Evento </h2></div>';
				componenteTabelaEvento($rowEvento);
			?>
			
			<div class="container">
				<h2>  Caçadores Inscritos </h2>          
				<table class="table table-hover">
					<thead>
						<?php
							
							echo("
							<tr>
							<th>ID Utilizador</th>
							<th>Nome</th>
							<th>Porta</th>
							<th>Contacto</th>
							<th>Morada</th> 
							<th>Estado</th>
							<th>Opções</th>
							</tr>
							");
						?>
					</thead>
					<tbody>
						<?php
							
							while( $rowInscritos = mysqli_fetch_array($res_inscritos)){
								$res_user = obterUtilizador($rowInscritos['idUtilizador']);
								$rowUser = mysqli_fetch_array($res_user);
								echo "<tr>";
								echo "<th>".$rowInscritos['idUtilizador']."</th>";
								echo "<th>".$rowUser['nome']."</th>";
								echo "<th>".$rowInscritos['porta']."</th>";
								echo "<th>".$rowUser['telef']."</th>";
								echo "<th>".$rowUser['morada']."</th>";
								echo "<th>".getDescricaoEstadoInscricao($rowInscritos['idEstado'])."</th>";
								echo '<th><form class="btn-group" method="GET" type="button" action="verInscricao.php" >
								<button type="submit" name="idInscricao" value="'.$rowInscritos['idInscricao'].'" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-search"></span> Ver</button>
								</form>';
								echo '<form class="btn-group" method="GET" type="button" action="editarInscricao.php" >
								<button type="submit" name="idInscricao" value="'.$rowInscritos['idInscricao'].'" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-edit"></span> Editar</button>
								</form>';
								echo '<form class="btn-group" method="POST" type="button" action="removerInscricao.php" >
								<button type="submit" name="idInscricao" value="'.$rowInscritos['idInscricao'].'" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-remove"></span> Remover</button>
								</form></th>';
								echo "</tr>";
							}
						?>
					</tbody> 
				</table>
			</div>
			
			
			<?php
				
				}else{
				echo('
				<div class="panel panel-warning" >
				<div class="panel-heading">Não existem inscritos</div>
				</div>
				');
				
			}
			
		?>
		
		
	</body>
</html>

<?php mysqli_close($conn); unsetSessionVars(); ?>
