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
 * MPEG4 file
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Mpeg4
 */
class File extends ContainerAtom
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.3.0';
    
    public function __toString()
    {
        $childrenData = '';
        
        foreach( $this->_children as $childAtom ) {
            
            $childrenData .= ( string )$childAtom;
        }
        
        return $childrenData;
    }
    
    public function addChild( $childType )
    {
        $atom          = parent::addChild( $childType );
        $atom->_parent = NULL;
        
        return $atom;
    }
    
    public function getLength()
    {
        $length = 0;
        
        foreach( $this->_children as $childAtom ) {
            
            $length += $childAtom->getLength();
        }
        
        return $length;
    }
    
    public function setExtended( $value = true )
    {
        return false;
    }
    
    public function validChildType( $type )
    {
        switch( $type ) {
            
            case 'ftyp':
                
                return true;
            
            case 'pdin':
                
                return true;
            
            case 'moov':
                
                return true;
            
            case 'moof':
                
                return true;
            
            case 'mfra':
                
                return true;
            
            case 'mdat':
                
                return true;
            
            case 'free':
                
                return true;
            
            case 'skip':
                
                return true;
            
            case 'meta':
                
                return true;
            
            default:
                
                return false;
        }
    }
}

