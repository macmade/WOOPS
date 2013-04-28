<?php
    
    // Includes the initialization script
    require_once(
        dirname( __FILE__ )
      . DIRECTORY_SEPARATOR
      . '..'
      . DIRECTORY_SEPARATOR
      . '..'
      . DIRECTORY_SEPARATOR
      . 'init.inc.php'
    );
    
    // Creates a new GZIP parser
    $GZIP_PARSER = new Woops_Gzip_Parser(
    	Woops_Core_Env_Getter::getInstance()->getSourcePath( 'tests/gzip/test.gz' )
    );
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
		# Copyright (C) 2009 Jean-David Gadina - www.xs-labs.com                       #
		# All rights reserved                                                          #
		################################################################################
		
		-->
		
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<title>WOOPS - Web Object Oriented Programming System</title>
		<link rel="schema.dc" href="http://purl.org/metadata/dublin_core_elements" />
		<link rel="stylesheet" rev="stylesheet" href="css/base.css" type="text/css" media="screen" charset="utf-8" />
		<meta http-equiv="content-language" content="en" />
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
		<pre>
<?php print htmlspecialchars( print_r( $GZIP_PARSER, true ) ); ?>
		</pre>
	</body>
</html>
