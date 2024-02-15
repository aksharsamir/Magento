define([
	'jquery',
	'Magento_Ui/js/model/messageList',
	'mage/url',
	"mage/template",
	"mage/translate",
	"loader"
], function ($, messageList, url, mageTemplate) {
	'use strict';
	
	$.widget('etailors.form', {
	    form: null,
	    form_id: null,
	    
	    _create: function(config, element) {  
	        console.log('Create');
	        this.loader = $(this.options.loaderSelector);   
	        this.messageContainer = $(this.options.messageContainerSelector);  

            this.element.on('submit', $.proxy(function(e) {
                this.submitForm(e);
            }, this));
            
            this.element.on('click', '[data-action="previous"]', $.proxy(function(e) {
                e.preventDefault();
                this.goBack();
            }, this));
            
            $(window).on('popstate', $.proxy(function(e) {
                var postData = e.originalEvent.state;
                var self = this;
                if(postData === null) { 
                    var formId = this.element.find('input[name="form_id"]').val();
                    postData = {'form_id': formId, 'page': 1};
                }
                var gobackUrl = url.build('forms/ajax/getpage');
                
                this.startLoader();
                
                $.ajax({
                    url: gobackUrl,
                    method: "POST",
                    data: postData,
                    dataType: "json",
                    success: function(returnData) {
                        self.processNewPage(returnData.fields_html, returnData.buttons_html, returnData.newpage, false); 
                    },
	                error: function(jqXHR, textStatus, errorThrown) {
	                    self.processLoadError();
	                }
                    
                });
            }, this));
	    },
	    
	    submitForm: function(e) {
	        e.preventDefault();

            if (this.element.valid() === true) {
	            this.validateBackend();
	        }
	    },

	    goBack: function() {
	        var gobackUrl = url.build('forms/ajax/getpage');
	        var formId = this.element.find('input[name="form_id"]').val();
	        var currentPage = this.element.find('input[name="current_page"]').val();
	        var newPage = parseInt(currentPage) - 1;
	        var postData = {page: newPage, form_id: formId};
	        var self = this;
	        
            this.startLoader();
	        
	        $.ajax({
	            url: gobackUrl,
	            method: "POST",
	            data: postData,
	            dataType: "json",
	            success: function(returnData) {
                    self.processNewPage(returnData.fields_html, returnData.buttons_html, returnData.newpage, true); 
	            },
	            error: function(jqXHR, textStatus, errorThrown) {
	                self.processLoadError();
	            }
	        });
	    },
	    
	    validateBackend: function() {
            var validationUrl = url.build('forms/ajax/validate');
            var currentPage = this.element.find('input[name="current_page"]').val();
	        var formData = this.element.serialize();
            var self = this;
            
            this.startLoader();
	        
	        $.ajax({
	            url: validationUrl,
	            method: "POST",
	            data: formData,
	            dataType: "json",
	            success: function(returnData) {
                    if (returnData.valid === false) {
                        self.processErrorResponse(returnData.errors);
                    }
                    else if (returnData.success === false) {
                        self.processNewPage(returnData.fields_html, returnData.buttons_html, returnData.newpage, true); 
                    }
                    else {
                        self.processSuccessMessage(returnData.success_html, 'success');
                    }
	            },
	            error: function(jqXHR, textStatus, errorThrown) {
	                self.processLoadError();
	            }
	            
	        });
	            
	    },
	    
	    startLoader: function() {
	        this.clearMessages();
	        this.loader.trigger('processStart');
	    },
	    
	    stopLoader: function() {
	        this.loader.trigger('processStop');
	    },
	    
	    addSuccessMessage(message) {
	        this.addMessage(message, 'success');
	    },
	    
	    addErrorMessage(message) {
	        this.addMessage(message, 'error');
	    },
	    
	    addMessage(message, severity) {
	        var template = mageTemplate('#forms-message-template');
	        var errorMessage = template({
                data: {
                    message: $.mage.__(message),
                    severity: severity
                }
            });
            
            this.messageContainer.append(errorMessage);
	    },
	    
	    clearMessages: function() {
	        this.messageContainer.html('');
	    },
	    
	    processLoadError: function() {
	        this.addErrorMessage('There was an error processing the request. Please try again later.');
	        this.stopLoader();
	    },
	    
	    processErrorResponse: function(errors) {
	        var template = mageTemplate('#forms-field-error-template');
	        $.each(errors, $.proxy(function(fieldId, validationResult) {
	            if (validationResult.valid === false) {
	                var field = this.getField(fieldId);
	                // Remove 'old' errors
	                if (field.siblings('.mage-error').length > 0) {
	                    field.siblings('.mage-error').remove();
	                }
	                
	                $.each(validationResult.errors, $.proxy(function(key, msg) {
	                    var errorMessage = template({
	                        data: {
	                            errorMessage: msg
	                        }
	                    });
	                    field.after(errorMessage);
                    }, this));
                    
                    field.addClass('mage-error');
                }
	        }, this));
	    },
	    
	    processNewPage: function(fieldsHtml, buttonsHtml, pageNum, updateUrl) {
	        var contentWrapper = $(this.options.contentSelector);
            var buttonsWrapper = $(this.options.buttonsSelector);
            
            contentWrapper.html(fieldsHtml);
            buttonsWrapper.html(buttonsHtml);
            
            this.element.find('input[name="current_page"]').val(pageNum);

            if(updateUrl !== false) {
                this.updateUrl(pageNum);
            }
            
            this.stopLoader();
	    },
	    
	    processSuccessMessage: function(successHtml, pageNum) {
	        this.processNewPage(successHtml, '', pageNum, true);
	    },
	    
	    updateUrl: function(pageNum) {
	        if (typeof history.pushState !== "undefined" && this.options.changeUrl === true) {
	            var currentPath = window.location.pathname;
	            if (currentPath.match(/\/page\/([0-9]+)(\/)?$/i)) {
	                currentPath = currentPath.replace(/\/page\/([0-9]+)(\/)?$/gi, '');
	            }
                
                // Always cut off trailign slash
                currentPath = currentPath.replace(/(\/)?$/gi, '');
                
                // Append new parts
                var newPath = currentPath + '/page/'+pageNum;
                
                var newUrl =    window.location.protocol + '//' +
                                window.location.hostname + 
                                newPath + 
                                window.location.search +
                                window.location.hash;
                
                var formId = this.element.find('input[name="form_id"]').val();
                var obj = {'form_id': formId, 'page': pageNum};
                
                history.pushState(obj, document.title, newUrl);               
                
            }
	    },
	    
	    getField: function(fieldId) {
	        return $('[data-field-id="'+fieldId+'"]');
	    }
	    
	});
	
	return $.etailors.form;
	
});
