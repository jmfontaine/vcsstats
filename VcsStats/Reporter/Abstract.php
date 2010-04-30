<?php
abstract class VcsStats_Reporter_Abstract implements VcsStats_Reporter_Interface
{
    public function displayData(array $data)
    {
        echo $this->formatData($data);
    }
}