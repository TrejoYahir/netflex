<?php
session_start();

if(!isset($_SESSION['user_session']))
{
	header("Location: index.php");
}


include_once 'dbconfig.php';

$selected_profile = trim($_GET['profile_id']);
$_SESSION['profile_id'] = $selected_profile;

if(isset($_SESSION['user_session']) && $_SESSION['profile_id']=="")
{
	header("Location: profile.php");
}

//user data
$stmt = $db_con->prepare("SELECT perfil.* FROM usuario, perfil WHERE usuario.id_usuario=:uid and perfil.id_usuario = usuario.id_usuario and perfil.id_perfil=:pid");
$stmt->execute(array(":uid"=>$_SESSION['user_session'], ":pid"=>$_SESSION['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$count = $stmt->rowCount();

//categories list 
$stmt = $db_con->prepare("SELECT * FROM categoria");
$stmt->execute();
$result=$stmt->fetchAll();

//user film list
$stmt = $db_con->prepare("SELECT metraje.* from lista, perfil, metraje, usuario WHERE usuario.id_usuario=:uid and perfil.id_perfil=lista.id_perfil and lista.id_metraje=metraje.id_metraje and perfil.id_perfil = :pid;");
$stmt->execute(array(":uid"=>$_SESSION['user_session'], ":pid"=>$_SESSION['profile_id']));
$list=$stmt->fetchAll();

//history
$stmt = $db_con->prepare("SELECT metraje.* from perfil,historial,historial_pelicula,pelicula,metraje,usuario where perfil.id_perfil=historial.id_perfil and historial.id_historial=historial_pelicula.id_historial and Historial_pelicula.id_pelicula=pelicula.id_metraje and metraje.id_metraje=pelicula.id_metraje and perfil.id_perfil=:pid and usuario.id_usuario=:uid;");
$stmt->execute(array(":uid"=>$_SESSION['user_session'], ":pid"=>$_SESSION['profile_id']));
$history_movies=$stmt->fetchAll();

$stmt = $db_con->prepare("SELECT metraje.*, max(capitulo.id_capitulo) as 'capid', capitulo.nombre as 'nombrecap', capitulo.descripcion as 'descap' from perfil,historial,Historial_serie,serie,metraje,temporada,capitulo,usuario where perfil.id_perfil=historial.id_perfil and historial.id_historial=Historial_serie.id_historial and Historial_serie.id_serie=serie.id_metraje and serie.id_metraje=metraje.id_metraje and Historial_serie.id_temporada=Temporada.id_temporada and Historial_serie.id_capitulo=Capitulo.id_capitulo and perfil.id_perfil=:pid and usuario.id_usuario=:uid;");
$stmt->execute(array(":uid"=>$_SESSION['user_session'], ":pid"=>$_SESSION['profile_id']));
$history_series=$stmt->fetchAll();

$history=array_merge($history_movies, $history_series);



?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Netflex</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<script type="text/javascript" src="js/script.js"></script>

<link href="css/style.css" rel="stylesheet" media="screen">

</head>

<body class="home">

	<div class="container-fluid no-margin no-padding">
	
		<div class="row no-margin no-padding">

			<nav class="navbar navbar-default navbar-fixed-top heading suddle-bg">
				<div class="container-fluid">
					<div class="long-shadow">Netflex</div>
					<div class="dropdown pull-right">
						<div class="user-nav dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							<div class="avatar">
								<span class="glyphicon glyphicon-user"></span>
							</div>
							<h3><?php echo $row['nombre'];?></h3>&nbsp;
							<span class="glyphicon glyphicon-chevron-down"></span>
						</div>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
							<li><a href="#">Ver perfil</a></li>
							<li><a href="profile.php">Cambiar perfil</a></li>						
							<li><a href="logout.php">Cerrar sesión</a></li>
						</ul>
					</div>
				</div>
			</nav>

			<div class="col-xs-12 col-sm-12">
					<div class="body-container">
					<div class="category">
					<h3>Continuar viendo</h3>							
						<?php foreach($history as $film): ?>
							<?php
								$stmt = $db_con->prepare("SELECT nombre FROM genero WHERE id_genero=:gid");
								$stmt->execute(array(":gid"=>$film['id_genero']));
								$genero = $stmt->fetch(PDO::FETCH_ASSOC);

								$stmt = $db_con->prepare("SELECT nombre FROM director WHERE id_director=:did");
								$stmt->execute(array(":did"=>$film['id_director']));
								$director = $stmt->fetch(PDO::FETCH_ASSOC);

								$stmt = $db_con->prepare("SELECT * FROM pelicula WHERE id_metraje=:mid");
								$stmt->execute(array(":mid"=>$film['id_metraje']));
								$film_type = $stmt->fetch(PDO::FETCH_ASSOC);

								if($film_type=="") {
									$stmt = $db_con->prepare("SELECT * FROM serie WHERE id_metraje=:mid");
									$stmt->execute(array(":mid"=>$film['id_metraje']));
									$film_type = $stmt->fetch(PDO::FETCH_ASSOC);
								}

								$date = date_parse($film['fecha']);

							?>
							<div class="film" style="background-image: url('<?php echo htmlspecialchars($film_type["imagen"]) ?>');">
								<div class="info">
									<h3><strong><?= isset($film['nombrecap']) ? $film['nombrecap'] : $film['nombre'] ?></strong></h3>
									<div class="calification">
									<?php for($i = $film["puntuacion"]; $i>0; $i--): ?>
										<span class="glyphicon glyphicon-star"></span>
									<?php endfor ?>
									</div>
									<span><?= $date['year'] ?></span><br>
									<span><?= isset($film['descap']) ? $film['descap'] : $film['descripcion'] ?></span>
									<div class="chevron-container" id='<?php echo htmlspecialchars($film["id_metraje"]) ?>'>
										<span class="glyphicon glyphicon-chevron-down movie-chevron"></span>										
									</div>
								</div>
							</div>
						<?php endforeach?>
					</div>
					<div class="category">
						<h3>Mi lista</h3>							
						<?php foreach($list as $film): ?>
							<?php
								$stmt = $db_con->prepare("SELECT nombre FROM genero WHERE id_genero=:gid");
								$stmt->execute(array(":gid"=>$film['id_genero']));
								$genero = $stmt->fetch(PDO::FETCH_ASSOC);

								$stmt = $db_con->prepare("SELECT nombre FROM director WHERE id_director=:did");
								$stmt->execute(array(":did"=>$film['id_director']));
								$director = $stmt->fetch(PDO::FETCH_ASSOC);

								$stmt = $db_con->prepare("SELECT * FROM pelicula WHERE id_metraje=:mid");
								$stmt->execute(array(":mid"=>$film['id_metraje']));
								$film_type = $stmt->fetch(PDO::FETCH_ASSOC);

								if($film_type=="") {
									$stmt = $db_con->prepare("SELECT * FROM serie WHERE id_metraje=:mid");
									$stmt->execute(array(":mid"=>$film['id_metraje']));
									$film_type = $stmt->fetch(PDO::FETCH_ASSOC);
								}

								$date = date_parse($film['fecha']);

							?>
							<div class="film" style="background-image: url('<?php echo htmlspecialchars($film_type["imagen"]) ?>');">
								<div class="info">
									<h3><strong><?= $film['nombre'] ?></strong></h3>
									<div class="calification">
									<?php for($i = $film["puntuacion"]; $i>0; $i--): ?>
										<span class="glyphicon glyphicon-star"></span>
									<?php endfor ?>
									</div>
									<span><?= $date['year'] ?></span><br>
									<span><?= $film['descripcion'] ?></span>
									<div class="chevron-container" id='<?php echo 'lista'.htmlspecialchars($film["id_metraje"]) ?>'>
										<span class="glyphicon glyphicon-chevron-down movie-chevron"></span>										
									</div>
								</div>
							</div>
						<?php endforeach?>
					</div>
					<div class="info-container">
						<?php foreach($list as $film): ?>
							<?php
								$stmt = $db_con->prepare("SELECT nombre FROM genero WHERE id_genero=:gid");
								$stmt->execute(array(":gid"=>$film['id_genero']));
								$genero = $stmt->fetch(PDO::FETCH_ASSOC);

								$stmt = $db_con->prepare("SELECT nombre FROM director WHERE id_director=:did");
								$stmt->execute(array(":did"=>$film['id_director']));
								$director = $stmt->fetch(PDO::FETCH_ASSOC);

								$stmt = $db_con->prepare("SELECT * FROM pelicula WHERE id_metraje=:mid");
								$stmt->execute(array(":mid"=>$film['id_metraje']));
								$film_type = $stmt->fetch(PDO::FETCH_ASSOC);

								if($film_type=="") {
									$stmt = $db_con->prepare("SELECT * FROM serie WHERE id_metraje=:mid");
									$stmt->execute(array(":mid"=>$film['id_metraje']));
									$film_type = $stmt->fetch(PDO::FETCH_ASSOC);
								}

								$date = date_parse($film['fecha']);

							?>
							<div class="film-detail" id='<?php echo 'lista'.htmlspecialchars($film["id_metraje"]) ?>'>
								<div class="info-column">
									<h2><strong><?= $film['nombre'] ?></strong></h2>
									<div class="calification">
									<?php for($i = $film["puntuacion"]; $i>0; $i--): ?>
										<span class="glyphicon glyphicon-star"></span>
									<?php endfor ?>
									</div>
									<strong class="color-grey"><?= $date['year'] ?></strong>&nbsp;
									<strong class="color-grey"><?= isset($film_type['duracion']) ? $film_type['duracion'] . ' minutos' : '' ?></strong><br><br>
									<span class="color-grey"><?= $film['descripcion'] ?></span>
								</div>
							</div>
						<?php endforeach?>
					</div>
					<?php foreach($result as $r): ?>
					<?php
						$stmt = $db_con->prepare("SELECT metraje.* FROM metraje where id_categoria=:cid");
						$stmt->execute(array(":cid"=>$r['id_categoria']));
						$films=$stmt->fetchAll();
					?>
					<?php if(!$films==""): ?>
						<div class="category">
							<h3><?= $r['nombre'] ?></h3>							
							<?php foreach($films as $film): ?>
								<?php
									$stmt = $db_con->prepare("SELECT nombre FROM genero WHERE id_genero=:gid");
									$stmt->execute(array(":gid"=>$film['id_genero']));
									$genero = $stmt->fetch(PDO::FETCH_ASSOC);

									$stmt = $db_con->prepare("SELECT nombre FROM director WHERE id_director=:did");
									$stmt->execute(array(":did"=>$film['id_director']));
									$director = $stmt->fetch(PDO::FETCH_ASSOC);

									$stmt = $db_con->prepare("SELECT * FROM pelicula WHERE id_metraje=:mid");
									$stmt->execute(array(":mid"=>$film['id_metraje']));
									$film_type = $stmt->fetch(PDO::FETCH_ASSOC);

									if($film_type=="") {
										$stmt = $db_con->prepare("SELECT * FROM serie WHERE id_metraje=:mid");
										$stmt->execute(array(":mid"=>$film['id_metraje']));
										$film_type = $stmt->fetch(PDO::FETCH_ASSOC);
									}

									$date = date_parse($film['fecha']);

								?>
								<div class="film" style="background-image: url('<?php echo htmlspecialchars($film_type["imagen"]) ?>');">
									<div class="info">
										<h3><strong><?= $film['nombre'] ?></strong></h3>
										<div class="calification">
										<?php for($i = $film["puntuacion"]; $i>0; $i--): ?>
											<span class="glyphicon glyphicon-star"></span>
										<?php endfor ?>
										</div>
										<span><?= $date['year'] ?></span><br>
										<span><?= $film['descripcion'] ?></span>
										<div class="chevron-container" id='<?php echo htmlspecialchars($film["id_metraje"]) ?>'>
											<span class="glyphicon glyphicon-chevron-down movie-chevron"></span>										
										</div>
									</div>
								</div>
							<?php endforeach?>
						</div>
					<?php endif ?>
					<?php endforeach?>
					</div>
			</div>

		</div>

	</div>
		
	<script src="bootstrap/js/bootstrap.min.js"></script>

</body>

</html>