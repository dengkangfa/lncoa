<?php

namespace App\Http\Controllers\Api;

use Redis;
use App\Services\Markdowner;
use Illuminate\Http\Request;

class NoticeController extends ApiController
{
    //站点内容键
    protected $contentKey = 'lncoa:notice:content';

    //站点是否显示
    protected $enabledKey = 'lncoa:notice:enabled';

    public function index()
    {
        if(Redis::exists($this->contentKey)) {
          $content = Redis::get($this->contentKey);
          //是否显示站点通告
          $enabled = Redis::exists($this->enabledKey)
                      ? Redis::get($this->enabledKey) : true;

          return [
            'content' => (new Markdowner)->convertHtmlToMarkdown($content),
            'enabled' => $enabled
          ];
        }
    }

    /**
     * 新建站点通告
     * @param  Request $request
     * @return null
     */
    public function store(Request $request)
    {
        Redis::set($this->contentKey, (new Markdowner)->convertMarkdownToHtml($request->content));

        return $this->noContent();
    }

    /**
     * 是否显示
     * @param  Request $request
     * @return null
     */
    public function enabled(Request $request)
    {
        Redis::set($this->enabledKey, $request->get('enabled'));

        return $this->noContent();
    }
}
