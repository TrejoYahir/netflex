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
$stmt = $db_con->prepare("SELECT metraje.* from lista, perfil, metraje WHERE lista.id_perfil=perfil.id_perfil and perfil.id_perfil=:pid group by id_metraje;");
$stmt->execute(array(":pid"=>$_SESSION['profile_id']));
$list=$stmt->fetchAll();

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