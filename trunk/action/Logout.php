<?php
/**
 *  Logout.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  logoutフォームの実装
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Logout extends Ethna_ActionForm
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
 *  logoutアクションの実装
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Logout extends Ethna_ActionClass
{
    /**
     *  logoutアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        return null;
    }

    /**
     *  logoutアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        unset($_SESSION['name']);
        $Config = $this->backend->getConfig();
        
        $typekey_url = $Config->get('typekey_url');
        $typekey_token = $Config->get('typekey_token');
    
        $Session = $this->backend->getSession();
        $Session->destroy();

        $tk = new Auth_TypeKey();
        $tk->site_token($typekey_token);
        
        $signin_url = $tk->urlSignIn($typekey_url);
        $signout_url = $tk->urlSignOut($Config->get('base_url'));
        
        header('Location: ' . $signout_url);
        exit();

    }
}
?>
