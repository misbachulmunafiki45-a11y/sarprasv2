<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Admin - {{ $report->code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align:center; margin-bottom: 10px; }
        .title { font-size: 18px; font-weight: bold; }
        .meta { margin-top: 5px; color:#555; }
        .section { margin-top: 15px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { text-align: left; padding: 6px; border: 1px solid #ccc; vertical-align: top; }
        .img { margin-top: 8px; }
        .divider { height: 6px; background: #f2f2f2; border: 0; }
        img { max-width: 100%; height: auto; }
    </style>
</head>
<body>
    <div class="header">
        <div class="title">Laporan Pengaduan - Ringkasan Admin</div>
        <div class="meta">Kode: {{ $report->code }} | Tanggal Cetak: {{ now()->locale('id')->translatedFormat('l, d F Y') }}</div>
    </div>

    <div class="section">
        <table>
            <tr>
                <th style="width: 28%">Pelapor</th>
                <td>{{ $report->resident->user->name }} ({{ $report->resident->user->email }})</td>
            </tr>
            <tr>
                <th>Judul</th>
                <td>{{ $report->title }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td>{{ $report->reportCategory->name }}</td>
            </tr>
            <tr>
                <th>Tanggal</th>
                <td>{{ \Carbon\Carbon::parse($report->created_at)->locale('id')->translatedFormat('l, d F Y') }}</td>
            </tr>
            <tr>
                <th>Deskripsi</th>
                <td>{{ $report->description }}</td>
            </tr>
            <tr>
                <th>Bukti Laporan</th>
                <td>
                    @if($report->image)
                        <img class="img" src="{{ storage_path('app/public/' . $report->image) }}" width="520" style="max-width:100%; height:auto;" />
                    @else - @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="title" style="font-size:14px;">Riwayat Progress</div>
        <table>
            <tbody>
                @foreach($report->reportStatuses as $status)
                <tr>
                    <th style="width: 28%">Tanggal</th>
                    <td>{{ \Carbon\Carbon::parse($status->created_at)->locale('id')->translatedFormat('d F Y H:i') }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $status->status }}</td>
                </tr>
                <tr>
                    <th>Bukti</th>
                    <td>
                        @if(!empty($status->image))
                            <img src="{{ storage_path('app/public/' . $status->image) }}" width="520" style="max-width:100%; height:auto;" />
                        @else - @endif
                    </td>
                </tr>
                <tr>
                    <th>Biaya</th>
                    <td>{{ filled($status->funding) ? $status->funding : '-' }}</td>
                </tr>
                <tr>
                    <th>Deskripsi</th>
                    <td>{{ $status->description }}</td>
                </tr>
                @if(!$loop->last)
                <tr>
                    <td colspan="2" class="divider"></td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>