<?php

namespace App\Http\Controllers;

use App\Models\Faktur;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class LaporanController extends Controller
{
    public function produk(Request $request): View
    {
        [$start, $end, $filters, $allTime] = $this->parseDateFilters($request);

        $jenis = $request->input('jenis');

        $produk = Produk::query()
            ->when($jenis, fn ($query) => $query->where('jenis', $jenis))
            ->with(['detailFaktur' => function ($detail) use ($allTime, $start, $end) {
                $detail->with('faktur')
                    ->when(!$allTime, function ($query) use ($start, $end) {
                        $query->whereHas('faktur', function ($faktur) use ($start, $end) {
                            $faktur->whereBetween('tgl_faktur', [$start->toDateString(), $end->toDateString()]);
                        });
                    });
            }])
            ->orderBy('nama_produk')
            ->get();

        $report = $produk->map(function ($produkItem) {
            $soldQty = $produkItem->detailFaktur->sum('qty');
            $revenue = $produkItem->detailFaktur->sum(fn ($detail) => $detail->qty * $detail->price);
            $lastSold = $produkItem->detailFaktur
                ->map(fn ($detail) => optional($detail->faktur)->tgl_faktur)
                ->filter()
                ->max();

            return (object) [
                'produk' => $produkItem,
                'sold_qty' => $soldQty,
                'revenue' => $revenue,
                'last_sold_at' => $lastSold,
            ];
        });

        $summary = [
            'total_produk' => $report->count(),
            'stok_tersedia' => $report->sum(fn ($row) => $row->produk->stock),
            'qty_terjual' => $report->sum(fn ($row) => $row->sold_qty),
            'pendapatan' => $report->sum(fn ($row) => $row->revenue),
        ];

        $filters = array_merge($filters, [
            'jenis' => $jenis,
            'jenis_list' => Produk::select('jenis')
                ->whereNotNull('jenis')
                ->distinct()
                ->orderBy('jenis')
                ->pluck('jenis'),
        ]);

        return view('laporan.produk', [
            'report' => $report,
            'summary' => $summary,
            'filters' => $filters,
        ]);
    }

    public function penjualan(Request $request): View
    {
        [$start, $end, $filters, $allTime] = $this->parseDateFilters($request);

        $search = $request->input('search');
        $metode = $request->input('metode');

        $faktur = Faktur::with(['customer'])
            ->withSum('detailFaktur as total_barang', 'qty')
            ->orderByDesc('tgl_faktur')
            ->when(!$allTime, fn ($query) => $query->whereBetween('tgl_faktur', [$start->toDateString(), $end->toDateString()]))
            ->when($search, function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('no_faktur', 'like', "%{$search}%")
                        ->orWhereHas('customer', fn ($customer) => $customer->where('nama_customer', 'like', "%{$search}%"));
                });
            })
            ->when($metode, fn ($query) => $query->where('metode_bayar', $metode))
            ->get();

        $summary = [
            'total_faktur' => $faktur->count(),
            'pendapatan' => $faktur->sum('grand_total'),
            'dp' => $faktur->sum('dp'),
            'piutang' => $faktur->sum(fn ($row) => max(($row->grand_total ?? 0) - ($row->dp ?? 0), 0)),
            'barang_terjual' => $faktur->sum(fn ($row) => $row->total_barang ?? 0),
        ];

        $filters = array_merge($filters, [
            'search' => $search,
            'metode' => $metode,
            'metode_list' => Faktur::select('metode_bayar')
                ->whereNotNull('metode_bayar')
                ->distinct()
                ->orderBy('metode_bayar')
                ->pluck('metode_bayar'),
        ]);

        return view('laporan.penjualan', [
            'faktur' => $faktur,
            'summary' => $summary,
            'filters' => $filters,
        ]);
    }

    private function parseDateFilters(Request $request): array
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'all_time' => ['nullable'],
        ]);

        $allTime = $request->boolean('all_time');

        if ($allTime) {
            return [null, null, [
                'from' => null,
                'to' => null,
                'all_time' => true,
            ], true];
        }

        [$start, $end] = $this->normalizeRange($validated['from'] ?? null, $validated['to'] ?? null);

        return [$start, $end, [
            'from' => $validated['from'] ?? $start?->toDateString(),
            'to' => $validated['to'] ?? $end?->toDateString(),
            'all_time' => false,
        ], false];
    }

    private function normalizeRange(?string $from, ?string $to): array
    {
        $start = $from ? Carbon::parse($from)->startOfDay() : null;
        $end = $to ? Carbon::parse($to)->endOfDay() : null;

        if (!$start && !$end) {
            $start = now()->startOfMonth();
            $end = now()->endOfDay();
        }

        if ($start && !$end) {
            $end = $start->copy()->addMonth()->endOfDay();
        }

        if ($end && !$start) {
            $start = $end->copy()->subMonth()->startOfDay();
        }

        return [$start, $end];
    }
}
