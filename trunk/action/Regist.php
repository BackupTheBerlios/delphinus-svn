<?php
/**
 *  Regist.php
 *
 *  @author     your name
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  regist�ե�����μ���
 *
 *  @author     your name
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Regist extends Ethna_ActionForm
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
        'author' => array(
            'name' => 'author',
            'required' => false,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING
        ),
        
        'site_name' => array(
            'name' => 'site name',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING
        ),
        
        'url' => array(
            'name' => 'URL',
            'required' => true,
            'custom' => 'checkURL',
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING
        ),

        'submit' => array(
            'name' => 'submit',
            'required' => true,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING
        )
    );
}

/**
 *  regist���������μ���
 *
 *  @author     your name
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Regist extends Ethna_AuthActionClass
{
    /**
     *  regist����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        return null;
    }

    /**
     *  regist���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        $DB = $this->backend->getDB();
        $Config = $this->backend->getConfig();
        $this->af->setApp('rss_list', $DB->getRssList());
        
        if ( is_null($this->af->get('submit')) ) {
            
            return 'regist';
        
        } else if ($this->af->validate() > 0) {
            
            //error
            return 'regist';
        
        } else {
        
            $rss_info = array(
                'name' => $this->af->get('site_name'),
                'url' => $this->af->get('url'),
                'author' => $this->af->get('author')
            );
            $DB->setRssList($rss_info);
            
            header('Location: '.$Config->get('base_url').'/regist');
            exit();
            
        }

        return 'regist';
    }

}
?>
