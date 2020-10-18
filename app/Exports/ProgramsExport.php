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

    public function __construct($program_ids)
    {
        $this->program_ids = $program_ids;
    }

    public function headings(): array
    {
        return [
            'Name',
            'Description',
            'Partner/s', # not done
            'Min',
            'Max',
            'Ages/Grades',
            'Min Enrollments',
            'Max Enrollments',
            'Start Date',
            'End Date',
            'Start Time',
            'End Time',
            'Site',
            'Location',
            'Total Fee',
            'Host Portion', # not done
            'Partner Portion', # not done
            'Material Fee', # not done
        ];
    }

    public function map($program): array
    {
        return [
            $program->name,
            $program->description,
            $program->otherContributors()->pluck('organization_id')->sort()->implode(','), # get names instead
            $program->min_age,
            $program->max_age,
            $program->ages_type,
            $program->min_enrollments,
            $program->max_enrollments,
            Carbon::parse($program->meetings->first()->start_datetime)->toDateString(),
            Carbon::parse($program->meetings->last()->end_datetime)->toDateString(),
            Carbon::parse($program->meetings->first()->start_datetime)->toTimeString(), 
            Carbon::parse($program->meetings->last()->end_datetime)->toTimeString(),
            $program->site->name,
            $program->location->name,
            $program->getFormattedBaseFeeAttribute(),
        ];
    }

    public function query()
    {
        return Program::with(['meetings.site', 'contributors.organization'])->whereIn('id', $this->program_ids);
    }

}
