/**
 * (C) OpenEyes Foundation, 2014
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (C) 2014, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

/**
 * Constructor for the trabeculectomy widget controller
 *
 * @param ED.Drawing drawing
 */
function OEEyeDrawWidgetTrabeculectomy_Controller (drawing) {
	var globalScaleFactor = 0.72;

	// 'Prototype position' of trabeculectomy Sutures (relative position, angle and shape)
	var sutureSet = [
		{xd: -1, yd: 0, a: 7 * Math.PI/4, s: 'Releasable', ex: -25},
		{xd: 0, yd: 0, a: 0, s:'Releasable', ex: 0},
		{xd: +1, yd: 0, a: 1 * Math.PI/4, s: 'Releasable', ex: +25},
		{xd: -1, yd: 0.5, a: Math.PI/2, s: 'Fixed', ex: 0},
		{xd: +1, yd: 0.5, a: 3 * Math.PI/2, s: 'Fixed', ex: 0},
	];

	var suturePoints = [], sutures = [];

	function init (drawing) {
		drawing.globalScaleFactor = globalScaleFactor;

		drawing.addDoodle('AntSeg');
		drawing.addDoodle('ConjunctivalFlap');
		drawing.addDoodle('PI', {rotation:0});
		drawing.addDoodle('SidePort', {rotation:(drawing.eye == ED.eye.Right?5:3) * Math.PI/4});

		var trab = drawing.addDoodle('TrabyFlap', {size:'5x2'});

		// Iterate through sutures creating an array of objects representing 'ideal' sutures
		for (var i in sutureSet) {
			// Create point from ideal with added attributes
			var p = new ED.Point(sutureSet[i].xd * trab.right.x * globalScaleFactor, (trab.height + sutureSet[i].yd * (trab.right.y - trab.height)) * globalScaleFactor);
			p.angle = sutureSet[i].a;
			p.shape = sutureSet[i].s;
			p.ex = sutureSet[i].ex;

			// Add it to an array
			suturePoints.push(p);
		}

		sutures = [null, null, null, null, null];
		for (i = 0; i < 3; i ++) drawing.addDoodle("TrabySuture");

		drawing.deselectDoodles();
	}

	function trabyFlapChangedSize(trab) {
		for (var i = 0; i < sutures.length; i++) {
			if (sutures[i]) {
				sutures[i].originX = sutureSet[i].xd * trab.right.x * 0.72;
				sutures[i].originY = (trab.height + sutureSet[i].yd * (trab.right.y - trab.height)) * 0.72;

				// Reset reference points (in doodle coordinates but before rotation applied)
				suturePoints[i].x = sutures[i].originX;
				suturePoints[i].y = sutures[i].originY;
			}
		}

		// Trigger notification to apply rotation to sutures
		drawing.notify(
			'parameterChanged',
			{doodle:trab, parameter: 'rotation', value: trab.rotation, oldValue: trab.rotation}
		);
	}

	function trabyFlapRotated(trab) {
		// Rotate sutures with flap
		for (var i = 0; i < sutures.length; i++) {
			// Get original position
			var p = suturePoints[i];

			// Calculate radius to this point
			var r = Math.sqrt(p.x * p.x + p.y * p.y);

			// Calculate new position
			var np = p.pointAtRadiusAndClockwiseAngle(r, trab.rotation);

			// Apply it to suture
			var sut = sutures[i];
			if (sut) {
				sut.originX = np.x;
				sut.originY = np.y;

				// Apply rotation
				sut.rotation = ED.Mod(p.angle + trab.rotation, 2 * Math.PI);
			}
		}

		// Also rotate a conjunctival flap if present
		var conjFlap = drawing.lastDoodleOfClass('ConjunctivalFlap');
		if (conjFlap) {
			conjFlap.rotation = trab.rotation;
		}
	}

	function sutureAdded (sut) {
		// Add it to first available slot in suture Array
		for (var i = 0; i < sutures.length; i++) {
			if (sutures[i]) continue;

			sutures[i] = sut;

			var trab = drawing.lastDoodleOfClass("TrabyFlap");
			if (trab) {
				// Get original position
				var p = suturePoints[i];

				// Calculate radius to this point
				var r = Math.sqrt(p.x * p.x + p.y * p.y);

				// Calculate new position
				var np = p.pointAtRadiusAndClockwiseAngle(r, trab.rotation);

				// Apply it to suture
				sutures[i].originX = np.x;
				sutures[i].originY = np.y;

				// Apply rotation
				sutures[i].rotation = ED.Mod(p.angle + trab.rotation, 2 * Math.PI);

				// Apply shape
				sutures[i].setParameterFromString('shape', p.shape);

				// Apply firstOrginX
				sutures[i].firstOriginX = p.ex;

				drawing.repaint();
			}
			break;
		}
	}

	function sutureDeleted (sut) {
		for (var i = 0; i < sutures.length; i++) {
			if (sutures[i] && !drawing.doodleOfId(sutures[i].id)) {
				sutures[i] = null;
				break;
			}
		}
	}

	this.notificationHandler = function (message) {
		switch (message.eventName) {
			case 'ready':
				init(drawing);
				break;
			case 'parameterChanged':
				var doodle = message.object.doodle;
				switch (doodle.className) {
					case 'TrabyFlap':
						switch (message.object.parameter) {
							case 'size':
								trabyFlapChangedSize(doodle);
								break;
							case 'rotation':
								trabyFlapRotated(doodle);
								break;
						}
					case 'TrabySuture':
						// Suture has moved (a refinement would be to feed back movement to suture plan so that movement of flap carries sutures with it)
						break;
				}
				break;
			case 'doodleAdded':
				var doodle = message.object;
				if (doodle.className === 'TrabySuture') sutureAdded(doodle);
				break;
			case 'doodleDeleted':
				var doodle = message.object;
				if (doodle.className === 'TrabySuture') sutureDeleted(doodle);
				break;
		}
	};

	drawing.registerForNotifications(this);
}
