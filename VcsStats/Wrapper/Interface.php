<?php
interface VcsStats_Wrapper_Interface
{
    public function getRepositoryPath();
    public function getRevisionsData($startRevision = '1',
                                     $endRevision = 'HEAD');
}