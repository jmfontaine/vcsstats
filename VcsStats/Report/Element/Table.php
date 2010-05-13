<?php
/**
 * Copyright (c) 2010, Jean-Marc Fontaine
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *     * Redistributions of source code must retain the above copyright
 *       notice, this list of conditions and the following disclaimer.
 *     * Redistributions in binary form must reproduce the above copyright
 *       notice, this list of conditions and the following disclaimer in the
 *       documentation and/or other materials provided with the distribution.
 *     * Neither the name of the <organization> nor the
 *       names of its contributors may be used to endorse or promote products
 *       derived from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL <COPYRIGHT HOLDER> BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
 * ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * @package VcsStats
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2010 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * Report table element
 */
class VcsStats_Report_Element_Table extends VcsStats_Report_Element_Abstract
{
    /**
     * Table columns
     *
     * @var array
     */
    protected $_columns = array();

    /**
     * Table rows
     *
     * @var $_rows unknown_type
     */
    protected $_rows = array();

    /**
     * Adds a column to the table
     *
     * @param VcsStats_Report_Element_Table_Column $column Column to be added
     * @return The current instance of the report
     */
    public function addColumn(VcsStats_Report_Element_Table_Column $column)
    {
        $this->_columns[] = $column;
        return $this;
    }

    /**
     * Adds a row to the table
     *
     * @param array $row Row to be added
     * @return The current instance of the report
     */
    public function addRow(array $row)
    {
        $this->_rows[] = $row;
        return $this;
    }

    /**
     * Retrieve a column by its code
     *
     * @param string $code Column code
     * @throws OutOfBoundsException When column can not be found
     */
    public function getColumn($code)
    {
        foreach ($this->_columns as $column) {
            if ($column->getCode() == $code) {
                return $column;
            }
        }

        throw new OutOfBoundsException("Unknow column ($code)");
    }

    /**
     * Returns the informations about the columns of the table
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->_columns;
    }

    /**
     * Returns the rows of the table
     *
     * @return array
     */
    public function getRows()
    {
        return $this->_rows;
    }

    /**
     * Defines all the columns at the same time. Existing columns are replaced.
     *
     * @param array $columns Columns to be defined
     * @return The current instance of the report
     */
    public function setColumns(array $columns)
    {
        $this->_columns = $columns;
        return $this;
    }

    /**
     * Defines all the rows at the same time. Existing rows are replaced.
     *
     * @param array $rows Rows to be defined
     * @return The current instance of the report
     */
    public function setRows(array $rows)
    {
        $this->_rows = $rows;
        return $this;
    }
}
