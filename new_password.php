<?php
require_once(__DIR__ . "/global.php");
session_start();
HtmlHeader("reset password");

$IsValidToken = false;
$token = "empty";
if (!empty($_GET['token']) && isset($_SESSION['PASSWD_TOKEN']))
{
	echo "TOKEN NOT EMPTY</br>";
	$token = isset($_GET['token'])? $_GET['token'] : '';
	$token = (string)$token;
	if ($token === $_SESSION['PASSWD_TOKEN'])
	{
		$IsValidToken = true;
	}
	else
	{
		echo "<debug> TOKEN=" . $_SESSION['PASSWD_TOKEN'];
	}
}

function print_html_main($fail_reason)
{
	global $token;
?>
	<div class="login-page">
		<div class="form">
			<form method="post" action="new_password.php?token=<?php echo $token; ?>" class="login-form">
				<input id="password" name="password" type="password" placeholder="new password"/>
				<input id="password_repeate" name="password_repeate" type="password" placeholder="repeate password"/>
				<button type="submit">update password</button>
				<p class="message">Not registered? <a href="register.php">Create an account</a></p>
			</form>
		</div>
	</div>
<?php
	if ($fail_reason != "none")
	{
		echo "<font color=\"red\">$fail_reason</font>";
	}
}

if ($IsValidToken === false)
{
	$real_token = isset($_SESSION['PASSWD_TOKEN']) ? $_SESSION['PASSWD_TOKEN'] : "unset";
	echo "Your token: $token</br>Real token: $real_token";
?>
	<div class="login-page">
		<div class="form">
			<form method="post" action="index.php" class="login-form">
				<a>Invalid or outdated reset link.</a>
				<button type="submit">okay</button>
				<p class="message">Not registered? <a href="register.php">Create an account</a></p>
			</form>
		</div>
	</div>
<?php
	fok();
}


if (!empty($_POST['password_repeate']) and !empty($_POST['password']))
{
	$password_repeate = isset($_POST['password_repeate'])? $_POST['password_repeate'] : '';
	$password = isset($_POST['password'])? $_POST['password'] : '';
	$password = (string)$password;
	$password_repeate = (string)$password_repeate;
	if ($password !== $password_repeate)
	{
		print_html_main("Passwords have to be the same.");
		fok();
	}
	$email = "";
	if (isset($_SESSION['RESET_MAIL']))
	{
		$email = $_SESSION['RESET_MAIL'];
	}
	else
	{
		echo "session mail: $email</br>";
		print_html_main("Something went wrong please try agian.");
		fok();
	}

	$db = new PDO(DATABASE_PATH);
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	$stmt = $db->prepare('UPDATE Accounts SET Password = ? WHERE Mail = ?;');
	$stmt->execute(array($password, $email));

	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if ($rows)
	{
		print_html_main("Sorry something went wrong.");
		$_SESSION['IsLogged'] = "failed";
	}
	else
	{
	/*
	$current_date = date("Y-m-d H:i:s");
	$stmt = $db->prepare('UPDATE Accounts SET LastPassChange = ? WHERE Username = ? ');
	$stmt->execute(array($current_date, $_SESSION['Username']));
	*/
?>
	<script type="text/javascript">
	window.setTimeout(function()
	{
		window.location.href='index.php';
	}, 2000);
	</script>
	<div class="login-page">
		<div class="form">
			<form action="login.php" class="login-form">
				<a>Successfully updated password.</a>
				<button type="submit">login</button>
			</form>
		</div>
	</div>
<?php
	}
}
else if (!empty($_POST['password_repeate']) or !empty($_POST['password']))
{
	print_html_main("both fields are required");
}
else //no name or pw given -> ask for it
{
	print_html_main("none");
}
fok();
?>

