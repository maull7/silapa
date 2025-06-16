<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RequestExport implements FromView
{
    protected $data;

    public function __construct($requests)
    {
        $this->data = $requests;
    }

    public function view(): View
    {
        return view('exports.request', [
            'requests' => $this->data
        ]);
    }
}
