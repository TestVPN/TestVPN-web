<?php
require_once(__DIR__ . "/global.php");
session_start();
HtmlHeader("rename");
if (IsLoggedIn())
{
	global $username;
	$username = $_SESSION['Username'];
	LoginIndex();
}
else
{
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
$cert_file=CERT_PATH . $username . "-" . $id . ".ovpn";

if ($error === "")
{
	if (!file_exists($cert_file))
	{
		$error = "no config file with this id=$id";
	}
}


if (!empty($_POST['name']))
{
	$name = isset($_POST['name'])? $_POST['name'] : '';
	$name = (string)$name;
	$error = "";
	if (strlen($name) > 20)
	{
		$error = "Maximum name length is 20 characters.";
	}
	if (!preg_match('/^[a-z0-9]+$/i', $name))
	{
		$errror = "Only numbers and letters allowed.";
	}

	if ($error !== "")
	{
?>
	<div class="login-page">
		<div class="form">
			<form action="rename.php?id=<?php echo $id; ?>" class="login-form" method="post">
				<a style="color:red"><?php echo $error; ?></br></a>
				<button type="submit">okay</button>
			</form>
		</div>
	</div>
<?php
		fok();
	}


	$db = new PDO(DATABASE_PATH);
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	$stmt = $db->prepare('UPDATE Accounts SET Config' . $id .' = ? WHERE Username = ?;');
	$stmt->execute(array($name, $_SESSION['Username']));
	$aConfig = $_SESSION['Config']; // load old cfg
	$aConfig[$id] = $name;          // update
	$_SESSION['Config'] = $aConfig; // save
?>
	<div class="login-page">
		<div class="form">
			<form action="index.php" class="login-form">
				<a>Updated config name to ' <?php echo $name; ?> '</br></a>
				<button type="submit">okay</button>
			</form>
		</div>
	</div>
<?php
fok();
}


if ($error === "")
{
?>
	<div class="login-page">
		<div class="form">
			<form method="post" action="rename.php?id=<?php echo $id; ?>" class="login-form">
				<input id="name" name="name" type="text" placeholder="new config name"/>
				<button type="submit">update</button>
				<p class="message">Old name was ok? <a href="index.php">back</a></p>
			</form>
		</div>
	</div>
<?php
}
else
{
?>
	<div class="login-page">
		<div class="form">
			<form action="index.php" class="login-form">
				<a>Error: <?php echo $error; ?></br></a>
				<button type="submit">back</button>
			</form>
		</div>
	</div>
<?php
}
fok();
}
?>
