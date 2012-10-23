<?php
class facebook_com implements SocialLoginPlugin {
	public static function login( $code ) {
		global $wgFacebookSecret, $wgFacebookAppId;
		$host = $_SERVER["SERVER_NAME"];
		$r = SLgetContents("https://graph.facebook.com/oauth/access_token?redirect_uri=http://$host/Special:SocialLogin?service=facebook.com&client_id=$wgFacebookAppId&client_secret=$wgFacebookSecret&code=$code");
		parse_str($r, $response);
		if (!isset($response['access_token'])) return false;
		$access_token = $response['access_token'];
		$r = SLgetContents("https://graph.facebook.com/me?fields=id,first_name,last_name,username,gender,birthday,email,picture&access_token=$access_token");
		$response = json_decode($r);
		$id = $response->id;
		$e = explode("@", $response->email);
		$e = $e[0];
		$name = SocialLogin::generateName(array($response->username, $e, $response->last_name . " " . $response->first_name));
		$_SESSION['sl_token']=$access_token;
		return array(
			"id" => $id,
			"service" => "facebook.com",
			"profile" => "$id@facebook.com",
			"name" => $name,
			"email" => $response->email,
			"realname" => $response->last_name . " " . $response->first_name,
		);

	}

	public static function check( $id ) {
	    $access_token=$_SESSION['sl_token'];
		$r = SLgetContents("https://graph.facebook.com/me?fields=id,first_name,last_name&access_token=$access_token");
		$response = json_decode($r);
		if (!isset($response->id) || $response->id != $id) return false;
		else return array(
			"id" => $id,
			"service" => "facebook.com",
			"profile" => "$id@facebook.com",
			"realname" => $response->last_name . " " . $response->first_name,
			"access_token" => $access_token
		);
	}
	
	public static function loginUrl( ) {
		global $wgFacebookAppId;
		$host = $_SERVER["SERVER_NAME"];
		return "https://www.facebook.com/dialog/oauth?client_id=$wgFacebookAppId&scope=email&display=popup&redirect_uri=http://$host/Special:SocialLogin?service=facebook.com&response_type=code";
	}
}