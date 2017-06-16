# SocialLogin

This extension adds a special page with the ability to connect an account with social networks accounts like Facebook, VKontakte, and Google with their respective APIs.

This mean you can easily login to your MediaWiki project once you have connected your existing or new account with your social media account. Afterwards, the client's application extension will ask you to connect your social media account with an existing or new wiki account.

There are a few interactions with social media plugins out of box: Facebook, Google, VKontakte, Yandex and Odnoklassniki. However, you can create your own.

## Contents
1. Features
2. Installation
    1. Configuration parameters
3. External Links

## Features
* Integration with Facebook, Google, Vk, Yandex and Odnoklassniki;
* Opportunity to connect different social media accounts with one account;
* Opportunity to connect few account of one social media with one account;
* Opportunity to unlink social media account at anytime;
* Opportunity to map Login / Register link to Special:SocialLogin;
* Easy extensioning.

## Installation

* Download and place the file(s) in a directory called SocialLogin in your extensions/ folder.
* Add the following code at the bottom of your LocalSettings.php after any plugin configuration:

```php
require_once "$IP/extensions/SocialLogin/SocialLogin.php";
```

* Configure as required. Configuration should go always before code above.

## Configuration parameters

```php
$wgSocialLoginServices
```

Associative array containing plugin => description structure. Every plugin key will be used to include SocialLoginPlugin`s from plugins folder and must match plugin filename, e.g. google.com, vk.com, odnoklassniki.ru. For example:

```php
$wgSocialLoginServices = array(
    'facebook.com' => 'Facebook',
    'google.com' => 'Google',
    'vk.com' => 'VKontakte',
    'odnoklassniki.ru' => 'Odnoklassniki',
    'yandex.ru' => 'Yandex'
);
```

```php
$wgFacebookSecret
```

facebook.com application secret.

```php
$wgFacebookAppId
```

facebook.com application ID.

```php
$wgGoogleSecret
```

google.com application secret.

```php
$wgGoogleAppId
```

google.com application ID.

```php
$wgSocialLoginOverrideUrls
```

If set to true, Login / Register link will be mapped to Special:SocialLogin.

```php
$wgSocialLoginAddForms
```

If set to true, two forms (login and register) will be added below SocialLogin page for anonymous user.

```php
$wgVkSecret
```

vk.com application secret.

```php
$wgVkAppId
```

vk.com application ID.


```php
$wgYandexSecret
```

yandex.ru application secret.

```php
$wgYandexAppId
```

yandex.ru application ID.

```php
$wgOdnoklassnikiPublic
```

odnoklassniki.ru application public.

```php
$wgOdnoklassnikiSecret
```

odnoklassniki.ru application private.

```php
$wgOdnoklassnikiAppId
```

odnoklassniki.ru application ID.

```php
$wg…Secret
```

Additional site application secret.

```php
$wg…AppId
```

Additional site application ID.: