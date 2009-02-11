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
 * Debug utilities
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Debug
 */
class Woops_Debug_Utils
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Class constructor
     * 
     * The class constructor is private as all methods from this class are
     * static.
     * 
     * @return  NULL
     */
    private function __construct()
    {}
    
    /**
     * 
     */
    private static function _getVarType( &$var )
    {
        $type = 'unknown';
        
        if( is_object( $var ) ) {
            
            $type = 'object';
            
        } elseif( is_resource( $var ) ) {
            
            $type = 'ressource';
            
        } elseif( is_array( $var ) ) {
            
            $type = 'array';
            
        } elseif( is_string( $var ) ) {
            
            $type = 'string';
            
        } elseif( is_int( $var ) ) {
            
            $type = 'int';
            
        } elseif( is_link( $var ) ) {
            
            $type = 'link';
            
        } elseif( is_float( $var ) ) {
            
            $type = 'float';
            
        } elseif( is_null( $var ) ) {
            
            $type = 'null';
            
        } elseif( is_bool( $var ) ) {
            
            $type = 'boolean';
        }
        
        return $type;
    }
    
    /**
     * 
     */
    public static function viewArray( array $array, $return = false )
    {
        $commonStyle          = 'font-family: Verdana, sans-serif; font-size: 10px; color: #898989; ';
        
        $container            = new Woops_Xhtml_Tag( 'div' );
        $container[ 'style' ] = $commonStyle;
        
        $container->comment( 'PHP array debug  - start' );
        
        $table                = $container->table;
        $table[ 'style' ]     = 'background-color: #EDF5FA; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
        
        $container->comment( 'PHP array debug - end' );
        
        foreach( $array as $key => &$value ) {
            
            $varType = self::_getVarType( $value );
            
            $row = $table->tr;
            
            $labelCol = $row->td;
            $dataCol  = $row->td;
            
            $labelCol[ 'width' ] = '20%';
            $labelCol[ 'style' ] = $commonStyle . 'background-color: #FFFFFF; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
            $dataCol[ 'style' ]  = $commonStyle . 'background-color: #FFFFFF; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
            
            $label            = $labelCol->strong;
            $label[ 'style' ] = 'color: #0062A0;';
            $labelCol->span   = ': ' . $varType;
            $label->addTextData( $key );
            
            if( is_array( $value ) ) {
                
                $dataCol->addChildNode( self::viewArray( $value, true ) );
                
            } elseif( is_object( $value ) ) {
                
                $dataCol->div->pre = print_r( $value, true );
                
            } elseif( is_bool( $value ) ) {
                
                $value = ( $value ) ? 'true' : 'false';
                $dataCol->addTextData( $value );
                
            } else {
                
                $dataCol->addTextData( $value );
            }
        }
        
        if( $return ) {
            
            return $container;
        }
        
        print ( string )$container;
    }
    
    /**
     * 
     */
    public static function debug( $var, $return = false, $header = 'Debug informations' )
    {
        $commonStyle          = 'font-family: Verdana, sans-serif; font-size: 10px; color: #898989; ';
        
        $container            = new Woops_Xhtml_Tag( 'div' );
        $container[ 'style' ] = $commonStyle . 'background-color: #EDF5FA; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
        
        $container->comment( 'PHP variable debug  - start' );
        
        $headerSection            = $container->div;
        $headerSection[ 'style' ] = $commonStyle . 'text-align: center; background-color: #FFFFFF; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
        $headerText               = $headerSection->strong;
        $headerText[ 'style' ]    = 'color: #0062A0; font-size: 15px';
        $headerText->addTextData( $header );
        
        $typeSection            = $container->div;
        $typeSection[ 'style' ] = $commonStyle . 'background-color: #FFFFFF; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
        $typeText               = $typeSection->strong;
        $typeText[ 'style' ]    = 'color: #0062A0;';
        $typeSection->span      = self::_getVarType( $var );
        $typeText->addTextData( 'Variable type:' );
        
        $container->comment( 'PHP variable debug - end' );
        
        $dataDiv            = $container->div;
        $dataDiv[ 'style' ] = $commonStyle . 'background-color: #FFFFFF; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
        
        if( is_array( $var ) ) {
            
            $dataDiv->addChildNode( self::viewArray( $var, true ) );
            
        } elseif( is_object( $var ) ) {
                
                $dataDiv->pre = print_r( $var, true );
                
            } elseif( is_bool( $var ) ) {
                
                $value = ( $var ) ? 'true' : 'false';
                $dataDiv->addTextData( $value );
                
            } else {
                
                $dataDiv->addTextData( $var );
            }
        
        if( $return ) {
            
            return $container;
        }
        
        print ( string )$container;
    }
}
