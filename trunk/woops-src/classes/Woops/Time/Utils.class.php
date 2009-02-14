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
 * Time relatded utilities
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Time
 */
final class Woops_Time_Utils implements Woops_Core_Singleton_Interface
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The unique instance of the class (singleton)
     */
    private static $_instance = NULL;
    
    /**
     * Class constructor
     * 
     * The class constructor is private to avoid multiple instances of the
     * class (singleton).
     * 
     * @return  NULL
     */
    private function __construct()
    {}
    
    /**
     * Clones an instance of the class
     * 
     * A call to this method will produce an exception, as the class cannot
     * be cloned (singleton).
     * 
     * @return  NULL
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
     * @return  Woops_Time_Utils    The unique instance of the class
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
     * Returns an age.
     * 
     * This method returns an age, calculated from a timestamp. By default,
     * the method takes the current time as reference, but another timestamp
     * can be specified. The method also returns by default the age in days,
     * but it can also returns it in seconds, minutes or hours.
     * 
     * @param   int     The base timestamp
     * @param   int     The time from which to calculate the age (timestamp). Will use current time if none supplied
     * @param   string  The type of age to return (s = seconds, i = minutes, h = hours, or d = days). Default is days.
     * @return  int     An age, as a numeric value
     */
    public function calcAge( $tstamp, $curTime = false, $ageType = false )
    {
        // Process age types
        switch( $ageType ) {
            
            // Seconds
            case 's':
                $division = 1;
                break;
            
            // Minutes
            case 'i':
                $division = 60;
                break;
            
            // Hours
            case 'h':
                $division = 3600;
                break;
            
            // Default - Days
            default:
                $division = 86400;
                break;
        }
        
        // Gets the current time, if none specified
        if( !$currentTime ) {
            
            $currentTime = time();
        }
        
        // Gets the differences between the two timestamps
        $diff = $currentTime - $tstamp;
        
        // Returns the age
        return ceil( $diff / $division );
    }
    
    /**
     * Converts a week number to a timestamp.
     * 
     * This method returns a timestamp for a given year (XXXX), week number, and
     * day number (0 is sunday, 6 is saturday).
     * 
     * Thanx to Nicolas Miroz for the informations about date computing.
     * 
     * @param   int The day number
     * @param   int The week number
     * @param   int The year
     * @return  int A timestamp
     */
    public function weekToDate( $day, $week, $year )
    {
        // First january of the year
        $firstDay = mktime( 0, 0, 0, 1, 1, $year );
        
        // Gets the day for the first january
        $firstDayNum = date( 'w', $firstDay );
        
        // Computes the first monday of the year and the week number for that day
        switch( $firstDayNum ) {
        
            // Sunday
            case 0:
                
                // Monday is 02.01 | Week is 1
                $monday = mktime( 0, 0, 0, 01, 02, $year );
                $weekNum = 1;
                break;
            
            // Monday
            case 1:
                
                // Monday is 01.01 | Week is 1
                $monday = mktime( 0, 0, 0, 01, 01, $year );
                $weekNum = 1;
                break;
            
            // Tuesday
            case 2:
                
                // Monday is 07.01 | Week is 2
                $monday = mktime( 0, 0, 0, 01, 07, $year );
                $weekNum = 2;
                break;
            
            // Wednesday
            case 3:
                
                // Monday is 06.01 | Week is 2
                $monday = mktime( 0, 0, 0, 01, 06, $year );
                $weekNum = 2;
                break;
            
            // Thursday
            case 4:
                
                // Monday is 05.01 | Week is 2
                $monday = mktime( 0, 0, 0, 01, 05, $year );
                $weekNum = 2;
                break;
            
            // Friday
            case 5:
                
                // Monday is 04.01 | Week is 1
                $monday = mktime( 0, 0, 0, 01, 04, $year );
                $weekNum = 1;
                break;
            
            // Saturday
            case 6:
                
                // Monday is 03.01 | Week is 1
                $monday = mktime( 0, 0, 0, 01, 03, $year );
                $weekNum = 1;
                break;
        }
        
        // Computes the difference in days from the monday to the requested day
        $dayDiff = ( $day == 0 ) ? 6 : ( $day - 1 );
        
        // Number of day to the requested date
        $numDay = ( ( $week - ( $weekNum - 1 ) - 1 ) * 7 ) + $dayDiff + date( 'd', $monday );
        
        // Creates and returns the timestamp for the requested date
        return mktime( 0, 0, 0, 01, $numDay, $year );
    }
}
