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
 * MPEG-4 MINF atom
 *
 * @author      Jean-David Gadina - www.xs-labs.com
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Woops_Mpeg4_Atom_Minf extends Woops_Mpeg4_ContainerAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'minf';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'vmhd':
                
                return true;
            
            case 'smhd':
                
                return true;
            
            case 'hmhd':
                
                return true;
            
            case 'nmhd':
                
                return true;
            
            case 'dinf':
                
                return true;
            
            case 'stbl':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
