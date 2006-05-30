<?php
/**
 *  Index.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id$
 */

/**
 *  indexフォームの実装
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Index extends Ethna_ActionForm
{
    /**
     *  @access private
     *  @var    array   フォーム値定義
     */
    var $form = array(
        /*
         *  TODO: このアクションが使用するフォーム値定義を記述してください
         *
         *  記述例(typeを除く全ての要素は省略可能)：
         *
         *  'sample' => array(
         *      'name'          => 'サンプル',      // 表示名
         *      'required'      => true,            // 必須オプション(true/false)
         *      'min'           => null,            // 最小値
         *      'max'           => null,            // 最大値
         *      'regexp'        => null,            // 文字種指定(正規表現)
         *      'custom'        => null,            // メソッドによるチェック
         *      'filter'        => null,            // 入力値変換フィルタオプション
         *      'form_type'     => FORM_TYPE_TEXT   // フォーム型
         *      'type'          => VAR_TYPE_INT,    // 入力値型
         *  ),
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
            'form_type' => FORM_TYPE_TEXT,
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
 *  indexアクションの実装
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Index extends Ethna_ActionClass
{
    /**
     *  indexアクションの前処理
     *
     *  @access public
     *  @return string      Forward先(正常終了ならnull)
     */
    function prepare()
    {
        $this->af->setApp('config', $this->config->config);
        return null;
    }

    /**
     *  indexアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $DB = $this->backend->getDB();
        $entries = $DB->getRecentEntries();
        $buf = array();
        foreach ( $entries as $entry) {
            $comments = $DB->getCommentsFromEntryId($entry['id']);
            $entry['comments'] = $comments;
            $buf[] = $entry;
        }
        $entries = $buf;
        
        $this->af->setApp('rss_list', $DB->getRssList());
        $this->af->setApp('entries', $entries);
        $this->af->setAppNE('entries', $entries);

        return 'index';
    }
}
?>
