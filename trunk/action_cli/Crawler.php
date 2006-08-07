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
class Delphinus_CLI_Form_Crawler extends Ethna_ActionForm
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
class Delphinus_CLI_Action_Crawler extends Ethna_ActionClass
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
        print("Start Crawl Feeds<br>\n");
        $this->crawlRSS();
        $Controller = $this->backend->getController();
        $path_bin = $Controller->getDirectory('bin');
        exec("php {$path_bin}/rss_rebuild.php");
        return null;
    }

    //{{{ crawlRSS
    /**
     * crawlRSS
     *
     * @access protected
     * @param void
     */
    function crawlRSS()
    {
        require_once 'XML/Feed/Parser.php';
        
        $DB = $this->backend->getDB();
        $rss_list = $DB->getRssList();
        $allow_category = $this->config->get('allow_category');
        
        foreach( $rss_list as $rss){

            print("Fetch:{$rss['url']}<br>\n");
        
            try {
                $feed = new XML_Feed_Parser(file_get_contents($rss['url']));
            } catch(XML_Feed_Parser_Exception $e) {
                die('Feed invalid: ' . $e->getMessage());
            }
             
            foreach($feed as $entry){

                if (is_string($allow_category) && ($entry->category !== false) && (strtoupper($entry->category) != $allow_category)) {
                    print("Parge:{$entry->category}\n");
                    continue;
                }

                $item['title'] = $entry->title;
                if ($entry->date !== false) {
                    $item['date'] = date('Y-m-d H:i:s', $entry->date);
                } else {
                    $item['date'] = date('Y-m-d H:i:s', $entry->pubdate);
                }
                $item['link'] = $entry->link;
                if ($entry->description !== false) {
                    $item['description'] = $entry->description;
                } else {
                    $item['description'] = $entry->content;
                }
                
                if ( !$DB->existsEntryFromLink($item['link']) ) {
                    $DB->setEntry($rss['id'], $item);
                } else {
                    print('Delete Entry' . "\n");
                    $DB->deleteEntry($item['link']);
                    $DB->setEntry($rss['id'], $item);
                }
            }
        }
    
        return true;
    }
    //}}}

}
?>
