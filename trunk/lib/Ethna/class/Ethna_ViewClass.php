<?php
// vim: foldmethod=marker
/**
 *	Ethna_ViewClass.php
 *
 *	@author		Masaki Fujimoto <fujimoto@php.net>
 *	@license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 *	@package	Ethna
 *	@version	$Id$
 */

// {{{ Ethna_ViewClass
/**
 *	viewクラス
 *
 *	@author		Masaki Fujimoto <fujimoto@php.net>
 *	@access		public
 *	@package	Ethna
 */
class Ethna_ViewClass
{
	/**#@+
	 *	@access	private
	 */

	/**	@var	object	Ethna_Backend		backendオブジェクト */
	var $backend;

	/**	@var	object	Ethna_Config		設定オブジェクト	*/
	var $config;

	/**	@var	object	Ethna_I18N			i18nオブジェクト */
	var $i18n;

	/**	@var	object	Ethna_ActionError	アクションエラーオブジェクト */
	var $action_error;

	/**	@var	object	Ethna_ActionError	アクションエラーオブジェクト(省略形) */
	var $ae;

	/**	@var	object	Ethna_ActionForm	アクションフォームオブジェクト */
	var $action_form;

	/**	@var	object	Ethna_ActionForm	アクションフォームオブジェクト(省略形) */
	var $af;

	/**	@var    array   アクションフォームオブジェクト(helper) */
	var $helper_action_form = array();

	/**	@var	object	Ethna_Session		セッションオブジェクト */
	var $session;

	/**	@var	string	遷移名 */
	var $forward_name;

	/**	@var	string	遷移先テンプレートファイル名 */
	var $forward_path;

	/**#@-*/

	/**
	 *	Ethna_ViewClassのコンストラクタ
	 *
	 *	@access	public
	 *	@param	object	Ethna_Backend	$backend	backendオブジェクト
	 *	@param	string	$forward_name	ビューに関連付けられている遷移名
	 *	@param	string	$forward_path	ビューに関連付けられているテンプレートファイル名
	 */
	function Ethna_ViewClass(&$backend, $forward_name, $forward_path)
	{
		$c =& $backend->getController();
		$this->backend =& $backend;
		$this->config =& $this->backend->getConfig();
		$this->i18n =& $this->backend->getI18N();

		$this->action_error =& $this->backend->getActionError();
		$this->ae =& $this->action_error;

		$this->action_form =& $this->backend->getActionForm();
		$this->af =& $this->action_form;

		$this->session =& $this->backend->getSession();

		// Ethna_AppManagerオブジェクトの設定
		$manager_list = $c->getManagerList();
		foreach ($manager_list as $k => $v) {
			$this->$k =& $backend->getManager($v);
		}

		$this->forward_name = $forward_name;
		$this->forward_path = $forward_path;

        foreach ($this->helper_action_form as $key => $value) {
            if (is_object($value)) {
                continue;
            }
            $this->helper_action_form[$key] =& $this->_getHelperActionForm($key);
        }
	}

	/**
	 *	画面表示前処理
	 *
	 *	テンプレートに設定する値でコンテキストに依存しないものは
	 *	ここで設定する(例:セレクトボックス等)
	 *
	 *	@access	public
	 */
	function preforward()
	{
	}

	/**
	 *	遷移名に対応する画面を出力する
	 *
	 *	特殊な画面を表示する場合を除いて特にオーバーライドする必要は無い
	 *	(preforward()のみオーバーライドすれば良い)
	 *
	 *	@access	public
	 */
	function forward()
	{
		$smarty =& $this->_getTemplateEngine();
		$this->_setDefault($smarty);
		$smarty->display($this->forward_path);
	}

    /**
     *  helperアクションフォームオブジェクトを設定する
     *
     *  @access public
     */
    function addActionFormHelper($action)
    {
        if (is_object($this->helper_action_form[$action])) {
            return;
        }
        $this->helper_action_form[$action] =& $this->_getHelperActionForm($action);
    }

    /**
     *  helperアクションフォームオブジェクトを削除する
     *
     *  @access public
     */
    function clearActionFormHelper($action)
    {
        unset($this->helper_action_form[$action]);
    }

    /**
     *  指定されたフォーム項目に対応するフォーム名(w/ レンダリング)を取得する
     *
     *  @access public
     */
    function getFormName($name, $params)
    {
        $def = $this->_getHelperActionFormDef($name);
        $form_name = null;
        if (is_null($def) || isset($def['name']) == false) {
            $form_name = $name;
        } else {
            $form_name = $def['name'];
        }

        return $form_name;
    }

    /**
     *  指定されたフォーム項目に対応するフォームタグを取得する
     *
     *  experimental(というかとりあえず-細かい実装は別クラスに行きそうです)
     *
     *  @access public
     *  @todo   form_type各種対応/JavaScript対応...
     */
    function getFormInput($name, $params)
    {
        $def = $this->_getHelperActionFormDef($name);
        if (is_null($def)) {
            return "";
        }

        if (isset($def['form_type']) == false) {
            $def['form_type'] = FORM_TYPE_TEXT;
        }
        
        switch ($def['form_type']) {
        case FORM_TYPE_BUTTON:
            $input = $this->_getFormInput_Button($name, $def, $params);
            break;
        case FORM_TYPE_CHECKBOX:
            // T.B.D.
            break;
        case FORM_TYPE_FILE:
            $input = $this->_getFormInput_File($name, $def, $params);
            break;
        case FORM_TYPE_HIDDEN:
            $input = $this->_getFormInput_Hidden($name, $def, $params);
            break;
        case FORM_TYPE_PASSWORD:
            $input = $this->_getFormInput_Password($name, $def, $params);
            break;
        case FORM_TYPE_RADIO:
            // T.B.D.
            break;
        case FORM_TYPE_SELECT:
            // T.B.D.
            break;
        case FORM_TYPE_SUBMIT:
            $input = $this->_getFormInput_Submit($name, $def, $params);
            break;
        case FORM_TYPE_TEXTAREA:
            $input = $this->_getFormInput_Textarea($name, $def, $params);
            break;
        case FORM_TYPE_TEXT:
        default:
            $input = $this->_getFormInput_Text($name, $def, $params);
            break;
        }

        print $input;
    }

    /**
     *  アクションフォームオブジェクト(helper)を生成する
     *
     *  @access protected
     */
    function &_getHelperActionForm($action)
    {
        $af = null;
        $ctl =& Ethna_Controller::getInstance();
        $form_name = $ctl->getActionFormName($action);
        if ($form_name == null) {
            // TODO: logging
            return null;
        }
        $af =& new $form_name($ctl);

        return $af;
    }

    /**
     *  フォーム項目に対応するフォーム定義を取得する
     *
     *  @access protected
     */
    function _getHelperActionFormDef($name)
    {
        $def = $this->af->getDef($name);
        if (is_null($def)) {
            foreach ($this->helper_action_form as $key => $value) {
                if (is_object($value) == false) {
                    continue;
                }
                $def = $value->getDef($name);
                if (is_null($def) == false) {
                    break;
                }
            }
        }
        return $def;
    }

    /**
     *  フォームタグを取得する(type="button")
     *
     *  @access protected
     */
    function _getFormInput_Button($name, $def, $params)
    {
        $r = array();
        $r['type'] = "button";
        $r['name'] = $name;

        return $this->_getFormInput_Html("input", $r, $params);
    }

    /**
     *  フォームタグを取得する(type="file")
     *
     *  @access protected
     */
    function _getFormInput_File($name, $def, $params)
    {
        $r = array();
        $r['type'] = "file";
        $r['name'] = $name;
        $r['value'] = "";

        return $this->_getFormInput_Html("input", $r, $params);
    }

    /**
     *  フォームタグを取得する(type="hidden")
     *
     *  @access protected
     */
    function _getFormInput_Hidden($name, $def, $params)
    {
        $r = array();
        $r['type'] = "hidden";
        $r['name'] = $name;
        $r['value'] = $this->af->get($name);

        return $this->_getFormInput_Html("input", $r, $params);
    }

    /**
     *  フォームタグを取得する(type="password")
     *
     *  @access protected
     */
    function _getFormInput_Password($name, $def, $params)
    {
        $r = array();
        $r['type'] = "password";
        $r['name'] = $name;
        $r['value'] = $this->af->get($name);

        return $this->_getFormInput_Html("input", $r, $params);
    }

    /**
     *  フォームタグを取得する(type="submit")
     *
     *  @access protected
     */
    function _getFormInput_Submit($name, $def, $params)
    {
        $r = array();
        $r['type'] = "submit";
        $r['name'] = $name;

        return $this->_getFormInput_Html("input", $r, $params);
    }

    /**
     *  フォームタグを取得する(textarea)
     *
     *  @access protected
     */
    function _getFormInput_Textarea($name, $def, $params)
    {
        $r = array();
        $r['name'] = $name;

        return $this->_getFormInput_Html("textarea", $r, $params, $this->af->get($name));
    }

    /**
     *  フォームタグを取得する(type="text")
     *
     *  @access protected
     */
    function _getFormInput_Text($name, $def, $params)
    {
        $r = array();
        $r['type'] = "text";
        $r['name'] = $name;
        $r['value'] = $this->af->get($name);
        if (isset($def['max']) && $def['max']) {
            $r['maxlength'] = $def['max'];
        }

        return $this->_getFormInput_Html("input", $r, $params);
    }

    /**
     *  HTMLタグを取得する
     *
     *  @access protected
     */
    function _getFormInput_Html($tag, $attr, $user_attr, $element = false)
    {
        // user defs
        foreach ($user_attr as $key => $value) {
            if ($key == "type" || $key == "name" || preg_match('/^[a-z0-9]+$/i', $key) == 0) {
                continue;
            }
            $attr[$key] = $value;
        }

        $r = "<$tag";

        foreach ($attr as $key => $value) {
            $r .= sprintf(' %s="%s"', $key, htmlspecialchars($value, ENT_QUOTES));
        }

        if ($element !== false) {
            $r .= sprintf('>%s</%s>', htmlspecialchars($element, ENT_QUOTES), $tag);
        } else {
            $r .= " />";
        }

        return $r;
    }

	/**
	 *	Smartyオブジェクトを取得する
	 *
	 *	@access	protected
	 *	@return	object	Smarty	Smartyオブジェクト
	 */
	function &_getTemplateEngine()
	{
		$c =& $this->backend->getController();
		$smarty =& $c->getTemplateEngine();

		$form_array =& $this->af->getArray();
		$app_array =& $this->af->getAppArray();
		$app_ne_array =& $this->af->getAppNEArray();
		$smarty->assign_by_ref('form', $form_array);
		$smarty->assign_by_ref('app', $app_array);
		$smarty->assign_by_ref('app_ne', $app_ne_array);
		$message_list = Ethna_Util::escapeHtml($this->ae->getMessageList());
		$smarty->assign_by_ref('errors', $message_list);
		if (isset($_SESSION)) {
			$tmp_session = Ethna_Util::escapeHtml($_SESSION);
			$smarty->assign_by_ref('session', $tmp_session);
		}
		$smarty->assign('script', basename($_SERVER['PHP_SELF']));
		$smarty->assign('request_uri', htmlspecialchars($_SERVER['REQUEST_URI'], ENT_QUOTES));

		return $smarty;
	}

	/**
	 *	共通値を設定する
	 *
	 *	@access	protected
	 *	@param	object	Smarty	Smartyオブジェクト
	 */
	function _setDefault(&$smarty)
	{
	}
}
// }}}
?>
