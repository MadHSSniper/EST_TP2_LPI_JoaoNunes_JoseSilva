<!DOCTYPE html>
<html lang="pt">
<head>
	<title> Menu de Evento </title>
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
	
	if(isset($_GET['idEvento'])){
		$idEvento = $_GET['idEvento'];
		
		$evento = obterEvento($idEvento);
		$rowEvento = mysqli_fetch_array($evento);
		
		$nome = $rowEvento['nome'];
		$data = $rowEvento['data'];
		$hora = $rowEvento['hora'];
		$preco = $rowEvento['preco'];
		$vagas = $rowEvento['vagas'];
		$tipo = $rowEvento['tipo'];
		$local = $rowEvento['local'];
		$descricao = $rowEvento['descricao'];
		$imagem = $rowEvento['imagem'];
		
		$_SESSION["idEvento"] = $idEvento;
		$_SESSION["modo"] = "editar";
		
	}else{
		$nome = $data = $hora = $preco = $vagas = $local = $descricao = $imagem = "";
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
	$nomeErr = $dataErr = $horaErr = $precoErr = $vagasErr = $tipoErr = $localErr = $descricaoErr = $imagemErr = "";
	
	$isValid = false;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST["code"]) && $_POST["code"] == "newEvento"){
			$nome = $data = $hora = $preco = $vagas = $local = $descricao = $imagem = "";
			$tipo = 1;
			
			$_SESSION["modo"] = "criar";
			$_SESSION["idEvento"] = null;
		}else{
			$isValid = true;
		   
			if (empty($_POST["nome"])) {
				$nomeErr = "Precisa de ter um nome!";
				$isValid = false;
			} else {
				$nome = test_input($_POST["nome"]);
				if(!preg_match("/^\p{L}[\p{L} .-]+$/u",$nome)){
					$nomeErr = "Nome Inválido!";
					$isValid = false;
				}
			}
			
			if (empty($_POST["data"])) {
				$dataErr = "Precisa de uma data!";
				$isValid = false;
			} else {
				$data = test_input($_POST["data"]);
				if(!preg_match("/^[0-9]{4}-[0-1][0-9]-[0-3][0-9]$/",$data)){
					$dataErr = "Data inválida!";
					$isValid = false;
				}
			}
			
			if (empty($_POST["hora"])) {
				$horaErr = "Hora inexistente!";
				$isValid = false;
			} else {
				$hora = test_input($_POST["hora"]);
				if(!preg_match("/(2[0-4]|[01][1-9]|10):([0-5][0-9])/",$hora)){
					$horaErr = "Hora Inválida!";
					$isValid = false;
				}
			}
			
			if(!empty($_POST["tipo"])){
				$tipo = $_POST["tipo"];
				if(!isset($tipo)){
					echo('			
						<div class="panel panel-danger">
						<div class="panel-heading">Não tem permissão para aceder a esta página</div>
						<div href="index.php" class="panel-body"></div>
						</div>
					');
					desenharFundoBarra("evento");
					exit();
				}
			}
			
			if (empty($_POST["preco"])) {
				$precoErr = "Preço é necessário!";
				$isValid = false;
			} else {
				$preco = test_input($_POST["preco"]);
				if(!preg_match("/^\d*[\.]\d{2}$/",$preco)){ //^((?:\d{1,3}[,\.]?)+\d*)$
					$precoErr = "Preço Inválido!";
					$isValid = false;
				}
			}
			
			if (empty($_POST["vagas"])) {
				$vagasErr = "Insira número de vagas!";
				$isValid = false;
			} else {
				$vagas = test_input($_POST["vagas"]);
				if(!preg_match("/^\d+$/",$vagas)){
					$vagasErr = "Valor Inválido!";
					$isValid = false;
				}
			}
			
			if (empty($_POST["local"])) {
				$localErr = "Necessário existir um local!";
				$isValid = false;
			} else {
				$local = test_input($_POST["local"]);
				if(!preg_match("/^\p{L}[\p{L} .-]+$/u",$local)){
					$localErr = "Local Inválido!";
					$isValid = false;
				}
			}
			
			if (empty($_POST["descricao"])) {
				$descricaoErr = "Uma descrição é necessária!";
				$isValid = false;
			} else {
				$descricao = test_input($_POST["descricao"]);
				if(!preg_match("/^\p{L}[\p{L} .-]+$/u",$descricao)){
					$descricaoErr = "Descrição Inválida!";
					$isValid = false;
				}
			}
			 // Expressao demasiado complexa
			if (empty($_POST["imagem"])) {
				$imagemErr = "Um caminho para a imagem é necessário!";
				$isValid = false;
			}else{
				$imagem = $_POST["imagem"];
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
		$retval = updateEvento($_SESSION["idEvento"], $nome, $data, $hora, $preco, $vagas, $tipo, $local, $descricao, $imagem);
		if($retval){
			echo '
			<div class="panel panel-info">
				<div class="panel-heading">Dados do evento guardados!</div>
			</div>
			';
			unset($_SESSION["idEvento"]);
			unset($_SESSION["modo"]);
			exit();
		}else{
			echo '
			<div class="panel panel-danger">
				<div class="panel-heading">ERRO! Não foi possível guardar o evento!</div>
			</div>
			';
			unset($_SESSION["idEvento"]);
			unset($_SESSION["modo"]);
			exit();
		}
	}
	
	echo '
	<div class="panel panel-warning">
		<div class="panel-heading">ATENÇÃO! Eliminar um evento elimina também todas as inscrições e álbuns dependentes do mesmo!</div>
	</div>
	';
	?>
	
	<div class="container">
		<h2>Menu de Evento</h2>
		<table class="table table-striped">
			
			<form action="editarEvento.php" method="POST">
				<fieldset>
					<table class="table table-striped">
						<thead>
							<tbody>
								<tr>
									<div class="form-group <?php if($nomeErr){echo "has-error has-feedback";}?>">
										<th>Nome</th>
										<th>
											<?php
											echo ('<input class="form-control" style="width: 300px;" 
												id="focusedInput" type="text"
												name="nome" placeholder="Nome" 
												value="'.$nome.'">');
											?>
										<span class="error"><?php echo $nomeErr;?></span>
										</th>
									</div>
								</tr>
								<tr>
									<div class="form-group <?php if($descricaoErr){echo "has-error has-feedback";}?>">
										<th>Descrição</th>
										<th>
											<?php
											echo ('<input class="form-control" style="width: 300px;" 
												id="focusedInput" type="text"
												name="descricao" placeholder="Descrição" 
												value="'.$descricao.'">');
											?>
										<span class="error"><?php echo $descricaoErr;?></span>
										</th>
									</div>
								</tr>
								<tr>
									<div class="form-group <?php if($dataErr){echo "has-error has-feedback";}?>">
										<th>Data</th>
										<th>
											<?php
											echo ('<input class="form-control" style="width: 300px;" 
												id="focusedInput" type="text"
												name="data" placeholder="YYYY-MM-DD" 
												value="'.$data.'">');
											?>
										<span class="error"><?php echo $dataErr;?></span>
										</th>
									</div>
								</tr>
								<tr>
									<div class="form-group <?php if($horaErr){echo "has-error has-feedback";}?>">
										<th>Hora</th>
										<th>
											<?php
											echo ('<input class="form-control" style="width: 300px;" 
												id="focusedInput" type="text"
												name="hora" placeholder="24:00:00" 
												value="'.$hora.'">');
											?>
										<span class="error"><?php echo $horaErr;?></span>
										</th>
									</div>
								</tr>
								<tr>
									<div class="form-group <?php if($tipoErr){echo "has-error has-feedback";}?>">
										<th>Tipo</th>
										<th>
										<select class="form-control" style="width: 300px;" id="sel1" name="tipo">
										<?php
											$categorias = getCategorias();
											while($info = mysqli_fetch_array($categorias)){
												echo '<option value="'.$info["idTipo"].'"'; if($tipo == $info["idTipo"]){echo " selected";} echo '>'.$info["descricao"].'</option>';
											}
										?>
										</select>
										<span class="error"><?php echo $tipoErr;?></span>
										</th>
									</div>
								</tr>
								<tr>
									<div class="form-group <?php if($precoErr){echo "has-error has-feedback";}?>">
										<th>Preço</th>
										<th>
											<?php
											echo ('<input class="form-control" style="width: 300px;" 
												id="focusedInput" type="text"
												name="preco" placeholder="0.00" 
												value="'.$preco.'">');
											?>
										<span class="error"><?php echo $precoErr;?></span>
										</th>
									</div>
								</tr>
								<tr>
									<div class="form-group <?php if($vagasErr){echo "has-error has-feedback";}?>">
										<th>Vagas por preencher</th>
										<th>
											<?php
											echo ('<input class="form-control" style="width: 300px;" 
												id="focusedInput" type="text"
												name="vagas" placeholder="Vagas" 
												value="'.$vagas.'">');
											?>
										<span class="error"><?php echo $vagasErr;?></span>
										</th>
									</div>
								</tr>
								<tr>
									<div class="form-group <?php if($localErr){echo "has-error has-feedback";}?>">
										<th>Local</th>
										<th>
											<?php
											echo ('<input class="form-control" style="width: 300px;" 
												id="focusedInput" type="text"
												name="local" placeholder="Local" 
												value="'.$local.'">');
											?>
										<span class="error"><?php echo $localErr;?></span>
										</th>
									</div>
								</tr>
								<tr>
									<div class="form-group <?php if($imagemErr){echo "has-error has-feedback";}?>">
										<th>Imagem</th>
										<th>
											<?php
											echo ('<input class="form-control" style="width: 300px;" 
												id="focusedInput" type="text"
												name="imagem" placeholder="Imagem" 
												value="'.$imagem.'">');
											?>
										<span class="error"><?php echo $imagemErr;?></span>
										</th>
									</div>
								</tr>
							</tbody>
						</thead>
					</table>
					<br>
					<input class="btn btn-default" type="submit" name="submit" value="<?php if($_SESSION["modo"] == "editar"){echo 'Alterar Evento';}else{echo 'Guardar Evento';} ?>">
					<input class="btn btn-default" type="submit" formaction="evento.php" value="Cancelar">
					<?php if($_SESSION["modo"] == "editar"){ ?>
					<form action="removerEvento.php" method="POST">
						<button class="btn btn-default" type="submit" name="idEvento" formaction="removerEvento.php" value="<?php echo $idEvento; ?>">Apagar Evento</button>
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