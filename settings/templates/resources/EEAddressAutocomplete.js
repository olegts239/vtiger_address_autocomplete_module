/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: EntExt
 * The Initial Developer of the Original Code is EntExt.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@entext.com
 ************************************************************************************/

jQuery.Class("EEAddressAutocomplete_Js",{},{

    autocomplete : null,

    registerOnFocusAttr : function() {
        if(!this.validViewAndModule()) return;

        var editViewForm = jQuery('#EditView');
        editViewForm.find('input[name="ee_google_search"]').attr('onFocus', 'geolocate()');
    },

    registerGoogleApiScript : function() {
        if(!this.validViewAndModule()) return;

        var aDeferred = jQuery.Deferred();
        var params = {};

        params['module'] = 'EEAddressAutocomplete';
        params['action'] = 'GetGooglePlacesApiKey';
        AppConnector.request(params).then(
            function(data) {
                if(data.success) {
                    if(data.result) {
                        var imported = document.createElement('script');
                        var apiKey = data.result;
                        imported.src = 'https://maps.googleapis.com/maps/api/js?key='+apiKey+'&libraries=places&callback=initAutocomplete';
                        document.head.appendChild(imported);
                    }
                }
                aDeferred.resolve(data);
            },

            function(error) {
                aDeferred.reject(error);
            }
        );
        return aDeferred.promise();
    },

    validViewAndModule : function() {
        var viewName = app.getViewName();
        var currentModule = app.getModuleName();
        return !!(viewName == 'Edit' && ['Leads', 'Accounts', 'Contacts'].indexOf(currentModule) != -1);

    },

    registerEvents : function() {
        this.registerOnFocusAttr();
        this.registerGoogleApiScript();
    }

});

jQuery(document).ready(function() {
    var eeAddressAutocompleteInstance = new EEAddressAutocomplete_Js();
    eeAddressAutocompleteInstance.registerEvents();
    app.listenPostAjaxReady(function() {
        var eeAddressAutocompleteInstance = new EEAddressAutocomplete_Js();
        eeAddressAutocompleteInstance.registerEvents();
    });
});

// Autocomplete feature of the Google Places API to help users fill in the information.
var autocomplete;

function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical location types.
    var eeAddressAutocompleteInstance = new EEAddressAutocomplete_Js();
    if(!eeAddressAutocompleteInstance.validViewAndModule()) return;

    var fieldId = app.getModuleName() + '_editView_fieldName_ee_google_search';
    autocomplete = new google.maps.places.Autocomplete(
        /** @type {!HTMLInputElement} */(document.getElementById(fieldId)),
        {types: ['geocode']}
    );

    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
}

function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();

    var componentForm = getComponentForm();
    var elem;

    for (var j in componentForm) {
        elem = document.getElementById(componentForm[j]);
        if(elem) {
            elem.value = '';
            elem.disabled = false;
        }
    }

    // Get each component of the address from the place details and fill the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
        var addressType = place.address_components[i].types[0];
        if(componentForm[addressType]) {
            var val = place.address_components[i]['long_name'];
            elem = jQuery('[name="' + componentForm[addressType] + '"]');
            if(elem) {
                // elem.value = val;
                // Adding support if any of the fields is selectbox
                // therefor intiating triggers
                jQuery(elem).val(val).trigger('liszt:updated').trigger('change');
            }
        }

        // Fill street field in modules
        if(addressType == 'street_number') {
            var streetNumber = place.address_components[i]['long_name'];
        }
        if(addressType == 'route') {
            var route = place.address_components[i]['long_name'];
        }
        var address = '';
        if(typeof route !== 'undefined') {
            address = route;
            if(typeof streetNumber !== 'undefined') {
                address += ', ' + streetNumber;
            }
        }
        var editViewForm = jQuery('#EditView');
        switch (app.getModuleName()) {
            case 'Leads':
                editViewForm.find('textarea[name="lane"]').val(address);
                break;
            case 'Accounts':
                editViewForm.find('textarea[name="bill_street"]').val(address);
                break;
            case 'Contacts':
                editViewForm.find('textarea[name="mailingstreet"]').val(address);
                break;
            default:
                break;
        }
    }
}

// Bias the autocomplete object to the user's geographical location, as supplied by the browser's 'navigator.geolocation' object.
function geolocate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
                center: geolocation,
                radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
        });
    }
}

function getComponentForm() {
    var componentForm = [];
    switch (app.getModuleName()) {
        // Changed values to work on field name (input name) rather than element id
        case 'Leads':
            componentForm['locality'] = 'city';
            componentForm['administrative_area_level_1'] = 'state';
            componentForm['country'] = 'country';
            componentForm['postal_code'] = 'code';
            break;
        case 'Accounts':
            componentForm['locality'] = 'bill_city';
            componentForm['administrative_area_level_1'] = 'bill_state';
            componentForm['country'] = 'bill_country';
            componentForm['postal_code'] = 'bill_code';
            break;
        case 'Contacts':
            componentForm['locality'] = 'mailingcity';
            componentForm['administrative_area_level_1'] = 'mailingstate';
            componentForm['country'] = 'mailingcountry';
            componentForm['postal_code'] = 'mailingzip';
            break;
        default:
            break;
    }

    return componentForm;
}
