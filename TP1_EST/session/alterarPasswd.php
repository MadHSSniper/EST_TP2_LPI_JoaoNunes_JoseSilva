<!DOCTYPE html>
<html lang="pt">
<head>
	<title> Password </title>
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
	
	if(!hasLoggedIn()){
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
	$passwdErr = $passwdConfirmErr = "";
	$passwd = $passwdConfirm = "";
	$isValid = false;
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
	   $isValid = true;
	   
	   if (empty($_POST["passwd"])) {
		 $passwdErr = "Password is required";
		 $isValid = false;
	   }else{
		 $passwd = $_POST["passwd"];
	   }
		 
	   if (empty($_POST["passwdConfirm"])) {
		 $passwdConfirmErr = "Enter password again";
		 $isValid = false;
	   } else {
		 $passwdConfirm = $_POST["passwdConfirm"];
		 if (!(strcmp($passwd, $passwdConfirm)==0)) {
		   $passwdConfirmErr = "Password must be the same!";
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
		$retval = updatePasswd($_SESSION['numC'], $passwd);
		if($retval){
			echo('
			<div class="panel panel-info">
			<div class="panel-heading">Password alterada com sucesso!</div>
			</div>
			');
			exit();
		}else{
			echo('
			<div class="panel panel-danger">
			<div class="panel-heading">Não foi possível alterar a Password</div>
			</div>
			');
			exit();
		}
	}
	
	?>

	<div class="container">      
		<table class="table table-striped">
			
			<form action="alterarPasswd.php" method="POST">
				<fieldset>
					<legend>Insira a password nova:</legend>
					<div class="form-group <?php if($passwdErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="password" name="passwd" placeholder="Password" value="<?php echo $passwd;?>">
						<span class="error"><?php echo $passwdErr;?></span>
					</div>
					<div class="form-group <?php if($passwdConfirmErr){echo "has-error has-feedback";}?>">
						<input class="form-control" style="width: 300px;" id="focusedInput" type="password" name="passwdConfirm" placeholder="Confirm Password" value="<?php echo $passwdConfirm;?>">
						<span class="error"><?php echo $passwdConfirmErr;?></span>
					</div>
					<br><input class="btn btn-default" type="submit" name="submit" value="Alterar">
					<input class="btn btn-default" type="submit" formaction="../index.php" value="Cancelar"><br><br><br>
				</fieldset>
			</form>
		</table>
	</div>

</body>

</html>

<?php mysqli_close($conn); unsetSessionVars(); ?>
