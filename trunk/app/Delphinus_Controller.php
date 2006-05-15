<?php
/**
 *  Delphinus_Controller.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id: app.controller.php,v 1.11 2005/04/07 11:00:00 fujimoto Exp $
 */

/** ���ץꥱ�������١����ǥ��쥯�ȥ� */
define('BASE', dirname(dirname(__FILE__)));

// include_path������(���ץꥱ�������ǥ��쥯�ȥ���ɲ�)
$app = BASE . "/app";
$lib = BASE . "/lib";
ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . implode(PATH_SEPARATOR, array($app, $lib)));


/** ���ץꥱ�������饤�֥��Υ��󥯥롼�� */
include_once('Ethna/Ethna.php');
require_once 'Ethna_AuthActionClass.php';
include_once('Delphinus_Error.php');
require_once 'Delphinus_DB.class.php';
require_once 'Haste_SmartyPlugins.php';
require_once 'Haste_ActionForm.php';

/**
 *  Delphinus���ץꥱ�������Υ���ȥ������
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
     *  @var    string  ���ץꥱ�������ID
     */
    var $appid = 'DELPHINUS';

    /**
     *  @var    array   forward���
     */
    var $forward = array(
        /*
         *  TODO: ������forward��򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'index'         => array(
         *      'view_name' => 'Delphinus_View_Index',
         *  ),
         */
    );

    /**
     *  @var    array   action���
     */
    var $action = array(
        /*
         *  TODO: ������action����򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'index'     => array(),
         */
    );

    /**
     *  @var    array   soap action���
     */
    var $soap_action = array(
        /*
         *  TODO: ������SOAP���ץꥱ��������Ѥ�action�����
         *  ���Ҥ��Ƥ�������
         *  �����㡧
         *
         *  'sample'            => array(),
         */
    );

    /**
     *  @var    array       ���ץꥱ�������ǥ��쥯�ȥ�
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
     *  @var    array       DB�����������
     */
    var $db = array(
        ''              => DB_TYPE_RW,
    );

    /**
     *  @var    array       ��ĥ������
     */
    var $ext = array(
        'php'           => 'php',
        'tpl'           => 'tpl',
    );

    /**
     *  @var    array   ���饹���
     */
    var $class = array(
        /*
         *  TODO: ���ꥯ�饹�������饹��SQL���饹�򥪡��С��饤��
         *  �������ϲ����Υ��饹̾��˺�줺���ѹ����Ƥ�������
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
     *  @var    array       �ե��륿����
     */
    var $filter = array(
        /*
         *  TODO: �ե��륿�����Ѥ�����Ϥ����ˤ��Υ��饹̾��
         *  ���Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'Delphinus_Filter_ExecutionTime',
         */
         'Delphinus_Filter_PreferenceCheck',
    );

    /**
     *  @var    array   �ޥ͡��������
     */
    var $manager = array(
        /*
         *  TODO: �����˥��ץꥱ�������Υޥ͡����㥪�֥������Ȱ�����
         *  ���Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'um'    => 'User',
         */
    );

    /**
     *  @var    array   smarty modifier���
     */
    var $smarty_modifier_plugin = array(
        /*
         *  TODO: �����˥桼�������smarty modifier�����򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'smarty_modifier_foo_bar',
         */
    );

    /**
     *  @var    array   smarty function���
     */
    var $smarty_function_plugin = array(
        /*
         *  TODO: �����˥桼�������smarty function�����򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'smarty_function_foo_bar',
         */
         array('Haste_SmartyPlugins', 'form_name'),
         array('Haste_SmartyPlugins', 'form_input'),
         array('Haste_SmartyPlugins', 'rss'),
                 
    );

    /**
     *  @var    array   smarty prefilter���
     */
    var $smarty_prefilter_plugin = array(
        /*
         *  TODO: �����˥桼�������smarty prefilter�����򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'smarty_prefilter_foo_bar',
         */
    );

    /**
     *  @var    array   smarty postfilter���
     */
    var $smarty_postfilter_plugin = array(
        /*
         *  TODO: �����˥桼�������smarty postfilter�����򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'smarty_postfilter_foo_bar',
         */
    );

    /**
     *  @var    array   smarty outputfilter���
     */
    var $smarty_outputfilter_plugin = array(
        /*
         *  TODO: �����˥桼�������smarty outputfilter�����򵭽Ҥ��Ƥ�������
         *
         *  �����㡧
         *
         *  'smarty_outputfilter_foo_bar',
         */
    );

    /**#@-*/

    /**
     *  ���ܻ��Υǥե���ȥޥ�������ꤹ��
     *
     *  @access protected
     *  @param  object  Smarty  $smarty �ƥ�ץ졼�ȥ��󥸥󥪥֥�������
     */
    function _setDefaultTemplateEngine(&$smarty)
    {
        /*
         *  TODO: �����ǥƥ�ץ졼�ȥ��󥸥�ν�������
         *  ���ƤΥӥ塼�˶��̤ʥƥ�ץ졼���ѿ������ꤷ�ޤ�
         *
         *  �����㡧
         * $smarty->assign_by_ref('session_name', session_name());
         * $smarty->assign_by_ref('session_id', session_id());
         *
         * // ������ե饰(true/false)
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
     *  �ե�����ˤ���׵ᤵ�줿���������̾���֤�
     *
     *  @access protected
     *  @return string  �ե�����ˤ���׵ᤵ�줿���������̾
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
