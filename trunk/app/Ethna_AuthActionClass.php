<?php
/**
 *  Ethna_AuthAction
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @package    Anubis
 *  @version    $Id$
 */

require_once 'Auth_TypeKey.php';

/**
 *  Ethna_AuthActionClass
 *
 *  @author     halt feits <halt.feits@gmail.com>
 *  @access     public
 *  @package    Anubis
 *
 * $config = array(
 *     'base_url' => 'http://example.com/index.php',
 *     'author'   => 'typekey_username',
 *     'typekey_token' => 'typekey_token',
 * );    
 */
class Ethna_AuthActionClass extends Ethna_ActionClass
{

    /**
     * Typekey Object
     * @var     object
     * @access  protected
     */
    var $TypeKey;

    var $typekey_token;
    var $signin_url;
    var $signout_url;
    
    //{{{ authenticate()
    /**
     * authentication
     *
     */
    function authenticate()
    {
        if ($this->session->isStart()) {
            //session_start();
            return null;
        }
        
        $typekey_url = "http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $base_url = $this->config->get('base_url');
        $author = $this->config->get('author');
        
        if ( !is_array($author) ) {
            $author = array($author);
        }
        
        //set typekey token from config
        $this->typekey_token = $this->config->get('typekey_token');
       
        $this->TypeKey = new Auth_TypeKey();
        $this->TypeKey->site_token($this->typekey_token);
        $this->TypeKey->version('1.1');
        
        $this->signin_url = $this->TypeKey->urlSignIn($typekey_url);
        $this->signout_url = $this->TypeKey->urlSignOut($base_url);
        
        $this->af->setApp('signout_url', $this->signout_url);

        if ( is_null($this->session->get('name')) ||
             !in_array($this->session->get('name'), $author) ) {
        
            if( $this->authTypeKey($_GET) === TRUE ){
            //if( TRUE ){
            
                //typekey user not defined allow list
                //if ( !in_array($_GET['name'], $author) ) {
                    
                //    $this->session->destroy();
                //    Aero_Util::move($this->signout_url, "3");
                //    //header('Location: ' . $this->signout_url);
                //    exit();
                
                //}
                
                //success
                $this->session->start();
                $this->session->set('name', $_GET['name']);
                
                return null;
            
            } else {

                //$this->session->destroy();
                print("fail auth typekey");
                Aero_Util::move($this->signout_url, "5");
                exit();
            
            }
       }

       return null;
       
    }
    //}}}

    //{{{ authTypeKey()
    /**
     * authTypeKey
     *
     * $query = array(
     *  'ts' => '',
     *  'email' => '',
     *  'name' => '',
     *  'nick' => '',
     *  'sig' => '',
     * )
     *
     * @access protected
     */
    function authTypeKey($query){
    
        $result = isset($query['ts'])
            && isset($query['email'])
            && isset($query['name'])
            && isset($query['nick'])
            && isset($query['sig']);
        
        if($result){
        
            $result = $this->TypeKey->verifyTypeKey($query);

            if (PEAR::isError($result)) {
                
                if($result->getMessage() == 'Timestamp from TypeKey is too old'){
                    header('Location: ' . $this->signout_url);
                    exit();
                    
                }

                if($result->getMessage() == 'Invalid signature'){
                    Ethna::raiseNotice('TypeKey Invalid signature');
                    return true;
                }
                
                Ethna::raiseError($result->getMessage());
                var_dump($result->getMessage());
                return false;
                
            } else {
                
                return true;
            
            }
            
        } else {
            
            //header('Location: ' . $this->signin_url);
            Aero_Util::move($this->signin_url, "0");
            exit();
        
        }
    }
    //}}}

}
?>
