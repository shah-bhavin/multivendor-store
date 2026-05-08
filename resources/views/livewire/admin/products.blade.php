<div class="p-6">

    <h1 class="text-3xl font-bold mb-5">
        Product Management
    </h1>

    @if(session()->has('success'))

        <div class="bg-green-500 text-white p-3 mb-5">

            {{ session('success') }}

        </div>

    @endif

    <form wire:submit="save" class="mb-10">

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
            Save Product
        </button>

    </form>

    <hr class="my-5">

    <h2 class="text-2xl font-bold mb-5">
        Product List
    </h2>

    <table class="w-full border">

        <thead>

            <tr class="bg-gray-200">

                <th class="border p-2">ID</th>

                <th class="border p-2">Image</th>

                <th class="border p-2">Name</th>

                <th class="border p-2">Price</th>

                <th class="border p-2">Stock</th>

            </tr>

        </thead>

        <tbody>

            @forelse($products as $product)

                <tr>

                    <td class="border p-2">

                        {{ $product->id }}

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

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="p-5 text-center">

                        No Products Found

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>