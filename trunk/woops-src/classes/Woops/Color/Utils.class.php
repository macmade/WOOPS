<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * Color utilities class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Color
 */
class Woops_Color_Utils
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Wether the static variables are set or not
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
     * The color method to use (RGB, HSL or HSV)
     */
    protected $_colorMethod      = 'RGB';
    
    /**
     * Class constructor
     * 
     * @param   string  The color method to use (RGB, HSV or HSL)
     * @return  void
     */
    public function __construct( $method = 'RGB' )
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
        self::$_number    = Woops_Number_Utils::getInstance();
        
        // Gets the instance of the color converter
        self::$_converter = Woops_Color_Converter::getInstance();
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Sets the color method to use
     * 
     * @param   string  The color method to use (RGB, HSV or HSL)
     * @return  void
     */
    public function setColorMethod( $method )
    {
        // Converts the method to uppercase
        $method = strtoupper( $method );
        
        // Checks the color method
        switch( $method ) {
            
            // Hue Saturation Value
            case 'HSV':
                
                $this->_colorMethod = 'HSV';
                break;
            
            // Hue Saturation Luminosity
            case 'HSL':
                
                $this->_colorMethod = 'HSL';
                break;
            
            // Default - Red Green Blue
            default:
                
                $this->_colorMethod = 'RGB';
                break;
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
     * @see     Woop_Color_Converter::hslToRgb
     * @see     Woop_Color_Converter::hsvToRgb
     * @see     Woop_Number_Utils::inRange
     */
    public function createHexColor( $v1, $v2, $v3, $uppercase = false )
    {
        // Check color creation method
        if( $this->_colorMethod === 'HSL' ) {
            
            // Convert colors
            $colors = self::$_converter->hslToRgb( $v1, $v2, $v3 );
            
            // Set converted values
            $v1 = $colors[ 'R' ];
            $v2 = $colors[ 'G' ];
            $v3 = $colors[ 'B' ];
            
        } elseif( $this->_colorMethod === 'HSV' ) {
            
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
     * @see     Woop_Color_Converter::rgbToHsl
     * @see     Woop_Color_Converter::rgbToHsv
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
        if( $this->_colorMethod === 'HSL' ) {
            
            // Convert colors
            $colors = self::$_converter->rgbToHsl( $R, $G, $B );
            
            // Create modified color
            return $this->createHexColor(
                $colors[ 'H' ] + $v1,
                $colors[ 'S' ] + $v2,
                $colors[ 'L' ] + $v3,
                'HSL',
                $uppercase
            );
            
        } elseif( $this->_colorMethod === 'HSV' ) {
            
            // Convert colors
            $colors = self::$_converter->rgbToHsv( $R,$G,$B );
            
            // Create modified color
            return $this->createHexColor(
                $colors[ 'H' ] + $v1,
                $colors[ 'S' ] + $v2,
                $colors[ 'V' ] + $v3,
                'HSV',
                $uppercase
            );
            
        } else {
            
            // Create modified color
            return $this->createHexColor(
                $R + $v1,
                $G + $v2,
                $B + $v3,
                'RGB',
                $uppercase
            );
        }
    }
}
