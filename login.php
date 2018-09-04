<?php
require_once(__DIR__ . "/global.php");
session_start();
HtmlHeader("login");

function print_html_main($fail_reason)
{
?>
	<div class="login-page">
		<div class="form">
			<form method="post" action="login.php" class="login-form">
				<input id="username" name="username" type="text" placeholder="username"/>
				<input id="password" name="password" type="password" placeholder="password"/>
				<button type="submit">login</button>
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


if (!empty($_POST['username']) and !empty($_POST['password']))
{
	$username = isset($_POST['username'])? $_POST['username'] : '';
	$password = isset($_POST['password'])? $_POST['password'] : '';

	$db = new PDO(DATABASE_PATH);
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	$stmt = $db->prepare('SELECT * FROM Accounts WHERE Username = ? and Password = ?');
	$stmt->execute(array($username, $password));

	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if ($rows)
	{
        $name = $rows[0]['Username'];
	$_SESSION['Username'] = $name;
	$_SESSION['IsLogged'] = "online";

	$current_date = date("Y-m-d H:i:s");
	$stmt = $db->prepare('UPDATE Accounts SET LastLogin = ? WHERE Username = ? ');
	$stmt->execute(array($current_date, $_SESSION['Username']));
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
				<a><?php echo "Logged in as '$name'."; ?></a>
				<button type="submit">Okay</button>
			</form>
		</div>
	</div>
<?php
	}
	else
	{
		print_html_main("wrong username or password");
		$_SESSION['IsLogged'] = "failed";
	}
}
else if (!empty($_POST['username']) or !empty($_POST['password']))
{
	print_html_main("both fields are required");
}
else //no name or pw given -> ask for it
{
	print_html_main("none");
}
fok();
?>

