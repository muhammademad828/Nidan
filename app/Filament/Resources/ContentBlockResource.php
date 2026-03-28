<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContentBlockResource\Pages;
use App\Models\ContentBlock;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ContentBlockResource extends Resource
{
    protected static ?string $model = ContentBlock::class;
    protected static ?string $navigationIcon = 'heroicon-o-pencil-square';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? 'المحتوى' : 'CMS';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'المحتوى النصي' : 'Text Blocks';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'كتلة محتوى' : 'Content Block';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'المحتوى النصي' : 'Content Blocks';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('page')
                ->options([
                    'home'     => 'Home / الرئيسية',
                    'products' => 'Products / المنتجات',
                    'checkout' => 'Checkout / الدفع',
                    'global'   => 'Global / عام',
                ])
                ->required(),
            Forms\Components\TextInput::make('key')
                ->label('Key (e.g. hero.title, cart.empty)')
                ->required()->maxLength(120),
            Forms\Components\TextInput::make('label')
                ->label('Display Label')
                ->maxLength(120),
            Forms\Components\Select::make('type')
                ->options(['text' => 'Text', 'textarea' => 'Textarea', 'image' => 'Image'])
                ->default('text')
                ->reactive(),
            Forms\Components\Toggle::make('is_rich_text')->label('Rich Text (HTML)'),

            Forms\Components\Textarea::make('value_en')
                ->label('Value (English)')
                ->rows(3)
                ->columnSpanFull()
                ->hidden(fn ($get) => $get('type') === 'image'),
            Forms\Components\Textarea::make('value_ar')
                ->label('Value (Arabic / العربية)')
                ->rows(3)
                ->columnSpanFull()
                ->extraInputAttributes(['dir' => 'rtl', 'lang' => 'ar'])
                ->hidden(fn ($get) => $get('type') === 'image'),

            Forms\Components\FileUpload::make('value_en')
                ->label('Image (EN/Shared)')
                ->image()
                ->disk('public')
                ->directory('cms/content')
                ->visibility('public')
                ->maxSize(4096)
                ->columnSpanFull()
                ->hidden(fn ($get) => $get('type') !== 'image'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page')->badge()->sortable(),
                Tables\Columns\TextColumn::make('key')->searchable()->sortable()->fontFamily('mono'),
                Tables\Columns\TextColumn::make('label')->label('Label')->limit(25),
                Tables\Columns\TextColumn::make('value_en')->label('Value EN')->limit(40)->wrap(),
                Tables\Columns\TextColumn::make('value_ar')->label('Value AR')->limit(40)->wrap(),
                Tables\Columns\BadgeColumn::make('type'),
            ])
            ->defaultSort('page')
            ->filters([
                Tables\Filters\SelectFilter::make('page')
                    ->options(['home' => 'Home', 'products' => 'Products', 'checkout' => 'Checkout', 'global' => 'Global']),
                Tables\Filters\SelectFilter::make('type')
                    ->options(['text' => 'Text', 'textarea' => 'Textarea', 'image' => 'Image']),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListContentBlocks::route('/'),
            'create' => Pages\CreateContentBlock::route('/create'),
            'edit'   => Pages\EditContentBlock::route('/{record}/edit'),
        ];
    }
}
