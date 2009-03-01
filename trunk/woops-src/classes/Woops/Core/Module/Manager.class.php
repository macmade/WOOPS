<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)                 #
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
     * 
     */
    private $_blockTypes        = array();
    
    /**
     * 
     */
    private $_blocks            = array();
    
    /**
     * 
     */
    private $_moduleDeps        = array();
    
    /**
     * 
     */
    private $_priorityModules   = array();
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    private function __construct()
    {
        $this->_conf = Woops_Core_Config_Getter::getInstance();
        $this->_env  = Woops_Core_Env_Getter::getInstance();
        
        $this->_registerModuleDirectory(
            $this->_env->getSourcePath( 'modules/' ),
            $this->_env->getSourceWebPath( 'modules/' )
        );
        $this->_registerModuleDirectory(
            $this->_env->getPath( 'modules/' ),
            $this->_env->getWebPath( 'modules/' )
        );
        
        $loadedModules = $this->_conf->getVar( 'modules', 'loaded' );
        
        if( is_array( $loadedModules ) ) {
            
            foreach( $loadedModules as $moduleName ) {
                
                if( !isset( $this->_modules[ $moduleName ] ) ) {
                    
                    throw new Woops_Core_Module_Manager_Exception(
                        'The module \'' . $moduleName . '\' does not exist',
                        Woops_Core_Module_Manager_Exception::EXCEPTION_NO_MODULE
                    );
                }
                
                $this->_loadedModules[ $moduleName ] = false;
                
                $this->_loadModuleInfos( $moduleName );
            }
        }
    }
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  void
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
    private function _registerModuleDirectory( $path, $relPath )
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
            $this->_modules[ ( string )$file ] = array(
                $file->getPathName() . DIRECTORY_SEPARATOR,
                $relPath . $file->getFileName() . '/'
            );
        }
    }
    
    /**
     * 
     */
    private function _loadModuleInfos( $moduleName )
    {
        $infoFile = $this->_modules[ $moduleName ][ 0 ] . 'infos.xml';
        
        if( file_exists( $infoFile ) ) {
            
            try {
                
                $infos = simplexml_load_file( $infoFile );
                
                if( isset( $infos->dependancies ) ) {
                    
                    foreach( $infos->dependancies->children() as $key => $value ) {
                        
                        if( !isset( $this->_moduleDeps[ $moduleName ] ) ) {
                            
                            $this->_moduleDeps[ $moduleName ] = array();
                        }
                        
                        $this->_moduleDeps[ $moduleName ][ $key ] = true;
                    }
                }
                
                if( isset( $infos->priority ) ) {
                    
                    $this->_priorityModules[ $moduleName ] = true;
                }
                
            } catch( Exception $e ) {
                
                throw new Woops_Core_Module_Manager_Exception(
                    $e->getMessage(),
                    Woops_Core_Module_Manager_Exception::EXCEPTION_BAD_XML
                );
            }
        }
    }
    
    /**
     * 
     */
    private function _initModule( $moduleName )
    {
        if( isset( $this->_moduleDeps[ $moduleName ] ) ) {
            
            foreach( $this->_moduleDeps[ $moduleName ] as $depsName => $void ) {
                
                $this->_initModule( $depsName );
            }
        }
        
        $modPath = $this->_modules[ $moduleName ][ 0 ];
        
        if( file_exists( $modPath . 'init.inc.php' ) ) {
            
            require_once( $modPath . 'init.inc.php' );
        }
        
        $this->_loadedModules[ $moduleName ] = true;
    }
    
    /**
     * 
     */
    public function initModules()
    {
        foreach( $this->_priorityModules as $moduleName => $void ) {
            
            if( $this->_loadedModules[ $moduleName ] === false ) {
                
                $this->_initModule( $moduleName );
            }
        }
        
        foreach( $this->_loadedModules as $moduleName => &$inited ) {
            
            if( $inited === false ) {
                
                $this->_initModule( $moduleName );
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
    public function getLoadedModules()
    {
        return $this->_loadedModules;
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
        
        return $this->_modules[ $moduleName ][ 0 ];
    }
    
    /**
     * 
     */
    public function getModuleRelativePath( $moduleName )
    {
        if( !isset( $this->_loadedModules[ $moduleName ] ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'The module \'' . $moduleName . '\' is not loaded',
                Woops_Core_Module_Manager_Exception::EXCEPTION_MODULE_NOT_LOADED
            );
        }
        
        return $this->_modules[ $moduleName ][ 1 ];
    }
    
    /**
     * 
     */
    public function isLoaded( $moduleName )
    {
        return isset( $this->_loadedModules[ $moduleName ] );
    }
    
    /**
     * 
     */
    public function registerBlockType( $type, $abstractClass )
    {
        if( isset( $this->_blockTypes[ $type ] ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'The block type \'' . $type . '\' is already registered',
                Woops_Core_Module_Manager_Exception::EXCEPTION_BLOCK_TYPE_EXISTS
            );
        }
        
        if( !class_exists( $abstractClass ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'Cannot register the block type \'' . $type . '\' because it\'s abstract class (\'' . $abstractClass . '\') does not exist',
                Woops_Core_Module_Manager_Exception::EXCEPTION_NO_BLOCK_ABSTRACT
            );
        }
        
        if( !is_subclass_of( $abstractClass, 'Woops_Core_Module_Block' ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'The abstract class \'' . $abstractClass . '\' for block type \'' . $type . '\' does not extends the \'Woops_Core_Module_Block\' abstract class',
                Woops_Core_Module_Manager_Exception::EXCEPTION_INVALID_BLOCK_ABSTRACT
            );
        }
        
        $this->_blockTypes[ $type ] = $abstractClass;
        $this->_blocks[ $type ]     = array();
    }
    
    /**
     * 
     */
    public function registerBlock( $type, $moduleName, $name, $blockClass )
    {
        if( !isset( $this->_loadedModules[ $moduleName ] ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'The module \'' . $moduleName . '\' is not loaded',
                Woops_Core_Module_Manager_Exception::EXCEPTION_MODULE_NOT_LOADED
            );
        }
        
        $blockName  = $moduleName . '.' . $name;
        
        if( !isset( $this->_blockTypes[ $type ] ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'Cannot register the block \'' . $blockName . '\' because it\'s its type (\'' . $type . '\') is not a registered block type',
                Woops_Core_Module_Manager_Exception::EXCEPTION_NO_BLOCK_TYPE
            );
        }
        
        if( isset( $this->_blocks[ $type ][ $blockName ] ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'The block \'' . $blockName . '\' is already registered for type \'' . $type . '\'',
                Woops_Core_Module_Manager_Exception::EXCEPTION_BLOCK_EXISTS
            );
        }
        
        if( !class_exists( $blockClass ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'The class \'' . $blockClass . '\' for block \'' . $blockName . '\' of type \'' . $type . '\' does not exist',
                Woops_Core_Module_Manager_Exception::EXCEPTION_NO_BLOCK_CLASS
            );
        }
        
        if( !is_subclass_of( $blockClass, $this->_blockTypes[ $type ] ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'The class \'' . $blockClass . '\' for block \'' . $blockName . '\' of type \'' . $type . '\' does not extends its type abstract class (' . $this->_blockTypes[ $type ] . ')',
                Woops_Core_Module_Manager_Exception::EXCEPTION_INVALID_BLOCK_CLASS
            );
        }
        
        $this->_blocks[ $type ][ $blockName ] = $blockClass;
    }
    
    /**
     * 
     */
    public function getBlock( $type, $name )
    {
        $moduleName = substr( $name, 0, strpos( $name, '.' ) );
        
        if( !isset( $this->_loadedModules[ $moduleName ] ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'The module \'' . $moduleName . '\' is not loaded',
                Woops_Core_Module_Manager_Exception::EXCEPTION_MODULE_NOT_LOADED
            );
        }
        
        if( !isset( $this->_blockTypes[ $type ] ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'Cannot get the block \'' . $name . '\' because it\'s its type (\'' . $type . '\') is not a registered block type',
                Woops_Core_Module_Manager_Exception::EXCEPTION_NO_BLOCK_TYPE
            );
        }
        
        if( !isset( $this->_blocks[ $type ][ $name ] ) ) {
            
            throw new Woops_Core_Module_Manager_Exception(
                'The block \'' . $name . '\' of type \'' . $type . '\' does not exist',
                Woops_Core_Module_Manager_Exception::EXCEPTION_NO_BLOCK
            );
        }
        
        $blockClass = $this->_blocks[ $type ][ $name ];
        
        return new $blockClass();
    }
}
