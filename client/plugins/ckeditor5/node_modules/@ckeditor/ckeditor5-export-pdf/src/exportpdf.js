/*
 *             Copyright (c) 2016 - 2020, CKSource - Frederico Knabben. All rights reserved.
 *
 *
 *
 *
 *          +---------------------------------------------------------------------------------+
 *          |                                                                                 |
 *          |                                 Hello stranger!                                 |
 *          |                                                                                 |
 *          |                                                                                 |
 *          |   What you're currently looking at is the source code of a legally protected,   |
 *          |    proprietary software. Any attempts to deobfuscate / disassemble this code    |
 *          |               are forbidden and will result in legal consequences.              |
 *          |                                                                                 |
 *          |                                                                                 |
 *          +---------------------------------------------------------------------------------+
 *
 *
 *
 *
 */
import _0x82955f from'@ckeditor/ckeditor5-core/src/plugin';import _0x10d841 from'@ckeditor/ckeditor5-ui/src/button/buttonview';import _0x4a2abf from'@ckeditor/ckeditor5-ui/src/view';import _0x11620b from'@ckeditor/ckeditor5-ui/src/notification/notification';import _0x12adc7 from'@ckeditor/ckeditor5-cloud-services/src/cloudservices';import _0x16a505 from'./exportpdfcommand';import _0x489d67 from'../theme/icons/exportpdf.svg';import'../theme/exportpdf.css';export default class p extends _0x82955f{static get['pluginName'](){return'ExportPdf';}static get['requires'](){return[_0x11620b,_0x12adc7];}['init'](){const _0x1af5e3=this['editor'],t=_0x1af5e3['t'],_0x303de2=_0x1af5e3['config']['get']('exportPdf')||{},_0xc92f7b=_0x1af5e3['plugins']['get']('CloudServices')['token'];_0x1af5e3['commands']['add']('exportPdf',new _0x16a505(_0x1af5e3)),_0x1af5e3['ui']['componentFactory']['add']('exportPdf',_0x1174e9=>{const _0x10ad2b=_0x1af5e3['commands']['get']('exportPdf'),_0x2f8aee=new _0x10d841(_0x1174e9);_0x2f8aee['set']({'label':t('Export\x20to\x20PDF'),'icon':_0x489d67,'tooltip':!0x0}),_0x2f8aee['bind']('isOn','isEnabled')['to'](_0x10ad2b,'isBusy','isEnabled'),_0x2f8aee['extendTemplate']({'attributes':{'class':[_0x2f8aee['bindTemplate']['if']('isOn','ck-exportpdf_status-pending')]}});const _0x14e748=new _0x4a2abf();return _0x14e748['setTemplate']({'tag':'span','attributes':{'class':['ck','ck-exportpdf__spinner-container']},'children':[{'tag':'span','attributes':{'class':['ck','ck-exportpdf__spinner']}}]}),_0x2f8aee['children']['add'](_0x14e748),this['listenTo'](_0x2f8aee,'execute',()=>{_0x1af5e3['execute']('exportPdf',{..._0x303de2,..._0xc92f7b&&{'token':_0xc92f7b}}),_0x1af5e3['editing']['view']['focus']();}),_0x2f8aee;});}}