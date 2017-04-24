<?php

namespace App\Services\FileManager;

use Carbon\Carbon;
use App\Exceptions\UploadException;
use Illuminate\Support\Facades\Storage;
use Dflydev\ApacheMimeTypes\PhpRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BaseManager
{
    /**
     * @var $disk
     */
    protected $disk;

    /**
     * @var PhpRepository $mimeDetect
     */
    protected $mimeDetect;

    public function __construct(PhpRepository $mimeDetect)
    {
        $this->disk = Storage::disk(config('filesystems.default', 'public'));

        $this->mimeDetect = $mimeDetect;
    }

    /**
     * 获取文件夹信息。
     *
     * @param $folder
     * @return array
     */
    public function folderInfo($folder)
    {
        $folder = $this->cleanFolder($folder);

        //获取面包屑
        $breadcrumbs = $this->breadcrumbs($folder);
        //获取当前文件夹名称
        $slice = array_slice($breadcrumbs, -1);
        $folderName = current($slice);
        //当前面包屑
        $breadcrumbs = array_slice($breadcrumbs, 0, -1);

        //获取子目录
        $subfolders = $this->getSubfolderList($folder);

        //获取当前文件夹下的文件信息
        $files = $this->getFileList($folder);

        return compact([
            'folder',
            'folderName',
            'breadcrumbs',
            'subfolders',
            'files'
        ]);
    }

    /**
     * 删除文件
     *
     * @param $path
     * @return mixed
     */
    public function deleteFile($path)
    {
        $this->cleanFolder($path);

        return $this->disk->delete($path);
    }

    /**
     * 删除文件夹
     *
     * @param $folder
     * @return string
     */
    public function deleteFolder($folder)
    {
        $this->cleanFolder($folder);

        //合并一个或多个数组
        $filesFolders = array_merge(
            $this->disk->directories($folder),
            $this->disk->files($folder)
        );

        //判断是否为空，如果该目录下存在文件或文件夹，则不允许删除
        if (!empty($filesFolders)) {
            throw new UploadException("The directory must be empty to delete it.");
            // throw new UploadException(transe());
        }

        //删除文件夹
        return $this->disk->deleteDirectory($folder);
    }

    /**
     * 创建一个新文件夹。
     *
     * @param $folder
     * @return string
     */
    public function createFolder($folder)
    {
        $this->cleanFolder($folder);

        //检查文件夹是否已存在
        if ($this->checkFolder($folder)) {
            throw new UploadException("The Folder exists.");
        }

        return $this->disk->makeDirectory($folder);
    }

    /**
     * 检查文件夹是否存在。
     *
     * @param $folder
     * @return mixed
     */
    public function checkFolder($folder)
    {
        return $this->disk->exists($folder);
    }

    /**
     * 检查文件是否存在
     * @param  string $path
     * @return boolean
     */
    public function checkFile($path)
    {
        return $this->disk->exists($path);
    }

    /**
     * 保存文件
     * @param  $path
     * @param  $content
     * @return string
     */
    public function saveFile($path, $content)
    {
        $this->cleanFolder($path);

        return $this->disk->put($path, $content);
    }

    public function renameFile($oldName, $newName)
    {
        return $this->disk->move($oldName, $newName);
    }

    /**
     * Clean the folder.
     *
     * @param $folder
     * @return string
     */
    public function cleanFolder($folder)
    {
        return '/' . trim(str_replace('..', '', $folder), '/'); //eq: ../../uploads
    }

    /**
     * 通过文件夹获取面包屑。
     *
     * @param $folder
     * @return array
     */
    public function breadcrumbs($folder)
    {
        $folder = trim($folder, '/'); //eq: /post_img/2016/10/01/
        $crumbs = ['/' => 'root'];

        if (empty($folder)) return $crumbs;

        $folders = explode('/', $folder); // eq: ['post_img', '2016', '10', '01']
        $build = '';
        foreach ($folders as $folder)
        {
            $build .= '/' . $folder;
            $crumbs[$build] = $folder;
        }

        return $crumbs;
    }

    /**
     * 通过文件夹获取所有子文件夹。
     *
     * @param  string $folder
     * @return array
     */
    public function getSubfolderList($folder)
    {
        $subfolders = [];
        //返回指定目录下的目录数组
        foreach (array_unique($this->disk->directories($folder)) as $subfolder) {
            $subfolders["/$subfolder"] = basename($subfolder);
        }

        return $subfolders;
    }

    /**
     * 获取文件夹中的所有文件。
     *
     * @param  string $folder
     * @return array
     */
    public function getFileList($folder)
    {
        $files = [];

        //返回指定目录下的所有文件数组
        $filesContent = $this->disk->files($folder);

        foreach ($filesContent as $file) {
            $files[] = $this->fileDetail($file);
        }

        return $files;
    }

    /**
     * 通过路径获取文件详细信息。
     *
     * @param $path
     * @return array
     */
    public function fileDetail($path)
    {
        $path = '/' . trim($path, '/');

        return [
            'name' => basename($path),
            'fullPath' => $path,
            'webPath'  => $this->fileWebPath($path),
            'mimeType' => $this->fileMimeType($path),
            'size'     => $this->fileSize($path),
            'modified' => $this->fileModified($path)
        ];
    }

    /**
     * 通过路径获取文件的webpath。
     *
     * @param $path
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function fileWebPath($path)
    {
        //根据当前请求的协议（HTTP 或 HTTPS）生成资源文件的 URL
        return asset("storage/" . ltrim($path, '/'));
    }

    /**
     * 通过路径获取文件的MIME类型。
     *
     * @param $path
     * @return mixed|null|string
     */
    public function fileMimeType($path)
    {
        return $this->mimeDetect->findType(
            pathinfo($path, PATHINFO_EXTENSION)
        );
    }

    /**
     * 通过路径获取文件的大小。
     *
     * @param $path
     * @return mixed
     */
    public function fileSize($path)
    {
        // 自定义函数
        return human_filesize($this->disk->size($path));
    }

    /**
     * 获取目录的大小
     * @param  [type] $path [description]
     * @return [type]       [description]
     */
    public function dirSize($path)
    {
        //返回指定目录下的所有文件数组
        $data = [];
        $result = 0;
        //获取目录以及子目录下所有的文件
        $filesContent = $this->disk->allFiles($path);
        foreach($filesContent as $file){
            //计算所有文件的大小
            $result += $this->disk->size($file);
        }
        $data['digital'] = $result;
        $data['human'] = human_filesize($result);
        return $data;
    }

    /**
     * 通过路径获取文件的最后修改时间。
     *
     * @param $path
     * @return string
     */
    public function fileModified($path)
    {
        return Carbon::createFromTimestamp(
            substr($this->disk->lastModified($path), 0, 10)
        )->toDateTimeString();
    }

    public function store(UploadedFile $file, $dir = '', Closure $callback = null)
    {
        $hashName = str_ireplace('.jpeg', '.jpg', $file->hashName());

        $mime = $file->getMimeType();

        $realname = $this->disk->putFileAs($dir, $file, $hashName);

        return [
              'success' => true,
              'filename' => $hashName,
              'original_name' => $file->getClientOriginalName(),
              'mime' => $mime,
              'size' => $file->getClientSize(),
              'relative_url' => "storage/$realname",
              'url' => asset("storage/$realname"),
        ];
    }
}
