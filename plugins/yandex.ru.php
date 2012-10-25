<?php
class yandex_ru implements SocialLoginPlugin {
	public static function login( $code ) {
		global $wgYandexSecret, $wgYandexAppId;
		$host = $_SERVER['SERVER_NAME'];
		$r = SLgetContents("https://oauth.yandex.ru/token", array(
			"client_id" => $wgYandexAppId,
			"client_secret" => $wgYandexSecret,
			"grant_type" => "authorization_code",
			"code" => $code
		));
		$response = json_decode($r);
		if (!isset($response->access_token)) return false;
		$access_token = $response->access_token;
		$r = SLgetContents("https://login.yandex.ru/info?format=json&oauth_token=$access_token");
		$response = json_decode($r);
		$id = $response->id;
		$name = SLgenerateName(array($response->display_name, $response->real_name));
		$_SESSION['sl_token'] = $access_token;
		return array(
			"id" => $id,
			"service" => "yandex.ru",
			"profile" => "$id@yandex.ru",
			"name" => $name,
			"email" => $response->default_email,
			"realname" => $response->real_name
		);
	}

	public static function check( $id ) {
	    $access_token = $_SESSION['sl_token'];
		$r = SLgetContents("https://login.yandex.ru/info?format=json&oauth_token=$access_token");
		$response = json_decode($r);
		if (!($response = json_decode($r)) || !isset($response->id) || $response->id != $id) return false;
		return array(
			"id" => $id,
			"service" => "yandex.ru",
			"profile" => "$id@yandex.ru",
			"realname" => $response->real_name,
			"access_token" => $access_token
		);
	}
	
	public static function loginUrl( ) {
		global $wgYandexAppId;
		$host = $_SERVER['SERVER_NAME'];
		return "https://oauth.yandex.ru/authorize?client_id=$wgYandexAppId&display=page&response_type=code&redirect_uri=" . urlencode("http://$host/Special:SocialLogin?action=login&service=yandex.ru");
	}
}