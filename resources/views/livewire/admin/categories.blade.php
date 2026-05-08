<div class="p-6">

    <h1 class="text-3xl font-bold mb-5">
        Categories
    </h1>

    @if(session()->has('success'))

        <div class="bg-green-500 text-white p-3 mb-5">

            {{ session('success') }}

        </div>

    @endif

    <form
        wire:submit="save"
        class="border p-5 mb-10"
    >

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

        <button
            type="submit"
            class="bg-blue-500 text-white px-5 py-2"
        >
            Save Category
        </button>

    </form>

    <table class="w-full border">

        <thead>

            <tr class="bg-gray-200">

                <th class="border p-2">
                    ID
                </th>

                <th class="border p-2">
                    Name
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse($categories as $category)

                <tr>

                    <td class="border p-2">

                        {{ $category->id }}

                    </td>

                    <td class="border p-2">

                        {{ $category->name }}

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="2"
                        class="text-center p-5">

                        No Categories Found

                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>