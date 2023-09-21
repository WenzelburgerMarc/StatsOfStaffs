<?php

namespace App\Exports;

use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportAuthedUser implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $user = Auth::user();

        return collect([$this->map($user)]);
    }

    public function map($user): array
    {

        if ($user->isBlocked == 1) {
            $isBlocked = 'Yes';
        } else {
            $isBlocked = 'No';
        }

        return [
            $user->id,
            $user->Role::find($user->role_id)->name,
            $user->username,
            $user->name,
            $user->email,
            $user->avatar,
            $isBlocked,
            $user->created_at,
            $user->updated_at,
        ];
    }

    public function headings(): array
    {
        return ['ID', 'Role', 'Username', 'Name', 'E-Mail', 'Avatar', 'Is Blocked', 'Created At', 'Updated At'];
    }
}
