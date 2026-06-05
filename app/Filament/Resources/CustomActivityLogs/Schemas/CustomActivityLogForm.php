<?php

namespace App\Filament\Resources\CustomActivityLogs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomActivityLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('actor_role_context')
                    ->required(),
                TextInput::make('action_performed')
                    ->required(),
                TextInput::make('target_resource_type')
                    ->required(),
                Textarea::make('logged_payload_summary')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
