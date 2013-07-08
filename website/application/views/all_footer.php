				</section>
			</div>
			<?php include 'menu.php'; ?>
		</div>
	</div>
	<footer class="navbar navbar-fixed-bottom">
		<div class="navbar-inner">
			<div class="pull-left" style="padding-top: 8px;">
				En cas de problème, n'hésitez pas à <a id="contact" href="#" onclick="contactUs();">nous contacter</a> !
				<script type="text/javascript">
					function contactUs() {
						window.location=base64.decode('bWFpbHRvOnNlYmFzdGllbi5zY2htaXR0QGNnaS5jb207ZnJhbmNpcy5iZWxsYW5nZXJAY2dpLmNvbT9zdWJqZWN0PVtTaXhDaGFsbGVuZ2VdIENvbnRhY3Q=');
						return false;
					}
				</script>
			</div>
		
			<form class="navbar-search pull-right" action="">
				<select id="cssSelector" name="cssSelector" title="Choose your side">
					<option value="Choose your side" selected="selected" disabled="disabled">Choose your side</option>
					<option value="<?php echo base_url("static/css/styles-light.css") ?>">Light Side</option>
					<option value="<?php echo base_url("static/css/styles-dark.css") ?>">Dark Side</option>
				</select>
			</form>
		</div>
	</footer>
</body>
</html>