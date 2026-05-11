<?php

namespace App\Filament\Resources\Conversations\RelationManagers;

use App\Filament\Resources\Conversations\ConversationResource;
use Filament\Actions\CreateAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;


class MessagesRelationManager extends RelationManager
{
    protected static string $relationship = 'messages';
    
    protected static ?string $relatedResource = ConversationResource::class;

    

    // public function table(Table $table): Table
    // {
    //     return $table
    //         ->headerActions([
    //             CreateAction::make(),
    //         ]);
    // }

    public function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make(
                    'sender.name'
                )
                ->label('Sender')
                ->searchable(),

                Tables\Columns\TextColumn::make(
                    'message'
                )
                ->limit(80)
                ->wrap(),

                Tables\Columns\TextColumn::make(
                    'created_at'
                )
                ->since(),
            ])

            ->defaultSort(
                'created_at',
                'desc'
            )

            ->headerActions([
                //
            ])

            ->actions([

                ViewAction::make(),

            ])

            ->bulkActions([
                //
            ]);
    }
}
