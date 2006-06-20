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
 *	�桼�ƥ���ƥ����饹
 *
 *	@author		Keita Arai<cocoiti@comio.info>
 *	@access		public
 *	@package	Aero
 */
class Aero_Util
{
	/**
	 *	CRCF�Υ����å���Ԥ�(���å�����ͭ���ˤ��Ƥ�����Τ�)
	 *	���å���󳫻Ϥ���Ƥʤ���Фʤ�ʤ�
	 *  
	 *	@access	public
	 *	@return	bool	true:������POST false:1���ܤ�POST
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
	 *	CRCFID�ν�����������Ԥ������å���󳫻Ϥ���Ƥʤ���Фʤ�ʤ�
	 *
	 *	@access	public
	 *	@access	public
	 *	@return	bool	true:���� false:����
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
	 *	Location��Refresh�ΰ�ư($url��FullPath�ǽ񤤤Ƥ�������)
	 *	���δؿ���Ƥ֤Ȥ����ǽ����Ͻ�λ���ޤ���
	 *	
	 *	@access	public
	 *	@param	string	$url	��ư��URL
	 *	@return	bool	true:���� false:����
	 */
	function move($url , $sec = 0)
	{
		//�إå���;�פ�ʸ���󤬤Ĥ��ʤ��ͤ˺��
		//URL���󥳡������ϳƥ��ץꥱ��������(���ݸ��ڤ��)
		$url = str_replace("\n", '', $url);
		$url = str_replace("\r", '', $url);
		if($sec <= 0){
			header('location: ' . $url);
		}

		//Smarty�ǽ������褦���¤ä���礳��
		print('<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">');
		print('<html><head>');
		printf('<meta http-equiv="refresh" content="%d;url=%s">',  htmlspecialchars($sec, ENT_QUOTES),  htmlspecialchars($url, ENT_QUOTES));
		print('</head><body></body></html>');
		exit;
	}


	/**
	 *	�ե����뤬���ꤵ�줿�ǥ��쥯�ȥ�ʲ��Υե����뤫���֤��ʥǥ��쥯�ȥ�ȥ�С������к�)
	 *
	 *	@access	public
	 *	@param	string	$securepath	���ꤹ��ǥ��쥯�ȥ�
	 *	@param	string	$filename	�ե�����̾
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
