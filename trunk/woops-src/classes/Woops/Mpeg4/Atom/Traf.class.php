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
 * MPEG-4 TRAF atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Traf extends \Woops\Mpeg4\ContainerAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'traf';
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'tfhd':
                
                return true;
            
            case 'trun':
                
                return true;
            
            case 'sdtp':
                
                return true;
            
            case 'sbgp':
                
                return true;
            
            case 'subs':
                
                return true;
            
            default:
                
                return false;
        }
    }
}
