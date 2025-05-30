<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Cookie;
use App\Models\Product;

//namespace Helpers;

class CartManagement
{

// menambahkan data ke cookie 
public static function addCartItemsToCookie($cartItems)
{
    Cookie :: queue('cart_items',json_encode($cartItems),
    60*24*30);
}

// menghapus data cart item 
public static function clearCartItems()
{
    Cookie :: forget('cart_items');
}

// mengambil data item cookie

public static function  getCartItemsFromCookie()
{
    $cartItems = json_decode(Cookie :: get ('cart_items'),true);
    if (!$cartItems) {
        $cartItems = [];
    }
    return $cartItems;
}

// menambah item ke cart 
public static function addItemToCart($product_id)
{
    $cartItems = self :: getCartItemsFromCookie();
    $existingItem= null;

    foreach ($cartItems as $key => $item){
        if ($item['product_id']== $product_id) {
            # code...
            $existingItem =$key;
            break;
        }
    }

    if ($existingItem !== null) {
        $cartItems[$existingItem]['quantity']++;
        $cartItems[$existingItem]['total_amount'] = $cartItems[$existingItem]['quantity'] * $cartItems[$existingItem]['unit_amount'];
    } else {
        $product = Product::where('id', $product_id)->first(['id', 'name', 'price', 'images']);
        if ($product) {
            $cartItems[] = [
                'product_id' => $product_id,
                'name' => $product->name,
                'image' => $product->images[0],
                'quantity' => 1,
                'unit_amount' => $product->price,
                'total_amount' => $product->price,
            ];
        }
    }

    self::addCartItemsToCookie($cartItems);
    return count($cartItems);
}

public static function addItemToCartWithQty($product_id,$qty = 1)
{
    $cartItems = self :: getCartItemsFromCookie();
    $existingItem = null ;

    foreach($cartItems as $key => $item){
        if ($item['product_id']==$product_id) {
            # code...
            $existingItem =$key;
            break;
        }
    }
    if ($existingItem !== null) {
        # code...
        $cartItems[$existingItem]['quantity']=$qty;
        $cartItems[$existingItem]['total_amount']= $cartItems[$existingItem]
        ['quantity']*$cartItems[$existingItem]['unit_amount'];
    } else{
        $product = Product :: where('id', $product_id)->first(['id','name','price','images']);
        if ($product) {
            # code...
            $cartItems[] =[
                'product_id'=> $product_id,
                'name' => $product->name,
                'image'=>$product->images[0],
                'quantity'=>$qty,
                'unit_amount'=> $product->price,
                'total_amount' => $product->price * $qty,
            ];
        }
    }
    self :: addCartItemsToCookie($cartItems);
    return count($cartItems);
}


// menghapus card item      
public static function removeCartItem($product_id)
{
    $cartItems = self::getCartItemsFromCookie();
    foreach ($cartItems as $key => $item) {
        if ($item['product_id'] == $product_id) {
            unset($cartItems[$key]);
            break;
        }
    }
    self::addCartItemsToCookie($cartItems);
    return $cartItems;
}

// melakukukan increment quabtyty item
public static function incrementQuantityToCartItem($product_id)
{
    $cartItems = self::getCartItemsFromCookie();
    foreach ($cartItems as $key => $item) {
        if ($item['product_id'] == $product_id) {
            $cartItems[$key]['quantity']++;
            $cartItems[$key]['total_amount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_amount'];
            break;
        }
    }
    self::addCartItemsToCookie($cartItems);
    return $cartItems;
}

// melakukan decrement quantity item 
public static function decrementQuantityToCartItem($product_id)
{
    $cartItems = self::getCartItemsFromCookie();
    foreach ($cartItems as $key => $item) {
        if ($item['product_id'] == $product_id && $cartItems[$key]['quantity'] > 1) {
            $cartItems[$key]['quantity']--;
            $cartItems[$key]['total_amount'] = $cartItems[$key]['quantity'] * $cartItems[$key]['unit_amount'];
            break;
        }
    }
    self::addCartItemsToCookie($cartItems);
    return $cartItems;
}

// melakukan kalkulasi  total items
public static function calculateGrandTotal($items)
{
    return array_sum(array_column($items, 'total_amount'));
}
}