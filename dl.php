<?php
/************************
*    DELETE ME          *
*************************

this file was created because download.php bugged
if download.php is fully working delete this file






*/
require_once(__DIR__ . "/global.php");
session_start();
if (IsLoggedIn())
{
	$id = 7;
	$username = $_SESSION['Username'];
	$cert_file=CERT_PATH . $username . "-" . $id . ".ovpn";
	if (file_exists($cert_file))
	{
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename='.basename($cert_file));
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($cert_file));
		ob_clean();
		flush();
		readfile($cert_file);
		exit();
	}
	else
	{
		echo "file not found $cert_file <a href=\"index.php\">back</a>";
	}
}
else
{
?>
	<div class="login-page">
		<div class="form">
			<form action="login.php" class="login-form">
				<a>Please login first.</br></a>
				<button type="submit">login</button>
			</form>
		</div>
	</div>
<?php
}
?>
