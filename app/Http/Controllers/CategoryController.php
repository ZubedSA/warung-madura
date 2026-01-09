<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display categories list
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->orderBy('sort_order')
            ->get();

        return view('admin.master.categories.index', compact('categories'));
    }

    /**
     * Show create category form
     */
    public function create()
    {
        return view('admin.master.categories.create');
    }

    /**
     * Store new category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:10'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ], [
            'name.required' => 'Nama kategori harus diisi',
            'icon.required' => 'Icon harus diisi',
        ]);

        Category::create($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan');
    }

    /**
     * Show edit category form
     */
    public function edit(Category $kategori)
    {
        return view('admin.master.categories.edit', ['category' => $kategori]);
    }

    /**
     * Update category
     */
    public function update(Request $request, Category $kategori)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['required', 'string', 'max:10'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diupdate');
    }

    /**
     * Delete category
     */
    public function destroy(Category $kategori)
    {
        if ($kategori->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki barang');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus');
    }
}
