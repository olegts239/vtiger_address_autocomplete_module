<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: EntExt
 * The Initial Developer of the Original Code is EntExt.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@entext.com
 ************************************************************************************/

class Settings_EEAddressAutocomplete_Record_Model extends Settings_Vtiger_Record_Model {

    const TABLE_NAME = 'vtiger_ee_address_autocomplete_settings';

    public function getId() {
    }

    public function getName() {
    }

    public function getModule() {
        return new Settings_EEAddressAutocomplete_Module_Model;
    }

    static function getCleanInstance() {
        return new self;
    }

    public static function getInstance() {
        $model = new self();
        $db = PearDatabase::getInstance();
        $query = 'SELECT * FROM '.self::TABLE_NAME;
        $result = $db->pquery($query, array());
        $resultCount = $db->num_rows($result);
        if($resultCount > 0) {
            for ($i = 0; $i < $resultCount; $i++) {
                $key = $db->query_result($result, $i, 'key');
                $value = $db->query_result($result, $i, 'value');
                $model->set($key, $value);
            }
            return $model;
        }
        return $model;
    }

    public function save() {
        $db = PearDatabase::getInstance();
        $parameters = '';
        $model = new Settings_EEAddressAutocomplete_Module_Model;
        foreach ($model->getSettingsParameters() as $field => $type) {
            $query = 'UPDATE '.self::TABLE_NAME.' SET `value` = ? WHERE `key` = ?';
            $db->pquery($query, array($this->get($field), $field));
            $parameters[$field] = $this->get($field);
        }
    }
}