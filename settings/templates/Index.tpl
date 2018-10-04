{*<!--
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is: EntExt
 * The Initial Developer of the Original Code is EntExt.
 * All Rights Reserved.
 * If you have any questions or comments, please email: devel@entext.com
 ************************************************************************************/
-->*}
{strip}
    <div class="container-fluid" id="addressAutocompleteSettings">
        <div class="widget_header row-fluid">
            <div class="span8"><h3>{vtranslate('LBL_EE_ADDRESS_AUTOCOMPLETE_SETTINGS', $QUALIFIED_MODULE)}</h3></div>
            {assign var=MODULE_MODEL value=Settings_EEAddressAutocomplete_Module_Model::getCleanInstance()}
            <div class="span4"><div class="pull-right"><button class="btn editButton" data-url='{$MODULE_MODEL->getEditViewUrl()}' type="button" title="{vtranslate('LBL_EDIT', $QUALIFIED_MODULE)}"><strong>{vtranslate('LBL_EDIT', $QUALIFIED_MODULE)}</strong></button></div></div>
        </div>
        <hr>
        <div class="contents row-fluid">
            <table class="table table-bordered table-condensed themeTableColor">
                <thead>
                <tr class="blockHeader">
                    <th colspan="2" class="mediumWidthType">
                        <span class="alignMiddle">{vtranslate('LBL_EE_ADDRESS_AUTOCOMPLETE_CONFIG', $QUALIFIED_MODULE)}</span>
                    </th>
                </tr>
                </thead>
                <tbody>
                {assign var=FIELDS value=Settings_EEAddressAutocomplete_Module_Model::getSettingsParameters()}
                {foreach item=FIELD_TYPE key=FIELD_NAME from=$FIELDS}
                    <tr><td width="25%"><label class="muted pull-right marginRight10px">{vtranslate($FIELD_NAME, $QUALIFIED_MODULE)}</label></td>
                        <td style="border-left: none;"><span>{$RECORD_MODEL->get($FIELD_NAME)}</span></td></tr>
                {/foreach}
                <input type="hidden" name="module" value="EEAddressAutocomplete"/>
                <input type="hidden" name="action" value="SaveAjax"/>
                <input type="hidden" name="parent" value="Settings"/>
                </tbody>
            </table>
        </div>
    </div>
{/strip}
