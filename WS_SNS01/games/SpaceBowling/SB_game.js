$(document).ready(function()
{
	var canvas = $("#gameCanvas");
	var context = canvas.get(0).getContext("2d");

	//Distinguish Event handler based on terminal types.
	var spFlag = false;
	var ua = navigator.userAgent;

	if(ua.indexOf('iPhone') > -1 || ua.indexOf('iPad') > -1 || ua.indexOf('iPod')  > -1)
	{
		var startEvent = "touchstart";
		var moveEvent  = "touchmove";
		var endEvent   = "touchend";

		spFlag = true;

	}
	else
	{
		var startEvent = "mousedown";
		var moveEvent  = "mousemove";
		var endEvent   = "mouseup";
	}

	// Canvas dimensions
	var canvasWidth = canvas.width;
	var canvasHeight = canvas.height;

	// Variables for game setting
	var playGame;
	var platformX;
	var platformY;
	var platformOuterRadius;
	var platformInnerRadius;

	var asteroids;

	var player;
	var playerOriginalX;
	var playerOriginalY;

	var playerSelected;
	var playerMaxAbsVelocity;
	var playerVelocityDampener;
	var powerX;
	var powerY;

	var score;

	var deadAsteroids;

	// <Activating the user interface>
	// These are a whole bunch of variables related to the various UI HTML elements; you'll be using them for
	// easy access further on. Right now, they're just shortcuts to the DOM elements for each part of the UI.
	var ui = $("#gameUI");
	var uiIntro = $("#gameIntro");
	var uiStats = $("#gameStats");
	var uiComplete = $("#gameComplete");
	var uiPlay = $("#gamePlay");
	var uiReset = $(".gameReset");
	var uiRemaining = $("#gameRemaining");
	var uiScore = $(".gameScore");

	var Asteroid = function(x, y, radius, mass, friction)
	{
		this.x = x;
		this.y = y;
		this.radius = radius;
		this.mass = mass;
		this.friction = friction;
		this.vX = 0;
		this.vY = 0;

		this.player = false;
	};


	$(window).load(function()
	{
		if(spFlag)
		{
			setCanvasSize4SP();
			canvasWidth = canvas.width;
			canvasHeight = canvas.height;
		}

		function setCanvasSize4SP()
		{
			canvas.width = window.innerWidth * window.devicePixelRatio;
			canvas.height = window.innerHeight * window.devicePixelRatio;
		}
		
	});



	// Initialize the game environment
	function init()
	{
		uiStats.hide();
		uiComplete.hide();

		uiPlay.click(function(e)
		{
			if (!e.pageX)
			{
				e = event.touches[0];
			}

			e.preventDefault();
			uiIntro.hide();
			startGame();
		});

		uiReset.click(function(e)
		{
			if (!e.pageX)
			{
				e = event.touches[0];
			}

			e.preventDefault();
			uiComplete.hide();
			startGame();
		});
	};

	// Reset and start the game
	function startGame()
	{
		// Set up initial game settings
		playGame = false;

		if (spFlag)
		{
			platformX = canvasWidth / 3.5;
			playerOriginalY = canvasHeight - 700;
		}
		else
		{
			platformX = canvasWidth / 2;
			playerOriginalY = canvasHeight - 150;
		}

		platformY = 150;
		platformOuterRadius = 100;
		platformInnerRadius = 75;

		asteroids = new Array();

		// Player setting
		var pRadius = 15;
		var pMass = 10;
		var pFriction = 0.97;
		playerOriginalX = platformX;
		
		player = new Asteroid(playerOriginalX, playerOriginalY, pRadius, pMass, pFriction);
		player.player = true;
		asteroids.push(player);

		playerSelected = false;
		playerMaxAbsVelocity = 30;
		playerVelocityDampener = 0.3;
		powerX = -1;
		powerY = -1;

		score = 0;

		// Set rings
		setRings();

		// Remaining asteroids
		uiRemaining.html(asteroids.length - 1);

		// Show the staistics screen
		uiScore.html("0");
		uiStats.show();

		// Event listeners
		$(window).bind(startEvent, function(e)
		{
			//Special process for smartphone.
			if (!e.pageX)
			{
				e = event.touches[0];
				$(this).data("e",e);	//for touchend event.
			}

			if (!playerSelected && (player.x == playerOriginalX) && (player.y == playerOriginalY))
			{
				var canvasOffset = canvas.offset();
				var canvasX = Math.floor(e.pageX - canvasOffset.left);
				var canvasY = Math.floor(e.pageY - canvasOffset.top);

				if (!playGame)
				{
					playGame = true;
					animate();
				};

				var dX = player.x - canvasX;
				var dY = player.y - canvasY;
				var distance = Math.sqrt((dX * dX) + (dY * dY));
				var padding = 5;

				if (distance < player.radius + padding)
				{
					powerX = player.x;
					powerY = player.y;
					playerSelected = true;
				};
			};
		});

		$(window).bind(moveEvent, function(e)	//"touchmove" for smartphone.
		{
			//Special process for smartphone.
			if (!e.pageX)
			{
				e = event.touches[0];
				$(this).data("e",e);	//for touchend event.
			}

			if (playerSelected)
			{
				var canvasOffset = canvas.offset();
				var canvasX = Math.floor(e.pageX - canvasOffset.left);
				var canvasY = Math.floor(e.pageY - canvasOffset.top);

				var dX = canvasX - player.x;
				var dY = canvasY - player.y;
				var distance = Math.sqrt((dX * dX) + (dY * dY));

				if ((distance * playerVelocityDampener) < playerMaxAbsVelocity)
				{
					powerX = canvasX;
					powerY = canvasY;
				}
				else
				{
					var ratio = playerMaxAbsVelocity / (distance * playerVelocityDampener);
					powerX = player.x + (dX * ratio);
					powerY = player.y + (dY * ratio);
				};
			};
		});

		$(window).bind(endEvent, function(e)	//"touchend" for smartphone.
		 {
			//Special process for smartphone.
			//"touchend" does not return event information, so special handligng is necessary.
			//Therfore some trics on touchstart/touchmove events.
			if (!e.pageX)
			{
				e = $(this).data("e");
			}

			if (playerSelected)
			{
				var dX = powerX - player.x;
				var dY = powerY - player.y;

				player.vX = -(dX* playerVelocityDampener);
				player.vY = -(dY*playerVelocityDampener);

				uiScore.html(++score);
			};

			playerSelected = false;
			powerX = -1;
			powerY = -1;
		});

		// Start the animation loop
		animate();
	};

	//Animation loop that does all the fun stuff
	function animate()
	{
		// Clear
		context.clearRect(0, 0, canvasWidth, canvasHeight);

		// Set up the platform
		context.fillStyle = "rgb(100, 100, 100)";
		createArc(platformX, platformY, platformOuterRadius, 0, Math.PI * 2, true);

		// Depict a line when the player is selected (dragged)
		depictPlayerDragLine();

		// Reset the position
		resetPlayerPos();

		// Making things move
		context.fillStyle = "rgb(255, 255, 255)";

		// Handling asteroid collide and calculate the new position aster the collide.
		collisionHandling();

		// Handling remaining asteroids.
		deadHandling();

		if (playGame)
		{
			// Run the animation loop again in 33 millisecends
			setTimeout(animate, 33);
		};
	};

	function resetPlayer()
	{
		player.x = playerOriginalX;
		player.y = playerOriginalY;
		player.vX = 0;
		player.vY = 0;
	};

	function setRings()
	{
		var outerRing = 8;	// Asteroids around outer rings
		var ringLayer = 3;	// Number of ring layers
		var ringSpacing = (platformInnerRadius / (ringLayer - 1));	// Distance between each ring

		for (var r = 0; r < ringLayer; r++)
		{
			var currentRing = 0;	// Asteroids around current ring
			var angle = 0;	// Angle between each asteroid
			var ringRadius = 0;

			// Is this the innermost ring? "ringLayer - 1" means 3rd time loop. (r = 2).
			if (r == ringLayer - 1)
			{
				currentRing = 1;
			}
			else
			{
				currentRing = outerRing - (r * 3);
				angle = 360 / currentRing;
				ringRadius = platformInnerRadius - (ringSpacing * r);
			};

			for (var a = 0; a < currentRing; a++)
			{
				var x = 0;
				var y = 0;

				// Is this the innermost ring?
				if (r == ringLayer - 1)
				{
					// Set the ring in the center of the platform.
					x = platformX;
					y = platformY;
				}
				else
				{
					// Set the X Y coordinate (anticlockwise)
					x = platformX + (ringRadius * Math.cos((angle * a) * (Math.PI / 180)));
					y = platformY + (ringRadius * Math.sin((angle * a) * (Math.PI / 180)));
				};

				var radius = 10;
				var mass = 5;
				var friction = 0.95;

				asteroids.push(new Asteroid(x, y, radius, mass, friction));
			};
		};
	};

	function depictPlayerDragLine()
	{
		if (playerSelected)
		{
			context.strokeStyle = "rgb(255, 255, 255)";
			context.lineWidth = 3;
			context.beginPath();
			context.moveTo(player.x, player.y);
			context.lineTo(powerX, powerY);
			context.closePath();
			context.stroke();
		};
	};

	function resetPlayerPos ()
	{
		if ((player.x != playerOriginalX) && (player.y != playerOriginalY))
		{
			// Player asteroid is not moving at all
			if ((player.vX == 0) && (player.vY == 0))
			{
				resetPlayer();
			}
			// Player asteroid is outside of canvas from the viewpoint of X coordinate (lefthand side)
			else if (player.x + player.radius < 0)
			{
				resetPlayer();
			}
			// Player asteroid is outside of canvas from the viewpoint of X coordinate (righthand side)
			else if (player.x - player.radius > canvasWidth)
			{
				resetPlayer();
			}
			// Player asteroid is outside of canvas from the viewpoint of Y coordinate (top side)
			else if (player.y + player.radius < 0)
			{
				resetPlayer();
			}
			// Player asteroid is outside of canvas from the viewpoint of Y coordinate (bottom side)
			else if (player.y - player.radius > canvasHeight)
			{
				resetPlayer();
			};
		};
	};

	function collisionHandling()
	{
		var asteroidsLength = asteroids.length;
		deadAsteroids = new Array();

		for (var i = 0; i < asteroidsLength; i++)
		{
			var tmpAsteroid = asteroids[i];

			for (var j = i+1; j < asteroidsLength; j++)		// "j" is +1 of "i" in order not to check self
			{
				var tmpAsteroidB = asteroids[j];

				var dX = tmpAsteroidB.x - tmpAsteroid.x;
				var dY = tmpAsteroidB.y - tmpAsteroid.y;
				var distance = Math.sqrt((dX * dX) + (dY * dY));

				if (distance < tmpAsteroid.radius + tmpAsteroidB.radius)	// Both asteroids collide with each other
				{
					var angle = Math.atan2(dY, dX);
					var sine = Math.sin(angle);
					var cosine = Math.cos(angle);

					// Rotate asteroids position
					var x = 0;
					var y = 0;

					// Rotate asteroidB position
					var xB = dX * cosine + dY * sine;
					var yB = dY * cosine + dX * sine;

					// Rotate asteroid velocity
					var vX = tmpAsteroid.vX * cosine + tmpAsteroid.vY * sine;
					var vY = tmpAsteroid.vY * cosine - tmpAsteroid.vX * sine;

					// Rotate asteroidB velocity
					var vXb = tmpAsteroidB.vX * cosine + tmpAsteroidB.vY * sine;
					var vYb = tmpAsteroidB.vY * cosine - tmpAsteroidB.vX * sine;

					// Converse momentum
					var vTotal = vX - vXb;
					vX = ((tmpAsteroid.mass - tmpAsteroidB.mass) * vX + 2 * tmpAsteroidB.mass * vXb) / (tmpAsteroid.mass + tmpAsteroidB.mass);
					vXb = vTotal + vX;

					// Move asteroids apart
					xB = x + (tmpAsteroid.radius + tmpAsteroidB.radius);

					// Rotate asteroid positions back
					tmpAsteroid.x = tmpAsteroid.x + (x * cosine - y * sine);
					tmpAsteroid.y = tmpAsteroid.y + (y * cosine + x * sine);

					tmpAsteroidB.x = tmpAsteroid.x + (xB * cosine - yB * sine);
					tmpAsteroidB.y = tmpAsteroid.y + (yB * cosine + xB * sine);

					// Rotate asteroid velocities back
					tmpAsteroid.vX = vX * cosine - vY * sine;
					tmpAsteroid.vY = vY * cosine + vX * sine;

					tmpAsteroidB.vX = vXb * cosine - vYb * sine;
					tmpAsteroidB.vY = vYb * cosine + vXb * sine;
				};


			};

			// Calculate new poistion
			tmpAsteroid.x += tmpAsteroid.vX;
			tmpAsteroid.y += tmpAsteroid.vY;

			// Friction
			if (Math.abs(tmpAsteroid.vX) > 0.1)
			{
				tmpAsteroid.vX *= tmpAsteroid.friction;
			}
			else
			{
				tmpAsteroid.vX = 0;
			};

			if (Math.abs(tmpAsteroid.vY) > 0.1)
			{
				tmpAsteroid.vY *= tmpAsteroid.friction;
			}
			else
			{
				tmpAsteroid.vY = 0;
			};

			// Checking asteroids whether they are in the platform or not.
			if (!tmpAsteroid.player)	// Excluding player's asteroid from check
			{
				var dXp = tmpAsteroid.x - platformX;
				var dYp = tmpAsteroid.y - platformY;
				var distanceP = Math.sqrt((dXp*dXp) + (dYp*dYp));

				if (distanceP > platformOuterRadius)
				{
					if (tmpAsteroid.radius > 0)
					{
						tmpAsteroid.radius -= 2;
					}
					else
					{
						deadAsteroids.push(tmpAsteroid);
					};
				};
			};

			// Depicting asteroids
			if (tmpAsteroid.player)
			{
				context.fillStyle = "rgb(255, 0, 0)";
				createArc(tmpAsteroid.x, tmpAsteroid.y, tmpAsteroid.radius, 0, Math.PI * 2, true);
				context.fillStyle = "rgb(255,255,255)";
			}
			else
			{
				createArc(tmpAsteroid.x, tmpAsteroid.y, tmpAsteroid.radius, 0, Math.PI * 2, true);
			};

		};
	};

	function deadHandling()
	{
		var deadAsteroidsLength = deadAsteroids.length;

		if (deadAsteroidsLength > 0)
		{
			for (var di = 0; di < deadAsteroidsLength; di++)
			{
				var tmpDeadAsteroid = deadAsteroids[di];
				asteroids.splice(asteroids.indexOf(tmpDeadAsteroid), 1);
			};

			var remaining = (asteroids.length - 1);	// Remove player asteroid from asteroid count
			uiRemaining.html(remaining);

			if (remaining == 0)
			{
				// Winner
				playGame = false;
				uiStats.hide();
				uiComplete.show();

				$(window).unbind("mousedown");
				$(window).unbind("mouseup");
				$(window).unbind("mousemove");
			};
		};
	};

	function createArc(centerX, centerY, radius, startAngle, endAngle, antiClockWise)
	{
		context.beginPath();
		context.arc(centerX, centerY, radius, startAngle, endAngle, antiClockWise);
		context.closePath();
		context.fill();
	};

	// Initial procedure !!
	init();


});
