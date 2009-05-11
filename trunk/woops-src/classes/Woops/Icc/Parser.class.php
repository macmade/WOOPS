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
namespace Woops\Icc;

/**
 * ICC profile parser
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Icc
 */
class Parser extends \Woops\Core\Object
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The ICC profile object
     */
    protected $_profile = NULL;
    
    /**
     * Class constructor
     * 
     * @param   Woops\Icc\Binary\Stream     An ICC binary stream
     * @return  void
     */
    public function __construct( Binary\Stream $stream )
    {
        // Creates an ICC profile object
        $this->_profile = new Profile();
        
        // Gets the ICC header
        $header         = $this->_profile->getHeader();
        
        // Gets the ICC tag table
        $tagTable       = $this->_profile->getTagTAble();
        
        // Process the ICC header data
        $header->processData( $stream );
        
        // Process the ICC tag table data
        $tagTable->processData( $stream );
    }
    
    /**
     * Gets the ICC profile object
     * 
     * @return  Woops\Icc\Profile   The ICC profile object
     */
    public function getProfile()
    {
        return $this->_profile;
    }
}
