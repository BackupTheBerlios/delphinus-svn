<?php
/**
 *  Admin.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  Adminフォームの実装
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Admin extends Ethna_ActionForm
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
 *  Adminアクションの実装
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Admin extends Ethna_AuthActionClass
{
    /**
     *  Adminアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
     */
    function prepare()
    {
        if ( 'crawl' == $this->getParameter() ) {
            
            $Controller = $this->backend->getController();
            $path_bin = $Controller->getDirectory('bin');
            system("php {$path_bin}/crawler.php");
            print('Crawl Finished.');
            exit();
        
        }
        return null;
    }

    /**
     *  Adminアクションの実装
     *
     *  @access public
     *  @return string  遷移名
     */
    function perform()
    {
        $DB = $this->backend->getDB();
        $this->af->setApp('rss_list', $DB->getRssList());
        return 'admin';
    }

    function getParameter()
    {
        if ( isset($_SERVER['PATH_INFO']) ) {
            $query = explode('/', $_SERVER['PATH_INFO']);
            $param = isset($query[2]) ? $query[2] : false;
            return $param;
        } else {
            return false;
        }    
    }
}
?>
