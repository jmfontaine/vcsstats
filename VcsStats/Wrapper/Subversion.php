<?php
class VcsStats_Wrapper_Subversion extends VcsStats_Wrapper_Abstract
{
    protected function _execute($options)
    {
        $command = 'svn ' . $options . ' --non-interactive --xml';
        VcsStats_Runner_Cli::displayDebug("Executing command '$command'");
        exec($command, $output, $returnCode);

        $output = implode('', $output);
        return new SimpleXMLElement($output);
    }

    public function getRevisionsData($startRevision = '1',
                                     $endRevision = 'HEAD')
    {
        $options = sprintf(
            'log -v -q -r %s:%s %s',
            $startRevision,
            $endRevision,
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