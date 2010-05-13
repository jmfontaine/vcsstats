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

class VcsStats_Report_Element_Table_ColumnTest
    extends PHPUnit_Framework_TestCase
{
    /* Tests */
    public function testConstructor()
    {
        $column = new VcsStats_Report_Element_Table_Column(
            'Dummy',
            'dummy',
            VcsStats_Report_Element_Table_Column::ALIGNMENT_CENTER
        );

        $this->assertSame('Dummy', $column->getTitle());
        $this->assertSame('dummy', $column->getCode());
        $this->assertSame(
            VcsStats_Report_Element_Table_Column::ALIGNMENT_CENTER,
            $column->getAlignment()
        );
    }

    public function testGetAlignment()
    {
        $column = new VcsStats_Report_Element_Table_Column(
            'Dummy',
            'dummy',
            VcsStats_Report_Element_Table_Column::ALIGNMENT_CENTER
        );
        $this->assertSame(
            VcsStats_Report_Element_Table_Column::ALIGNMENT_CENTER,
            $column->getAlignment()
        );
    }

    public function testGetCode()
    {
        $column = new VcsStats_Report_Element_Table_Column('Dummy', 'dummy');
        $this->assertSame('dummy', $column->getCode());
    }

    public function testGetTitle()
    {
        $column = new VcsStats_Report_Element_Table_Column('Dummy', 'dummy');
        $this->assertSame('Dummy', $column->getTitle());
    }

    public function testSetAlignment()
    {
        $column = new VcsStats_Report_Element_Table_Column(
            'Dummy',
            'dummy',
            VcsStats_Report_Element_Table_Column::ALIGNMENT_CENTER
        );
        $column->setAlignment(
            VcsStats_Report_Element_Table_Column::ALIGNMENT_RIGHT
        );
        $this->assertSame(
            VcsStats_Report_Element_Table_Column::ALIGNMENT_RIGHT,
            $column->getAlignment()
        );
    }

    public function testSetCode()
    {
        $column = new VcsStats_Report_Element_Table_Column('Dummy', 'dummy');
        $column->setCode('new-dummy');
        $this->assertSame('new-dummy', $column->getCode());
    }

    public function testSetTitle()
    {
        $column = new VcsStats_Report_Element_Table_Column('Dummy', 'dummy');
        $column->setTitle('New dummy');
        $this->assertSame('New dummy', $column->getTitle());
    }

    /* Bugs */
}