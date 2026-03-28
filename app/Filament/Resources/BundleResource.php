<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BundleResource\Pages;
use App\Models\Bundle;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BundleResource extends Resource
{
    protected static ?string $model = Bundle::class;
    protected static ?string $navigationIcon = 'heroicon-o-gift';
    protected static ?int $navigationSort = 3;

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? 'المتجر' : 'Catalog';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'الباقات' : 'Bundles';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'باقة' : 'Bundle';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'الباقات' : 'Bundles';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name_en')->label('Name (EN)')->required(),
            Forms\Components\TextInput::make('name_ar')->label('Name (AR)')->required(),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\Textarea::make('description_en')->label('Description (EN)')->rows(3),
            Forms\Components\Textarea::make('description_ar')->label('Description (AR)')->rows(3),
            Forms\Components\TextInput::make('bundle_price')->label('Bundle Price (EGP)')->numeric()->required()->prefix('EGP'),
            Forms\Components\TextInput::make('compare_at_price')->label('Compare At')->numeric()->prefix('EGP'),
            Forms\Components\Toggle::make('is_active')->label('Active')->default(true),
            Forms\Components\Toggle::make('is_featured')->label('Featured')->default(false),

            Forms\Components\Section::make('Bundle Image')->schema([
                Forms\Components\FileUpload::make('image_path')
                    ->label('Bundle Image')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('bundles')
                    ->visibility('public')
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('Bundle Items')->schema([
                Forms\Components\Repeater::make('items')
                    ->relationship()
                    ->schema([
                        Forms\Components\Select::make('product_id')->label('Product')
                            ->options(fn () => Product::active()->pluck('name_en', 'id'))
                            ->searchable()->required(),
                        Forms\Components\TextInput::make('quantity')->numeric()->default(1)->required(),
                    ])
                    ->columns(2)
                    ->createItemButtonLabel('Add Product'),
            ]),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_en')->label('Name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('bundle_price')->label('Price')->money('EGP')->sortable(),
                Tables\Columns\TextColumn::make('compare_at_price')->label('Compare')->money('EGP'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBundles::route('/'),
            'create' => Pages\CreateBundle::route('/create'),
            'edit'   => Pages\EditBundle::route('/{record}/edit'),
        ];
    }
}
