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
 * Wrapper for Subversion
 */
class VcsStats_Wrapper_Subversion extends VcsStats_Wrapper_Abstract
{
    /**
     * Executes svn command and returns output as a SimpleXMLElement object
     *
     * @param string $options Options to provide to svn command
     * @return SimpleXMLElement
     */
    protected function _execute($options)
    {
        $command = 'svn ' . $options . ' --non-interactive --xml';
        VcsStats_Runner_Cli::displayDebug("Executing command '$command'");
        exec($command, $output, $returnCode);

        $output = implode('', $output);
        return new SimpleXMLElement($output);
    }

    /**
     * Returns data for revisions in the specified range
     *
     * @param string $startRevisionId   Id of the first revision to retrieve
     * @param string $endRevisionId     Id of the last revision to retrieve
     * @return array
     */
    public function getRevisionsData($startRevisionId = 1,
                                     $endRevisionId = 'HEAD')
    {
        $options = sprintf(
            'log -v -q -r %s:%s %s',
            $startRevisionId,
            $endRevisionId,
            $this->_options['path']
        );
        $log       = $this->_execute($options);
        $revisions = array();
        foreach($log as $revision) {
            $resources = array();
            foreach($revision->paths->path as $path) {
                $resources[] = array(
                    'action' => (string) strtolower($path['action']),
                    'path'   => (string) $path,
                    'type'   => (string) substr($path['kind'], 0, 1),
                );
            }

            $data = array(
                'id'        => (int)    $revision['revision'],
                'author'    => (string) $revision->author,
                'date'      => strtotime($revision->date),
                'message'   => (string) $revision->msg,
                'resources' => $resources,
            );
            array_unshift($revisions, $data);
        }
        return $revisions;
    }
}