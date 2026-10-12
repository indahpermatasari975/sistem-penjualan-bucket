<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('stock')) {
            match ($request->stock) {
                'low' => $query->whereBetween('stock', [1, 5]),
                'out' => $query->where('stock', 0),
                default => null,
            };
        }
        match ($request->get('sort', 'latest')) {
            'price_asc'  => $query->reorder('price', 'asc'),
            'price_desc' => $query->reorder('price', 'desc'),
            'stock_asc'  => $query->reorder('stock', 'asc'),
            'name_asc'   => $query->reorder('name', 'asc'),
            default      => null,
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::all();

        $stats = [
            'total'      => Product::count(),
            'low_stock'  => Product::whereBetween('stock', [1, 5])->count(),
            'out_stock'  => Product::where('stock', 0)->count(),
            'income'     => Product::sum('price'),
        ];

        return view('products.index', compact('products', 'categories', 'stats'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ], [
            'category_id.required' => 'Kategori wajib dipilih.',
            'name.required'        => 'Nama buket wajib diisi.',
            'price.required'       => 'Harga wajib diisi.',
            'stock.required'       => 'Jumlah tersedia wajib diisi.',
            'image.image'          => 'File harus berupa gambar.',
            'image.max'            => 'Ukuran gambar maksimal 2MB.',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('products.index')
            ->with('success', 'Buket "' . $request->name . '" berhasil ditambahkan!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|integer|min:0',
            'stock'       => 'required|integer|min:0',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Buket "' . $product->name . '" berhasil diperbarui!');
    }

    public function destroy(Product $product)
    {
        $name = $product->name;
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Buket "' . $name . '" berhasil dihapus!');
    }
}
