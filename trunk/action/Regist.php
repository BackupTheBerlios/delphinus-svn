<?php
/**
 *  Regist.php
 *
 *  @author     your name
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  registフォームの実装
 *
 *  @author     your name
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Regist extends Ethna_ActionForm
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
        'author' => array(
            'name' => 'author',
            'required' => false,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING
        ),
        
        'site_name' => array(
            'name' => 'site name',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING
        ),
        
        'url' => array(
            'name' => 'URL',
            'required' => true,
            'custom' => 'checkURL',
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING
        ),

        'submit' => array(
            'name' => 'submit',
            'required' => true,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING
        )
    );
}

/**
 *  registアクションの実装
 *
 *  @author     your name
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Regist extends Ethna_AuthActionClass
{
    /**
     *  registアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        return null;
    }

    /**
     *  registアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $DB = $this->backend->getDB();
        $Config = $this->backend->getConfig();
        $this->af->setApp('rss_list', $DB->getRssList());
        
        if ( is_null($this->af->get('submit')) ) {
            
            return 'regist';
        
        } else if ($this->af->validate() > 0) {
            
            //error
            return 'regist';
        
        } else {
        
            $rss_info = array(
                'name' => $this->af->get('site_name'),
                'url' => $this->af->get('url'),
                'author' => $this->af->get('author')
            );
            $DB->setRssList($rss_info);
            
            header('Location: '.$Config->get('base_url').'/regist');
            exit();
            
        }

        return 'regist';
    }

}
?>
