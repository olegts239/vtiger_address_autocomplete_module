<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: EntExt
 * The Initial Developer of the Original Code is EntExt.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@entext.com
 ************************************************************************************/

class EEAddressAutocomplete_GetGooglePlacesApiKey_Action extends Vtiger_Action_Controller {

    public function checkPermission(Vtiger_Request $request) {

    }

    public function process(Vtiger_Request $request) {
        $addressAutocompleteInstance = Settings_EEAddressAutocomplete_Record_Model::getInstance();
        $response = new Vtiger_Response();
        $response->setResult($addressAutocompleteInstance->get('api_key'));
        $response->emit();
    }
}