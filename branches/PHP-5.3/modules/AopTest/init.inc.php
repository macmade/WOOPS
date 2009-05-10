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

Woops\Core\Aop\Advisor::addAdvice(
    Woops\Core\Aop_Advisor::ADVICE_TYPE_BEFORE_CALL,
    array( 'Woops\Mod\AopTest\Interceptor', 'interceptBefore' ),
    '\Woops\Mod\HelloWorld\SayHello',
    'getBlockContent'
);

Woops\Core\Aop\Advisor::addAdvice(
    Woops\Core\Aop\Advisor::ADVICE_TYPE_AFTER_CALL,
    array( 'Woops\Mod\AopTest\Interceptor', 'interceptAfter' ),
    '\Woops\Mod\HelloWorld\SayHello',
    'getBlockContent'
);
