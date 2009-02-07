<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * WOOPS environment class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Request
 */
final class Woops_Core_Request_Getter implements Woops_Core_Singleton_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * The global lookup order
     */
    private $_lookupOrder     = 'GPCS';
    
    /**
     * An array with references to $_GET, $_POST, $_COOKIE and $_SESSION
     */
    private $_requestVars     = array(
        'G' => array(),
        'P' => array(),
        'C' => array(),
        'S' => array()
    );
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return NULL
     */
    private function __construct()
    {
        // Stores references to the request vars
        $this->_requestVars[ 'G' ]   = &$_GET;
        $this->_requestVars[ 'P' ]   = &$_POST;
        $this->_requestVars[ 'C' ]   = &$_COOKIE;
        $this->_requestVars[ 'S' ]   = &$_SESSION;
    }
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  NULL
     * @throws  Woops_Core_Singleton_Exception  Always, as the class cannot be cloned (singleton)
     */
    public function __clone()
    {
        throw new Woops_Core_Singleton_Exception(
            'Class ' . __CLASS__ . ' cannot be cloned',
            Woops_Core_Singleton_Exception::EXCEPTION_CLONE
        );
    }
    
    /**
     * 
     */
    public function __get( $name )
    {
        return $this->getVar( $name, $this->_lookupOrder );
    }
    
    /**
     * 
     */
    public function __isset( $name )
    {
        return $this->varExists( $name, $this->_lookupOrder );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_Core_Request_Getter   The unique instance of the class
     * @see     __construct
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // Creates the unique instance
            self::$_instance = new self();
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
    
    /**
     * 
     */
    public function setLookupOrder( $lookupOrder )
    {
        $oldValue           = $this->_lookupOrder;
        $this->_lookupOrder = ( string )$lookupOrder;
        return $oldValue;
    }
    
    /**
     * 
     */
    public function getVar( $name, $order = '' )
    {
        $order = ( $order ) ? $order : $this->_lookupOrder;
        $keys  = preg_split( '//', $order );
        
        foreach( $keys as $key ) {
            
            if( isset( $this->_requestVars[ $key ][ $name ] ) ) {
                
                return $this->_requestVars[ $key ][ $name ];
            }
        }
        
        return false;
    }
    
    /**
     * 
     */
    public function varExists( $name, $order = '' )
    {
        $order = ( $order ) ? $order : $this->_lookupOrder;
        $keys  = preg_split( '//', $order );
        
        foreach( $keys as $key ) {
            
            if( isset( $this->_requestVars[ $key ][ $name ] ) ) {
                
                return true;
            }
        }
        
        return false;
    }
}