<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Spatie\Sitemap\Sitemap;

use Spatie\Sitemap\Tags\Url;

use App\Models\Product;

use App\Models\User;

class GenerateSitemap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature =
    'generate:sitemap';

    protected $description =
    'Generate sitemap.xml';

    public function handle()
    {
        $sitemap = Sitemap::create();

        /*
        |--------------------------------------------------------------------------
        | Homepage
        |--------------------------------------------------------------------------
        */

        $sitemap->add(

            Url::create('/')
        );

        /*
        |--------------------------------------------------------------------------
        | Products
        |--------------------------------------------------------------------------
        */

        Product::all()

            ->each(function ($product)
            use ($sitemap) {

                $sitemap->add(

                    Url::create(

                        "/products/{$product->slug}"

                    )
                );
            });

        /*
        |--------------------------------------------------------------------------
        | Vendor Stores
        |--------------------------------------------------------------------------
        */

        User::whereNotNull(

            'store_slug'

        )->get()

            ->each(function ($vendor)
            use ($sitemap) {

                $sitemap->add(

                    Url::create(

                        "/shop/{$vendor->store_slug}"

                    )
                );
            });

        $sitemap->writeToFile(

            public_path(
                'sitemap.xml'
            )
        );

        $this->info(
            'Sitemap generated successfully.'
        );
    }

    //protected $signature = 'app:generate-sitemap';

    /**
     * The console command description.
     *
     * @var string
     */
    //protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    // public function handle()
    // {
    //     //
    // }
}
