<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vinkla\Vimeo\Facades\Vimeo;

class VimeoController extends Controller
{
    public function index()
    {
        return view('vimeo.home');
    }

    private function getResponseVimeo(array $responseVimeo)
    {
    	return response($responseVimeo['body'], $responseVimeo['status'], $responseVimeo['headers']);
    }

    public function request(Request $request)
    {
        $responseVimeo = Vimeo::request('/me/videos', $request->all(), 'POST');
        return response()->json($responseVimeo['body'], $responseVimeo['status']);
    }

    public function completeUpload(Request $request)
    {
    	$responseVimeo = Vimeo::request($request->get('complete_uri'), [], 'DELETE');
    	return $this->getResponseVimeo($responseVimeo);
    }

    public function updateVideoData(Request $request, $videoId)
    {
    	$responseVimeo = Vimeo::request('/videos/' . $videoId, $request->all(), 'PATCH');
    	return $this->getResponseVimeo($responseVimeo);
    }
}
