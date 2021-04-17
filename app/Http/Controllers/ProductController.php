<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getAllFeatureProducts() {
        $data = Product::select('products.code as code', 
                                'products.slug as slug',
                                'products.name as name', 
                                'products.units as units', 
                                'products.thumbnail as thumbnail', 
                                'products.main_price as main_price', 
                                'products.offer_price as offer_price', 
                                'brands.name as brand_name as brand_name', 
                                'types.name as type_name as type_name', 
                                'sub_types.name as sub_type_name as sub_type_name'
                            )
                        ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                        ->leftJoin('types', 'types.id', '=', 'products.type_id')
                        ->leftJoin('sub_types', 'sub_types.id', '=', 'products.sub_type_id')
                        ->where('products.status', 'published')
                        ->where('products.is_feature', 'yes')
                        ->orderByDesc('products.created_at')
                        ->get();

        return $this->getResponse(200, 'success', 'Feature Products', $data);
    }

    public function processImage($data) {
        $result = $data->map(function($item) {
            return [
                'url' => $item->url,
                'color' => $item->color
            ];
        });

        return $result;
    }

    public function getProductDetails($slug) {
        $product =  Product::with('brand', 'type', 'subType', 'productImages', 'productOptions')
                        ->where('products.status', 'published')
                        ->where('products.slug', $slug)
                        ->first();
        
        $data = array(
            'code' => $product->code,
            'slug' => $product->slug,
            'name' => $product->name,
            'units' => $product->units,
            'thumbnail' => $product->thumbnail,
            'main_price' => $product->main_price,
            'offer_price' => $product->offer_price,
            'brand' => $product->brand->name,
            'type' => $product->type->name,
            'sub_type' => $product->subType->name,
            'images' => $this->processImage($product->productImages),
            'color' => $product->productOptions ? $product->productOptions->color : null,
            'size' => $product->productOptions ? $product->productOptions->size : null,
            'weight' => $product->productOptions ? $product->productOptions->weight: null
        );

        if($data)
            return $this->getResponse(200, 'success', 'Products Data', $data);
        return $this->getResponse(400, 'false', 'No product details found', null);
        
    }

    public function getProductByCategory($category) {
        $data = Product::select('products.code as code', 
                                'products.slug as slug',
                                'products.name as name',
                                'products.units as units',
                                'products.thumbnail as thumbnail', 
                                'products.main_price as main_price', 
                                'products.offer_price as offer_price', 
                                'brands.name as brand_name', 
                                'types.name as type_name', 
                                'sub_types.name as sub_type_name'
                            )
                        ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                        ->leftJoin('types', 'types.id', '=', 'products.type_id')
                        ->leftJoin('sub_types', 'sub_types.id', '=', 'products.sub_type_id')
                        ->where('products.status', 'published')
                        ->where('sub_types.name', $category)
                        ->orderByDesc('products.created_at')
                        ->get();
        if(!$data->isEmpty())
            return $this->getResponse(200, 'success', 'Products Data', $data);

        return $this->getResponse(400, 'false', 'No products found', null);
    }
}
