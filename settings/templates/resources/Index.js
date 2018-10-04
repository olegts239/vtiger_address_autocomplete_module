/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: SalesPlatform Ltd
 * The Initial Developer of the Original Code is SalesPlatform Ltd.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@salesplatform.ru
 ************************************************************************************/

jQuery.Class("Settings_EEAddressAutocomplete_Js", {},{

    /**
     * Function to save config details
     * @param form
     */
    saveConfigDetails : function(form) {
        var thisInstance = this;
        var data = form.serializeFormData();
        var progressIndicatorElement = jQuery.progressIndicator({
            'position' : 'html',
            'blockInfo' : {
                'enabled' : true
            }
        });

        if(typeof data == 'undefined' ) {
            data = {};
        }
        data.module = app.getModuleName();
        data.parent = app.getParentModuleName();
        data.action = 'SaveAjax';

        AppConnector.request(data).then(
            function(data) {
                if(data['success']) {
                    var addressAutocompleteDetailUrl = form.data('detailUrl');
                    //after save, load detail view contents and register events
                    thisInstance.loadContents(addressAutocompleteDetailUrl).then(
                        function(data) {
                            progressIndicatorElement.progressIndicator({'mode':'hide'});
                            thisInstance.registerDetailViewEvents();
                        },
                        function(error, err) {
                            progressIndicatorElement.progressIndicator({'mode':'hide'});
                        }
                    );
                } else {
                    progressIndicatorElement.progressIndicator({'mode':'hide'});
                    jQuery('div.alert-error', form).text(data['error'].message);
                    jQuery('.errorMessage', form).removeClass('hide');
                }
            },

            function(error, errorThrown) {
            }
        );
    },

    /**
     * Function to register the events in editView
     */
    registerEditViewEvents : function() {
        var thisInstance = this;
        var form = jQuery('#addressAutocompleteModal');
        var cancelLink = jQuery('.cancelLink', form);

        //register validation engine
        var params = app.validationEngineOptions;
        params.onValidationComplete = function(form, valid){
            if(valid) {
                thisInstance.saveConfigDetails(form);
                return valid;
            }
        };
        form.validationEngine(params);

        form.submit(function(e) {
            e.preventDefault();
        });

        //register click event for cancelLink
        cancelLink.click(function(e) {
            var addressAutocompleteDetailUrl = form.data('detailUrl');
            var progressIndicatorElement = jQuery.progressIndicator({
                'position' : 'html',
                'blockInfo' : {
                    'enabled' : true
                }
            });

            thisInstance.loadContents(addressAutocompleteDetailUrl).then(
                function(data) {
                    progressIndicatorElement.progressIndicator({'mode':'hide'});
                    //after loading contents, register the events
                    thisInstance.registerDetailViewEvents();
                },
                function(error, err) {
                    progressIndicatorElement.progressIndicator({'mode':'hide'});
                }
            );
        });
    },

    /**
     * Function to register events in DetailView
     */
    registerDetailViewEvents : function() {
        var thisInstance = this;
        var container = jQuery('#addressAutocompleteSettings');
        var editButton = jQuery('.editButton', container);

        editButton.click(function(e){
            var url = jQuery(e.currentTarget).data('url');
            var progressIndicatorElement = jQuery.progressIndicator({
                'position' : 'html',
                'blockInfo' : {
                    'enabled' : true
                }
            });

            thisInstance.loadContents(url).then(
                function(data) {
                    //after load the contents register the edit view events
                    thisInstance.registerEditViewEvents();
                    progressIndicatorElement.progressIndicator({'mode':'hide'});
                },
                function(error, err) {
                    progressIndicatorElement.progressIndicator({'mode':'hide'});
                }
            );
        });
    },

    /**
     * Function to load the contents from the url through pjax
     * @param url
     * @returns {*}
     */
    loadContents : function(url) {
        var aDeferred = jQuery.Deferred();
        AppConnector.requestPjax(url).then(
            function(data) {
                jQuery('.contentsDiv').html(data);
                aDeferred.resolve(data);
            },
            function(error, err){
                aDeferred.reject();
            }
        );
        return aDeferred.promise();
    },

    /**
     * Registering required events on index view page
     */
    registerEvents: function() {
        var thisInstance = this;
        if(jQuery('#addressAutocompleteSettings').length > 0) {
            thisInstance.registerDetailViewEvents();
        } else {
            thisInstance.registerEditViewEvents();
        }
    }
});

jQuery(document).ready(function(e) {
    var instance = new Settings_EEAddressAutocomplete_Js();
    instance.registerEvents();
});
