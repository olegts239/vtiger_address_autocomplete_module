<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: EntExt
 * The Initial Developer of the Original Code is EntExt.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@entext.com
 ************************************************************************************/

class EEAddressAutocomplete_Module_Model extends Vtiger_Module_Model {

    /**
     * Function to get Settings links
     *
     * @return array <Array>
     */
    public function getSettingLinks(){
        $settingsLinks = array();
        $settingsLinks[] = array(
            'linktype' => 'LISTVIEWSETTING',
            'linklabel' => 'LBL_SETTINGS',
            'linkurl' => 'index.php?module='.$this->getName().'&parent=Settings&view=Index',
            'linkicon' => ''
        );
        return $settingsLinks;
    }

    /**
     * Not customizable
     *
     * @return bool
     */
    public function isCustomizable() {
        return false;
    }

}
