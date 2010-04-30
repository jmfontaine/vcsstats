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
class VcsStats_Cache
{
    protected $_cachePath;
    protected $_pdo;
    protected $_wrapper;

    protected function _connect()
    {
        $filename = hash('sha1', $this->_wrapper->getRepositoryPath());
        $dsn = "sqlite:$this->_cachePath/$filename";
        $pdo = new PDO($dsn, '', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->_pdo = $pdo;
    }

    protected function _initializeDatabase()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS revisions (id INTEGER PRIMARY KEY,
                author TEXT, date INTEGER, message TEXT)';
        $this->_pdo->exec($sql);

        $sql = 'CREATE TABLE IF NOT EXISTS resources (
                id INTEGER PRIMARY KEY AUTOINCREMENT, revisionId INTEGER,
                action TEXT, path TEXT, type TEXT)';
        $this->_pdo->exec($sql);
    }

    public function __construct(VcsStats_Wrapper_Interface $wrapper, $cachePath)
    {
        VcsStats_Runner_Cli::displayMessage('Initializing cache');

        $this->_cachePath = $cachePath;
        $this->_wrapper   = $wrapper;

        $this->_connect();
        $this->_initializeDatabase();
    }

    public function getLastCachedRevision()
    {
        $sql = 'SELECT id FROM revisions
                ORDER BY id DESC
                LIMIT 1;';
        $statement = $this->_pdo->query($sql);
        $id = $statement->fetchColumn(0);
        if (false === $id) {
            $id = null;
        }
        return $id;
    }

    public function populate(array $data)
    {
        $count = count($data);
        if (0 === $count) {
            VcsStats_Runner_Cli::displayMessage(
                "No revision to insert. Skipping cache population"
            );
            return;
        }

        VcsStats_Runner_Cli::displayMessage(
            "Populating cache data with $count revisions"
        );

        $sql = 'INSERT INTO revisions (id,author,date,message)
                VALUES (:id, :author, :date, :message);';
        $options   = array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY);
        $revisionsStatement = $this->_pdo->prepare($sql, $options);

        $sql = 'INSERT INTO resources (revisionId,action,path,type)
                VALUES (:revisionId, :action, :path, :type);';
        $options   = array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY);
        $resourcesStatement = $this->_pdo->prepare($sql, $options);

        foreach($data as $revision) {
            $params = array(
                ':id'      => $revision['id'],
                ':author'  => $revision['author'],
                ':date'    => $revision['date'],
                ':message' => $revision['message'],
            );
            $revisionsStatement->execute($params);

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

    public function query($sql)
    {
        $statement = $this->_pdo->query($sql);
        $statement->setFetchMode(PDO::FETCH_ASSOC);
        return $statement->fetchAll();
    }

    public function updateData()
    {
        VcsStats_Runner_Cli::displayMessage('Updating cache data');

        $startRevision = $this->getLastCachedRevision();
        if (null === $startRevision) {
            $startRevision = 1;
        }

        $data = $this->_wrapper->getRevisionsData($startRevision);
        $data = array_slice($data, 1, null, true);
        $this->populate($data);
    }
}