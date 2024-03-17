<?php
//credits to cvolton/megasa1nt for this

class Captcha {
	public static function displayCaptcha() {
		include __DIR__ . "/../config/config.php";
		if ($enableCaptcha) {
			switch($captchaType) {
				case 1:
					echo "<script src='https://js.hCaptcha.com/1/api.js' id='captchascript' async defer></script>
					<div class=\"h-captcha\" data-sitekey=\"${captchaKey}\" data-theme='dark' style='border-width: 0px !important;border-radius: 20px !important;'></div>";
					break;
				case 2:
					echo "<script src='https://www.google.com/recaptcha/api.js' id='captchascript' async defer></script>
					<div class=\"g-recaptcha\" data-sitekey=\"${captchaKey}\" data-theme='dark'></div>";
					break;
				case 3:
					echo "<script src='https://challenges.cloudflare.com/turnstile/v0/api.js' id='captchascript' async defer></script>
					<div class=\"cf-turnstile\" data-sitekey=\"${captchaKey}\" data-theme='dark' data-callback=\"javascriptCallback\"></div>";
					break;
				default:
					break;
			}
		}
	}

	public static function validateCaptcha() {
		include __DIR__ . "/../config/config.php";
		if(!$enableCaptcha) return true;
		switch($captchaType) {
			case 1:
				if(isset($_GET['h-captcha-response'])) $_POST['h-captcha-response'] = $_GET['h-captcha-response'];
				$url = "https://hcaptcha.com/siteverify";
				$req = isset($_POST['h-captcha-response']) ? $_POST['h-captcha-response'] : null;
				break;
			case 2:
				if(isset($_GET['g-recaptcha-response'])) $_POST['g-recaptcha-response'] = $_GET['g-recaptcha-response'];
				$url = "https://www.google.com/recaptcha/api/siteverify";
				$req = isset($_POST['g-recaptcha-response']) ? $_POST['g-recaptcha-response'] : null;
				break;
			case 3:
				if(isset($_GET['cf-turnstile-response'])) $_POST['cf-turnstile-response'] = $_GET['cf-turnstile-response'];
				$url = "https://challenges.cloudflare.com/turnstile/v0/siteverify";
				$req = isset($_POST['cf-turnstile-response']) ? $_POST['cf-turnstile-response'] : null;
				break;
			default:
				return false;
				break;
		}
		$data = array(
			'secret' => $captchaSecret,
			'response' => $req
		);
		$verify = curl_init();
		curl_setopt($verify, CURLOPT_URL, $url);
		curl_setopt($verify, CURLOPT_POST, true);
		curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($verify, CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
		$response = curl_exec($verify);
		$responseData = json_decode($response);
		return $responseData->success;
	}
}