<!DOCTYPE html>
<html lang="pt">
<head>
  <title>Ver Evento</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  
  <link href="../css/bootstrap.min.css" rel="stylesheet">
  <script type="text/javascript" src="../js/jquery.min.js"></script>
  <script type="text/javascript" src="../js/bootstrap.min.js"></script>
  <link href="../css/blog-home.css" rel="stylesheet">

</head>

<body>
  <?php
  include("../funcoesBD.php");
  desenhaTopoBarra( "evento" );

  $idSessao = -1;
  $idEvento = -1;
  if( hasLoggedIn() ){
    $idSessao = $_SESSION['numC'];
    $tipoUser = obterDescricaoUtilizador($_SESSION['numC']);
  }
  ?>

  <?php
  if( isset($_POST['id']) && $_POST['id']!="" ){
    $idEvento = $_POST['id'];
    $resEvento = obterEvento($idEvento);
  }
  else if( isset($_GET['id']) && $_GET['id']!="" ){
    $idEvento = $_GET['id'];
    $resEvento = obterEvento($idEvento);
	
	if(mysqli_num_rows($resEvento) == 0){
		echo '
			<div class="panel panel-info">
				<div class="panel-heading">Não existe evento!</div>
			</div>
		';
		exit();
	}
  }
  ?>


</br>
</br>
</br>
<center>
  <?php 
  if( isset($resEvento) && $resEvento!=null ){
    $row = mysqli_fetch_array( $resEvento );

    echo('</br></br>');
    echo('
      <div class="container-fluid text-center">   
      <div class="form-group">
      <div class="col-md-6 text-left" >
      <div class="container-fluid text-center">   
      <div class="form-group">
      <div class="col-md-6 text-left" >

      <table class="table">
      <thead>
      <tbody>
      <tr>
      <th>Nome do Evento</th>
      <th>'. $row['nome'] .'</th>
      </tr>
      <tr>
      <th>Descrição</th>
      <th>'. $row['descricao'] .'</th>
      </tr>
      <tr>
      <th>Data</th>
      <th>'. $row['data'] .'</th>
      </tr>
      <tr>
      <th>Tipo</th>
      <th>'. $row['tipo'] .'</th>
      </tr>
      <tr>
      <th>Preço</th>
      <th>'. $row['preco'] .'</th>
      </tr>
      <tr>
      <th>Vagas</th>
      <th>'. $row['vagas'] .'</th>
      </tr>
      <tr>
      <th>Localização</th>
      <th>'. $row['local'] .'</th>
      </tr>
      </tbody>
      </thead>
      </table>
      </div>

      <div class="container-fluid col-md-6" >
      <img src="../'. $row['imagem'] .'" class="img-thumbnail" width="304" height="236">     
      </div>
      </div>
      </div>
      ');

	
	$botaoVerInscricao = "";
	$botaoInscrever = "";
	
	if(existeIdInscricao($idSessao, $idEvento) == false){
		$botaoVerInscricao = 'disabled="disabled"';
	}
	
	if(!existemVagas($idEvento) || existeIdInscricao($idSessao, $idEvento) != false){
		$botaoInscrever = 'disabled="disabled"';
	}
	
	$idInscricao = existeIdInscricao($idSessao, $idEvento);
	echo('
	  <div class="col-md-5">
	  <div class="btn-group">
	  <form class="btn-group" method="GET" type="button" action="../inscricao/registoEvento.php" >
	  <button name="id" '.$botaoInscrever.' class="btn btn-primary" value="'.$idEvento.'">Inscrever</button>
	  </form>
	  <form class="btn-group" method="GET" type="button" action="../inscricao/verInscricao.php" >
	  <button name="idInscricao" '.$botaoVerInscricao.' class="btn btn-primary" value="'.$idInscricao.'">Ver Incrição</button>
	  </form>
	  </div>
	  </div>
	  ');

	if( isset($tipoUser) && $tipoUser == "Presidente")
	  echo('
		<div class="col-md-6">
		<div class="btn-group span" >
		<form class="btn-group" method="GET" type="button" action="../evento/editarEvento.php" >
		<button name="idEvento" class="btn btn-primary" value="'.$idEvento.'">Editar</button>
		</form>
		<form class="btn-group" method="GET" type="button" action="../inscricao/verInscritos.php" >
		<button name="id" class="btn btn-primary" value="'.$idEvento.'">Ver Inscritos
		<span class="badge">'.numInscritos( $idEvento ).'</span></button> 
		</form>
		</div>
		</div>

		</div>

		');
	}

	?>


</center>

  <?php desenharFundoBarra( "evento" ); ?>

</body>
</html>

<?php mysqli_close($conn); unsetSessionVars(); ?>
