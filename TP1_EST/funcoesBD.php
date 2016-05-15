<?php
session_start();
include "/connection/basedados.php";


/*
  Retorna todos os eventos da tabela
*/
	function obterEventos(){
		global $conn;
		// Liga a tabela na base de dados
		$sql = 'SELECT * FROM evento';
        //Seleciona a base de dados
		$retval = mysqli_query( $conn, $sql );

		if(! $retval ){
		  die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Retorna true caso o utilizador tenha efetuado o login, false caso contrário
*/
	function hasLoggedIn(){
		return (isset($_SESSION['numC'])) && ($_SESSION['numC'] != NULL);
	}

/*
  Retorna o nome do utilizador "logado"
*/
	function getNome(){
		if(hasLoggedIn()){
			return $_SESSION['nome'];
		}
		return false;
	}

/*
  Regista um utilizador novo, adicionando-o à base de dados com tipo 2 - "Sócio"
*/
	function registerUser($username, $passwd, $nome, $cc, $numCarta, $morada, $email, $telef){
		$cryptPasswd = hash("sha512", $passwd); //encriptar password com sha512
		
		global $conn;
		if(ccExists($cc)){
			$sql = "UPDATE utilizador SET numTipo = 2, username = '".$username."', passwd = '".$cryptPasswd."', nome = '".$nome."', numCarta = '".$numCarta."', 
			morada = '".$morada."', email = '".$email."', telef = ".$telef." 
			WHERE utilizador.cc = '".$cc."';";
		}else{
			$sql = "INSERT INTO utilizador(numTipo, username, nome, cc, numCarta, morada, email, passwd, telef) 
			VALUES(2, '".$username."', '".$nome."', '".$cc."', ".$numCarta.", '".$morada."', '".$email."', '".$cryptPasswd."', ".$telef.")";
		}
		$retval = mysqli_query( $conn, $sql );
		
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Copia dados relativos ao utilizador no acto do login, dados úteis para várias páginas.
*/
	function login($username, $passwd){
		global $conn;
		
		$sql = "select numC, numTipo, nome from utilizador where username='".$username."' and passwd='".$passwd."'";
		$retval = mysqli_query($conn, $sql);
		
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Retorna true se existir o username passado por parâmetro
*/
	function usernameExists($username){
		global $conn;
		$uname = null;
		
		$sql = "select username from utilizador where username='".$username."'";
		$retval = mysqli_query($conn, $sql);
		
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}else{ 
			while($registo = mysqli_fetch_array($retval)){
				$uname = $registo['username'];
			}
			
			return $uname!=null;
		}
	}

/*
  Retorna true se o numero do cartão do cidadão já existir na base de dados
*/
	function ccExists($cc){
		global $conn;
		$cartao=null;
		
		$sql = "select cc from utilizador where cc='".$cc."'";
		$retval = mysqli_query($conn, $sql);
		
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}else{ 
			while($registo = mysqli_fetch_array($retval)){
				$cartao = $registo['cc'];
			}
			
			return $cartao!=null;
		}
	}

/*
  Regista um caçador sem username e password, com tipo 3 - "Caçador". Caso já exista um caçador com o "cc" dado é feito um update à base de dados
*/
	function updateCacador($nome, $cc, $numCarta, $morada, $email, $telef){
		global $conn;
		
		if(ccExists($cc)){
			$sql = "UPDATE `utilizador` SET `nome` = '".$nome."', `numCarta` = '".$numCarta."', `morada` = '".$morada."', `email` = '".$email."', `telef` = ".$telef." WHERE `utilizador`.`cc` = '".$cc."';";
		}else{
			$sql = "INSERT INTO utilizador(numTipo, nome, cc, numCarta, morada, email, telef) 
			VALUES(3, '".$nome."', '".$cc."', ".$numCarta.", '".$morada."', '".$email."', ".$telef.")";
		}
		
		$retval = mysqli_query($conn, $sql);
		
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Atualiza a base de dados com os dados fornecidos na página "user.php" - editar utilizador
*/
	function updateMember($numTipo, $numC, $nome, $cc, $numCarta, $morada, $email, $telef){
		global $conn;
		
		
		$sql = "UPDATE `utilizador` SET numTipo = ".$numTipo.", `nome` = '".$nome."', `numCarta` = '".$numCarta."', `morada` = '".$morada."', `email` = '".$email."', `telef` = ".$telef." 
		WHERE `utilizador`.`numC` = '".$numC."';";
		
		$retval = mysqli_query($conn, $sql);
		
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Seleciona todos os utilizadores existentes na base de dados
*/
	function getMembros(){
		global $conn;
		// tipos utilizador 1-presidente; 2-socio; 3-cacador
		$sql = 'SELECT * FROM utilizador';
		//Seleciona a base de dados
		$retval = mysqli_query( $conn, $sql );

		if(! $retval ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Altera a password armazenada na base de dados para o utilizador selecionado pelo "numC"
*/
	function updatePasswd($numC, $passwd){
		global $conn;
		$cryptPasswd = hash("sha512", $passwd);
		
		if(hasLoggedIn()){
			$sql = "UPDATE `utilizador` SET `passwd` = '".$cryptPasswd."' WHERE `utilizador`.`numC` = '".$numC."';";
		}else{
			return false;
		}
		
		$retval = mysqli_query($conn, $sql);
		
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Retorna todas as inscrições efetuadas pelo utilizador com índice "numC" para todos os eventos
*/
	function getInscricoes($numC){
		global $conn;
		
		if(hasLoggedIn()){
			$sql = "SELECT idInscricao, nome, data, evento.descricao AS descricaoEvento, porta, estado_inscricao.descricao AS descricaoInscricao, evento.idEvento AS idEvento 
			FROM evento, inscricao, estado_inscricao 
			WHERE idUtilizador = ".$numC." AND evento.idEvento = inscricao.idEvento AND estado_inscricao.idEstado = inscricao.idEstado";
		}
		
		$retval = mysqli_query($conn, $sql);
		
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Verifica se existem inscrições para os valores da query passada como parâmetro
*/
	function temInscricoes($retval){
		$idInscricao = null;
		while($registo = mysqli_fetch_array($retval)){
			$idInscricao = $registo['idInscricao'];
		}
		if($idInscricao==null){
			return false;
		}
		return true;
	}

/*
  Retorna a descrição do estado da inscrição relativa ao id de estado existente na tabela inscrição
*/
	function getDescricaoEstadoInscricao($idEstado){
		global $conn;
		
		$sql = "SELECT descricao FROM estado_inscricao WHERE idEstado = ".$idEstado.";";
		
		$retval = mysqli_query($conn, $sql);
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		while($registo = mysqli_fetch_array($retval)){
			$descricao = $registo['descricao'];
		}
		
		return $descricao;
	}

/*
  Apaga o evento com índice idEvento e tudo o que estiver dependente do mesmo
*/
	function apagarEvento($idEvento){
		global $conn;
		
		//apagar albums dependentes do evento
		$sql = "DELETE FROM album WHERE idEvento = ".$idEvento.";";
		
		$retval = mysqli_query($conn, $sql);
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		//apagar inscricoes dependentes do evento
		$sql = "DELETE FROM inscricao WHERE idEvento = ".$idEvento.";";
		
		$retval = mysqli_query($conn, $sql);
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		//apagar evento
		$sql = "DELETE FROM evento WHERE idEvento = ".$idEvento.";";
		
		$retval = mysqli_query($conn, $sql);
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Apaga uma inscrição com índice passado por parâmetro, incrementando o número de vagas existentes no evento respectivo
*/
	function apagarInscricao($idInscricao){
		global $conn;
		
		//Incrementar vagas
		$sql = "SELECT idEvento FROM inscricao WHERE idInscricao = ".$idInscricao.";";
		$retval = mysqli_query($conn, $sql);
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		while($registo = mysqli_fetch_array($retval)){
			$idEvento = $registo['idEvento'];
		}
		
		if(isset($idEvento) && $idEvento != null){
			incVagas($idEvento);
		}
		
		//apagar inscricao selecionada
		$sql = "DELETE FROM inscricao WHERE idInscricao = ".$idInscricao.";";
		
		$retval = mysqli_query($conn, $sql);
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Incrementa as vagas existentes no evento com idEvento do parâmetro
*/
	function incVagas( $idEvento ){
		global $conn;
		
		if( !isset($idEvento)|| $idEvento == -1)
		  return false;
		
		$sql_update_evento = 'UPDATE evento SET vagas = vagas + 1 WHERE idEvento = '.$idEvento.';';
		
		$res_update_evento = mysqli_query( $conn, $sql_update_evento );
		if( !$res_update_evento ){
		  die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $res_update_evento;
	}

/*
  Retorna o valor da coluna "numC" do utilizador com número de cartão do cidadão "cc"
*/
	function getIdSessao($cc){
		global $conn;
		
		$sql = "SELECT numC FROM utilizador WHERE cc = '".$cc."';";
		
		$retval = mysqli_query($conn, $sql);
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		while($registo = mysqli_fetch_array($retval)){
			$idSessao = $registo['numC'];
		}
		
		if(isset($idSessao) && $idSessao != null){
			return $idSessao;
		}
		
		return $retval;
	}

/*
  Retorna todos os dados relativos a uma dada inscrição
*/
	function getInscricao($idInscricao){
		global $conn;
		
		$sql = "SELECT * FROM inscricao WHERE idInscricao = '".$idInscricao."';";
		
		$retval = mysqli_query($conn, $sql);
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Verifica se a porta do evento que se pretende alterar já está ocupada ou não, 
  ignorando o caso em que a porta escolhida é igual à que já existia na inscrição que se pretende alterar
*/
	function portaExists($idEvento, $portaVelha, $porta){
		global $conn;
		
		if($portaVelha == $porta){
			return false;
		}
		
		$sql = "SELECT porta FROM inscricao WHERE idEvento='".$idEvento."'";
		$retval = mysqli_query($conn, $sql);
		
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}else{ 
			while($registo = mysqli_fetch_array($retval)){
				$portaDB = $registo['porta'];
				if($portaDB == $porta){
					return true;
				}
			}
			return false;
		}
	}

/*
  Atualiza os dados da inscrição com o novo estado e porta inseridos pelo admin/presidente
*/
	function updateInscricao($idInscricao, $estado, $porta){
		global $conn;
		
		if($estado == 3){ //estado = 3 ==> Recusado
			$retval = apagarInscricao($idInscricao);
			return $retval;
		}
		
		$sql = "UPDATE inscricao SET idEstado = ".$estado.", porta = ".$porta." WHERE idInscricao = ".$idInscricao.";";
		
		$retval = mysqli_query($conn, $sql);
		
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Atualiza o evento com índice "idEvento", ou cria um evento novo caso este índice seja nulo
*/
	function updateEvento($idEvento, $nome, $data, $hora, $preco, $vagas, $tipo, $local, $descricao, $imagem){
		global $conn;
		
		if($idEvento == null){
			$sql = "INSERT INTO evento(`nome`, `data`, `hora`, `preco`, `vagas`, `tipo`, `local`, `descricao`, `imagem`) 
			VALUES ('".$nome."', '".$data."', '".$hora."', ".$preco.", ".$vagas.", ".$tipo.", '".$local."', '".$descricao."', '".$imagem."');";
		}else{
			$sql = "UPDATE evento SET nome = '".$nome."', data = '".$data."', hora = '".$hora."', preco = ".$preco.", vagas = ".$vagas.", tipo = ".$tipo.", local = '".$local."', descricao = '".$descricao."', imagem = '".$imagem."' WHERE idEvento = ".$idEvento.";";
		}
		
		$retval = mysqli_query($conn, $sql);
		
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Apaga um utilizador selecionado pelo admin, através do índice de utilizador "numC"
*/
	function apagarUtilizador($numC){
		global $conn;
		
		//apagar inscricoes dependentes do utilizador
		$sql = "SELECT idInscricao FROM inscricao WHERE idUtilizador = ".$numC.";";
		
		$retval = mysqli_query($conn, $sql);
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		while($registo = mysqli_fetch_array($retval)){
			apagarInscricao($registo['idInscricao']);
		}
		
		//apagar utilizador
		$sql = "DELETE FROM utilizador WHERE numC = ".$numC.";";
		
		$retval = mysqli_query($conn, $sql);
		if(! $retval ){
			die('Could not insert data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}
	
/*
	Apaga algumas variáveis de sessão que não são necessárias;
	As que estão comentadas foram declaradas como necessárias após determinados testes
	e não podem ser apagadas, pois o seu UNSET cria bugs e erros inesperados;
	No entanto ficam com "lugar reservado" caso seja encontrada uma solução alternativa.
*/
	function unsetSessionVars(){
		if(isset($_SESSION["editedUser"])){ unset($_SESSION["editedUser"]); }
		if(isset($_SESSION["idCat"])){ unset($_SESSION["idCat"]); }
		if(isset($_SESSION["editar"])){ unset($_SESSION["editar"]); }
		//if(isset($_SESSION["idEvento"])){ unset($_SESSION["idEvento"]); }
		//if(isset($_SESSION["modo"])){ unset($_SESSION["modo"]); }
		if(isset($_SESSION["idInscricao"])){ unset($_SESSION["idInscricao"]); }
		if(isset($_SESSION["portaVelha"])){ unset($_SESSION["portaVelha"]); }
	}

/*
  Retorna toda a informação relativa à associação
*/
	function getInfoAssociacao($idAssociacao){
		global $conn;

		$sql = 'SELECT * FROM associacao WHERE idAssociacao='.$idAssociacao;
		$retval = mysqli_query(  $conn, $sql );

		if(! $retval ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Retorna todas as categorias de evento disponíveis/existentes
*/
	function getCategorias(){
		global $conn;

		$sql = 'SELECT idTipo, descricao FROM tipo_evento';
		$retval = mysqli_query(  $conn, $sql );

		if(! $retval ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Retorna todas as colunas de um evento
*/
	function obterEvento( $idEvento ){
		global $conn;
		if( $idEvento == -1)
		return;

		$sql = 'SELECT * FROM evento WHERE idEvento='.$idEvento;
		$retval = mysqli_query(  $conn, $sql );

		if(! $retval ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/* 
	Obter os eventos mais recentes 
*/
	function obterEventosRecentes( $numEventos ){
		global $conn;

		$sql = 'SELECT * FROM evento ORDER BY data DESC LIMIT '.$numEventos.'';
		$retval= mysqli_query(  $conn, $sql );

		if( !$retval ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
	Retorna os eventos de uma dada descricao
	"Montaria", "Batida", etc...
*/
	function obterEventosDescricao( $descricao ){
		global $conn;
		if( $descricao == NULL )
		return;

		if( !strcmp($descricao, "Todos") )
			$sql = "SELECT * FROM evento";
		else
			$sql = "SELECT * FROM evento WHERE tipo=(SELECT idTipo FROM tipo_evento WHERE descricao='".$descricao."')";
		
		

		$retval = mysqli_query(  $conn, $sql );

		if(! $retval ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Retorna os dados relativos ao utilizador com "numC"
*/
	function obterUtilizador($idSessao){
		global $conn;
		if( $idSessao == -1)
		return;

		$sql = 'SELECT * FROM utilizador WHERE numC='.$idSessao;
		$retval = mysqli_query(  $conn, $sql );

		if(! $retval ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $retval;
	}

/*
  Retorna a descrição do tipo de utilizador dependendo do utilizador fornecido
*/
	function obterDescricaoUtilizador( $idSessao ){
		global $conn;
		if( $idSessao == -1 || $idSessao == null )
			$sqlTipo = "SELECT descricao from tipo_utilizador WHERE idTipo=3";
		else
			$sqlTipo = "SELECT descricao from tipo_utilizador WHERE idTipo=(SELECT numTipo from utilizador WHERE numC=".$idSessao.")";

		$resDescricao = mysqli_query( $conn, $sqlTipo );
		if(! $resDescricao ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_array( $resDescricao );
		$tipo = $row['descricao'];
		
		return $tipo;

	}

/*
  Retorna o idInscricao para o utilizador e evento fornecidos, ou false caso não exista inscrição para o dado evento e utilizador
*/
	function existeIdInscricao( $idSessao, $idEvento ){
		global $conn;
		
		if( !isset($idSessao) ||!isset($idEvento)|| $idSessao == -1 || $idEvento == -1)
			return false;
		else
			$sqlInscricao = "SELECT idInscricao from inscricao WHERE idEvento=".$idEvento ." and idUtilizador=".$idSessao;

		$resInscricao = mysqli_query( $conn,$sqlInscricao );
		if(! $resInscricao ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		$row = mysqli_fetch_array( $resInscricao );
		
		if( $row['idInscricao'] >0 )
			return $row['idInscricao'];
		return false;
	}

/*
  Retorna true se existem vagas para o evento
*/
	function existemVagas( $idEvento ){
		global $conn;
		if( !isset($idEvento) || $idEvento == -1)
			return false;
		
		$sql_vagas = 'SELECT vagas FROM evento WHERE idEvento='.$idEvento;

		$res_vagas = mysqli_query( $conn, $sql_vagas );
		
		if( !$res_vagas ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		$row_vagas = mysqli_fetch_array($res_vagas);
		$res = $row_vagas['vagas'] > 0;
		
		return $res;
	}

// Faz o registo na BD
// insere uma inscricao
// se correr bem, decrementa as vagas
	function registar($idSessao,$idEvento,$numPorta){
		global $conn;
		if( !isset($idSessao) ||!isset($idEvento)|| !isset($numPorta) || $idSessao == -1 || $idEvento == -1 || !isset($numPorta)){
			
			return false;
		}
		
		$sql_incricao = 'INSERT INTO `inscricao`(`idEvento`, `idUtilizador`, `porta`, `idEstado`) VALUES ('.$idEvento.', '.$idSessao.' , '.$numPorta.', 1)';
		$res_incricao = mysqli_query( $conn, $sql_incricao );
		if( !$res_incricao ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		if( !$res_incricao ) 
			return false;
		return decVagas($idEvento);
	}

/*
  Decrementa 1 vaga ao evento e retorna true
*/
	function decVagas( $idEvento ){
		global $conn;
		
		if( !isset($idEvento)|| $idEvento == -1){
			
			return false;
		}
		
		if(getVagas($idEvento) <= 0 ){
			
			return false;
		}

		$sql_update_evento = 'UPDATE `evento` SET `vagas`=`vagas`-1 WHERE `idEvento`='.$idEvento.' AND `vagas`>0';
		
		$res_update_evento = mysqli_query( $conn, $sql_update_evento );
		if( !$res_update_evento ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $res_update_evento;
	}

/*
  Retorna o número de vagas existentes num dado evento
*/
	function getVagas( $idEvento ){
		global $conn;
		
		if( !isset($idEvento)|| $idEvento == -1){
			
			return false;
		}
		
		$sql_get_vagas = 'SELECT vagas FROM evento WHERE idEvento='.$idEvento;
		$res_vagas = mysqli_query( $conn, $sql_get_vagas );
		if( !$res_vagas ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		$row_vagas = mysqli_fetch_array($res_vagas);
		
		return $row_vagas['vagas'];
	}

/*
  Retorna o numero de inscritos num evento
*/
	function numInscritos( $idEvento ){
		global $conn;
		if( !isset($idEvento)|| $idEvento == -1){
			
			return 0;
		}

		$sql_numInscritos = 'SELECT idInscricao FROM inscricao WHERE idEvento='.$idEvento;
		$res_numInscritos = mysqli_query( $conn, $sql_numInscritos );
		if( !$res_numInscritos ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		$n=0;
		while( $row_vagas = mysqli_fetch_array($res_numInscritos) ){
			$n++;
		}
		
		return $n;
	}

/*
  Retorna as inscricoes de um dado evento
*/
	function obterInscritos($idEvento){
		global $conn;
		if( !isset($idEvento)|| $idEvento == null || $idEvento == -1){
			
			return null;
		}

		$sql_inscritos = 'SELECT * FROM inscricao WHERE idEvento='.$idEvento;
		$res_inscritos = mysqli_query( $conn, $sql_inscritos );
		if( !$res_inscritos ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $res_inscritos;
	}

/*
  Retorna a galeria
*/
	function obterGaleria( $idGaleria ){
		global $conn;
		if( !isset($idGaleria)|| $idGaleria == -1){
			
			return null;
		}

		$sql_galeria = 'SELECT * FROM galeria WHERE idGaleria='.$idGaleria;
		$res_galeria = mysqli_query( $conn, $sql_galeria );
		if( !$res_galeria ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $res_galeria;
	}

/*
  Retorna o id da galeria de um dado evento
*/
	function obterIdGaleria( $idEvento ){
		global $conn;
		if( !isset($idEvento)|| $idEvento == -1){
			
			return null;
		}

		$sql_idGaleria = 'SELECT idGaleria FROM album WHERE idEvento='.$idEvento;
		$res_idGaleria = mysqli_query( $conn, $sql_idGaleria );
		if( !$res_idGaleria ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		$row_idGaleria = mysqli_fetch_array($res_idGaleria);
		
		return $row_idGaleria['idGaleria'];
	}

/*
  Retorna as galerias existentes
*/
	function obterGalerias(){
		global $conn;

		$sql_galerias = 'SELECT * FROM album';
		$res_galeria = mysqli_query( $conn, $sql_galerias );
		if( !$res_galeria ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $res_galeria;
	}

/*
  Retorna uma breve informacao de um album
*/
	function obterBreveInfoGalerias($idGaleria){ 
		global $conn;
		if( !isset($idGaleria)|| $idGaleria == -1){
			
			return null;
		}

		$sql_galerias = 'SELECT source, data FROM galeria WHERE idGaleria='.$idGaleria.' LIMIT 1';
		$res_galeria = mysqli_query( $conn, $sql_galerias );
		if( !$res_galeria ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		
		return $res_galeria;
	}

/*
  Retorna uma porta que esteja livre para o evento fornecido,
  analisando quais é que já estão ocupadas nas inscrições
*/
	function getPorta($idEvento){
		global $conn;
		if( !isset($idEvento)|| $idEvento == -1){
			
			return null;
		}

		$sql_porta = 'SELECT porta FROM inscricao WHERE idEvento='.$idEvento.' ORDER BY porta DESC LIMIT 1';
		$res_porta = mysqli_query( $conn, $sql_porta );
		if( !$res_porta ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		$portNum=mysqli_fetch_array($res_porta);

		$capacidade = getCapacidadeVagas($idEvento);
		if( $capacidade ==null ){
			die('Could not get data: ' . mysqli_error($conn));
		}

		if(existemVagas($idEvento) && $portNum['porta']>$capacidade){
			
			return $portNum['porta']+1;
		}else{
			$sql_verificar_porto = null;

			if(!existemVagas($idEvento)){
				
				return -1;
			}
			
			for($i=1; $i<=$capacidade; $i++){
				$sql_verificar_porto = 'SELECT idInscricao FROM inscricao WHERE porta='.$i.' AND idEvento ='.$idEvento;
				$res_verificar = mysqli_query( $conn, $sql_verificar_porto );
				if( mysqli_num_rows($res_verificar)==0 ){
					
					return $i;
				}
			}
		}
	}

/*
  Retorna a capacidade máxima de um evento (nº de portas máximo)
*/
	function getCapacidadeVagas($idEvento){
		global $conn;
		$sql_numVagas = 'SELECT vagas from evento where idEvento='.$idEvento.';';
		$sql_numInscrito = 'SELECT COUNT(idInscricao) AS total FROM inscricao WHERE idEvento ='.$idEvento.';';
		$res_numVagas =mysqli_query( $conn, $sql_numVagas );
		$res_numInscrito=mysqli_query( $conn, $sql_numInscrito );
		if( !$res_numVagas || !$res_numInscrito ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		$numVagas = mysqli_fetch_array($res_numVagas);
		$numInscritos = mysqli_fetch_array($res_numInscrito);
		$total = $numVagas['vagas'] + $numInscritos['total'];
		
		return $total;
	}
	
/*
  Cria uma categoria para um evento
*/
	function criarCategoria($categoria){
		global $conn;
		$sql_categoria = "INSERT INTO `tipo_evento`( `descricao`) VALUES ('".$categoria."')";

		$res_categoria = mysqli_query( $conn, $sql_categoria );
		if( !$res_categoria ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		return $res_categoria;
	}


/*
  Elimina uma categoria para um evento
*/
	function eliminarCategoria($idCat){
		global $conn;
		
		// Obter Eventos, do tipo idCat
		$sql_obterEventos = "SELECT * FROM `evento` WHERE tipo=".$idCat.";";
		
		$res_obterEventos = mysqli_query( $conn, $sql_obterEventos );
		if( !$res_obterEventos ){
			die('Could not get data: ' . mysqli_error($conn));
		}

		// Apagar os eventos
		while( $rowEvento = mysqli_fetch_array($res_obterEventos)){
			apagarEvento( $rowEvento['idEvento'] );
		}

		$sql_delCat = "DELETE FROM `tipo_evento` WHERE idTipo='".$idCat."';";
		
		$res_delCat = mysqli_query( $conn, $sql_delCat );
		if( !$res_delCat ){
			die('A operação de eliminar a categoria falhou: ' . mysqli_error($conn));
		}
		
		return true;
	}

/*
  Retorna o id de uma categoria, se existir
  Caso não exista retorna -1
*/
	function getIDCategoria($categoria){
		global $conn;
		$sql_idCat = "SELECT idTipo from tipo_evento where descricao='".$categoria."';";
		
		$res_idCat = mysqli_query( $conn, $sql_idCat );
		if( !$res_idCat ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		$row_cat = mysqli_fetch_array($res_idCat);
		return $row_cat['idTipo'] == false ? false : $row_cat['idTipo'];
	}

/*
  Retorna a categoria de uma dado id
  Retorna -1 se não existir categoria para esse id
*/
	function getCategoria($IdCat){
		global $conn;
		$sql_categoria = "SELECT descricao from tipo_evento where idTipo=".$IdCat.";";
		
		$res_Cat = mysqli_query( $conn, $sql_categoria );
		if( !$res_Cat ){
			die('Could not get data: ' . mysqli_error($conn));
		}
		$row_cat = mysqli_fetch_array($res_Cat);
		return $row_cat['descricao'] == false ? false : $row_cat['descricao'];
	}

/*
  Alterar a descricao da categoria para um dado ID
*/
	function alterarCategoria($idCat, $categoria){
		global $conn;
		$sql_alterarCat = "UPDATE `tipo_evento` SET `descricao`='".$categoria."' WHERE idTipo = ".$idCat.";";

		$res_alterarCat = mysqli_query( $conn, $sql_alterarCat );
		if( !$res_alterarCat ){
			die('Could not get data: ' . mysqli_error($conn));
		}

		return $res_alterarCat;
	}

?>

<?php

/*
  Desenha a tabela dos eventos
*/
	function componenteTabelaEvento($rowEvento){
		if($rowEvento == NULL) return;
		echo('
		<div class="container">
		<table class="table">
		<thead>
		<tr>
		<th>Evento</th>
		<th>Data</th>
		<th>Horas</th>
		<th>Local</th>
		</thead>
		<tbody>
		<tr>
		<td>'.$rowEvento['nome'].'</td>
		<td>'.$rowEvento['data'].'</td>
		<td>'."12:00".'</td>
		<td>'.$rowEvento['local'].'</td>
		</tr>
		</tbody>
		</table>

		<table class="table">
		<thead>
		<tr><th>Descrição</th></tr>
		</thead>
		<tbody>
		<tr><td>'.$rowEvento['descricao'].'</td></tr>
		</tbody>
		</table>
		</div>
		');

	}

/*
  FUNÇÃO PARA DEBUGGING
*/
	function debug($tipoUser, $idEvento, $idSessao){
		$err ="";
		if( isset($tipoUser))
		$err .= "tipo: ".$tipoUser."-";
		if( isset($idEvento))
		$err .="idEvento: ".$idEvento."-";
		if( isset($idSessao))
		$err .="idSessao".$idSessao;
	}

?>





<?php



/*
Desenha componete : Barra de topo
@parm local : recebe o local onde esta a ser desenhada a barra
valores possiveis  para local : 
'logo', 'index', 'contacto','acerca','session', 'evento','inscricao'
*/
	function desenhaTopoBarra( $local ){

		$pathIndex = $pathMembros = $pathEvento = $pathContacto = $pathLog = $pathInsc = "";
		$btIndex = $btMembros = $btEvento = $btContacto = "";



		switch ($local) {
		case 'logo':
		case 'index':
			$btIndex = 'class="active"';
			setVars( $pathIndex, $pathMembros, $pathEvento, $pathContacto, $pathLog , $pathInsc , 
			'', 'session/', 'evento/', '', 'session/', 'inscricao/');
			break;
		case 'about':
			setVars( $pathIndex, $pathMembros, $pathEvento, $pathContacto, $pathLog , $pathInsc , 
			'', 'session/', 'evento/', '', 'session/', 'inscricao/');
			break;
		case 'contacto':
			$btContacto = 'class="active"';
			setVars( $pathIndex, $pathMembros, $pathEvento, $pathContacto, $pathLog , $pathInsc , 
			'', 'session/', 'evento/', '', 'session/','inscricao/');
			break;

		case 'membros':
			$btMembros = 'class="active"';
			setVars( $pathIndex, $pathMembros, $pathEvento, $pathContacto, $pathLog , $pathInsc , 
			'../', '', '../evento/', '../', '','../inscricao/');
			break;

		case 'session':
			setVars( $pathIndex, $pathMembros, $pathEvento, $pathContacto, $pathLog , $pathInsc , 
			'../', '', '../evento/', '../', '','../inscricao/');
			break;

		case 'evento':
			$btEvento = 'class="active"';
			setVars( $pathIndex, $pathMembros, $pathEvento, $pathContacto, $pathLog , $pathInsc , 
			'../', '../session/', '', '../', '../session/', '../inscricao/');
			break;
		case 'inscricao':
			$btVerInsc = 'class="active"';
			setVars( $pathIndex, $pathMembros, $pathEvento, $pathContacto, $pathLog , $pathInsc , 
			'../', '../session/', '../evento/', '../', '../session/', "" );
			break;

		default:
			break;
		}
		
		echo('
		<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
		<div class="container-fluid">

		<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>                        
		</button>
		<a class="navbar-brand" href="'.$pathIndex.'index.php">Caça EST</a>
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
		<ul class="nav navbar-nav">
		<li '.$btIndex.' ><a href="'.$pathIndex.'index.php">Home</a></li>
		<li '.$btEvento.' ><a href="'.$pathEvento.'evento.php">Eventos</a></li>');

		if( hasLoggedIn() && obterDescricaoUtilizador($_SESSION['numC']) =='Presidente' )
			echo '<li '.$btMembros.' ><a href="'.$pathMembros.'members.php">Membros</a></li>';
		echo ('
		<li '.$btContacto.' ><a href="'.$pathContacto.'contacto.php">Contacto</a></li>
		
		
		</ul>
		<ul class="nav navbar-nav navbar-right">
		');
		if(hasLoggedIn())
			echo('
				<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">'.getNome().' <span class="caret"></span></a>
				<ul class="dropdown-menu">
					<li><a href="'.$pathMembros.'user.php"><span class="glyphicon glyphicon-user"></span>  Gerir Conta</a></li>
					<li><a href="'.$pathInsc.'verInscricoes.php"><span class="glyphicon glyphicon-th-list"></span>  Ver Inscrições</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="'.$pathMembros.'alterarPasswd.php"><span class="glyphicon glyphicon-lock"></span>  Alterar Password</a></li>
					<li role="separator" class="divider"></li>
					<li><a href="'.$pathLog.'logout.php"><span class="glyphicon glyphicon-log-out"></span>  Sair</a></li>
				</ul>
				</li>
				');
		else
			echo('
				<li><a href="'.$pathLog.'login.php"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
				<li><a href="'.$pathLog.'register.php"><span class="glyphicon glyphicon-edit"></span> Register</a></li>
				');
		echo('
			</ul>
			</div>
			</div>
			</nav>
			');
	}

/*
  "Variáveis de ambiente"
*/
	function setVars( &$pathIndex, &$pathAbout, &$pathEvento, &$pathContacto, &$pathLog , &$pathInsc , 
	$varIndex, $varAbout, $varEvento, $varContacto, $varLog, $varInsc){
		$pathIndex = $varIndex;
		$pathAbout = $varAbout;
		$pathEvento = $varEvento;
		$pathContacto = $varContacto;
		$pathLog = $varLog;
		$pathInsc = $varInsc;
	}

/*
  Desenha o "footer"
*/
	function desenharFundoBarra( $local ){
		echo('
		</br></br></br>
		<div class="container-fixed-fluid down">
		<footer class="text-center navbar-bottom">
		<p>ESTCB &copy; 2016</p>
		</footer>
		</div>
		');
	}
?>

<?php
/*
  Desenha barra para debug relativo ao login
*/
	function barraDebug(){
		$estadoSessao = hasLoggedIn() ? "S" : "N";
		echo("  Estou Logado?_". $estadoSessao ."  _numC?_");
		if(isset($_SESSION['numC']) ) echo($_SESSION['numC']);
	}
?>