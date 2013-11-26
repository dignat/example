<?php
/**
 * Create a "least recently used" cache class in PHP. The class should have the following specification:
 *
 * 1. Two public methods get($key) and set($key, $value) (obviously as many private/protected methods are required).
 * 2. Allow up to 10 items to be in the cache at all times. Evicting the least recently used items.
 * 3. Store cached items persistently across requests, using either MySQL table, APC, Memecache or file system.
 *
 * Please bear in mind it's a cache so it should be fast and thus use a fast cross-request cache/storage engine, optimised for a large volume of hits.
 *
 * You may use google to search for methods, but where possible please avoid direct copy and paste, and please give credit & a link where credit is due.
 *
 * Please include any test files, schemas or plugins needed and instructions on how to run the test, e.g. cli commands
 *
 */

class LRUCache {

	private $head;
	private $tail;
	private $hashmap;
	private $capacity;


	public function __construct($capacity)
	{
		$this->capacity = $capacity;
		$this->hashmap = array();
		$this->head = new Node(null, null);
		$this->tail = new Node(null, null);


		$this->head->setNext($this->tail);
		$this->tail->setPrevious($this->head);
	}

	public function get($key)
	{
		if(!isset($this->hashmap[$key])){
			return null;
		}
		$node = $this->hashmap[$key];
		if(count($this->hashmap) == 1) {
			return $node->getData();
		}
		$this->detach($node);
		$this->attach($this->head, $node);
		return $node->getData();
	}

	public function set($key, $data)
	{
		if($this->capacity <=0)
			{return false;
			}

			if(isset($this->hashmap[$key]) && !empty($this->hashmap[$key])){
				$node = $this->hashmap[$key];
				$this->detach($node);
				$this->attach($this->head, $node);
				$node->setData($data);
			}
			else{
				$node = new Node($key, $data);
				$this->hashmap[$key] = $node;
				$this->attach($this->head, $node);
			

			if(count($this->hashmap) > $this->capacity){
				$nodeToRemove = $this->tail->getPrevious();
				$this->detach($nodeToRemove);
				unset($this->hashmap[$nodeToRemove->getKey()]);
			}
		}
		return true;
	}

	 public function remove($key) {
       if (!isset($this->hashmap[$key])) { return false; }
       $nodeToRemove = $this->hashmap[$key];
       $this->detach($nodeToRemove);
       unset($this->hashmap[$nodeToRemove->getKey()]);
       return true;
     }

	private function attach($head, $node) {
        $node->setPrevious($head);
        $node->setNext($head->getNext());
        $node->getNext()->setPrevious($node);
        $node->getPrevious()->setNext($node);
    }

   
    private function detach($node) {
        $node->getPrevious()->setNext($node->getNext());
        $node->getNext()->setPrevious($node->getPrevious());
    }


}


class Node {

	private $key;
	private $data;
	private $previous;
	private $next;


	public function __construct($key, $data)
	{
		$this->key = $key;
		$this->data = $data;
	}

	public function setData($data)
	{
		$this->data = $data;
	}

	public function setNext($next)
	{
		$this->next = $next;
	}

	public function setPrevious($previous)
	{
		$this->previous = $previous;
	}

	public function getKey()
	{
		return $this->key;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getNext()
	{
		return $this->next;
	}

	public function getPrevious()
	{
		return $this->previous;
	}
}