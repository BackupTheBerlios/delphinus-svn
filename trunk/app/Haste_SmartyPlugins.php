<?php
// vim: foldmethod=marker
/**
 *  Haste_SmartyPlugins.php
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @author     Masaki Fujimoto <fujimoto@php.net>
 *  @package    Haste
 *  @version    $Id: Sample_SmartyPlugin.php,v 1.1 2005/01/23 13:46:58 masaki-f Exp $
 */

if (!defined('FORM_TYPE_LIVESEARCH')) {
    define('FORM_TYPE_LIVESEARCH', '101');
} 

/**
 *  Haste_SmartyPlugins
 *
 *  @package Haste
 *  @author     halt feits <halt.feits@gmail.com>
 *  @access public
 */
class Haste_SmartyPlugins
{

// {{{ form_name
/**
 *  smarty function:フォーム表示名生成
 *
 *  @param  string  $name   フォーム項目名
 */
function form_name($params, &$smarty)
{
    extract($params);

    $ctl =& Ethna_Controller::getInstance();
    $ae =& $ctl->getActionError();

    // 現在アクティブなアクションフォーム以外のフォーム定義を
    // 利用する場合にはSmarty変数を仲介させる(いまいちか？)
    $app = $smarty->get_template_vars('app');
    if (isset($app['__def__']) && $app['__def__'] != null) {
        if (isset($app['__def__'][$name])) {
            $def = $app['__def__'][$name];
        }
    } else {
        $af =& $ctl->getActionForm();
        $def = $af->getDef($name);
    }

    if (is_null($def) || isset($def['name']) == false) {
        $form_name = $name;
    } else {
        $form_name = $def['name'];
    }

    if ($ae->isError($name)) {
        // 入力エラーの場合の表示
        print '<span class="error">' . $form_name . '</span>';
    } else {
        // 通常時の表示
        print $form_name;
    }
    if (isset($def['required']) && $def['required'] == true) {
        // 必須時の表示
        print '<span class="must">(*)</span>';
    }
}
// }}}

// {{{ form_input
/**
 *  smarty function:フォームタグ生成
 *
 *  結構適当です(ぉぃ
 *
 *  sample:
 *  <code>
 *  {form_input name="mailaddress" attr="..."}
 *  </code>
 *
 *  @param  string  $name   フォーム項目名
 */
function form_input($params, &$smarty)
{
    $c =& Ethna_Controller::getInstance();

    extract($params);

    $app = $smarty->get_template_vars('app');
    if (isset($app['__def__']) && $app['__def__'] != null) {
        if (isset($app['__def__'][$name])) {
            $def = $app['__def__'][$name];
        }
        $af =& $c->getActionForm();
    } else {
        $af =& $c->getActionForm();
        $def = $af->getDef($name);
    }

    if (isset($def['form_type']) == false) {
        $def['form_type'] = FORM_TYPE_TEXT;
    }

    //set default value
    if (isset($default)) {
        $af->set($name, $default);
    }

    switch ($def['form_type']) {
    
    case FORM_TYPE_FILE:
        $input = sprintf('<input type="file" name="%s"', $name);
        if (isset($attr)) {
            $input .= " $attr";
        }
        $input .= ">";
        break;
    
    case FORM_TYPE_TEXTAREA:
        $input = sprintf('<textarea name="%s"', $name);
        if (isset($attr)) {
            $input .= " $attr";
        }
        $input .= sprintf('>%s</textarea>', htmlspecialchars($af->get($name)));
        break;
    
    case FORM_TYPE_PASSWORD:
        $input = sprintf('<input type="password" name="%s" value="%s"', $name, htmlspecialchars($af->get($name)));
        if (isset($attr)) {
            $input .= " $attr";
        }
        if (isset($def['max']) && $def['max']) {
            $input .= sprintf(' maxlength="%d"', $def['max']);
        }
        $input .= ">";
        break;
    
    case FORM_TYPE_CHECKBOX:

        $input = sprintf('<input type="checkbox" name="%s" value="%s"', $name, htmlspecialchars($af->get($name)));

        if (isset($attr)) {
            $input .= " $attr";
        }
        if (isset($def['max']) && $def['max']) {
            $input .= sprintf(' maxlength="%d"', $def['max']);
        }
        $input .= ">";
        break;
    
    case FORM_TYPE_SUBMIT:

        if ( $def['name'] != "" ) {
            $input = "<input type=\"submit\" name=\"{$name}\" value=\"{$def['name']}\" />";
        } else {
            $input = "<input type=\"submit\" name=\"{$name}\" />";
        }
        break;

    case FORM_TYPE_LIVESEARCH:
    
        $input = sprintf('<input type="text" autocomplete="off" class="live_search" id="%s" name="%s" value="%s"', $name, $name, htmlspecialchars($af->get($name)));
        if (isset($attr)) {
            $input .= " $attr";
        }
        if (isset($def['max']) && $def['max']) {
            $input .= sprintf(' maxlength="%d"', $def['max']);
        }
        
        $input .= " />\n";
        $input .= "<div class=\"auto_complete\" id=\"{$name}_list\"></div>\n";
        $input .= "<script type=\"text/javascript\">\n";
        $input .= "var auto = new Ajax.Autocompleter('{$name}','{$name}_list','{$def['receiver']}/{$name}',{});\n";
        $input .= "auto.select_entry = select_entry;\n";
        $input .= "</script>\n";
 
        break;
        
    case FORM_TYPE_TEXT:
        // fall thru
    
    default:

        //support multi value(alpha
        if ( is_array($af->get($name)) ) {
            
            $input = "";
            $values = $af->get($name);
            foreach ( $values as $value ) {
                
                $input .= sprintf('<input type="text" name="%s[]" value="%s"', $name, htmlspecialchars($value, ENT_QUOTES));
                
                if (isset($attr)) {
                    $input .= " $attr";
                }
                if (isset($def['max']) && $def['max']) {
                    $input .= sprintf(' maxlength="%d"', $def['max']);
                }
                $input .= " /><br />";

            }

            break;
 
        }

        $input = sprintf('<input type="text" name="%s" value="%s"', $name, htmlspecialchars($af->get($name)));
        if (isset($attr)) {
            $input .= " $attr";
        }
        if (isset($def['max']) && $def['max']) {
            $input .= sprintf(' maxlength="%d"', $def['max']);
        }
        $input .= " />";
        break;
    }

    print $input;
}
// }}}

    //{{{ rss
    /**
     * rss
     *
     * @access public
     * @author halt <halt.hde@gmail.com>
     */
    function rss($params, $smarty)
    {
        require_once "Cache/Lite.php";

        if (isset($params['encoding_from'])) {
            $encoding_from = strtoupper($params['encoding_from']);
        } else {
            $encoding_from = 'UTF-8';
        }
        
        if (isset($params['encoding_to'])) {
            $encoding_to = strtoupper($params['encoding_to']);
        } else {
            $encoding_to = 'EUC-JP';
        }

        $Controller =& Ethna_Controller::getInstance();
        $dir_cache = $Controller->getDirectory('tmp');
        $options = array(
            'cacheDir' => $dir_cache . '/',
            'lifeTime' => 3600
        );
        $CacheLite = new Cache_Lite($options);
        
        $url = $params['url'];
        
        if ( $data = $CacheLite->get($url)) {
            print($data);
        } else {
            
            $ret[] = '<ul class="plugin_rss">';
            $xml = @simplexml_load_file($url);
            
            if ( $xml == false) {
                $buf = "<ul>\n";
                $buf .= "<li>RSSを取得できません。</li>\n";
                $buf .= "</ul>\n";

                if ($encoding_to != $encoding_from) {
                    $buf = mb_convert_encoding($buf, $encoding_to, $encoding_from);
                }
                print($buf);
                return false;
            }
            
            foreach($xml->item as $item){
            
                /**
                 * Namespace付きの子要素を取得
                 * この場合、<dc:date>要素が対象
                 */
                $dc = $item->children('http://purl.org/dc/elements/1.1/');
            
                $date = isset($dc->date) ? '&nbsp;(' . date('Y-m-d H:i', strtotime($dc->date)) . ')' : '';
                $link = str_replace('&', '&amp;', $item->link);
                $title = mb_convert_encoding($item->title, 'UTF-8', 'auto');
                $line = '<li>';
                $line.= "<a href=\"{$link}\">{$title}</a>";
                $line.= '</li>';

                $ret[] = $line;
            }

            $ret[] = '</ul>';
            $data = join("\n", $ret);
            if ($encoding_to != $encoding_from) {
                $data = mb_convert_encoding($data, $encoding_to, $encoding_from);
            }
            $CacheLite->save($data);
            print($data);
        }
 
    }
    //}}}

}

?>
