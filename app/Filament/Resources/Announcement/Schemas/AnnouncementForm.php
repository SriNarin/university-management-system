<?php

namespace App\Filament\Resources\Announcement\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class AnnouncementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title_en')
                    ->required(),
                TextInput::make('title_kh')
                    ->required(),
                Textarea::make('body_payload')
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_pinned_to_top')
                    ->required(),
            ]);
    }
}
