<?php
// vim: foldmethod=marker
/**
 *	Aero_Util.php
 *
 *	@author		Keita Arai<cocoiti@comio.info>
 *	@license	http://www.opensource.org/licenses/bsd-license.php The BSD License
 *	@package	Aero
 *	@version	$Id
 */

// {{{ Aero_Util
/**
 *	ユーティリティクラス
 *
 *	@author		Keita Arai<cocoiti@comio.info>
 *	@access		public
 *	@package	Aero
 */
class Aero_Util
{
	/**
	 *	CRCFのチェックを行う(セッションを有効にしている時のみ)
	 *	セッション開始されてなければならない
	 *  
	 *	@access	public
	 *	@return	bool	true:正当なPOST false:1回目のPOST
	 */
	function isCSRF()
	{
		$c =& Ethna_Controller::getInstance();
		$session = $c->getSession();

		if (! $session->isStart(true)) {
			return false;
		}
		if(is_Null($session->get('__CSRF__'))){
			return false;
		}

		// use raw post data
		if (isset($_POST['csrfid'])) {
			$csrfid = $_POST['csrfid'];
		} else if (isset($_GET['csrfid'])) {
			$csrfid = $_GET['csrfid'];
		} else {
			return false;
		}

		if(strcmp($csrfid, $session->get('__CSRF__')) == 0){
			return true;
		}else{
			return false;
		}

	}


	/**
	 *	CRCFIDの初期化と設定を行う。セッション開始されてなければならない
	 *
	 *	@access	public
	 *	@access	public
	 *	@return	bool	true:成功 false:失敗
	 */
	function setCSRF()
	{
		$c =& Ethna_Controller::getInstance();
		$session = $c->getSession();

		if (! $session->isStart(true)) {
			return false;
		}
		if(is_Null($session->get('__CSRF__'))){
			$session->set('__CSRF__', Ethna_Util::getRandom());
		}

		$csrfid = $session->get('__CSRF__');
		$form = $c->getActionForm();
		$form->setApp('csrfid', $csrfid);
		return true;
	}

	/**
	 *	Location→Refreshの移動($urlはFullPathで書いてください)
	 *	この関数を呼ぶとそこで処理は終了します。
	 *	
	 *	@access	public
	 *	@param	string	$url	移動先URL
	 *	@return	bool	true:成功 false:失敗
	 */
	function move($url , $sec = 0)
	{
		//ヘッダに余計な文字列がつかない様に削除
		//URLエンコード等は各アプリケーションで(面倒見切れん)
		$url = str_replace("\n", '', $url);
		$url = str_replace("\r", '', $url);
		if($sec <= 0){
			header('location: ' . $url);
		}

		//Smartyで処理しようか迷った挙句これ
		print('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">');
		print('<html><head>');
		printf('<meta http-equiv="refresh" content="%d;url=%s">',  htmlspecialchars($sec, ENT_QUOTES),  htmlspecialchars($url, ENT_QUOTES));
		print('</head><body></body></html>');
		exit;
	}


	/**
	 *	ファイルが指定されたディレクトリ以下のファイルかを返す（ディレクトリトラバーサル対策)
	 *
	 *	@access	public
	 *	@param	string	$securepath	指定するディレクトリ
	 *	@param	string	$filename	ファイル名
	 */
	function isSecurePath($securepath, $filename)
	{
		$real_securepath = realpath($securepath);
		$real_filename   = realpath($filename);
		if ($real_securepath == false) {
			return false;
		}

		if ($real_filename == false) {
			return false;
		}

		if(strncmp($real_filename, $real_securepath, strlen($real_filename)) !== 0){
			return false;
		}

		return true;
	}

}
// }}}
?>
