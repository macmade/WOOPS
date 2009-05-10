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
namespace Woops\Mpeg4\Atom;

/**
 * MPEG-4 STBL atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Stbl extends \Woops\Mpeg4\ContainerAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
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
