<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RegionResource\Pages;
use App\Models\Region;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class RegionResource extends Resource
{
    protected static ?string $model = Region::class;
    protected static ?string $navigationIcon = 'heroicon-o-map-pin';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? 'الإعدادات' : 'Settings';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'المناطق' : 'Regions';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'منطقة' : 'Region';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'المناطق' : 'Regions';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name_en')->label('Name (EN)')->required()->maxLength(100),
            Forms\Components\TextInput::make('name_ar')->label('Name (AR)')->required()->maxLength(100),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('delivery_fee')->label('Delivery Fee (EGP)')->numeric()->required()->prefix('EGP'),
            Forms\Components\TextInput::make('min_order_amount')->label('Min Order (EGP)')->numeric()->prefix('EGP'),
            Forms\Components\Toggle::make('is_active')->label('Active')->default(true),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name_en')->label('Name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('name_ar')->label('Name (AR)'),
                Tables\Columns\TextColumn::make('delivery_fee')->label('Delivery Fee')->money('EGP'),
                Tables\Columns\TextColumn::make('min_order_amount')->label('Min Order')->money('EGP'),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
            ])
            ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListRegions::route('/'),
            'create' => Pages\CreateRegion::route('/create'),
            'edit'   => Pages\EditRegion::route('/{record}/edit'),
        ];
    }
}
