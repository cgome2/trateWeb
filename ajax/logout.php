
<script>
	// pagefunction

	var pagefunction = function() {
	$.smallBox({
		title : "Cerrando sesion",
		content : "Gracias por utilizar SiteOmat 6",
		color : "#5384AF",
		timeout:800,
		icon : "fa fa-bell"
	});
	setTimeout(function(){window.location.href='login.php'},1800);

		

	// end pagefunction
}
	// run pagefunction
	pagefunction();


</script>

<?php
session_start();
unset($_SESSION['loginSite']);

