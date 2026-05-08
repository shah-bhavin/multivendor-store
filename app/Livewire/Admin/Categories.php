<?php

namespace App\Livewire\Admin;

use App\Models\Category;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts/app')]

class Categories extends Component
{
    public $name = '';
    public $status = true;

    public function save()
    {
        $this->validate([

            'name' => 'required|min:3',
        ]);

        Category::create([

            'name' => $this->name,

            'status' => $this->status,
        ]);

        session()->flash(
            'success',
            'Category Created Successfully'
        );

        $this->reset([
            'name'
        ]);

        $this->status = true;
    }

    public function render()
    {
        $categories = Category::latest()->get();

        return view(
            'livewire.admin.categories',
            [
                'categories' => $categories
            ]
        );
    }
}
