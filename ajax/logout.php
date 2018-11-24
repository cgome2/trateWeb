
<script>
	// pagefunction

	var pagefunction = function() {
       window.location.href='login.php';
	// end pagefunction
}
	// run pagefunction
	pagefunction();


</script>

<?php
session_start();
unset($_SESSION['loginSite']);

