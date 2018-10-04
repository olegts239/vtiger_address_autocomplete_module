<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: EntExt
 * The Initial Developer of the Original Code is EntExt.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@entext.com
 ************************************************************************************/

class Settings_EEAddressAutocomplete_SaveAjax_Action extends Vtiger_SaveAjax_Action {

    public function checkPermission(Vtiger_Request $request) {

    }

    public function process(Vtiger_Request $request) {
        $recordModel = Settings_EEAddressAutocomplete_Record_Model::getCleanInstance();
        $model = new Settings_EEAddressAutocomplete_Module_Model();
        foreach ($model->getSettingsParameters() as $field => $type) {
            $recordModel->set($field, $request->get($field));
        }
        $response = new Vtiger_Response();
        $recordModel->save();
        $response->setResult(true);
        $response->emit();
    }
}
