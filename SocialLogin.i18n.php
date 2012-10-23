<?php
$messages = array();
$messages['en'] = array( 
	'sl' => 'Social Login',
	'sl-desc' => 'Adds ability to login with Social Networks like Facebook, VK, Google and more using OAuth 2.0 protocol.',
	'sl-unlink' => 'unlink',
	'sl-login-success' => 'Login success.',
	'sl-account-connected' => 'Your account was connected with «$1» account of $2.',
	'sl-sign-forms' =>	'<style>
							td input {width: 100%}
						</style>
						<div style="float: left; width: 49%">
							If you do not have account, just fill registration form below.
							<form method="post">
								<input type="hidden" name="service" value="$1" />
								<input type="hidden" name="id" value="$2" />
								<input type="hidden" name="action" value="create" />
								<table style="width: 100%">
									<tr>
										<td style="width: 30%">Login:</td>
										<td><input autocomplete="off" type="text" name="name" value="$3" /></td>
									</tr>
									<tr>
										<td>Real name:</td>
										<td><input autocomplete="off" type="text" name="realname" value="$4" /></td>
									</tr>
									<tr>
										<td>Email:</td>
										<td><input autocomplete="off" type="text" name="email" value="$5" /></td>
									</tr>
									<tr>
										<td>Password:</td>
										<td><input autocomplete="off" type="password" name="pass" /></td>
									</tr>
									<tr>
										<td>Confirm:</td>
										<td><input autocomplete="off" type="password" name="pass_confirm" /></td>
									</tr>
								</table>	
								<input type="submit" value="Create" />
							</form>
						</div>
						<div style="float: right; width: 49%">
							If already have account and you want to link it with selected service account, just fill login form below.
							<form method="post">
								<input type="hidden" name="service" value="$1" />
								<input type="hidden" name="id" value="$2" />
								<input type="hidden" name="action" value="connect" />
								<table style="width: 100%">
									<tr>
										<td style="width: 30%">Login:</td>
										<td><input autocomplete="off" type="text" name="name" value="$3" /></td>
									</tr>
									<tr>
										<td>Password:</td>
										<td><input autocomplete="off" type="password" name="pass" /></td>
									</tr>
								</table>	
								<input type="submit" value="Connect" />
							</form>
						</div>
						<div style="clear: both"></div>',
	'sl-login-register' =>	'<style>
							td input {width: 100%}
						</style>
						<div style="float: left; width: 49%">
							If you do not have account, just fill registration form below.
							<form method="post">
								<input type="hidden" name="action" value="signup" />
								<table style="width: 100%">
									<tr>
										<td style="width: 30%">Login:</td>
										<td><input type="text" name="name" /></td>
									</tr>
									<tr>
										<td>Real name:</td>
										<td><input type="text" name="realname" /></td>
									</tr>
									<tr>
										<td>Email:</td>
										<td><input type="text" name="email" /></td>
									</tr>
									<tr>
										<td>Password:</td>
										<td><input type="password" name="pass" /></td>
									</tr>
									<tr>
										<td>Confirm:</td>
										<td><input type="password" name="pass_confirm" /></td>
									</tr>
								</table>	
								<input type="submit" value="Register" />
							</form>
						</div>
						<div style="float: right; width: 49%">
							If already have account, just fill login form below.
							<form method="post">
								<input type="hidden" name="action" value="signin" />
								<table style="width: 100%">
									<tr>
										<td style="width: 30%">Login:</td>
										<td><input type="text" name="name" /></td>
									</tr>
									<tr>
										<td>Password:</td>
										<td><input type="password" name="pass" /></td>
									</tr>
								</table>	
								<input type="submit" value="Login" />
							</form>
						</div>
						<div style="clear: both"></div>',
	'sl-hacking' => 'Something went wrong or you are trying to pretend to be someone else.',
	'sl-invalid-password' => 'Incorrect password.',
	'sl-passwords-not-equal' => 'Passwords are not equal.',
	'sl-user-exist' => 'Login $1 already exists.',
	'sl-email-exist' => 'Email $1 already exists.',
	'sl-user-not-exist' => 'There is no $1 user.',
	'sl-invalid-name' => 'Incorrect login $1.',
	'sl-invalid-email' => 'Incorrect Email $1.',
	'sl-missing-param' => 'Hidden parameter $1 is missing. It is necessary to identify you.',
);

$messages['ru'] = array( 
	'sl' => 'Социальная авторизация',
	'sl-desc' => 'Добавляет возможность входа через социальные сети Facebook, ВКонтакте, Google и другие, используя протокол OAuth 2.0.',
	'sl-have-account' => 'Если у вас уже есть аккаунт на сайте и вы хотите объединить его с аккаунтом выбранной службы, то войдите, используя форму ниже.',
	'sl-havenot-account' => 'Если у вас ещё нет аккаунта на сайте, то заполните регистрационную форму ниже.',
	'sl-unlink' => 'отсоединить',
	'sl-login-success' => 'Вы успешно авторизировались.',
	'sl-account-connected' => 'Ваш аккаунт объединён с аккаунтом «$1» службы $2.',
	'sl-sign-forms' =>	'<style>
							td input {width: 100%}
						</style>
						<div style="float: left; width: 49%">
							Если у вас нет аккаунта, просто заполните предложенную регистрационную форму.
							<form method="post">
								<input type="hidden" name="service" value="$1" />
								<input type="hidden" name="id" value="$2" />
								<input type="hidden" name="action" value="create" />
								<table style="width: 100%">
									<tr>
										<td style="width: 30%">Логин:</td>
										<td><input autocomplete="off" type="text" name="name" value="$3" /></td>
									</tr>
									<tr>
										<td>Настоящее имя:</td>
										<td><input autocomplete="off" type="text" name="realname" value="$4" /></td>
									</tr>
									<tr>
										<td>Email:</td>
										<td><input autocomplete="off" type="text" name="email" value="$5" /></td>
									</tr>
									<tr>
										<td>Пароль:</td>
										<td><input autocomplete="off" type="password" name="pass" /></td>
									</tr>
									<tr>
										<td>Повторно:</td>
										<td><input autocomplete="off" type="password" name="pass_confirm" /></td>
									</tr>
								</table>	
								<input type="submit" value="Создать" />
							</form>
						</div>
						<div style="float: right; width: 49%">
							Если у вас уже есть аккаунт и вы хотите присоединить его к аккаунту выбранной службы, просто заполните предложенную форму входа.
							<form method="post">
								<input type="hidden" name="service" value="$1" />
								<input type="hidden" name="id" value="$2" />
								<input type="hidden" name="action" value="connect" />
								<table style="width: 100%">
									<tr>
										<td style="width: 30%">Логин:</td>
										<td><input autocomplete="off" type="text" name="name" value="$3" /></td>
									</tr>
									<tr>
										<td>Пароль:</td>
										<td><input autocomplete="off" type="password" name="pass" /></td>
									</tr>
								</table>	
								<input type="submit" value="Объединить" />
							</form>
						</div>
						<div style="clear: both"></div>',
	'sl-login-register' =>	'<style>
							td input {width: 100%}
						</style>
						<div style="float: left; width: 49%">
							Если у вас нет аккаунта, просто заполните предложенную регистрационную форму.
							<form method="post">
								<input type="hidden" name="action" value="signup" />
								<table style="width: 100%">
									<tr>
										<td style="width: 30%">Логин:</td>
										<td><input type="text" name="name" /></td>
									</tr>
									<tr>
										<td>Настоящее имя:</td>
										<td><input type="text" name="realname" /></td>
									</tr>
									<tr>
										<td>Email:</td>
										<td><input type="text" name="email" /></td>
									</tr>
									<tr>
										<td>Пароль:</td>
										<td><input type="password" name="pass" /></td>
									</tr>
									<tr>
										<td>Повторно:</td>
										<td><input type="password" name="pass_confirm" /></td>
									</tr>
								</table>	
								<input type="submit" value="Регистрация" />
							</form>
						</div>
						<div style="float: right; width: 49%">
							Если у вас уже есть аккаунт, просто заполните предложенную форму входа.
							<form method="post">
								<input type="hidden" name="action" value="signin" />
								<table style="width: 100%">
									<tr>
										<td style="width: 30%">Логин:</td>
										<td><input type="text" name="name" /></td>
									</tr>
									<tr>
										<td>Пароль:</td>
										<td><input type="password" name="pass" /></td>
									</tr>
								</table>	
								<input type="submit" value="Войти" />
							</form>
						</div>
						<div style="clear: both"></div>',
	'sl-hacking' => 'Что-то пошло не так или вы пытаетесь выдать себя за того, кем вы не являетесь.',
	'sl-invalid-password' => 'Неправильный пароль.',
	'sl-passwords-not-equal' => 'Пароли не совпадают.',
	'sl-user-exist' => 'Имя $1 уже используется.',
	'sl-email-exist' => 'Email $1 уже используется.',
	'sl-user-not-exist' => 'Пользователя $1 не существует.',
	'sl-invalid-name' => 'Некорректный логин $1.',
	'sl-invalid-email' => 'Некорректный Email $1.',
	'sl-missing-param' => 'Не указан скрытый параметр $1 необходимый для подтверждения вашей личности.',
);