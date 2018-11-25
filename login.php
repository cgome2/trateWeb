<?php

//initilize the page
require_once 'init.web.php';

	if($_SERVER['REQUEST_METHOD'] =="POST")
	{
		$usuario = $_POST['usuario'];
		$clave = $_POST['clave'];
		$valida = rquery("select count(*) from usuarios where usuario ='$usuario' and password = sha1('$clave')");
		if($valida > 0)
		{
			$nombre =  rquery("select nombre from usuarios where usuario ='$usuario' and password = sha1('$clave')");
			$_SESSION['loginSite'] = true;
			$_SESSION['login'] =$nombre;
			header("location: index.php");
			exit;
		}
		else
		{
			$notok = true;
		}
		
	}
/*---------------- PHP Custom Scripts ---------

YOU CAN SET CONFIGURATION VARIABLES HERE BEFORE IT GOES TO NAV, RIBBON, ETC.
E.G. $page_title = "Custom Title" */

$page_title = "Siteomat Authentication";

/* ---------------- END PHP Custom Scripts ------------- */

//include header
//you can add your custom css in $page_css array.
//Note: all css files are inside css/ folder
// $page_css[] = "your_style.css";
$no_main_header = true;
$page_body_prop = array("id"=>"extr-page", "class"=>"animated fadeInDown ");
include("inc/header.php");

?>
<!-- ==========================CONTENT STARTS HERE ========================== -->
<!-- possible classes: minified, no-right-panel, fixed-ribbon, fixed-header, fixed-width-->
<header id="header">
	<!--<span id="logo"></span>-->

	<div id="logo-group">
		<img src="<?php echo ASSETS_URL; ?>/img/logo.png" alt="Siteomat 6"> </span>

		<!-- END AJAX-DROPDOWN -->
	</div>


</header>

<div id="main" role="main">

	<!-- MAIN CONTENT -->
	<div id="content" class="container ">

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
				<h1 class="txt-color-red login-header-big">Siteomat 6 </h1>

				<div class="well no-padding">
					<form action="<?php echo APP_URL; ?>" id="login-form" class="smart-form client-form" method="post">
						<header>
							Inicio de sesi&oacute;n
						</header>

						<fieldset>
							
							<section>
								<label class="label">Usuario</label>
								<label class="input"> <i class="icon-append fa fa-user"></i>
									<input type="text" name="usuario">
									<b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Nombre dle usuario</b></label>
							</section>

							<section>
								<label class="label">Clave</label>
								<label class="input"> <i class="icon-append fa fa-lock"></i>
									<input type="password" name="clave">
									<b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Ingrese su clave</b> </label>
							</section>

						</fieldset>
						<footer>
							<button type="submit" class="btn btn-primary">
								Ingresar
							</button>
						</footer>
					</form>

				</div>
								
			</div>
		</div>
	</div>

</div>
<!-- END MAIN PANEL -->
<!-- ==========================CONTENT ENDS HERE ========================== -->

<?php 
	//include required scripts
	include("inc/scripts.php"); 
?>

<!-- PAGE RELATED PLUGIN(S) 
<script src="..."></script>-->

<script type="text/javascript">
	runAllForms();

	$(function() {
		// Validation
		$("#login-form").validate({
			// Rules for form validation
			rules : {
				email : {
					required : true,
					email : true
				},
				password : {
					required : true,
					minlength : 3,
					maxlength : 20
				}
			},

			// Messages for form validation
			messages : {
				email : {
					required : 'Please enter your email address',
					email : 'Please enter a VALID email address'
				},
				password : {
					required : 'Please enter your password'
				}
			},

			// Do not change code below
			errorPlacement : function(error, element) {
				error.insertAfter(element.parent());
			}
		});
	});
</script>

<?php 
if(isset($notok))
{
	?>
<script>
$( document ).ready(function() {

$.smallBox({
                        title : "Datos Incorrectos",
                        content : " Revise sus credenciales",
                        color : "orange",
                        iconSmall : "fa fa-warning fa-2x fadeInRight animated",
                        timeout : 4000
                    });

});
 </script>
	<?php
}
	//include footer
	// include("inc/google-analytics.php"); 
?>