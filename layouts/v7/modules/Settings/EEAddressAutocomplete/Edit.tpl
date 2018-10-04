{*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: EntExt
 * The Initial Developer of the Original Code is EntExt.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@entext.com
 ************************************************************************************}
{strip}
    <div class="widget_header col-lg-12">
        <h4>{vtranslate('LBL_EE_ADDRESS_AUTOCOMPLETE_SETTINGS', $QUALIFIED_MODULE)}</h4>
        <hr>
    </div>
    <div class="container-fluid">
        {assign var=MODULE_MODEL value=Settings_EEAddressAutocomplete_Module_Model::getCleanInstance()}
        <form id="addressAutocompleteModal" class="form-horizontal" data-detail-url="{$MODULE_MODEL->getDetailViewUrl()}">
            <input type="hidden" name="module" value="EEAddressAutocomplete"/>
            <input type="hidden" name="action" value="SaveAjax"/>
            <input type="hidden" name="parent" value="Settings"/>
            <div class="blockData">
                <table class="table detailview-table no-border">
                    <tbody>
                    {assign var=FIELDS value=Settings_EEAddressAutocomplete_Module_Model::getSettingsParameters()}
                    {foreach item=FIELD_TYPE key=FIELD_NAME from=$FIELDS}
                        <tr>
                            <td class="fieldLabel control-label" style="width:25%">
                                <label>{vtranslate($FIELD_NAME,$QUALIFIED_MODULE)}&nbsp;<span class="redColor">*</span></label>
                            </td>
                            <td style="word-wrap:break-word;">
                                <input class="inputElement fieldValue" type="{$FIELD_TYPE}" name="{$FIELD_NAME}" data-rule-required="true" value="{$RECORD_MODEL->get($FIELD_NAME)}" />
                            </td>
                        </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
            <div class="modal-overlay-footer clearfix">
                <div class="row clearfix">
                    <div class="textAlignCenter col-lg-12 col-md-12 col-sm-12">
                        <button type="submit" class="btn btn-success saveButton">{vtranslate('LBL_SAVE', $QUALIFIED_MODULE)}</button>&nbsp;&nbsp;
                        <a class="cancelLink" data-dismiss="modal" href="#">{vtranslate('LBL_CANCEL', $QUALIFIED_MODULE)}</a>
                    </div>
                </div>
            </div>
        </form>
        <div class="col-sm-12 col-xs-12">
            <div class="col-sm-8 col-xs-8">
                <div class="alert alert-info container-fluid">
                    <a target="_blank" href="http://entext.org/address-autocomplete/">{vtranslate('LBL_HOW_TO_GET_API_KEY', $QUALIFIED_MODULE)}</a>
                </div>
            </div>
        </div>
    </div>
{/strip}