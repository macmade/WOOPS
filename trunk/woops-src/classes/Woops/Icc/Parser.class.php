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
 * ICC profile parser
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Icc
 */
class Woops_Icc_Parser
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The ICC profile object
     */
    protected $_profile = NULL;
    
    /**
     * Class constructor
     * 
     * @param   Woops_Icc_Binary_Stream     An ICC binary stream
     * @return  void
     */
    public function __construct( Woops_Icc_Binary_Stream $stream )
    {
        // Creates an ICC profile object
        $this->_profile = new Woops_Icc_Profile();
        
        // Gets the ICC header
        $header         = $this->_profile->getHeader();
        
        // Process the ICC header data
        $header->processData( $stream );
    }
    
    /**
     * Gets the ICC profile object
     * 
     * @return  Woops_Icc_Profile   The ICC profile object
     */
    public function getProfile()
    {
        return $this->_profile;
    }
}
