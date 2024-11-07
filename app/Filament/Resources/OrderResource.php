<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DateTimePicker;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // public static function can(string $action, ?Model $record = null): bool
    // {
    //     $user = auth()->user();
    
    //     if ($user->isAdmin()) {
    //         return true; // Admin może wykonywać wszystkie akcje.
    //     }
    
    //     if ($user->isManager()) {
    //         // Manager może tylko przeglądać swoje zamówienia i edytować wybrane pola.
    //         if (in_array($action, ['viewAny', 'view']) || ($action === 'edit' && $record && $record->manager_id === $user->id)) {
    //             return true;
    //         }
    //     }
    
    //     return false; // Inni użytkownicy nie mają dostępu.
    // }

    public static function form(Form $form): Form
    {
        $user = auth()->user();

        return $form
        ->schema([
            TextInput::make('pickup_address')->required(),
            TextInput::make('delivery_address')->required(),
            Select::make('status')
                ->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In Progress',
                    'completed' => 'Completed',
                    'canceled' => 'Canceled',
                ])
                ->required(),
            // Pola edytowalne tylko przez administratora:
            TextInput::make('pickup_latitude')
                ->visible(fn () => $user->hasRole('admin')),
            TextInput::make('pickup_longitude')
                ->visible(fn () => $user->hasRole('admin')),
            TextInput::make('current_address')
                ->visible(fn () => $user->hasRole('admin')),
            TextInput::make('current_latitude')
                ->visible(fn () => $user->hasRole('admin')),
            TextInput::make('current_longitude')
                ->visible(fn () => $user->hasRole('admin')),
            DateTimePicker::make('pickup_at')
                ->visible(fn () => $user->hasRole('admin')),
            DateTimePicker::make('delivered_at')
                ->visible(fn () => $user->hasRole('admin')),
        ]);
    }

    public static function table(Table $table): Table
    {
        $user = auth()->user();

        return $table
        ->modifyQueryUsing(function (Builder $query) use ($user) {
            if ($user && $user->isManager()) {
                $query->where('manager_id', $user->id);
            }
        })
            ->columns([
                TextColumn::make('pickup_address'),
                TextColumn::make('delivery_address'),
                TextColumn::make('status')->sortable(),
                TextColumn::make('created_at')->label('Created'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    


}
