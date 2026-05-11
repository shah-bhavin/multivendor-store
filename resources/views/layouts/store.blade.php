<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Multi Vendor Store</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="bg-gray-100">

    {{-- Header --}}

    <header class="bg-white shadow">

        <div class="container mx-auto px-5 py-4 flex justify-between">

            <a href="/"
               class="text-2xl font-bold">

                Multi Vendor Store

            </a>

            <nav class="space-x-5">

                <a href="/"
                   class="hover:text-blue-500">

                    Home

                </a>

                <a href="/products"
                   class="hover:text-blue-500">

                    Products

                </a>
                <a href="/cart"
                    class="hover:text-blue-500">

                        Cart

                    </a>

                @auth

                    <a href="/my-orders">

                        My Orders

                    </a>

                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="inline">

                        @csrf

                        <button type="submit">

                            Logout

                        </button>

                    </form>

                @else

                    <a href="/login">

                        Login

                    </a>

                    <a href="/register">

                        Register

                    </a>

                @endauth

                <a href="/wishlist">

                    Wishlist

                </a>
            </nav>

        </div>

    </header>

    {{-- Main Content --}}

    <main class="container mx-auto px-5 py-10">

        {{ $slot }}
         @stack('scripts')

    </main>



</body>

</html>