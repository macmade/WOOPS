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

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Core\Module;

/**
 * WOOPS module manager
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Module
 */
final class Manager extends \Woops\Core\Singleton\Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    /**
     * The configuration object
     */
    protected $_conf              = NULL;
    
    /**
     * The environment object
     */
    protected $_env               = NULL;
    
    /**
     * The directories with WOOPS modules
     */
    protected $_modulesDirs       = array();
    
    /**
     * The existing modules
     */
    protected $_modules           = array();
    
    /**
     * The loaded (active) modules
     */
    protected $_loadedModules     = array();
    
    /**
     * 
     */
    protected $_blockTypes        = array();
    
    /**
     * 
     */
    protected $_blocks            = array();
    
    /**
     * 
     */
    protected $_moduleDeps        = array();
    
    /**
     * 
     */
    protected $_priorityModules   = array();
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    protected function __construct()
    {
        $this->_conf = \Woops\Core\Config\Getter::getInstance();
        $this->_env  = \Woops\Core\Env\Getter::getInstance();
        
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
                    
                    throw new Manager\Exception(
                        'The module \'' . $moduleName . '\' does not exist',
                       Manager\Exception::EXCEPTION_NO_MODULE
                    );
                }
                
                $this->_loadedModules[ $moduleName ] = false;
            }
            
            foreach( $this->_loadedModules as $moduleName => $void ) {
                
                $this->_loadModuleInfos( $moduleName );
            }
        }
    }
    
    /**
     * 
     */
    protected function _registerModuleDirectory( $path, $relPath )
    {
        if( !file_exists( $path ) && !is_dir( $path ) ) {
            
            throw new Manager\Exception(
                'The specified modules directory does not exist (path: ' . $path . ')',
                Manager\Exception::EXCEPTION_NO_DIRECTORY
            );
        }
        
        $this->_modulesDirs[] = $path;
        
        $dirIterator = new \DirectoryIterator( $path );
        
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
    protected function _loadModuleInfos( $moduleName )
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
                        
                        if( !isset( $this->_loadedModules[ $key ] ) ) {
                            
                            throw new Manager\Exception(
                                'The module \'' . $moduleName . '\' has a dependancy to the module \'' . $key . '\' which is not loaded',
                                Manager\Exception::EXCEPTION_MODULE_NOT_LOADED
                            );
                        }
                    }
                }
                
                if( isset( $infos->priority ) ) {
                    
                    $this->_priorityModules[ $moduleName ] = true;
                }
                
            } catch( Exception $e ) {
                
                throw new Manager\Exception(
                    $e->getMessage(),
                    Manager\Exception::EXCEPTION_BAD_XML
                );
            }
        }
    }
    
    /**
     * 
     */
    protected function _initModule( $moduleName )
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
            
            throw new Manager\Exception(
                'The module \'' . $moduleName . '\' is not loaded',
                Manager\Exception::EXCEPTION_MODULE_NOT_LOADED
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
            
            throw new Manager\Exception(
                'The module \'' . $moduleName . '\' is not loaded',
                Manager\Exception::EXCEPTION_MODULE_NOT_LOADED
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
            
            throw new Manager\Exception(
                'The block type \'' . $type . '\' is already registered',
                Manager\Exception::EXCEPTION_BLOCK_TYPE_EXISTS
            );
        }
        
        if( !class_exists( $abstractClass ) ) {
            
            throw new Manager\Exception(
                'Cannot register the block type \'' . $type . '\' because it\'s abstract class (\'' . $abstractClass . '\') does not exist',
                Manager\Exception::EXCEPTION_NO_BLOCK_ABSTRACT
            );
        }
        
        if( !is_subclass_of( $abstractClass, '\Woops\Core\Module\Block' ) ) {
            
            throw new Manager\Exception(
                'The abstract class \'' . $abstractClass . '\' for block type \'' . $type . '\' does not extends the \'Woops\Core\Module\Block\' abstract class',
                Manager\Exception::EXCEPTION_INVALID_BLOCK_ABSTRACT
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
            
            throw new Manager\Exception(
                'The module \'' . $moduleName . '\' is not loaded',
                Manager\Exception::EXCEPTION_MODULE_NOT_LOADED
            );
        }
        
        $blockName  = $moduleName . '.' . $name;
        
        if( !isset( $this->_blockTypes[ $type ] ) ) {
            
            throw new Manager\Exception(
                'Cannot register the block \'' . $blockName . '\' because it\'s its type (\'' . $type . '\') is not a registered block type',
                Manager\Exception::EXCEPTION_NO_BLOCK_TYPE
            );
        }
        
        if( isset( $this->_blocks[ $type ][ $blockName ] ) ) {
            
            throw new Manager\Exception(
                'The block \'' . $blockName . '\' is already registered for type \'' . $type . '\'',
                Manager\Exception::EXCEPTION_BLOCK_EXISTS
            );
        }
        
        if( !class_exists( $blockClass ) ) {
            
            throw new Manager\Exception(
                'The class \'' . $blockClass . '\' for block \'' . $blockName . '\' of type \'' . $type . '\' does not exist',
                Manager\Exception::EXCEPTION_NO_BLOCK_CLASS
            );
        }
        
        if( !is_subclass_of( $blockClass, $this->_blockTypes[ $type ] ) ) {
            
            throw new Manager\Exception(
                'The class \'' . $blockClass . '\' for block \'' . $blockName . '\' of type \'' . $type . '\' does not extends its type abstract class (' . $this->_blockTypes[ $type ] . ')',
                Manager\Exception::EXCEPTION_INVALID_BLOCK_CLASS
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
            
            throw new Manager\Exception(
                'The module \'' . $moduleName . '\' is not loaded',
                Manager\Exception::EXCEPTION_MODULE_NOT_LOADED
            );
        }
        
        if( !isset( $this->_blockTypes[ $type ] ) ) {
            
            throw new Manager\Exception(
                'Cannot get the block \'' . $name . '\' because it\'s its type (\'' . $type . '\') is not a registered block type',
                Manager\Exception::EXCEPTION_NO_BLOCK_TYPE
            );
        }
        
        if( !isset( $this->_blocks[ $type ][ $name ] ) ) {
            
            throw new Manager\Exception(
                'The block \'' . $name . '\' of type \'' . $type . '\' does not exist',
                Manager\Exception::EXCEPTION_NO_BLOCK
            );
        }
        
        $blockClass = $this->_blocks[ $type ][ $name ];
        
        return new $blockClass();
    }
}
