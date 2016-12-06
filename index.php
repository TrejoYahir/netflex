<?php

session_start();

if(isset($_SESSION['user_session'])!="")
{
	header("Location: home.php");
}

?>

<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Netflex</title>

<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
<link href="css/style.css" rel="stylesheet" type="text/css" media="screen">
<link async href="http://fonts.googleapis.com/css?family=Fredoka%20One" data-generated="http://enjoycss.com" rel="stylesheet" type="text/css"/>

<script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
<script type="text/javascript" src="js/validation.min.js"></script>
<script type="text/javascript" src="js/script.js"></script>

</head>

<body class="login">

	<div class="container-fluid no-margin no-padding">
	
		<div class="row no-margin no-padding">

			<div class="col-xs-12 col-sm-12 heading">
				<div class="long-shadow">Netflex</div>
			</div>

			<div class="col-xs-12 col-sm-12">
				<form class="form-signin" method="post" id="login-form">			
					<h2 class="form-signin-heading">Iniciar sesión</h2><hr />					
					<div id="error"></div>					
					<div class="form-group">
						<label for="user_email">Email</label>
						<input type="email" class="form-control" name="user_email" id="user_email" />
						<span id="check-e"></span>
					</div>
					
					<div class="form-group">
						<label for="password">Contraseña</label>
						<input type="password" class="form-control" placeholder="Password" name="password" id="password" />
					</div>
				 
					<hr />
					<button type="submit" class="btn btn-default btn-lg btn-block" name="btn-login" id="btn-login">Iniciar Sesión</button>
					<h4><a href="">Suscribete ya</a></h4>
				</form>
			</div>

		</div>

	</div>
		
	<script src="bootstrap/js/bootstrap.min.js"></script>

</body>

</html>