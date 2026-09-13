<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pemberitahuan Revisi LKj</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h2 style="color: #4338ca;">Pemberitahuan Revisi Laporan Kinerja (LKj)</h2>

    <p>Yth. Operator Satker <strong>{{ $submission->satker->nama_satker ?? '-' }}</strong>,</p>

    <p>
        Berdasarkan hasil review Tim Monev, terdapat
        <strong>{{ $jumlahPerbaikan }}</strong> item yang memerlukan perbaikan pada dokumen LKj
        periode <strong>LKj {{ $submission->periode->tahun_lkj ?? '-' }} / Review {{ $submission->periode->tahun_review ?? '-' }}</strong>.
    </p>

    <p>
        Silakan login ke aplikasi untuk melihat detail catatan perbaikan, mengisi tanggapan,
        dan mengunggah dokumen LKj revisi.
    </p>

    <p style="margin-top: 24px;">
        <a href="{{ url('/satker/revisi') }}"
           style="display: inline-block; background-color: #4338ca; color: #ffffff; padding: 10px 18px; border-radius: 6px; text-decoration: none;">
            Lihat Detail Revisi
        </a>
    </p>

    <p style="margin-top: 24px; font-size: 12px; color: #6b7280;">
        Email ini dikirim otomatis oleh sistem Review LKj. Mohon tidak membalas email ini.
    </p>
</body>
</html>
