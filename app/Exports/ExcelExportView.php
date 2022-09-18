<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ExcelExportView implements FromView
{

    protected $tableName;
    protected $tableHead;
    protected $tableBody;

    public function __construct($fileName,$tableHead, $tableBody)
    {
        $this->tableName = $fileName;
        $this->tableHead = $tableHead;
        $this->tableBody = $tableBody;
    }

    /**
     * Set data to the view file
     */
    public function view(): View
    {
        return view('export.excel', ['tableName' => $this->tableName,'tableHead' => $this->tableHead, 'tableBody' => $this->tableBody]);
    }
}
