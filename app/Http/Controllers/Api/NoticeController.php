<?php

namespace App\Http\Controllers\Api;

use Redis;
use App\Services\Markdowner;
use Illuminate\Http\Request;

class NoticeController extends ApiController
{
    protected $key = 'notice';

    public function index()
    {
        if(Redis::exists($this->key)) {
          $content = Redis::get($this->key);
          return (new Markdowner)->convertHtmlToMarkdown($content);
        }
    }

    public function store(Request $request)
    {
        $key = 'notice';

        Redis::set($key, (new Markdowner)->convertMarkdownToHtml($request->content));

        return $this->noContent();
    }
}
