<?php
class VcsStats_Analyzer
{
    protected $_cache;

    protected function _getRevisionsCountByAuthor()
    {
        VcsStats_Runner_Cli::displayMessage(
            'Calculating revisions count by author'
        );

        $sql = 'SELECT author, COUNT(*) AS count
                FROM revisions
                GROUP BY author
                ORDER BY count DESC';
        $data = $this->_cache->query($sql);

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

    public function __construct(VcsStats_Cache $cache)
    {
        VcsStats_Runner_Cli::displayMessage('Initializing analyzer');
        $this->_cache = $cache;
    }

    public function analyze()
    {
        $data   = array();
        $data[] = $this->_getRevisionsCountByAuthor();
        return $data;
    }
}