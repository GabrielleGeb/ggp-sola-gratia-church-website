<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SermonController extends Controller
{
    public function index(Request $request)
    {
        $apiKey    = env('YOUTUBE_API_KEY');
        $channelId = env('YOUTUBE_CHANNEL_ID');
        $bulan     = $request->get('bulan');
        $page      = (int) $request->get('page', 1);

        // Cache 1 jam biar tidak boros quota YouTube API
        $allVideos = Cache::remember('youtube_videos_' . $channelId, 3600, function () use ($apiKey, $channelId) {
            $videos    = [];
            $pageToken = null;

            do {
                $params = [
                    'key'        => $apiKey,
                    'channelId'  => $channelId,
                    'part'       => 'snippet',
                    'order'      => 'date',
                    'type'       => 'video',
                    'maxResults' => 50,
                ];

                if ($pageToken) {
                    $params['pageToken'] = $pageToken;
                }

                $response  = Http::get('https://www.googleapis.com/youtube/v3/search', $params);
                $data      = $response->json();
                $videos    = array_merge($videos, $data['items'] ?? []);
                $pageToken = $data['nextPageToken'] ?? null;

            } while ($pageToken);

            return $videos;
        });

        $allVideos = collect($allVideos);

        $bulanList = $allVideos->map(fn($v) => substr($v['snippet']['publishedAt'], 0, 7))
            ->unique()->sort()->reverse()->values();

        $filtered = $bulan
            ? $allVideos->filter(fn($v) => str_starts_with($v['snippet']['publishedAt'], $bulan))->values()
            : $allVideos->values();

        $perPage  = 20;
        $total    = $filtered->count();
        $lastPage = max(1, ceil($total / $perPage));
        $page     = min($page, $lastPage);
        $videos   = $filtered->slice(($page - 1) * $perPage, $perPage)->values();

        return view('pages.sermon', compact('videos', 'bulanList', 'bulan', 'page', 'lastPage'));
    }
}