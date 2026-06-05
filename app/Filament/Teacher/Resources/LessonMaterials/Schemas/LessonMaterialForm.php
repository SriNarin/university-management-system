<?php

namespace App\Filament\Teacher\Resources\LessonMaterials\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class LessonMaterialForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('class_schedule_id')
                    ->required()
                    ->numeric(),
                TextInput::make('lecture_title_topic')
                    ->required(),
                TextInput::make('resource_attachment_path')
                    ->required(),
                Toggle::make('is_visible_to_students')
                    ->required(),
            ]);
    }
}
