<?php

namespace App\Models;

use App\Traits\HasCoverImage;
use App\Traits\HasIcon;
use App\Traits\HasSalesChannels;
use App\Traits\HasStatus;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasUuid;
    use HasCoverImage;
    use HasFactory;
    use HasStatus;
    use HasSalesChannels;
    use HasIcon;
    use SoftDeletes;

    const CACHE_KEY = "category_items";
    const POS_CACHE_KEY = "pos_category_items";
    const ANDROID_APP_CACHE_KEY = "android_category_items";
    const IOS_APP_CACHE_KEY = "ios_category_items";
    const CACHE_TTL = 60 * 60 * 24;
    const CATALOG_CACHE_KEY = "catalog";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'name',
    'description',
    'sort_order',
    'meta_title',           // Add these if they exist in the database
    'meta_description',
    'keywords',
];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'image_url',
        'cover_image_url',
        'icon_url',
        'select_icon_url',
        'status',
        'url',
        'is_active',
        'is_drafted',
        
    ];

    // protected $with = ['subcategories'];

    protected $casts = [
        'is_active' => 'boolean',
        'pos' => 'boolean',
        'website' => 'boolean',
        'android_app' => 'boolean',
        'ios_app' => 'boolean',
    ];


    /**
     * Override the boot function from Laravel so that
     * we give the model a new UUID when we create it.
     */
    // protected static function boot(): void
    // {
    //     parent::boot();
    //     static::deleted(function ($category) {
    //         $category->items->each->delete();
    //     });
    // }

    protected static function boot(): void
    {
        parent::boot();
        static::created(function ($product) {
            static::logAction('created', $product);
        });

        static::updated(function ($product) {
            static::logAction('updated', $product);
        });

        static::deleted(function ($category) {            
            $category->items->each->delete();
            static::logAction('deleted', $category);
        });
    }

    protected static function logAction(string $action, $category)
    {
        $user = Auth::user();
        $ipAddress = request()->ip();
        $username = $user ? $user->id : 'Guest';

        Log::info("Category {$action}", [
            'category_id' => $category->id,
            'action' => $action,
            'user_id' => $username,
            'ip_address' => $ipAddress,
        ]);
    }
    public function parent_category()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function subcategories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * get status.
     *
     * @return string
     */
    public function getUrlAttribute(): string
    {
        //dd('test1');
        return route("category.show", $this->id);
    }

    public function getDateModifiedAttribute()
    {
        //dd('test2');
        return gmdate('Y-m-d\TH:i:s\Z', strtotime($this->updated_at));
    }


    public function getPageTitleAttribute()
    {
        //dd('test3');
        if (!is_null($this->meta_title)) return $this->meta_title;
        return Str::limit($this->name, 69);
    }

    public function getPageDescriptionAttribute()
    {
        //dd('test4');
        if (!is_null($this->meta_description)) return $this->meta_description;
        return html_entity_decode(strip_tags(Str::limit($this->description, 319)));
    }

    public function getPageKeywordsAttribute()
    {
        //dd('test5');
        if (!is_null($this->keywords)) return $this->keywords;
        return Str::limit(str_replace(' ', ', ', $this->name), 159);
    }
}
