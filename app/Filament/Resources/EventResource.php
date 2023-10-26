<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers\TalksRelationManager;
use Domain\TalksAtConfs\Models\Event;
use Filament\Forms;
use Filament\Forms\Components\SpatieTagsInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;
use Squire\Models\Country;

class EventResource extends Resource
{
    protected static ?string $model = \Domain\TalksAtConfs\Models\Event::class;

    protected static ?string $navigationGroup = 'talksatconfs';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Select::make('conference_id')
                                    ->relationship('conference', 'name')
                                    ->searchable()
                                    ->required()
                                    ->columnSpan('full'),

                                Forms\Components\TextInput::make('name')
                                    ->required()
                                    ->lazy()
                                    ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->required()
                                    ->unique(Conference::class, 'slug', ignoreRecord: true),

                                Forms\Components\DatePicker::make('from_date')
                                    ->label('From Date'),
                                Forms\Components\DatePicker::make('to_date')
                                    ->label('To Date'),

                                Forms\Components\MarkdownEditor::make('description')
                                    ->required()
                                    ->columnSpan('full'),

                                Forms\Components\TextInput::make('link')
                                    ->columnSpan('full'),

                                SpatieTagsInput::make('tags'),
                            ])
                            ->columns(2),

                        Forms\Components\Card::make('Location')
                            ->schema([
                                Forms\Components\TextInput::make('location'),
                                Forms\Components\TextInput::make('venue'),
                                Forms\Components\TextInput::make('city'),
                                Forms\Components\Select::make('country')
                                    ->searchable()
                                    ->getSearchResultsUsing(fn (string $query) => Country::where('name', 'like', "%{$query}%")->pluck('name', 'id'))
                                    ->getOptionLabelUsing(fn ($value): ?string => Country::find($value)?->getAttribute('name')),
                            ])
                            ->columns(2)
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => fn (?Event $record) => $record === null ? 3 : 2]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Event $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Event $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Event $record) => $record === null),
            ])
            ->columns([
                'sm' => 1,
                'lg' => 3,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable()
                    ->description(fn (\Domain\TalksAtConfs\Models\Event $record): string => 'Conference: ' . $record->conference->name),

                TextColumn::make('conference.name'),

                TextColumn::make('from_date')
                    ->dateTime('d M, Y')
                    ->sortable(),
                TextColumn::make('to_date')
                    ->dateTime('d M, Y')
                    ->sortable(),

                TextColumn::make('talks_count')
                    ->counts('talks')
                    ->sortable(),

                TextColumn::make('location'),
                TextColumn::make('venue'),
                TextColumn::make('city'),
                TextColumn::make('country'),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            TalksRelationManager::class,
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
