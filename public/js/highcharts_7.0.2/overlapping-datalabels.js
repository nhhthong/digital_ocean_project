/**
 * @license @product.name@ JS v@product.version@ (@product.date@)
 * Highcharts module to hide overlapping data labels. This module is included by default in Highmaps.
 *
 * (c) 2010-2014 Torstein Honsi
 *
 * License: www.highcharts.com/license
 */

(function (H) {
	var Series = H.Series,
		each = H.each,
		wrap = H.wrap;

	// Add the overlapping logic after drawing data labels
	wrap(Series.prototype, 'drawDataLabels', function (proceed) {
		var labels = [];
		proceed.call(this);

		each(this.points, function (point) { 
			if (point.dataLabel) {
				point.dataLabel.labelrank = point.labelrank;
				labels.push(point.dataLabel);
			}
		});
		this.hideOverlappingLabels(labels);
	});

	/**
	 * Hide overlapping labels. Labels are moved and faded in and out on zoom to provide a smooth 
	 * visual imression.
	 */		
	Series.prototype.hideOverlappingLabels = function (labels) {

		var len = labels.length,
			label,
			i,
			j,
			label1,
			label2,
			intersectRect = function (pos1, pos2, size1, size2) {
				return !(
					pos2.x > pos1.x + size1.width ||
					pos2.x + size2.width < pos1.x ||
					pos2.y > pos1.y + size1.height ||
					pos2.y + size2.height < pos1.y
				);
			};
	
		// Mark with initial opacity
		for (i = 0; i < len; i++) {
			label = labels[i];
			if (label) {
				label.oldOpacity = label.opacity;
				label.newOpacity = 1;
			}
		}

		// Detect overlapping labels
		for (i = 0; i < len; i++) {
			label1 = labels[i];

			for (j = i + 1; j < len; ++j) {
				label2 = labels[j];
				if (label1 && label2 && label1.placed && label2.placed && label1.newOpacity !== 0 && label2.newOpacity !== 0 && 
						intersectRect(label1.alignAttr, label2.alignAttr, label1, label2)) {
					(label1.labelrank < label2.labelrank ? label1 : label2).newOpacity = 0;
				}
			}
		}

		// Hide or show
		for (i = 0; i < len; i++) {
			label = labels[i];
			if (label) {
				if (label.oldOpacity !== label.newOpacity && label.placed) {
					label.alignAttr.opacity = label.newOpacity;
					label[label.isOld ? 'animate' : 'attr'](label.alignAttr);
				}
				label.isOld = true;
			}
		}
	};

}(Highcharts));