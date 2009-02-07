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
 * Color converter class
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops/Color
 */
class Woops_Color_Converter
{
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
     * Converts RGB color values into HSL
     * 
     * This method takes RGB (Red-Green-Blue) color values and converts them
     * into HSL (Hue-Saturation-Luminance) color values. RGB values are from 0
     * to 255, and HSL values are returned in an array with associative keys.
     * Note that the returned hue value is an angle (0-360), and the saturation
     * and luminance are percentage (0-100).
     * 
     * @param   number  The red value (0-255)
     * @param   number  The green value (0-255)
     * @param   number  The blue value (0-255)
     * @param   boolean Round final values
     * @return  array   An array with HSL color values
     * @see     Woops_Number_Utils::inRange
     */
    public static function rgbToHsl( $R, $G, $B, $round = true )
    {
        // Check correct values
        $R = Woops_Number_Utils::inRange( $R, 0, 255 );
        $G = Woops_Number_Utils::inRange( $G, 0, 255 );
        $B = Woops_Number_Utils::inRange( $B, 0, 255 );
        
        // HSL colors storage
        $colors = array(
            'H' => 0,   // Hue
            'S' => 0,   // Saturation
            'L' => 0,   // Luminance
        );
        
        // Converts RGB values (0-1)
        $R = ( $R / 255 );
        $G = ( $G / 255 );
        $B = ( $B / 255 );
        
        // Find the maximum and minimum RGB values
        $max = max( $R, $G, $B );
        $min = min( $R, $G, $B );
        
        // RGB delta
        $delta = $max - $min;
        
        // Compute luminance
        $colors[ 'L' ] = ( $max + $min ) / 2;
        
        // Check for chromatic data
        if( $delta === 0 ) {
            
            // No chromatic data
            $colors[ 'H' ] = 0;
            $colors[ 'S' ] = 0;
            
        } else {
            
            // Check luminance
            if( $colors[ 'L' ] < 0.5 ) {
                
                // Compute saturation
                $colors[ 'S' ] = $delta / ( $max + $min );
                
            } else {
                
                // Compute saturation
                $colors[ 'S' ] = $delta / ( 2 - $max - $min );
            }
            
            // RGB deltas
            $R_delta = ( ( ( $max - $R ) / 6 ) + ( $delta / 2 ) ) / $delta;
            $G_delta = ( ( ( $max - $G ) / 6 ) + ( $delta / 2 ) ) / $delta;
            $B_delta = ( ( ( $max - $B ) / 6 ) + ( $delta / 2 ) ) / $delta;
            
            // Check RGB max value
            if( $R === $max ) {
                
                // Compute hue
                $colors[ 'H' ] = $B_delta - $G_delta;
                
            } elseif( $G === $max ) {
                
                // Compute hue
                $colors[ 'H' ] = ( 1 / 3 ) + $R_delta - $B_delta;
                
            } elseif( $B === $max ) {
                
                // Compute hue
                $colors[ 'H' ] = ( 2 / 3 ) + $G_delta - $R_delta;
            }
            
            // Check hue
            if( $colors[ 'H' ] < 0 ) {
                
                // Increase hue
                $colors[ 'H' ] += 1;
                
            } elseif( $colors[ 'H' ] > 1 ) {
                
                // Decrease hue
                $colors[ 'H' ] -= 1;
            }
        }
        
        // Convert HSL values
        $colors[ 'H' ] = $colors[ 'H' ] * 360;  // Angle
        $colors[ 'S' ] = $colors[ 'S' ] * 100;  // Percentage
        $colors[ 'L' ] = $colors[ 'L' ] * 100;  // Percentage
        
        // Round values?
        if( $round ) {
            
            // Process each value
            foreach( $colors as $key => $value ) {
                
                // Adds the value
                $colors[ $key ] = round( $value );
            }
        }
        
        // Return HSL values
        return $colors;
    }
    
    /**
     * Converts HSL color values into RGB
     * 
     * This method takes HSL (Hue-Saturation-Luminance) color values and
     * converts them into RGB (Red-Green-Blue) color values. This is the
     * reverse method of rgbToHsl().
     * 
     * @param   number  The hue value (0-360)
     * @param   number  The saturation value (0-100)
     * @param   number  The luminance value (0-100)
     * @param   boolean Round final values
     * @return  array   An array with RGB color values
     * @see     Woops_Number_Utils::inRange
     */
    public static function hslToRgb( $H, $S, $L, $round = true )
    {
        // Check correct values
        $H = Woops_Number_Utils::inRange( $H, 0, 360 );
        $S = Woops_Number_Utils::inRange( $S, 0, 100 );
        $L = Woops_Number_Utils::inRange( $L, 0, 100 );
        
        // RGB colors storage
        $colors = array(
            'R' => 0,   // Red
            'G' => 0,   // Green
            'B' => 0,   // Blue
        );
        
        // Converts HSL values (0-1)
        $H = ( $H / 360 );
        $S = ( $S / 100 );
        $L = ( $L / 100 );
        
        // Check saturation
        if( $S === 0 ) {
            
            // No saturation
            $colors[ 'R' ] = $L * 255;
            $colors[ 'G' ] = $L * 255;
            $colors[ 'B' ] = $L * 255;
        
        } else {
            
            // Check luminance
            if( $L < 0.5 ) {
                
                // Computing variable #2
                $c2 = $L * ( 1 + $S );
                
            } else {
                
                // Computing variable #2
                $c2 = ( $L + $S ) - ( $S * $L );
            }
            
            // Computing variable #1
            $c1 = 2 * $L - $c2;
            
            // Process each RGB color
            foreach( $colors as $key => $value ) {
                
                // Create hue variable for specific RGB values
                switch( $key ) {
                    
                    // Red
                    case 'R':
                        $vH = $H + ( 1 / 3 );
                        break;
                    
                    // Green
                    case 'G':
                        $vH = $H;
                        break;
                    
                    // Blue
                    case 'B':
                        $vH = $H - ( 1 / 3 );
                        break;
                }
                
                // Adjust hue variable
                if( $vH < 0 ) {
                    
                    // Increase hue
                    $vH += 1;
                    
                } elseif( $vH > 1 ) {
                    
                    // Decrease hue
                    $vH -= 1;
                }
                
                // Check hue
                if( ( 6 * $vH ) < 1 ) {
                    
                    // Create color value
                    $colors[ $key ] = $c1 + ( $c2 - $c1 ) * 6 * $vH;
                    
                } elseif( ( 2 * $vH ) < 1 ) {
                    
                    // Create color value
                    $colors[ $key ] = $c2;
                    
                } elseif( ( 3 * $vH ) < 2 ) {
                    
                    // Create color value
                    $colors[ $key ] = $c1 + ( $c2 - $c1 ) * ( ( 2 / 3 ) - $vH ) * 6;
                    
                } else {
                    
                    // Create color value
                    $colors[ $key ] = $c1;
                }
            }
            
            // Convert RBG colors
            $colors[ 'R' ] = $colors[ 'R' ] * 255;
            $colors[ 'G' ] = $colors[ 'G' ] * 255;
            $colors[ 'B' ] = $colors[ 'B' ] * 255;
        }
        
        // Round values?
        if( $round ) {
            
            // Process each value
            foreach( $colors as $key => $value ) {
                
                // Adds the value
                $colors[ $key ] = round( $value );
            }
        }
        
        // Return RGB values
        return $colors;
    }
    
    /**
     * Converts RGB color values into HSV
     * 
     * This method takes RGB (Red-Green-Blue) color values and converts them
     * into HSV (Hue-Saturation-Value) color values. RGB values are from 0 to
     * 255, and HSV values are returned in an array with associative keys.
     * Note that the returned hue value is an angle (0-360), and the saturation
     * and value are percentage (0-100).
     * 
     * @param   number  The red value (0-255)
     * @param   number  The green value (0-255)
     * @param   number  The blue value (0-255)
     * @param   boolean Round final values
     * @return  array   An array with HSV color values
     * @see     Woops_Number_Utils::inRange
     */
    public static function rgbToHsv( $R, $G, $B, $round = true )
    {
        // Check correct values
        $R = Woops_Number_Utils::inRange( $R, 0, 255 );
        $G = Woops_Number_Utils::inRange( $G, 0, 255 );
        $B = Woops_Number_Utils::inRange( $B, 0, 255 );
        
        // HSV colors storage
        $colors = array(
            'H' => 0,   // Hue
            'S' => 0,   // Saturation
            'V' => 0,   // Luminance
        );
        
        // Converts RGB values (0-1)
        $R = ( $R / 255 );
        $G = ( $G / 255 );
        $B = ( $B / 255 );
        
        // Find the maximum and minimum RGB values
        $max = max( $R, $G, $B );
        $min = min( $R, $G, $B );
        
        // RGB delta
        $delta = $max - $min;
        
        // Compute value
        $colors[ 'V' ] = $max;
        
        // Check for chromatic data
        if( $delta === 0 ) {
            
            // No chromatic data
            $colors[ 'H' ] = 0;
            $colors[ 'S' ] = 0;
            
        } else {
            
            // Compute saturation
            $colors[ 'S' ] = $delta / $max;
            
            // RGB deltas
            $R_delta = ( ( ( $max - $R ) / 6 ) + ( $delta / 2 ) ) / $delta;
            $G_delta = ( ( ( $max - $G ) / 6 ) + ( $delta / 2 ) ) / $delta;
            $B_delta = ( ( ( $max - $B ) / 6 ) + ( $delta / 2 ) ) / $delta;
            
            // Check RGB max value
            if( $R === $max ) {
                
                // Compute hue
                $colors[ 'H' ] = $B_delta - $G_delta;
                
            } elseif( $G === $max ) {
                
                // Compute hue
                $colors[ 'H' ] = ( 1 / 3 ) + $R_delta - $B_delta;
                
            } elseif( $B === $max ) {
                
                // Compute hue
                $colors[ 'H' ] = ( 2 / 3 ) + $G_delta - $R_delta;
            }
            
            // Check hue
            if( $colors[ 'H' ] < 0 ) {
                
                // Increase hue
                $colors[ 'H' ] += 1;
                
            } elseif( $colors[ 'H' ] > 1 ) {
                
                // Decrease hue
                $colors[ 'H' ] -= 1;
            }
        }
        
        // Convert HSL values
        $colors[ 'H' ] = $colors[ 'H' ] * 360;  // Angle
        $colors[ 'S' ] = $colors[ 'S' ] * 100;  // Percentage
        $colors[ 'V' ] = $colors[ 'V' ] * 100;  // Percentage
        
        // Round values?
        if( $round ) {
            
            // Process each value
            foreach( $colors as $key => $value ) {
                
                // Adds the value
                $colors[ $key ] = round( $value );
            }
        }
        
        // Return HSV values
        return $colors;
    }
    
    /**
     * Converts HSV color values into RGB
     * 
     * This method takes HSV (Hue-Saturation-Value) color values and converts
     * them into RGB (Red-Green-Blue) color values. This is the reverse
     * method of rgb2hsv().
     * 
     * @param   number  The hue value (0-360)
     * @param   number  The saturation value (0-100)
     * @param   number  The value value (0-100)
     * @param   boolean Round final values
     * @return  array   An array with RGB color values
     * @see     Woops_Number_Utils::inRange
     */
    public static function hsvToRgb( $H, $S, $V, $round = true )
    {
        // Check correct values
        $H = Woops_Number_Utils::inRange( $H, 0, 360 );
        $S = Woops_Number_Utils::inRange( $S, 0, 100 );
        $V = Woops_Number_Utils::inRange( $V, 0, 100 );
        
        // RGB colors storage
        $colors = array(
            'R' => 0,   // Red
            'G' => 0,   // Green
            'B' => 0,   // Blue
        );
        
        // Converts HSV values (0-1)
        $H = ( $H / 360 );
        $S = ( $S / 100 );
        $V = ( $V / 100 );
        
        // Check saturation
        if( $S === 0 ) {
            
            // No saturation
            $colors[ 'R' ] = $V * 255;
            $colors[ 'G' ] = $V * 255;
            $colors[ 'B' ] = $V * 255;
        
        } else {
            
            // Hue variables
            $vH = $H * 6;
            $iH = intval( $vH );
            
            // Computing variables
            $c1 = $V * ( 1 - $S );
            $c2 = $V * ( 1 - $S * ( $vH - $iH ) );
            $c3 = $V * ( 1 - $S * ( 1 - ( $vH - $iH ) ) );
            
            // Check hue integer value
            if( $iH === 0 ) {
                
                // Create RGB values
                $vR = $V;
                $vG = $c3;
                $vB = $c1;
                
            } elseif( $iH === 1 ) {
                
                // Create RGB values
                $vR = $c2;
                $vG = $V;
                $vB = $c1;
                
            } elseif( $iH === 2 ) {
                
                // Create RGB values
                $vR = $c1;
                $vG = $V;
                $vB = $c3;
                
            } elseif( $iH === 3 ) {
                
                // Create RGB values
                $vR = $c1;
                $vG = $c2;
                $vB = $V;
                
            } elseif( $iH === 4 ) {
                
                // Create RGB values
                $vR = $c3;
                $vG = $c1;
                $vB = $V;
                
            } else {
                
                // Create RGB values
                $vR = $V;
                $vG = $c1;
                $vB = $c2;
            }
            
            // Create RBG colors
            $colors[ 'R' ] = $vR * 255;
            $colors[ 'G' ] = $vG * 255;
            $colors[ 'B' ] = $vB * 255;
        }
        
        // Round values?
        if( $round ) {
            
            // Process each value
            foreach( $colors as $key => $value ) {
                
                // Adds the value
                $colors[ $key ] = round( $value );
            }
        }
        
        // Return RGB values
        return $colors;
    }
    
    /**
     * Converts HSL color values into HSV
     * 
     * This method takes HSL (Hue-Saturation-Luminance) color values and
     * converts them into HSV (Hue-Saturation-Value) color values.
     * 
     * @param   number  The hue value (0-360)
     * @param   number  The saturation value (0-100)
     * @param   number  The luminance value (0-100)
     * @param   boolean Round final values
     * @return  array   An array with HSV color values
     * @see     hslToRgb
     * @see     rgbToHsv
     */
    public static function hslToHsv( $H, $S, $L, $round = true )
    {
        // Convert HSL to RGB
        $rgbColors = self::hslToRgb( $H, $S, $L, $round );
        
        // Convert RGB to HSV
        return self::rgbToHsv(
            $rgbColors[ 'R' ],
            $rgbColors[ 'G' ],
            $rgbColors[ 'B' ],
            $round
        );
    }
    
    /**
     * Converts HSV color values into HSL
     * 
     * This method takes HSV (Hue-Saturation-Value) color values and converts
     * them into HSL (Hue-Saturation-Luminance) color values. This is the
     * reverse method of hsl2hsv().
     * 
     * @param   number  The hue value (0-360)
     * @param   number  The saturation value (0-100)
     * @param   number  The value value (0-100)
     * @param   boolean Round final values
     * @return  array   An array with HSL color values
     * @see     hsvToRgb
     * @see     rgbToHsl
     */
    public static function hsvToHsl( $H, $S, $V, $round = true )
    {
        // Convert HSV to RGB
        $rgbColors = self::hsvToRgb( $H, $S, $V, $round );
        
        // Convert RGB to HSL
        return self::rgbToHsl(
            $rgbColors[ 'R' ],
            $rgbColors[ 'G' ],
            $rgbColors[ 'B' ],
            $round
        );
    }
}
