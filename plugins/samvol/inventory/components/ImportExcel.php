<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Product;
use Samvol\Inventory\Models\Operation;
use Samvol\Inventory\Models\OperationType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Input;
use Log;

class ImportExcel extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Импорт Excel',
            'description' => 'Импорт остатков через Excel с проверкой и выводом результатов'
        ];
    }

    // -----------------------------
    // Применение выбранных различий
    // -----------------------------
    public function onApplyDifferences()
    {
        $updates = post('updates', []);
        if (empty($updates)) {
            return ['error' => 'Нет выбранных продуктов для обновления'];
        }

        $counteragent = post('counteragent', 'Не указан');

        foreach ($updates as $item) {
            $invNumber = $item['inv_number'] ?? null;
            $excelQuantity = floatval($item['quantity'] ?? 0);
            $price = floatval($item['price'] ?? 0);
            $sum   = floatval($item['sum'] ?? 0);

            if (!$invNumber) continue;

            $product = Product::where('inv_number', $invNumber)->first();
            if (!$product) continue;

            $currentQuantity = $product->calculated_quantity ?? 0;
            $delta = $excelQuantity - $currentQuantity;

            if ($delta != 0) {
                $operationTypeName = $delta > 0 ? 'Импорт приход' : 'Импорт расход';
                $operationType = OperationType::firstOrCreate(['name' => $operationTypeName]);

                $operation = new Operation();
                $operation->type_id = $operationType->id;
                $operation->save();

                $pivotSum = $delta > 0
                    ? $sum
                    : ($product->calculated_sum * abs($delta) / max($currentQuantity,1));

                $operation->products()->attach($product->id, [
                    'quantity' => abs($delta),
                    'sum' => $pivotSum,
                    'counteragent' => $counteragent
                ]);
            }

            // Обновляем цену и сумму продукта
            $product->price = $price;
            $product->sum   = $sum;
            $product->save();
        }

        return ['success' => true, 'toast' => [
            'message' => 'Изменения применены',
            'type' => 'success',
            'timeout' => 4000,
            'position' => 'top-center'
        ]];
    }

    // -----------------------------
    // Импорт Excel
    // -----------------------------
    public function onImportExcel()
    {
        function parseExcelNumber($value) {
            if (!$value) return 0;
            $clean = preg_replace('/[\s\x{00A0}\x{202F}]+/u', '', $value);
            $clean = str_replace(',', '.', $clean);
            return floatval($clean);
        }

        $file = Input::file('excel_file');
        if (!$file) return ['toast' => ['message'=>'Файл не загружен','type'=>'error']];

        // Контрагент для всей операции
        $counteragent = Input::get('counteragent', 'Не указан');

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $rows = $spreadsheet->getActiveSheet()->toArray();
            if (empty($rows)) return ['toast' => ['message'=>'Файл пустой','type'=>'error']];

            unset($rows[0]); // пропускаем заголовок
            $differences = [];
            $newProducts = [];
            $newCount = 0;

            foreach ($rows as $row) {
                if (empty($row[0])) continue;

                $name       = trim($row[0]);
                $unit       = trim($row[1] ?? 'шт');
                $inv_number = trim($row[2] ?? '');
                $price      = parseExcelNumber($row[3] ?? 0);
                $quantity   = parseExcelNumber($row[4] ?? 0);
                $sum        = parseExcelNumber($row[5] ?? 0);

                $product = Product::where('inv_number', $inv_number)->first();
                if (!$product) $product = Product::where('name',$name)->where('unit',$unit)->first();

                if (!$product) {
                    $product = Product::create([
                        'name'=>$name,
                        'unit'=>$unit,
                        'inv_number'=>$inv_number,
                        'price'=>$price,
                    ]);
                    $newProducts[] = ['product'=>$product, 'quantity'=>$quantity, 'sum'=>$sum];
                    $newCount++;
                    continue;
                }

                $currentQuantity = $product->calculated_quantity ?? 0;
                $currentSum = $product->calculated_sum ?? 0;

                if ($currentQuantity != $quantity || $product->price != $price || $currentSum != $sum) {
                    $differences[] = [
                        'id'=>$product->id,
                        'name'=>$product->name,
                        'inv_number'=>$product->inv_number,
                        'current_quantity'=>$currentQuantity,
                        'excel_quantity'=>$quantity,
                        'price'=>$price,
                        'current_sum'=>$currentSum,
                        'unit'=>$unit
                    ];
                }

                $product->price = $price;
                $product->save();
            }

            // Создаём операцию для новых продуктов
            if (!empty($newProducts)) {
                $importType = OperationType::firstOrCreate(['name'=>'Импорт']);
                $operation = new Operation();
                $operation->type_id = $importType->id;
                $operation->save();

                foreach ($newProducts as $item) {
                    $operation->products()->attach($item['product']->id, [
                        'quantity'=>$item['quantity'],
                        'sum'=>$item['sum'],
                        'counteragent'=>$counteragent
                    ]);
                }
            }

            if (!empty($differences)) {
                $html = $this->renderPartial('modals/modal_import_result', [
                    'differences'=>$differences,
                    'newCount'=>$newCount
                ]);

                return [
                    'modalContent'=>$html,
                    'modalType'=>'warning',
                    'modalTitle'=>'Есть различия с текущими данными:'
                ];
            }

            return ['toast'=>['message'=>'Импорт завершен','type'=>'success']];

        } catch (\Exception $e) {
            return ['toast'=>['message'=>'Ошибка: '.$e->getMessage(),'type'=>'error']];
        }
    }
}
