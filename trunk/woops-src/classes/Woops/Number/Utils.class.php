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
 * Number related utilities
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Number
 */
class Woops_Number_Utils
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
     * Ensures a number is in a specified range
     * 
     * This method forces the specified number into the boundaries of a
     * minimum and maximum number.
     * 
     * @param   numbe   The number to check
     * @param   number  The minimum value
     * @param   number  The maximum value
     * @param   boolean Evaluates the number as an integer
     * @return  number  A number in the specified range
     */
    public static function inRange( $number, $min, $max, $int = false )
    {
        // Checks if we must evaluate the number as an integer
        if( $int ) {
            
            // Converts the number to an integer
            $number = ( int )$number;
        }
        
        // Checks the number
        if( $number > $max ) {
            
            // Number is bigger than maximum value
            $number = $max;
            
        } elseif( $number < $min ) {
            
            // Number is smaller than minimal value
            $number = $min;
        }
        
        // Returns the number
        return $number;
    }
}
