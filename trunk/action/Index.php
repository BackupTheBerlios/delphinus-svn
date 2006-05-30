<?php
/**
 *  Index.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id$
 */

/**
 *  index�ե�����μ���
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Index extends Ethna_ActionForm
{
    /**
     *  @access private
     *  @var    array   �ե����������
     */
    var $form = array(
        /*
         *  TODO: ���Υ�������󤬻��Ѥ���ե�����������򵭽Ҥ��Ƥ�������
         *
         *  ������(type��������Ƥ����ǤϾ�ά��ǽ)��
         *
         *  'sample' => array(
         *      'name'          => '����ץ�',      // ɽ��̾
         *      'required'      => true,            // ɬ�ܥ��ץ����(true/false)
         *      'min'           => null,            // �Ǿ���
         *      'max'           => null,            // ������
         *      'regexp'        => null,            // ʸ�������(����ɽ��)
         *      'custom'        => null,            // �᥽�åɤˤ������å�
         *      'filter'        => null,            // �������Ѵ��ե��륿���ץ����
         *      'form_type'     => FORM_TYPE_TEXT   // �ե����෿
         *      'type'          => VAR_TYPE_INT,    // �����ͷ�
         *  ),
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
            'form_type' => FORM_TYPE_TEXT,
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
 *  index���������μ���
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Index extends Ethna_ActionClass
{
    /**
     *  index����������������
     *
     *  @access public
     *  @return string      Forward��(���ｪλ�ʤ�null)
     */
    function prepare()
    {
        $this->af->setApp('config', $this->config->config);
        return null;
    }

    /**
     *  index���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        $DB = $this->backend->getDB();
        $entries = $DB->getRecentEntries();
        $buf = array();
        foreach ( $entries as $entry) {
            $comments = $DB->getCommentsFromEntryId($entry['id']);
            $entry['comments'] = $comments;
            $buf[] = $entry;
        }
        $entries = $buf;
        
        $this->af->setApp('rss_list', $DB->getRssList());
        $this->af->setApp('entries', $entries);
        $this->af->setAppNE('entries', $entries);

        return 'index';
    }
}
?>
