/**
 * HAL - HAL logo animation
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

var HalLogo = {
	width: 0,
	height: 0,
	canvas: null,
	ctx: null,

	frontImageObj: null,
	nbImage: 2,
	backImageObj: [],
	
	nbFrames: [1200, 1800],
	lastPos: [0, 900],
	
	initCanvas: function(w, h) {
		this.width = w;
		this.height = h;
		
		try {
			// Check if canvas is supported
			this.canvas = document.getElementById('logo');
	        this.ctx = this.canvas.getContext('2d');
	        // Define pos & style
			this.ctx.translate(this.width / 2, this.height / 2);
			this.ctx.strokeStyle = '#bbbbbb';

			this.frontImageObj = new Image();
			this.frontImageObj.onload = function() {
				HalLogo.animate.apply(HalLogo);
			};
			this.frontImageObj.src = document.getElementById('logo_img').src;

			for (var i=0; i<this.nbImage; i++) {
				this.backImageObj[i] = new Image();
				this.backImageObj[i].src = document.getElementById('planet_'+(i+1)).src ;
			}
			
			var val = localStorage.getItem("HalLogo.lastPos");
			if (val != null) {
				this.lastPos = JSON.parse(val);
			}

			document.getElementById('logo').style.display = 'none';
		} catch (e) {
			// old browsers support
			if (window.console && console.log) console.log(e.message);
			document.getElementById('logo').style.display = 'block';
		};
	},

	animate: function() {
		var ctx = this.ctx;
		// Clear
		ctx.clearRect(this.width / -2, this.height / -2, this.width, this.height);

		// Background ellipse
		ctx.save();
		ctx.rotate(-1 * Math.PI / 4);
		this.drawHalfEllipseByCenter(0, 0, 100, 50, true);
		this.drawPlanet(0, true);
		ctx.rotate(Math.PI / 2);
		this.drawHalfEllipseByCenter(0, 0, 100, 50, true);
		this.drawPlanet(1, true);
		ctx.restore();
		
		// Draw logo
		ctx.drawImage(this.frontImageObj, this.width / -2, this.height / -2);

		// Front ellipse
		ctx.save();
		ctx.rotate(-1 * Math.PI / 4);
		this.drawHalfEllipseByCenter(0, 0, 100, 50, false);
		this.drawPlanet(0, false);
		ctx.rotate(Math.PI / 2);
		this.drawHalfEllipseByCenter(0, 0, 100, 50, false);
		this.drawPlanet(1, false);
		ctx.restore();

		for (var i=0; i<this.nbImage; i++) {
			this.lastPos[i] += 1;
			if (this.lastPos[i] >= this.nbFrames[i]) {
				this.lastPos[i] = 0;
			}
		}
	
		// Save state
		localStorage.setItem("HalLogo.lastPos", JSON.stringify(this.lastPos));
	
		// request new frame
		requestAnimFrame(function() {HalLogo.animate.apply(HalLogo);});
	},
	
	drawPlanet: function(idx, up) {
		var halfFrame = this.nbFrames[idx] / 2;
		
		if (!up && this.lastPos[idx] < halfFrame) {
			// Draw planet on BG
			var x = (100 * this.lastPos[idx] / halfFrame) - 50;
			var y = Math.sqrt(Math.pow(25, 2) - Math.pow(x, 2) / 4);
			//if (idx==0) console.log("DrawPlanet BG #"+idx+" at "+[x, y]+' for '+this.lastPos[idx]);
			
			// Ajust with img size
			x = x - this.backImageObj[idx].width / 2;
			y = y - this.backImageObj[idx].height / 2;
			
			this.ctx.drawImage(this.backImageObj[idx], x, y);
		}
		if (up && this.lastPos[idx] >= halfFrame) {
			// Draw planet on FG
			var x = -1 * ((100 * (this.lastPos[idx] - halfFrame) / halfFrame) - 50);
			var y = -1 * Math.sqrt(Math.pow(25, 2) - Math.pow(x, 2) / 4);
			//if (idx==0) console.log("DrawPlanet FG #"+idx+" at "+[x, y]+' for '+this.lastPos[idx]);
			
			// Ajust with img size
			x = x - this.backImageObj[idx].width / 2;
			y = y - this.backImageObj[idx].height / 2;

			this.ctx.drawImage(this.backImageObj[idx], x, y);
		}
	},
	
	drawHalfEllipseByCenter: function(cx, cy, w, h, up) {
		this.drawHalfEllipse(cx - w/2.0, cy - h/2.0, w, h, up);
	},

	drawHalfEllipse: function(x, y, w, h, up) {
		var kappa = .5522848,
			ox = (w / 2) * kappa, // control point offset horizontal
			oy = (h / 2) * kappa, // control point offset vertical
			xe = x + w,           // x-end
			ye = y + h,           // y-end
			xm = x + w / 2,       // x-middle
			ym = y + h / 2;       // y-middle

		this.ctx.beginPath();
		if (up) {
			this.ctx.moveTo(x, ym);
			this.ctx.bezierCurveTo(x, ym - oy, xm - ox, y, xm, y);
			this.ctx.bezierCurveTo(xm + ox, y, xe, ym - oy, xe, ym);
		} else {
			this.ctx.moveTo(xe, ym);
			this.ctx.bezierCurveTo(xe, ym + oy, xm + ox, ye, xm, ye);
			this.ctx.bezierCurveTo(xm - ox, ye, x, ym + oy, x, ym);
		}
		this.ctx.stroke();
	}
};
