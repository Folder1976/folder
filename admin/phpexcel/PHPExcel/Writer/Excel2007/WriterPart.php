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
 * @package    _Writer_Excel2007
 * @copyright  Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.7.9, 2013-06-02
 */


/**
 * _Writer_Excel2007_WriterPart
 *
 * @category   
 * @package    _Writer_Excel2007
 * @copyright  Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 */
abstract class _Writer_Excel2007_WriterPart
{
	/**
	 * Parent IWriter object
	 *
	 * @var _Writer_IWriter
	 */
	private $_parentWriter;

	/**
	 * Set parent IWriter object
	 *
	 * @param _Writer_IWriter	$pWriter
	 * @throws _Writer_Exception
	 */
	public function setParentWriter(_Writer_IWriter $pWriter = null) {
		$this->_parentWriter = $pWriter;
	}

	/**
	 * Get parent IWriter object
	 *
	 * @return _Writer_IWriter
	 * @throws _Writer_Exception
	 */
	public function getParentWriter() {
		if (!is_null($this->_parentWriter)) {
			return $this->_parentWriter;
		} else {
			throw new _Writer_Exception("No parent _Writer_IWriter assigned.");
		}
	}

	/**
	 * Set parent IWriter object
	 *
	 * @param _Writer_IWriter	$pWriter
	 * @throws _Writer_Exception
	 */
	public function __construct(_Writer_IWriter $pWriter = null) {
		if (!is_null($pWriter)) {
			$this->_parentWriter = $pWriter;
		}
	}

}
