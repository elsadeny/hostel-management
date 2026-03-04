<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomApplicationResource\Pages;
use App\Models\RoomApplication;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RoomApplicationResource extends Resource
{
    protected static ?string $model = RoomApplication::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Management';

    protected static ?string $navigationLabel = 'Room Applications';

    protected static ?int $navigationSort = 3;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('status', 'pending')->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Student Information')
                    ->schema([
                        Forms\Components\TextInput::make('student.full_name')
                            ->label('Student Name')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('student.student_id')
                            ->label('Student ID')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('student.department')
                            ->label('Department')
                            ->disabled()
                            ->dehydrated(false),
                        Forms\Components\TextInput::make('student.gender')
                            ->label('Gender')
                            ->disabled()
                            ->dehydrated(false),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Application Details')
                    ->schema([
                        Forms\Components\TextInput::make('preferred_hostel')
                            ->label('Preferred Hostel')
                            ->disabled(),
                        Forms\Components\TextInput::make('room_type')
                            ->label('Room Type')
                            ->disabled(),
                        Forms\Components\TextInput::make('academic_year')
                            ->label('Academic Year')
                            ->disabled(),
                        Forms\Components\Textarea::make('special_needs')
                            ->label('Special Needs')
                            ->disabled()
                            ->columnSpanFull(),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes from Student')
                            ->disabled()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Admin Decision')
                    ->schema([
                        Forms\Components\Select::make('status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('admin_notes')
                            ->label('Admin Notes')
                            ->columnSpanFull(),
                        Forms\Components\Hidden::make('processed_by')
                            ->default(auth()->id()),
                        Forms\Components\Hidden::make('processed_at')
                            ->default(now()),
                    ])
                    ->columns(2),
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
                Tables\Columns\TextColumn::make('student.student_id')
                    ->label('Student ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('preferred_hostel')
                    ->label('Preferred Hostel')
                    ->searchable(),
                Tables\Columns\TextColumn::make('room_type')
                    ->label('Room Type'),
                Tables\Columns\TextColumn::make('academic_year')
                    ->label('Academic Year'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                        'danger' => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime()
                    ->sortable(),
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
                    ->visible(fn(RoomApplication $record) => $record->status === 'pending'),
                Tables\Actions\ViewAction::make()
                    ->visible(fn(RoomApplication $record) => $record->status !== 'pending'),
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
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRoomApplications::route('/'),
            'edit' => Pages\EditRoomApplication::route('/{record}/edit'),
            'view' => Pages\ViewRoomApplication::route('/{record}'),
        ];
    }
}
