<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index()
    {
        return view('invoice', [
            'invoiceItems' => [],
            'totalAmount' => 0,
        ]);
    }

    public function calculate(Request $request)
    {
        $productsInput = $request->input('products', []);

        $invoiceItems = [];
        $totalAmount = 0;

        foreach ($productsInput as $product) {
            $name = trim($product['name'] ?? '');
            $quantity = $product['quantity'] ?? '';
            $price = $product['price'] ?? '';

            $isEmptyProduct = $name === '' && $quantity === '' && $price === '';

            if ($isEmptyProduct) {
                continue;
            }

            if ($name === '' || !is_numeric($quantity) || !is_numeric($price) || $quantity <= 0 || $price < 0) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'products' => 'Please fill product name, valid quantity, and valid price for each entered product.',
                    ]);
            }

            $quantity = (int) $quantity;
            $price = (float) $price;
            $subtotal = $quantity * $price;

            $invoiceItems[] = [
                'name' => $name,
                'quantity' => $quantity,
                'price' => $price,
                'subtotal' => $subtotal,
            ];

            $totalAmount += $subtotal;
        }

        if (count($invoiceItems) === 0) {
            return back()
                ->withInput()
                ->withErrors([
                    'products' => 'Please enter at least one product.',
                ]);
        }

        return view('invoice', [
            'invoiceItems' => $invoiceItems,
            'totalAmount' => $totalAmount,
        ]);
    }
}
