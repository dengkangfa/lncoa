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
     * Get the folder information.
     *
     * @param $folder
     * @return array
     */
    public function folderInfo($folder)
    {
        $folder = $this->cleanFolder($folder);

        $breadcrumbs = $this->breadcrumbs($folder);
        $slice = array_slice($breadcrumbs, -1);
        $folderName = current($slice);
        $breadcrumbs = array_slice($breadcrumbs, 0, -1);

        $subfolders = $this->getSubfolderList($folder);

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
     * Delete the file.
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
     * Delete the folder.
     *
     * @param $folder
     * @return string
     */
    public function deleteFolder($folder)
    {
        $this->cleanFolder($folder);

        $filesFolders = array_merge(
            $this->disk->directories($folder),
            $this->disk->files($folder)
        );

        if (!empty($filesFolders)) {
            throw new UploadException("The directory must be empty to delete it.");
            // throw new UploadException(transe());
        }

        return $this->disk->deleteDirectory($folder);
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
     * Get the breadcrumbs by the folder.
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
     * Get all the subfolders by folder.
     *
     * @param  string $folder
     * @return array
     */
    public function getSubfolderList($folder)
    {
        $subfolders = [];
        foreach (array_unique($this->disk->directories($folder)) as $subfolder) {
            $subfolders["/$subfolder"] = basename($subfolder);
        }

        return $subfolders;
    }

    /**
     * Get all the files by the folder.
     *
     * @param  string $folder
     * @return array
     */
    public function getFileList($folder)
    {
        $files = [];

        $filesContent = $this->disk->files($folder);

        foreach ($filesContent as $file) {
            $files[] = $this->fileDetail($file);
        }

        return $files;
    }

    /**
     * Get the file detail by the path.
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
     * Get the file's webpath by the path.
     *
     * @param $path
     * @return \Illuminate\Contracts\Routing\UrlGenerator|string
     */
    public function fileWebPath($path)
    {
        return asset("storage/" . ltrim($path, '/'));
    }

    /**
     * Get the file's mime type by the path.
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
     * Get the file's size by the path.
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
     * Get the file's last modified time by the path.
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
