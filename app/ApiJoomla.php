<?php

namespace App;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;


trait ApiJoomla
{
    public function getLatestArticles(Request $request, int $limit = 5): Collection
    {
        $query = array_filter([
            'page' => array_filter([
                'offset' => $request->integer('page', 1),
                'limit'  => $limit,
            ]),
            'filter' => array_filter([
                'category' => $request->integer('category'),
            ]),
        ]);

        $response = Http::timeout(15)
            ->accept('application/vnd.api+json')
            ->withHeaders([
                'X-Joomla-Token' => config('services.joomla.token'),
            ])
            ->get(rtrim(config('services.joomla.url'), '/') . '/content/articles', $query);


        if ($response->failed()) {
            Log::error('Joomla API Error', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return collect();
        }

        $data = $response->json();
        return collect($data['data'] ?? [])
            ->take($limit)
            ->map(function ($item) {
                $attr = $item['attributes'] ?? [];

                return [
                    'id'      => $item['id'] ?? null,
                    'title'   => $attr['title'] ?? '',
                    'alias'   => $attr['alias'] ?? '',
                    'created' => isset($attr['created']) ? date('d F Y', strtotime($attr['created'])) : '',
                    'image'   => $attr['images']['image_intro'] ?? $attr['images']['image_fulltext'] ?? '',
                    'text'    => strip_tags($attr['text'] ?? ''),
                    'category_id' => $item['relationships']['category']['data']['id'] ?? null,
                    'url' => "https://kemenaggresik.id/component/content/article/" . $attr['alias'] . "?catid=" . $item['relationships']['category']['data']['id'] . "&Itemid=" . $item['id'],
                ];
            })
            ->values();
    }
}
