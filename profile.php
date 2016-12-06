<?php
session_start();

if(!isset($_SESSION['user_session']))
{
	header("Location: index.php");
}

include_once 'dbconfig.php';

$stmt = $db_con->prepare("SELECT nombre, id_perfil FROM usuario, perfil WHERE usuario.id_usuario=:uid and perfil.id_usuario = usuario.id_usuario");
$stmt->execute(array(":uid"=>$_SESSION['user_session']));
$result=$stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Netflex</title>
<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen"> 
<link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet" media="screen"> 
<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<link href="css/style.css" rel="stylesheet" media="screen">

</head>

<body class="profile">

<div class="container">
	<div class="row text-center">
		<div class="col-xs-12 col-sm-12 heading no-bg">
			<div class="long-shadow">Netflex</div>
		</div>
		<div class="col-xs-12 col-sm-12">
			<h1 style="margin-bottom: 50px;">¿Quien está viendo ahora?</h1>
			<?php foreach($result as $r): ?>
					<div class="profile-card" id="<?= $r['id_perfil'] ?>">
						<div class="avatar">
							<span class="glyphicon glyphicon-user"></span>
						</div>
						<h3><?= $r['nombre'] ?></h3>
					</div>
			<?php endforeach?>
		</div>
	</div>
</div>

<script src="bootstrap/js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<script type="text/javascript" src="js/script.js"></script>

</body>
</html>