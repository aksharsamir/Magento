define([
	'jquery'
], function ($) {
	'use strict';
	
	$.widget('etailors.recaptcha', {
		
		_create: function(config, element) {
			var that = this;
			$(this.element).closest('form').on('submit', function() {
				
				// Find the recaptcha field
				var wrapper = $(that.element).closest('.recaptcha-wrapper');
				var response = wrapper.find('textarea[name="g-recaptcha-response"]').val();

				$(that.element).val(response);
			});
		}
		
	});
		
	return $.etailors.recaptcha;
});