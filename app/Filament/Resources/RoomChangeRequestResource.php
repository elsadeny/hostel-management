<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomChangeRequestResource\Pages;
use App\Filament\Resources\RoomChangeRequestResource\RelationManagers;
use App\Models\RoomChangeRequest;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomChangeRequestResource extends Resource
{
    protected static ?string $model = RoomChangeRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Management';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('student_id')
                    ->relationship('student', 'full_name')
                    ->label('Student')
                    ->disabled() // Student shouldn't be changed
                    ->required(),
                Forms\Components\Select::make('current_room_id')
                    ->relationship('currentRoom', 'room_number')
                    ->label('Current Room')
                    ->disabled() // Current room shouldn't be changed
                    ->required(),
                Forms\Components\Textarea::make('reason')
                    ->required()
                    ->disabled() // Reason shouldn't be changed by admin
                    ->columnSpanFull(),
                Forms\Components\Select::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ])
                    ->required()
                    ->live(), // To trigger visibility of new_room_id
                Forms\Components\Select::make('new_room_id')
                    ->label('Assign New Room')
                    ->options(function (Forms\Get $get, ?\App\Models\RoomChangeRequest $record) {
                        $studentId = $get('student_id') ?? $record?->student_id;
                        if (!$studentId)
                            return [];

                        $student = \App\Models\Student::find($studentId);
                        if (!$student)
                            return [];

                        return \App\Models\Room::available()
                            ->whereHas('hostel', function ($q) use ($student) {
                                $q->whereIn('gender', [strtolower($student->gender), 'mixed']);
                            })
                            ->pluck('room_number', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->visible(fn(Forms\Get $get) => $get('status') === 'approved')
                    ->helperText('Select a room to automatically move the student. Leave empty to approve without moving.'),
                Forms\Components\Textarea::make('admin_notes')
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('processed_by')
                    ->default(auth()->id()),
                Forms\Components\Hidden::make('processed_at')
                    ->default(now()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.full_name')
                    ->label('Student')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currentRoom.room_number')
                    ->label('Current Room')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason')
                    ->limit(50)
                    ->tooltip(fn(Tables\Columns\TextColumn $column): ?string => $column->getState()),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('processed_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'rejected' => 'Rejected',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn(\App\Models\RoomChangeRequest $record) => $record->status === 'pending'),
                Tables\Actions\ViewAction::make()
                    ->visible(fn(\App\Models\RoomChangeRequest $record) => $record->status !== 'pending'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
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
            'index' => Pages\ListRoomChangeRequests::route('/'),
            'create' => Pages\CreateRoomChangeRequest::route('/create'),
            'edit' => Pages\EditRoomChangeRequest::route('/{record}/edit'),
        ];
    }
}
