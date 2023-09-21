<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportUsers implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::all()->map([$this, 'map']);
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->username,
            $user->name,
            $user->email,
            $user->avatar,
            $user->created_at,
            $user->updated_at,
        ];
    }

    public function headings(): array
    {
        return ['ID', 'Username', 'Name', 'E-Mail', 'Avatar', 'Created At', 'Updated At'];
    }
}
