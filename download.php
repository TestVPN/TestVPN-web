<?php
require_once(__DIR__ . "/global.php");
session_start();
if (IsLoggedIn())
{
	global $username;
	$username = $_SESSION['Username'];
	LoginIndex();
}
else
{
	HtmlHeader("download");
	NotLoginIndex();
}
fok();


function NotLoginIndex()
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

function LoginIndex()
{
global $username;
$id=0;
$error="";
if (!empty($_GET['id']))
{
	$id = isset($_GET['id'])? $_GET['id'] : 0;
	if (!is_numeric($id))
	{
		$error="erronous id";
	}
	$id = (int)$id;
}

if ($error === "")
{
	$cert_file=CERT_PATH . $username . "-" . $id . ".ovpn";
	if (file_exists($cert_file))
	{
		$aConfig = $_SESSION['Config'];
		$cert_name = $aConfig[$id];
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		if ($cert_name === "")
		{
		header('Content-Disposition: attachment; filename='.basename($cert_file));
		} else {
		header('Content-Disposition: attachment; filename='.$cert_name.'.ovpn');
		}
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
		$error = "file not found";
	}
}


HtmlHeader("download");

?>
	<div class="login-page">
		<div class="form">
			<form action="index.php" method="post" class="login-form">
				<a><?php if ($error === "") { echo "Downloaded c:"; } else { echo "error: $error"; } ?></br></a>
				<button type="submit">back</button>
			</form>
		</div>
	</div>
<?php
fok();
}
?>
