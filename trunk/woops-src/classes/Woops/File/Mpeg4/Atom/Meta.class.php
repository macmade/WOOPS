<?php
################################################################################
#                                                                              #
#                WOOPS - Web Object Oriented Programming System                #
#                                                                              #
#                               COPYRIGHT NOTICE                               #
#                                                                              #
# (c) 2009 Jean-David Gadina (macmade@eosgarden.com)                           #
# All rights reserved                                                          #
################################################################################

# $Id$

/**
 * MPEG-4 META atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Meta extends Woops_File_Mpeg4_ContainerAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'meta';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'hdlr':
                
                return true;
            
            case 'dinf':
                
                return true;
            
            case 'ipmc':
                
                return true;
            
            case 'iloc':
                
                return true;
            
            case 'ipro':
                
                return true;
            
            case 'iinf':
                
                return true;
            
            case 'xml':
                
                return true;
            
            case 'bxml':
                
                return true;
            
            case 'pitm':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
