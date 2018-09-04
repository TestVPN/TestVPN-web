<?php
require_once(__DIR__ . "/global.php");
require_once(__DIR__ . "/mail.php");
session_start();
HtmlHeader("HOME");
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

if (!empty($_GET['action']))
{
	$action = isset($_GET['action'])? $_GET['action'] : '';
	$action = (string)$action;
	if ($action === "mail")
	{
		$mail_status = SendMail($_SESSION['Mail'], $_SESSION['Username']) . "</br>";
?>
	<div class="login-page">
		<div class="form">
			<form action="index.php" method="post" class="login-form">
				<a>
				Mail status:</br>
				<?php echo $mail_status; ?></br>
				</a>
				<button type="submit">okay</button>
				<p class="message">All done? <a href="logout.php">Log out</a></p>
			</form>
		</div>
	</div>
<?php
	}
	if ($action === "create")
	{
	$db = new PDO(DATABASE_PATH);
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

	$stmt = $db->prepare('SELECT * FROM Accounts WHERE Username = ? ');
	$stmt->execute(array($_SESSION['Username']));
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
	if ($rows)
	{
		$total_vpns = $rows[0]['NumVPNS'];
	}
	$total_vpns += 1;

	if ($total_vpns >= 10)
	{

?>
	<div class="login-page">
		<div class="form">
			<form action="index.php" class="login-form">
				<a><?php echo "Created maximum of $total_vpns vpns"; ?></br></a>
				<button type="submit">menu</button>
				<p class="message">All done? <a href="logout.php">Log out</a></p>
			</form>
		</div>
	</div>
<?php
		fok();
	}

	$cert_name = $_SESSION['Username'] . "-" . $total_vpns;
	$shell_str = "sudo " . TEST_VPN_DIR . "adduser-vpn.sh $cert_name";
	$out = shell_exec($shell_str);
	//echo "exec:</br>$shell_str</br>out:</br> $out";

	$current_date = date("Y-m-d H:i:s");
	$stmt = $db->prepare('UPDATE Accounts SET NumVPNS = ?, LastVPN = ? WHERE Username = ? ');
	$stmt->execute(array($total_vpns, $current_date, $_SESSION['Username']));
?>
	<div class="login-page">
		<div class="form">
			<form action="index.php" class="login-form">
				<a><?php echo "Created $total_vpns / 10 VPNS"; ?></br></a>
				<a>Press button to go back to menu</br></a>
				<button type="submit">menu</button>
				<p class="message">All done? <a href="logout.php">Log out</a></p>
			</form>
		</div>
	</div>
<?php
	}
}
else
{
?>
	<div class="login-page">
		<div class="form">
			<a>
			Press buttons to either create a new config</br>
			or send existing ones to your mail</br>
			</a>
			<button onclick="window.location.href='index.php?action=mail'">Mail</button>
			<form action="index.php?action=create" method="post" class="login-form">
				<button type="submit">create</button>
				<p class="message">All done? <a href="logout.php">Log out</a></p>
			</form>
		</div>
	</div>
<?php
}

?>
	<div class="certs-dl">
<?php
for ($i=0;$i<10;$i++)
{
	$cert_file=CERT_PATH . $_SESSION['Username'] . "-" . $i . ".ovpn";
	if (file_exists($cert_file))
	{
	echo "
		<!-- <a href=\"download.php?id=$i\">download $username-$i.ovpn<br/></a> -->
		<button class=\"certs-btn\" onclick=\"window.location.href='download.php?id=$i'\"> download $username-$i.ovpn </button>
	";
	}
	else
	{
		//echo "$cert_file is not there<br>";
	}
}
?>
	</div>
<?php
}
?>
