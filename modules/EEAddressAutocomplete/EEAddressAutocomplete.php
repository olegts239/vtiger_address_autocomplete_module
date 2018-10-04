<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: EntExt
 * The Initial Developer of the Original Code is EntExt.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@entext.com
 ************************************************************************************/
include_once 'include/Zend/Json.php';
require_once('data/CRMEntity.php');
require_once('data/Tracker.php');

class EEAddressAutocomplete {

    /**
     * Invoked when special actions are performed on the module.
     *
     * @param String $moduleName
     * @param String $event_type
     */
    function vtlib_handler($moduleName, $event_type) {
        global $adb;
        $moduleInstance = Vtiger_Module::getInstance($moduleName);
        if($event_type == 'module.postinstall') {

            $this->createSettingField();

            // Initialize settings
            $sql = "INSERT INTO vtiger_ee_address_autocomplete_settings VALUES
                ('api_key', '')";
            $adb->pquery($sql,array());

            // Register header js script
            if($moduleInstance) {
                $moduleInstance->addLink('HEADERSCRIPT', 'EEAddressAutocompleteHeaderScript', 'layouts/vlayout/modules/Settings/EEAddressAutocomplete/resources/EEAddressAutocomplete.js');
            }

            // Adding new fields to Leads
            $this->addNewField('Leads', array(
                'block' => 'LBL_ADDRESS_INFORMATION',
                'label' => 'Google Search',
                'name' => 'ee_google_search',
                'table' => 'vtiger_leadscf',
                'column' => 'ee_google_search'
            ));

            // Adding new fields to Accounts
            $this->addNewField('Accounts', array(
                'block' => 'LBL_ADDRESS_INFORMATION',
                'label' => 'Google Search',
                'name' => 'ee_google_search',
                'table' => 'vtiger_accountscf',
                'column' => 'ee_google_search'
            ));

            // Adding new field to Contacts
            $this->addNewField('Contacts', array(
                'block' => 'LBL_ADDRESS_INFORMATION',
                'label' => 'Google Search',
                'name' => 'ee_google_search',
                'table' => 'vtiger_contactscf',
                'column' => 'ee_google_search'
            ));

        } else if($event_type == 'module.disabled') {

            // Unregister header js script
            if($moduleInstance) {
                $moduleInstance->deleteLink('HEADERSCRIPT', 'EEAddressAutocompleteHeaderScript', 'layouts/vlayout/modules/Settings/EEAddressAutocomplete/resources/EEAddressAutocomplete.js');
            }

            // There is no vtlib method so direct DB update
            $adb->pquery('UPDATE vtiger_settings_field SET active = 1 WHERE name = ?',array('Address Autocomplete'));

            // Disabled additional fields
            $this->setPresence('Leads', 'ee_google_search', 1);
            $this->setPresence('Accounts', 'ee_google_search', 1);
            $this->setPresence('Contacts', 'ee_google_search', 1);

        } else if($event_type == 'module.enabled') {

            // Register header js script
            if($moduleInstance) {
                $moduleInstance->addLink('HEADERSCRIPT', 'EEAddressAutocompleteHeaderScript', 'layouts/vlayout/modules/Settings/EEAddressAutocomplete/resources/EEAddressAutocomplete.js');
            }

            // There is no vtlib method so direct DB update
            $adb->pquery('UPDATE vtiger_settings_field SET active = 0 WHERE name = ?',array('Address Autocomplete'));

            // Enable additional fields
            $this->setPresence('Leads', 'ee_google_search', 2);
            $this->setPresence('Accounts', 'ee_google_search', 2);
            $this->setPresence('Contacts', 'ee_google_search', 2);

        } else if($event_type == 'module.preuninstall') {
            // TODO Handle actions when this module is about to be deleted.
        } else if($event_type == 'module.preupdate') {
            // TODO Handle actions before this module is updated.
        } else if($event_type == 'module.postupdate') {
            // TODO Handle actions after this module is updated.
        }

    }

    /**
     * Create Setting
     * ( There is no vtlib method so direct DB update )
     */
    function createSettingField() {
        global $adb;
        $sql = "set @lastfieldid = (select `id` from `vtiger_settings_field_seq`);";
        $adb->pquery($sql,array());
        $sql = "set @blockid = (select `blockid` from `vtiger_settings_blocks` where `label` = 'LBL_OTHER_SETTINGS');";
        $adb->pquery($sql,array());
        $sql = "set @maxseq = (select max(`sequence`) from `vtiger_settings_field` where `blockid` = @blockid);";
        $adb->pquery($sql,array());
        $sql = "INSERT INTO `vtiger_settings_field` (`fieldid`, `blockid`, `name`, `iconpath`, `description`, `linkto`, `sequence`, `active`) "
            . " VALUES (@lastfieldid+1, @blockid, 'Address Autocomplete', '', 'LBL_EE_ADDRESS_AUTOCOMPLETE_SETTINGS_DESCRIPTION', 'index.php?module=EEAddressAutocomplete&parent=Settings&view=Index', @maxseq+1, 0);";
        $adb->pquery($sql,array());
        $sql = "UPDATE `vtiger_settings_field_seq` SET `id` = @lastfieldid+1;";
        $adb->pquery($sql,array());
    }

    /**
     * Add new field to $module
     * @param string $module
     * @param array() $params
     */
    function addNewField($module, $params) {
        $moduleInstance = Vtiger_Module::getInstance($module);
        $blockInstance = Vtiger_Block::getInstance($params['block'], $moduleInstance);
        $fieldInstance = new Vtiger_Field();
        $fieldInstance->label = $params['label'];
        $fieldInstance->name = $params['name'];
        $fieldInstance->table = $params['table'];
        $fieldInstance->column = $params['column'];
		$fieldInstance->columntype = 'varchar(100)';
        $fieldInstance->uitype = 1;
        $fieldInstance->typeofdata = 'V~O';
        $blockInstance->addField($fieldInstance);
    }

    /**
     * Set presence to chosen field
     * @param string $module
     * @param string $field
     * @param int $presence
     */
    function setPresence($module, $field, $presence) {
        $moduleInstance = Vtiger_Module::getInstance($module);
        $fieldInstance = Vtiger_Field::getInstance($field, $moduleInstance);
        $fieldInstance->setPresence($presence);
        $fieldInstance->save();
    }
}

?>
