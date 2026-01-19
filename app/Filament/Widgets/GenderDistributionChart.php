<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;

class GenderDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Gender Distribution';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $male = \App\Models\Allocation::where('status', 'active')
            ->whereHas('student', fn($q) => $q->where('gender', 'male'))
            ->count();

        $female = \App\Models\Allocation::where('status', 'active')
            ->whereHas('student', fn($q) => $q->where('gender', 'female'))
            ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Students',
                    'data' => [$male, $female],
                    'backgroundColor' => ['#3b82f6', '#ec4899'], // Blue for Male, Pink for Female
                ],
            ],
            'labels' => ['Male', 'Female'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
