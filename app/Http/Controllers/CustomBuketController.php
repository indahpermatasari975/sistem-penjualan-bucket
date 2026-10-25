<?php

namespace App\Http\Controllers;

use App\Models\CustomOption;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomBuketController extends Controller
{
    // Menampilkan halaman fitur rakit buket custom (Minggu 4)
    public function index(?int $product = null)
    {
        $product = $product
            ? Product::findOrFail($product)
            : Product::query()->first();

        $customOptions = $this->getCustomOptions();

        return view('custom.index', compact('product', 'customOptions'));
    }

    // Memproses form rakit custom buket (ke keranjang atau langsung checkout)
    public function process(Request $request)
    {
        $customOptions = $this->getCustomOptions();

        // Pastikan pilihan yang dikirim memang tersedia di form.
        $request->validate([
            'product_id'      => 'nullable|integer|exists:products,id',
            'bunga_isi'       => ['required', Rule::in(array_column($customOptions['Bunga'], 'value'))],
            'uang_nominal'    => ['required', Rule::in(array_column($customOptions['Uang'], 'value'))],
            'jumlah_lembar'   => ['required', Rule::in(array_column($customOptions['Banyak Lembar Uang'], 'value'))],
            'tambahan_boneka' => ['required', Rule::in(array_column($customOptions['Boneka'], 'value'))],
            'warna_buket'     => ['required', Rule::in(array_column($customOptions['Warna Buket'], 'value'))],
            'pesan_ucapan'    => 'nullable|string|max:255',
            'request_khusus'  => 'nullable|string|max:1000',
        ]);

        $product = $request->filled('product_id')
            ? Product::findOrFail($request->integer('product_id'))
            : null;

        $hargaBunga = $this->getOptionPrice($customOptions['Bunga'], $request->input('bunga_isi'));
        $hargaLembar = $this->getOptionPrice($customOptions['Banyak Lembar Uang'], $request->input('jumlah_lembar'));
        $hargaBoneka = $this->getOptionPrice($customOptions['Boneka'], $request->input('tambahan_boneka'));
        $totalHarga = (int) ($product->price ?? 0) + $hargaBunga + $hargaLembar + $hargaBoneka;

        $customData = [
            'type'            => 'custom',
            'name'            => 'Buket Custom (' . $request->bunga_isi . ')',
            'bunga_isi'       => $request->bunga_isi,
            'tambahan_uang'   => 'Rp ' . number_format((int) $request->uang_nominal, 0, ',', '.') .
                ' (' . $request->jumlah_lembar . ' lembar)',
            'tambahan_boneka' => $request->tambahan_boneka,
            'warna_kertas'    => $request->warna_buket,
            'warna_pita'      => '-',
            'pesan_ucapan'    => $request->pesan_ucapan ?: '-',
            'request_khusus'  => $request->request_khusus ?: '-',
            'price'           => $totalHarga,
            'quantity'        => 1,
        ];

        $customData['custom_text'] = "Bunga: {$customData['bunga_isi']}, Uang: {$customData['tambahan_uang']}, Boneka: {$customData['tambahan_boneka']}, Warna buket: {$customData['warna_kertas']}, Ucapan: {$customData['pesan_ucapan']}";

        if ($request->input('action') === 'keranjang') {
            session()->push('cart', $customData);
            return redirect()->route('cart.index')->with('success', 'Buket custom berhasil dimasukkan ke keranjang!');
        }

        session(['checkout_item' => $customData]);
        return redirect()->route('checkout.index')->with('success', 'Lanjut ke pembayaran buket custom Anda!');
    }

    private function getCustomOptions(): array
    {
        $options = [
            'Bunga' => [
                ['value' => 'Mawar Merah', 'name' => 'Mawar Merah', 'price' => 35000],
                ['value' => 'Mawar Pink', 'name' => 'Mawar Pink', 'price' => 35000],
                ['value' => 'Tulip', 'name' => 'Tulip', 'price' => 45000],
                ['value' => 'Bunga Matahari (Sunflower)', 'name' => 'Bunga Matahari (Sunflower)', 'price' => 35000],
                ['value' => "Baby's Breath", 'name' => "Baby's Breath", 'price' => 25000],
                ['value' => 'Daisy (Aster)', 'name' => 'Daisy (Aster)', 'price' => 25000],
            ],
            'Uang' => [
                ['value' => '5000', 'name' => 'Rp 5.000', 'price' => 0],
                ['value' => '10000', 'name' => 'Rp 10.000', 'price' => 0],
                ['value' => '20000', 'name' => 'Rp 20.000', 'price' => 0],
                ['value' => '50000', 'name' => 'Rp 50.000', 'price' => 0],
                ['value' => '100000', 'name' => 'Rp 100.000', 'price' => 0],
            ],
            'Banyak Lembar Uang' => [],
            'Boneka' => [
                ['value' => 'Tanpa Boneka', 'name' => 'Tanpa Boneka', 'price' => 0],
                ['value' => 'Boneka Beruang (Teddy Bear)', 'name' => 'Boneka Beruang (Teddy Bear)', 'price' => 25000],
                ['value' => 'Boneka Karakter Sanrio', 'name' => 'Boneka Karakter Sanrio', 'price' => 35000],
                ['value' => 'Boneka Kelinci (Bunny)', 'name' => 'Boneka Kelinci (Bunny)', 'price' => 30000],
                ['value' => 'Boneka Kucing (Cat)', 'name' => 'Boneka Kucing (Cat)', 'price' => 30000],
                ['value' => 'Boneka Karakter Animasi / Disney', 'name' => 'Boneka Karakter Animasi / Disney', 'price' => 35000],
            ],
            'Warna Buket' => [
                ['value' => 'Dusty Pink & Soft Pink', 'name' => 'Dusty Pink & Soft Pink', 'price' => 0],
                ['value' => 'Milky White / Putih Matte', 'name' => 'Milky White / Putih Matte', 'price' => 0],
                ['value' => 'Lilac / Ungu Muda', 'name' => 'Lilac / Ungu Muda', 'price' => 0],
                ['value' => 'Sage Green & Mint', 'name' => 'Sage Green & Mint', 'price' => 0],
                ['value' => 'Khaki & Cokelat Susu', 'name' => 'Khaki & Cokelat Susu', 'price' => 0],
                ['value' => 'Kraft/Cokelat Semen', 'name' => 'Kraft/Cokelat Semen', 'price' => 0],
                ['value' => 'Black Matte (Hitam)', 'name' => 'Black Matte (Hitam)', 'price' => 0],
                ['value' => 'Maroon (Merah Tua)', 'name' => 'Maroon (Merah Tua)', 'price' => 0],
                ['value' => 'Navy Blue (Biru Dongker)', 'name' => 'Navy Blue (Biru Dongker)', 'price' => 0],
            ],
        ];

        foreach ([5, 10, 15, 20, 25, 30, 35, 40, 45, 50] as $jumlah) {
            $options['Banyak Lembar Uang'][] = [
                'value' => (string) $jumlah,
                'name' => $jumlah . ' lembar',
                'price' => $jumlah * 1000,
            ];
        }

        // Harga bunga dan boneka bisa disesuaikan dari data opsi admin yang namanya cocok.
        $databaseOptions = CustomOption::query()->get();
        foreach (['Bunga', 'Boneka'] as $kategori) {
            foreach ($options[$kategori] as &$option) {
                $databaseOption = $databaseOptions->first(function (CustomOption $item) use ($kategori, $option) {
                    return mb_strtolower(trim($item->category)) === mb_strtolower($kategori)
                        && mb_strtolower(trim($item->name)) === mb_strtolower($option['name']);
                });

                if ($databaseOption) {
                    $option['price'] = (int) $databaseOption->price;
                }
            }
            unset($option);
        }

        return $options;
    }

    private function getOptionPrice(array $options, string $value): int
    {
        foreach ($options as $option) {
            if ($option['value'] === $value) {
                return (int) $option['price'];
            }
        }

        return 0;
    }
}
