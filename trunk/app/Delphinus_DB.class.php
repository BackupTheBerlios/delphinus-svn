<?php
// vim: foldmethod=marker
/**
 *  Delphinus_DB.class.php
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @version    $Id$
 */

require_once 'Haste_Creole.php';

/**
 *  Delphinus DB Class
 *
 *  Delphinus内でのオリジナルな処理を記述したクラス
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Ethna
 */
class Delphinus_DB extends Haste_Creole
{
    
    //{{{ getRssFromId()
    /**
     * getRssFromId
     *
     * @access public
     */
    function getRssFromId($id)
    {
        $query = "SELECT * FROM rss_list WHERE id = {$id} LIMIT 1";

        try {

            $result = $this->db->executeQuery($query);
            $result->next();
            return $result->getRow();

        } catch (Exception $e) {
        
            var_dump($e);

        }

    }
    //}}}
    
    //{{{ getRssList()
    /**
     *
     * getRssList
     *
     * @return  array    rss information
     * @access  public
     */
    function getRssList()
    {
        $rows = array();
        try{
        
            $query = 'SELECT * FROM rss_list';
            $result = $this->db->executeQuery($query);
            foreach($result as $row){
                $rows[$row['id']] = $row;
            }

        }catch(Exception $e){
            var_dump($e);
        }

        return $rows;
    }
    //}}}
    
    //{{{ setRssList()
    /**
     * setRssList
     *
     */
    function setRssList($rss_info, $id = false)
    {
        if ( is_numeric($id) ) {
            $query = "UPDATE rss_list SET";
            foreach( $rss_info as $key => $value) {
                $query .= " {$key} = ? ,";
            }
            $query = substr($query, 0, -1);
            $query.= " WHERE id = {$id}";
        } else {
            $query = 'INSERT INTO rss_list (name,url,author) VALUES (?,?,?)';
        }

        try{
            $Statement = $this->db->prepareStatement($query);

            $vars = array_values($rss_info);
            $Statement->executeUpdate($vars);
        }catch(Exception $e){
            var_dump($e);
        }
    }
    //}}}

    //{{{ deleteFeed()
    function deleteFeed($id)
    {
        $query = "DELETE FROM rss_list WHERE id = {$id}";
        try {
            $this->db->executeQuery($query);
        } catch(Exception $e) {
            var_dump($e);
        }

        $this->deleteEntriesFromRssId($id);
    }
    //}}}

    //{{{ deleteCommentsFromRssId()
    function deleteCommentsFromEntryId($id)
    {
        $query = "DELETE FROM comments WHERE entry_id = {$id}";
        try {
            $this->db->executeQuery($query);
        } catch(Exception $e) {
            var_dump($e);
        }
    }
    //}}}

    //{{{ getRecentEntries()
    function getRecentEntries()
    {
        $entries = array();
        try{
            $query = 'SELECT * FROM entries ORDER BY date DESC LIMIT 10';
            $rs = $this->db->executeQuery($query);
            
            while ($rs->next()) {
                
                $entries[] = $rs->getRow();
            
            }

            return $entries;
        
        } catch (Exception $e) {
            var_dump($e);
        }
    }
    //}}}
    
    //{{{ setEntry
    /**
     * setEntry
     *
     */
    function setEntry($rss_id, $item)
    {
        try{
        
            $query = 'INSERT INTO entries (
                rss_id,
                title,
                date,
                description,
                link
                ) VALUES (?,?,?,?,?)';
            $Statement = $this->db->prepareStatement($query);

            $vars = array(
                $rss_id,
                $item['title'],
                $item['date'],
                $item['description'],
                $item['link']
            );
            
            $Statement->executeUpdate($vars);
        
        }catch(Exception $e){
        
            var_dump($e);
            //var_dump($vars);
        
        }

    }
    //}}}

    //{{{ deleteEntriesFromRssId()
    /**
     * deleteEntriesFromRssId
     *
     * @access public
     */
    function deleteEntriesFromRssId($rss_id)
    {
        try{
            $query = "DELETE FROM entries WHERE rss_id = {$rss_id}";
            $this->db->executeQuery($query);
        } catch(Exception $e) {
            var_dump($e);
        }
    }
    //}}}
   
    //{{{ existsEntryFromLink
    /**
     *
     * existsEntryFromLink
     *
     * @return   boolean
     * @access   public
     */
    function existsEntryFromLink($link)
    {
        try{
            $query = "SELECT count(*) as count FROM entries WHERE link = '{$link}'";
            $ResultSet = $this->db->executeQuery($query);
            $ResultSet->next();
            $count = $ResultSet->get('count');

            if ( $count == '0') {
                return false;
            } else {
                return true;
            }

        } catch(Exception $e) {
            var_dump($e);
        }
    }
    //}}}

    //{{{ registerComment()
    /**
     *
     * registerComment
     *
     * @access public
     */
    function registerComment($id, $item)
    {
        $query = "INSERT INTO comments (
            entry_id,
            name,
            timestamp,
            comment,
            ip,
            ua,
            email,
            url
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";
 
        try {
            $Statement = $this->db->prepareStatement($query);

            $vars = array(
                $id,
                $item['name'],
                date('Y-m-d H:i:s'),
                $item['comment'],
                $_SERVER['REMOTE_ADDR'],
                $_SERVER['HTTP_USER_AGENT'],
                $item['email'],
                $item['url']
            );
            
            $Statement->executeUpdate($vars);
        
        } catch( Exception $e) {
        
            var_dump($e);
            //var_dump($vars);
        
        }

    }
    //}}}

    //{{{ getCommentsFromEntryId()
    /**
     * getCommentsFromEntryId()
     *
     * @access public
     */
    function getCommentsFromEntryId($id)
    {
        $id = intval($id);
        $query = "SELECT * FROM comments WHERE entry_id = {$id}";
        $vars = array();

        try {
        
            $ResultSet = $this->db->executeQuery($query);
            while ($ResultSet->next()) {
                $vars[] = $ResultSet->getRow();
            }

            return $vars;
            
        } catch(Exception $e) {
            var_dump($e);
        }
    }
    //}}}

}
?>
