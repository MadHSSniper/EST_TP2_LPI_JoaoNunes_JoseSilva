<?php
ob_start();
include('../funcoesBD.php');
$username = $_POST["username"];
$passwd = $_POST["passwd"];
$cryptPasswd = hash("sha512", $passwd);
$numC = null;

$resultado = login($username, $cryptPasswd);
if ($resultado) {
	while($registo = mysqli_fetch_array($resultado)){
		$numC = $registo['numC'];
		$numTipo = $registo['numTipo'];
		$nome = $registo['nome'];
	}
	if($numC==null){
		print ("Invalid username and/or password, please <a href='login.php'>try again</a>");
	}else{
		$_SESSION["numC"] = $numC;
		$_SESSION["numTipo"] = $numTipo;
		$_SESSION["nome"] = $nome;
		echo("Logging in, please wait");
		header('Refresh: 1; success.php');
	}
}
else
	echo("FATAL ERROR - Login Failed!!!");
?>

<?php mysqli_close($conn); unsetSessionVars(); ?>
