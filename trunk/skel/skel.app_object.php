<?php
/**
 *	{$app_path}
 *
 *	@author		{$author}
 *	@package	Delphinus
 *	@version	$Id$
 */

/**
 *	{$app_object}Manager
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Delphinus
 */
class {$app_object}Manager extends Ethna_AppManager
{
}

/**
 *	{$app_object}
 *
 *	@author		{$author}
 *	@access		public
 *	@package	Delphinus
 */
class {$app_object} extends Ethna_AppObject
{
    /**
     *  �ץ�ѥƥ���ɽ��̾���������
     *
     *  @access public
     */
    function getName($key)
    {
        return $this->get($key);
    }
}
?>
