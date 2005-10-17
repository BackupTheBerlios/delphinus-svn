<?php
/**
 *  Crawler.php
 *
 *  @author     your name
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  Crawler�ե�����μ���
 *
 *  @author     your name
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Crawler extends Ethna_ActionForm
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
 *  Crawler���������μ���
 *
 *  @author     your name
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Crawler extends Ethna_ActionClass
{
    /**
     *  Crawler����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {
        $Controller = $this->backend->getController();
        if ( $Controller->getCLI() ) {
            return null;
        }
        
        return false;
    
    }

    /**
     *  Crawler���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        require_once 'magpierss/rss_fetch.inc';
        
        $DB = $this->backend->getDB();
        $rss_list = $DB->getRssList();
        
        foreach( $rss_list as $rss){
            $Rss = fetch_rss($rss['url']);
            //var_dump($Rss->channel);
            //var_dump('feed_type:' . $Rss->feed_type);
            //var_dump($Rss->feed_version);
            //$DB->deleteEntriesFromRssId($rss['id']);
            foreach( $Rss->items as $item){

                $item['date'] = date('Y-m-d H:i:s', $item['date_timestamp']);   
                if ( empty($item['description']) ) {
                    $item['description'] = $item['atom_content'];
                }
                //var_dump($item);
                if ( !$DB->existsEntryFromLink($item['link']) ) {
                    $DB->setEntry($rss['id'], $item);
                }
            }
        }
 
        return null;
    }

}
?>
