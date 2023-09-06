<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $page = Page::create([
            'page_id'       =>  'TERMS_AND_POLICY',
            'title'         =>  'Terms and Policy',
            'description'   =>  '<div class="row">
                                    <div class="col-12 mb-4">
                                        <h6>Terms of use</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 lh-lg grey-b7b7b7">
                                    <p class="fs-12">Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit.</p>
                                    <p class="fs-12 lh-lg grey-b7b7b7">Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit.</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12 mb-4 mt-4">
                                        <h6>Privacy</h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                    <p class="fs-12 lh-lg grey-b7b7b7">Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit.</p>
                                    <p class="fs-12 lh-lg grey-b7b7b7">Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit. Lorem ipsum dolor site amet aliquon sciscilant aluor inherent adubit.</p>
                                    </div>
                                </div>'  
        ]);
    }
}
