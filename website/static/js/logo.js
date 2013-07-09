/**
 * CIG - SiX logo animation
 * @author sebastien.schmitt@cgi.com
 */

/**
 * Create function to acces browser specific requestAnimationFrame function
 */
if (!window.requestAnimFrame) {
	window.requestAnimFrame = (function(callback) {
		return window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || window.oRequestAnimationFrame || window.msRequestAnimationFrame ||
			function(callback) {
				window.setTimeout(callback, 1000 / 60);
			};
	})();
}

var SixLogo = {
	canvas: null,
	ctx: null,
	
	frontImageObj: null,
	backImageObj: [],
	
	nbFrames: [1200, 1800, 1400, 2000, 1600],
	lastAngle: [1, 1, 1, 1, 1],
	
	initCanvas: function() {
		try {
			// Check if canvas is supported
			this.canvas = document.getElementById('logo');
	        this.ctx = this.canvas.getContext('2d');
			
			this.frontImageObj = new Image();
			this.frontImageObj.src = document.getElementById('logo_front').src;

			for (var i=0; i<5; i++) {
				this.backImageObj[i] = new Image();
				this.backImageObj[i].src = document.getElementById('logo_back_'+(i+1)).src;
			}

			var val = localStorage.getItem("SixLogo.lastAngle");
			if (val != null) {
				this.lastAngle = JSON.parse(val);
			}
			
			this.animate();
		} catch (e) {
			// old browsers support
			if (window.console) console.log(e.message);
			document.getElementById('logo_fixed').style.display = 'block';
		}
	},

	animate: function() {
		// Clear
		this.ctx.clearRect(0, 0, 100, 100);
		
		// Translate & Rotate each bg image
		for (var i=0; i<5; i++) {
			this.ctx.save();
			
			// Draw image
			this.ctx.translate(50, 50);
			var rotateAngle = this.lastAngle[i] * Math.PI / this.nbFrames[i];
			this.ctx.rotate(rotateAngle);
			
			// Advance angle
			this.lastAngle[i] += 1;
			if (this.lastAngle[i] == 2*this.nbFrames[i]) {
				this.lastAngle[i] = 1;
			}
			
			// draw image
			this.ctx.drawImage(this.backImageObj[i], -50, -50);
			
			this.ctx.restore();
		}
		
		// Draw Front image
		this.ctx.drawImage(this.frontImageObj, 0, 0);
		
		// Save state
		localStorage.setItem("SixLogo.lastAngle", JSON.stringify(this.lastAngle));
	
		// request new frame
		requestAnimFrame(function() {SixLogo.animate.apply(SixLogo);});
	}
};
