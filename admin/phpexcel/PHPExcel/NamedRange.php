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
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.9, 2013-06-02
 */


/**
 * _NamedRange
 *
 * @category   
 * @package    
 * @copyright  Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 */
class _NamedRange
{
	/**
	 * Range name
	 *
	 * @var string
	 */
	private $_name;

	/**
	 * Worksheet on which the named range can be resolved
	 *
	 * @var _Worksheet
	 */
	private $_worksheet;

	/**
	 * Range of the referenced cells
	 *
	 * @var string
	 */
	private $_range;

	/**
	 * Is the named range local? (i.e. can only be used on $this->_worksheet)
	 *
	 * @var bool
	 */
	private $_localOnly;

	/**
	 * Scope
	 *
	 * @var _Worksheet
	 */
	private $_scope;

    /**
     * Create a new NamedRange
     *
     * @param string $pName
     * @param _Worksheet $pWorksheet
     * @param string $pRange
     * @param bool $pLocalOnly
     * @param _Worksheet|null $pScope	Scope. Only applies when $pLocalOnly = true. Null for global scope.
     * @throws _Exception
     */
    public function __construct($pName = null, _Worksheet $pWorksheet, $pRange = 'A1', $pLocalOnly = false, $pScope = null)
    {
    	// Validate data
    	if (($pName === NULL) || ($pWorksheet === NULL) || ($pRange === NULL)) {
    		throw new _Exception('Parameters can not be null.');
    	}

    	// Set local members
    	$this->_name 		= $pName;
    	$this->_worksheet 	= $pWorksheet;
    	$this->_range 		= $pRange;
    	$this->_localOnly 	= $pLocalOnly;
    	$this->_scope 		= ($pLocalOnly == true) ?
								(($pScope == null) ? $pWorksheet : $pScope) : null;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
    	return $this->_name;
    }

    /**
     * Set name
     *
     * @param string $value
     * @return _NamedRange
     */
    public function setName($value = null) {
    	if ($value !== NULL) {
    		// Old title
    		$oldTitle = $this->_name;

    		// Re-attach
    		if ($this->_worksheet !== NULL) {
    			$this->_worksheet->getParent()->removeNamedRange($this->_name,$this->_worksheet);
    		}
    		$this->_name = $value;

    		if ($this->_worksheet !== NULL) {
    			$this->_worksheet->getParent()->addNamedRange($this);
    		}

	    	// New title
	    	$newTitle = $this->_name;
	    	_ReferenceHelper::getInstance()->updateNamedFormulas($this->_worksheet->getParent(), $oldTitle, $newTitle);
    	}
    	return $this;
    }

    /**
     * Get worksheet
     *
     * @return _Worksheet
     */
    public function getWorksheet() {
    	return $this->_worksheet;
    }

    /**
     * Set worksheet
     *
     * @param _Worksheet $value
     * @return _NamedRange
     */
    public function setWorksheet(_Worksheet $value = null) {
    	if ($value !== NULL) {
    		$this->_worksheet = $value;
    	}
    	return $this;
    }

    /**
     * Get range
     *
     * @return string
     */
    public function getRange() {
    	return $this->_range;
    }

    /**
     * Set range
     *
     * @param string $value
     * @return _NamedRange
     */
    public function setRange($value = null) {
    	if ($value !== NULL) {
    		$this->_range = $value;
    	}
    	return $this;
    }

    /**
     * Get localOnly
     *
     * @return bool
     */
    public function getLocalOnly() {
    	return $this->_localOnly;
    }

    /**
     * Set localOnly
     *
     * @param bool $value
     * @return _NamedRange
     */
    public function setLocalOnly($value = false) {
    	$this->_localOnly = $value;
    	$this->_scope = $value ? $this->_worksheet : null;
    	return $this;
    }

    /**
     * Get scope
     *
     * @return _Worksheet|null
     */
    public function getScope() {
    	return $this->_scope;
    }

    /**
     * Set scope
     *
     * @param _Worksheet|null $value
     * @return _NamedRange
     */
    public function setScope(_Worksheet $value = null) {
    	$this->_scope = $value;
    	$this->_localOnly = ($value == null) ? false : true;
    	return $this;
    }

    /**
     * Resolve a named range to a regular cell range
     *
     * @param string $pNamedRange Named range
     * @param _Worksheet|null $pSheet Scope. Use null for global scope
     * @return _NamedRange
     */
    public static function resolveRange($pNamedRange = '', _Worksheet $pSheet) {
		return $pSheet->getParent()->getNamedRange($pNamedRange, $pSheet);
    }

	/**
	 * Implement PHP __clone to create a deep clone, not just a shallow copy.
	 */
	public function __clone() {
		$vars = get_object_vars($this);
		foreach ($vars as $key => $value) {
			if (is_object($value)) {
				$this->$key = clone $value;
			} else {
				$this->$key = $value;
			}
		}
	}
}
