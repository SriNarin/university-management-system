<?php
namespace App\Filament\Faculty\Resources\DepartmentResource\Pages;
use App\Filament\Faculty\Resources\DepartmentResource;
use Filament\Resources\Pages\EditRecord;
class EditDepartment extends EditRecord { 
    
protected static string $resource = DepartmentResource::class; 
 protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }}