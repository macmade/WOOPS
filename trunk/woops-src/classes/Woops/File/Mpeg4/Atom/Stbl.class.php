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
 * MPEG-4 STBL atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.File.Mpeg4.Atom
 */
final class Woops_File_Mpeg4_Atom_Stbl extends Woops_File_Mpeg4_ContainerAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The atom type
     */
    protected $_type = 'stbl';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'stsd':
                
                return true;
            
            case 'stts':
                
                return true;
            
            case 'ctts':
                
                return true;
            
            case 'stsc':
                
                return true;
            
            case 'stsz':
                
                return true;
            
            case 'stz2':
                
                return true;
            
            case 'stco':
                
                return true;
            
            case 'co64':
                
                return true;
            
            case 'stss':
                
                return true;
            
            case 'stsh':
                
                return true;
            
            case 'padb':
                
                return true;
            
            case 'stdp':
                
                return true;
            
            case 'sdtp':
                
                return true;
            
            case 'sbgp':
                
                return true;
            
            case 'sgpd':
                
                return true;
            
            case 'subs':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
