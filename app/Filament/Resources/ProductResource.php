<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? 'المتجر' : 'Catalog';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'المنتجات' : 'Products';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'منتج' : 'Product';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'المنتجات' : 'Products';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Basic Info')->schema([
                Forms\Components\TextInput::make('name_en')->label('Name (EN)')->required()->maxLength(255)->columnSpan(1),
                Forms\Components\TextInput::make('name_ar')->label('Name (AR)')->required()->maxLength(255)->columnSpan(1),
                Forms\Components\TextInput::make('slug')->label('Slug')->required()->maxLength(255)->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('sku')->label('SKU')->required()->maxLength(100)->unique(ignoreRecord: true),
                Forms\Components\Select::make('category_id')->label('Category')
                    ->options(fn () => Category::active()->pluck('name_en', 'id'))
                    ->searchable()->required(),
            ])->columns(2),

            Forms\Components\Section::make('Description')->schema([
                Forms\Components\Textarea::make('short_description_en')->label('Short Desc (EN)')->rows(2)->maxLength(300),
                Forms\Components\Textarea::make('short_description_ar')->label('Short Desc (AR)')->rows(2)->maxLength(300),
                Forms\Components\Textarea::make('description_en')->label('Description (EN)')->rows(5),
                Forms\Components\Textarea::make('description_ar')->label('Description (AR)')->rows(5),
            ])->columns(2),

            Forms\Components\Section::make('Pricing & Stock')->schema([
                Forms\Components\TextInput::make('base_price')->label('Base Price (EGP)')->numeric()->required()->prefix('EGP'),
                Forms\Components\TextInput::make('compare_at_price')->label('Compare At Price')->numeric()->prefix('EGP'),
                Forms\Components\Select::make('stock_status')->label('Stock Status')
                    ->options(['in_stock' => 'In Stock', 'low_stock' => 'Low Stock', 'out_of_stock' => 'Out of Stock'])
                    ->required()->default('in_stock'),
                Forms\Components\TextInput::make('stock_quantity')->label('Stock Qty')->numeric()->nullable(),
            ])->columns(2),

            Forms\Components\Section::make('Main Image')->schema([
                Forms\Components\FileUpload::make('primary_image_path')
                    ->label('Product Image')
                    ->image()
                    ->imageEditor()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('1:1')
                    ->disk('public')
                    ->directory('products')
                    ->visibility('public')
                    ->maxSize(5120)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->downloadable()
                    ->helperText('JPG, PNG or WebP — max 5 MB. Square crop recommended.')
                    ->columnSpanFull(),
            ]),

            Forms\Components\Section::make('Settings')->schema([
                Forms\Components\Toggle::make('is_active')->label('Active')->default(true),
                Forms\Components\Toggle::make('is_featured')->label('Featured'),
                Forms\Components\Toggle::make('is_giftable')->label('Giftable'),
                Forms\Components\Toggle::make('requires_delivery_slot')->label('Needs Delivery Slot'),
                Forms\Components\TextInput::make('display_order')->label('Sort Order')->numeric()->default(0),
            ])->columns(3),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('primary_image_path')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->size(48),
                Tables\Columns\TextColumn::make('sku')->label('SKU')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name_en')->label('Name')->searchable()->sortable()->limit(35),
                Tables\Columns\TextColumn::make('category.name_en')->label('Category')->sortable(),
                Tables\Columns\TextColumn::make('base_price')->label('Price')->money('EGP')->sortable(),
                Tables\Columns\BadgeColumn::make('stock_status')->label('Stock')
                    ->colors(['success' => 'in_stock', 'warning' => 'low_stock', 'danger' => 'out_of_stock']),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
                Tables\Columns\ToggleColumn::make('is_featured')->label('Featured'),
                Tables\Columns\TextColumn::make('updated_at')->label('Updated')->since()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('category_id')->label('Category')
                    ->options(fn () => Category::active()->pluck('name_en', 'id')),
                Tables\Filters\SelectFilter::make('stock_status')
                    ->options(['in_stock' => 'In Stock', 'low_stock' => 'Low Stock', 'out_of_stock' => 'Out of Stock']),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
                Tables\Filters\TernaryFilter::make('is_featured')->label('Featured'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}
