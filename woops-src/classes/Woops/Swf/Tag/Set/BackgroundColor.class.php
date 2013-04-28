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

# $Id: Stream.class.php 637 2009-03-09 09:05:52Z macmade $

/**
 * SWF SetBackgroundColor tag
 * 
 * The SetBackgroundColor tag sets the background color of the display.
 * The minimum file format version is SWF 1. 
 * 
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Swf.Tag.Set
 */
class Woops_Swf_Tag_Set_BackgroundColor extends Woops_Swf_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The SWF tag type
     */
    protected $_type = 0x09;
    
    /**
     * The RGB record
     */
    protected $_rgb  = NULL;
    
    /**
     * Class constructor
     * 
     * @param   Woops_Swf_File  The instance of the SWF file in which the tag is contained
     */
    public function __construct( Woops_Swf_File $file )
    {
        // Calls the parent constructor
        parent::__construct( $file );
        
        // Creates a new RGB record
        $this->_rgb = new Woops_Swf_Record_Rgb();
    }
    
    /**
     * Process the raw data from a binary stream
     * 
     * @return  void
     */
    public function processData( Woops_Swf_Binary_Stream $stream )
    {
        $this->_rgb->processData( $stream );
    }
    
    /**
     * Gets the RGB record
     * 
     * @return  Woops_Swf_Record_Rgb    The RGB record
     */
    public function getRgb()
    {
        return $this->_rgb;
    }
}
