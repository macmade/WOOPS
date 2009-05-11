<?php
	
	// Includes the initialization script
	require_once(
		__DIR__
	  . DIRECTORY_SEPARATOR
	  . '..'
	  . DIRECTORY_SEPARATOR
	  . '..'
	  . DIRECTORY_SEPARATOR
	  . 'init.inc.php'
	);
	
	// File encoding
	declare( ENCODING = 'UTF-8' );
	
	// Creates an AMF server
	$AMF_SERVER = new Woops\Amf\Server();
	$AMF_SERVER->handle();
	print $AMF_SERVER;
	
	// WOOPS environment object
	$ENV    = Woops\Core\Env\Getter::getInstance();
	
	// URL of the AMF server
	$URL    = urlencode( ( ( $ENV->HTTPS ) ? 'https://' : 'http://' )
			. $ENV->HTTP_HOST
			. $ENV->getSourceWebPath( 'tests/amf/' ) );
?>

<!-- $Id: index.php 658 2009-03-09 16:19:21Z macmade $ -->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		
		<!--
		
		################################################################################
		#                                                                              #
		#                WOOPS - Web Object Oriented Programming System                #
		#                                                                              #
		#                               COPYRIGHT NOTICE                               #
		#                                                                              #
		# Copyright (C) 2009 Jean-David Gadina (macmade@eosgarden.com)                 #
		# All rights reserved                                                          #
		################################################################################
		
		-->
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>WOOPS - Web Object Oriented Programming System</title>
		<link rel="schema.dc" href="http://purl.org/metadata/dublin_core_elements" />
		<link rel="stylesheet" rev="stylesheet" href="css/base.css" type="text/css" media="screen" charset="utf-8" />
		<link rev="made" href="mailto:macmade@eosgarden.com" />
		<meta http-equiv="content-language" content="en" />
		<meta http-equiv="reply-to" content="macmade@eosgarden.com" />
		<meta name="author" content="Jean-David Gadina" />
		<meta name="copyright" content="Copyright (C) 2009 Jean-David Gadina" />
		<meta name="DC.Creator" content="Jean-David Gadina" />
		<meta name="DC.Language" scheme="NISOZ39.50" content="en" />
		<meta name="DC.Rights" content="Copyright (C) 2009 Jean-David Gadina" />
		<meta name="generator" content="BBEdit 9.1" />
		<meta name="rating" content="General" />
		<meta name="robots" content="all" />
	</head>
	<body>
		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=10,0,0,0" width="400" height="400" id="test" align="middle">
			<param name="allowScriptAccess" value="sameDomain" />
			<param name="allowFullScreen" value="false" />
			<param name="movie" value="test.swf" />
			<param name="quality" value="high" />
			<param name="bgcolor" value="#ffffff" />
			<param name="FlashVars" value="amfTestUrl=amfTestUrl=<?php print $URL; ?>" />
			<embed src="test.swf" FlashVars="amfTestUrl=<?php print $URL; ?>" quality="high" bgcolor="#ffffff" width="400" height="400" name="test" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
		</object>
	</body>
</html>
