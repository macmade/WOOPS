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
namespace Woops\Color;

/**
 * Color utilities class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Color
 */
class Utils extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The available color methods
     */
    const METHOD_RGB = 0x01;
    const METHOD_HSL = 0x02;
    const METHOD_HSV = 0x03;
    
    /**
     * Whether the static variables are set or not
     */
    private static $_hasStatic   = false;
    
    /**
     * The instance of the number utilities class
     */
    protected static $_number    = NULL;
    
    /**
     * The instance of the color converter class
     */
    protected static $_converter = NULL;
    
    /**
     * The color method to use
     */
    protected $_colorMethod      = self::METHOD_RGB;
    
    /**
     * Class constructor
     * 
     * @param   int     The color method to use (one of the METHOD_XXX constant)
     * @return  void
     */
    public function __construct( $method = self::METHOD_RGB )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Sets the color method
        $this->setColorMethod( $method );
    }
    
    /**
     * Sets the needed static variables
     * 
     * @return  void
     */
    private static function _setStaticVars()
    {
        // Gets the instance of the number utilities
        self::$_number    = \Woops\Number\Utils::getInstance();
        
        // Gets the instance of the color converter
        self::$_converter = \Woops\Color\Converter::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Sets the color method to use
     * 
     * @param   int     The color method to use (one of the METHOD_XXX constant)
     * @return  void
     */
    public function setColorMethod( $method )
    {
        // Ensures we have an integer
        $method = ( int )$method;
        
        // Checks the color method
        if( $method === self::METHOD_HSV ) {
            
            // Hue Saturation Value
            $this->_colorMethod = self::METHOD_HSV;
            
        } elseif( $method === self::METHOD_HSV ) {
            
            // Hue Saturation Luminosity
            $this->_colorMethod = self::METHOD_HSL;
            
        } else {
            
            // Default - Red Green Blue
            $this->_colorMethod = self::METHOD_RGB;
        }
    }
    
    /**
     * Creates an hexadecimal color
     * 
     * This method is used to create an hexadecimal color representation from
     * RGB (Red-Green-Blue), HSL (Hue-Saturation-Luminance) or HSV
     * (Hue-Saturation-Value) values.
     * 
     * @param   number  The first value (red or hue, depending of the method)
     * @param   number  The second value (green or saturation, depending of the method)
     * @param   number  The third value (blue, luminosity or value, depending of the method)
     * @param   boolean Return value in uppercase
     * @return  string  The hexadecimal value of the color
     * @see     Woop\Color\Converter::hslToRgb
     * @see     Woop\Color\Converter::hsvToRgb
     * @see     Woop\Number\Utils::inRange
     */
    public function createHexColor( $v1, $v2, $v3, $uppercase = false )
    {
        // Check color creation method
        if( $this->_colorMethod === self::METHOD_HSL ) {
            
            // Convert colors
            $colors = self::$_converter->hslToRgb( $v1, $v2, $v3 );
            
            // Set converted values
            $v1 = $colors[ 'R' ];
            $v2 = $colors[ 'G' ];
            $v3 = $colors[ 'B' ];
            
        } elseif( $this->_colorMethod === self::METHOD_HSV ) {
            
            // Convert colors
            $colors = self::$_converter->hsvToRgb( $v1, $v2, $v3 );
            
            // Set converted values
            $v1 = $colors[ 'R' ];
            $v2 = $colors[ 'G' ];
            $v3 = $colors[ 'B' ];
        }
        
        // Convert each color into hexadecimal
        $R = dechex( self::$_number->inRange( $v1, 0, 255 ) );
        $G = dechex( self::$_number->inRange( $v2, 0, 255 ) );
        $B = dechex( self::$_number->inRange( $v3, 0, 255 ) );
        
        // Complete each color if needed
        $R = ( strlen( $R ) === 1 ) ? '0' . $R : $R;
        $G = ( strlen( $G ) === 1 ) ? '0' . $G : $G;
        $B = ( strlen( $B ) === 1 ) ? '0' . $B : $B;
        
        // Create full hexadecimal color
        $color =  $R . $G . $B;
        
        // Upper or lower case
        $color = ( $uppercase ) ? strtoupper( $color ) : strtolower( $color );
        
        // Return color
        return '#' . $color;
    }
    
    /**
     * Modifies an hexadecimal color
     * 
     * This method is used to modify an hexadecimal color representation by
     * adding RGB (Red-Green-Blue), HSL (Hue-Saturation-Luminance) or HSV
     * (Hue-Saturation-Value) values.
     * 
     * @param   string  The original color (hexadecimal)
     * @param   number  The first value (red or hue, depending of the method)
     * @param   number  The second value (green or saturation, depending of the method)
     * @param   number  The third value (blue, luminosity or value, depending of the method)
     * @param   boolean Return value in uppercase
     * @return  string  The hexadecimal value of the modified color
     * @see     Woop\Color\Converter::rgbToHsl
     * @see     Woop\Color\Converter::rgbToHsv
     * @see     createHexColor
     */
    public function modifyHexColor( $color, $v1, $v2, $v3, $uppercase = false )
    {
        // Erase the # character if present
        $color = ( substr( $color, 0, 1 ) === '#' ) ? substr( $color, 1, strlen( $color ) ) : $color;
        
        // Check color length (3 or 6)
        if( strlen( $color ) === 3 ) {
            
            // Extract RGB values from the hexadecimal color
            $R = hexdec( substr( $color, 0, 1 ) );
            $G = hexdec( substr( $color, 1, 1 ) );
            $B = hexdec( substr( $color, 2, 1 ) );
            
        } elseif( strlen( $color ) === 6 ) {
            
            // Extract RGB values from the hexadecimal color
            $R = hexdec( substr( $color, 0, 2 ) );
            $G = hexdec( substr( $color, 2, 2 ) );
            $B = hexdec( substr( $color, 4, 2 ) );
        }
        
        // Check modification method
        if( $this->_colorMethod === self::METHOD_HSL ) {
            
            // Convert colors
            $colors = self::$_converter->rgbToHsl( $R, $G, $B );
            
            // Create modified color
            return $this->createHexColor(
                $colors[ 'H' ] + $v1,
                $colors[ 'S' ] + $v2,
                $colors[ 'L' ] + $v3,
                $uppercase
            );
            
        } elseif( $this->_colorMethod === self::METHOD_HSV ) {
            
            // Convert colors
            $colors = self::$_converter->rgbToHsv( $R,$G,$B );
            
            // Create modified color
            return $this->createHexColor(
                $colors[ 'H' ] + $v1,
                $colors[ 'S' ] + $v2,
                $colors[ 'V' ] + $v3,
                $uppercase
            );
            
        } else {
            
            // Create modified color
            return $this->createHexColor(
                $R + $v1,
                $G + $v2,
                $B + $v3,
                $uppercase
            );
        }
    }
}
