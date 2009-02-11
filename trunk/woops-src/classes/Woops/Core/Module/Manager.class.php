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
 * WOOPS page engine class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Module
 */
final class Woops_Core_Module_Manager implements Woops_Core_Singleton_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance   = NULL;
    
    /**
     * The configuration object
     */
    private $_conf              = NULL;
    
    /**
     * The environment object
     */
    private $_env               = NULL;
    
    /**
     * The directories with WOOPS modules
     */
    private $_modulesDirs       = array();
    
    /**
     * The existing modules
     */
    private $_modules           = array();
    
    /**
     * The loaded (active) modules
     */
    private $_loadedModules     = array();
    
    /**
     * Class constructor
     * 
     * @return  NULL
     */
    private function __construct()
    {
        $this->_conf = Woops_Core_Config_Getter::getInstance();
        $this->_env  = Woops_Core_Env_Getter::getInstance();
        
        $this->_registerModuleDirectory( $this->_env->getSourcePath( 'modules' ) );
        $this->_registerModuleDirectory( $this->_env->getPath( 'modules' ) );
        
        $loadedModules = $this->_conf->getVar( 'modules', 'loaded' );
        
        if( is_array( $loadedModules ) ) {
            
            foreach( $loadedModules as $moduleName ) {
                
                if( !isset( $this->_modules[ $moduleName ] ) ) {
                    
                    throw new Woops_Core_Module_Manager_Exception(
                        'The module \'' . $moduleName . '\' does not exist',
                        Woops_Core_Module_Manager_Exception::EXCEPTION_NO_MODULE
                    );
                }
                
                $this->_loadedModules[ $moduleName ] = true;
            }
        }
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
     * Gets the unique class instance
     * 
     * This method is used to get the unique instance of the class
     * (singleton). If no instance is available, it will create it.
     * 
     * @return  Woops_Core_Module_Manager   The unique instance of the class
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
    private function _registerModuleDirectory( $path )
    {
        if( !file_exists( $path ) && !is_dir( $path ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'The specified modules directory does not exist (path: ' . $path . ')',
                Woops_Core_Module_Manager_Exception::EXCEPTION_NO_DIRECTORY
            );
        }
        
        $this->_modulesDirs[] = $path;
        
        $dirIterator = new DirectoryIterator( $path );
        
        // Process each directory
        foreach( $dirIterator as $file ) {
            
            // Checks if the file is a directory
            if( !$file->isDir() ) {
                
                // File - Process the next file
                continue;
            }
            
            // Checks if the directory is hidden
            if( substr( $file, 0, 1 ) === '.' ) {
                
                // Hidden - Process the next file
                continue;
            }
            
            // Stores the directory name, with it's full path
            $this->_modules[ ( string )$file ] = $file->getPathName() . DIRECTORY_SEPARATOR;
        }
    }
    
    /**
     * 
     */
    public function initModules()
    {
        foreach( $this->_loadedModules as $moduleName => $void ) {
            
            $modPath = $this->_modules[ $moduleName ];
            
            if( file_exists( $modPath . 'init.inc.php' ) ) {
                
                require_once( $modPath . 'init.inc.php' );
            }
        }
    }
    
    /**
     * 
     */
    public function getAvailableModules()
    {
        return $this->_modules;
    }
    
    /**
     * 
     */
    public function getModulePath( $moduleName )
    {
        if( !isset( $this->_loadedModules[ $moduleName ] ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'The module \'' . $moduleName . '\' is not loaded',
                Woops_Core_Module_Manager_Exception::EXCEPTION_MODULE_NOT_LOADED
            );
        }
        
        return $this->_modules[ $moduleName ];
    }
    
    /**
     * 
     */
    public function isLoaded( $moduleName )
    {
        return isset( $this->_loadedModules[ $moduleName ] );
    }
}
