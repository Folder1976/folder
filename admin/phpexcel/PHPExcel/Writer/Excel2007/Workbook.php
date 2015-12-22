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
 * _Writer_Excel2007_Workbook
 *
 * @category   
 * @package    _Writer_Excel2007
 * @copyright  Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 */
class _Writer_Excel2007_Workbook extends _Writer_Excel2007_WriterPart
{
	/**
	 * Write workbook to XML format
	 *
	 * @param 		$p
	 * @param	boolean		$recalcRequired	Indicate whether formulas should be recalculated before writing
	 * @return 	string 		XML Output
	 * @throws 	_Writer_Exception
	 */
	public function writeWorkbook( $p = null, $recalcRequired = FALSE)
	{
		// Create XML writer
		$objWriter = null;
		if ($this->getParentWriter()->getUseDiskCaching()) {
			$objWriter = new _Shared_XMLWriter(_Shared_XMLWriter::STORAGE_DISK, $this->getParentWriter()->getDiskCachingDirectory());
		} else {
			$objWriter = new _Shared_XMLWriter(_Shared_XMLWriter::STORAGE_MEMORY);
		}

		// XML header
		$objWriter->startDocument('1.0','UTF-8','yes');

		// workbook
		$objWriter->startElement('workbook');
		$objWriter->writeAttribute('xml:space', 'preserve');
		$objWriter->writeAttribute('xmlns', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
		$objWriter->writeAttribute('xmlns:r', 'http://schemas.openxmlformats.org/officeDocument/2006/relationships');

			// fileVersion
			$this->_writeFileVersion($objWriter);

			// workbookPr
			$this->_writeWorkbookPr($objWriter);

			// workbookProtection
			$this->_writeWorkbookProtection($objWriter, $p);

			// bookViews
			if ($this->getParentWriter()->getOffice2003Compatibility() === false) {
				$this->_writeBookViews($objWriter, $p);
			}

			// sheets
			$this->_writeSheets($objWriter, $p);

			// definedNames
			$this->_writeDefinedNames($objWriter, $p);

			// calcPr
			$this->_writeCalcPr($objWriter,$recalcRequired);

		$objWriter->endElement();

		// Return
		return $objWriter->getData();
	}

	/**
	 * Write file version
	 *
	 * @param 	_Shared_XMLWriter $objWriter 		XML Writer
	 * @throws 	_Writer_Exception
	 */
	private function _writeFileVersion(_Shared_XMLWriter $objWriter = null)
	{
		$objWriter->startElement('fileVersion');
		$objWriter->writeAttribute('appName', 'xl');
		$objWriter->writeAttribute('lastEdited', '4');
		$objWriter->writeAttribute('lowestEdited', '4');
		$objWriter->writeAttribute('rupBuild', '4505');
		$objWriter->endElement();
	}

	/**
	 * Write WorkbookPr
	 *
	 * @param 	_Shared_XMLWriter $objWriter 		XML Writer
	 * @throws 	_Writer_Exception
	 */
	private function _writeWorkbookPr(_Shared_XMLWriter $objWriter = null)
	{
		$objWriter->startElement('workbookPr');

		if (_Shared_Date::getExcelCalendar() == _Shared_Date::CALENDAR_MAC_1904) {
			$objWriter->writeAttribute('date1904', '1');
		}

		$objWriter->writeAttribute('codeName', 'ThisWorkbook');

		$objWriter->endElement();
	}

	/**
	 * Write BookViews
	 *
	 * @param 	_Shared_XMLWriter 	$objWriter 		XML Writer
	 * @param 						$p
	 * @throws 	_Writer_Exception
	 */
	private function _writeBookViews(_Shared_XMLWriter $objWriter = null,  $p = null)
	{
		// bookViews
		$objWriter->startElement('bookViews');

			// workbookView
			$objWriter->startElement('workbookView');

			$objWriter->writeAttribute('activeTab', $p->getActiveSheetIndex());
			$objWriter->writeAttribute('autoFilterDateGrouping', '1');
			$objWriter->writeAttribute('firstSheet', '0');
			$objWriter->writeAttribute('minimized', '0');
			$objWriter->writeAttribute('showHorizontalScroll', '1');
			$objWriter->writeAttribute('showSheetTabs', '1');
			$objWriter->writeAttribute('showVerticalScroll', '1');
			$objWriter->writeAttribute('tabRatio', '600');
			$objWriter->writeAttribute('visibility', 'visible');

			$objWriter->endElement();

		$objWriter->endElement();
	}

	/**
	 * Write WorkbookProtection
	 *
	 * @param 	_Shared_XMLWriter 	$objWriter 		XML Writer
	 * @param 						$p
	 * @throws 	_Writer_Exception
	 */
	private function _writeWorkbookProtection(_Shared_XMLWriter $objWriter = null,  $p = null)
	{
		if ($p->getSecurity()->isSecurityEnabled()) {
			$objWriter->startElement('workbookProtection');
			$objWriter->writeAttribute('lockRevision',		($p->getSecurity()->getLockRevision() ? 'true' : 'false'));
			$objWriter->writeAttribute('lockStructure', 	($p->getSecurity()->getLockStructure() ? 'true' : 'false'));
			$objWriter->writeAttribute('lockWindows', 		($p->getSecurity()->getLockWindows() ? 'true' : 'false'));

			if ($p->getSecurity()->getRevisionsPassword() != '') {
				$objWriter->writeAttribute('revisionsPassword',	$p->getSecurity()->getRevisionsPassword());
			}

			if ($p->getSecurity()->getWorkbookPassword() != '') {
				$objWriter->writeAttribute('workbookPassword',	$p->getSecurity()->getWorkbookPassword());
			}

			$objWriter->endElement();
		}
	}

	/**
	 * Write calcPr
	 *
	 * @param 	_Shared_XMLWriter	$objWriter		XML Writer
	 * @param	boolean						$recalcRequired	Indicate whether formulas should be recalculated before writing
	 * @throws 	_Writer_Exception
	 */
	private function _writeCalcPr(_Shared_XMLWriter $objWriter = null, $recalcRequired = TRUE)
	{
		$objWriter->startElement('calcPr');

		$objWriter->writeAttribute('calcId', 			'124519');
		$objWriter->writeAttribute('calcMode', 			'auto');
		//	fullCalcOnLoad isn't needed if we've recalculating for the save
		$objWriter->writeAttribute('fullCalcOnLoad', 	($recalcRequired) ? '0' : '1');

		$objWriter->endElement();
	}

	/**
	 * Write sheets
	 *
	 * @param 	_Shared_XMLWriter 	$objWriter 		XML Writer
	 * @param 						$p
	 * @throws 	_Writer_Exception
	 */
	private function _writeSheets(_Shared_XMLWriter $objWriter = null,  $p = null)
	{
		// Write sheets
		$objWriter->startElement('sheets');
		$sheetCount = $p->getSheetCount();
		for ($i = 0; $i < $sheetCount; ++$i) {
			// sheet
			$this->_writeSheet(
				$objWriter,
				$p->getSheet($i)->getTitle(),
				($i + 1),
				($i + 1 + 3),
				$p->getSheet($i)->getSheetState()
			);
		}

		$objWriter->endElement();
	}

	/**
	 * Write sheet
	 *
	 * @param 	_Shared_XMLWriter 	$objWriter 		XML Writer
	 * @param 	string 						$pSheetname 		Sheet name
	 * @param 	int							$pSheetId	 		Sheet id
	 * @param 	int							$pRelId				Relationship ID
	 * @param   string                      $sheetState         Sheet state (visible, hidden, veryHidden)
	 * @throws 	_Writer_Exception
	 */
	private function _writeSheet(_Shared_XMLWriter $objWriter = null, $pSheetname = '', $pSheetId = 1, $pRelId = 1, $sheetState = 'visible')
	{
		if ($pSheetname != '') {
			// Write sheet
			$objWriter->startElement('sheet');
			$objWriter->writeAttribute('name', 		$pSheetname);
			$objWriter->writeAttribute('sheetId', 	$pSheetId);
			if ($sheetState != 'visible' && $sheetState != '') {
				$objWriter->writeAttribute('state', $sheetState);
			}
			$objWriter->writeAttribute('r:id', 		'rId' . $pRelId);
			$objWriter->endElement();
		} else {
			throw new _Writer_Exception("Invalid parameters passed.");
		}
	}

	/**
	 * Write Defined Names
	 *
	 * @param 	_Shared_XMLWriter	$objWriter 		XML Writer
	 * @param 						$p
	 * @throws 	_Writer_Exception
	 */
	private function _writeDefinedNames(_Shared_XMLWriter $objWriter = null,  $p = null)
	{
		// Write defined names
		$objWriter->startElement('definedNames');

		// Named ranges
		if (count($p->getNamedRanges()) > 0) {
			// Named ranges
			$this->_writeNamedRanges($objWriter, $p);
		}

		// Other defined names
		$sheetCount = $p->getSheetCount();
		for ($i = 0; $i < $sheetCount; ++$i) {
			// definedName for autoFilter
			$this->_writeDefinedNameForAutofilter($objWriter, $p->getSheet($i), $i);

			// definedName for Print_Titles
			$this->_writeDefinedNameForPrintTitles($objWriter, $p->getSheet($i), $i);

			// definedName for Print_Area
			$this->_writeDefinedNameForPrintArea($objWriter, $p->getSheet($i), $i);
		}

		$objWriter->endElement();
	}

	/**
	 * Write named ranges
	 *
	 * @param 	_Shared_XMLWriter	$objWriter 		XML Writer
	 * @param 						$p
	 * @throws 	_Writer_Exception
	 */
	private function _writeNamedRanges(_Shared_XMLWriter $objWriter = null,  $p)
	{
		// Loop named ranges
		$namedRanges = $p->getNamedRanges();
		foreach ($namedRanges as $namedRange) {
			$this->_writeDefinedNameForNamedRange($objWriter, $namedRange);
		}
	}

	/**
	 * Write Defined Name for named range
	 *
	 * @param 	_Shared_XMLWriter	$objWriter 		XML Writer
	 * @param 	_NamedRange			$pNamedRange
	 * @throws 	_Writer_Exception
	 */
	private function _writeDefinedNameForNamedRange(_Shared_XMLWriter $objWriter = null, _NamedRange $pNamedRange)
	{
		// definedName for named range
		$objWriter->startElement('definedName');
		$objWriter->writeAttribute('name',			$pNamedRange->getName());
		if ($pNamedRange->getLocalOnly()) {
			$objWriter->writeAttribute('localSheetId',	$pNamedRange->getScope()->getParent()->getIndex($pNamedRange->getScope()));
		}

		// Create absolute coordinate and write as raw text
		$range = _Cell::splitRange($pNamedRange->getRange());
		for ($i = 0; $i < count($range); $i++) {
			$range[$i][0] = '\'' . str_replace("'", "''", $pNamedRange->getWorksheet()->getTitle()) . '\'!' . _Cell::absoluteReference($range[$i][0]);
			if (isset($range[$i][1])) {
				$range[$i][1] = _Cell::absoluteReference($range[$i][1]);
			}
		}
		$range = _Cell::buildRange($range);

		$objWriter->writeRawData($range);

		$objWriter->endElement();
	}

	/**
	 * Write Defined Name for autoFilter
	 *
	 * @param 	_Shared_XMLWriter	$objWriter 		XML Writer
	 * @param 	_Worksheet			$pSheet
	 * @param 	int							$pSheetId
	 * @throws 	_Writer_Exception
	 */
	private function _writeDefinedNameForAutofilter(_Shared_XMLWriter $objWriter = null, _Worksheet $pSheet = null, $pSheetId = 0)
	{
		// definedName for autoFilter
		$autoFilterRange = $pSheet->getAutoFilter()->getRange();
		if (!empty($autoFilterRange)) {
			$objWriter->startElement('definedName');
			$objWriter->writeAttribute('name',			'_xlnm._FilterDatabase');
			$objWriter->writeAttribute('localSheetId',	$pSheetId);
			$objWriter->writeAttribute('hidden',		'1');

			// Create absolute coordinate and write as raw text
			$range = _Cell::splitRange($autoFilterRange);
			$range = $range[0];
			//	Strip any worksheet ref so we can make the cell ref absolute
			if (strpos($range[0],'!') !== false) {
				list($ws,$range[0]) = explode('!',$range[0]);
			}

			$range[0] = _Cell::absoluteCoordinate($range[0]);
			$range[1] = _Cell::absoluteCoordinate($range[1]);
			$range = implode(':', $range);

			$objWriter->writeRawData('\'' . str_replace("'", "''", $pSheet->getTitle()) . '\'!' . $range);

			$objWriter->endElement();
		}
	}

	/**
	 * Write Defined Name for PrintTitles
	 *
	 * @param 	_Shared_XMLWriter	$objWriter 		XML Writer
	 * @param 	_Worksheet			$pSheet
	 * @param 	int							$pSheetId
	 * @throws 	_Writer_Exception
	 */
	private function _writeDefinedNameForPrintTitles(_Shared_XMLWriter $objWriter = null, _Worksheet $pSheet = null, $pSheetId = 0)
	{
		// definedName for PrintTitles
		if ($pSheet->getPageSetup()->isColumnsToRepeatAtLeftSet() || $pSheet->getPageSetup()->isRowsToRepeatAtTopSet()) {
			$objWriter->startElement('definedName');
			$objWriter->writeAttribute('name',			'_xlnm.Print_Titles');
			$objWriter->writeAttribute('localSheetId',	$pSheetId);

			// Setting string
			$settingString = '';

			// Columns to repeat
			if ($pSheet->getPageSetup()->isColumnsToRepeatAtLeftSet()) {
				$repeat = $pSheet->getPageSetup()->getColumnsToRepeatAtLeft();

				$settingString .= '\'' . str_replace("'", "''", $pSheet->getTitle()) . '\'!$' . $repeat[0] . ':$' . $repeat[1];
			}

			// Rows to repeat
			if ($pSheet->getPageSetup()->isRowsToRepeatAtTopSet()) {
				if ($pSheet->getPageSetup()->isColumnsToRepeatAtLeftSet()) {
					$settingString .= ',';
				}

				$repeat = $pSheet->getPageSetup()->getRowsToRepeatAtTop();

				$settingString .= '\'' . str_replace("'", "''", $pSheet->getTitle()) . '\'!$' . $repeat[0] . ':$' . $repeat[1];
			}

			$objWriter->writeRawData($settingString);

			$objWriter->endElement();
		}
	}

	/**
	 * Write Defined Name for PrintTitles
	 *
	 * @param 	_Shared_XMLWriter	$objWriter 		XML Writer
	 * @param 	_Worksheet			$pSheet
	 * @param 	int							$pSheetId
	 * @throws 	_Writer_Exception
	 */
	private function _writeDefinedNameForPrintArea(_Shared_XMLWriter $objWriter = null, _Worksheet $pSheet = null, $pSheetId = 0)
	{
		// definedName for PrintArea
		if ($pSheet->getPageSetup()->isPrintAreaSet()) {
			$objWriter->startElement('definedName');
			$objWriter->writeAttribute('name',			'_xlnm.Print_Area');
			$objWriter->writeAttribute('localSheetId',	$pSheetId);

			// Setting string
			$settingString = '';

			// Print area
			$printArea = _Cell::splitRange($pSheet->getPageSetup()->getPrintArea());

			$chunks = array();
			foreach ($printArea as $printAreaRect) {
				$printAreaRect[0] = _Cell::absoluteReference($printAreaRect[0]);
				$printAreaRect[1] = _Cell::absoluteReference($printAreaRect[1]);
				$chunks[] = '\'' . str_replace("'", "''", $pSheet->getTitle()) . '\'!' . implode(':', $printAreaRect);
			}

			$objWriter->writeRawData(implode(',', $chunks));

			$objWriter->endElement();
		}
	}
}
