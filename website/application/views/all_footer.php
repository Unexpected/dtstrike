				</section>
			</div>
<?php if (!isset($no_menu)) { ?>
			<?php include 'menu.php'; ?>
<?php } ?>
		</div>
	</div>
<?php if (!isset($no_footer)) { ?>
	<footer class="navbar navbar-fixed-bottom nav-hide-on-mobile">
		<div class="navbar-inner">
			<div class="pull-left" style="padding-top: 8px;">
				En cas de problème, n'hésitez pas à <a href="<?php echo base_url("forum") ?>">nous contacter</a> !
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
<?php } ?>
<?php if (isset($no_header)) { ?>
<script type="text/javascript">
$('body').css('padding', '0');
if ($('#container').width() > $('.container').width()) {
	$('.container').css('width', ($('#container').width()+40)+'px');
}
$('.span9').css('width', 'auto');
$('.span9').css('float', 'none');
</script>
<?php } ?>
</body>
</html>