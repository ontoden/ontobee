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
 * @file index.php
 * @author Edison Ong
 * @since Jul 9, 2018
 * @comment 
 */
 
if ( !$this ) {
	exit( header( 'HTTP/1.0 403 Forbidden' ) );
}

$site = SITEURL;

?>

<?php require TEMPLATE . 'header.default.dwt.php'; ?>

<h3 class="head3_darkred">Ontobee Annotator</h3>

<div>
	<form enctype="multipart/form-data" name="annotate" id="annotate" action="<?php echo SITEURL; ?>annotate/submit" method="get">
		
	<textarea name="querytext" cols="70" rows="10" id="querytext" style="margin-left:32px"></textarea><br/><br/>

	<select name="ontology[]" id="ontology" size=5 multiple="multiple">
<?php
	
	foreach ( $ontologies as $ontology ) {
		if ( $ontology->mgrep_ready == 'y' ) {
			echo
<<<END
		<option value="$ontology->ontology_abbrv">$ontology->ontology_fullname ($ontology->ontology_abbrv)</option>
END;
		}
	}
?>
	</select>

		<p style="text-align:center;">
			<input type="submit" name="submit" id="submit" value="Search" text-align="center" onClick="submitForm();"/>
			
			<button type="button" name="reset" value="Reset" style="margin-left:40px;" text-align="center" onClick="document.getElementById('batchkeywords').value=''">Reset</button>
		</p>
		
	</form>
</div>

<?php require TEMPLATE . 'footer.default.dwt.php'; ?>