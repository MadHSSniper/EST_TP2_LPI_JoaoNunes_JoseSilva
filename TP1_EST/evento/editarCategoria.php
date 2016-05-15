<!DOCTYPE html>
<html lang="pt">
<head>
	<title> Editar Categoria </title>
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
	<style>
		.modal-header, h4, .close {
			background-color: #5cb85c;
			color:white !important;
			text-align: center;
			font-size: 30px;
		}
		.modal-footer {
			background-color: #f9f9f9;
		}
	</style>
</head>
<body data-spy="scroll" data-target="#myScrollspy" data-offset="20">

</body>
<body>

	<?php
	include('../funcoesBD.php');
	desenhaTopoBarra( "evento" );
	
	if(isset($_POST['idTipo'])){
		$idTipo = $_POST['idTipo'];
		
		$descricao = getCategoria($idTipo);	
		
		$_SESSION["idCat"] = $idTipo;
		$_SESSION["editar"] = true;
		
	}else{
		$descricao = "";
	}
	
	if( !(hasLoggedIn() && strcmp(obterDescricaoUtilizador($_SESSION['numC']),"Presidente")==0) ){
		// Nao tem permissao para aceder a esta pagina
		echo('			
			<div class="panel panel-danger">
			<div class="panel-heading">Não tem permissão para aceder a esta página</div>
			<div href="index.php" class="panel-body"></div>
			</div>
		');
		desenharFundoBarra("evento");
		exit();
	}
	?>
	
	<?php
	
	// define variables and set to empty values
	$descricaoErr = "";
	
	$isValid = false;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$isValid = true;
		
		if (empty($_POST["descricao"])) {
			$descricaoErr = "Insira o nome ou uma breve descrição do tipo de evento";
			$isValid = false;
		} else {
			$descricao = test_input($_POST["descricao"]);
			if(!preg_match("/^\p{L}[\p{L}\d .-]+$/u",$descricao)){
				$descricaoErr = "Descrição/Nome Inválido!";
				$isValid = false;
			}
		}
	}

	function test_input($dados) {
		$dados = trim($dados);
		$dados = stripslashes($dados);
		$dados = htmlspecialchars($dados);
		return $dados;
	}
	
	if($isValid){
		$retval = isset($_SESSION["editar"]) ? alterarCategoria($_SESSION["idCat"], $descricao) : criarCategoria($descricao);
		if($retval){
			echo '
			<div class="panel panel-info">
				<div class="panel-heading">Categoria Guardada!</div>
			</div>
			';
			unset($_SESSION["idCat"]);
			unset($_SESSION["editar"]);
			exit();
		}else{
			echo '
			<div class="panel panel-danger">
				<div class="panel-heading">ERRO! Não foi possível guardar a categoria!</div>
			</div>
			';
			unset($_SESSION["idCat"]);
			unset($_SESSION["editar"]);
			exit();
		}
	}
	
	?>
	
	<div class="container">
		<h2><?php if(isset($_SESSION["editar"])){echo 'Editar Categoria';}else{echo 'Criar Categoria';} ?></h2>
		<table class="table table-striped">
			
			<form action="editarCategoria.php" method="POST">
				<fieldset>
					<table class="table table-striped">
						<thead>
							<tbody>
								<tr>
									<div class="form-group <?php if($descricaoErr){echo "has-error has-feedback";}?>">
										<th>Descrição/Nome</th>
										<th>
											<?php
											echo ('<input class="form-control" style="width: 300px;" 
												id="focusedInput" type="text" 
												name="descricao" placeholder="ex: Jantarada" 
												value="'.$descricao.'">');
											?>
										<span class="error"><?php echo $descricaoErr;?></span>
										</th>
									</div>
								</tr>
							</tbody>
						</thead>
					</table>
					<br>
					<input class="btn btn-default" type="submit" name="submit" value="Guardar">
					<input class="btn btn-default" type="submit" formaction="../evento/categorias.php" value="Cancelar">
					<?php if(isset($_SESSION["editar"])){ ?>
					<form action="removerInscricao.php" method="POST">
						<button class="btn btn-default" type="submit" name="idTipo" formaction="removerCategoria.php" value="<?php echo $_SESSION["idCat"]; ?>">Apagar Categoria</button>
					</form>
					<?php } ?>
					<br><br><br>
				</fieldset>
			</form>
		</table>
	</div>
	
</body>

</html>

<?php mysqli_close($conn); ?>
