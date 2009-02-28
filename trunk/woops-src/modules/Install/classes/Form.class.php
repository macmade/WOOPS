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
 * WOOPS installation form
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mod.Install
 */
class Woops_Mod_Install_Form extends Woops_Core_Module_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The module's content
     */
    protected $_content = NULL;
    
    /**
     * The INI file parser
     */
    protected $_ini     = NULL;
    
    /**
     * Class constructor
     * 
     * @return  void
     */
    public function __construct()
    {
        // Calls the parent constructor
        parent::__construct();
        
        // Creates an INI file parser
        $this->_ini     = new Woops_File_Ini_Parser( self::$_env->getPath( 'config.ini.php' ) );
        
        // Creates the base form tag
        $this->_content = new Woops_Xhtml_Tag( 'form' );
        $this->_content[ 'action' ]  = Woops_Core_Env_Getter::getInstance()->getSourceWebPath( 'scripts/install/' );
        
        // Gets the ini values
        $ini = $this->_ini->getIniArray();
        
        // Process each section of the INI file
        foreach( $ini as $section => $items ) {
            
            // Section container
            $container               = $this->_content->div;
            
            // Section title
            $sectionTitle            = $container->h2;
            $sectionTitle[ 'class' ] = $section;
            $sectionTitle->addTextData( $section );
            
            // Creates the section items form elements
            $this->_createSectionItems( $section, $items, $container );
        }
        
        // Adds the submit button
        $submit            = $this->_content->input;
        $submit[ 'type' ]  = 'submit';
        $submit[ 'value' ] = $this->_lang->writeConfValues;
    }
    
    /**
     * Gets the install form
     * 
     * @return  string  The install form
     */
    public function __toString()
    {
        return ( string )$this->_content;
    }
    
    /**
     * 
     */
    public function _createSectionItems( $section, array $items, Woops_Xhtml_Tag $container )
    {
        // Counter variables
        $counter    = 0;
        $itemsCount = count( $items );
        
        // Process each item in the current section
        foreach( $items as $name => $item ) {
            
            $itemContainer            = $container->div;
            
            if( $itemsCount > 1 ) {
                
                $itemContainer[ 'class' ] = ( $counter === 0 ) ? 'left' : 'right';
            }
            
            $box                      = $itemContainer->div;
            $box[ 'class' ]           = ( isset( $item[ 'comments' ][ 'required' ] ) ) ? 'box-required' : 'box';
            $box->h4                  = $name;
            
            if( isset( $item[ 'comments' ][ 'title' ] ) ) {
                
                $title            = $box->div;
                $title[ 'class' ] = 'title';
                $title->span      = $item[ 'comments' ][ 'title' ];
            }
            
            if( isset( $item[ 'comments' ][ 'description' ] ) ) {
                
                $title            = $box->div;
                $title[ 'class' ] = 'description';
                $title->addTextData( $item[ 'comments' ][ 'description' ] );
            }
            
            $formElement            = $box->div;
            $formElement[ 'class' ] = 'form-element';
            
            if( $section === 'modules' && $name === 'loaded' ) {
                
                $this->_createModuleList( $section, $name, $item, $formElement );
                
            } else {
                
                $this->_createFormItem( $section, $name, $item, $formElement );
            }
            
            if( $counter === 0 ) {
                
                $counter = 1;
                
            } else {
                
                $counter            = 0;
                $clearer            = $container->div;
                $clearer[ 'class' ] = 'clearer';
            }
        }
    }
    
    /**
     * 
     */
    protected function _createModuleList( $section, $itemName, array $item, Woops_Xhtml_Tag $container )
    {
        $modules = Woops_Core_Module_Manager::getInstance()->getAvailableModules();
        
        foreach( $modules as $modName => $modPath ) {
            
            $module = $container->div;
            $module[ 'class' ] = 'module';
            $check = $module->input;
            $check[ 'type' ] = 'checkbox';
            $module->label = $modName;
            
            if( in_array( $modName, $item[ 'value' ] ) ) {
                
                $check[ 'checked' ] = 'checked';
                $module[ 'class' ] = 'module-loaded';
            }
            
            if( $modName === 'Install' ) {
                
                $check[ 'disabled' ] = 'disabled';
            }
        }
    }
    
    /**
     * 
     */
    protected function _createFormItem( $section, $itemName, array $item, Woops_Xhtml_Tag $container )
    {
        $type = ( isset( $item[ 'comments' ][ 'type' ] ) ) ? $item[ 'comments' ][ 'type' ] : 'string';
        
        switch( $type ) {
                    
            case 'string':
                
                $value = ( is_array( $item[ 'value' ] ) ) ? implode( ', ', $item[ 'value' ] ) : $item[ 'value' ];
                
                $input            = $container->input;
                $input[ 'type' ]  = 'text';
                $input[ 'size' ]  = 30;
                $input[ 'value' ] = $value;
                break;
            
            case 'int':
                
                $input            = $container->input;
                $input[ 'type' ]  = 'text';
                $input[ 'size' ]  = 30;
                $input[ 'value' ] = $item[ 'value' ];
                break;
            
            case 'boolean':
                
                $input            = $container->input;
                $input[ 'type' ]  = 'checkbox';
                
                if( $item[ 'value' ] ) {
                    
                    $input[ 'checked' ] = 'checked';
                }
                break;
            
            case 'select':
                
                $select = $container->select;
                
                if( !isset( $item[ 'comments' ][ 'required' ] ) ) {
                    
                    $option            = $select->option;
                    $option[ 'value' ] = '';
                }
                
                if( isset( $item[ 'comments' ][ 'options' ] ) ) {
                    
                    foreach( $item[ 'comments' ][ 'options' ] as $key => $value ) {
                        
                        $option            = $select->option;
                        $option[ 'value' ] = $value;
                        $option->addTextData( $value );
                        
                        if( $value === $item[ 'value' ] ) {
                            
                            $option[ 'selected' ] = 'selected';
                        }
                    }
                }
                
                break;
        }
    }
}
