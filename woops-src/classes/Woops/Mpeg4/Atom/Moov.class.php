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
 * MPEG-4 MOOV atom
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Moov extends Woops_Mpeg4_ContainerAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'moov';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'mvhd':
                
                return true;
            
            case 'trak':
                
                return true;
            
            case 'mvex':
                
                return true;
            
            case 'ipmc':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
