<?php
// vim: foldmethod=marker
/**
 *  Sample_SmartyPlugin.php
 *
 *  @author     Masaki Fujimoto <fujimoto@php.net>
 *  @package    Sample
 *  @version    $Id: Sample_SmartyPlugin.php,v 1.1 2005/01/23 13:46:58 masaki-f Exp $
 */

// {{{ smarty_function_form_name
/**
 *  smarty function:�ե�����ɽ��̾����
 *
 *  @param  string  $name   �ե��������̾
 */
function smarty_function_form_name($params, &$smarty)
{
    extract($params);

    $ctl =& Ethna_Controller::getInstance();
    $ae =& $ctl->getActionError();

    // ���ߥ����ƥ��֤ʥ��������ե�����ʳ��Υե����������
    // ���Ѥ�����ˤ�Smarty�ѿ�����𤵤���(���ޤ�������)
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
        // ���ϥ��顼�ξ���ɽ��
        print '<span class="error">' . $form_name . '</span>';
    } else {
        // �̾����ɽ��
        print $form_name;
    }
    if (isset($def['required']) && $def['required'] == true) {
        // ɬ�ܻ���ɽ��
        print '<span class="must">(*)</span>';
    }
}
// }}}

// {{{ smarty_function_form_input
/**
 *  smarty function:�ե����ॿ������
 *
 *  �빽Ŭ���Ǥ�(����
 *
 *  sample:
 *  <code>
 *  {form_input name="mailaddress" attr="..."}
 *  </code>
 *
 *  @param  string  $name   �ե��������̾
 */
function smarty_function_form_input($params, &$smarty)
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

    switch ($def['form_type']) {
    
    case FORM_TYPE_FILE:
        $input = sprintf('<input type="file" name="%s"', $name);
        if ($attr) {
            $input .= " $attr";
        }
        $input .= ">";
        break;
    
    case FORM_TYPE_TEXTAREA:
        $input = sprintf('<textarea name="%s"', $name);
        if ($attr) {
            $input .= " $attr";
        }
        $input .= sprintf('>%s</textarea>', htmlspecialchars($af->get($name)));
        break;
    
    case FORM_TYPE_PASSWORD:
        $input = sprintf('<input type="password" name="%s" value="%s"', $name, htmlspecialchars($af->get($name)));
        if ($attr) {
            $input .= " $attr";
        }
        if (isset($def['max']) && $def['max']) {
            $input .= sprintf(' maxlength="%d"', $def['max']);
        }
        $input .= ">";
        break;
    
    case FORM_TYPE_CHECKBOX:

        $input = sprintf('<input type="checkbox" name="%s" value="%s"', $name, htmlspecialchars($af->get($name)));

        if ($attr) {
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

    case FORM_TYPE_TEXT:
        // fall thru
    
    default:
        $input = sprintf('<input type="text" name="%s" value="%s"', $name, htmlspecialchars($af->get($name)));
        if ($attr) {
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

//{{{ smarty_function_rss
/**
 * smarty_function_rss
 *
 * @access public
 * @author halt <halt.hde@gmail.com>
 */
function smarty_function_rss($params, $smarty)
{
        $url = $params['url'];
        $ret[] = '<ul class="plugin_rss">';
        $xml = simplexml_load_file($url);
        foreach($xml->item as $item){
            /**
             * Namespace�դ��λ����Ǥ����
             * ���ξ�硢<dc:date>���Ǥ��о�
             */
            $dc = $item->children('http://purl.org/dc/elements/1.1/');
            
            $date = isset($dc->date) ? '&nbsp;(' . date('Y-m-d H:i', strtotime($dc->date)) . ')' : '';
            $link = $item->link;
            $title = mb_convert_encoding($item->title, 'UTF-8', 'auto');
            $line = '<li>';
            //$line.= "<a href=\"{$link}\">{$title}</a>" . $date;
            $line.= "<a href=\"{$link}\">{$title}</a>";
            $line.= '</li>';

            $ret[] = $line;
        }

        $ret[] = '</ul>';
        print(join("\n", $ret));
 
}
//}}}
?>
