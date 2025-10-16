<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderInfo;
use Carbon\Carbon;

class CsvImportController extends Controller
{
    public function import()
    {
        $file = storage_path('app/sales.csv');

        if (!file_exists($file)) {
            return response()->json(['error' => "CSV file not found at $file"], 404);
        }

        $rows = array_map('str_getcsv', file($file));
        $header = array_map('trim', array_shift($rows));
        // Here I struggled with the array_map/array_shift use, It was tricky to remember how to import the data from the csv
        // Had to check on Internet/phpdoc  kinda old but worked fine (https://stackoverflow.com/questions/50869961/csv-data-to-array-map-and-str-getcsv-using-delimiter)
        $imported = 0;
        $skipped = 0;

        $lineNumber = 1;
        $loggedErrors = [];


        foreach ($rows as $row) {
            $lineNumber++;
            $data = array_combine($header, $row);

            $errors = [];
            //Check if datas are fine
            if (empty($data['order_id'])) $errors[] = 'order_id missing';
            if (!Carbon::hasFormat($data['order_date'], 'Y-m-d')) $errors[] = 'invalid order_date';
            if (empty($data['customer_email']) || !filter_var($data['customer_email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'invalid customer_email';
            if (!is_numeric($data['unit_price']) || $data['unit_price'] <= 0) $errors[] = 'negative unit_price';
            if (!is_numeric($data['quantity']) || intval($data['quantity']) <= 0) $errors[] = 'invalid quantity';

            if (!empty($errors)) {
                $skipped++;
                $message = "Line $lineNumber skipped: " . implode(', ', $errors);
                Log::warning("Line $lineNumber skipped: " . implode(', ', $errors));
                $loggedErrors[] = $message;
                continue;
            }
            // create if not already for performance
            $order = Order::firstOrCreate(
                ['order_id' => $data['order_id']],  
                [
                'order_date' => $data['order_date'],
                'customer_email' => $data['customer_email']
                ]
            );

            $product = Product::firstOrCreate(
                ['sku' => $data['product_sku']],
                [
                'name' => $data['product_name'],
                'price' => $data['unit_price']
                ]
            );

            $exists = OrderInfo::where('order_id', $order->id)
                ->where('product_id', $product->id)
                ->exists();

            if ($exists) {
                $skipped++;
                continue;
            }

            OrderInfo::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'price' => $data['unit_price'],
            ]);

            $imported++;
        }

        return response()->json([
            'imported' => $imported,
            'skipped' => $skipped,
            'errors' => $loggedErrors
        ]);
    }
}
