<?php

namespace App\Livewire;

use App\Models\Product;
use Livewire\Attributes\Title;
use Livewire\Component;

class ProductDetailPage extends Component
{
    #[Title('product-detail')]
    public $slug;

    public function mount($slug){
        $this->slug= $slug;
    } 
    
    public function render()
    {
        $product = Product::where('slug',$this->slug)->firstOrFail();
        return view('livewire.product-detail-page',[
        'product'=> $product,
    
    ]);
    }
}
