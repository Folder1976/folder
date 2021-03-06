<?php
/**
 *  
 *
 *  Copyright (c) 2006 - 2013 
 *
 *  This library is free software; you can redistribute it and/or
 *  modify it under the terms of the GNU Lesser General Public
 *  License as published by the Free Software Foundation; either
 *  version 2.1 of the License, or (at your option) any later version.
 *
 *  This library is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 *  Lesser General Public License for more details.
 *
 *  You should have received a copy of the GNU Lesser General Public
 *  License along with this library; if not, write to the Free Software
 *  Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 *  @category    
 *  @package     _Writer_PDF
 *  @copyright   Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 *  @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt    LGPL
 *  @version     1.7.9, 2013-06-02
 */


/**  Require mPDF library */
$pdfRendererClassFile = _Settings::getPdfRendererPath() . '/mpdf.php';
if (file_exists($pdfRendererClassFile)) {
    require_once $pdfRendererClassFile;
} else {
    throw new _Writer_Exception('Unable to load PDF Rendering library');
}

/**
 *  _Writer_PDF_mPDF
 *
 *  @category    
 *  @package     _Writer_PDF
 *  @copyright   Copyright (c) 2006 - 2013  (http://www.codeplex.com/)
 */
class _Writer_PDF_mPDF extends _Writer_PDF_Core implements _Writer_IWriter
{
    /**
     *  Create a new _Writer_PDF
     *
     *  @param    $phpExcel   object
     */
    public function __construct( $phpExcel)
    {
        parent::__construct($phpExcel);
    }

    /**
     *  Save  to file
     *
     *  @param     string     $pFilename   Name of the file to save as
     *  @throws    _Writer_Exception
     */
    public function save($pFilename = NULL)
    {
        $fileHandle = parent::prepareForSave($pFilename);

        //  Default PDF paper size
        $paperSize = 'LETTER';    //    Letter    (8.5 in. by 11 in.)

        //  Check for paper size and page orientation
        if (is_null($this->getSheetIndex())) {
            $orientation = ($this->_phpExcel->getSheet(0)->getPageSetup()->getOrientation()
                == _Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
                    ? 'L'
                    : 'P';
            $printPaperSize = $this->_phpExcel->getSheet(0)->getPageSetup()->getPaperSize();
            $printMargins = $this->_phpExcel->getSheet(0)->getPageMargins();
        } else {
            $orientation = ($this->_phpExcel->getSheet($this->getSheetIndex())->getPageSetup()->getOrientation()
                == _Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
                    ? 'L'
                    : 'P';
            $printPaperSize = $this->_phpExcel->getSheet($this->getSheetIndex())->getPageSetup()->getPaperSize();
            $printMargins = $this->_phpExcel->getSheet($this->getSheetIndex())->getPageMargins();
        }
        $this->setOrientation($orientation);

        //  Override Page Orientation
        if (!is_null($this->getOrientation())) {
            $orientation = ($this->getOrientation() == _Worksheet_PageSetup::ORIENTATION_DEFAULT)
                ? _Worksheet_PageSetup::ORIENTATION_PORTRAIT
                : $this->getOrientation();
        }
        $orientation = strtoupper($orientation);

        //  Override Paper Size
        if (!is_null($this->getPaperSize())) {
            $printPaperSize = $this->getPaperSize();
        }

        if (isset(self::$_paperSizes[$printPaperSize])) {
            $paperSize = self::$_paperSizes[$printPaperSize];
        }

        //  Create PDF
        $pdf = new mpdf();
        $ortmp = $orientation;
        $pdf->_setPageSize(strtoupper($paperSize), $ortmp);
        $pdf->DefOrientation = $orientation;
        $pdf->AddPage($orientation);

        //  Document info
        $pdf->SetTitle($this->_phpExcel->getProperties()->getTitle());
        $pdf->SetAuthor($this->_phpExcel->getProperties()->getCreator());
        $pdf->SetSubject($this->_phpExcel->getProperties()->getSubject());
        $pdf->SetKeywords($this->_phpExcel->getProperties()->getKeywords());
        $pdf->SetCreator($this->_phpExcel->getProperties()->getCreator());

        $pdf->WriteHTML(
            $this->generateHTMLHeader(FALSE) .
            $this->generateSheetData() .
            $this->generateHTMLFooter()
        );

        //  Write to file
        fwrite($fileHandle, $pdf->Output('', 'S'));

		parent::restoreStateAfterSave($fileHandle);
    }

}
