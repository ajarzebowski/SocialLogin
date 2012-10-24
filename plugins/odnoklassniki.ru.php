<?php
class odnoklassniki_ru implements SocialLoginPlugin {
	public static function login( $code ) {
		global $wgOdnoklassnikiSecret, $wgOdnoklassnikiAppId, $wgOdnoklassnikiPublic;
		$r = SLgetContents("http://api.odnoklassniki.ru/oauth/token.do", http_build_query(array(
			"redirect_uri" => SpecialPage::getTitleFor('SocialLogin')->getCanonicalURL() . "?action=login&service=odnoklassniki.ru",
			"client_id" => $wgOdnoklassnikiAppId,
			"client_secret" => $wgOdnoklassnikiSecret,
			"grant_type" => "authorization_code",
			"code" => $code
		), '', '&'));
		$response = json_decode($r);
		if (!isset($response->access_token)) return false;
		$access_token = $response->access_token;
		$sig = md5("application_key=$wgOdnoklassnikiPublic" . "client_id=$wgOdnoklassnikiAppId" . "method=users.getCurrentUser" . md5($access_token . $wgOdnoklassnikiSecret));
		$r = SLgetContents("http://api.odnoklassniki.ru/fb.do?application_key=$wgOdnoklassnikiPublic&sig=$sig&client_id=$wgOdnoklassnikiAppId&method=users.getCurrentUser&access_token=$access_token");
		$response = json_decode($r);
		$id = $response->uid;
		$name = SLgenerateName(array($response->last_name . " " . $response->first_name));
		$_SESSION['sl_token']=$access_token;
		return array(
			"id" => $id,
			"service" => "odnoklassniki.ru",
			"profile" => "$id@odnoklassniki.ru",
			"name" => $name,
			"email" => "",
			"realname" => $response->last_name . " " . $response->first_name,
		);
	}

	public static function check( $id ) {
	    $access_token = $_SESSION['sl_token'];
		global $wgOdnoklassnikiSecret, $wgOdnoklassnikiAppId, $wgOdnoklassnikiPublic;
		$sig = md5("application_key=$wgOdnoklassnikiPublic" . "client_id=$wgOdnoklassnikiAppId" . "method=users.getCurrentUser" . md5($access_token . $wgOdnoklassnikiSecret));
		$r = SLgetContents("http://api.odnoklassniki.ru/fb.do?application_key=$wgOdnoklassnikiPublic&sig=$sig&client_id=$wgOdnoklassnikiAppId&method=users.getCurrentUser&access_token=$access_token");
		$response = json_decode($r);
		if (!isset($response->uid) || $response->uid != $id) return false;
		else return array(
			"id" => $id,
			"service" => "odnoklassniki.ru",
			"profile" => "$id@odnoklassniki.ru",
			"realname" => $response->last_name . " " . $response->first_name,
			"access_token" => $access_token
		);
	}
	
	public static function loginUrl( ) {
		global $wgOdnoklassnikiAppId;
		return "http://www.odnoklassniki.ru/oauth/authorize?client_id=$wgOdnoklassnikiAppId&display=page&response_type=code&redirect_uri=" . urlencode(SpecialPage::getTitleFor('SocialLogin')->getCanonicalURL() . "?action=login&service=odnoklassniki.ru");
	}
}