<?php
/**
 *  Admin.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  Admin�ե�����μ���
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Admin extends Ethna_ActionForm
{
    /**
     *  @access private
     *  @var    array   �ե����������
     */
    var $form = array(
        /*
        'sample' => array(
            'name'          => '����ץ�',      // ɽ��̾
            'required'      => true,            // ɬ�ܥ��ץ����(true/false)
            'min'           => null,            // �Ǿ���
            'max'           => null,            // ������
            'regexp'        => null,            // ʸ�������(����ɽ��)
            'custom'        => null,            // �᥽�åɤˤ������å�
            'filter'        => null,            // �������Ѵ��ե��륿���ץ����
            'form_type'     => FORM_TYPE_TEXT   // �ե����෿
            'type'          => VAR_TYPE_INT,    // �����ͷ�
        ),
        */
    );
}

/**
 *  Admin���������μ���
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Admin extends Ethna_AuthActionClass
{
    /**
     *  Admin����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
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
     *  Admin���������μ���
     *
     *  @access public
     *  @return string  ����̾
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
