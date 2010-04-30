<?php
abstract class VcsStats_Wrapper_Abstract implements VcsStats_Wrapper_Interface
{
    protected $_options;

    public function __construct(array $options)
    {
        VcsStats_Runner_Cli::displayMessage('Initializing wrapper');
        $this->_options = $options;
    }

    public function getRepositoryPath()
    {
        if (empty($this->_options['path'])) {
            throw new VcsStats_Wrapper_Exception('Path to repository is missing');
        }
        return $this->_options['path'];
    }
}