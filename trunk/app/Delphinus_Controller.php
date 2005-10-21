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
         array('HasteSmartyPlugins', 'form_name'),
         array('HasteSmartyPlugins', 'form_input'),
         array('HasteSmartyPlugins', 'rss'),
                 
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
        
        $arr = explode('/', $_SERVER['PATH_INFO']);
        $action_name = $arr[1];
        
        return $action_name;
    
    }

    /**
     * getDefaultActionPath
     *
     * @access public
     */
    function getDefaultActionPath($action_name, $fallback = true)
    {
        
        $default_path = preg_replace('/_(.)/e', "strtoupper('\$1')", ucfirst($action_name)) . '.' . $this->getExt('php');
        $action_dir = $this->getActiondir();

        if ($this->getClientType() == CLIENT_TYPE_SOAP) {
            $r = 'SOAP/' . $default_path;
        } else if ($this->getClientType() == CLIENT_TYPE_MOBILE_AU) {
            $r = 'MobileAU/' . $default_path;
        } else {
            $r = $default_path;
        }

        if ($fallback && file_exists($action_dir . $r) == false && $r != $default_path) {
            $this->logger->log(LOG_DEBUG, 'client_type specific file not found [%s] -> try defualt', $r);
            $r = $default_path;
        }

        $this->logger->log(LOG_DEBUG, "default action path [%s]", $r);
        return $r;
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

    /**
     *  �ƥ�ץ졼�ȥ��󥸥��������(���ߤ�smarty�Τ��б�)
     *
     *  @access public
     *  @return object  Smarty  �ƥ�ץ졼�ȥ��󥸥󥪥֥�������
     */
    function &getTemplateEngine()
    {
        $smarty =& new Smarty();
        $smarty->template_dir = $this->getTemplatedir();
        $smarty->compile_dir = $this->getDirectory('template_c');
        $smarty->compile_id = md5($smarty->template_dir);

        // �������ФäƤߤ�
        if (@is_dir($smarty->compile_dir) == false) {
            mkdir($smarty->compile_dir, 0755);
        }
        $smarty->plugins_dir = $this->getDirectory('plugins');

        // default modifiers
        $smarty->register_modifier('number_format', 'smarty_modifier_number_format');
        $smarty->register_modifier('strftime', 'smarty_modifier_strftime');
        $smarty->register_modifier('count', 'smarty_modifier_count');
        $smarty->register_modifier('join', 'smarty_modifier_join');
        $smarty->register_modifier('filter', 'smarty_modifier_filter');
        $smarty->register_modifier('unique', 'smarty_modifier_unique');
        $smarty->register_modifier('wordwrap_i18n', 'smarty_modifier_wordwrap_i18n');
        $smarty->register_modifier('truncate_i18n', 'smarty_modifier_truncate_i18n');
        $smarty->register_modifier('i18n', 'smarty_modifier_i18n');
        $smarty->register_modifier('checkbox', 'smarty_modifier_checkbox');
        $smarty->register_modifier('select', 'smarty_modifier_select');
        $smarty->register_modifier('form_value', 'smarty_modifier_form_value');

        // user defined modifiers
        foreach ($this->smarty_modifier_plugin as $modifier) {
            $name = str_replace('smarty_modifier_', '', $modifier);
            $smarty->register_modifier($name, $modifier);
        }

        // default functions
        $smarty->register_function('is_error', 'smarty_function_is_error');
        $smarty->register_function('message', 'smarty_function_message');
        $smarty->register_function('uniqid', 'smarty_function_uniqid');
        $smarty->register_function('select', 'smarty_function_select');
        $smarty->register_function('checkbox_list', 'smarty_function_checkbox_list');

        // user defined functions
        foreach ($this->smarty_function_plugin as $function) {
            
            if ( !is_array($function) ) {
                $name = str_replace('smarty_function_', '', $function);
                $smarty->register_function($name, $function);
            } else {
                $smarty->register_function($function[1], $function);
            }
        }

        // user defined prefilters
        foreach ($this->smarty_prefilter_plugin as $prefilter) {
            $smarty->register_prefilter($prefilter);
        }

        // user defined postfilters
        foreach ($this->smarty_postfilter_plugin as $postfilter) {
            $smarty->register_postfilter($postfilter);
        }

        // user defined outputfilters
        foreach ($this->smarty_outputfilter_plugin as $outputfilter) {
            $smarty->register_outputfilter($outputfilter);
        }

        $this->_setDefaultTemplateEngine($smarty);

        return $smarty;
    }

}
?>
