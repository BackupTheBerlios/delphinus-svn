<?php
/**
 *  Ethna_AuthAction
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @package    Delphinus
 *  @version    $Revision$
 */

require_once 'Auth_TypeKey.php';

/**
 *  Ethna_AuthActionClass
 *
 *  @author     halt <halt.hde@gmail.com>
 *  @access     public
 *  @package    Delphinus
 *
 * $config = array(
 *     'base_url' => 'http://example.com/index.php',
 *     'author'   => 'typekey_username',
 *     'typekey_token' => 'typekey_token',
 * );    
 */
class Ethna_AuthActionClass extends Ethna_ActionClass
{

    var $typekey_url;
    var $typekey_token;
    
    //{{{ authenticate()
    /**
     * authentication
     *
     */
    function authenticate()
    {
        
        $typekey_url = "http://".$_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    
        $Config = $this->backend->getConfig();
        $Session = $this->backend->getSession();
        $base_url = $Config->get('base_url');
        
        $author = $Config->get('author');
        if ( !is_array($author) ) {
            $author = array($author);
        }
        
        $typekey_token = $Config->get('typekey_token');
       
        $tk = new Auth_TypeKey();
        $tk->site_token($typekey_token);
        
        $signin_url = $tk->urlSignIn($typekey_url);
        $signout_url = $tk->urlSignOut($base_url);
        
        $this->af->setApp('signout_url', $signout_url);
        
        //if ( $_SESSION['name'] != $author) {
        if ( !in_array($_SESSION['name'], $author) ) {
        
            if( $this->authTypeKey() ){
            
                if ( !in_array($_GET['name'], $author) ) {
                    unset($_SESSION['name']);
                    $Session->destroy();
                    header('Location: ' . $signout_url);
                    exit();
                }
            
                //success
                $Session->start();
                $Session->set('name', $_GET['name']);
        
            } else {
       
                if( empty($_GET['name']) ){
                    header('Location: '. $signin_url);
                }
               
                $_SESSION = array();
                $Session->destroy();
                print('Location: ' . $signout_url);
                exit();
            
            }
       } 
       
    }
    //}}}

    //{{{ authTypeKey()
    /**
     * authTypeKey
     *
     * @access protected
     */
    function authTypeKey(){
    
        $result = isset($_GET['ts'])
            && isset($_GET['email'])
            && isset($_GET['name'])
            && isset($_GET['nick'])
            && isset($_GET['sig']);
        
        if($result){
        
            $tk = new Auth_TypeKey();
            $tk->site_token($this->typekey_token);
            
            $result = $tk->verifyTypeKey( $_GET  );
            if (PEAR::isError($result)) {

                return false;
                
            } else {
                
                return true;
            
            }
            
        }
    }
    //}}}

}
?>
