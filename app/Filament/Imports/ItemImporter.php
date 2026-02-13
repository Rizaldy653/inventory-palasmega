<?php

namespace App\Filament\Imports;

use App\Models\Item;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class ItemImporter extends Importer
{
    protected static ?string $model = Item::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('code')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('type')
                ->rules(['max:255']),
            ImportColumn::make('brand')
                ->rules(['max:255']),
            ImportColumn::make('color')
                ->rules(['max:255']),
            ImportColumn::make('load')
                ->rules(['max:255']),
            ImportColumn::make('unit'),
            ImportColumn::make('added_at')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('category_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('is_active')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
            ImportColumn::make('note')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?Item
    {
        $this->data['code'] = strtoupper(trim($this->data['code']));

        $this->data['is_active'] = filter_var(
            $this->data['is_active'],
            FILTER_VALIDATE_BOOLEAN
        );

        return Item::firstOrNew([
            'code' => $this->data['code'],
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your item import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
