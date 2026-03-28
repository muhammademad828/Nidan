<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? 'المتجر' : 'Catalog';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'الفئات' : 'Categories';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'فئة' : 'Category';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'الفئات' : 'Categories';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name_en')->label('Name (EN)')->required()->maxLength(255),
            Forms\Components\TextInput::make('name_ar')->label('Name (AR)')->required()->maxLength(255),
            Forms\Components\TextInput::make('slug')->label('Slug')->required()->unique(ignoreRecord: true),
            Forms\Components\Select::make('parent_id')
                ->label('Parent Category')
                ->options(fn (?Category $record) => Category::query()
                    ->when($record, fn ($q) => $q->where('id', '!=', $record->id))
                    ->pluck('name_en', 'id'))
                ->searchable()
                ->nullable()
                ->placeholder('None (Top-level)'),
            Forms\Components\Textarea::make('description_en')->label('Description (EN)')->rows(3),
            Forms\Components\Textarea::make('description_ar')->label('Description (AR)')->rows(3),
            Forms\Components\FileUpload::make('image_path')
                ->label('Category Image')
                ->image()
                ->imageEditor()
                ->disk('public')
                ->directory('categories')
                ->visibility('public')
                ->maxSize(3072)
                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                ->columnSpanFull(),
            Forms\Components\TextInput::make('display_order')->label('Sort Order')->numeric()->default(0),
            Forms\Components\Toggle::make('is_active')->label('Active')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Image')->disk('public')->square()->size(48),
                Tables\Columns\TextColumn::make('name_en')->label('Name (EN)')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name_ar')->label('Name (AR)')->searchable(),
                Tables\Columns\TextColumn::make('slug')->label('Slug'),
                Tables\Columns\TextColumn::make('products_count')->label('Products')->counts('products')->sortable(),
                Tables\Columns\TextColumn::make('display_order')->label('Order')->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->defaultSort('display_order')
            ->reorderable('display_order')
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit'   => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
