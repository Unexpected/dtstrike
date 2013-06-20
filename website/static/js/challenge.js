$(document).ready(function() {
	initCssSelector();
	initCanvas();
});

function initCssSelector() {
	$('#cssSelector').change(function() {
		var oldSkinLink = document.getElementById('skin');
		var newSkinLink = oldSkinLink.cloneNode(false);
		$(newSkinLink).attr('href', $('#cssSelector').val());
		oldSkinLink.parentNode.replaceChild(newSkinLink, oldSkinLink);
	});	
}

function initCanvas() {
	try {
		// Check if canvas is supported
		var canvas = document.getElementById('logo');
		var context = canvas.getContext('2d');
		
		frontImageObj = new Image();
		frontImageObj.src = 'static/images/logo/logo_six_front.png';

		for (var i=0; i<5; i++) {
			backImageObj[i] = new Image();
			backImageObj[i].src = 'static/images/logo/logo_six_back_'+(i+1)+'.png';
		}
		
		animate();
	} catch (e) {
		// old browsers support
		document.getElementById('logo_fixed').style.display = 'block';
	}
}

window.requestAnimFrame = (function(callback) {
	return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame ||
		function(callback) {
			window.setTimeout(callback, 1000 / 60);
		};
})();

var frontImageObj;
var backImageObj = [];
var nbFrames = [1200, 1800, 1400, 2000, 1600];
var lastAngle = [1, 1, 1, 1, 1];
function animate() {
	var canvas = document.getElementById('logo');
	var context = canvas.getContext('2d');
	
	// Clear
	context.clearRect(0, 0, 100, 100);
	
	// Translate & Rotate each bg image
	for (var i=0; i<5; i++) {
		context.save();
		
		context.translate(50, 50);
		var rotateAngle = lastAngle[i] * Math.PI / nbFrames[i];
		lastAngle[i] += 1;
		if (lastAngle[i] == 2*nbFrames[i]) {
			lastAngle[i] = 1;
		}
		context.rotate(rotateAngle);
		
		// draw image
		context.drawImage(backImageObj[i], -50, -50);
		
		context.restore();
	}
	
	// Draw Front image
	context.drawImage(frontImageObj, 0, 0);

	// request new frame
	requestAnimFrame(animate);
}