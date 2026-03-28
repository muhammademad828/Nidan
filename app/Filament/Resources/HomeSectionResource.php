<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeSectionResource\Pages;
use App\Models\Section;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class HomeSectionResource extends Resource
{
    protected static ?string $model = Section::class;

    protected static ?string $navigationIcon = 'heroicon-o-squares-2x2';

    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? 'المحتوى' : 'CMS';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'ترتيب الصفحة الرئيسية' : 'Home page blocks';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'كتلة' : 'Block';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'كتل الصفحة الرئيسية' : 'Home page blocks';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('page')
                ->options(['home' => 'Home'])
                ->default('home')
                ->required(),
            Forms\Components\TextInput::make('name')->required()->maxLength(120),
            Forms\Components\Select::make('component_name')
                ->required()
                ->options([
                    'hero' => 'Hero',
                    'services' => 'Services',
                    'best_sellers' => 'Best Sellers',
                    'wallets' => 'Wallets',
                    'jewelry' => 'Jewelry',
                    'partners' => 'Partners',
                ])
                ->disabledOn('edit'),
            Forms\Components\Toggle::make('is_visible')->default(true),
            Forms\Components\TextInput::make('order')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page')->badge(),
                Tables\Columns\TextColumn::make('order')->sortable(),
                Tables\Columns\TextColumn::make('name')->searchable(),
                Tables\Columns\TextColumn::make('component_name')->badge(),
                Tables\Columns\IconColumn::make('is_visible')->boolean(),
            ])
            ->defaultSort('order')
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomeSections::route('/'),
            'create' => Pages\CreateHomeSection::route('/create'),
            'edit' => Pages\EditHomeSection::route('/{record}/edit'),
        ];
    }
}
