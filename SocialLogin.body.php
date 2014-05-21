<?php
/**
 * Interface to implement SocialLogin plugins
 **/
interface SocialLoginPlugin {
	public static function login( $code );
	public static function check( $id );
	public static function loginUrl( );
}

// If session is no started yet then we can't correctly map Login / Register link, so start the session
global $wgSessionStarted, $wgOut, $wgRequest;
if (!$wgSessionStarted) {
	wfSetupSession();
	$wgOut->redirect($wgRequest->getFullRequestURL());
}

function SLgetContents( $url, $data = false, $headers = false ) {
 	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, $headers);
	curl_setopt($ch, CURLOPT_POST, $data?1:0);
	if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = curl_exec($ch);
	curl_close($ch);
	return $output;
}

function SLgenerateName( $names ) {
	$possibles = array();
	foreach ($names as $name) {
		$name = SocialLogin::processName($name);
		if (!$name) continue;
		$possibles[] = $name;
		if (!SocialLogin::userExist($name)) return $name;
	}
	foreach ($possibles as $possible) {
		$i = 1;
		while(SocialLogin::userExist($possible . "_$i")) $i++;
		return $possible . "_$i";
	}
	
	$dbr = wfGetDB(DB_MASTER);
	$res = $dbr->selectRow('sociallogin', array('MAX(id) as max'), array(), __METHOD__);
	$maxId = $res->max?$res->max+1:1;
	$result = "user_$maxId";
	return $result;
}

class SocialLogin extends SpecialPage {
	var $loginForm;

	function __construct( ) {
		global $wgHooks;
		$this->loginForm = new LoginForm;
		parent::__construct('SocialLogin');
		$wgHooks['UserLoadAfterLoadFromSession'][] = $this;
		wfLoadExtensionMessages('SocialLogin');
	}

	// Replace login / register href with Special:SocialLogin
	static function onPersonalUrls( &$personal_urls ) {
		global $wgSocialLoginOverrideUrls, $wgOut, $wgRequest;
		// If we want to map Login / Register link to Special:SocialLogin
		if ($wgSocialLoginOverrideUrls) {
			// Replace personal_url with Special:SocialLogin
			if (isset($personal_urls['anonlogin'])) {
				$personal_urls['anonlogin']['href'] = SpecialPage::getTitleFor('SocialLogin')->getLocalURL() . '?returnto=' . ($wgRequest->getText('returnto')?$wgRequest->getText('returnto'):$wgOut->getTitle());
			}
		}
		return true;
	}

	static function getContents( $url, $data = false ) {
	 	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, $data?1:0);
		if ($data) curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;
	}

	static function translit( $str ) 
	{
		$tr = array(
			"А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
			"Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
			"Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
			"О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
			"У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
			"Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
			"Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
			"в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
			"з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
			"м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
			"с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
			"ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
			"ы"=>"yi","ь"=>"","э"=>"e","ю"=>"yu","я"=>"ya"
		);
		return strtr($str,$tr);
	}
	
	static function processName( $name ) {
		global $wgContLang;
		$name = $wgContLang->ucfirst($name);
		$name = SocialLogin::translit($name);
		$name = strtolower($name);
		$name = str_replace(" ", "_", $name);
		$name = preg_replace("/[^a-zA-Z0-9_]/i", "", $name);
		$name = ltrim($name, "0123456789_");
		return $name;
	}
	
	static function userExist( $name ) {
		$user = User::newFromName($name);
		return $user && $user->getId();
	}

	function emailExist( $email ) {
		$dbr = wfGetDB(DB_MASTER);
		$res = $dbr->selectRow('user', array('user_id'), array('user_email' => $email), __METHOD__);
		return isset($res->user_id) && $res->user_id;
	}

	function onUserLoadAfterLoadFromSession( $user ) {
		global $wgRequest, $wgOut, $wgContLang, $wgSocialLoginServices, $wgSocialLoginAddForms, $wgSessionStarted;
		$action = $wgRequest->getText('action', 'auth');
		switch ($action) {
			case "auth":
				$wgOut->includeJQuery();
				$accounts = array();
				if ($user->isLoggedIn()) {
					foreach ($wgSocialLoginServices as $key => $name) {
						$accounts[$key] = array();
						$dbr = wfGetDB(DB_MASTER);
						$res = $dbr->select('sociallogin', array('profile', 'full_name'), array('user_id' => $user->getId()));
						foreach ($res as $row) {
							$service = explode('@', $row->profile);
							$service = $service[1];
							$accounts[$service][$row->profile] = $row->full_name;
						}
					}
				}
				$buttonsTpl = new SocialLoginButtonsTpl();
				$buttonsTpl->set('services', $wgSocialLoginServices);
				$buttonsTpl->set('accounts', $accounts);
				$buttonsTpl->setRef('user', $user);
				$wgOut->addTemplate($buttonsTpl);
				$wgOut->addHeadItem('Authorization', "<script type='text/javascript'>
					function unlink(profile) {
						$.ajax({
							url: '/Special:SocialLogin',
							data: {action: 'unlink', profile: profile},
							success: function(response) {
								if (/.*yes.*/.test(response)) $('#' + profile.replace('@', '_').replace('.', '_')).remove();
								else alert('Не удалось отсоединить профиль социальной сети.');
							}
						});
					}
				</script>");
				$scripts = "$(function() {";
				foreach ($wgSocialLoginServices as $key => $name) {
					$s = explode('.', $key);
					$s = $s[0];
					$scripts .= "
						$('.$s').click(function() {
							document.location.href = '" . call_user_func(array(str_replace(".", "_", $key), "loginUrl")) . "';
						});";
				}
				$scripts .= "});";
				$wgOut->addHeadItem('Login scripts', "<script type='text/javascript'>$scripts</script>");
				if ($wgSocialLoginAddForms && !$user->isLoggedIn()) {
					$loginRegisterTpl = new SocialLoginLoginRegisterTpl();
					$wgOut->addTemplate($loginRegisterTpl);
				}
				break;
			case "signin":
                sleep(1); // to prevent posible ddos
				$name = $wgContLang->ucfirst($wgRequest->getText('name'));
				$pass = $wgRequest->getText('pass');
				$error = "";
				if (!User::isValidUserName($name)) $error .= "<li>" . wfMsg('sl-invalid-name', $name) . "</li>";
				if (!SocialLogin::userExist($name)) $error .= "<li>" . wfMsg('sl-user-not-exist', $name) . "</li>";
				$newUser = User::newFromName($name);
				if (!$newUser->isValidPassword($pass)) $error .= "<li>" . wfMsg('sl-invalid-password') . "</li>";
				if ($error) {
					$wgOut->addHTML("<ul class='error'>$error</ul>");
				} else {
					$user->setId($newUser->getId());
					$user->loadFromId();
					$user->setCookies();
					$user->saveSettings();
					$this->loginForm->successfulLogin();
				}
				break;
			case "signup":
                sleep(1); // to prevent posible ddos
				$name = $wgContLang->ucfirst($wgRequest->getText('name'));
				$realname = $wgRequest->getText('realname');
				$email = $wgRequest->getText('email');
				$pass1 = $wgRequest->getText('pass');
				$pass2 = $wgRequest->getText('pass_confirm');
				$error = "";
				if (!User::isValidUserName($name)) $error .= "<li>" . wfMsg('sl-invalid-name', $name) . "</li>";
				if (SocialLogin::userExist($name)) $error .= "<li>" . wfMsg('sl-user-exist', $name) . "</li>";
				if (!User::isValidEmailAddr($email)) $error .= "<li>" . wfMsg('sl-invalid-email', $email) . "</li>";
				if ($this->emailExist($email)) $error .= "<li>" . wfMsg('sl-email-exist', $name) . "</li>";
				//Note: Add password validation
				if (!$pass1) $error .= "<li>" . wfMsg('sl-invalid-password') . "</li>";
				if ($pass1 != $pass2) $error .= "<li>" . wfMsg('sl-passwords-not-equal') . "</li>";
				if ($error) {
					$wgOut->addHTML("<ul class='error'>$error</ul>");
				} else {
					$newUser = User::createNew($name, array(
						'email' => $email,
						'real_name' => $realname,
						'token' => User::generateToken()
					));
					$newUser->setInternalPassword($pass1);
					$newUser->saveSettings();
					$user->setId($newUser->getId());
					$user->loadFromId();
					$user->setCookies();
					$user->saveSettings();
					$this->loginForm->successfulLogin();
				}
				break;
			case "login":
				$service = $wgRequest->getText('service');
				$code = $wgRequest->getText('code');
				$auth = call_user_func(array(str_replace(".", "_", $service), "login"), $code);
				if (!$auth) { $wgOut->addHTML(wfMsg('sl-hacking')); return true; }
				$dbr = wfGetDB(DB_MASTER);
				$res = $dbr->selectRow('sociallogin', array('user_id'), array('profile' => $auth['profile']), __METHOD__);
				$user_id = $res?($res->user_id?$res->user_id:false):false;
				if ($user_id) {
					$user->setID($user_id);
					$user->loadFromId();
					$user->setCookies();
					$user->saveSettings();
					$this->loginForm->successfulLogin();
				} else {
					if ($user->isLoggedIn()) {
						$dbr = wfGetDB(DB_MASTER);
						$res = $dbr->insert('sociallogin', array(
							"user_id" => $user->getId(),
							"profile" => $auth["profile"],
							"full_name" => $auth["realname"]
						));
						//$wgOut->addHTML(wfMsg('sl-account-connected', $auth["realname"], $wgSocialLoginServices[$service]));
						$_SESSION['sl_msg'] = wfMsg('sl-account-connected', $auth["realname"], $wgSocialLoginServices[$service]);
						wfRunHooks('UserLoginComplete', array(&$user, $this));
						$wgOut->redirect(SpecialPage::getTitleFor('SocialLogin')->getLocalUrl());
					} else {
						$signFormsTpl = new SocialLoginSignFormsTpl();
						$signFormsTpl->set('auth', $auth);
						$wgOut->addTemplate($signFormsTpl);
						//$wgOut->addHTML(wfMsg('sl-sign-forms', $auth["service"], $auth["id"], $auth["name"], $auth["realname"], $auth["email"]));
					}
				}
				break;
			case "create":
				$service = $wgRequest->getText('service');
				$id = $wgRequest->getText('id');
				$name = $wgContLang->ucfirst($wgRequest->getText('name'));
				$realname = $wgRequest->getText('realname');
				$email = $wgRequest->getText('email');
				$pass1 = $wgRequest->getText('pass');
				$pass2 = $wgRequest->getText('pass_confirm');
				$auth = call_user_func(array(str_replace(".", "_", $service), "check"), $id);
				if (!$auth) { $wgOut->addHTML(wfMsg('sl-hacking')); return true; }
				else {
					$error = "";
					if (!$service) $error .= "<li>" . wfMsg('sl-missing-param', 'service') . "</li>";
					if (!$id) $error .= "<li>" . wfMsg('sl-missing-param', 'id') . "</li>";
					if (!User::isValidUserName($name)) $error .= "<li>" . wfMsg('sl-invalid-name', $name) . "</li>";
					if (SocialLogin::userExist($name)) $error .= "<li>" . wfMsg('sl-user-exist', $name) . "</li>";
					if (!User::isValidEmailAddr($email)) $error .= "<li>" . wfMsg('sl-invalid-email', $email) . "</li>";
					if ($this->emailExist($email)) $error .= "<li>" . wfMsg('sl-email-exist', $name) . "</li>";
					//Note: Add password validation
					if (!$pass1) $error .= "<li>" . wfMsg('sl-invalid-password') . "</li>";
					if ($pass1 != $pass2) $error .= "<li>" . wfMsg('sl-passwords-not-equal') . "</li>";
					if ($error) {
						$wgOut->addHTML("<ul class='error'>$error</ul>");
						$signFormsTpl = new SocialLoginSignFormsTpl();
						$signFormsTpl->set('auth', array(
							"service" => $service,
							"id" => $id,
							"name" => $name,
							"realname" => $realname,
							"email" => $email
						));
						$wgOut->addTemplate($signFormsTpl);
						//$wgOut->addHTML(wfMsg('sl-sign-forms', $access_token, $service, $id, $name, $realname, $email));
					} else {
						$newUser = User::createNew($name, array(
							'email' => $email,
							'real_name' => $realname,
							'token' => User::generateToken()
						));
						$newUser->setInternalPassword($pass1);
						$newUser->saveSettings();
						$user->setId($newUser->getId());
						$user->loadFromId();
						$user->setCookies();
						$user->saveSettings();
						wfRunHooks('UserLoginComplete', array(&$user, $this));
						$dbr = wfGetDB(DB_MASTER);
						$res = $dbr->insert('sociallogin', array(
							"user_id" => $newUser->getId(),
							"profile" => $auth["profile"],
							"full_name" => $auth["realname"]
						));
						$this->loginForm->successfulLogin();
					}
				}
				break;
			case "connect":
				$service = $wgRequest->getText('service');
				$id = $wgRequest->getText('id');
				$name = $wgContLang->ucfirst($wgRequest->getText('name'));
				$pass = $wgRequest->getText('pass');
				$auth = call_user_func(array(str_replace(".", "_", $service), "check"), $id );
				if (!$auth) { $wgOut->addHTML(wfMsg('sl-hacking')); return true; }
				else {
					$error = "";
					if (!$service) $error .= "<li>" . wfMsg('sl-missing-param', 'service') . "</li>";
					if (!$id) $error .= "<li>" . wfMsg('sl-missing-param', 'id') . "</li>";
					if (!User::isValidUserName($name)) $error .= "<li>" . wfMsg('sl-invalid-name', $name) . "</li>";
					if (!SocialLogin::userExist($name)) $error .= "<li>" . wfMsg('sl-user-not-exist', $name) . "</li>";
					$newUser = User::newFromName($name);
					if (!$newUser->isValidPassword($pass)) $error .= "<li>" . wfMsg('sl-invalid-password') . "</li>";
					if ($error) {
						$wgOut->addHTML("<ul class='error'>$error</ul>");
						$signFormsTpl = new SocialLoginSignFormsTpl();
						$signFormsTpl->set('auth', array(
							"service" => $service,
							"id" => $id,
							"name" => $name,
							"realname" => '',
							"email" => ''
						));
						$wgOut->addTemplate($signFormsTpl);
						//$wgOut->addHTML(wfMsg('sl-sign-forms', $access_token, $service, $id, $name, '', ''));
					} else {
						$user->setId($newUser->getId());
						$user->loadFromId();
						$user->setCookies();
						$user->saveSettings();
						$dbr = wfGetDB(DB_MASTER);
						$res = $dbr->insert('sociallogin', array(
							"user_id" => $newUser->getId(),
							"profile" => $auth["profile"],
							"full_name" => $auth["realname"]
						));
						$_SESSION['sl_msg'] = wfMsg('sl-account-connected', $auth["realname"], $wgSocialLoginServices[$service]);
						wfRunHooks('UserLoginComplete', array(&$user, $this));
						$wgOut->redirect(SpecialPage::getTitleFor('SocialLogin')->getLocalUrl());
						//$wgOut->addHTML(wfMsg('sl-account-connected', $auth["realname"], $wgSocialLoginServices[$service]));
					}
				}
				break;
			case 'unlink':
				if (!$user->isLoggedIn()) exit('no');
				else {
					$profile = $wgRequest->getText('profile');
					$dbr = wfGetDB(DB_MASTER);
					$res = $dbr->selectRow('sociallogin', array('user_id'), array('profile' => $profile), __METHOD__);
					if ($res && $res->user_id && $user->getId() == $res->user_id) {
						$dbr = wfGetDB(DB_MASTER);
						$res = $dbr->delete('sociallogin', array('profile' => $profile));
						$dbr->commit();
						exit('yes');
					} else exit('no');
				}
				break;
		}
		return true;
	}

	function execute( $par ) {
		global $wgRequest, $wgOut, $wgUser, $wgScriptPath;
		$this->loginForm->load();
		if (isset($_SESSION['sl_msg'])) {
			$wgOut->addHTML($_SESSION['sl_msg']);
			unset($_SESSION['sl_msg']);
		}
		$wgOut->addHeadItem('SocialLogin buttons styles', "<link type='text/css' href='$wgScriptPath/extensions/SocialLogin/css/style.css' rel='stylesheet' />");
		$this->setHeaders();
	}
}
