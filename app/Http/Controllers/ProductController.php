<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllFeatureProducts() {
        $data = Product::select('products.code as pd_code', 
                                'products.slug as pd_slug',
                                'products.name as pd_name', 
                                'products.thumbnail as pd_thumbnail', 
                                'products.main_price as pd_main_price', 
                                'products.offer_price as pd_offer_price', 
                                'brands.name as brand_name as pd_brand_name', 
                                'types.name as type_name as pd_type_name', 
                                'sub_types.name as sub_type_name as pd_sub_type_name'
                            )
                        ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                        ->leftJoin('types', 'types.id', '=', 'products.type_id')
                        ->leftJoin('sub_types', 'sub_types.id', '=', 'products.sub_type_id')
                        ->where('products.status', 'published')
                        ->where('products.is_feature', 'yes')
                        ->orderByDesc('products.created_at')
                        ->get();

        return $this->getResponse(200, 'success', 'Feature Product', $data);
    }
}
