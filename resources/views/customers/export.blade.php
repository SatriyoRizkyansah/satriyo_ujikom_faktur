<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Customer</title>
    <style>
        * {
            font-family: "Instrument Sans", Arial, sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 24px 32px;
            font-size: 12px;
            color: #0f172a;
        }

        header {
            text-align: center;
            margin-bottom: 24px;
        }

        h1 {
            margin: 0;
            font-size: 20px;
        }

        p {
            margin: 4px 0;
            color: #475569;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        th,
        td {
            border: 1px solid #cbd5f5;
            padding: 8px;
            text-align: left;
        }

        th {
            background: #2563eb;
            color: #fff;
            font-weight: 600;
            font-size: 12px;
        }

        tr:nth-child(even) td {
            background: #f1f5f9;
        }
    </style>
</head>
<body>
    <header>
        <h1>Daftar Data Customer</h1>
        <p>Dicetak pada: {{ $exportedAt }}</p>
    </header>

    <table>
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Customer</th>
                <th>Perusahaan</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($customers as $index => $customer)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $customer->nama_customer }}</td>
                    <td>{{ $customer->perusahaan_cust ?? '-' }}</td>
                    <td>{{ $customer->alamat }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Belum ada data customer.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
