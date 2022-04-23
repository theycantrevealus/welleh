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
import _0x26e7a from'@ckeditor/ckeditor5-core/src/command';import{getStyles as _0x707942}from'./utils';export default class c extends _0x26e7a{constructor(_0x22a79d){super(_0x22a79d),this['set']('isBusy',!0x1);}['refresh'](){this['isEnabled']=!this['isBusy'],this['value']=this['isBusy']?'pending':void 0x0;}['execute'](_0x38b443={}){const _0x23456c=this['editor'],t=_0x23456c['t'],_0x3e1848=_0x38b443['converterUrl']||'https://pdf-converter.cke-cs.com/v1/convert/',_0x1452ae=_0x38b443['dataCallback']||(_0x50aaac=>_0x50aaac['getData']());return this['isBusy']=!0x0,this['refresh'](),_0x707942(_0x38b443['stylesheets']||['EDITOR_STYLES'])['then'](_0x5b5fc0=>{const _0x535163={'html':'<html>\x0a\x09\x09\x09\x09\x09\x09<head>\x0a\x09\x09\x09\x09\x09\x09\x09<meta\x20charset=\x22utf-8\x22>\x0a\x09\x09\x09\x09\x09\x09</head>\x0a\x09\x09\x09\x09\x09\x09<body>\x0a\x09\x09\x09\x09\x09\x09\x09<div\x20class=\x22ck-content\x22\x20dir=\x22'+_0x23456c['locale']['contentLanguageDirection']+'\x22>\x0a\x09\x09\x09\x09\x09\x09\x09\x09'+_0x1452ae(_0x23456c)+'\x0a\x09\x09\x09\x09\x09\x09\x09</div>\x0a\x09\x09\x09\x09\x09\x09<body>\x0a\x09\x09\x09\x09\x09</html>','css':_0x5b5fc0,'options':_0x38b443['converterOptions']},_0x522bdc={'method':'POST','headers':{'Content-Type':'application/json','Accept':'application/pdf',..._0x38b443['token']&&{'Authorization':_0x38b443['token']['value']},'x-cs-app-id':_0x38b443['appID']||'cke5'},'body':JSON['stringify'](_0x535163)};return window['fetch'](_0x3e1848,_0x522bdc)['then'](_0x2d32eb=>{if(0xc8!==_0x2d32eb['status'])throw _0x2d32eb;return _0x2d32eb['blob']();})['then'](_0x50b41f=>{this['o'](_0x50b41f,_0x38b443['fileName']||'document.pdf');});})['catch'](_0x37d5ae=>{throw _0x23456c['plugins']['get']('Notification')['showWarning'](t('An\x20error\x20occurred\x20while\x20generating\x20the\x20PDF.')),_0x37d5ae;})['finally'](()=>{this['isBusy']=!0x1,this['refresh']();});}['o'](_0x262a15,_0x2738e8){const _0x5b13f7=document['createElement']('a');_0x5b13f7['href']=window['URL']['createObjectURL'](_0x262a15),_0x5b13f7['download']=_0x2738e8,_0x5b13f7['click'](),_0x5b13f7['remove']();}}