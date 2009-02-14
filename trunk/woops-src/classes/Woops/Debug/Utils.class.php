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
     * Gets the type of a PHP variable
     * 
     * @param   mixed   The variable
     * @return  string  The type of the passed variable (object, ressource, array, string, int, link, float, null, boolean or unknown)
     */
    private static function _getVarType( &$var )
    {
        // Default - Unknown type
        $type = 'unknown';
        
        // Checks the variable type
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
        
        // Returns the variable type
        return $type;
    }
    
    /**
     * Gets an HTML representation of a PHP array
     * 
     * @param   array   The array to display
     * @param   boolean Whether the result must be returned, or directly printed
     * @return  mixed   If the $return parameter is set, this method will return a Woops_Xhtml_Tag instance, otherwise NULL
     */
    public static function viewArray( array $array, $return = false )
    {
        // Common CSS styles
        $commonStyle          = 'font-family: Verdana, sans-serif; font-size: 10px; color: #898989; ';
        
        // Creates the container DIV
        $container            = new Woops_Xhtml_Tag( 'div' );
        $container[ 'style' ] = $commonStyle;
        
        // Adds an HTML comment
        $container->comment( 'PHP array debug  - start' );
        
        // Creates a table
        $table                = $container->table;
        $table[ 'style' ]     = 'background-color: #EDF5FA; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
        
        // Adds an HTML comment
        $container->comment( 'PHP array debug - end' );
        
        // Process each value of the array
        foreach( $array as $key => &$value ) {
            
            // Gets the variable type
            $varType = self::_getVarType( $value );
            
            // Adds a row
            $row = $table->tr;
            
            // Adds two columns
            $labelCol = $row->td;
            $dataCol  = $row->td;
            
            // Sets the CSS styles
            $labelCol[ 'width' ] = '20%';
            $labelCol[ 'style' ] = $commonStyle . 'background-color: #FFFFFF; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
            $dataCol[ 'style' ]  = $commonStyle . 'background-color: #FFFFFF; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
            
            // Adds the label for the current value
            $label            = $labelCol->strong;
            $label[ 'style' ] = 'color: #0062A0;';
            $labelCol->span   = ': ' . $varType;
            $label->addTextData( $key );
            
            // Checks the value type
            if( is_array( $value ) ) {
                
                // Displays a sub-array
                $dataCol->addChildNode( self::viewArray( $value, true ) );
                
            } elseif( is_object( $value ) ) {
                
                // Displays an object
                $dataCol->div->pre = print_r( $value, true );
                
            } elseif( is_bool( $value ) ) {
                
                // Boolean value
                $value = ( $value ) ? 'true' : 'false';
                $dataCol->addTextData( $value );
                
            } else {
                
                // Other kind of data
                $dataCol->addTextData( $value );
            }
        }
        
        // Checks if we have to return the array representation
        if( $return ) {
            
            // Returns the XHTML tag object
            return $container;
        }
        
        // Prints the array representation
        print ( string )$container;
    }
    
    /**
     * Gets an HTML representation of a PHP variable
     * 
     * @param   mixed   The variable to display
     * @param   boolean Whether the result must be returned, or directly printed
     * @param   string  An optionnal header to display
     * @return  mixed   If the $return parameter is set, this method will return a Woops_Xhtml_Tag instance, otherwise NULL
     * @see     viewArray
     */
    public static function debug( $var, $return = false, $header = 'Debug informations' )
    {
        // Common CSS styles
        $commonStyle          = 'font-family: Verdana, sans-serif; font-size: 10px; color: #898989; ';
        
        // Creates the container DIV
        $container            = new Woops_Xhtml_Tag( 'div' );
        $container[ 'style' ] = $commonStyle . 'background-color: #EDF5FA; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
        
        // Adds an HTML comment
        $container->comment( 'PHP variable debug  - start' );
        
        // Creates the header
        $headerSection            = $container->div;
        $headerSection[ 'style' ] = $commonStyle . 'text-align: center; background-color: #FFFFFF; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
        $headerText               = $headerSection->strong;
        $headerText[ 'style' ]    = 'color: #0062A0; font-size: 15px';
        $headerText->addTextData( $header );
        
        // Adds the variable type
        $typeSection            = $container->div;
        $typeSection[ 'style' ] = $commonStyle . 'background-color: #FFFFFF; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
        $typeText               = $typeSection->strong;
        $typeText[ 'style' ]    = 'color: #0062A0;';
        $typeSection->span      = self::_getVarType( $var );
        $typeText->addTextData( 'Variable type:' );
        
        // Adds an HTML comment
        $container->comment( 'PHP variable debug - end' );
        
        // Creates a DIV for the data
        $dataDiv            = $container->div;
        $dataDiv[ 'style' ] = $commonStyle . 'background-color: #FFFFFF; border: solid 1px #D3E7F4; margin: 2px; padding: 2px;';
        
        // Checks the variable type
        if( is_array( $var ) ) {
            
            // Displays an array
            $dataDiv->addChildNode( self::viewArray( $var, true ) );
            
        } elseif( is_object( $var ) ) {
            
            // Displays an object
            $dataDiv->pre = print_r( $var, true );
            
        } elseif( is_bool( $var ) ) {
            
            // Boolean value
            $value = ( $var ) ? 'true' : 'false';
            $dataDiv->addTextData( $value );
            
        } else {
            
            // Other kind of data
            $dataDiv->addTextData( $var );
        }
        
        // Checks if we have to return the variable representation
        if( $return ) {
            
            // Returns the XHTML tag object
            return $container;
        }
        
        // Prints the variable representation
        print ( string )$container;
    }
}
