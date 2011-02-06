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

# $Id$

// File encoding
declare( ENCODING = 'UTF-8' );

// Internal namespace
namespace Woops\Mpeg4;

/**
 * MPEG-4 unknown atom
 * 
 * This class is used for the MPEG-4 atoms that are not part of ISO-14496-12.
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4
 */
final class UnknownAtom extends DataAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = '';
    
    /**
     * Class constructor
     * 
     * @param   string  The atom type
     * @return  NULL
     */
    public function __construct( $type )
    {
        // Sets the atom type
        $this->_type = substr( $type, 0, 4 );
    }
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        return new \stdClass();
    }
}
