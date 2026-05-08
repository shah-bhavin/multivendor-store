<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts/app')]
class Products extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $category_id = '';
    public $name = '';
    public $description = '';
    public $price = '';
    public $stock = '';
    public $image;
    public $status = true;

    // Edit Mode
    public $productId = null;
    public $editMode = false;

    // Search
    public $search = '';

    protected $paginationTheme = 'tailwind';

    public function save()
    {
        $this->validate([

            'name' => 'required|min:3',

            'price' => 'required|numeric',

            'stock' => 'required|integer',

            'image' => 'nullable|image|max:2048',

            'category_id' => 'required',
        ]);

        $imagePath = null;

        if ($this->image) {

            $imagePath = $this->image->store(
                'products',
                'public'
            );
        }

        Product::create([

            'name' => $this->name,

            'category_id' => $this->category_id,

            'description' => $this->description,

            'price' => $this->price,

            'stock' => $this->stock,

            'image' => $imagePath,

            'status' => $this->status,
        ]);

        session()->flash(
            'success',
            'Product Created Successfully'
        );

        $this->resetForm();

        $this->status = true;
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $this->productId = $product->id;

        $this->name = $product->name;

        $this->description = $product->description;

        $this->price = $product->price;

        $this->stock = $product->stock;

        $this->status = $product->status;

        $this->editMode = true;
    }

    public function update()
    {
        $this->validate([

            'name' => 'required|min:3',

            'price' => 'required|numeric',

            'stock' => 'required|integer',

            'category_id' => 'required',
        ]);

        $product = Product::findOrFail(
            $this->productId
        );

        $imagePath = $product->image;

        if ($this->image) {

            $imagePath = $this->image->store(
                'products',
                'public'
            );
        }

        $product->update([

            'name' => $this->name,

            'category_id' => $this->category_id,

            'description' => $this->description,

            'price' => $this->price,

            'stock' => $this->stock,

            'image' => $imagePath,

            'status' => $this->status,
        ]);

        session()->flash(
            'success',
            'Product Updated Successfully'
        );

        $this->resetForm();
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();

        session()->flash(
            'success',
            'Product Deleted Successfully'
        );
    }

    public function resetForm()
    {
        $this->reset([

            'name',
            'category_id',
            'description',
            'price',
            'stock',
            'image',
            'productId',
        ]);

        $this->status = true;

        $this->editMode = false;
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        $products = Product::where(
            'name',
            'like',
            '%' . $this->search . '%'
        )
            ->latest()
            ->paginate(5);

        $categories = Category::all();

        return view(
            'livewire.admin.products',
            [
                'products' => $products,
                'categories' => $categories,
            ]
        );
    }
}
