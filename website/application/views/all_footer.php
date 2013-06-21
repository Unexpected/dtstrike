				</section>
			</div>
			<?php include 'menu.php'; ?>
		</div>
	</div>
	<footer class="navbar navbar-fixed-bottom">
		<div class="navbar-inner">
			<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds</p>
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