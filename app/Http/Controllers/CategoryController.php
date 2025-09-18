<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Hiển thị danh sách categories (cho trang home).
     */
    public function index()
    {
        // Lấy categories kèm theo products
        $categories = Category::with('products')->get();

        // Nếu trang home cần thêm cả products, bạn nên load luôn từ HomeController
        // Ở đây chỉ trả về categories thôi
        return view('categories.index', compact('categories'));
    }

    /**
     * Form thêm category (admin).
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Lưu category mới.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name',
            'slug' => 'required|unique:categories,slug',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'Thêm danh mục thành công');
    }

    /**
     * Hiển thị chi tiết category + các sản phẩm trong category đó.
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);

        // Lấy sản phẩm thuộc category này
        $products = $category->products;

        // Truyền cả categories để hiển thị menu bên ngoài (nếu cần)
        $categories = Category::all();

        return view('category.show', compact('category', 'products', 'categories'));
    }

    /**
     * Form edit category.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update category.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|unique:categories,name,' . $id,
            'slug' => 'required|unique:categories,slug,' . $id,
        ]);

        $category = Category::findOrFail($id);
        $category->update($request->all());

        return redirect()->route('categories.index')
            ->with('success', 'Cập nhật danh mục thành công');
    }

    /**
     * Xóa category.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Xóa danh mục thành công');
    }
}
