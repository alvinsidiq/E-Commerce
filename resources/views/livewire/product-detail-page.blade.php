<div>
  <div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <section class="overflow-hidden bg-white py-11 font-poppins dark:bg-gray-800">
      <div class="max-w-6xl px-4 py-4 mx-auto lg:py-8 md:px-6">

        @if ($product)
        <div class="flex flex-wrap -mx-4">
          <div class="w-full mb-8 md:w-1/2 md:mb-0"
               x-data="{ mainImage: '{{ asset('storage/' . ($product->images[0] ?? 'default.jpg')) }}' }">
            <div class="sticky top-0 z-50 overflow-hidden ">
              <div class="relative mb-6 lg:mb-10 lg:h-2/4 ">
                <img :src="mainImage" alt="{{ $product->name ?? 'Product' }}"
                     class="object-cover w-full lg:h-full transition-all duration-300">
              </div>

              <div class="flex-wrap hidden md:flex ">
                @if (!empty($product->images) && is_array($product->images))
                  @foreach ($product->images as $image)
                    <div class="w-1/2 p-2 sm:w-1/4"
                         @click="mainImage='{{ asset('storage/' . $image) }}'">
                      <img src="{{ asset('storage/' . $image) }}"
                           alt="{{ $product->name ?? 'Product' }}"
                           class="object-cover w-full lg:h-20 cursor-pointer hover:border hover:border-blue-500">
                    </div>
                  @endforeach
                @endif
              </div>

              <div class="px-6 pb-6 mt-6 border-t border-gray-300 dark:border-gray-400 ">
                <div class="flex flex-wrap items-center mt-6">
                  <span class="mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         width="16" height="16" fill="currentColor"
                         class="w-4 h-4 text-gray-700 dark:text-gray-400 bi bi-truck"
                         viewBox="0 0 16 16">
                      <path
                        d="M0 3.5A1.5 1.5 0 0 1 1.5 2h9A1.5 1.5 0 0 1 12 3.5V5h1.02a1.5 1.5 0 0 1 1.17.563l1.481 1.85a1.5 1.5 0 0 1 .329.938V10.5a1.5 1.5 0 0 1-1.5 1.5H14a2 2 0 1 1-4 0H5a2 2 0 1 1-3.998-.085A1.5 1.5 0 0 1 0 10.5v-7z"/>
                    </svg>
                  </span>
                  <h2 class="text-lg font-bold text-gray-700 dark:text-gray-400">Free Shipping</h2>
                </div>
              </div>
            </div>
          </div>

          <div class="w-full px-4 md:w-1/2 ">
            <div class="lg:pl-20">
              <div class="mb-8 ">
                <h1 class="max-w-xl mb-6 text-2xl font-bold dark:text-gray-400 md:text-4xl">
                  {{ $product->name }}
                </h1>

                <p class="inline-block mb-6 text-4xl font-bold text-gray-700 dark:text-gray-400">
                  <span>{{ number_format($product->price, 0, ',', '.') }} INR</span>
                </p>

                <div class="prose list-disc ml-4 text-gray-700 dark:text-gray-400">
                  {!! Str::markdown($product->description) !!}
                </div>
              </div>

              <div class="w-32 mb-8">
                <label class="w-full pb-1 text-xl font-semibold text-gray-700 border-b border-blue-300 dark:border-gray-600 dark:text-gray-400">
                  Quantity
                </label>
                <div class="relative flex flex-row w-full h-10 mt-6 bg-transparent rounded-lg">

                  <button class="w-20 h-full text-gray-600 bg-gray-300 rounded-l outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 hover:text-gray-700 dark:bg-gray-900 hover:bg-gray-400" wire:click="decreaseQty">
                    <span class="m-auto text-2xl font-thin">-</span>
                  </button>

                  <input type="number" readonly
                         class="flex items-center w-full font-semibold text-center text-gray-700 placeholder-gray-700 bg-gray-300 outline-none dark:text-gray-400 dark:placeholder-gray-400 dark:bg-gray-900 focus:outline-none text-md hover:text-black"
                         wire:model="quantity" value="{{ $quantity }}" min="1"
                         placeholder="1">

                  <button class="w-20 h-full text-gray-600 bg-gray-300 rounded-r outline-none cursor-pointer dark:hover:bg-gray-700 dark:text-gray-400 dark:bg-gray-900 hover:text-gray-700 hover:bg-gray-400" wire:click="increaseQty">
                    <span class="m-auto text-2xl font-thin">+</span>
                  </button>
                </div>
              </div>

              <div class="flex flex-wrap items-center gap-4">

                <button wire:click="addToCart({{ $product->id }})" class="w-full p-4 bg-blue-500 rounded-md lg:w-2/5 dark:text-gray-200 text-gray-50 hover:bg-blue-600 dark:bg-blue-500 dark:hover:bg-blue-700" wire:click="addToCart({{ $product->id }})">
                <span wire:loading.remove wire:target="addToCart({{ $product->id }})">Add to Cart</span>
                <span wire:loading wire:target="addToCart({{ $product->id }})">Adding...</span>
                </button>
              </div>
            </div>
          </div>
        </div>
        @else
        <div class="text-center py-10 text-gray-600 dark:text-gray-300">
          <h2 class="text-xl font-semibold">Product not found</h2>
        </div>
        @endif

      </div>
    </section>
  </div>
</div>
