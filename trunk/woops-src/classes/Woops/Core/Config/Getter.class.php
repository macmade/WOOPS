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
 * WOOPS configuration getter class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Config
 */
final class Woops_Core_Config_Getter implements Woops_Core_Singleton_Interface
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
     * The WOOPS configuration object
     */
    private $_conf            = NULL;
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return NULL
     */
    private function __construct()
    {}
    
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
     * Sets and stores the WOOPS configuration object
     * 
     * This method will create the unique instance of the class and store
     * the WOOPS configuration object. Once it's done, this method will
     * produce an exception if called another time.
     * 
     * @param   stdClass                            The WOOPS configuration object
     * @return  NULL
     * @throws  Woops_Core_Config_Getter_Exception  If the configuration object is already set
     */
    public static function setConfiguration( stdClass $conf )
    {
        // Checks if the unique instance has already be created
        if( is_object( self::$_instance ) ) {
            
            // The configuration is already set
            throw new Woops_Core_Config_Getter_Exception(
                'The WOOPS configuration object already exist. You cannot configure WOOPS multiple times.',
                Woops_Core_Config_Getter_Exception::EXCEPTION_CONFIG_ALREADY_SET
            );
        }
        
        // Creates the unique instance
        self::$_instance        = new self();
        
        // Stores a copy of the configuration object (as the global one will be erased)
        self::$_instance->_conf = clone( $conf );
    }
    
    /**
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If the instance does not exists, this method will
     * produce and exception, as it will mean the configuration
     * object has not been set with the setConfiguration() method.
     * 
     * @return  Woops_Core_ClassManager             The unique instance of the class
     * @see     __construct
     * @throws  Woops_Core_Config_Getter_Exception  If the unique instance does not exist
     */
    public static function getInstance()
    {
        // Checks if the unique instance already exists
        if( !is_object( self::$_instance ) ) {
            
            // The configuration is not set
            throw new Woops_Core_Config_Getter_Exception(
                'The WOOPS configuration object does not exist. It has to be set previously with the ' . __CLASS__ . '::setConfiguration() method.',
                Woops_Core_Config_Getter_Exception::EXCEPTION_CONFIG_NOT_SET
            );
        }
        
        // Returns the unique instance
        return self::$_instance;
    }
}
