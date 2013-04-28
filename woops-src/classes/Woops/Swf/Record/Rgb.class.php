<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
# All rights reserved                                                          #
################################################################################

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * SWF RGB record
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Swf.Record
 */
class Woops_Swf_Record_Rgb extends Woops_Core_Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * Whether the static variables are set or not
     */
    private static $_hasStatic = false;
    
    /**
     * The instance of the number utilities class
     */
    protected static $_number  = NULL;
    
    /**
     * The red color value
     */
    protected $_red            = 0;
    
    /**
     * The green color value
     */
    protected $_green          = 0;
    
    /**
     * The red color value
     */
    protected $_blue           = 0;
    
    /**
     * Class constructor
     * 
     * @param   int     The red color value (0-255)
     * @param   int     The green color value (0-255)
     * @param   int     The blue color value (0-255)
     * @return  void
     */
    public function __construct( $red = 0, $green = 0, $blue = 0 )
    {
        // Checks if the static variables are set
        if( !self::$_hasStatic ) {
            
            // Sets the static variables
            self::_setStaticVars();
        }
        
        // Stores the RGB values
        $this->_red   = self::$_number->inRange( $red,   0, 255 );
        $this->_green = self::$_number->inRange( $green, 0, 255 );
        $this->_blue  = self::$_number->inRange( $blue,  0, 255 );
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
        
        // Static variables are set
        self::$_hasStatic = true;
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        $this->_red   = $stream->unsignedChar();
        $this->_green = $stream->unsignedChar();
        $this->_blue  = $stream->unsignedChar();
    }
    
    /**
     * Gets the red color value
     * 
     * @return  int     The red color value
     */
    public function getRed()
    {
        return $this->_red;
    }
    
    /**
     * Sets the red color value
     * 
     * @param   int     The red color value
     * @return  void
     */
    public function setRed( $value )
    {
        $this->_red = self::$_number->inRange( $value, 0, 255 );
    }
    
    /**
     * Gets the green color value
     * 
     * @return  int     The green color value
     */
    public function getGreen()
    {
        return $this->_green;
    }
    
    /**
     * Sets the green color value
     * 
     * @param   int     The green color value
     * @return  void
     */
    public function setGreen( $value )
    {
        $this->_green = self::$_number->inRange( $value, 0, 255 );
    }
    
    /**
     * Gets the blue color value
     * 
     * @return  int     The blue color value
     */
    public function getBlue()
    {
        return $this->_blue;
    }
    
    /**
     * Sets the blue color value
     * 
     * @param   int     The blue color value
     * @return  void
     */
    public function setBlue( $value )
    {
        $this->_blue = self::$_number->inRange( $value, 0, 255 );
    }
}
