$(document).ready(function() {
	initCssSelector();
	SixLogo.initCanvas();
});

function initCssSelector() {
	$('#cssSelector').change(function() {
		var oldSkinLink = document.getElementById('skin');
		var newSkinLink = oldSkinLink.cloneNode(false);
		$(newSkinLink).attr('href', $('#cssSelector').val());
		oldSkinLink.parentNode.replaceChild(newSkinLink, oldSkinLink);
	});	
}