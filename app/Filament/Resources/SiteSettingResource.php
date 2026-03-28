<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SiteSettingResource\Pages;
use App\Models\SiteSetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SiteSettingResource extends Resource
{
    protected static ?string $model = SiteSetting::class;
    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? 'الإعدادات' : 'Settings';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'إعدادات الموقع' : 'Site Settings';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'إعداد' : 'Setting';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'الإعدادات' : 'Settings';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('group')
                ->options([
                    'general' => 'General',
                    'home' => 'Home (landing copy)',
                    'contact' => 'Contact',
                    'social' => 'Social',
                    'delivery' => 'Delivery',
                    'payment' => 'Payment',
                    'branding' => 'Branding',
                ])
                ->required()->searchable(),
            Forms\Components\TextInput::make('key')->required()->maxLength(60),
            Forms\Components\Select::make('type')
                ->options(['text' => 'Text', 'textarea' => 'Textarea', 'image' => 'Image', 'boolean' => 'Boolean', 'json' => 'JSON'])
                ->default('text')
                ->reactive(),

            // Text / Textarea value
            Forms\Components\Textarea::make('value')
                ->label('Value')
                ->rows(3)
                ->columnSpanFull()
                ->hidden(fn ($get) => $get('type') === 'image'),

            // Image upload
            Forms\Components\FileUpload::make('value')
                ->label('Image')
                ->image()
                ->imageEditor()
                ->disk('public')
                ->directory('settings')
                ->visibility('public')
                ->maxSize(5120)
                ->columnSpanFull()
                ->hidden(fn ($get) => $get('type') !== 'image'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group')->sortable()->badge(),
                Tables\Columns\TextColumn::make('key')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('value')->limit(50)->wrap(),
                Tables\Columns\TextColumn::make('type')->badge(),
            ])
            ->defaultSort('group')
            ->filters([Tables\Filters\SelectFilter::make('group')->options(fn () => SiteSetting::distinct()->pluck('group', 'group'))])
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiteSettings::route('/'),
            'edit'  => Pages\EditSiteSetting::route('/{record}/edit'),
        ];
    }
}
