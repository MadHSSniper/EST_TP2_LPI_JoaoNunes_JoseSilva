<!DOCTYPE html>
<html lang="pt">
	<head>
		<title> Editar Inscrição </title>
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
			desenhaTopoBarra( "inscricao" );
			
			if(isset($_GET['idInscricao'])){
				$idInscricao = $_GET['idInscricao'];
				
				$inscricao = getInscricao($idInscricao);	
				$row = mysqli_fetch_array($inscricao);
				
				$estado = $row['idEstado'];
				$porta = $row['porta'];
				$portaVelha = $row['porta'];
				$idEvento = $row['idEvento'];
				
				$_SESSION["idInscricao"] = $idInscricao;
				$_SESSION["idEvento"] = $idEvento;
				$_SESSION["portaVelha"] = $portaVelha;
			}
			
			if( !(hasLoggedIn() && strcmp(obterDescricaoUtilizador($_SESSION['numC']),"Presidente")==0) ){
				// Nao tem permissao para aceder a esta pagina
				echo('			
				<div class="panel panel-danger">
				<div class="panel-heading">Não tem permissão para aceder a esta página</div>
				<div href="index.php" class="panel-body"></div>
				</div>
				');
				desenharFundoBarra("inscricao");
				exit();
			}
		?>
		
		<?php
			
			// define variables and set to empty values
			$portaErr = "";
			
			$isValid = false;
			
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$isValid = true;
				
				if (empty($_POST["porta"])) {
					$porta = "";
					$dataErr = "Precisa de uma porta!";
					$isValid = false;
					}else if($_POST["porta"] == false || $_POST["porta"] == 0 || !$_POST["porta"]){
					$porta = $_SESSION["portaVelha"];
					$dataErr = "Precisa de uma porta!";
					$isValid = false;
					}else{
					$porta = test_input($_POST["porta"]);
					if( !preg_match("/^\d+$/",$porta) || portaExists($_SESSION["idEvento"], $_SESSION["portaVelha"], $porta) || !($porta <= getCapacidadeVagas($_SESSION["idEvento"])) ){
						echo $_SESSION["portaVelha"];
						echo getCapacidadeVagas($_SESSION["idEvento"]);
						$portaErr = "Porta inválida ou ocupada!";
						$isValid = false;
					}
				}
				
				if(!empty($_POST["estado"])){
					$estado = $_POST["estado"];
					if(!isset($estado)){
						echo('			
						<div class="panel panel-danger">
						<div class="panel-heading">Não tem permissão para aceder a esta página</div>
						<div href="index.php" class="panel-body"></div>
						</div>
						');
						desenharFundoBarra("inscricao");
						exit();
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
				$retval = updateInscricao($_SESSION["idInscricao"], $estado, $porta);
				if($retval){
					echo '
					<div class="panel panel-info">
					<div class="panel-heading">Inscrição alterada!</div>
					</div>
					';
					unset($_SESSION["idInscricao"]);
					unset($_SESSION["idEvento"]);
					unset($_SESSION["portaVelha"]);
					exit();
					}else{
					echo '
					<div class="panel panel-danger">
					<div class="panel-heading">ERRO! Não foi possível alterar a inscrição!</div>
					</div>
					';
					unset($_SESSION["idInscricao"]);
					unset($_SESSION["idEvento"]);
					unset($_SESSION["portaVelha"]);
					exit();
				}
			}
			
		?>
		
		<div class="container">
			<h2>Editar Inscrição</h2>
			<table class="table table-striped">
				
				<form action="editarInscricao.php" method="POST">
					<fieldset>
						<table class="table table-striped">
							<thead>
								<tbody>
									<tr>
										<div class="form-group">
											<th>Estado</th>
											<th>
												<select class="form-control" style="width: 300px;" id="sel1" name="estado">
													<option value="1" <?php if($estado == 1){echo " selected";} ?> >Por Validar</option>
													<option value="2" <?php if($estado == 2){echo " selected";} ?> >Validar</option>
													<option value="3" <?php if($estado == 3){echo " selected";} ?> >Recusar</option>
												</select>
											</th>
										</div>
									</tr>
									<tr>
										<div class="form-group <?php if($portaErr){echo "has-error has-feedback";}?>">
											<th>Porta</th>
											<th>
												<?php
													echo ('<input class="form-control" style="width: 300px;" 
													id="focusedInput" type="text" 
													name="porta" placeholder="Porta" 
													value="'.$porta.'">');
												?>
												<span class="error"><?php echo $portaErr;?></span>
											</th>
										</div>
									</tr>
								</tbody>
							</thead>
						</table>
						<br>
						<input class="btn btn-default" type="submit" name="submit" value="Alterar Inscrição">
						<input class="btn btn-default" type="submit" formaction="../evento/evento.php" value="Cancelar">
						<form action="removerInscricao.php" method="POST">
							<button class="btn btn-default" type="submit" name="idInscricao" formaction="removerInscricao.php" value="<?php echo $idInscricao; ?>">Apagar Inscricao</button>
						</form>
						<br><br><br>
					</fieldset>
				</form>
			</table>
		</div>
		
	</body>
	
</html>

<?php mysqli_close($conn); ?>
