<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return app()->getLocale() === 'ar' ? 'الطلبات' : 'Orders';
    }

    public static function getNavigationLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'الطلبات' : 'Orders';
    }

    public static function getModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'طلب' : 'Order';
    }

    public static function getPluralModelLabel(): string
    {
        return app()->getLocale() === 'ar' ? 'الطلبات' : 'Orders';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Order Info')->schema([
                Forms\Components\TextInput::make('order_number')->disabled(),
                Forms\Components\Select::make('status')
                    ->options(['pending' => 'Pending', 'paid' => 'Paid', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled'])
                    ->required(),
                Forms\Components\TextInput::make('contact_person')->label('Contact'),
                Forms\Components\TextInput::make('contact_phone')->label('Phone'),
            ])->columns(2),
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Order Details')->schema([
                Infolists\Components\TextEntry::make('order_number')->label('Order #'),
                Infolists\Components\TextEntry::make('status')->badge()
                    ->color(fn ($state) => match ($state) {
                        'pending'   => 'warning',
                        'paid'      => 'success',
                        'delivered' => 'info',
                        'cancelled' => 'danger',
                        default     => 'gray',
                    }),
                Infolists\Components\TextEntry::make('contact_person')->label('Contact'),
                Infolists\Components\TextEntry::make('contact_phone')->label('Phone'),
                Infolists\Components\TextEntry::make('region.name')->label('Region'),
                Infolists\Components\TextEntry::make('created_at')->label('Placed At')->dateTime(),
            ])->columns(3),

            Infolists\Components\Section::make('Financials')->schema([
                Infolists\Components\TextEntry::make('subtotal')->money('EGP'),
                Infolists\Components\TextEntry::make('delivery_fee')->label('Delivery Fee')->money('EGP'),
                Infolists\Components\TextEntry::make('tax_amount')->label('VAT')->money('EGP'),
                Infolists\Components\TextEntry::make('total')->money('EGP')->weight('bold'),
            ])->columns(4),

            Infolists\Components\Section::make('Delivery')->schema([
                Infolists\Components\TextEntry::make('deliveryDetail.address')->label('Address'),
                Infolists\Components\TextEntry::make('deliveryDetail.scheduled_date')->label('Scheduled Date')->date(),
            ])->columns(2),

            Infolists\Components\Section::make('Gift Details')
                ->visible(fn ($record) => $record->is_gift)
                ->schema([
                    Infolists\Components\TextEntry::make('giftDetail.recipient_name')->label('Recipient'),
                    Infolists\Components\TextEntry::make('giftDetail.recipient_phone')->label('Phone'),
                    Infolists\Components\TextEntry::make('giftDetail.gift_message')->label('Message'),
                    Infolists\Components\IconEntry::make('is_surprise')->label('Surprise')->boolean(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order_number')->label('Order #')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('contact_person')->label('Customer')->searchable(),
                Tables\Columns\TextColumn::make('contact_phone')->label('Phone'),
                Tables\Columns\TextColumn::make('region.name')->label('Region'),
                Tables\Columns\TextColumn::make('total')->money('EGP')->sortable(),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors(['warning' => 'pending', 'success' => 'paid', 'info' => 'delivered', 'danger' => 'cancelled']),
                Tables\Columns\IconColumn::make('is_gift')->label('Gift')->boolean(),
                Tables\Columns\TextColumn::make('created_at')->label('Date')->date()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(['pending' => 'Pending', 'paid' => 'Paid', 'delivered' => 'Delivered', 'cancelled' => 'Cancelled']),
                Tables\Filters\TernaryFilter::make('is_gift')->label('Gift Orders'),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('markPaid')
                    ->label('Mark Paid')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Order $record) => $record->status === 'pending')
                    ->action(fn (Order $record) => $record->update(['status' => 'paid'])),
                Tables\Actions\Action::make('markDelivered')
                    ->label('Delivered')
                    ->icon('heroicon-o-truck')
                    ->color('info')
                    ->visible(fn (Order $record) => $record->status === 'paid')
                    ->action(fn (Order $record) => $record->update(['status' => 'delivered'])),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOrders::route('/'),
            'view'   => Pages\ViewOrder::route('/{record}'),
            'edit'   => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
