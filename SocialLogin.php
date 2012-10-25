<?php
if (!defined('MEDIAWIKI')) die('Not an entry point.');
$wgExtensionCredits['specialpage'][] = array(
        'name' => 'SocialLogin',
        'author' => 'Luft-on',
        'url' => 'http://www.mediawiki.org/wiki/Extension:SocialLogin',
        'descriptionmsg' => 'sl-desc',
        'version' => '0.9.9',
);
 
$dir = dirname(__FILE__) . '/';
 
global $wgSocialLoginServices;

// Main SpecialPage class
$wgAutoloadClasses['SocialLogin'] = $dir . 'SocialLogin.body.php';
// Plugins classes
foreach ($wgSocialLoginServices as $key => $value) {
	$name = str_replace('.', '_', $key);
	$wgAutoloadClasses[$name] = $dir . "/plugins/$key.php";
}
// Buttons template class
$wgAutoloadClasses['SocialLoginButtonsTpl'] = $dir . '/templates/SocialLoginButtonsTpl.php';
// Signin/signup forms to connect accounts template class
$wgAutoloadClasses['SocialLoginSignFormsTpl'] = $dir . '/templates/SocialLoginSignFormsTpl.php';
// Signin/signup forms under SocialLogin page template class
$wgAutoloadClasses['SocialLoginLoginRegisterTpl'] = $dir . '/templates/SocialLoginLoginRegisterTpl.php';
// Handle replacement of Login / Register link
$wgHooks['PersonalUrls'][] = 'SocialLogin::onPersonalUrls';
// Add i18n messages file
$wgExtensionMessagesFiles['SocialLogin'] = $dir . 'SocialLogin.i18n.php';
// Add aliases file
$wgExtensionAliasesFiles['SocialLogin'] = $dir . 'SocialLogin.alias.php';
// Register special page
$wgSpecialPages['SocialLogin'] = 'SocialLogin';
$wgSpecialPageGroups['SocialLogin'] = 'login';

# Schema updates for update.php
$wgHooks['LoadExtensionSchemaUpdates'][] = 'socialLoginUpdate';
function socialLoginUpdate( DatabaseUpdater $updater ) {
        $updater->addExtensionTable('sociallogin', dirname(__FILE__) . '/SocialLogin.sql'); # works just since 1.18
        return true;
}
