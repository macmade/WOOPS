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

# $Id$

/**
 * MPEG-4 unknown atom
 * 
 * This class is used for the MPEG-4 atoms that are not part of ISO-14496-12.
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mpeg4
 */
final class Woops_Mpeg4_UnknownAtom extends Woops_Mpeg4_DataAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
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
        $this->_type =substr( $type, 0, 4 );
    }
    
    /**
     * Process the atom data
     * 
     * @return  object  The processed atom data
     */
    public function getProcessedData()
    {
        return new stdClass();
    }
}
