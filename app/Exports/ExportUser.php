<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportUser implements FromCollection, WithHeadings
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect([$this->map($this->user)]);
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
