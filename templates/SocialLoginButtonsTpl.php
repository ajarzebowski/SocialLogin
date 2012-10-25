<?php
/**
 * HTML template for buttons on Special:SocialLogin page
 * $this->data['user'] - User class reference;
 * $this->data['services'] - global array wgSocialLoginServices;
 * $this->data['accounts'] - key-value array, keys - are service names, values - are key-value arrays, keys - are profile string, values - are account full_name;
 */
class SocialLoginButtonsTpl extends QuickTemplate {
	public function execute() {
		$w = 100 / count($this->data['services']);
?>
		<table style='width: 100%'>
			<tr>
<?php
		foreach ($this->data['services'] as $key => $name) {
			$n = explode('.', $key);
?>
				<td style='width: <?php echo $w?>%'><div style='width: 95%' class='slbutton <?php echo $n[0]?>'><?php echo $name?></div></td>
<?php
		}
?>
			</tr>
<?php
		if ($this->data['user']->isLoggedIn()) {
?>
			<tr>
<?php
			foreach ($this->data['services'] as $key => $name) {
?>
				<td>
<?php
				foreach ($this->data['accounts'][$key] as $profile => $full_name) {
?>
					<p id='<?php echo preg_replace("/[@\.]/i", "_", $profile)?>'><?php echo $full_name?> <a href="javascript:unlink('<?php echo $profile?>')">(<?php echo wfMsg('sl-unlink')?>)</a></p>
<?php
				}
?>
				</td>
<?php
			}
?>
			</tr>
<?php
		}
?>
		</table>
<?php
	}
}