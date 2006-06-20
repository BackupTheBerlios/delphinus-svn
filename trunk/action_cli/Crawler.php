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
class Delphinus_CLI_Form_Crawler extends Ethna_ActionForm
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
class Delphinus_CLI_Action_Crawler extends Ethna_ActionClass
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
        require_once 'magpierss/rss_fetch.inc';
        
        $DB = $this->backend->getDB();
        $rss_list = $DB->getRssList();
        
        foreach( $rss_list as $rss){

            print("Fetch:{$rss['url']}<br>\n");
            $Rss = fetch_rss($rss['url']);
            
            foreach( $Rss->items as $item){

                if (isset($item['date_timestamp'])) {
                    $item['date'] = date('Y-m-d H:i:s', $item['date_timestamp']);   
                }

                if ( !isset($item['description']) || empty($item['description'])) {
                    $item['description'] = $item['atom_content'];
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
