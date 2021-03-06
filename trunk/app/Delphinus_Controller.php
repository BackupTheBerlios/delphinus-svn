<?php
/**
 *  Delphinus_Controller.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id: app.controller.php,v 1.11 2005/04/07 11:00:00 fujimoto Exp $
 */

/** アプリケーションベースディレクトリ */
define('BASE', dirname(dirname(__FILE__)));

// include_pathの設定(アプリケーションディレクトリを追加)
$include_path = array(
    'app' => BASE . "/app",
    'lib' => BASE . "/lib",
    'include_path' => ini_get('include_path'),
);

ini_set('include_path', implode(PATH_SEPARATOR, $include_path));

/** アプリケーションライブラリのインクルード */
include_once('Ethna/Ethna.php');
require_once 'Ethna_AuthActionClass.php';
include_once('Delphinus_Error.php');
require_once 'Delphinus_DB.class.php';
require_once 'Haste_SmartyPlugins.php';
require_once 'Haste_ActionForm.php';
require_once 'Aero_Util.php';

/**
 *  Delphinusアプリケーションのコントローラ定義
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Controller extends Ethna_Controller
{
    /**#@+
     *  @access private
     */

    /**
     *  @var    string  アプリケーションID
     */
    var $appid = 'DELPHINUS';

    /**
     *  @var    array   forward定義
     */
    var $forward = array(
        /*
         *  TODO: ここにforward先を記述してください
         *
         *  記述例：
         *
         *  'index'         => array(
         *      'view_name' => 'Delphinus_View_Index',
         *  ),
         */
    );

    /**
     *  @var    array   action定義
     */
    var $action = array(
        /*
         *  TODO: ここにaction定義を記述してください
         *
         *  記述例：
         *
         *  'index'     => array(),
         */
    );

    /**
     *  @var    array   soap action定義
     */
    var $soap_action = array(
        /*
         *  TODO: ここにSOAPアプリケーション用のaction定義を
         *  記述してください
         *  記述例：
         *
         *  'sample'            => array(),
         */
    );

    /**
     *  @var    array       アプリケーションディレクトリ
     */
    var $directory = array(
        'action'        => 'action',
        'action_cli'    => 'action_cli',
        'action_xmlrpc' => 'action_xmlrpc',
        'etc'           => 'etc',
        'filter'        => 'app/filter',
        'locale'        => 'locale',
        'log'           => 'log',
        'plugins'       => array(),
        'template'      => 'template',
        'template_c'    => 'tmp',
        'tmp'           => 'tmp',
        'view'          => 'app/view',
        'bin'           => 'bin'
    );

    /**
     *  @var    array       DBアクセス定義
     */
    var $db = array(
        ''              => DB_TYPE_RW,
    );

    /**
     *  @var    array       拡張子設定
     */
    var $ext = array(
        'php'           => 'php',
        'tpl'           => 'tpl',
    );

    /**
     *  @var    array   クラス定義
     */
    var $class = array(
        /*
         *  TODO: 設定クラス、ログクラス、SQLクラスをオーバーライド
         *  した場合は下記のクラス名を忘れずに変更してください
         */
        'class'         => 'Ethna_ClassFactory',
        'backend'       => 'Ethna_Backend',
        'config'        => 'Ethna_Config',
        'db'            => 'Delphinus_DB',
        'error'         => 'Ethna_ActionError',
        'form'          => 'Haste_ActionForm',
        'i18n'          => 'Ethna_I18N',
        'logger'        => 'Ethna_Logger',
        'session'       => 'Ethna_Session',
        'sql'           => 'Ethna_AppSQL',
        'view'          => 'Ethna_ViewClass',
    );

    /**
     *  @var    array       フィルタ設定
     */
    var $filter = array(
        /*
         *  TODO: フィルタを利用する場合はここにそのクラス名を
         *  記述してください
         *
         *  記述例：
         *
         *  'Delphinus_Filter_ExecutionTime',
         */
    );

    /**
     *  @var    array   マネージャ一覧
     */
    var $manager = array(
        /*
         *  TODO: ここにアプリケーションのマネージャオブジェクト一覧を
         *  記述してください
         *
         *  記述例：
         *
         *  'um'    => 'User',
         */
    );

    /**
     *  @var    array   smarty modifier定義
     */
    var $smarty_modifier_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty modifier一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_modifier_foo_bar',
         */
    );

    /**
     *  @var    array   smarty function定義
     */
    var $smarty_function_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty function一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_function_foo_bar',
         */
         array('Haste_SmartyPlugins', 'form_name'),
         array('Haste_SmartyPlugins', 'form_input'),
         array('Haste_SmartyPlugins', 'rss'),
                 
    );

    /**
     *  @var    array   smarty prefilter定義
     */
    var $smarty_prefilter_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty prefilter一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_prefilter_foo_bar',
         */
    );

    /**
     *  @var    array   smarty postfilter定義
     */
    var $smarty_postfilter_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty postfilter一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_postfilter_foo_bar',
         */
    );

    /**
     *  @var    array   smarty outputfilter定義
     */
    var $smarty_outputfilter_plugin = array(
        /*
         *  TODO: ここにユーザ定義のsmarty outputfilter一覧を記述してください
         *
         *  記述例：
         *
         *  'smarty_outputfilter_foo_bar',
         */
    );

    /**#@-*/

    /**
     *  遷移時のデフォルトマクロを設定する
     *
     *  @access protected
     *  @param  object  Smarty  $smarty テンプレートエンジンオブジェクト
     */
    function _setDefaultTemplateEngine(&$smarty)
    {
        /*
         *  TODO: ここでテンプレートエンジンの初期設定や
         *  全てのビューに共通なテンプレート変数を設定します
         *
         *  記述例：
         * $smarty->assign_by_ref('session_name', session_name());
         * $smarty->assign_by_ref('session_id', session_id());
         *
         * // ログインフラグ(true/false)
         * $session =& $this->getClassFactory('session');
         * if ($session && $this->session->isStart()) {
         *  $smarty->assign_by_ref('login', $session->isStart());
         * }
         */
        $Config = $this->getConfig();
        $smarty->assign('title', $Config->get('title') );
        $smarty->assign('base_url', $Config->get('base_url') );
        $smarty->assign('copyright', $Config->get('copyright') );
    }
    
    /**
     *  フォームにより要求されたアクション名を返す
     *
     *  @access protected
     *  @return string  フォームにより要求されたアクション名
     */
    function _getActionName_Form()
    {
        
        if ( isset($_SERVER['PATH_INFO']) ) {
            $arr = explode('/', $_SERVER['PATH_INFO']);
            $action_name = $arr[1];
        } else {
            $action_name = false;
        }

        return $action_name;
    
    }

    /**
     * getTemplateDir
     *
     */
    function getTemplatedir()
    {
        $template = $this->getDirectory('template');
        $Config = $this->getConfig();
        $theme = $Config->get('theme');
        
        if ( !$theme ) {
            $theme = 'default';
        }
        
        if ( file_exists( "{$template}/{$theme}") ) {
            $template .= "/{$theme}";
        }

        return $template;
    }

}
?>
