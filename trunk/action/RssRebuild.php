<?php
/**
 *  RssRebuild.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  RssRebuild�ե�����μ���
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_RssRebuild extends Ethna_ActionForm
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
 *  RssRebuild���������μ���
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_RssRebuild extends Ethna_ActionClass
{
    /**
     * Database Object
     * @var     object
     * @access  protected
     */
    var $DB;
    
    /**
     * Config Container
     * @var     object
     * @access  protected
     */
    var $Config;
    
    /**
     *  RssRebuild����������������
     *
     *  @access public
     *  @return string      ����̾(���ｪλ�ʤ�null, ������λ�ʤ�false)
     */
    function prepare()
    {   
        $Controller = $this->backend->getController();
        if ( $Controller->getCLI() ) {
            $this->DB = $this->backend->getDB();
            $this->Config = $this->backend->getConfig();
            return null;
        }
 
        return false;
    }

    /**
     *  RssRebuild���������μ���
     *
     *  @access public
     *  @return string  ����̾
     */
    function perform()
    {
        $rss = array();

        if ( !$filepath = $this->Config->get('rss_path') ) {
            print('ERROR:undefined parameter rss_path');
            return null;
        }
        
        $rss['title'] = $this->Config->get('title');
        $rss['description'] = $this->Config->get('description');
        $rss['link'] = $this->Config->get('base_url');
        $rss['feeds'] = $this->DB->getRecentEntries();

        $this->buildRSS($rss, $filepath);
        return null;
    }

    //{{{ buildRSS()
    /**
     * buildRSS
     *
     * @access protected
     * @author halt <halt.hde@gmail.com>
     */
    function buildRSS($r_data, $r_filepath, $r_type = "RSS1.0")
    {
        require_once "feedcreator.class.php";

        $rss = new UniversalFeedCreator();
        //$rss->encoding = 'UTF-8'; can't override FeedCreator::encoding :-<
        $rss->useCached();
        $rss->title = $r_data['title'];
        $rss->description = $r_data['description'];
        $rss->link = $r_data['link'];
        //$rss->syndicationURL = "http://www.dailyphp.net/".$PHP_SELF;

        if (isset($r_data['image'])) {
            $image = new FeedImage();
            $image->title = $r_data['image']['title'];
            $image->url = $r_data['image']['url'];
            $image->link = $r_data['image']['link'];
            $image->description = $r_data['image']['description'];
            $rss->image = $image;
        }

        foreach($r_data['feeds'] as $feed) {
    
            $item = new FeedItem();
            $item->title = $feed['title'];
            $item->link = $feed['link'];
            $item->description = $feed['description'];
            $item->date = $feed['date'];
            $item->source = $feed['source'];
            $item->author = $feed['author'];
    
            $rss->addItem($item);
        }

        $rss->saveFeed($r_type, $r_filepath); 
    }
    //}}}
}
?>
