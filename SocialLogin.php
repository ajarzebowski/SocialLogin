<?php
if (!defined('MEDIAWIKI')) die('Not an entry point.');
$wgExtensionCredits['specialpage'][] = array(
        'name' => 'SocialLogin',
        'author' => 'Luft-on',
        'url' => 'http://www.mediawiki.org/wiki/Extension:SocialLogin',
        'descriptionmsg' => 'sl-desc',
        'version' => '0.9.8',
);
 
$dir = dirname(__FILE__) . '/';
 
global $wgSocialLoginServices;

$wgAutoloadClasses['SocialLogin'] = $dir . 'SocialLogin.body.php'; # Попросите MediaWiki загрузить тело основного файла.
foreach ($wgSocialLoginServices as $key => $value) {
	$name = str_replace('.', '_', $key);
	$wgAutoloadClasses[$name] = $dir . "/plugins/$key.php";
}
$wgHooks['PersonalUrls'][] = 'SocialLogin::onPersonalUrls';
$wgExtensionMessagesFiles['sociallogin'] = $dir . 'SocialLogin.i18n.php';
$wgExtensionAliasesFiles['sociallogin'] = $dir . 'SocialLogin.alias.php';
$wgSpecialPages['sociallogin'] = 'SocialLogin';

# Schema updates for update.php
$wgHooks['LoadExtensionSchemaUpdates'][] = 'socialLoginUpdate';
function socialLoginUpdate( DatabaseUpdater $updater ) {
        $updater->addExtensionTable('sociallogin', dirname(__FILE__) . '/SocialLogin.sql'); # works just since 1.18
        return true;
}
