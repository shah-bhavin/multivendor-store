@php
    // Change 'SEO' to 'SEOTools'
    use Artesaos\SEOTools\Facades\SEOTools;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    {!! SEO::generate() !!}
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
                <div class="flex gap-3">

                    <a
                        href="{{ route(

                            'language.switch',

                            'en'

                        ) }}"

                        class="text-sm">

                        EN

                    </a>

                    <a
                        href="{{ route(

                            'language.switch',

                            'gu'

                        ) }}"

                        class="text-sm">

                        ગુજરાતી

                    </a>

                    <a
                        href="{{ route(

                            'language.switch',

                            'hi'

                        ) }}"

                        class="text-sm">

                        हिंदी

                    </a>

                </div>

                <div class="flex gap-3">

                    <a
                        href="{{ route(

                            'currency.switch',

                            'INR'

                        ) }}">

                        ₹ INR

                    </a>

                    <a
                        href="{{ route(

                            'currency.switch',

                            'USD'

                        ) }}">

                        $ USD

                    </a>

                    <a
                        href="{{ route(

                            'currency.switch',

                            'EUR'

                        ) }}">

                        € EUR

                    </a>

                </div>

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

                <a href="/my-chats">

                    Chats

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