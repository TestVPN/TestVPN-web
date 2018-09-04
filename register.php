<?php
require_once(__DIR__ . "/global.php");
session_start();
HtmlHeader("register");

function print_html_main($fail_reason)
{
?>
	<div class="login-page">
		<div class="form">
			<form method="post" action="register.php" class="login-form">
				<input id="username" name="username" type="text" placeholder="name"/>
				<input id="password" name="password" type="password" placeholder="password"/>
				<input id="repeate_password" name="repeate_password" type="password" placeholder="repeate password"/>
				<input id="beta_key" name="beta_key" type="text" placeholder="BETA_KEY"/>
				<!-- <input type="text" placeholder="email address"/> -->
				<button>create</button>
				<p class="message">Already registered? <a href="login.php">Sign In</a></p>
			</form>
		</div>
	</div>
<?php
	/*
	echo
	"
			<h2> TestVPN Register</h2>
        		<form method=\"post\" action=\"register.php\">
				<div id=\"html_element\"></div>
                		<input id=\"username\" type=\"text\" name=\"username\"  placeholder=\"username\"></br>
                		<input id=\"password\" type=\"password\" name=\"password\" placeholder=\"password\"></br>
				</br>
                		<input type=\"submit\" value=\"Register\" >
        		</form>
			<form>
				<input type=\"button\" value=\"Got an account -> Login\" onclick=\"window.location.href='login.php'\" />
			</form>
	";
	*/
	if ($fail_reason != "none")
	{
		echo "<font color=\"red\">$fail_reason</font>";
	}
}


if (!empty($_POST['username']) and !empty($_POST['password']) and !empty($_POST['repeate_password']))
{
	$username = isset($_POST['username'])? $_POST['username'] : '';
	$password = isset($_POST['password'])? $_POST['password'] : '';
	$repeate_password = isset($_POST['repeate_password'])? $_POST['repeate_password'] : '';


	$username = (string)$username;
	$password = (string)$password;
	$repeate_password = (string)$repeate_password;
	
	if ($repeate_password != $password)
	{
		print_html_main("Passwords have to be the same");
		fok();
	}
	/*
	if (empty($_POST["g-recaptcha-response"]))
	{
		print_html_main("make sure to click the captcha");
		fok();
	}
	
	$response = $_POST["g-recaptcha-response"];
	$url = 'https://www.google.com/recaptcha/api/siteverify';
	$data = array(
		'secret' => 'SECRET',
		'response' => $_POST["g-recaptcha-response"]
	);
	$options = array(
		'http' => array (
			'method' => 'POST',
			'content' => http_build_query($data),
			'header' => "Content-Type: application/x-www-form-urlencoded\r\n"
		)
	);
	$context  = stream_context_create($options);
	$verify = file_get_contents($url, false, $context);
	$captcha_success=json_decode($verify);
	if ($captcha_success->success==false) {
		print_html_main("Detected bot");
		fok();
	} else if ($captcha_success->success==true) {
		//echo "<p>proofed human!</p>";
	}
	else
	{
		print_html_main("Something went horrible wrong. Please contact an admin.");
		fok();
	}
	*/
	if (strlen($username) > 32)
    {
        print_html_main("Username too long.");
        fok();
    }
    if (strlen($password) > 64)
    {
        print_html_main("Password too long.");
        fok();
    }



	if (!preg_match('/^[a-z0-9]+$/i', $username))
	{
		print_html_main("Only letters and numbers in username allowed");
		fok();
	}

	$db = new PDO(DATABASE_PATH);
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	$stmt = $db->prepare('SELECT * FROM Accounts WHERE Username = ?');
	$stmt->execute(array($username));
	$db = null;
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if ($rows || $username == "admin") //add blocked users here
	{
		print_html_main("Username already exsits");
		fok();
	}
        $current_date = date("Y-m-d H:i:s");
        
        //=================================
        // A D D Account to D A T A B A S E
        //=================================
    	$db = new PDO(DATABASE_PATH);
    	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
    	$stmt = $db->prepare('INSERT INTO Accounts (Username, Password, IP, RegisterDate) VALUES (?, ?, ?, ?)');
	$stmt->execute(array($username, $password, $_SERVER['REMOTE_ADDR'], $current_date));
	//print_html_main("sucessfully created an account");


        $_SESSION['Username'] = $username;
        $_SESSION['IsLogged'] = "online";
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
				<a><?php echo "Registered account '$username'."; ?></a>
				<button type="submit">Okay</button>
			</form>
		</div>
	</div>
<?php
}
else if (!empty($_POST['username']) or !empty($_POST['password']))
{
	print_html_main("All fields are required");
}
else //no name or pw given -> ask for it
{
	print_html_main("none");
}
fok();
?>

