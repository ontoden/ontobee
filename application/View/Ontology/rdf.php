<?php

use View\Helper;
/**
 * Copyright © 2015 The Regents of the University of Michigan
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 * http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and limitations under the License.
 * 
 * For more information, questions, or permission requests, please contact:
 * Yongqun “Oliver” He - yongqunh@med.umich.edu
 * Unit for Laboratory Animal Medicine, Center for Computational Medicine & Bioinformatics
 * University of Michigan, Ann Arbor, MI 48109, USA
 * He Group:  http://www.hegroup.org
 */

/**
 * @file rdf.php
 * @author Yongqun Oliver He
 * @author Zuoshuang Allen Xiang
 * @author Edison Ong
 * @since Sep 9, 2015
 * @comment 
 */
 
if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

if ( strpos($_SERVER['HTTP_ACCEPT'], 'application/rdf+xml') !== false ) {	
	header("Content-type: application/rdf+xml");
} elseif ( strpos( $_SERVER['HTTP_ACCEPT'], 'application/xml' ) !== false ) {
	header( "Content-type: application/xml" );
} else {
	header( "Content-type: text/xml" );
}

$site = SITEURL;

$rdf = preg_replace( '/\s?encoding\s?=\s?\"utf-8\"\s?/', '', $rdf );

if ( $xslt ) {
	$stylesheet = "<?xml-stylesheet type=\"text/xsl\" href=\"{$site}ontology/view/$ontology->ontology_abbrv?iri={$GLOBALS['call_function']( Helper::encodeURL( $termIRI ) )}\"?>";

	$rdf = preg_replace( '/(\<\?xml[\s]?version[\s]?=[\s]?"[\d]+.[\d]"[^?]*\?>)/', '$1' . PHP_EOL . $stylesheet, $rdf );
}

echo $rdf;
?>