<?php
/**
 *  Logout.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  logout�ե�����μ���
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Logout extends Ethna_ActionForm
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
 *  logout���������μ���
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Logout extends Ethna_ActionClass
{
    /**
     *  logout����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        return null;
    }

    /**
     *  logout���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        unset($_SESSION['name']);
        $Config = $this->backend->getConfig();
        
        $typekey_url = $Config->get('typekey_url');
        $typekey_token = $Config->get('typekey_token');
    
        $Session = $this->backend->getSession();
        $Session->destroy();

        $tk = new Auth_TypeKey();
        $tk->site_token($typekey_token);
        
        $signin_url = $tk->urlSignIn($typekey_url);
        $signout_url = $tk->urlSignOut($Config->get('base_url'));
        
        header('Location: ' . $signout_url);
        exit();

    }
}
?>
