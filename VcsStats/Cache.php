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
 * Cache management class
 */
class VcsStats_Cache
{
    /**
     * Path to the cache directory
     *
     * @var string
     */
    protected $_cachePath;

    /**
     * Instance of the PDO connection to the database
     *
     * @var PDO
     */
    protected $_pdo;

    /**
     * Instance of the VCS wrapper to access repository
     *
     * @var VcsStats_Wrapper_Interface
     */
    protected $_wrapper;

    /**
     * Opens connection to the database
     *
     * @return void
     */
    protected function _connect()
    {
        $filename = hash('sha1', $this->_wrapper->getRepositoryPath());
        $dsn      = "sqlite:$this->_cachePath/$filename";
        $pdo      = new PDO($dsn, '', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_pdo = $pdo;
    }

    /**
     * Creates database tables
     *
     * @return void
     */
    protected function _initializeDatabase()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS informations (
                id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT, value TEXT);';
        $this->_pdo->exec($sql);

        $sql = "SELECT value FROM informations WHERE name = 'creationDate';";
        $creationDate = $this->fetchOne($sql);

        if (false !== $creationDate) {
            $this->_insert(
                'informations',
                array('name' => 'creationDate', 'value' => time())
            );
            $this->_insert(
                'informations',
                array('name' => 'modificationDate', 'value' => time())
            );
            $this->_insert(
                'informations',
                array('name' => 'vcs', 'value' => $this->_wrapper->getVcsName())
            );
            $this->_insert(
                'informations',
                array(
                    'name' => 'repositoryPath',
                    'value' => $this->_wrapper->getRepositoryPath()
                )
            );
        } else {
            $this->_update(
                'informations',
                array('name' => 'modificationDate', 'value' => time()),
                "name = 'modificationDate'"
            );
        }

        $sql = 'CREATE TABLE IF NOT EXISTS revisions (id INTEGER PRIMARY KEY,
                author TEXT, date INTEGER, message TEXT);';
        $this->_pdo->exec($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS resources (
                id INTEGER PRIMARY KEY AUTOINCREMENT, revisionId INTEGER,
                action TEXT, path TEXT, type TEXT,
                FOREIGN KEY(revisionId) REFERENCES revisions(id));';
        $this->_pdo->exec($sql);
    }

    /**
     * Inserts data into a table
     *
     * @param string $table Table name
     * @param array  $data  Data to insert
     * @return bool Whether the insertion succeeded or not
     */
    protected function _insert($table, array $data)
    {
        $columns = array_keys($data);
        $values  = array_values($data);

        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (?%s)',
            $table,
            implode(',', $columns),
            str_repeat(', ?', count($columns) - 1)
        );

        $statement = $this->_pdo->prepare($sql);
        return $statement->execute($values);
    }

    /**
     * Updates data in a table
     *
     * @param string $table Table name
     * @param array  $data  Data to insert
     * @param string $where SQL where clause
     * @return bool Whether the update succeeded or not
     */
    protected function _update($table, array $data, $where)
    {
        $values       = array_values($data);
        $assignations = array();
        foreach ($data as $field => $value) {
            $assignations[] = $field . ' = ?';
        }

        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            implode(',', $assignations),
            $where
        );

        $statement = $this->_pdo->prepare($sql);
        return $statement->execute($values);
    }

    /**
     * Populates the cache with revisions data
     *
     * @param array $data Data
     * @return void
     */
    private function _populate(array $data)
    {
        $count = count($data);
        if (0 === $count) {
            VcsStats_Runner_Cli::displayMessage(
                'No revision to insert. Skipping cache population'
            );
            return;
        }

        VcsStats_Runner_Cli::displayMessage(
            "Populating cache data with $count revisions"
        );

        $cachedRevisionsIds = $this->getCachedRevisionsIds();

        $sql = 'INSERT INTO revisions (id,author,date,message)
                VALUES (:id, :author, :date, :message);';
        $options   = array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY);
        $revisionsStatement = $this->_pdo->prepare($sql, $options);

        $sql = 'INSERT INTO resources (revisionId,action,path,type)
                VALUES (:revisionId, :action, :path, :type);';
        $options   = array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY);
        $resourcesStatement = $this->_pdo->prepare($sql, $options);

        foreach($data as $revision) {
            if (in_array($revision['id'], $cachedRevisionsIds)) {
                VcsStats_Runner_Cli::displayDebug(
                    'Skipping already cached revision ' . $revision['id']
                );
                continue;
            }

            VcsStats_Runner_Cli::displayDebug(
                'Caching revision ' . $revision['id']
            );
            $params = array(
                ':id'      => $revision['id'],
                ':author'  => $revision['author'],
                ':date'    => $revision['date'],
                ':message' => $revision['message'],
            );
            $result = $revisionsStatement->execute($params);

            foreach($revision['resources'] as $resource) {
                $params = array(
                    ':revisionId' => $revision['id'],
                    ':action'     => $resource['action'],
                    ':path'       => $resource['path'],
                    ':type'       => $resource['type'],
                );
                $resourcesStatement->execute($params);
            }
        }
    }

    /**
     * Class constructor
     *
     * @param VcsStats_Wrapper_Interface $wrapper   VCS wrapper
     * @param string                     $cachePath Path to the cache directory
     * @return void
     */
    public function __construct(VcsStats_Wrapper_Interface $wrapper, $cachePath)
    {
        VcsStats_Runner_Cli::displayMessage('Initializing cache');

        $this->_cachePath = $cachePath;
        $this->_wrapper   = $wrapper;

        $this->_connect();
        $this->_initializeDatabase();
    }

    /**
     * Executes the SQL query and returns all the results
     *
     * @param string  $sql       SQL query
     * @param int     $fetchMode Fetch mode
     * @param int     $column    Column index, used only when fetch mode is
     *                           PDO::FETCH_COLUMN
     * @return array
     */
    public function fetchAll($sql, $fetchMode = PDO::FETCH_ASSOC, $column = 0)
    {
        $statement = $this->_pdo->query($sql);

        if (PDO::FETCH_COLUMN === $fetchMode) {
            $result = $statement->fetchAll($fetchMode, $column);
        } else {
            $result = $statement->fetchAll($fetchMode);
        }

        return $result;
    }

    /**
     * Executes the SQL query and returns only one column
     *
     * @param string $sql    SQL query
     * @param int    $column Column index
     * @return array
     */
    public function fetchColumn($sql, $column = 0)
    {
        $statement = $this->_pdo->query($sql);
        return $statement->fetchColumn($column);
    }

    /**
     * Executes the SQL query and returns only the first value of the first
     * column
     *
     * @param string $sql SQL query
     * @return mixed
     */
    public function fetchOne($sql)
    {
        $statement = $this->_pdo->query($sql);
        return $statement->fetchColumn(0);
    }

    /**
     * Returns the last cached revision id
     *
     * @return int|null
     */
    public function getLastCachedRevisionId()
    {
        $sql = 'SELECT id
                FROM revisions
                ORDER BY id DESC
                LIMIT 1;';
        $id = $this->fetchColumn($sql, 0);
        if (false === $id) {
            $id = null;
        }
        return $id;
    }

    /**
     * Returns the cached revisions ids
     *
     * @return array
     */
    public function getCachedRevisionsIds()
    {
        $sql = 'SELECT id
                FROM revisions
                ORDER BY id ASC;';
        return $this->fetchAll($sql, PDO::FETCH_COLUMN, 0);
    }

    /**
     * Updates the cache data
     *
     * @param int|null $endRevisionId Id of the last revision to retrieve
     * @return void
     */
    public function updateData($endRevisionId = null)
    {
        VcsStats_Runner_Cli::displayMessage('Updating cache data');

        $startRevisionId = $this->getLastCachedRevisionId();
        if (null === $startRevisionId) {
            $startRevisionId = 1;
        }

        if ($startRevisionId >= $endRevisionId) {
            VcsStats_Runner_Cli::displayDebug('Everything is already in cache');
            return;
        }

        if (null === $endRevisionId) {
            $endRevisionId = 'HEAD';
        }
        $data = $this->_wrapper->getRevisionsData(
            $startRevisionId,
            $endRevisionId
        );
        $this->_populate($data);
    }
}