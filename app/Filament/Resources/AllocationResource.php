<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AllocationResource\Pages;
use App\Filament\Resources\AllocationResource\RelationManagers;
use App\Models\Allocation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AllocationResource extends Resource
{
    protected static ?string $model = Allocation::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        // Custom Header
                        Forms\Components\Grid::make(2)
                            ->schema([
                                Forms\Components\Placeholder::make('spacer')
                                    ->label('')
                                    ->content(''),

                                Forms\Components\Select::make('student_id')
                                    ->label('')
                                    ->relationship('student', 'full_name')
                                    ->getOptionLabelFromRecordUsing(fn($record) => "{$record->full_name} ({$record->student_id})")
                                    ->searchable(['full_name', 'student_id'])
                                    ->preload(false)
                                    ->required()
                                    ->placeholder('Search Student ID or Name...')
                                    ->extraAttributes(['class' => 'min-w-[300px]'])
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                                        if ($state) {
                                            $student = \App\Models\Student::find($state);
                                            if ($student) {
                                                $set('student_name', $student->full_name);
                                                $set('student_number', $student->student_id);
                                                $set('student_gender', ucfirst($student->gender));
                                                $set('student_gender_value', strtolower($student->gender)); // Store raw gender
                                                $set('student_dept', $student->department);
                                            }
                                        } else {
                                            $set('student_name', null);
                                            $set('student_number', null);
                                            $set('student_gender', null);
                                            $set('student_gender_value', null);
                                            $set('student_dept', null);
                                        }
                                    }),
                            ])
                            ->extraAttributes(['class' => 'bg-gray-50 dark:bg-gray-800 px-6 py-4 rounded-t-xl border-b border-gray-200 dark:border-gray-700 items-center']),

                        // Card Body
                        Forms\Components\Group::make()
                            ->schema([
                                // Student Details (Visible when selected)
                                Forms\Components\Grid::make(4)
                                    ->schema([
                                        Forms\Components\TextInput::make('student_name')
                                            ->label('Name')
                                            ->disabled()
                                            ->dehydrated(false),
                                        Forms\Components\TextInput::make('student_number')
                                            ->label('Student ID')
                                            ->disabled()
                                            ->dehydrated(false),
                                        Forms\Components\TextInput::make('student_gender')
                                            ->label('Gender')
                                            ->disabled()
                                            ->dehydrated(false),
                                        Forms\Components\Hidden::make('student_gender_value')
                                            ->dehydrated(false),
                                        Forms\Components\TextInput::make('student_dept')
                                            ->label('Department')
                                            ->disabled()
                                            ->dehydrated(false),
                                    ])
                                    ->visible(fn(Forms\Get $get) => $get('student_id') !== null)
                                    ->extraAttributes(['class' => 'mb-6 border-b pb-6 border-gray-100 dark:border-gray-800']),

                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        // Hostel Selection (Left)
                                        Forms\Components\Select::make('hostel_id')
                                            ->label('Hostel')
                                            ->relationship('hostel', 'name', function (Builder $query, Forms\Get $get) {
                                                $gender = $get('student_gender_value');
                                                if ($gender) {
                                                    return $query->whereIn('gender', [$gender, 'mixed']);
                                                }
                                                return $query;
                                            })
                                            ->required()
                                            ->live()
                                            ->afterStateUpdated(fn(Forms\Set $set) => $set('room_id', null)),

                                        // Room Selection (Filtered by Hostel)
                                        Forms\Components\Select::make('room_id')
                                            ->label('Room')
                                            ->relationship(
                                                'room',
                                                'room_number',
                                                fn(Builder $query, Forms\Get $get) =>
                                                $query->where('hostel_id', $get('hostel_id'))
                                            )
                                            ->getOptionLabelFromRecordUsing(fn($record) => "Room {$record->room_number} (Floor {$record->floor})")
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->disabled(fn(Forms\Get $get) => !$get('hostel_id')),

                                        Forms\Components\DatePicker::make('allocation_date')
                                            ->default(now())
                                            ->required(),

                                        Forms\Components\Select::make('status')
                                            ->options([
                                                'active' => 'Active',
                                                'completed' => 'Completed',
                                                'cancelled' => 'Cancelled',
                                            ])
                                            ->default('active')
                                            ->required(),

                                        Forms\Components\Hidden::make('allocation_type')
                                            ->default('manual'),

                                        Forms\Components\TextInput::make('academic_year')
                                            ->default(date('Y') . '-' . (date('Y') + 1))
                                            ->required(),
                                    ]),
                            ])
                            ->extraAttributes(['class' => 'p-6']),
                    ])
                    ->columnSpanFull()
                    ->extraAttributes(['class' => 'bg-white dark:bg-gray-900 rounded-xl shadow ring-1 ring-gray-950/5 dark:ring-white/10']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('hostel.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('allocation_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('allocation_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('academic_year')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAllocations::route('/'),
            'create' => Pages\CreateAllocation::route('/create'),
            'edit' => Pages\EditAllocation::route('/{record}/edit'),
        ];
    }
}
