<?php
/**
 *  Crawler.php
 *
 *  @author     your name
 *  @package    Delphinus
 *  @version    $Id: skel.action.php,v 1.4 2005/01/04 12:53:26 fujimoto Exp $
 */

/**
 *  Crawlerフォームの実装
 *
 *  @author     your name
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Form_Crawler extends Ethna_ActionForm
{
    /**
     *  @access private
     *  @var    array   フォーム値定義
     */
    var $form = array(
        /*
        'sample' => array(
            'name'          => 'サンプル',      // 表示名
            'required'      => true,            // 必須オプション(true/false)
            'min'           => null,            // 最小値
            'max'           => null,            // 最大値
            'regexp'        => null,            // 文字種指定(正規表現)
            'custom'        => null,            // メソッドによるチェック
            'filter'        => null,            // 入力値変換フィルタオプション
            'form_type'     => FORM_TYPE_TEXT   // フォーム型
            'type'          => VAR_TYPE_INT,    // 入力値型
        ),
        */
    );
}

/**
 *  Crawlerアクションの実装
 *
 *  @author     your name
 *  @access     public
 *  @package    Delphinus
 */
class Delphinus_Action_Crawler extends Ethna_ActionClass
{
    /**
     *  Crawlerアクションの前処理
     *
     *  @access public
     *  @return string      遷移名(正常終了ならnull, 処理終了ならfalse)
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
     *  Crawlerアクションの実装
     *
     *  @access public
     *  @return string  遷移名
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
