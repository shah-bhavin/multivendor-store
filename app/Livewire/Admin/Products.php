<?php

namespace App\Livewire\Admin;

use App\Models\Product;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts/app')]
class Products extends Component
{
    use WithFileUploads;

    public $name = '';
    public $description = '';
    public $price = '';
    public $stock = '';
    public $image;
    public $status = true;

    public function save()
    {
        $this->validate([

            'name' => 'required|min:3',

            'price' => 'required|numeric',

            'stock' => 'required|integer',

            'image' => 'nullable|image|max:2048',
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

        $this->reset([
            'name',
            'description',
            'price',
            'stock',
            'image',
        ]);

        $this->status = true;
    }

    public function render()
    {
        $products = Product::latest()->get();

        return view('livewire.admin.products', [
            'products' => $products
        ]);
    }
}