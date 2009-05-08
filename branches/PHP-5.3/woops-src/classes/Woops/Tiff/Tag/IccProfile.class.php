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
 * 
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Tiff.Tag
 */
class Woops_Tiff_Tag_IccProfile extends Woops_Tiff_Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The TIFF tag type
     */
    protected $_type    = 0x8773;
    
    /**
     * The ICC profile object
     */
    protected $_profile = NULL;
    
    /**
     * Reads tag value(s) from the binary stream
     * 
     * @param   Woops_Tiff_Binary_Stream    The binary stream
     * @param   int                         The number of values
     * @return  void
     */
    protected function _readValuesFromStream( $stream, $count )
    {
        // Gets the raw ICC profile data
        $profileData     = $stream->read( $count );
        $this->_values[] = $profileData;
        
        // Creates an ICC binary stream
        $iccStream       = new Woops_Icc_Binary_Stream( $profileData );
        
        // Creates an ICC parser
        $parser          = new Woops_Icc_Parser( $iccStream );
        
        // Stores the ICC profile
        $this->_profile  = $parser->getProfile();
    }
    
    /**
     * Gets the ICC profile
     * 
     * @return  Woops_Icc_Profile   The ICC profile object
     */
    public function getProfile()
    {
        return $this->_profile;
    }
    
}
