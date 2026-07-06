<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice Generator</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f8;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 14px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        }

        h1 {
            text-align: center;
            color: #222;
            margin-bottom: 10px;
        }

        .note {
            text-align: center;
            color: #555;
            margin-bottom: 25px;
        }

        .product-section {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            background: #fafafa;
        }

        .product-section h2 {
            margin-top: 0;
            color: #333;
            font-size: 20px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: bold;
            color: #444;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 14px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 17px;
            cursor: pointer;
            font-weight: bold;
        }

        button:hover {
            background: #1d4ed8;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        th {
            background: #111827;
            color: white;
            padding: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }

        .total-row td {
            font-weight: bold;
            background: #f3f4f6;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Invoice Generator</h1>

    <p class="note">
        Enter at least one product. You can leave the other product sections empty.
    </p>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('invoice.calculate') }}">
        @csrf

        @for ($i = 0; $i < 3; $i++)
            <div class="product-section">
                <h2>Product {{ $i + 1 }}</h2>

                <label>Product Name</label>
                <input
                    type="text"
                    name="products[{{ $i }}][name]"
                    value="{{ old('products.' . $i . '.name') }}"
                    placeholder="Enter product name"
                >

                <label>Quantity</label>
                <input
                    type="number"
                    name="products[{{ $i }}][quantity]"
                    value="{{ old('products.' . $i . '.quantity') }}"
                    placeholder="Enter quantity"
                    min="1"
                >

                <label>Price</label>
                <input
                    type="number"
                    name="products[{{ $i }}][price]"
                    value="{{ old('products.' . $i . '.price') }}"
                    placeholder="Enter price"
                    min="0"
                    step="0.01"
                >
            </div>
        @endfor

        <button type="submit">Calculate</button>
    </form>

    @if (count($invoiceItems) > 0)
        <table>
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($invoiceItems as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ number_format($item['price'], 2) }}</td>
                        <td>{{ number_format($item['subtotal'], 2) }}</td>
                    </tr>
                @endforeach

                <tr class="total-row">
                    <td colspan="3">Total Amount</td>
                    <td>{{ number_format($totalAmount, 2) }}</td>
                </tr>
            </tbody>
        </table>
    @endif
</div>

</body>
</html>
