<?php

namespace Database\Seeders;

use App\Models\PublicResource;
use Illuminate\Database\Seeder;

class PublicResourceSeeder extends Seeder
{
    public function run(): void
    {
        $resources = [
            [
                'title' => 'دليل المبتدئين في التعلم الإلكتروني',
                'description' => 'دليل شامل يشرح كيفية الاستفادة من المنصة، تنظيم الوقت، ومتابعة الكورسات بفعالية.',
                'type' => 'link',
                'url' => 'https://developer.mozilla.org/ar/docs/Learn',
                'sort_order' => 1,
            ],
            [
                'title' => 'قوالب تخطيط المشاريع التعليمية',
                'description' => 'مجموعة قوالب جاهزة لتخطيط مشاريعك الدراسية والتطبيقية.',
                'type' => 'link',
                'url' => 'https://www.notion.so/templates',
                'sort_order' => 2,
            ],
            [
                'title' => 'مصادر إضافية للمبرمجين',
                'description' => 'روابط مرجعية لأهم المصادر التعليمية في البرمجة والتطوير.',
                'type' => 'link',
                'url' => 'https://github.com/',
                'sort_order' => 3,
            ],
        ];

        foreach ($resources as $data) {
            PublicResource::updateOrCreate(
                ['slug' => PublicResource::generateUniqueSlug($data['title'])],
                array_merge($data, ['is_published' => true])
            );
        }
    }
}
