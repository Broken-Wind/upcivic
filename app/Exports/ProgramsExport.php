<?php

namespace App\Exports;

use App\Program;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;

class ProgramsExport implements FromQuery
{
    use Exportable;

    public function __construct($program_ids)
    {
        $this->program_ids = $program_ids;
    }

    public function query()
    {
        return Program::with(['meetings.site', 'contributors.organization'])->whereIn('id', $this->program_ids);
    }
}
