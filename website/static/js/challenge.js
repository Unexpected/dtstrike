$(document).ready(function() {
	initCssSelector();
	SixLogo.initCanvas();
});

function initCssSelector() {
	$('#cssSelector').change(function() {
		changeSkin($('#cssSelector').val());
		
		// Store value
		localStorage.setItem("cssSelector", $('#cssSelector').val());
	});
	
	// Check for previous choice
	var val = localStorage.getItem("cssSelector");
	if (val != null) {
		changeSkin(val);
	}
}
function changeSkin(val) {
	var oldSkinLink = document.getElementById('skin');
	var newSkinLink = oldSkinLink.cloneNode(false);
	$(newSkinLink).attr('href', val);
	oldSkinLink.parentNode.replaceChild(newSkinLink, oldSkinLink);
}