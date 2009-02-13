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
 * AOP class builder
 * 
 * @author      Jean-David Gadina <macmade@eosgarden.com>
 * @version     1.0
 * @package     Woops.Core.Aop.Class
 */
class Woops_Core_Aop_Class_Builder
{
    /**
     * The minimum version of PHP required to run this class (checked by the WOOPS class manager)
     */
    const PHP_COMPATIBLE = '5.2.0';
    
    /**
     * The PHP code of the class
     */
    protected $_classCode    = '';
    
    /**
     * The AOP version of the class PHP code
     */
    protected $_classAopCode = '';
    
    /**
     * Class constructor
     * 
     * @param   string  The name of the class for which to build an AOP version
     * @return  NULL
     */
    public function __construct( $className )
    {
        if( !class_exists( $className ) ) {
            
            throw new Woops_Core_Aop_Class_Builder_Exception(
                'The class ' . $className . ' does not exist',
                Woops_Core_Aop_Class_Builder_Exception::EXCEPTION_NO_CLASS
            );
        }
        
        $reflection          = Woops_Core_Reflection_Class::getInstance( $className );
        $filePath            = $reflection->getFileName();
        
        $this->_classCode    = file_get_contents( $filePath );
        
        if( $reflection->isAopReady() ) {
            
            $this->_classAopCode = preg_replace(
                '/([\s\t]*public\s+function\s+)([^_(]+)/',
                '\1\2' . Woops_Core_Aop_Advisor::JOINPOINT_METHOD_SUFFIX,
                $this->_classCode
            );
            
        } else {
            
            $this->_classAopCode = $this->_classCode;
        }
    }
    
    /**
     * 
     */
    public function __toString()
    {
        return $this->_classAopCode;
    }
}
