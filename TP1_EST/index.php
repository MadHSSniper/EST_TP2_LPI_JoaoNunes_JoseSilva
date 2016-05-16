<!DOCTYPE html>
<html lang="pt">
	
	<head>
		
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		
		<title>Associação de Caça</title>
		
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<script type="text/javascript" src="js/jquery.min.js"></script>
		<link href="css/blog-home.css" rel="stylesheet">
	</head>
	
	<body>
		
		<?php
			include("funcoesBD.php");
			desenhaTopoBarra( "index" );
		?>
		
		
		<!-- Page Content -->
		<div class="container">
			
			<div class="row">
				
				<!-- Blog Entries Column -->
				<div class="col-md-8">
					
					<h1 class="page-header">
						Associação de Caça
						<small> EST</small>
					</h1>
					
					<?php
						$retEventos = obterEventosRecentes( 3 );
						while($rowEvento = mysqli_fetch_array($retEventos) ){
							echo('
                            <h2>
							<a href="evento/verEvento.php?id='.$rowEvento['idEvento'].'">'.$rowEvento['nome'].'</a>
                            </h2>
                            <p><span class="glyphicon glyphicon-calendar"></span> Data: '.$rowEvento['data'].' <span class="glyphicon glyphicon-time"></span> Hora: '.$rowEvento['hora'].'</p>
                            <hr>
                            <img class="img-responsive" src="'.$rowEvento['imagem'].'" alt="">
                            <hr>
                            <p>'.$rowEvento['descricao'].'</p>
                            <a class="btn btn-primary" href="evento/verEvento.php?id='.$rowEvento['idEvento'].'">
                            Ver Evento<span class="glyphicon glyphicon-chevron-right">
                            </span></a>
							
                            <hr>
							
							
							
                            ');
						}
					?>
					
					<!-- Pager -->
					<ul class="pager">
						<li class="previous">
							<a href="evento/evento.php">Ver todos</a>
						</li>
					</ul>
					
				</div>
				
				<!-- Blog Sidebar Widgets Column -->
				<div class="col-md-4">
					
					<!-- Blog Categories Well -->
					<div class="well">
						<h4>Sítios</h4>
						<div class="row">
							<div class="col-lg-6">
								<ul class="list-unstyled">
									<li><a href="evento/evento.php">Eventos</a>
									</li>
									<li><a href="verGaleria.php">Galeria</a>
									</li>
									<li><a href="about.php">Acerca</a>
									</ul>
								</div>
								<!-- /.col-lg-6 -->
								<div class="col-lg-6">
									<ul class="list-unstyled">                                
									</ul>
								</div>
								<!-- /.col-lg-6 -->
							</div>
							<!-- /.row -->
						</div>
						
						<!-- Side Widget Well -->
						<div class="well">
							<h4>Associação EST</h4>
							<p>Bem vindos à Associação de Caça EST, um espaço onde todos os caçadores da EST se podem reunir para desfrutar deste desporto.</p>
						</div>
						<!-- Side Widget Well -->
						<div class="well">
							<h4>Montaria</h4>
							<img class="img-responsive" src="img/epoca2015.jpg" alt="">
							<center><a class="btn btn-primary" href="galeria.php?idGaleria=1">Ver Fotos
								<span class="glyphicon glyphicon-chevron-right"></span>
							</a>
							<p>Fotos da epoca 2015!</p>
							</center>
							
						</div>
						<!-- Side Widget Well -->
						<div class="well">
							<h4>Petiscos</h4>
							<img class="img-responsive" src="img/petiscos.jpg" alt="">
							<p>Os melhores petiscos, venha celebrar conosco</p>
						</div>
						<!-- Side Widget Well -->
						<div class="well">
							<h4>Acessórios EST</h4>
							<img class="img-responsive" src="img/acessorios.jpg" alt="">
							<p>Material de Caça EST</p>
						</div>
						
					</div>
					
				</div>
				<!-- /.row -->
				
				<hr>
				
				<!-- Footer -->
				<footer>
					<div class="row">
						<div class="col-lg-12">
							<p>ESTCB &copy; 2016</p>
						</div>
						<!-- /.col-lg-12 -->
					</div>
					<!-- /.row -->
				</footer>
				
			</div>
			<!-- /.container -->
			
			<!-- jQuery -->
			<script src="js/jquery.js"></script>
			
			<!-- Bootstrap Core JavaScript -->
			<script src="js/bootstrap.min.js"></script>
			
		</body>
		
	</html>
	
	
