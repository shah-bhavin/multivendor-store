<div class="p-6">

    <h1 class="text-3xl font-bold mb-5">
        Product Management
    </h1>

    @if(session()->has('success'))

        <div class="bg-green-500 text-white p-3 mb-5">

            {{ session('success') }}

        </div>

    @endif

    {{-- Product Form --}}

    <form
        wire:submit="{{ $editMode ? 'update' : 'save' }}"
        class="mb-10 border p-5"
    >

        <h2 class="text-2xl font-bold mb-5">

            {{ $editMode ? 'Edit Product' : 'Create Product' }}

        </h2>
        <div class="mb-4">

    <label>Category</label>

    <select
        wire:model="category_id"
        class="w-full border p-2"
    >

        <option value="">
            Select Category
        </option>

        @foreach($categories as $category)

            <option value="{{ $category->id }}">

                {{ $category->name }}

            </option>

        @endforeach

    </select>

    @error('category_id')

        <div class="text-red-500">

            {{ $message }}

        </div>

    @enderror

</div>
        <div class="mb-4">

            <label>Name</label>

            <input
                type="text"
                wire:model="name"
                class="w-full border p-2"
            >

            @error('name')

                <div class="text-red-500">

                    {{ $message }}

                </div>

            @enderror

        </div>

        <div class="mb-4">

            <label>Description</label>

            <textarea
                wire:model="description"
                class="w-full border p-2"
            ></textarea>

        </div>

        <div class="mb-4">

            <label>Price</label>

            <input
                type="number"
                wire:model="price"
                class="w-full border p-2"
            >

        </div>

        <div class="mb-4">

            <label>Stock</label>

            <input
                type="number"
                wire:model="stock"
                class="w-full border p-2"
            >

        </div>

        <div class="mb-4">

            <label>Image</label>

            <input
                type="file"
                wire:model="image"
            >

        </div>

        <div class="mb-4">

            <label>

                <input
                    type="checkbox"
                    wire:model="status"
                >

                Active

            </label>

        </div>

        <button
            type="submit"
            class="bg-blue-500 text-white px-5 py-2"
        >

            {{ $editMode ? 'Update Product' : 'Save Product' }}

        </button>

        @if($editMode)

            <button
                type="button"
                wire:click="resetForm"
                class="bg-gray-500 text-white px-5 py-2 ml-2"
            >
                Cancel
            </button>

        @endif

    </form>

    {{-- Search --}}

    <div class="mb-5">

        <input
            type="text"
            wire:model.live="search"
            placeholder="Search Product..."
            class="w-full border p-3"
        >

    </div>

    {{-- Product Table --}}

    <table class="w-full border">

        <thead>

            <tr class="bg-gray-200">

                <th class="border p-2">ID</th>

                <th class="border p-2">Category</th>

                <th class="border p-2">Image</th>

                <th class="border p-2">Name</th>

                <th class="border p-2">Price</th>

                <th class="border p-2">Stock</th>

                <th class="border p-2">Actions</th>

            </tr>

        </thead>

        <tbody>

            @forelse($products as $product)

                <tr>

                    <td class="border p-2">

                        {{ $product->id }}

                    </td>

                    <td class="border p-2">

                        {{ $product->category?->name }}

                    </td>

                    <td class="border p-2">

                        @if($product->image)

                            <img
                                src="{{ asset('storage/'.$product->image) }}"
                                width="70"
                            >

                        @endif

                    </td>

                    <td class="border p-2">

                        {{ $product->name }}

                    </td>

                    <td class="border p-2">

                        ₹{{ $product->price }}

                    </td>

                    <td class="border p-2">

                        {{ $product->stock }}

                    </td>

                    <td class="border p-2">

                        <button
                            wire:click="edit({{ $product->id }})"
                            class="bg-yellow-500 text-white px-3 py-1"
                        >
                            Edit
                        </button>

                        <button
                            wire:click="delete({{ $product->id }})"
                            class="bg-red-500 text-white px-3 py-1 ml-2"
                        >
                            Delete
                        </button>

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="6" class="text-center p-5">

                        No Products Found

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

    {{-- Pagination --}}

    <div class="mt-5">

        {{ $products->links() }}

    </div>

</div>