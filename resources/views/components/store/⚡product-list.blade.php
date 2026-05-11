<?php
namespace App\Livewire\Store;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Livewire\Attributes\Layout;    

new class extends Component
{
    use WithPagination;
    #[Layout('layouts.store')]
    public $search = '';

    public $category = '';

    public $vendor = '';

    public $minPrice = '';

    public $maxPrice = '';

    public $sort = 'latest';

    protected $queryString = [

        'search',
        'category',
        'vendor',
        'minPrice',
        'maxPrice',
        'sort',
    ];

    public function updating(){
        $this->resetPage();
    }

    public function with(): array
    {
        $query = Product::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($this->search) {

            $query->where(

                'name',

                'like',

                '%' . $this->search . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Category Filter
        |--------------------------------------------------------------------------
        */

        if ($this->category) {

            $query->where(
                'category_id',
                $this->category
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Vendor Filter
        |--------------------------------------------------------------------------
        */

        if ($this->vendor) {

            $query->where(
                'user_id',
                $this->vendor
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Price Filters
        |--------------------------------------------------------------------------
        */

        if ($this->minPrice) {

            $query->where(
                'price',
                '>=',
                $this->minPrice
            );
        }

        if ($this->maxPrice) {

            $query->where(
                'price',
                '<=',
                $this->maxPrice
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        if ($this->sort === 'latest') {

            $query->latest();

        } elseif ($this->sort === 'price_low') {

            $query->orderBy(
                'price',
                'asc'
            );

        } elseif ($this->sort === 'price_high') {

            $query->orderBy(
                'price',
                'desc'
            );
        }

        $products = $query->paginate(12);

        return [

                'products' => $products,

                'categories' =>
                    Category::all(),

                'vendors' =>
                    User::role('vendor')->get(),
            ];

        
    }
};
?>

<div>

    <h1 class="text-4xl font-bold mb-10">

        All Products

    </h1>

    {{-- FILTERS --}}

    <div class="bg-white p-5 rounded shadow mb-10">

        <div
            class="grid grid-cols-1 md:grid-cols-6 gap-4">

            {{-- Search --}}

            <input
                type="text"
                wire:model.live="search"
                placeholder="Search products..."
                class="border p-3 rounded"
            >

            {{-- Category Filter --}}

            <select
                wire:model.live="category"
                class="border p-3 rounded">

                <option value="">
                    All Categories
                </option>

                @foreach($categories as $cat)

                    <option value="{{ $cat->id }}">

                        {{ $cat->name }}

                    </option>

                @endforeach

            </select>

            {{-- Vendor Filter --}}

            <select
                wire:model.live="vendor"
                class="border p-3 rounded">

                <option value="">
                    All Vendors
                </option>

                @foreach($vendors as $vendor)

                    <option value="{{ $vendor->id }}">

                        {{ $vendor->name }}

                    </option>

                @endforeach

            </select>

            {{-- Min Price --}}

            <input
                type="number"
                wire:model.live="minPrice"
                placeholder="Min Price"
                class="border p-3 rounded"
            >

            {{-- Max Price --}}

            <input
                type="number"
                wire:model.live="maxPrice"
                placeholder="Max Price"
                class="border p-3 rounded"
            >

            {{-- Sorting --}}

            <select
                wire:model.live="sort"
                class="border p-3 rounded">

                <option value="latest">

                    Latest

                </option>

                <option value="price_low">

                    Price Low To High

                </option>

                <option value="price_high">

                    Price High To Low

                </option>

            </select>

            <p class="mb-5 text-gray-600">

                Showing
                {{ $products->total() }}
                products

            </p>

        </div>

    </div>

    {{-- PRODUCTS GRID --}}

    <div
        class="grid grid-cols-1 md:grid-cols-4 gap-6">

        @forelse($products as $product)

            <div
                class="bg-white rounded shadow p-4">

                @if(
                    $product->getFirstMediaUrl(
                        'products'
                    )
                )

                    <img
                        src="{{
                            $product
                                ->getFirstMediaUrl(
                                    'products'
                                )
                        }}"
                        class="w-full h-52 object-cover mb-3 rounded"
                    >

                @endif

                <h2
                    class="text-xl font-bold">

                    {{ $product->name }}

                </h2>

                <p
                    class="text-gray-500 mb-2">

                    {{
                        $product->category?->name
                    }}

                </p>

                <p
                    class="text-sm text-gray-400 mb-2">

                    Vendor:
                    {{
                        $product->user?->name
                    }}

                </p>

                <p
                    class="text-lg font-bold mb-3">

                    ₹{{ $product->price }}

                </p>

                <a
                    href="/products/{{ $product->id }}"
                    class="bg-blue-500 text-white px-4 py-2 inline-block">

                    View Product

                </a>

            </div>

        @empty

            <div
                class="col-span-4 bg-white p-10 rounded shadow text-center">

                No products found

            </div>

        @endforelse

    </div>

    {{-- PAGINATION --}}

    <div class="mt-10">

        {{ $products->links() }}

    </div>

</div>