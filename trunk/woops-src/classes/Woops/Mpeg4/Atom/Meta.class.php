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
 * MPEG-4 META atom
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4.Atom
 */
final class Meta extends \Woops\Mpeg4\ContainerAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0RC2';
    
    /**
     * The atom type
     */
    protected $_type = 'meta';
    
    public function validChildType( $type )
    {
        switch( $type )
        {
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
