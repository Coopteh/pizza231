<?php
namespace Test;

use App\Models\Product;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testPrepareData()
    {
        $storage = new MockStorage();
        $model = new Product($storage, "", "");

        $form_data['fio'] = "";
        $form_data['address'] = "";
        $form_data['phone'] = "";

        $basket_products[] = array( 
            'id' => 100, 
            'name' => 'пицца тестовая', 
            'quantity' => 2,
            'price' => 350,
            'sum' => 700,
        );
        $basket_products[] = array( 
            'id' => 101, 
            'name' => 'пицца тестовая 2', 
            'quantity' => 1,
            'price' => 500,
            'sum' => 500,
        );
        $result = $model->prepareData($form_data, $basket_products);
        $this->assertEquals($result['all_sum'], 1200);
    }
}
