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
 * Abstract for the module blocks
 *
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Module
 */
abstract class Woops_Core_Module_Block extends Woops_Core_Module_Base
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * 
     */
    protected $_blockType       = '';
    
    /**
     * 
     */
    protected $_blockName       = '';
    
    /**
     * Class constructor
     * 
     * @return  NULL
     */
    public function __construct()
    {
        parent::__construct();
        
        $blockShortName    = substr( $this->_modClass, 17 + strlen( $this->_modName ) );
        
        $this->_blockType  = substr( $blockShortName, 0, strpos( $blockShortName, '_' ) );
        $this->_blockName  = substr( $blockShortName, strpos( $blockShortName, '_' ) + 1 );
    }
}
