<?php
	session_start();
	require_once 'dbconfig.php';

	if(isset($_POST['btn-login']))
	{
		//$user_name = $_POST['user_name'];
		$user_email = trim($_POST['user_email']);
		$user_password = trim($_POST['password']);
		
		$password = $user_password;
		
		try
		{	
		
			$stmt = $db_con->prepare("SELECT * FROM usuario WHERE correo=:email");
			$stmt->execute(array(":email"=>$user_email));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$count = $stmt->rowCount();
			
			if($row['contrasena']==$password){
				
				echo "ok"; // log in
				$_SESSION['user_session'] = $row['id_usuario'];
			}
			else{
				
				echo "Usuario o contraseña incorrectos"; // wrong details 
			}
				
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
	}

?>