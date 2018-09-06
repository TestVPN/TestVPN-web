<?php
require_once(__DIR__ . "/global.php");
require_once(__DIR__ . "/other/random_compat/lib/random.php");
require_once(__DIR__ . "/mail.php");
session_start();
HtmlHeader("login");

function print_html_main($fail_reason)
{
?>
	<div class="login-page">
		<div class="form">
			<form method="post" action="reset_password.php" class="login-form">
				<input id="email" name="email" type="text" placeholder="e-mail"/>
				<button type="submit">send</button>
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


if (!empty($_POST['email']))
{
	$email = isset($_POST['email'])? $_POST['email'] : '';
	$email = (string)$email;

	$db = new PDO(DATABASE_PATH);
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	$stmt = $db->prepare('SELECT * FROM Accounts WHERE Mail = ?');
	$stmt->execute(array($email));

	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if ($rows)
	{
	try {
	    $pw_token = random_bytes(32);
	} catch (TypeError $e) {
	    // Well, it's an integer, so this IS unexpected.
	    print_html_main("An unexpected error has occurred");
	} catch (Error $e) {
	    // This is also unexpected because 32 is a reasonable integer.
	    print_html_main("An unexpected error has occurred");
	} catch (Exception $e) {
	    // If you get this message, the CSPRNG failed hard.
	    print_html_main("Could not generate a random string. Please contact an admin.");
	}

	$pw_token = bin2hex($pw_token);
	$pw_token = (string)$pw_token;
	$_SESSION['IsLogged'] = "pw_reset";
	$_SESSION['PASSWD_TOKEN'] = $pw_token;
	$_SESSION['RESET_MAIL'] = $email;
	$mailstatus = SendMailPasswd($email, $pw_token);
?>
	<!--
	<script type="text/javascript">
	window.setTimeout(function()
	{
		window.location.href='index.php';
	}, 2000);
	-->
	</script>
	<div class="login-page">
		<div class="form">
			<form action="index.php" class="login-form">
				<a><?php echo "A reset link has been sent to '$email'.<br>Mail status: $mailstatus"; ?></a>
				<button type="submit">Okay</button>
			</form>
		</div>
	</div>
<?php
	}
	else
	{
		print_html_main("Error: this is not a valid mail.");
		$_SESSION['IsLogged'] = "failed";
	}
}
else
{
	print_html_main("none");
}
fok();
?>

