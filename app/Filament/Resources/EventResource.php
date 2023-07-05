<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\DB;
use Domain\TalksAtConfs\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Domain\TalksAtConfs\Models\Conference;
use App\Filament\Resources\EventResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EventResource\RelationManagers;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected static ?string $navigationGroup = 'talksatconfs.com';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('conference.name'),
                Tables\Columns\TextColumn::make('location'),
                Tables\Columns\TextColumn::make('venue'),
                Tables\Columns\TextColumn::make('city'),
                Tables\Columns\TextColumn::make('country'),
                Tables\Columns\TextColumn::make('from_date')->date(),
                Tables\Columns\TextColumn::make('to_date')->date(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('conference_id')
                    ->relationship('conference', 'name'),

                Tables\Filters\SelectFilter::make('event_year')
                    ->form([
                        Forms\Components\Select::make('event_year')
                            ->options(
                                Event::select([DB::raw('DISTINCT(YEAR(`from_date`)) AS event_year')])
                                    ->whereNotNull(DB::raw('YEAR(`from_date`)'))
                                    ->orderBy(DB::raw('YEAR(`from_date`)'))
                                    ->get()
                                    ->mapWithKeys(fn ($row) => [$row->getAttribute('event_year') => $row->getAttribute('event_year')])
                                    ->toArray()
                            ),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['event_year'],
                                fn (Builder $query, $date): Builder => $query->where(DB::raw('YEAR(`from_date`)'), $data['event_year']),
                            );
                    }),

                Tables\Filters\TernaryFilter::make('city')
                    ->nullable()
                    ->attribute('city'),
                Tables\Filters\TernaryFilter::make('country')
                    ->nullable()
                    ->attribute('country'),
                Tables\Filters\TernaryFilter::make('venue')
                    ->nullable()
                    ->attribute('venue'),


                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\TalksRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
