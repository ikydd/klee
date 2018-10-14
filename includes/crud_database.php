<?php

abstract class crud_database extends database
{
	abstract public function create($location, $values = array());
	abstract public function retrieve($location, $conditions = array());
	abstract public function update($location, $conditions = array(), $values = array());
	abstract public function delete($location, $conditions = array());
}