<?php
/**
 *  Comment.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  comment�ե�����μ���
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Comment extends Ethna_ActionForm
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
        'name' => array(
            'name' => 'Name',
            'required' => true,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),

        'comment' => array(
            'name' => 'Comment',
            'required' => true,
            'form_type' => FORM_TYPE_TEXTAREA,
            'type' => VAR_TYPE_STRING,
        ),

        'url' => array(
            'name' => 'Url',
            'required' => false,
            'form_type' => FORM_TYPE_TEXT,
            'type' => VAR_TYPE_STRING,
        ),

        'submit' => array(
            'name' => 'Submit',
            'required' => true,
            'form_type' => FORM_TYPE_SUBMIT,
            'type' => VAR_TYPE_STRING,
        ),
    );
}

/**
 *  comment���������μ���
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Comment extends Ethna_ActionClass
{
    /**
     *  comment����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        //get query
        $request = explode('/', $_SERVER['PATH_INFO']);
        if ( is_numeric($request[2]) ){ 
         
            $this->af->setApp('id', $request[2]);
            
            if( $this->af->get('submit') ){
                
                if( $this->af->validate() > 0 ) {
                    return 'error';
                } else {
                    return null;
                }
            
            }
        
        }
 
        return 'error';
    }

    /**
     *  comment���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        
        //get query
        $request = explode('/', $_SERVER['PATH_INFO']);
        $id = $request[2];
 
        $DB = $this->backend->getDB();
        $Config = $this->backend->getConfig();
        $base_url = $Config->get('base_url');

        $DB->registerComment($id, $this->af->getArray());
        header("Location: {$base_url}");
        exit();

    }

}
?>
