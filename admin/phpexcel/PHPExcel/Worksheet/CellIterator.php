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
 * @package    _Worksheet
 * @copyright  Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.9, 2013-06-02
 */


/**
 * _Worksheet_CellIterator
 *
 * Used to iterate rows in a _Worksheet
 *
 * @category   
 * @package    _Worksheet
 * @copyright  Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 */
class _Worksheet_CellIterator implements Iterator
{
	/**
	 * _Worksheet to iterate
	 *
	 * @var _Worksheet
	 */
	private $_subject;

	/**
	 * Row index
	 *
	 * @var int
	 */
	private $_rowIndex;

	/**
	 * Current iterator position
	 *
	 * @var int
	 */
	private $_position = 0;

	/**
	 * Loop only existing cells
	 *
	 * @var boolean
	 */
	private $_onlyExistingCells = true;

	/**
	 * Create a new cell iterator
	 *
	 * @param _Worksheet 		$subject
	 * @param int						$rowIndex
	 */
	public function __construct(_Worksheet $subject = null, $rowIndex = 1) {
		// Set subject and row index
		$this->_subject 	= $subject;
		$this->_rowIndex 	= $rowIndex;
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		unset($this->_subject);
	}

	/**
	 * Rewind iterator
	 */
    public function rewind() {
        $this->_position = 0;
    }

    /**
     * Current _Cell
     *
     * @return _Cell
     */
    public function current() {
		return $this->_subject->getCellByColumnAndRow($this->_position, $this->_rowIndex);
    }

    /**
     * Current key
     *
     * @return int
     */
    public function key() {
        return $this->_position;
    }

    /**
     * Next value
     */
    public function next() {
        ++$this->_position;
    }

    /**
     * Are there any more _Cell instances available?
     *
     * @return boolean
     */
    public function valid() {
        // columnIndexFromString() returns an index based at one,
        // treat it as a count when comparing it to the base zero
        // position.
        $columnCount = _Cell::columnIndexFromString($this->_subject->getHighestColumn());

        if ($this->_onlyExistingCells) {
            // If we aren't looking at an existing cell, either
            // because the first column doesn't exist or next() has
            // been called onto a nonexistent cell, then loop until we
            // find one, or pass the last column.
            while ($this->_position < $columnCount &&
                   !$this->_subject->cellExistsByColumnAndRow($this->_position, $this->_rowIndex)) {
                ++$this->_position;
            }
        }

        return $this->_position < $columnCount;
    }

	/**
	 * Get loop only existing cells
	 *
	 * @return boolean
	 */
    public function getIterateOnlyExistingCells() {
    	return $this->_onlyExistingCells;
    }

	/**
	 * Set the iterator to loop only existing cells
	 *
	 * @param	boolean		$value
	 */
    public function setIterateOnlyExistingCells($value = true) {
    	$this->_onlyExistingCells = $value;
    }
}
