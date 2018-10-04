{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: EntExt
 * The Initial Developer of the Original Code is EntExt.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@entext.com
 ************************************************************************************}
{strip}
    <div class="col-sm-12 col-xs-12">
        <div class="container-fluid" id="addressAutocompleteSettings">
            <div class="widget_header row">
                <div class="col-sm-8"><h4>{vtranslate('LBL_EE_ADDRESS_AUTOCOMPLETE_SETTINGS', $QUALIFIED_MODULE)}</h4></div>
                {assign var=MODULE_MODEL value=Settings_EEAddressAutocomplete_Module_Model::getCleanInstance()}
                <div class="col-sm-4">
                    <div class="clearfix">
                        <div class="btn-group pull-right editbutton-container">
                            <button class="btn btn-default editButton" data-url='{$MODULE_MODEL->getEditViewUrl()}' type="button" title="{vtranslate('LBL_EDIT', $QUALIFIED_MODULE)}">{vtranslate('LBL_EDIT', $QUALIFIED_MODULE)}</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="contents col-lg-12">
                <table class="table detailview-table no-border">
                    <tbody>
                    {assign var=FIELDS value=Settings_EEAddressAutocomplete_Module_Model::getSettingsParameters()}
                    {foreach item=FIELD_TYPE key=FIELD_NAME from=$FIELDS}
                        <tr>
                            <td class="fieldLabel" style="width:25%"><label>{vtranslate($FIELD_NAME, $QUALIFIED_MODULE)}</label></td>
                            <td style="word-wrap:break-word;"><span>{$RECORD_MODEL->get($FIELD_NAME)}</span></td></tr>
                    {/foreach}
                    <input type="hidden" name="module" value="EEAddressAutocomplete"/>
                    <input type="hidden" name="action" value="SaveAjax"/>
                    <input type="hidden" name="parent" value="Settings"/>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-12 col-xs-12">
            <div class="col-sm-8 col-xs-8">
                <div class="alert alert-info container-fluid">
                    <a target="_blank" href="http://entext.org/address-autocomplete/">{vtranslate('LBL_HOW_TO_GET_API_KEY', $QUALIFIED_MODULE)}</a>
                </div>
            </div>
        </div>
    </div>
{/strip}