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
        
        'name' => array(
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
     * Description of the Variable
     * @var     object
     * @access  protected
     */
    var $DB;

    /**
     *  regist����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        $this->DB = $this->backend->getDB();
        $rss_list = $this->DB->getRssList();
        $id = $this->getParameter();
        
        $this->af->setApp('rss_list', $rss_list);
        
        if ( is_numeric($id) && is_null($this->af->get('submit')) ) {
            $record = $rss_list[$id];
            unset($record['id']);
            foreach( $record as $key => $value){
                $this->af->set($key, $value);
            }
        }
        
        if ( is_null($this->af->get('submit')) ) {
            
            return 'regist';
        
        } else if ($this->af->validate() > 0) {
            var_dump($this->af->getArray());
            //error
            return 'regist';
        
        } else {

            return null;
        
       }

        return 'regist';
    }

    /**
     *  regist���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
    
        $Config = $this->backend->getConfig();
        $id = $this->getParameter();
        
        $rss_info = array(
            'name' => $this->af->get('name'),
            'url' => $this->af->get('url'),
            'author' => $this->af->get('author')
        );

        $this->DB->setRssList($rss_info, $id);
            
        header('Location: '.$Config->get('base_url').'/regist');
        exit();
 
    }

    function getParameter()
    {
         $query = explode('/', $_SERVER['PATH_INFO']);
        if ( is_numeric($query[2]) ) {
            $id = $query[2];
        } else {
            $id = false;
        }

        return $id;
    
    }

}
?>
