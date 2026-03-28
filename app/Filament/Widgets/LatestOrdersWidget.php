<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrdersWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    public function getTableHeading(): string
    {
        return app()->getLocale() === 'ar' ? 'أحدث الطلبات' : 'Latest Orders';
    }

    public function table(Table $table): Table
    {
        $isAr = app()->getLocale() === 'ar';

        return $table
            ->query(Order::query()->latest()->limit(10))
            ->columns([
                Tables\Columns\TextColumn::make('order_number')
                    ->label($isAr ? 'رقم الطلب' : 'Order #'),
                Tables\Columns\TextColumn::make('contact_person')
                    ->label($isAr ? 'العميل' : 'Customer'),
                Tables\Columns\TextColumn::make('total')->money('EGP'),
                Tables\Columns\BadgeColumn::make('status')
                    ->colors(['warning' => 'pending', 'success' => 'paid', 'info' => 'delivered', 'danger' => 'cancelled']),
                Tables\Columns\IconColumn::make('is_gift')
                    ->label($isAr ? 'هدية' : 'Gift')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label($isAr ? 'التاريخ' : 'Date')
                    ->since(),
            ]);
    }
}
