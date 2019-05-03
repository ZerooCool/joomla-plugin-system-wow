/**
 * @author      Branko Wilhelm <branko.wilhelm@gmail.com>
 * @link        http://www.z-index.net
 * @copyright   (c) 2013 - 2016 Branko Wilhelm
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

window.wow = window.wow || {};
window.wow.ajax = function () {
    jQuery('div[class^=mod_wo].ajax').each(function () {
        var module = jQuery(this), name = module.attr('class').split(' ')[0];
        jQuery.ajax({
            url: window.wow.base,
            data: {
                option: 'com_ajax',
                module: name.replace('mod_', ''),
                format: 'raw',
                Itemid: window.wow.Itemid
            },
            dataType: 'html',
            success: function (html) {
                module.replaceWith(html);
                jQuery('.' + name).hide().fadeIn();
                (typeof window.wow[name] == 'function') ? window.wow[name]() : '';
            }
        });
    });
};

if (typeof jQuery !== 'undefined') {
    jQuery(document).ready(window.wow.ajax);
}