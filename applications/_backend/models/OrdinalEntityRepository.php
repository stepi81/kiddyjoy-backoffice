<?php

/**
 * An abstract repository that automates inserts of an ordinal property 
 * that is to say, if your entity needs to have a unique, non-key property that is always ordered from 1 to N (nulls allowed),  
 * extend the repo using this.  
 * @author Goran Lalić 2013 ABC Design
 */

namespace models;

use Doctrine\ORM\EntityRepository;

if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 
class OrdinalEntityRepository extends EntityRepository {
 	
	private function comparer($a, $b) {
		$vala = $this->getValue($a); $valb = $this->getValue($b); //  $a[$propertyName]; $valb = $b[$propertyName];
		if ($vala == $valb) return 0;
		return $vala < $valb ? -1 : 1;
	}
	
	/**
	 * Ensures all the entries are sorted according to an ordinal property 
	 */
	public function ensureOrdinality($allEntries, $getterName, $setterName) {
		// in here we use a little hack - we SET the ordinal property of the first element of the array to the value it already has. 
		// PHP treats this as a value update and forces resort.
		if (count($allEntries) == 0) return;
		$this->getterName = $getterName; $this->setterName = $setterName;
		$this->enforceOrdinalProperty($allEntries, $allEntries[0], $this->getValue($allEntries[0]), $getterName, $setterName );		
	}
	
	public function enforceOrdinalProperty($allEntities, $editedEntity, $newValueInItem, $getterName, $setterName, $allowNull = false ) {
	
		if ($newValueInItem == FALSE) 	$newValueInItem = NULL;
		if ($newValueInItem == 0) 		$newValueInItem = NULL;
		if ($newValueInItem == '0') 	$newValueInItem = NULL;
		
		if ($newValueInItem == NULL && !$allowNull) {
			echo ' :  Trying to set value to null, but not allowed to! <br>' ;
			return;
		}
	    
		$posessors = array();	
			
		$this->getterName = $getterName;
		$this->setterName = $setterName;
		
		foreach ($allEntities as $item) { // array of all the items with the property but WITHOUT the item itself
			if ($item != $editedEntity && $this->getValue($item) != NULL) {
				$posessors[] = $item;
			}
		}
		
		usort($posessors, array($this, 'comparer'));
		
		$this->setValue($editedEntity, $newValueInItem);
		if ($newValueInItem != NULL) $this->insertItem($posessors, $editedEntity, $newValueInItem - 1);			 
		
		
		$changedItems = array(); // we do not persist all the entities, only the ones that have been changed.
		
		$counter = 0; 
		foreach ($posessors as $item) {
			$counter++;			
			$this->setValue($item, $counter);								
			$changedItems[] = $item;
		}
		
		$changedItems[] = $editedEntity; // regardless, add edited entity to changed items
		
		foreach ($changedItems as $item) $this->_em->persist($item);		
		$this->_em->flush();
				
	}
	
	private $getterName;
	private $setterName;	
	
	private function getValue($entity) {		
		return call_user_func(array($entity, $this->getterName));
	}
	
	private function setValue($entity, $value) {
		call_user_func(array($entity, $this->setterName), $value);		
	}
	
	private function insertItem(&$array, $item, $atIndex) {
		
		if ($atIndex <= 0) 			   { $array = array_merge(array($item), $array); return; }
		if ($atIndex >= count($array)) { $array[] = $item; return; }
		
		$a1 = array_slice($array, 0, $atIndex);
		$a2 = array($item);
		$a3 = array_slice($array, $atIndex, count($array) - 1);
		
		$array = array_merge($a1 , $a2, $a3);
	}
	
	private function outputValues($arr) {
		echo '[ ';
		 foreach ($arr as $item) echo ' , ' . $this->getValue($item);
		echo ' ] ';
		echo '<br>';
	}
	
}