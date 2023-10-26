<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TalkResource\Pages;
use Domain\TalksAtConfs\Models\Talk;
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

class TalkResource extends Resource
{
    protected static ?string $model = \Domain\TalksAtConfs\Models\Talk::class;

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
                                Forms\Components\Select::make('event_id')
                                    ->relationship('event', 'name')
                                    ->searchable()
                                    ->required(),

                                Forms\Components\DatePicker::make('talk_date')
                                    ->label('From Date'),

                                Forms\Components\TextInput::make('title')
                                    ->required()
                                    ->lazy()
                                    ->afterStateUpdated(fn (string $context, $state, callable $set) => $context === 'create' ? $set('slug', Str::slug($state)) : null)
                                    ->columnSpan('full'),

                                Forms\Components\TextInput::make('slug')
                                    ->disabled()
                                    ->required()
                                    ->unique(Talk::class, 'slug', ignoreRecord: true)
                                    ->columnSpan('full'),

                                Forms\Components\MarkdownEditor::make('description')
                                    ->required()
                                    ->columnSpan('full'),

                                SpatieTagsInput::make('tags'),
                            ])
                            ->columns(2),

                    ])
                    ->columnSpan(['lg' => fn (?Talk $record) => $record === null ? 3 : 2]),

                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Talk $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Talk $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Talk $record) => $record === null),
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
                TextColumn::make('title')
                    ->weight(FontWeight::Bold)
                    ->searchable()
                    ->sortable()
                    ->description(fn (\Domain\TalksAtConfs\Models\Talk $record): string => Str::limit($record->description, 50)),
                TextColumn::make('event.name')
                    ->description(fn (\Domain\TalksAtConfs\Models\Talk $record): string => 'Conference: ' . $record->event->conference->name),
                TextColumn::make('talk_date')
                    ->dateTime('d M, Y'),

                TextColumn::make('speakers.name')
                    ->listWithLineBreaks()
                    ->bulleted(),

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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTalks::route('/'),
            'create' => Pages\CreateTalk::route('/create'),
            'edit' => Pages\EditTalk::route('/{record}/edit'),
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
