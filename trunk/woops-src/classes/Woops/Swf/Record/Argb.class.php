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

# $Id: Parser.class.php 588 2009-03-07 11:52:36Z macmade $

/**
 * SWF RGBA record
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Swf.Record
 */
class Woops_Swf_Record_Argb extends Woops_Swf_Record_Rgb
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The alpha value defining opacity
     */
    protected $_alpha = 0;
    
    /**
     * Class constructor
     * 
     * @param   int     The alpha value defining opacity (0-255)
     * @param   int     The red color value (0-255)
     * @param   int     The green color value (0-255)
     * @param   int     The blue color value (0-255)
     * @return  void
     */
    public function __construct( $alpha = 0, $red = 0, $green = 0, $blue = 0 )
    {
        // Calls the parent constructor
        parent::_construct();
        
        // Stores the alpha values
        $this->_alpha = self::$_number->inRange( $alpha, 0, 255 );
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        // Gets the alpha value
        $this->_alpha = $stream->unsignedChar();
        
        // Calls the parent method
        parent::processData( $stream );
    }
    
    /**
     * Gets the alpha value defining opacity
     * 
     * @return  int     The alpha value defining opacity
     */
    public function getAlpha()
    {
        return $this->_alpha;
    }
    
    /**
     * Sets the alpha value defining opacity
     * 
     * @param   int     The alpha value defining opacity
     * @return  void
     */
    public function setAlpha( $value )
    {
        $this->_alpha = self::$_number->inRange( $value, 0, 255 );
    }
}
