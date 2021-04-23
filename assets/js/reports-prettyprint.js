/*
 * Details page
 */

+function ($) { "use strict";
	var CSPReportDetails = function () {
		this.init()
	}

	CSPReportDetails.prototype.init = function() {

		$(document).ready(function() {
			$('.csp-details-content pre').addClass('prettyprint')
			prettyPrint()
		})

	}

	if ($.zaxbux === undefined)
		$.zaxbux = {}

	$.zaxbux.reportDetails = new CSPReportDetails;

}(window.jQuery);
