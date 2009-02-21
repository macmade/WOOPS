<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 eosgarden - Jean-David Gadina (macmade@eosgarden.com)               #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * MPEG-4 SINF atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Sinf extends Woops_File_Mpeg4_ContainerAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'sinf';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'frma':
                
                return true;
            
            case 'imif':
                
                return true;
            
            case 'schm':
                
                return true;
            
            case 'schi':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
