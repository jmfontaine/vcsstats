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
 * @subpackage Tests
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2010 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

class VcsStats_Report_Element_TableTest extends PHPUnit_Framework_TestCase
{
    /* Tests */
    public function testAddColumn()
    {
        $column = new VcsStats_Report_Element_Table_Column('Dummy', 'dummy');
        $table  = new VcsStats_Report_Element_Table();
        $table->addColumn($column);

        $this->assertSame(array($column), $table->getColumns());
    }

    public function testAddColumnInvalidClass()
    {
        $column = new StdClass();
        $table = new VcsStats_Report_Element_Table();

        $this->setExpectedException('PHPUnit_Framework_Error');
        $table->addColumn($column);
    }

    public function testAddColumnImplementsFluentInterface()
    {
        $column = new VcsStats_Report_Element_Table_Column('Dummy', 'dummy');
        $table  = new VcsStats_Report_Element_Table();

        $this->assertSame($table, $table->addColumn($column));
    }

    public function testAddRow()
    {
        $row   = array('Dummy');
        $table = new VcsStats_Report_Element_Table();
        $table->addRow($row);

        $this->assertSame(array($row), $table->getRows());
    }

    public function testAddRowInvalidValue()
    {
        $row   = 'Dummy';
        $table = new VcsStats_Report_Element_Table();

        $this->setExpectedException('PHPUnit_Framework_Error');
        $table->addRow($row);
    }

    public function testAddRowImplementsFluentInterface()
    {
        $row   = array('Dummy');
        $table = new VcsStats_Report_Element_Table();

        $this->assertSame($table, $table->addRow($row));
    }

    public function testGetColumn()
    {
        $column = new VcsStats_Report_Element_Table_Column('Dummy', 'dummy');
        $table  = new VcsStats_Report_Element_Table();
        $table->addColumn($column);

        $this->assertSame($column, $table->getColumn('dummy'));
    }

    public function testGetColumnUnknownColumn()
    {
        $column = new VcsStats_Report_Element_Table_Column('Dummy', 'dummy');
        $table  = new VcsStats_Report_Element_Table();
        $table->addColumn($column);

        $this->setExpectedException('OutOfBoundsException');
        $this->assertSame($column, $table->getColumn('unknown'));
    }

    public function testGetColumns()
    {
        $table   = new VcsStats_Report_Element_Table();
        $column1 = new VcsStats_Report_Element_Table_Column('Column 1', 'col1');
        $table->addColumn($column1);
        $column2 = new VcsStats_Report_Element_Table_Column('Column 2', 'col2');
        $table->addColumn($column2);
        $column3 = new VcsStats_Report_Element_Table_Column('Column 3', 'col3');
        $table->addColumn($column3);

        $this->assertSame(
            array(
                $column1,
                $column2,
                $column3,)
            ,
            $table->getColumns()
        );
    }

    public function testGetRows()
    {
        $table = new VcsStats_Report_Element_Table();
        $row1  = array('Value 1a', 'Value 1b', 'Value 1c');
        $table->addRow($row1);
        $row2 = array('Value 2a', 'Value 2b', 'Value 2c');
        $table->addRow($row2);
        $row3 = array('Value 3a', 'Value 3b', 'Value 3c');
        $table->addRow($row3);

        $this->assertSame(
            array(
                $row1,
                $row2,
                $row3,
            ),
            $table->getRows()
        );
    }

    public function testSetColumns()
    {
        $table   = new VcsStats_Report_Element_Table();
        $columns = array(
            new VcsStats_Report_Element_Table_Column('Column 1', 'col1'),
            new VcsStats_Report_Element_Table_Column('Column 2', 'col2'),
            new VcsStats_Report_Element_Table_Column('Column 3', 'col3'),
        );
        $table->setColumns($columns);

        $this->assertSame($columns, $table->getColumns());
    }

    public function testSetColumnsExistingColumnsShouldBeReplaced()
    {
        $table   = new VcsStats_Report_Element_Table();
        $table->addColumn(
            new VcsStats_Report_Element_Table_Column('Column 0', 'col0')
        );

        $columns = array(
            new VcsStats_Report_Element_Table_Column('Column 1', 'col1'),
            new VcsStats_Report_Element_Table_Column('Column 2', 'col2'),
            new VcsStats_Report_Element_Table_Column('Column 3', 'col3'),
        );
        $table->setColumns($columns);

        $this->assertSame($columns, $table->getColumns());
    }

    public function testSetRows()
    {
        $table = new VcsStats_Report_Element_Table();
        $rows  = array(
            array('Value 1a', 'Value 1b', 'Value 1c'),
            array('Value 2a', 'Value 2b', 'Value 2c'),
            array('Value 3a', 'Value 3b', 'Value 3c'),
        );
        $table->setRows($rows);

        $this->assertSame($rows, $table->getRows());
    }

    public function testSetRowsExistingRowsShouldBeReplaced()
    {
        $table = new VcsStats_Report_Element_Table();
        $table->addRow(
            array('Value 0a', 'Value 0b', 'Value 0c')
        );

        $rows  = array(
            array('Value 1a', 'Value 1b', 'Value 1c'),
            array('Value 2a', 'Value 2b', 'Value 2c'),
            array('Value 3a', 'Value 3b', 'Value 3c'),
        );
        $table->setRows($rows);

        $this->assertSame($rows, $table->getRows());
    }

    /* Bugs */
}