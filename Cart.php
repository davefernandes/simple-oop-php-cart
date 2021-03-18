<?php

class Cart
{
    private $items = array();

    /**
     * @return CartItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Product   $product
     * @param int       $quantity
     */
    public function addItem(Product $product, int $quantity = 1) 
    {
        $id = $product->getId();

        if( $id === NULL ) {
            throw new Exception('Invalid Product');
            return;
        }

        if(isset($this->items[$id])) {
            $this->updateItem($this->items[$id],$this->items[$id]->getQuantity() + $quantity);
        } else {
            $item = new CartItem($product,$quantity);
            $this->items[$id] = $item;
        }
    }

    /**
     * @param CartItem  $item
     * @param int       $quantity
     */
    public function updateItem(CartItem $item, int $quantity = 1)
    {
        if( $quantity <= 0 ) {
            $this->deleteItem($item->getProduct());
            return;
        }

        $item->setQuantity($quantity);

        return $item;
    }

    /**
     * @param Product $product
     */
    public function deleteItem(Product $product) 
    {
        $id = $product->getId();

        if(!isset($this->items[$id])) {
            throw new Exception('Invalid Cart item');
            return;
        } else {
            unset($this->items[$id]);
        }

        return;
    }

    /**
     * @return float
     */
    public function getTotalAmount()
    {
        $totalAmount = 0;
        foreach($this->items as $item) {
            $totalAmount += $item->getProduct()->getPrice() * $item->getQuantity();
        }

        return $totalAmount;
    }

}