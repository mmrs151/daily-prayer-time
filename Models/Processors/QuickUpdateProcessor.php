<?php

class QuickUpdateProcessor
{
    /**
     * @var array
     */
    private $data;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function process()
    {
        $db = new DatabaseConnection();
        $rows = $this->data['thisMonth'];
        $db->updateRow($rows);
    }
}