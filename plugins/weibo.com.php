<?php
class weibo_com implements SocialLoginPlugin {
	public static function login( $code ) {
		global $wgWeiboSecret, $wgWeiboAppId;
		$r = SLgetContents("https://api.weibo.com/oauth2/access_token?grant_type=authorization_code&client_id=$wgWeiboAppId&client_secret=$wgWeiboSecret&code=$code&redirect_uri=" . urlencode(SpecialPage::getTitleFor('SocialLogin')->getCanonicalURL() . "?action=login&service=weibo.com"));
		$response = json_decode($r);
		if (!isset($response->access_token)) return false;
		$access_token = $response->access_token;
		$id = $response->uid;
		$r = SLgetContents("https://api.weibo.com/2/users/show.json?uid=$id&access_token=$access_token");
		$response = json_decode($r);
		$name = SLgenerateName(array($response->name, $id, ""));
		$_SESSION['sl_token']=$access_token;
		return array(
			"id" => $id,
			"service" => "weibo.com",
			"profile" => "$id@weibo.com",
			"name" => $name,
			"email" => $response->email,
			"realname" => $response->screen_name,
		);

	}

	public static function check( $id ) {
	    $access_token=$_SESSION['sl_token'];
		$r = SLgetContents("https://api.weibo.com/2/users/show.json?uid=$id&access_token=$access_token");
		$response = json_decode($r);
		if (!isset($response->id) || $response->id != $id) return false;
		else return array(
			"id" => $id,
			"service" => "weibo.com",
			"profile" => "$id@weibo.com",
			"realname" => $response->screen_name,
			"access_token" => $access_token
		);
	}
	
	public static function loginUrl( ) {
		global $wgWeiboAppId;
		return "https://api.weibo.com/oauth2/authorize?client_id=$wgWeiboAppId&response_type=code&redirect_uri=" . urlencode(SpecialPage::getTitleFor('SocialLogin')->getCanonicalURL() . "?action=login&service=weibo.com");
	}
}
