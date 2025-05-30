<?php

namespace App\Livewire;
use Jorenvh\Share\LivewireAlert;
use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;
use App\Helpers\CartManagement;

class ProductDetailPage extends Component
{
        
    public $product;
    public $quantity = 1;
    #[Title('product-detail')]
    public $slug;

    public function mount($slug){
        $this->slug= $slug;
        $this->product = Product::where('slug', $slug)->where('is_active', 1)->first();

        if (!$this->product) {
            abort(404); // Jika tidak ditemukan, tampilkan 404
        }
    } 

    public function increaseQty()
    {
        $this->quantity++;

    }

    public function decreaseQty()
    {
        if ($this->quantity>1){
            $this->quantity--;
        }
    }
    
    public function render()
    {
        $product = Product::where('slug',$this->slug)->firstOrFail();
        return view('livewire.product-detail-page',[
        'product'=> $product,
    
    ]);
    }

    
    public function addToCart($product_id)
    {
        $total_count = CartManagement::addItemToCartWithQty($product_id, $this->quantity);

        // Update komponen Navbar
        $this->dispatch('update-cart-count', total_count: $total_count)
             ->to(\App\Livewire\Partials\Navbar::class);

        // Kirim event untuk tampilkan SweetAlert
        // $this->alert('success', 'Product added to the cart successfully', [
        //     'position' => 'bottom-end',
        //     'timer' => 3000,
        //     'toast' => true,
        // ]);
    }
    
}
