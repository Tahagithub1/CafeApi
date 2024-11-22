<div class="p-6 bg-yellow-50 dark:bg-yellow-900 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-yellow-700 dark:text-yellow-300">Products</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($carts as $cart)
            <div class="bg-yellow-100 dark:bg-yellow-800 rounded-lg shadow-lg p-4 hover:scale-105 transition-transform duration-300">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-yellow-900 dark:text-yellow-200">
                        Table_Number : {{ $cart->table_number }}
                    </h3>
                    <button
                        wire:click="removeCart({{ $cart->id }})"
                        class="bg-red-500 hover:bg-red-600 dark:bg-red-700 dark:hover:bg-red-800
                               text-white px-4 py-2 rounded-full transition-all duration-300
                               transform hover:scale-110 shadow-md hover:shadow-lg">
                        🗑 Remove All
                    </button>
                </div>

                <ul class="space-y-4">
                    @foreach ($cart->items as $item)
                        <li class="flex items-center justify-between border-b border-yellow-300 dark:border-yellow-600 pb-2">
                            <div class="flex items-center">
                                <div>
                                    <p  class="text-sky-400">
                                        {{ $item->product->title }}
                                    </p>
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400">
                                        Quantity: {{ $item->quantity }}
                                    </p>
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400">
                                        Price: {{ $item->product->price }}
                                    </p>
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400">
                                        Total: {{ $item->quantity * $item->product->price }}
                                    </p>
                                </div>
                            </div>
                            <button
                                wire:click="removeItem({{ $item->id }})"
                                class="bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-700 dark:hover:bg-yellow-800
                                       text-white px-4 py-2 rounded-full transition-all duration-300
                                       transform hover:scale-110 shadow-md hover:shadow-lg">
                                ❌ Remove
                            </button>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>
</div>
