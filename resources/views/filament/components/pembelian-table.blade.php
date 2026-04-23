<table class="table-auto w-full border-collapse border border-gray-300">
    <thead>
        <tr class="bg-gray-200">
            <th class="border px-4 py-2">No Faktur</th>
            <th class="border px-4 py-2">Tanggal</th>
            <th class="border px-4 py-2">Jumlah</th>
        </tr>
    </thead>
    <tbody>
        @foreach($getRecord()->detail as $item)
            <tr>
                <td class="border px-4 py-2">{{ $getRecord()->no_faktur }}</td>
                <td class="border px-4 py-2">{{ $getRecord()->tgl }}</td>
                <td class="border px-4 py-2">
                    Rp{{ number_format($item->jml * $item->harga, 0, ',', '.') }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>