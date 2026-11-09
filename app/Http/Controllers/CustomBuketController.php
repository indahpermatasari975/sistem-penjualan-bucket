<?php

namespace App\Http\Controllers;

use App\Models\CustomOption;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CustomBuketController extends Controller
{
    // Menampilkan halaman fitur rakit buket custom
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

        // 1. Validasi input form
        $request->validate([
            'product_id'      => 'nullable|integer',
            'bunga_isi'       => 'nullable|string',
            'uang_nominal'    => 'nullable|string',
            'jumlah_lembar'   => 'nullable|string',
            'tambahan_boneka' => 'nullable|string',
            'tambahan_snack'  => 'nullable|string',
            'warna_buket'     => 'nullable|string',
            'warna_pita'      => 'nullable|string',
            'pesan_ucapan'    => 'nullable|string|max:255',
            'request_khusus'  => 'nullable|string|max:1000',
        ]);

        // 2. Ambil data produk dasar
        $product = null;
        if ($request->filled('product_id')) {
            $product = Product::find($request->product_id);
        }
        if (!$product) {
            $product = Product::first();
        }

        // 3. Hitung harga masing-masing pilihan opsi
        $hargaDasarProduk = $product ? (int) $product->price : 0;
        $hargaBunga = $this->getOptionPrice($customOptions['Bunga'], (string) $request->input('bunga_isi'));

        $jumlahLembar = (int) $request->input('jumlah_lembar');
        $hargaLembar = ($jumlahLembar > 0) ? ($jumlahLembar * 1000) : 0;

        $hargaBoneka = $this->getOptionPrice($customOptions['Boneka'], (string) $request->input('tambahan_boneka'));
        $hargaSnack = $this->getOptionPrice($customOptions['Snack'], (string) $request->input('tambahan_snack'));

        // Total harga keseluruhan custom
        $totalHarga = $hargaDasarProduk + $hargaBunga + $hargaLembar + $hargaBoneka + $hargaSnack;

        // Fallback jika total_price dikirim dari form javascript
        if ($totalHarga <= 0 && $request->filled('total_price')) {
            $totalHarga = (int) $request->input('total_price');
        }
        if ($totalHarga <= 0) {
            $totalHarga = 150000; // Default jika gagal hitung
        }

        // 4. Tangkap rincian custom buket
        $bunga = $request->input('bunga_isi') ?: 'Buket Custom';
        $uangNominal = $request->input('uang_nominal');
        $lembar = $request->input('jumlah_lembar');
        $tambahanUang = ($uangNominal && $uangNominal !== 'Tanpa Uang' && (int)$lembar > 0)
            ? 'Rp ' . number_format((int) $uangNominal, 0, ',', '.') . ' (' . $lembar . ' lembar)'
            : 'Tanpa Uang';

        $boneka = $request->input('tambahan_boneka') ?: 'Tanpa Boneka';
        $snack = $request->input('tambahan_snack') ?: 'Tanpa Snack';
        $warnaKertas = $request->input('warna_kertas') ?: ($request->input('warna_buket') ?: 'Default');
        $warnaPita = $request->input('warna_pita') ?: 'Senada dengan Buket';
        $ucapan = $request->input('pesan_ucapan') ?: '-';
        $catatan = $request->input('request_khusus') ?: '-';

        // Buat teks rincian lengkap
        $rincianCustom = "Bunga: {$bunga}, Kertas: {$warnaKertas}, Pita: {$warnaPita}, Uang: {$tambahanUang}, Boneka: {$boneka}, Snack: {$snack}, Ucapan: {$ucapan}";

        // 5. Susun struktur data pesanan buket custom
        $customData = [
            'type'            => 'custom',
            'product_id'      => $product ? $product->id : null,
            'name'            => 'Buket Custom (' . $bunga . ')',
            'image'           => $product ? $product->image : null,
            'bunga_isi'       => $bunga,
            'tambahan_uang'   => $tambahanUang,
            'tambahan_boneka' => $boneka,
            'tambahan_snack'  => $snack,
            'warna_kertas'    => $warnaKertas,
            'warna_pita'      => $warnaPita,
            'pesan_ucapan'    => $ucapan,
            'request_khusus'  => $catatan,
            'custom_text'     => $rincianCustom,
            'price'           => $totalHarga, // HARGA CUSTOM DISIMPAN DI SINI (Rp 220.000)
            'quantity'        => 1,
        ];

        // 6. Jika user memilih tombol "Masukkan ke Keranjang"
        if ($request->input('action') === 'keranjang') {
            if (\Illuminate\Support\Facades\Auth::check()) {
                \App\Models\Cart::create([
                    'user_id'     => \Illuminate\Support\Facades\Auth::id(),
                    'product_id'  => $product ? $product->id : (\App\Models\Product::first()->id ?? 1),
                    'quantity'    => 1,
                    'custom_text' => $customData['custom_text'],
                ]);
            }
            return redirect()->route('cart.index')->with('success', 'Buket custom berhasil dimasukkan ke keranjang!');
        }

        // 7. Jika user memilih "Beli Sekarang" -> Simpan ke Session
        session([
            'checkout_item' => $customData,
            'custom_buket'  => $customData,
        ]);

        return redirect()->route('checkout.index')->with('success', 'Buket custom berhasil dirakit! Silakan lengkapi data checkout.');
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

            'Snack' => [
                ['value' => 'Tanpa Snack', 'name' => 'Tanpa Snack', 'price' => 0],
                [
                    'value' => 'Paket Hemat','name' => 'Paket Hemat (Chiki ball + Oreo + Permen Kis)','price' => 35000
                ],[
                    'value' => 'Paket Coklat',
                    'name' => 'Paket Coklat Lovers (Silverqueen + KitKat + Beng-Beng + Tango)',
                    'price' => 65000
                ],
                [
                    'value' => 'Paket Jumbo',
                    'name' => 'Paket Jumbo (Chitato + Roma + Cadbury + Taro + Kopiko)',
                    'price' => 80000
                ],
                [
                    'value' => 'Paket Snack Mix',
                    'name' => 'Paket Snack Mix (Chiki + Silverqueen + KitKat + Beng-Beng + Tango + Chitato + Roma + Cadbury + Taro + Kopiko)',
                    'price' => 125000
                ],
                [
                    'value' => 'beng - beng ',
                    'name' => 'Beng-Beng (Coklat + Wafer + Karamel)',
                    'price' => 20000
                ],
                [
                    'value' => 'chitato',
                    'name' => 'Chitato (Keripik Kentang Rasa Original)',
                    'price' => 25000
                ],
                [
                    'value' => 'silverqueen',
                    'name' => 'Silverqueen (Coklat Batangan)',
                    'price' => 30000
                ],
                [
                    'value' => 'kitkat',
                    'name' => 'KitKat (Coklat Wafer)',
                    'price' => 20000
                ],
                [
                    'value' => 'tango',
                    'name' => 'Tango (Wafer Coklat)',
                    'price' => 15000
                ],
                [
                    'value' => 'chocolatos',
                    'name' => 'Chocolatos ',
                    'price' => 15000
                ]

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

        // Ambil harga dari database jika ada yang cocok
        $databaseOptions = CustomOption::query()->get();
        foreach (['Bunga', 'Boneka', 'Snack'] as $kategori) {
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
