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
     * 
     */
    protected $_content = NULL;
    
    /**
     * 
     */
    protected $_ini     = NULL;
    
    /**
     * 
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->_ini     = new Woops_File_Ini_Parser( self::$_env->getPath( 'config.ini.php' ) );
        
        $this->_content = new Woops_Xhtml_Tag( 'form' );
        
        $ini = $this->_ini->getIniArray();
        
        foreach( $ini as $section => $items ) {
            
            $container     = $this->_content->div;
            $container->h2 = $section;
            
            $counter       = 0;
            $itemsCount    = count( $items );
            
            foreach( $items as $name => $item ) {
                
                $itemContainer            = $container->div;
                
                if( $itemsCount > 1 ) {
                    
                    $itemContainer[ 'class' ] = ( $counter === 0 ) ? 'left' : 'right';
                }
                
                $box                      = $itemContainer->div;
                $box[ 'class' ]           = 'box';
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
                
                $type = ( isset( $item[ 'comments' ][ 'type' ] ) ) ? $item[ 'comments' ][ 'type' ] : 'string';
                
                $formElement            = $box->div;
                $formElement[ 'class' ] = 'form-element';
                
                switch( $type ) {
                    
                    case 'string':
                        
                        $value = ( is_array( $item[ 'value' ] ) ) ? implode( ', ', $item[ 'value' ] ) : $item[ 'value' ];
                        
                        $input            = $formElement->input;
                        $input[ 'type' ]  = 'text';
                        $input[ 'size' ]  = 30;
                        $input[ 'value' ] = $value;
                        break;
                    
                    case 'int':
                        
                        $input            = $formElement->input;
                        $input[ 'type' ]  = 'text';
                        $input[ 'size' ]  = 30;
                        $input[ 'value' ] = $item[ 'value' ];
                        break;
                    
                    case 'boolean':
                        
                        $input            = $formElement->input;
                        $input[ 'type' ]  = 'checkbox';
                        
                        if( $item[ 'value' ] ) {
                            
                            $input[ 'checked' ] = 'checked';
                        }
                        break;
                    
                    case 'select':
                        
                        $select = $formElement->select;
                        
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
                
                if( $counter === 0 ) {
                    
                    $counter = 1;
                    
                } else {
                    
                    $counter            = 0;
                    $clearer            = $container->div;
                    $clearer[ 'class' ] = 'clearer';
                }
            }
        }
        
        $submit           = $this->_content->input;
        $submit[ 'type' ] = 'submit';
    }
    
    /**
     * 
     */
    public function __toString()
    {
        return ( string )$this->_content;
    }
}
