<?php
/**
 * HTML template for login register form below SocialLogin buttons
 */
class SocialLoginLoginRegisterTpl extends QuickTemplate {
	public function execute() {
?>
		<style>
			td input {width: 100%}
		</style>
		<div style="float: left; width: 49%">
			<?php echo wfMsg('sl-no-account')?>
			<form method="post">
				<input type="hidden" name="action" value="signup" />
				<table style="width: 100%">
					<tr>
						<td style="width: 30%"><?php echo wfMsg('sl-login')?>:</td>
						<td><input type="text" name="name" /></td>
					</tr>
					<tr>
						<td><?php echo wfMsg('sl-realname')?>:</td>
						<td><input type="text" name="realname" /></td>
					</tr>
					<tr>
						<td><?php echo wfMsg('sl-email')?>:</td>
						<td><input type="text" name="email" /></td>
					</tr>
					<tr>
						<td><?php echo wfMsg('sl-password')?>:</td>
						<td><input type="password" name="pass" /></td>
					</tr>
					<tr>
						<td><?php echo wfMsg('sl-password-confirm')?>:</td>
						<td><input type="password" name="pass_confirm" /></td>
					</tr>
				</table>	
				<input type="submit" value="<?php echo wfMsg('sl-register')?>" />
			</form>
		</div>
		<div style="float: right; width: 49%">
			<?php echo wfMsg('sl-account')?>
			<form method="post">
				<input type="hidden" name="action" value="signin" />
				<table style="width: 100%">
					<tr>
						<td style="width: 30%"><?php echo wfMsg('sl-login')?>:</td>
						<td><input type="text" name="name" /></td>
					</tr>
					<tr>
						<td><?php echo wfMsg('sl-password')?>:</td>
						<td><input type="password" name="pass" /></td>
					</tr>
				</table>	
				<input type="submit" value="<?php echo wfMsg('sl-enter')?>" />
			</form>
		</div>
		<div style="clear: both"></div>
<?php
	}
}