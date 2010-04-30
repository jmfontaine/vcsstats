<?php
interface VcsStats_Reporter_Interface
{
    public function displayData(array $data);
    public function formatData(array $data);
}