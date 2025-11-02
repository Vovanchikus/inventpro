<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\OperationType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Input;
use Flash;

class ImportExcel extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Импорт Excel',
            'description' => 'Импорт остатков на склад через Excel-файл'
        ];
    }

    /**
     * Основная функция импорта
     */
    public function onImportExcel()
    {
        $file = Input::file('excel_file');
        if (!$file) {
            Flash::error('Файл не загружен.');
            return;
        }

        $path = $file->getRealPath();

        try {
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            if (empty($rows)) {
                Flash::error('Файл пустой или не удалось прочитать данные.');
                return;
            }

            // Пропускаем первую строку (заголовки)
            unset($rows[0]);

            // Создаём/получаем тип операции "Импорт"
            $importType = OperationType::firstOrCreate(['name' => 'Импорт']);

            // Создаём операцию импорта
            $operation = Operation::firstOrCreate(
                ['type_id' => $importType->id],
                ['doc_date' => now()]
            );

            $differences = []; // массив для хранения несоответствий количества

            foreach ($rows as $row) {
                if (empty($row[0])) continue;

                $name = trim($row[0]);
                $excelQuantity = (float)($row[1] ?? 0);
                $unit = $row[2] ?? 'шт';

                // Находим продукт по имени
                $product = Product::where('name', $name)->first();

                if ($product) {
                    // Продукт существует → проверяем суммарное количество через calculated_quantity
                    $currentQuantity = $product->calculated_quantity;

                    if ($currentQuantity != $excelQuantity) {
                        // Несоответствие → сохраняем для отображения
                        $differences[] = [
                            'product' => $product->name,
                            'excel_quantity' => $excelQuantity,
                            'current_quantity' => $currentQuantity
                        ];
                    }

                    // Обновляем unit продукта
                    $product->unit = $unit;
                    $product->save();
                } else {
                    // Продукта нет → создаём новый и добавляем в pivot операции с количеством
                    $product = Product::create([
                        'name' => $name,
                        'unit' => $unit
                    ]);

                    $operation->products()->attach($product->id, ['quantity' => $excelQuantity]);
                }
            }

            Flash::success('Импорт завершён. Новых продуктов добавлено: ' . count($rows) . '.');

            // Если есть несоответствия, выводим уведомление (можно сделать partial для подробностей)
            if (!empty($differences)) {
                Flash::warning('Обнаружены несоответствия количества для существующих продуктов.');
                // $this->vars['differences'] = $differences;
                // можно сделать partial для отображения различий на фронте
            }

        } catch (\Exception $e) {
            Flash::error('Ошибка при импорте: ' . $e->getMessage());
        }
    }
}
