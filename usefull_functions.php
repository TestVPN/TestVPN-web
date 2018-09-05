<?php
require_once(__DIR__ . "/global.php");

function fok()
{
	HtmlFooter();
	die();
}

function HtmlFooter()
{
?>
	</body>
</html>
<?php
}

function HtmlHeader($title)
{
?>
<!DOCTYPE html>
<html lang="en">
  <head>
	<title><?php echo "TestVPN - " . $title; ?></title>
	<link rel="stylesheet" href="style.css">
	<script src="main.js"></script>
 </head>
  <body>
	<a>
<?php
	//Get City
	$ip = $_SERVER['REMOTE_ADDR'];
	$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
	echo "Your ip:  $ip city: $details->city";
?>
	</a>
<?php
}

function GetAccountAge($username) //Totally working but unused yet
{
    $db = new PDO(DATABASE_PATH);
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
    $stmt = $db->prepare('SELECT * FROM Accounts WHERE Username = ?');
    $stmt->execute(array($username));
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($rows)
    {
        $register_date = new DateTime($rows[0]['RegisterDate']);
        $current_date = date_create(date("Y-m-d"));
        $interval = $current_date->diff($register_date);
        
        return $interval->days;
        
        //working but only interesting for debugging
        /*
        $register_date_str = $register_date->format('Y-m-d');
        $current_date_str = $current_date->format('Y-m-d');
        echo "<a>Total days: $interval->days</a></br>";
        echo "<a>Your registerd " . $interval->y . " years, " . $interval->m." months, ".$interval->d." days  ago </br></a>";
        echo "<a>Register date=$register_date_str </br></a>";
        echo "<a>Today date=$current_date_str </br></a>";
        */
    }
    return -1;
}

function AntiXSS($str)
{
	$str = filter_var($str, FILTER_SANITIZE_STRING);
	$str = htmlspecialchars($str);
	return $str;
}

function IsLoggedIn()
{
	if (empty($_SESSION['IsLogged']) || $_SESSION['IsLogged'] != "online")
		return false;
	return true;
}

function CheckAccountState($username)
{
	$db = new PDO(DATABASE_PATH);
	$db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
	$stmt = $db->prepare('SELECT * FROM Accounts WHERE Username = ?');
	$stmt->execute(array($username));

	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	if ($rows)
	{
		//could check for frozen or banned account here
	}
	else
	{
		echo "<a>Invalid account</a></br>";
		echo "<form class=\"form\"><input type=\"button\" value=\"Login\" onclick=\"window.location.href='login.php'\"/></form>";
		fok();
	}
}
?>

