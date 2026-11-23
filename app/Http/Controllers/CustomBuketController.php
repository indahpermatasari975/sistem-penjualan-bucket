<?php

namespace App\Http\Controllers;

use App\Models\CustomOption;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // Memproses form rakit custom buket
    public function process(Request $request)
    {
        $customOptions = $this->getCustomOptions();

        // 1. Validasi input form
        $validated = $request->validate([
            'product_id'      => 'nullable|integer',
            'bunga_isi'       => 'nullable|string|max:100',
            'jumlah_tangkai'  => 'nullable|integer|min:0|max:50',
            'uang_nominal'    => 'nullable|string|max:20',
            'jumlah_lembar'   => 'nullable|integer|min:0|max:50',
            'tambahan_boneka' => 'nullable|string|max:150',
            'tambahan_snack'  => 'nullable|string|max:150',
            'warna_buket'     => 'nullable|string|max:100',
            'warna_kertas'    => 'nullable|string|max:100',
            'warna_pita'      => 'nullable|string|max:100',
            'pesan_ucapan'    => 'nullable|string|max:255',
            'request_khusus'  => 'nullable|string|max:1000',
            'action'          => 'required|in:keranjang,checkout',
        ]);

        // 2. Ambil produk hanya untuk referensi gambar
        // Harga produk dasar tidak digunakan
        $product = null;

        if (!empty($validated['product_id'])) {
            $product = Product::find($validated['product_id']);
        }

        if (!$product) {
            $product = Product::first();
        }

        // 3. Hitung harga bunga
        $bunga = $validated['bunga_isi'] ?? 'Tanpa Bunga';

        $hargaBungaPerTangkai = ($bunga !== 'Tanpa Bunga')
            ? $this->getOptionPrice($customOptions['Bunga'], $bunga)
            : 0;

        $jumlahTangkai = ($bunga !== 'Tanpa Bunga')
            ? max(1, (int) ($validated['jumlah_tangkai'] ?? 1))
            : 0;

        $hargaBunga = $hargaBungaPerTangkai * $jumlahTangkai;

        // 4. Hitung harga uang
        $uangNominalInput = $validated['uang_nominal'] ?? '';

        $nominalUang = ($uangNominalInput !== '' && $uangNominalInput !== 'Tanpa Uang')
            ? (int) $uangNominalInput
            : 0;

        $lembarInput = (int) ($validated['jumlah_lembar'] ?? 0);

        $jumlahLembar = ($nominalUang > 0 && $lembarInput > 0)
            ? $lembarInput
            : 0;

        $hargaUang = $nominalUang * $jumlahLembar;

        // 5. Hitung harga boneka
        $boneka = $validated['tambahan_boneka'] ?? 'Tanpa Boneka';

        $hargaBoneka = ($boneka !== 'Tanpa Boneka')
            ? $this->getOptionPrice($customOptions['Boneka'], $boneka)
            : 0;

        // 6. Hitung harga snack
        $snack = $validated['tambahan_snack'] ?? 'Tanpa Snack';

        $hargaSnack = ($snack !== 'Tanpa Snack')
            ? $this->getOptionPrice($customOptions['Snack'], $snack)
            : 0;

        // 7. Ambil dan hitung harga warna kertas
        $warnaKertas = $validated['warna_kertas']
            ?? $validated['warna_buket']
            ?? '';

        if ($warnaKertas === '') {
            $warnaKertas = 'Belum dipilih';
        }

        $hargaWarnaBuket = $this->getOptionPrice(
            $customOptions['Warna Buket'],
            $warnaKertas
        );

        // 8. Ambil dan hitung harga pita
        $warnaPita = $validated['warna_pita']
            ?? 'Senada dengan Buket';

        $hargaPita = $this->getOptionPrice(
            $customOptions['Pita'],
            $warnaPita
        );

        // 9. Hitung total harga
        // Harga produk dasar tidak ikut ditambahkan
        $totalHarga = $hargaBunga
            + $hargaUang
            + $hargaBoneka
            + $hargaSnack
            + $hargaWarnaBuket
            + $hargaPita;

        // 10. Ambil pilihan tambahan lainnya
        $ucapan = $validated['pesan_ucapan'] ?? '-';
        $catatan = $validated['request_khusus'] ?? '-';

        // 11. Susun rincian pilihan bunga
        $bungaText = ($bunga !== 'Tanpa Bunga')
            ? "{$bunga} ({$jumlahTangkai} tangkai)"
            : 'Tanpa Bunga';

        // Rincian uang
        $tambahanUang = ($nominalUang > 0 && $jumlahLembar > 0)
            ? 'Rp ' . number_format($nominalUang, 0, ',', '.') .
                ' (' . $jumlahLembar . ' lembar)'
            : 'Tanpa Uang';

        // 12. Susun rincian custom
        $rincianUser = "Bunga: {$bungaText}, "
            . "Harga Bunga: Rp " . number_format($hargaBunga, 0, ',', '.') . ", "
            . "Uang: {$tambahanUang}, "
            . "Harga Uang: Rp " . number_format($hargaUang, 0, ',', '.') . ", "
            . "Boneka: {$boneka}, "
            . "Harga Boneka: Rp " . number_format($hargaBoneka, 0, ',', '.') . ", "
            . "Snack: {$snack}, "
            . "Harga Snack: Rp " . number_format($hargaSnack, 0, ',', '.') . ", "
            . "Kertas: {$warnaKertas}, "
            . "Harga Warna: Rp " . number_format($hargaWarnaBuket, 0, ',', '.') . ", "
            . "Pita: {$warnaPita}, "
            . "Harga Pita: Rp " . number_format($hargaPita, 0, ',', '.') . ", "
            . "Ucapan: {$ucapan}, "
            . "Catatan: {$catatan}";

        // Penanda harga agar harga custom dapat dikenali
        $customTextWithPrice = "[HARGA:{$totalHarga}] " . $rincianUser;

        // 13. Susun data buket custom
        $customData = [
            'type'            => 'custom',
            'product_id'      => $product ? $product->id : null,

            'name'            => 'Buket Custom (' .
                ($bunga !== 'Tanpa Bunga' ? $bunga : 'Kustom') . ')',

            'image'           => $product ? $product->image : null,

            'bunga_isi'       => $bungaText,
            'jumlah_tangkai'  => $jumlahTangkai,
            'harga_bunga'     => $hargaBunga,

            'tambahan_uang'   => $tambahanUang,
            'nominal_uang'    => $nominalUang,
            'jumlah_lembar'   => $jumlahLembar,
            'harga_uang'      => $hargaUang,

            'tambahan_boneka' => $boneka,
            'harga_boneka'    => $hargaBoneka,

            'tambahan_snack'  => $snack,
            'harga_snack'     => $hargaSnack,

            'warna_kertas'    => $warnaKertas,
            'harga_warna'     => $hargaWarnaBuket,

            'warna_pita'      => $warnaPita,
            'harga_pita'      => $hargaPita,

            'pesan_ucapan'    => $ucapan,
            'request_khusus'  => $catatan,

            'custom_text'     => $customTextWithPrice,
            'price'           => $totalHarga,
            'quantity'        => 1,
        ];

        // 14. Masukkan ke keranjang
        if ($validated['action'] === 'keranjang') {

            if (!Auth::check()) {
                return redirect()->route('login');
            }

            if (!$product) {
                return back()
                    ->withInput()
                    ->with('error', 'Produk referensi belum tersedia.');
            }

            Cart::create([
                'user_id'     => Auth::id(),
                'product_id'  => $product->id,
                'quantity'    => 1,
                'price'       => $totalHarga,
                'custom_text' => $customTextWithPrice,
            ]);

            return redirect()->route('cart.index')
                ->with(
                    'success',
                    'Buket custom berhasil dimasukkan ke keranjang!'
                );
        }

        // 15. Checkout langsung
        session([
            'checkout_item' => $customData,
            'custom_buket'  => $customData,
        ]);

        return redirect()->route('checkout.index')
            ->with(
                'success',
                'Buket custom berhasil dirakit! Silakan lengkapi data checkout.'
            );
    }

    // Mengambil daftar pilihan custom
    private function getCustomOptions(): array
    {
        $options = [
            'Bunga' => [
                ['value' => 'Mawar Merah', 'name' => 'Mawar Merah', 'price' => 12000],
                ['value' => 'Mawar Pink', 'name' => 'Mawar Pink', 'price' => 12000],
                ['value' => 'Mawar Putih', 'name' => 'Mawar Putih', 'price' => 12000],
                ['value' => 'Lili (Lily)', 'name' => 'Lili (Lily)', 'price' => 20000],
                ['value' => 'Tulip', 'name' => 'Tulip', 'price' => 12000],
                ['value' => 'Bunga Matahari (Sunflower)', 'name' => 'Bunga Matahari (Sunflower)', 'price' => 15000],
                ['value' => "Baby's Breath", 'name' => "Baby's Breath", 'price' => 10000],
                ['value' => 'Daisy (Aster)', 'name' => 'Daisy (Aster)', 'price' => 8000],
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
                ['value' => 'Paket Hemat', 'name' => 'Paket Hemat (Chiki ball + Oreo + Permen Kis)', 'price' => 35000],
                ['value' => 'Paket Coklat', 'name' => 'Paket Coklat Lovers (Silverqueen + KitKat + Beng-Beng + Tango)', 'price' => 65000],
                ['value' => 'Paket Jumbo', 'name' => 'Paket Jumbo (Chitato + Roma + Cadbury + Taro + Kopiko)', 'price' => 80000],
                ['value' => 'Paket Snack Mix', 'name' => 'Paket Snack Mix (Chiki + Silverqueen + KitKat + Beng-Beng + Tango + Chitato + Roma + Cadbury + Taro + Kopiko)', 'price' => 125000],
                ['value' => 'beng - beng ', 'name' => 'Beng-Beng (Coklat + Wafer + Karamel)', 'price' => 20000],
                ['value' => 'chitato', 'name' => 'Chitato (Keripik Kentang Rasa Original)', 'price' => 25000],
                ['value' => 'silverqueen', 'name' => 'Silverqueen (Coklat Batangan)', 'price' => 30000],
                ['value' => 'kitkat', 'name' => 'KitKat (Coklat Wafer)', 'price' => 20000],
                ['value' => 'tango', 'name' => 'Tango (Wafer Coklat)', 'price' => 15000],
                ['value' => 'chocolatos', 'name' => 'Chocolatos', 'price' => 15000],
            ],

            'Warna Buket' => [
                ['value' => 'Dusty Pink & Soft Pink', 'name' => 'Dusty Pink & Soft Pink', 'price' => 15000],
                ['value' => 'Milky White / Putih Matte', 'name' => 'Milky White / Putih Matte', 'price' => 15000],
                ['value' => 'Lilac / Ungu Muda', 'name' => 'Lilac / Ungu Muda', 'price' => 15000],
                ['value' => 'Sage Green & Mint', 'name' => 'Sage Green & Mint', 'price' => 15000],
                ['value' => 'Peach & Coral', 'name' => 'Peach & Coral', 'price' => 15000],
                ['value' => 'Soft Yellow & Cream', 'name' => 'Soft Yellow & Cream', 'price' => 15000],
                ['value' => 'Sky Blue & Baby Blue', 'name' => 'Sky Blue & Baby Blue', 'price' => 15000],
                ['value' => 'Lavender & Soft Purple', 'name' => 'Lavender & Soft Purple', 'price' => 15000],
                ['value' => 'Champagne / Beige', 'name' => 'Champagne / Beige', 'price' => 15000],
                ['value' => 'Coral Pink & Salmon', 'name' => 'Coral Pink & Salmon', 'price' => 15000],
                ['value' => 'Khaki & Cokelat Susu', 'name' => 'Khaki & Cokelat Susu', 'price' => 15000],
                ['value' => 'Kraft/Cokelat Semen', 'name' => 'Kraft/Cokelat Semen', 'price' => 15000],
                ['value' => 'Black Matte (Hitam)', 'name' => 'Black Matte (Hitam)', 'price' => 15000],
                ['value' => 'Maroon (Merah Tua)', 'name' => 'Maroon (Merah Tua)', 'price' => 15000],
                ['value' => 'Navy Blue (Biru Dongker)', 'name' => 'Navy Blue (Biru Dongker)', 'price' => 15000],
            ],

            'Pita' => [
                ['value' => 'Senada dengan Buket', 'name' => 'Pita Senada dengan Buket', 'price' => 0],
                ['value' => 'Pita Satin Putih', 'name' => 'Pita Satin Putih', 'price' => 3000],
                ['value' => 'Pita Satin Emas', 'name' => 'Pita Satin Emas', 'price' => 3000],
                ['value' => 'Pita Satin Pink', 'name' => 'Pita Satin Pink', 'price' => 3000],
                ['value' => 'Pita Satin Hitam', 'name' => 'Pita Satin Hitam', 'price' => 3000],
                ['value' => 'Pita Satin Maroon', 'name' => 'Pita Satin Maroon', 'price' => 3000],
            ],
        ];

        // Pilihan jumlah lembar uang
        foreach ([5, 10, 15, 20, 25, 30, 35, 40, 45, 50] as $jumlah) {
            $options['Banyak Lembar Uang'][] = [
                'value' => (string) $jumlah,
                'name' => $jumlah . ' lembar',
                'price' => 0,
            ];
        }

        // Ambil harga dari database jika tersedia
        $databaseOptions = CustomOption::query()->get();

        foreach (['Bunga', 'Boneka', 'Snack'] as $kategori) {
            foreach ($options[$kategori] as &$option) {
                $databaseOption = $databaseOptions->first(
                    function (CustomOption $item) use ($kategori, $option) {
                        return mb_strtolower(trim($item->category))
                                === mb_strtolower($kategori)
                            && mb_strtolower(trim($item->name))
                                === mb_strtolower($option['name']);
                    }
                );

                if ($databaseOption) {
                    $option['price'] = (int) $databaseOption->price;
                }
            }

            unset($option);
        }

        return $options;
    }

    // Mengambil harga berdasarkan pilihan
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

