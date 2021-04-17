<?php

namespace App\Traits;
use Illuminate\Support\Str;
use App\Models\Product;

trait GeneralTrait {

    public function uniqueNumber($digits) {
        $data = rand(pow(10, $digits-1), pow(10, $digits)-1);
        return $data;
    }

    public function uniqueSlug($data) {
        $slug = Str::slug($data, '-');
        $query = Product::select('slug')->where('slug', 'like', $slug.'%')->get();
        $count = count($query);
        if($count > 0) {
            $data = [];
            foreach($query as $row) {
                $data[] = $row->slug;
            }
            if(in_array($slug, $data)) {
                $i = 0;
                while(in_array(($slug) . '-' . (++$i), $data));
                $slug = ($slug) . '-' . ($i);
            }
        }
        return $slug;
    }
}