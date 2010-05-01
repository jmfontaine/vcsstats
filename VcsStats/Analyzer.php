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
     * @return array Computed data
     */
    protected function _getRevisionsCountByAuthor()
    {
        VcsStats_Runner_Cli::displayMessage(
            'Calculating revisions count by author'
        );

        $sql = 'SELECT author, COUNT(*) AS count
                FROM revisions
                GROUP BY author
                ORDER BY count DESC';
        $data = $this->_cache->fetchAll($sql);

        return array(
            'code'    => 'revisions_count_by_author',
            'name'    => 'Revisions count by author',
            'columns' => array(
                'author' => array(
                    'alignment' => 'left',
                    'label'     => 'Author',
                ),
                'count' => array(
                    'alignment' => 'right',
                    'label'     => 'Count',
                ),
            ),
            'data' => $data,
        );
    }

    /**
     * Class constructor
     *
     * @param VcsStats_Cache $cache Cache instance
     */
    public function __construct(VcsStats_Cache $cache)
    {
        VcsStats_Runner_Cli::displayMessage('Initializing analyzer');

        $this->_cache = $cache;
    }

    /**
     * Generates and returns data regarding the repository
     */
    public function getAnalyzedData()
    {
        $data   = array();
        $data[] = $this->_getRevisionsCountByAuthor();
        return $data;
    }
}