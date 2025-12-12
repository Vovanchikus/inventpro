<?php namespace Samvol\Inventory\Components;

use Cms\Classes\ComponentBase;
use Samvol\Inventory\Models\Product;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;

class QrProductCode extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'QR Product Code',
            'description' => 'Генерирует QR-код на основе инвентарного номера'
        ];
    }

    public function defineProperties()
    {
        return [
            'slug' => [
                'title' => 'Инвентарный номер (slug)',
                'type'  => 'string',
                'default' => '{{ :slug }}'
            ]
        ];
    }

    public function onRun()
    {
        $slug = $this->property('slug'); // здесь inv_number из URL

        // Ищем товар по inv_number
        $product = Product::where('inv_number', $slug)->first();

        if (!$product) {
            $this->page['qrCode'] = null;
            return;
        }

        // Генерируем QR-код с тем же inv_number
        $qr = QrCode::create($product->inv_number)
            ->setSize(300)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qr);

        $this->page['qrCode'] = $result->getDataUri();
        $this->page['product'] = $product;
    }
}
