<?php
/**
 * HTML template for forms showing when connecting social media account with local MW account
 * $this->data['auth'] - array of auth data: service, name, realname, email;
 */
class SocialLoginSignFormsTpl extends QuickTemplate {
	public function execute() {
?>
		<style>
			td input {width: 100%}
		</style>
		<div style="float: left; width: 49%">
			<?php echo wfMsg('sl-signforms-no-account')?>
			<form method="post">
				<input type="hidden" name="service" value="<?php echo $this->data['auth']['service']?>" />
				<input type="hidden" name="id" value="<?php echo $this->data['auth']['id']?>" />
				<input type="hidden" name="action" value="create" />
				<table style="width: 100%">
					<tr>
						<td style="width: 30%"><?php echo wfMsg('sl-login')?>:</td>
						<td><input autocomplete="off" type="text" name="name" value="<?php echo $this->data['auth']['name']?>" /></td>
					</tr>
					<tr>
						<td><?php echo wfMsg('sl-realname')?>:</td>
						<td><input autocomplete="off" type="text" name="realname" value="<?php echo $this->data['auth']['realname']?>" /></td>
					</tr>
					<tr>
						<td><?php echo wfMsg('sl-email')?>:</td>
						<td><input autocomplete="off" type="text" name="email" value="<?php echo $this->data['auth']['email']?>" /></td>
					</tr>
					<tr>
						<td><?php echo wfMsg('sl-password')?>:</td>
						<td><input autocomplete="off" type="password" name="pass" /></td>
					</tr>
					<tr>
						<td><?php echo wfMsg('sl-password-confirm')?>:</td>
						<td><input autocomplete="off" type="password" name="pass_confirm" /></td>
					</tr>
				</table>	
				<input type="submit" value="<?php echo wfMsg('sl-create')?>" />
			</form>
		</div>
		<div style="float: right; width: 49%">
			<?php echo wfMsg('sl-signforms-account')?>
			<form method="post">
				<input type="hidden" name="service" value="<?php echo $this->data['auth']['service']?>" />
				<input type="hidden" name="id" value="<?php echo $this->data['auth']['id']?>" />
				<input type="hidden" name="action" value="connect" />
				<table style="width: 100%">
					<tr>
						<td style="width: 30%"><?php echo wfMsg('sl-login')?>:</td>
						<td><input autocomplete="off" type="text" name="name" value="<?php echo $this->data['auth']['name']?>" /></td>
					</tr>
					<tr>
						<td><?php echo wfMsg('sl-password')?>:</td>
						<td><input autocomplete="off" type="password" name="pass" /></td>
					</tr>
				</table>	
				<input type="submit" value="<?php echo wfMsg('sl-connect')?>" />
			</form>
		</div>
		<div style="clear: both"></div>
<?php
	}
}