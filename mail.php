<?php
require_once(__DIR__ . "/secrets.php");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once(__DIR__ . "/other/PHPMailer/Exception.php");
require_once(__DIR__ . "/other/PHPMailer/PHPMailer.php");
require_once(__DIR__ . "/other/PHPMailer/SMTP.php");

function SendMail($mailAddr, $username)
{
	$mail = new PHPMailer(true);
	try {
		//Server settings
		$mail->SMTPDebug = 0; // 2=Enable verbose debug output
		$mail->isSMTP();
		$mail->Host = 'smtp.gmail.com';
		$mail->SMTPAuth = true;
		$mail->Username = 'info.testvpn@gmail.com';
		$mail->Password = SECRET_MAIL_PASS;
		$mail->SMTPSecure = 'ssl';
		$mail->Port = 465;

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
		$mail->Subject = "Free TestVPN";
		$mail->Body    = "Hello <b>User!</b></br>You have $certs certs";
		$mail->AltBody = "Hello User! (get html u nobo)";

		$mail->send();
		return 'Message has been sent';
	} catch (Exception $e) {
		return 'Message could not be sent. Mailer Error: ' . (string)$mail->ErrorInfo;
	}
}
?>
