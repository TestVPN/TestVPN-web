<?php
require_once(__DIR__ . "/global.php");
HtmlHeader("logout");

session_start();
session_destroy(); //clear all before new login
//session_start();
?>
	<script type="text/javascript">
	window.setTimeout(function()
	{
		window.location.href='index.php';
	}, 2000);
	</script>
	<div class="login-page">
		<div class="form">
			<form action="index.php" class="login-form">
				<a>Logged out.</a>
				<button type="submit">Okay</button>
			</form>
		</div>
	</div>
<?php
fok();
?>

