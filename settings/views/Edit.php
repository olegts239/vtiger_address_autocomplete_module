<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: EntExt
 * The Initial Developer of the Original Code is EntExt.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@entext.com
 ************************************************************************************/

class Settings_EEAddressAutocomplete_Edit_View extends Settings_Vtiger_Index_View {

    public function process(Vtiger_Request $request) {
        $qualifiedModuleName = $request->getModule(false);
        $viewer = $this->getViewer($request);
        $recordModel = Settings_EEAddressAutocomplete_Record_Model::getInstance();
        $viewer->assign('RECORD_MODEL', $recordModel);
        $viewer->assign('QUALIFIED_MODULE', $qualifiedModuleName);
        $viewer->assign('MODULE', $request->getModule(false));
        $viewer->view('Edit.tpl', $request->getModule(false));
    }

}
