<?php
/**
 *  FeedDelete.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  FeedDeleteフォームの実装
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_FeedDelete extends Ethna_ActionForm
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
        'submit' => array(
            'name' => '削除する',
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING
        )
    );
}

/**
 *  FeedDeleteアクションの実装
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_FeedDelete extends Ethna_ActionClass
{
    /**
     * Database Object
     * @var     object
     * @access  protected
     */
    var $DB;
    
    /**
     *  FeedDeleteアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        $this->DB = $this->backend->getDB();
        $id = $this->getParameter();
        
        if ( is_numeric($id) && is_null($this->af->get('submit')) ) {
            $this->af->setApp('rss', $this->DB->getRssFromId($id));
            return 'feeddelete';
        } else {
        
            return null;

        }
    }

    /**
     *  FeedDeleteアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $Config = $this->backend->getConfig();

        $id = $this->getParameter();
        $this->DB->deleteFeed($id);
        
        header('Location: '.$Config->get('base_url').'/admin');
        exit();   
    }

    function getParameter()
    {
        $query = explode('/', $_SERVER['PATH_INFO']);
        return $query[2];
    }
}
?>
