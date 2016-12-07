
$('document').ready(function()
{ 
	 /* validation */
	 $("#login-form").validate({
		rules:
		{
			password: {
			required: true,
			},
			user_email: {
			required: true,
			email: true
			},
		 },
		 messages:
		 {
			password:{
						required: "Ingresa tu contraseña"
					 },
			user_email: "Ingresa un email válido",
		 },
		 submitHandler: submitForm	
		 });  
		 /* validation */
		 
		 /* login submit */
		 function submitForm()
		 {		
			var data = $("#login-form").serialize();
				
			$.ajax({
				
			type : 'POST',
			url  : 'login_process.php',
			data : data,
			beforeSend: function()
			{	
				$("#error").fadeOut();
				$("#btn-login").html('<span class="glyphicon glyphicon-transfer"></span> &nbsp; Enviando ...');
				console.log(data)
			},
			success: function(response) {						
				if(response=="ok"){									
					$("#btn-login").html('Entrando ...');
					setTimeout(' window.location.href = "profile.php"; ',4000);
				}
				else{									
					$("#error").fadeIn(1000, function(){						
						$("#error").html('<div class="alert alert-danger"> <span class="glyphicon glyphicon-info-sign"></span> &nbsp; '+response+'!</div>');
						$("#btn-login").html('<span class="glyphicon glyphicon-log-in"></span> &nbsp; Entrar');
					});
				}
				}
			});
			return false;
		}
		 /* login submit */



		$(".profile-card").click(function () {
			var clickedBtnID = $(this).attr('id');

			window.location.href = "home.php?profile_id=" + clickedBtnID; 
		});

		$(".chevron-container").click(function () {
			$('.info-container').css('height','auto');
			$('.film-detail').css('height','0px');
			$('.film-detail').css('visibility','hidden');
			var clickedBtnID = $(this).attr('id');
			$("#"+clickedBtnID+".film-detail").css('height','300px');
			$("#"+clickedBtnID+".film-detail").css('visibility','visible');

		});

});
