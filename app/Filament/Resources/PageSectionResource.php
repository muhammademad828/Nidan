<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PageSectionResource\Pages;
use App\Models\PageSection;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PageSectionResource extends Resource
{
    protected static ?string $model = PageSection::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? 'المحتوى' : 'CMS';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'الأقسام' : 'Sections';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'قسم' : 'Section';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'الأقسام' : 'Sections';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Location')->schema([
                Forms\Components\Select::make('page')
                    ->options([
                        'home'     => 'Home / الرئيسية',
                        'products' => 'Products / المنتجات',
                        'global'   => 'Global / عام',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('section_key')
                    ->label('Section Key (e.g. hero, featured, craft)')
                    ->required()->maxLength(60),
                Forms\Components\TextInput::make('display_order')->numeric()->default(0),
                Forms\Components\Toggle::make('is_active')->default(true),
            ])->columns(4),

            Forms\Components\Section::make('Titles / العناوين')->schema([
                Forms\Components\TextInput::make('title_en')->label('Title (EN)'),
                Forms\Components\TextInput::make('title_ar')->label('Title (AR)')
                    ->extraInputAttributes(['dir' => 'rtl', 'lang' => 'ar']),
                Forms\Components\TextInput::make('subtitle_en')->label('Subtitle (EN)'),
                Forms\Components\TextInput::make('subtitle_ar')->label('Subtitle (AR)')
                    ->extraInputAttributes(['dir' => 'rtl', 'lang' => 'ar']),
            ])->columns(2),

            Forms\Components\Section::make('Description / الوصف')->schema([
                Forms\Components\Textarea::make('description_en')->label('Description (EN)')->rows(4),
                Forms\Components\Textarea::make('description_ar')->label('Description (AR)')->rows(4)
                    ->extraInputAttributes(['dir' => 'rtl', 'lang' => 'ar']),
            ])->columns(2),

            Forms\Components\Section::make('Button / الزر')->schema([
                Forms\Components\TextInput::make('button_text_en')->label('Button (EN)'),
                Forms\Components\TextInput::make('button_text_ar')->label('Button (AR)')
                    ->extraInputAttributes(['dir' => 'rtl', 'lang' => 'ar']),
                Forms\Components\TextInput::make('button_url')->label('Button URL')->url()->columnSpanFull(),
            ])->columns(2),

            Forms\Components\Section::make('Background Image / الصورة الخلفية')->schema([
                Forms\Components\FileUpload::make('background_image')
                    ->label('Background Image')
                    ->image()
                    ->imageEditor()
                    ->disk('public')
                    ->directory('cms/sections')
                    ->visibility('public')
                    ->maxSize(6144)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText('JPG, PNG or WebP — max 6 MB')
                    ->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('page')->badge()->sortable(),
                Tables\Columns\TextColumn::make('section_key')->label('Section')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('title_en')->label('Title EN')->limit(30),
                Tables\Columns\TextColumn::make('title_ar')->label('Title AR')->limit(30),
                Tables\Columns\ImageColumn::make('background_image')
                    ->disk('public')->label('BG')->square()->size(40),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
                Tables\Columns\TextColumn::make('display_order')->label('Order')->sortable(),
            ])
            ->defaultSort('page')
            ->filters([
                Tables\Filters\SelectFilter::make('page')
                    ->options(['home' => 'Home', 'products' => 'Products', 'global' => 'Global']),
            ])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPageSections::route('/'),
            'create' => Pages\CreatePageSection::route('/create'),
            'edit'   => Pages\EditPageSection::route('/{record}/edit'),
        ];
    }
}
