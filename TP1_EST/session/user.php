<!DOCTYPE html>
<html lang="pt">
<head>
	<title> Editar Utilizador </title>
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
	
	if(isset($_GET['select'])){
		$numC = $_GET['select'];
		
		$_SESSION["editedUser"] = $numC;
		
		$user = obterUtilizador($numC);
		$rowUser = mysqli_fetch_array($user);
		
		$adminMode = true;
		
	}else if(isset($_SESSION["editedUser"])){
		$numC = $_SESSION["editedUser"];
		
		$user = obterUtilizador($numC);
		$rowUser = mysqli_fetch_array($user);
		
		$adminMode = true;
		
	}else if(hasLoggedIn()){
		$user = obterUtilizador($_SESSION['numC']);
		$rowUser = mysqli_fetch_array($user);
		
		$adminMode = false;
		
	}else{
		echo '
			<div class="panel panel-danger">
				<div class="panel-heading">Não possui permissões para aceder a esta página!!</div>
			</div>
		';
		exit();
	}
	
	?>
	
	
	<?php
	// define variables and set to empty values
	$usernameErr = $passwdErr = $passwdConfirmErr = $numCErr = $numTipoErr = $nomeErr = $ccErr = $numCartaErr = $moradaErr = $emailErr = $telefErr = "";
	
	$username = $rowUser['username'];
	$passwd = $rowUser['passwd'];
	$passwdConfirm = $rowUser['passwd'];
	$numC = $rowUser['numC'];
	$numTipo = $rowUser['numTipo'];
	$nome = $rowUser['nome'];
	$cc = $rowUser['cc'];
	$numCarta = $rowUser['numCarta'];
	$morada = $rowUser['morada'];
	$email = $rowUser['email'];
	$telef = $rowUser['telef'];
	
	$isValid = false;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$isValid = true;
		
		if(!empty($_POST["numTipo"])){
			$numTipo = $_POST["numTipo"];
			if(!isset($numTipo)){
				echo('			
					<div class="panel panel-danger">
					<div class="panel-heading">Não tem permissão para aceder a esta página</div>
					<div href="index.php" class="panel-body"></div>
					</div>
				');
				desenharFundoBarra("session");
				exit();
			}
		}
		
		if (empty($_POST["nome"])) {
			$nomeErr = "Name is required";
			$isValid = false;
		} else {
			$nome = test_input($_POST["nome"]);
			if(!preg_match("/^\p{L}[\p{L} .-]+$/u",$nome)){
				$nomeErr = "Invalid name!";
				$isValid = false;
			}
		}

		if (empty($_POST["cc"])) {
			$ccErr = "CC is required";
			$isValid = false;
		} else {
			$cc = test_input($_POST["cc"]);
			if(!preg_match("/\A([0-9]{9}[A-Z]{2}[0-9]|[0-9]{8})\z/",$cc)){
				$ccErr = "Invalid CC number! Format: 123456789AB1";
				$isValid = false;
			}
		}
		
		if (empty($_POST["numCarta"])) {
			$numCartaErr = "NumCarta is required";
			$isValid = false;
		} else {
			$numCarta = test_input($_POST["numCarta"]);
			// check if name only contains letters and whitespace
			if (!preg_match("/\A[0-9]{9}\z/",$numCarta)) {
				$numCartaErr = "Invalid NumCarta!! Format: 123456789";
				$isValid = false;		   
			}
		}
		
		if (empty($_POST["morada"])) {
			$moradaErr = "An address is required";
			$isValid = false;
		} else {
			$morada = test_input($_POST["morada"]);
			// check if name only contains letters and whitespace
			if (!preg_match("/^\p{L}[\p{L} .-]+$/u",$morada)) {
				$moradaErr = "Invalid Address!";
				$isValid = false;
			}
		}
		
		if (empty($_POST["email"])) {
			$emailErr = "Email is required";
			$isValid = false;
		} else {
			$email = test_input($_POST["email"]);
			// check if name only contains letters and whitespace
			if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
				$emailErr = "Invalid email!"; 
				$isValid = false;
			}
		}
		
		if (empty($_POST["telef"])) {
			$telefErr = "Phone number is required";
			$isValid = false;
		} else {
			$telef = test_input($_POST["telef"]);
			// check if name only contains letters and whitespace
			if (!preg_match("/\A(9[0-9]{8}|2[0-9]{8})\z/",$telef)) {
				$telefErr = "Invalid phone number!";
				$isValid = false;
			}
		}
	}

	function test_input($data) {
		$data = trim($data);
		$data = stripslashes($data);
		$data = htmlspecialchars($data);
		return $data;
	}
	
	if($isValid){
		$retval = updateMember($numTipo, $numC, $nome, $cc, $numCarta, $morada, $email, $telef);
		if($retval){
			echo '
			<div class="panel panel-info">
				<div class="panel-heading">Dados alterados com sucesso!</div>
			</div>
			';
			unset($_SESSION["editedUser"]);
			exit();
		}else{
			echo '
			<div class="panel panel-danger">
				<div class="panel-heading">ERRO! Dados não alterados!</div>
			</div>
			';
			unset($_SESSION["editedUser"]);
			exit();
		}
	}
	
	?>
	
	<div class="container">
		<h2><?php echo "Conta de: ".$nome ?></h2>
		<table class="table table-striped">
			
			<form action="user.php" method="POST">
				<fieldset>
					<table class="table table-hover">
						<thead>
							<?php
							echo("<tr>");
							if($adminMode){
								echo("<th>Id</th>");
								echo("<th>Tipo</th>");
							}
							echo("<th>Nome</th>");
							echo("<th>CC/BI</th>");
							echo("<th>Carta Caçador</th>");
							echo("<th>Morada</th>");
							echo("<th>Email</th>");
							echo("<th>Telefone</th>");
							echo("</tr>");
							?>
						</thead>
						<tbody>
							<?php
							if($adminMode){
								echo "<tr><td>".$numC."</td>";
								echo "<td>".obterDescricaoUtilizador($numC)."</td>";
							}
							echo "<td>".$nome."</td>";
							echo "<td>".$cc."</td>";
							echo "<td>".$numCarta."</td>";
							echo "<td>".$morada."</td>";
							echo "<td>".$email."</td>";
							echo "<td>".$telef."</td>";
							echo "</tr>";
							?>
						</tbody> 
					</table>
					
					
					<?php
					if($adminMode){
						echo '<br>Dados Administrativos:<br>
						<div class="form-group'; if($numTipoErr){echo "has-error has-feedback";} echo'">
							<select class="form-control" style="width: 300px;" id="sel1" name="numTipo" data-toggle="tooltip" data-placement="bottom" title="Tipo de utilizador">';
								if($numTipo != 3){echo '<option value="1"'; if($numTipo == 1){echo " selected";} echo'>Presidente</option>';}
								if($numTipo != 3){echo '<option value="2"'; if($numTipo == 2){echo " selected";} echo'>Socio</option>';}
								if($numTipo == 3){echo '<option value="3"'; if($numTipo == 3){echo " selected";} echo'>Caçador</option>';}
							echo'</select>
							<span class="error">'; echo $numTipoErr; echo'</span>
						</div>';
					}
					?>
					
					<br>Dados Pessoais:<br>
					<div class="form-group <?php if($nomeErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="text" name="nome" placeholder="Nome" data-toggle="tooltip" data-placement="bottom" title="Nome" value="<?php echo $nome;?>">
						<span class="error"><?php echo $nomeErr;?></span>
					</div>
					<div class="form-group <?php if($ccErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="text" name="cc" placeholder="Cartão do Cidadão" data-toggle="tooltip" data-placement="bottom" title="Numero Cartão do cidadão" value="<?php echo $cc;?>">
						<span class="error"><?php echo $ccErr;?></span>
					</div>
					<div class="form-group <?php if($numCartaErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="text" name="numCarta" placeholder="Carta Caçador" data-toggle="tooltip" data-placement="bottom" title="Numero carta de caçador" value="<?php echo $numCarta;?>">
						<span class="error"><?php echo $numCartaErr;?></span>
					</div>
					<div class="form-group <?php if($moradaErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="text" name="morada" placeholder="Morada" data-toggle="tooltip" data-placement="bottom" title="Morada" value="<?php echo $morada;?>">
						<span class="error"><?php echo $moradaErr;?></span>
					</div>
					
					
					<br>Contactos:<br>
					<div class="form-group <?php if($emailErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="inputError" type="text" name="email" placeholder="Email" data-toggle="tooltip" data-placement="bottom" title="Email" value="<?php echo $email;?>">
						<span class="error"><?php echo $emailErr;?></span>
					</div>
					<div class="form-group <?php if($telefErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="text" name="telef" placeholder="Telefone" data-toggle="tooltip" data-placement="bottom" title="Telefone" value="<?php echo $telef;?>">
						<span class="error"><?php echo $telefErr;?></span>
					</div>
					<br><input class="btn btn-default" type="submit" name="submit" value="Guardar">
					<input class="btn btn-default" type="submit" formaction="<?php if($adminMode){echo "members.php";}else{echo "../index.php";} ?>" value="Cancelar"><br><br><br>
				</fieldset>
			</form>
		</table>
	</div>
	
</body>

</html>

<?php mysqli_close($conn); ?>
