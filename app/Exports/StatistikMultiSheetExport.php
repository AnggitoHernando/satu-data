<?php

namespace App\Exports;

use App\Models\GroupKategori;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class StatistikMultiSheetExport implements WithMultipleSheets
{
    use Exportable;

    protected Collection $groups;

    public function __construct(Collection $groups)
    {
        $this->groups = $groups;
    }

    public function sheets(): array
    {
        return $this->groups->map(
            fn($group) =>
            new StatistikSheetExport($group)
        )->toArray();
    }
}
