<?php

namespace Mindy\Form\Fields;

/**
 * Class TinymceField
 * @package Mindy\Form
 */
class TinymceField extends TextAreaField
{
    public function render()
    {
        $id = $this->getHtmlId();
        $js = "<script type='text/javascript'>
        tinyMCE.init({
            mode: 'exact',
            elements: '{$id}',
            theme: 'advanced',
            language : 'ru',
            width: '100%',
            height: '400px',
            plugins: 'watermark,jaretypograph,youtube,images,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,inlinepopups,insertdatetime,media,contextmenu,paste,directionality,fullscreen,noneditable,nonbreaking,xhtmlxtras,template,loremipsum',
            theme_advanced_resizing : true,
            theme_advanced_resize_horizontal : 0,
            theme_advanced_resizing_use_cookie : 0,
            pagebreak_separator : '<!--pagebreak-->',
            theme_advanced_path : false,
            theme_advanced_buttons1 : 'undo,redo,|,bold,italic,underline,strikethrough,|,sub,sup,|,bullist,numlist,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect,|,forecolor,backcolor,fullscreen',
            theme_advanced_buttons2 : 'paste,pastetext,pasteword,|,tablecontrols,|,hr,removeformat,|,youtube,images,image,|,pagebreak,outdent,indent,blockquote,|,link,unlink,cleanup,code,watermark,jaretypograph,loremipsum',
            theme_advanced_buttons3 : '',
            theme_advanced_toolbar_location : 'top',
            theme_advanced_toolbar_align : 'left',
            theme_advanced_statusbar_location : 'bottom',
            dialog_type : 'modal',
            relative_urls : false,
            remove_script_host : true,
            paste_auto_cleanup_on_paste : true,
            tab_focus : ':prev,:next',
            skin : 'o2k7',
            skin_variant : 'silver'
        });
        </script>";
        return parent::render() . $js;
    }
}
