<?php

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
 * @file IndexController.php
 * @author Edison Ong
 * @since Sep 3, 2015
 * @comment 
 */

namespace Controller;

use Controller\Controller;

use Model\OntologyModel;
 
Class IndexController extends Controller {
	
	public function introduction() {
		require VIEWPATH . 'Index/introduction.php';
	}
	
	public function faqs() {
		require VIEWPATH . 'Index/faqs.php';
	}
	
	public function references() {
		require VIEWPATH . 'Index/references.php';
	}
	
	public function links() {
		require VIEWPATH . 'Index/links.php';
	}
	
	public function contactus() {
		require VIEWPATH . 'Index/contactus.php';
	}
	
	public function acknowledge() {
		require VIEWPATH . 'Index/acknowledge.php';
	}
	
	public function news() {
		require VIEWPATH . 'Index/news.php';
	}
	
	public function history() {
		require VIEWPATH . 'Index/history.php';
	}
	
	public function download() {
		require VIEWPATH . 'Index/download.php';
	}
	
	public function index( $params = array() ) {
		$this->loadModel( 'Ontology' );
		$ontologies = $this->model->getAllOntology();
		require VIEWPATH . 'Index/home.php';
	}
	
	public function sparql( $params = array() ) {
		if ( array_key_exists( 'query', $params ) ) {
			$query = $params['query'];
		} else {
			$query = '';
		}
		if ( array_key_exists( 'format', $params ) ) {
			$format = $params['format'];
		} else {
			$format = '';
		}
		if ( array_key_exists( 'maxrows', $params ) ) {
			$maxrows = $params['maxrows'];
		} else {
			$maxrows = '';
		}
		if ( array_key_exists( 'go', $params ) ) {
			$go = $params['go'];
		} else {
			$go = '';
		}
		require VIEWPATH . 'Index/sparql.php';
	}
	
	public function tutorial( $params = array() ) {
		if ( !empty( $params ) ) {
			if ( $params[0] == 'sparql' ) {
				require VIEWPATH . 'Tutorial/sparql.php';
			}
		} else {
			require VIEWPATH . 'Tutorial/index.php';
		}
	}
	
	public function listTerms( $params = array() ) {
		$this->loadModel( 'Ontology' );
		$ontAbbr = $params[0];
		$termIRI = null;
		
		$dir = SCRIPTPATH . 'ontology' . DIRECTORY_SEPARATOR;
		$xlsFile = "$ontAbbr.xls";
		$xlsxFile = "$ontAbbr.xlsx";
		
		if ( !file_exists( $dir . $xlsFile ) || ( time() - filemtime( $dir . $xlsFile )  > 60*60*8 ) ) {
			$this->model->exportOntology( $ontAbbr );
			$ontology = $this->model->getOntology();
			if ( empty( $ontology ) ) {
				throw new Exception ( "Invalid ontology." );
			}
			
			$terms = array();
			foreach ( $ontology['class'] as $result ) {
				$terms[$result['s']] = $result;
			}
			foreach ( $ontology['type'] as $result ) {
				$terms[$result['s']] = $result;
			}
			
			print_r($terms);
			

			/** PHPExcel */
			require_once PHPLIB . 'PHPExcel.php';
				
			/** PHPExcel_Cell_AdvancedValueBinder */
			require_once PHPLIB . 'PHPExcel/Cell/AdvancedValueBinder.php';
				
			/** PHPExcel_IOFactory */
			require_once PHPLIB . 'PHPExcel/IOFactory.php';
				
			// Set value binder
			\PHPExcel_Cell::setValueBinder( new \PHPExcel_Cell_AdvancedValueBinder() );
				
			// Create new PHPExcel object
			$objPHPExcel = new \PHPExcel();
				
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(45);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(100);
				
				
			$styleArray = array('font' => array('bold' => true));
			
			$objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('C1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('D1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('E1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('F1')->applyFromArray($styleArray);
			
				
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, 1)->setValue("Term IRI");
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, 1)->setValue("Term label");
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, 1)->setValue("Parent term IRI");
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, 1)->setValue("Parent term label");
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, 1)->setValue("Alternative term");
			$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, 1)->setValue("Definition");
			
			$i=2;
			foreach($terms as $term_url => $term) {
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(0, $i)->setValue($term_url);
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(1, $i)->setValue($term['l']);
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(2, $i)->setValue($term['pTerm']);
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(3, $i)->setValue($term['pLabel']);
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(4, $i)->setValue($term['alt_names']);
				$objPHPExcel->getActiveSheet()->getCellByColumnAndRow(5, $i)->setValue($term['definition']);
				$i++;
			}
				
			$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel5");
			$objWriter->save( $dir . $xlsFile );
			$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, "Excel2007");
			$objWriter->save( $dir . $xlsxFile );
		}
		
		header( 'Content-Type: application/vnd.ms-excel' );
		header( 'Content-Description: File Transfer' );
		header( "Content-Disposition: attachment; filename=\"$xlsFile\"" );
		header( 'Content-Length: ' . filesize( $dir . $xlsFile ) ); // length
		
		readfile( $dir . $xlsFile );
	}
}



?>