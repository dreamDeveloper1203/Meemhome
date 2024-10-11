<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Item;
use App\Models\Review;
use App\Models\Settings;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{

    /**
     * Show home page.
     *
     * @return \Illuminate\View\View
     */
    public function show(): View
    {

        // $discountProducts = Cache::rememberForever(Item::DISCOUNT_ITEMS_CACHE_KEY, function () {
        //     return Item::whereHas('category', function ($query) {
        //         return $query->website()->active();
        //     })->website()->active()->where('discount', '>', 0)->with('category')->take(8)->get();
        // });
        
        $parentCategories = Category::where('parent_id', Null)->get();

        // $childCategories = Category::whereNotNull('parent_id')->get()->toArray();
        
        $childCategories = \DB::select('SELECT * FROM categories WHERE parent_id IS NOT NULL');
        // shuffle($childCategories);
        
        
        $banners = Banner::active()->orderBy('sort_order', 'ASC')->latest()->get();
        
        $latestProducts = Item::whereHas('category', function ($query) {
                return $query->website()->active();
            })
                ->MainProduct()
                ->website()->active()->with('category')->latest()->take(8)->get();
        // $latestProducts = Cache::rememberForever(Item::LATEST_ITEMS_CACHE_KEY, function () {
        //     return Item::whereHas('category', function ($query) {
        //         return $query->website()->active();
        //     })
        //         ->MainProduct()
        //         ->website()->active()->with('category')->latest()->take(8)->get();
        // });


        // $randomProducts = Cache::rememberForever(Item::RANDOM_ITEMS_CACHE_KEY, function () {
        //     return Item::whereHas('category', function ($query) {
        //         return $query->website()->active();
        //     })->website()->active()->with('category')->inRandomOrder()->take(8)->get();
        // });


        
        // Cache::rememberForever(Banner::CACHE_KEY, function () {
        //     return Banner::active()->orderBy('sort_order', 'ASC')->latest()->get();
        // });

        return view('home.show', [
            'global_alert' => Settings::getGlobalAlertValue(),
            // 'discountProducts' => $discountProducts,
            'latestProducts' => $latestProducts,
            // 'randomProducts' => $randomProducts,
            'banners' => $banners,
            'parentCategories' => $parentCategories,
            'childCategories' => $childCategories
        ]);
    }

    /**
     * Show item page.
     *
     * @param string $slug
     *
     * @return \Illuminate\View\View
     */

    // public function showItem(string $id): View
    // {
    //     $item = Cache::remember("item-{$id}", Item::CACHE_TTL, function () use ($id) {
    //         return Item::whereHas('category', function ($query) {
    //             return $query->website()->active();
    //         })->with(['category', 'additional_images'])->website()->where("id", $id)->firstOrFail();
    //     });
    //     $reviews = Review::where('item_id', $item->id)->with('user')->latest()->paginate(10);

    //     $key = "item-{$item->id}";
    //     if (!session($key)) {
    //         $item->views += 1;
    //         $item->save();
    //         session([$key => 1]);
    //     }
    //     return view('home.item', [
    //         'item' => $item,
    //         'reviews' => $reviews,
    //     ]);
    // }

    // public function showItem(Request $request, string $id): View
    // {
    //     $item = Item::findOrFail($id);

    //     $mainProductId = $item->parent_id ? $item->parent_id : $item->id;

    //     $mainItem = Item::findOrFail($mainProductId);

    //     $key = "item-{$mainItem->id}";

    //     if (!session($key)) {
    //         $mainItem->increment('views');
    //         session([$key => 1]);
    //     }

    //     $item = Cache::remember("item-{$mainProductId}", Item::CACHE_TTL, function () use ($mainProductId) {
    //         return Item::whereHas('category', function ($query) {
    //             $query->website()->active();
    //         })->with(['category', 'additional_images', 'variants', 'siblings'])
    //             ->website()
    //             ->findOrFail($mainProductId);
    //     });

    //     if (!session($key)) {
    //         $item->increment('views');
    //         session([$key => 1]);
    //     }

    //     $matchedProduct = $item;
    //     if ($request->filled('color') && $request->filled('size')) {
    //         $color = $request->input('color');
    //         $size = $request->input('size');
    //         $matchedProduct = $item->variants()->where('color', $color)->where('size', $size)->first()
    //             ?: $item->siblings()->where('color', $color)->where('size', $size)->first()
    //             ?: $item;
    //     }

    //     $sizes = collect([$item->size])->filter()->merge($item->variants->pluck('size'))->merge($item->siblings->pluck('size'))->unique()->toArray();
    //     $colors = collect([$item->color])->filter()->merge($item->variants->pluck('color'))->merge($item->siblings->pluck('color'))->unique()->toArray();

    //     $matchedProduct->sizes = array_values(array_unique($sizes));
    //     $matchedProduct->colors = array_values(array_unique($colors));

    //     $reviews = Review::where('item_id', $mainProductId)->with('user')->latest()->paginate(10);
    //     return view('home.item', [
    //         'item' => $matchedProduct,
    //         'reviews' => $reviews,
    //     ]);
    // }

public function showItem(Request $request, string $id): View
{
    $item = Item::findOrFail($id);
    $stock = $item->in_stock;
    $mainProductId = $item->parent_id ? $item->parent_id : $item->id;

    $mainItem = Item::findOrFail($mainProductId);

    $key = "item-{$mainItem->id}";

    if (!session($key)) {
        $mainItem->increment('views');
        session([$key => 1]);
    }

    $item = Cache::remember("item-{$mainProductId}", Item::CACHE_TTL, function () use ($mainProductId) {
        return Item::whereHas('category', function ($query) {
            $query->website()->active();
        })->with(['category', 'additional_images', 'variants', 'siblings'])
            ->website()
            ->findOrFail($mainProductId);
    });

    if (!session($key)) {
        $item->increment('views');
        session([$key => 1]);
    }

    $matchedProduct = $item;

    if ($request->filled('color') && !$request->filled('size')) {
        $color = $request->input('color');
        $matchedProduct = $item->variants()->where('color', $color)->first()
            ?: $item->siblings()->where('color', $color)->first()
            ?: $item;
    }

    if ($request->filled('size') && !$request->filled('color')) {
        $size = $request->input('size');
        $matchedProduct = $item->variants()->where('size', $size)->first()
            ?: $item->siblings()->where('size', $size)->first()
            ?: $item;
    }

    if ($request->filled('color') && $request->filled('size')) {
        $color = $request->input('color');
        $size = $request->input('size');
        $matchedProduct = $item->variants()->where('color', $color)->where('size', $size)->first()
            ?: $item->siblings()->where('color', $color)->where('size', $size)->first()
            ?: $item;
    }

    // Remove null values from sizes and colors
    $sizes = collect([$item->size])
        ->merge($item->variants->pluck('size'))
        ->merge($item->siblings->pluck('size'))
        ->filter()
        ->unique()
        ->toArray();

    $colors = collect([$item->color])
        ->merge($item->variants->pluck('color'))
        ->merge($item->siblings->pluck('color'))
        ->filter()
        ->unique()
        ->toArray();

    $matchedProduct->sizes = array_values($sizes);
    $matchedProduct->colors = array_values($colors);

    $reviews = Review::where('item_id', $mainProductId)->with('user')->latest()->paginate(10);
    $matchedProduct->in_stock = $stock;
    return view('home.item', [
        'item' => $matchedProduct,
        'reviews' => $reviews,
    ]);
}


    /**
     * Show home page.
     *
     * @return \Illuminate\View\View
     */
    public function test(): View
    {

        // $discountProducts = Cache::rememberForever(Item::DISCOUNT_ITEMS_CACHE_KEY, function () {
        //     return Item::whereHas('category', function ($query) {
        //         return $query->website()->active();
        //     })->website()->active()->where('discount', '>', 0)->with('category')->take(8)->get();
        // });

        $latestProducts = Cache::rememberForever(Item::LATEST_ITEMS_CACHE_KEY, function () {
            return Item::whereHas('category', function ($query) {
                return $query->website()->active();
            })->website()->active()->with('category')->latest()->take(8)->get();
        });


        // $randomProducts = Cache::rememberForever(Item::RANDOM_ITEMS_CACHE_KEY, function () {
        //     return Item::whereHas('category', function ($query) {
        //         return $query->website()->active();
        //     })->website()->active()->with('category')->inRandomOrder()->take(8)->get();
        // });


        $banners = Cache::rememberForever(Banner::CACHE_KEY, function () {
            return Banner::active()->orderBy('sort_order', 'ASC')->latest()->get();
        });


        $parentCategories = Category::where('parent_id', Null)->get();
        $childCategories = Category::where('parent_id', '!=', Null)->get()->toArray();
        shuffle($childCategories);
        $banners = Cache::rememberForever(Banner::CACHE_KEY, function () {
            return Banner::active()->orderBy('sort_order', 'ASC')->latest()->get();
        });

        return view('home.show', [
            'global_alert' => Settings::getGlobalAlertValue(),
            // 'discountProducts' => $discountProducts,
            'latestProducts' => $latestProducts,
            // 'randomProducts' => $randomProducts,
            'banners' => $banners,
            'parentCategories' => $parentCategories,
            'childCategories' => $childCategories
        ]);
    }
}
