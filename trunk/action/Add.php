<?php
/**
 *  Add.php
 *
 *  @author     your name
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  addフォームの実装
 *
 *  @author     your name
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Add extends Ethna_ActionForm
{
    /**
     *  @access private
     *  @var    array   フォーム値定義
     */
    var $form = array(
        /*
        'sample' => array(
            'name'          => 'サンプル',      // 表示名
            'required'      => true,            // 必須オプション(true/false)
            'min'           => null,            // 最小値
            'max'           => null,            // 最大値
            'regexp'        => null,            // 文字種指定(正規表現)
            'custom'        => null,            // メソッドによるチェック
            'filter'        => null,            // 入力値変換フィルタオプション
            'form_type'     => FORM_TYPE_TEXT   // フォーム型
            'type'          => VAR_TYPE_INT,    // 入力値型
        ),
        */
    );
}

/**
 *  addアクションの実装
 *
 *  @author     your name
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Add extends Ethna_ActionClass
{
    /**
     *  addアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        return null;
    }

    /**
     *  addアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        return 'add';
    }
}
?>
