<?php
/**
 * 
 *
 * Copyright (c) 2006 - 2013 
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   
 * @package	
 * @copyright  Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 * @license	http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version	1.7.9, 2013-06-02
 */


/**
 * _HashTable
 *
 * @category   
 * @package	
 * @copyright  Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 */
class _HashTable
{
	/**
	 * HashTable elements
	 *
	 * @var array
	 */
	public $_items = array();

	/**
	 * HashTable key map
	 *
	 * @var array
	 */
	public $_keyMap = array();

	/**
	 * Create a new _HashTable
	 *
	 * @param	_IComparable[] $pSource	Optional source array to create HashTable from
	 * @throws	_Exception
	 */
	public function __construct($pSource = null)
	{
		if ($pSource !== NULL) {
			// Create HashTable
			$this->addFromSource($pSource);
		}
	}

	/**
	 * Add HashTable items from source
	 *
	 * @param	_IComparable[] $pSource	Source array to create HashTable from
	 * @throws	_Exception
	 */
	public function addFromSource($pSource = null) {
		// Check if an array was passed
		if ($pSource == null) {
			return;
		} else if (!is_array($pSource)) {
			throw new _Exception('Invalid array parameter passed.');
		}

		foreach ($pSource as $item) {
			$this->add($item);
		}
	}

	/**
	 * Add HashTable item
	 *
	 * @param	_IComparable $pSource	Item to add
	 * @throws	_Exception
	 */
	public function add(_IComparable $pSource = null) {
		$hash = $pSource->getHashCode();
		if (!isset($this->_items[$hash])) {
			$this->_items[$hash] = $pSource;
			$this->_keyMap[count($this->_items) - 1] = $hash;
		}
	}

	/**
	 * Remove HashTable item
	 *
	 * @param	_IComparable $pSource	Item to remove
	 * @throws	_Exception
	 */
	public function remove(_IComparable $pSource = null) {
		$hash = $pSource->getHashCode();
		if (isset($this->_items[$hash])) {
			unset($this->_items[$hash]);

			$deleteKey = -1;
			foreach ($this->_keyMap as $key => $value) {
				if ($deleteKey >= 0) {
					$this->_keyMap[$key - 1] = $value;
				}

				if ($value == $hash) {
					$deleteKey = $key;
				}
			}
			unset($this->_keyMap[count($this->_keyMap) - 1]);
		}
	}

	/**
	 * Clear HashTable
	 *
	 */
	public function clear() {
		$this->_items = array();
		$this->_keyMap = array();
	}

	/**
	 * Count
	 *
	 * @return int
	 */
	public function count() {
		return count($this->_items);
	}

	/**
	 * Get index for hash code
	 *
	 * @param	string	$pHashCode
	 * @return	int	Index
	 */
	public function getIndexForHashCode($pHashCode = '') {
		return array_search($pHashCode, $this->_keyMap);
	}

	/**
	 * Get by index
	 *
	 * @param	int	$pIndex
	 * @return	_IComparable
	 *
	 */
	public function getByIndex($pIndex = 0) {
		if (isset($this->_keyMap[$pIndex])) {
			return $this->getByHashCode( $this->_keyMap[$pIndex] );
		}

		return null;
	}

	/**
	 * Get by hashcode
	 *
	 * @param	string	$pHashCode
	 * @return	_IComparable
	 *
	 */
	public function getByHashCode($pHashCode = '') {
		if (isset($this->_items[$pHashCode])) {
			return $this->_items[$pHashCode];
		}

		return null;
	}

	/**
	 * HashTable to array
	 *
	 * @return _IComparable[]
	 */
	public function toArray() {
		return $this->_items;
	}

	/**
	 * Implement PHP __clone to create a deep clone, not just a shallow copy.
	 */
	public function __clone() {
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value) {
			if (is_object($value)) {
				$this->$key = clone $value;
			}
		}
	}
}
