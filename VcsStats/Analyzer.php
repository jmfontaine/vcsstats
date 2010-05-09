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
 * @package vcsstats
 * @author Jean-Marc Fontaine <jm@jmfontaine.net>
 * @copyright 2010 Jean-Marc Fontaine <jm@jmfontaine.net>
 * @license http://www.opensource.org/licenses/bsd-license.php BSD License
 */

/**
 * Analyzer for repository data
 */
class VcsStats_Analyzer
{
    /**
     * Cache instance
     *
     * @var VcsStats_Cache
     */
    protected $_cache;

    /**
     * Computes the number of revisions commited by each user
     *
     * @param int $startRevision First revision to work on
     * @param int $endRevision   Last revision to work on
     * @return array Computed data
     */
    protected function _computeRevisionsCountByAuthor($startRevision,
        $endRevision)
    {
        VcsStats_Runner_Cli::displayMessage(
            'Calculating revisions count by author'
        );

        $table = new VcsStats_Report_Element_Table();
        $table->setCode('revisions_count_by_author');
        $table->setTitle('Revisions count by author');

        $table->addColumn(
            new VcsStats_Report_Element_Table_Column('Author', 'author')
        );
        $table->addColumn(
            new VcsStats_Report_Element_Table_Column(
                'Count',
                'count',
                VcsStats_Report_Element_Table_Column::ALIGNMENT_RIGHT
            )
        );

        $where = '';
        if (null !== $startRevision && null != $endRevision) {
            $where = "WHERE id >= $startRevision AND id <= $endRevision";
        }

        $sql = "SELECT author, COUNT(*) AS count
                FROM revisions
                $where
                GROUP BY author
                ORDER BY count DESC";
        $data = $this->_cache->fetchAll($sql);
        $table->setRows($data);

        return $table;
    }

    /**
     * Class constructor
     *
     * @param VcsStats_Cache $cache Cache instance
     * @return void
     */
    public function __construct(VcsStats_Cache $cache)
    {
        VcsStats_Runner_Cli::displayMessage('Initializing analyzer');

        $this->_cache = $cache;
    }

    /**
     * Generates and returns report
     *
     * @param int $startRevision First revision to work on
     * @param int $endRevision   Last revision to work on
     * @return VcsStats_Report Report
     */
    public function getReport($startRevision, $endRevision)
    {
        $report = new VcsStats_Report();

        $summarySection = new VcsStats_Report_Section();
        $report->addSection($summarySection);

        $summarySection->addElement(
            $this->_computeRevisionsCountByAuthor(
                $startRevision,
                $endRevision
            )
        );

        return $report;
    }
}