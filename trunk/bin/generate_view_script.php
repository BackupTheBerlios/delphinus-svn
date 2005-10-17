<?php
// vim: foldmethod=marker
/**
 *	generate_view_script.php
 *
 *	@author		yourname
 *	@package	Delphinus
 *	@version	$Id: bin.generate_view_script.php,v 1.1 2004/12/01 06:03:59 fujimoto Exp $
 */
chdir(dirname(__FILE__));
include_once('../app/Delphinus_Controller.php');

ini_set('max_execution_time', 0);

// {{{ Delphinus_Action_CliGenerateViewScript
class Delphinus_Action_CliGenerateViewScript extends Ethna_CLI_ActionClass
{
	/**
	 *	cli_generate_view_scriptアクションの実行
	 *
	 *	@access	public
	 */
	function perform()
	{
		parent::perform();

		if (count($_SERVER['argv']) != 2) {
			return $this->_usage();
		}

		$forward_name = $_SERVER['argv'][1];

		printf("generating view script for [%s]...\n", $forward_name);

		$sg = new Ethna_SkeltonGenerator();
		$sg->generateViewSkelton($forward_name);
	}

	/**
	 *	usageを表示する
	 *
	 *	@access	private
	 */
	function _usage()
	{
		printf("%s [view name]\n", $_SERVER['argv'][0]);
	}
}
// }}}

Delphinus_Controller::main_CLI('Delphinus_Controller', 'cli_generate_view_script');
?>
