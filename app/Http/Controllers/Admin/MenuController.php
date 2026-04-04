<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->get();
        $categories = Category::all();
        return view('admin.menus.index', compact('menus', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'image' => 'nullable|image',
        ]);
        
        $data = $request->except('image');
        $data['is_ready'] = $request->has('is_ready');

        if($request->hasFile('image')){
            $data['image'] = $request->file('image')->store('menus', 'public');
        }
        Menu::create($data);
        return redirect()->route('admin.menus.index')->with('success', 'Menu ditambahkan');
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'image' => 'nullable|image'
        ]);

        $data = $request->except('image');
        $data['is_ready'] = $request->has('is_ready');

        if($request->hasFile('image')){
            $data['image'] = $request->file('image')->store('menus', 'public');
        }
        $menu->update($data);
        return redirect()->route('admin.menus.index')->with('success', 'Menu diupdate');
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu dihapus');
    }
}
