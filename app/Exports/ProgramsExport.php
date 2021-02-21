<?php

namespace App\Exports;

use App\Program;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProgramsExport implements FromQuery, WithHeadings, WithMapping
{
    use Exportable;

    public function __construct($programIds)
    {
        $this->program_ids = $programIds;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Partner/s',
            'Ages/Grades',
            'Min Age/Grade',
            'Max Age/Grade',
            'Min Enrollments',
            'Max Enrollments',
            'Start Date',
            'End Date',
            'Meeting Dates',
            'Start Time',
            'End Time',
            'Site',
            'Location',
            'Total Fee',
            tenant()->name . ' Portion',
            'Partner Portion',
            'Public Notes',
            'Contributor Notes',
        ];
    }

    public function map($program): array
    {
        return [
            $program->name,
            $program->description,
            $program->otherContributors()->pluck('organization.name')->sort()->implode(', '),
            $program->ages_type,
            $program->min_age,
            $program->max_age,
            $program->min_enrollments,
            $program->max_enrollments,
            $program->meetings->first()->start_date,
            $program->meetings->last()->end_date,
            $program->meetings->sortBy('start_datetime')->map(function ($meeting) {
                return $meeting->start_date . " " . $meeting->start_time . "-" . $meeting->end_time;
            })->implode(', '),
            $program->meetings->first()->start_time,
            $program->meetings->last()->end_time,
            $program->site->name,
            $program->location->name,
            $program->formatted_base_fee,
            $program->getContributorFor(tenant())->formatted_invoice_amount,
            $program->otherContributors()->sum('formatted_invoice_amount'),
            $program->public_notes,
            $program->contributor_notes,
        ];
    }

    public function query()
    {
        return Program::with(['meetings.site', 'contributors.organization'])->whereIn('id', $this->program_ids);
    }

}
