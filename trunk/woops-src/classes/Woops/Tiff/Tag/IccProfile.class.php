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

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Tiff\Tag;

/**
 * 
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Tiff.Tag
 */
class IccProfile extends \Woops\Tiff\Tag
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
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
     * @param   Woops\Tiff\Binary\Stream    The binary stream
     * @param   int                         The number of values
     * @return  void
     */
    protected function _readValuesFromStream( \Woops\Tiff\Binary\Stream $stream, $count )
    {
        // Gets the raw ICC profile data
        $profileData     = $stream->read( $count );
        $this->_values[] = $profileData;
        
        // Creates an ICC binary stream
        $iccStream       = new \Woops\Icc\Binary\Stream( $profileData );
        
        // Creates an ICC parser
        $parser          = new \Woops\Icc\Parser( $iccStream );
        
        // Stores the ICC profile
        $this->_profile  = $parser->getProfile();
    }
    
    /**
     * Gets the ICC profile
     * 
     * @return  Woops\Icc\Profile   The ICC profile object
     */
    public function getProfile()
    {
        return $this->_profile;
    }
    
}
