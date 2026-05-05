<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class OrdersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle, WithEvents
{
    protected $startDate;
    protected $endDate;
    protected $period;
    protected $orderCount = 0;
    protected $totalRevenue = 0;

    public function __construct(?string $startDate = null, ?string $endDate = null, ?string $period = null)
    {
        $this->period = $period;

        // Handle preset periods
        if ($period === '7days') {
            $this->startDate = now()->subDays(7)->startOfDay();
            $this->endDate = now()->endOfDay();
        } elseif ($period === '30days') {
            $this->startDate = now()->subDays(30)->startOfDay();
            $this->endDate = now()->endOfDay();
        } elseif ($period === 'this_month') {
            $this->startDate = now()->startOfMonth();
            $this->endDate = now()->endOfDay();
        } elseif ($period === 'last_month') {
            $this->startDate = now()->subMonth()->startOfMonth();
            $this->endDate = now()->subMonth()->endOfMonth();
        } elseif ($startDate && $endDate) {
            $this->startDate = $startDate;
            $this->endDate = $endDate;
        } else {
            // Default: all data
            $this->startDate = null;
            $this->endDate = null;
        }
    }

    public function collection()
    {
        $query = Order::query()->orderBy('created_at', 'desc');

        if ($this->startDate) {
            $query->whereDate('created_at', '>=', $this->startDate);
        }
        if ($this->endDate) {
            $query->whereDate('created_at', '<=', $this->endDate);
        }

        $orders = $query->get();
        $this->orderCount = $orders->count();
        $this->totalRevenue = $orders->sum('total_price');

        return $orders;
    }

    public function headings(): array
    {
        return [
            'No',
            'Order ID',
            'Tanggal',
            'Nama Pelanggan',
            'No. HP',
            'Merek Sepatu',
            'Ukuran',
            'Kondisi',
            'Kategori Layanan',
            'Nama Layanan',
            'Harga Layanan',
            'Biaya Tambahan',
            'Total Harga',
            'Metode Bayar',
            'Status Bayar',
            'Status Order',
            'Estimasi (Hari)',
            'Catatan',
            'Dibuat Oleh',
        ];
    }

    public function map($order): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $order->order_id_formatted,
            $order->created_at->format('d/m/Y H:i'),
            $order->customer_name,
            "'" . $order->phone_number,
            $order->shoe_brand ?? '-',
            $order->shoe_size ?? '-',
            $order->shoe_condition ?? '-',
            $order->service_category ?? '-',
            $order->service_name,
            $order->total_price - ($order->additional_fees ?? 0),
            $order->additional_fees ?? 0,
            $order->total_price,
            $order->payment_method ?? '-',
            $order->payment_status === 'paid' ? 'Lunas' : 'Belum Lunas',
            $order->status,
            $order->estimated_days ?? '-',
            $order->notes ?? '-',
            $order->created_by ?? '-',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,    // No
            'B' => 12,   // Order ID
            'C' => 18,   // Tanggal
            'D' => 22,   // Nama
            'E' => 16,   // HP
            'F' => 15,   // Merek
            'G' => 10,   // Ukuran
            'H' => 14,   // Kondisi
            'I' => 18,   // Kategori
            'J' => 22,   // Layanan
            'K' => 16,   // Harga Layanan
            'L' => 16,   // Biaya Tambahan
            'M' => 16,   // Total
            'N' => 14,   // Metode Bayar
            'O' => 14,   // Status Bayar
            'P' => 14,   // Status Order
            'Q' => 14,   // Estimasi
            'R' => 25,   // Catatan
            'S' => 14,   // Dibuat Oleh
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 11,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F2937'], // Dark gray
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ],
        ];
    }

    public function title(): string
    {
        if ($this->period === '7days') return 'Laporan 7 Hari Terakhir';
        if ($this->period === '30days') return 'Laporan 30 Hari Terakhir';
        if ($this->period === 'this_month') return 'Laporan Bulan Ini';
        if ($this->period === 'last_month') return 'Laporan Bulan Lalu';

        return 'Laporan Pesanan';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->orderCount + 1; // +1 for header

                // Format currency columns (K, L, M)
                $sheet->getStyle("K2:M{$lastRow}")
                    ->getNumberFormat()
                    ->setFormatCode('#,##0');

                // Add borders to all data
                $sheet->getStyle("A1:S{$lastRow}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->getColor()->setRGB('D1D5DB');

                // Header row height
                $sheet->getRowDimension(1)->setRowHeight(30);

                // Alternate row colors for readability
                for ($i = 2; $i <= $lastRow; $i++) {
                    if ($i % 2 === 0) {
                        $sheet->getStyle("A{$i}:S{$i}")
                            ->getFill()
                            ->setFillType(Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F9FAFB');
                    }
                }

                // Status Bayar coloring
                for ($i = 2; $i <= $lastRow; $i++) {
                    $cellValue = $sheet->getCell("O{$i}")->getValue();
                    if ($cellValue === 'Lunas') {
                        $sheet->getStyle("O{$i}")
                            ->getFont()->getColor()->setRGB('059669');
                        $sheet->getStyle("O{$i}")
                            ->getFont()->setBold(true);
                    } else {
                        $sheet->getStyle("O{$i}")
                            ->getFont()->getColor()->setRGB('DC2626');
                        $sheet->getStyle("O{$i}")
                            ->getFont()->setBold(true);
                    }
                }

                // Summary section
                $summaryRow = $lastRow + 2;
                $sheet->setCellValue("A{$summaryRow}", 'RINGKASAN LAPORAN');
                $sheet->getStyle("A{$summaryRow}")
                    ->getFont()->setBold(true)->setSize(12);
                $sheet->mergeCells("A{$summaryRow}:C{$summaryRow}");

                $infoRow1 = $summaryRow + 1;
                $infoRow2 = $summaryRow + 2;
                $infoRow3 = $summaryRow + 3;
                $infoRow4 = $summaryRow + 4;

                // Period info
                $periodText = 'Semua Data';
                if ($this->startDate && $this->endDate) {
                    $start = $this->startDate instanceof \Carbon\Carbon ? $this->startDate->format('d/m/Y') : date('d/m/Y', strtotime($this->startDate));
                    $end = $this->endDate instanceof \Carbon\Carbon ? $this->endDate->format('d/m/Y') : date('d/m/Y', strtotime($this->endDate));
                    $periodText = "{$start} - {$end}";
                }

                $sheet->setCellValue("A{$infoRow1}", 'Periode:');
                $sheet->setCellValue("B{$infoRow1}", $periodText);
                $sheet->getStyle("A{$infoRow1}")->getFont()->setBold(true);

                $sheet->setCellValue("A{$infoRow2}", 'Total Pesanan:');
                $sheet->setCellValue("B{$infoRow2}", $this->orderCount . ' pesanan');
                $sheet->getStyle("A{$infoRow2}")->getFont()->setBold(true);

                $sheet->setCellValue("A{$infoRow3}", 'Total Pendapatan:');
                $sheet->setCellValue("B{$infoRow3}", $this->totalRevenue);
                $sheet->getStyle("B{$infoRow3}")
                    ->getNumberFormat()
                    ->setFormatCode('"Rp "#,##0');
                $sheet->getStyle("A{$infoRow3}")->getFont()->setBold(true);
                $sheet->getStyle("B{$infoRow3}")->getFont()->setBold(true)->setSize(13);

                $sheet->setCellValue("A{$infoRow4}", 'Diekspor pada:');
                $sheet->setCellValue("B{$infoRow4}", now()->format('d/m/Y H:i') . ' WIB');
                $sheet->getStyle("A{$infoRow4}")->getFont()->setBold(true);

                // Summary border
                $sheet->getStyle("A{$summaryRow}:D{$infoRow4}")
                    ->getBorders()
                    ->getOutline()
                    ->setBorderStyle(Border::BORDER_MEDIUM)
                    ->getColor()->setRGB('1F2937');

                // Freeze first row
                $sheet->freezePane('A2');
            },
        ];
    }
}
