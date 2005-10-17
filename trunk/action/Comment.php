<?php
/**
 *  Comment.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  commentフォームの実装
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Comment extends Ethna_ActionForm
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
        'name' => array(
            'name' => 'Name',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),

        'comment' => array(
            'name' => 'Comment',
            'required' => true,
            'form_type' => FORM_TYPE_TEXTAREA,
            'type' => VAR_TYPE_STRING,
        ),

        'url' => array(
            'name' => 'Url',
            'required' => false,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),

        'submit' => array(
            'name' => 'Submit',
            'required' => true,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),
    );
}

/**
 *  commentアクションの実装
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Comment extends Ethna_ActionClass
{
    /**
     *  commentアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        //get query
        $request = explode('/', $_SERVER['PATH_INFO']);
        if ( is_numeric($request[2]) ){ 
         
            $this->af->setApp('id', $request[2]);
            
            if( $this->af->get('submit') ){
                
                if( $this->af->validate() > 0 ) {
                    return 'error';
                } else {
                    return null;
                }
            
            }
        
        }
 
        return 'error';
    }

    /**
     *  commentアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        
        //get query
        $request = explode('/', $_SERVER['PATH_INFO']);
        $id = $request[2];
 
        $DB = $this->backend->getDB();
        $Config = $this->backend->getConfig();
        $base_url = $Config->get('base_url');

        $DB->registerComment($id, $this->af->getArray());
        header("Location: {$base_url}");
        exit();

    }

}
?>
