<?php

namespace App\Livewire;

use App\Helpers\CartManagement;
use Livewire\Attributes\Title;
use Livewire\Component;

class CartPage extends Component
{
    #[Title('Cart-page')]
    public $cart_items =[];
    public $grand_total;

    public function removeItem($product_id)
{
    $this->cart_items = CartManagement::removeCartItem($product_id);
    $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    $this->dispatch('update-cart-count', total_count: count($this->cart_items))->to(\App\Livewire\Partials\Navbar::class);
    // $this->alert('success', 'Item removed successfully', [
    //     'position' => 'bottom-end',
    //     'timer' => 3000,
    //     'toast' => true,
    // ]);
}
public function increaseQty($product_id)
{
    $this->cart_items = CartManagement::incrementQuantityToCartItem($product_id);
    $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
}

public function decreaseQty($product_id)
{
    $this->cart_items = CartManagement::decrementQuantityToCartItem($product_id);
    $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
}
    public function mount()
    {
        $this->cart_items = CartManagement::getCartItemsFromCookie();
        $this->grand_total = CartManagement::calculateGrandTotal($this->cart_items);
    }



    public function render()
    {
        return view('livewire.cart-page');
    }
}
