<?php
include "../funcoesBD.php";
if (!hasLoggedIn() || ($_SESSION['numTipo'] > 3)) {
	?>
	Não está logado<br/>
	Por favor, faça <a href="login.php">Login</a>
	<?php
}else{
	?>
	Bem vindo(a) <?php echo $_SESSION['nome'];
	header('Refresh: 1; ../index.php');
}
?>

<?php mysqli_close($conn); unsetSessionVars(); ?>