<div>
  <div class="w-full max-w-[85rem] py-10 px-4 sm:px-6 lg:px-8 mx-auto">
    <div class="container mx-auto px-4">
      <h1 class="text-2xl font-semibold mb-4">Shopping Cart</h1>
      <div class="flex flex-col md:flex-row gap-4">
        <!-- Cart Items -->
        <div class="md:w-3/4">
          <div class="bg-white overflow-x-auto rounded-lg shadow-md p-6 mb-4">
            <table class="w-full">
              <thead>
                <tr>
                  <th class="text-left font-semibold">Image</th>
                  <th class="text-left font-semibold">Product</th>
                  <th class="text-left font-semibold">Price</th>
                  <th class="text-left font-semibold">Quantity</th>
                  <th class="text-left font-semibold">Total</th>
                  <th class="text-left font-semibold">Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($cart_items as $item)
                  <tr wire:key="item-{{ $item['product_id'] }}">
                    <td class="py-4">
                      <img src="{{ url('storage', $item['image']) }}" alt="{{ $item['name'] }}" class="h-16 w-16 object-cover">
                    </td>
                    <td class="py-4">{{ $item['name'] }}</td>
                    <td class="py-4">{{ Number::currency($item['unit_amount'], 'INR') }}</td>
                    <td class="py-4">
                      <div class="flex items-center">
                        <button wire:click="decreaseQty({{ $item['product_id'] }})" class="border rounded-md py-2 px-3 mr-2">-</button>
                        <span class="text-center w-8">{{ $item['quantity'] }}</span>
                        <button wire:click="increaseQty({{ $item['product_id'] }})" class="border rounded-md py-2 px-3 ml-2">+</button>
                      </div>
                    </td>
                    <td class="py-4">{{ Number::currency($item['total_amount'], 'INR') }}</td>
                    <td class="py-4">
                      <button wire:click="removeItem({{ $item['product_id'] }})" class="bg-slate-300 border-2 border-slate-400 rounded-lg px-3 py-1 hover:bg-red-500 hover:text-white hover:border-red-700">
                        <span wire:loading.remove wire:target="removeItem({{ $item['product_id'] }})">Remove</span>
                        <span wire:loading wire:target="removeItem({{ $item['product_id'] }})">Removing...</span>
                      </button>
                    </td>
                  </tr>
                @empty
                  <tr>
                    <td colspan="6" class="text-center py-4 text-2xl font-semibold text-slate-500">
                      No items available in cart
                    </td>
                  </tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>

        <!-- Summary Section -->
        <div class="md:w-1/4">
          <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4">Summary</h2>

            @if ($cart_items)
              <div class="flex justify-between mb-2">
                <span>Subtotal</span>
                <span>{{ Number::currency($grand_total, 'INR') }}</span>
              </div>
              <div class="flex justify-between mb-2">
                <span>Taxes</span>
                <span>{{ Number::currency(0, 'INR') }}</span>
              </div>
              <div class="flex justify-between mb-2">
                <span>Shipping</span>
                <span>{{ Number::currency(0, 'INR') }}</span>
              </div>
              <hr class="my-2">
              <div class="flex justify-between mb-2">
                <span class="font-semibold">Total</span>
                <span class="font-semibold">{{ Number::currency($grand_total, 'INR') }}</span>
              </div>
              <button class="bg-blue-500 text-white py-2 px-4 rounded-lg mt-4 w-full">Checkout</button>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
