<!DOCTYPE html>
<html lang="pt">
<head>
	<title> Inscrição </title>
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

<body>

	<?php
	include('../funcoesBD.php');
	desenhaTopoBarra( "inscricao" );
	
	if(!isset($_SESSION['idEvento'])){
		echo '
			<div class="panel panel-danger">
				<div class="panel-heading">Não tem permissões para aceder esta página!!</div>
			</div>
		';
		exit();
	}
	
	$idEvento = $_SESSION['idEvento'];
	
	if(hasLoggedIn()){
		echo '
			<div class="panel panel-danger">
				<div class="panel-heading">Já tem login efetuado!!<br>Não deve realizar a inscrição através desta página!!</div>
			</div>
		';
		exit();
	}
	
	?>
	
	
	<?php
	// define variables and set to empty values
	$nomeErr = $ccErr = $numCartaErr = $moradaErr = $emailErr = $telefErr = "";
	$nome = $cc = $numCarta = $morada = $email = $telef = "";
	$isValid = false;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	   $isValid = true;

	   if (empty($_POST["nome"])) {
		 $nomeErr = "Name is required";
		 $isValid = false;
	   } else {
		 $nome = test_input($_POST["nome"]);
		 if(!preg_match("/^[a-zA-Z ]*$/",$nome)){
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
		 if (!preg_match("/^[a-zA-Z ]*$/",$morada)) {
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
		$registo = updateCacador($nome, $cc, $numCarta, $morada, $email, $telef);
		$idSessao = getIdSessao($cc);
		$inscricao = registar($idSessao, $idEvento, getPorta($idEvento));
		if($registo && $inscricao){
			echo '
			<div class="panel panel-info">
				<div class="panel-heading">Inscrição no evento efetuada!<br>Poderá ser necessária a validação desta inscrição!!</div>
			</div>
			';
			$_SESSION["idEvento"] = null;
			unset($_SESSION["idEvento"]);
			exit();
		}else{
			echo '
			<div class="panel panel-danger">
				<div class="panel-heading">ERRO! Inscrição não efetuada!</div>
			</div>
			';
			exit();
		}
	}
	
	?>
	
	<div class="container">
		<h2>Inscrição</h2>          
		<table class="table table-striped">
			
			<form action="inscreverEvento.php" method="POST">
				<fieldset>
					<legend>Preencha os seguintes campos:</legend>
					<br>Dados Pessoais:<br>
					<div class="form-group <?php if($nomeErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="text" name="nome" placeholder="Nome" value="<?php echo $nome;?>">
						<span class="error"><?php echo $nomeErr;?></span>
					</div>
					<div class="form-group <?php if($ccErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="text" name="cc" placeholder="Cartão do Cidadão" value="<?php echo $cc;?>">
						<span class="error"><?php echo $ccErr;?></span>
					</div>
					<div class="form-group <?php if($numCartaErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="text" name="numCarta" placeholder="Carta Caçador" value="<?php echo $numCarta;?>">
						<span class="error"><?php echo $numCartaErr;?></span>
					</div>
					<div class="form-group <?php if($moradaErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="text" name="morada" placeholder="Morada" value="<?php echo $morada;?>">
						<span class="error"><?php echo $moradaErr;?></span>
					</div>
					<br>Contactos:<br>
					<div class="form-group <?php if($emailErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="inputError" type="text" name="email" placeholder="Email" value="<?php echo $email;?>">
						<span class="error"><?php echo $emailErr;?></span>
					</div>
					<div class="form-group <?php if($telefErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="text" name="telef" placeholder="Telefone" value="<?php echo $telef;?>">
						<span class="error"><?php echo $telefErr;?></span>
					</div>
					<br><input class="btn btn-default" type="submit" name="submit" value="Inscrever">
				</fieldset>
			</form>
		</table>
	</div>
	
</body>

</html>

<?php mysqli_close($conn); unsetSessionVars(); ?>
