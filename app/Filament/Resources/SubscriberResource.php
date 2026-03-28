<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SubscriberResource\Pages;
use App\Models\Subscriber;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubscriberResource extends Resource
{
    protected static ?string $model = Subscriber::class;
    protected static ?string $navigationIcon = 'heroicon-o-envelope';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? 'التسويق' : 'Marketing';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'المشتركين' : 'Newsletter Subscribers';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'مشترك' : 'Subscriber';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'المشتركين' : 'Subscribers';
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\BadgeColumn::make('source')
                    ->colors(['primary' => 'footer', 'success' => 'popup', 'warning' => 'checkout']),
                Tables\Columns\TextColumn::make('locale')->badge()->sortable(),
                Tables\Columns\ToggleColumn::make('is_active')->label('Active'),
                Tables\Columns\TextColumn::make('created_at')->label('Joined')->date()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('source')
                    ->options(['footer' => 'Footer', 'popup' => 'Popup', 'checkout' => 'Checkout']),
                Tables\Filters\TernaryFilter::make('is_active')->label('Active'),
            ])
            ->actions([Tables\Actions\DeleteAction::make()])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\Action::make('export')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $subscribers = Subscriber::where('is_active', true)->get(['email', 'locale', 'created_at']);
                        $csv = "email,locale,joined\n";
                        foreach ($subscribers as $s) {
                            $csv .= "{$s->email},{$s->locale},{$s->created_at->format('Y-m-d')}\n";
                        }
                        return response()->streamDownload(fn () => print($csv), 'subscribers.csv');
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubscribers::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
