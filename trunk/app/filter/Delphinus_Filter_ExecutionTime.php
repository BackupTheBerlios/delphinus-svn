<?php
/**
 *	Delphinus_Filter_ExecutionTime.php
 *
 *	@author		your name
 *	@package	Delphinus
 *	@version	$Id: app.filter.default.php,v 1.3 2005/01/04 12:50:42 fujimoto Exp $
 */

/**
 *	実行時間計測フィルタの実装
 *
 *	@author		your name
 *	@access		public
 *	@package	Delphinus
 */
class Delphinus_Filter_ExecutionTime extends Ethna_Filter
{
	/**#@+
	 *	@access	private
	 */

	/**
	 *	@var	int		開始時間
	 */
	var	$stime;

	/**#@-*/


	/**
	 *	実行前フィルタ
	 *
	 *	@access	public
	 */
	function preFilter()
	{
		$stime = explode(' ', microtime());
		$stime = $stime[1] + $stime[0];
		$this->stime = $stime;
	}

	/**
	 *	実行後フィルタ
	 *
	 *	@access	public
	 */
	function postFilter()
	{
		$etime = explode(' ', microtime());
		$etime = $etime[1] + $etime[0];
		$time   = round(($etime - $this->stime), 4);

		print "\n<!-- page was processed in $time seconds -->\n";
	}
}
?>
