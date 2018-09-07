<?php
require_once(__DIR__ . "/secrets.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once(__DIR__ . "/other/PHPMailer/Exception.php");
require_once(__DIR__ . "/other/PHPMailer/PHPMailer.php");
require_once(__DIR__ . "/other/PHPMailer/SMTP.php");

function SetServerSettings($mail, $IsElastic = IS_ELASTIC)
{
	//Server settings
	if ($IsElastic)
	{
		$mail->SMTPDebug = 0; // 2=Enable verbose debug output
		$mail->isSMTP();
		$mail->Host = "smtp.elasticemail.com";  //elastice
		$mail->SMTPAuth = true;
		$mail->Username = 'info.testvpn@gmail.com';
		$mail->Password = SECRET_MAIL_PASS_ELASTIC;
		$mail->Port = 2525; // elastic
	}
	else
	{
		$mail->SMTPDebug = 0; // 2=Enable verbose debug output
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = 'info.testvpn@gmail.com';
		$mail->Password = SECRET_MAIL_PASS;
		$mail->SMTPSecure = 'ssl'; // google ssl
		$mail->Port = 465; // google ssl
	}
	return $mail;
}

function SendMail($mailAddr, $subject, $body, $altbody)
{
	$mail = new PHPMailer(true);
	try {
		$mail = SetServerSettings($mail);
		//Recipients
		$mail->setFrom('info.testvpn@gmail.com', 'Test VPN');
		$mail->addAddress($mailAddr);

		//Content
		$mail->isHTML(true);
		$mail->Subject = "$subject";
		$mail->Body    = "$body";
		$mail->AltBody = "$altbody";
		$mail->send();
		return 'Message has been sent';
	} catch (Exception $e) {
		return 'Message could not be sent. Mailer Error: ' . (string)$mail->ErrorInfo;
	}
}

function SendMailPasswd($mailAddr, $token)
{
		//Content
		$reset_url = "https://" . DOMAIN_NAME . "/new_password.php?token=$token";
		$Subject = "Free TestVPN";
		$Body    = "
Hello TestVPN User!</br>\n
</br>\n
Someone requested a password reset on your mail.</br>\n
If this was not you ignore this mail.</br>\n
</br>\n
If you want to change your password click <a href=\"$reset_url\">here</a>.
		";

		$AltBody = "
Hello TestVPN User!

Someone requested a password reset on your mail.
If this was not you ignore this mail.

If you want to change your password go to this url:
$reset_url
		";
	return SendMail($mailAddr, $Subject, $Body, $AltBody);
}


function SendMailConfig($mailAddr, $username)
{
	$mail = new PHPMailer(true);
	try {
		$mail = SetServerSettings($mail, false); // elastic mails with attachement get deleted

		//Recipients
		$mail->setFrom('info.testvpn@gmail.com', 'Test VPN');
		$mail->addAddress($mailAddr);

		//Attachments
		$certs = 0;
		$aConfig = $_SESSION['Config'];
		for ($i=0;$i<10;$i++)
		{
			$cert_name=$username . "-" . $i . ".ovpn";
			$cert_path=CERT_PATH . $cert_name;
			if (file_exists($cert_path))
			{
				if ($aConfig[$i] === "")
				{
				$mail->addAttachment($cert_path, $cert_name);
				}
				else
				{
				$mail->addAttachment($cert_path, $aConfig[$i] . '.ovpn');
				}
				$certs++;
			}
		}

		//Content
		$mail->isHTML(true);
		$mail->Subject = "Free TestVPN configs";
		$mail->Body    = "
<html>
	<head>
	</head>
	<body>
Hello <b>User!</b></br>\n
</br>\n
You have $certs config files.</br>\n
Use one for each device you want to connect to the VPN.</br>\n
</br>\n
Download the <a href=\"https://openvpn.net/index.php/open-source/downloads.html\">OpenVPN client</a> for your operating system and load the configs.</br>\n
	</body>
</html>
";
		$mail->AltBody = "Hello! You have $certs!";

		$mail->send();
		return 'Message has been sent';
	} catch (Exception $e) {
		return 'Message could not be sent. Mailer Error: ' . (string)$mail->ErrorInfo;
	}
}
?>
