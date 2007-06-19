<?php
// vim: foldmethod=marker
/**
 *  Haste_ActionForm.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @license    http://www.opensource.org/licenses/bsd-license.php The BSD License
 *  @package    Haste
 *  @version    $Id: Ethna_ActionForm.php,v 1.21 2005/09/22 03:32:51 fujimoto Exp $
 */

if ( !defined('FORM_LIVESEARCH') ) {
    define('FORM_LIVESEARCH', 9);
}

// {{{ Haste_ActionForm
/**
 *  アクションフォームクラス
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Haste
 */
class Haste_ActionForm extends Ethna_ActionForm
{
    /**#@+
     *  @access private
     */

    /** @var    array   フォーム定義要素 */
    var $def = array(
        'name',
        'required',
        'max',
        'min',
        'regexp',
        'custom',
        'filter',
        'form_type',
        'type',
        'receiver'
        );

    var $form_template = array(
        'submit' => array(
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        )
    );

}
// }}}
?>
