<link href="{{ asset('css/output.css') }}" rel="stylesheet">
    <div class="p-6 bg-gray-50 dark:bg-gray-900 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6 text-gray-800 dark:text-gray-200">Orders List</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($carts as $cart)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-4 hover:shadow-xl transition-shadow duration-300">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                            Table Number: {{ $cart->table_number }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            @if($cart->status == 0)
                                Status: <span class="text-yellow-500 font-semibold">Pending</span>
                            @elseif($cart->status == 1)
                                Status: <span class="text-green-500 font-semibold">Completed</span>
                            @endif
                        </p>
                        <button
                            wire:click="removeCart({{ $cart->id }})"
                            class="
                                   text-white px-3 py-1 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md">
                            🗑 Remove All
                        </button>
                    </div>

                    <ul class="space-y-4">
                        @foreach ($cart->items as $item)
                            <li class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-2">
                                <div>
                                    <p class="text-gray-800 dark:text-gray-200 font-semibold">
                                        {{ $item->product->title }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Quantity: {{ $item->quantity }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Price: {{ $item->product->price }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Total: {{ $item->quantity * $item->product->price }}
                                    </p>
                                </div>
                                <button
                                    wire:click="removeItem({{ $item->id }})"
                                    class="
                                           text-white px-3 py-1 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-md">
                                    ❌ Remove
                                </button>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
