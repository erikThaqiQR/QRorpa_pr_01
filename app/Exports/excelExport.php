<?php
namespace App\Exports;

use App\exportToExcel;
use App\Orders;
use Maatwebsite\Excel\Concerns\FromCollection;

class excelExport implements FromCollection{
  public function collection(){
    return exportToExcel::all();
  }
}