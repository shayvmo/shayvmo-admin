<?php


namespace App\Services\Base;


use App\Exceptions\ServiceException;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    protected $config;

    public function __construct()
    {
        $this->config = config('my.upload');
        $this->config['file_size'] *= (1024*1024);
    }

    public function uploadFile(Request $request, array $config = []): array
    {
        if (!empty($config)) {
            $config = array_merge($this->config, $config);
        } else {
            $config = $this->config;
        }

        $group_id = $request->get('attachment_group_id', 0);
        $files = $request->file();
        $file_index = $config['file_index'];
        if (!isset($files[$file_index])) {
            throw new ServiceException("请使用正确名称上传文件");
        }
        if (is_object($files[$file_index])) {
            $files = [
                $file_index => [
                    $files[$file_index]
                ]
            ];
        }
        $files = $files[$file_index];

        array_map(function ($file) use ($config) {

            if (!$file->isValid()) {
                throw new ServiceException($file->getClientOriginalName() . ' 无效的上传文件');
            }

            if ($file->getSize() > $config['file_size']) {
                throw new ServiceException($file->getClientOriginalName() . ' 文件大小超出限制');
            }

            if (!in_array($file->getClientOriginalExtension(), $config['file_ext'])) {
//                throw new ServiceException('文件后缀限制: ' . $file->getClientOriginalExtension());
            }

            if (!in_array($file->getClientMimeType(), $config['file_mime'])) {
//                throw new ServiceException('未允许的文件mime类型: ' . $file->getClientMimeType());
            }

        }, $files);

        $paths = [];

        $disk = Storage::disk($config['storage']);

        $source_file_path = 'files/'.date('Ymd');
        foreach ($files as $file) {
            $file_name = md5_file($file);
            $full_file_path = $source_file_path .'/'. $file_name . '.' . $file->getClientOriginalExtension();
            if ($asset = Attachment::query()->where('md5_file', $file_name)->first()) {
                $paths[] = [
                    'id' => $asset->id,
                    'path' => $asset->path,
                ];
                continue;
            }
            $file_path = $config['storage'] === 'oss' ? $disk->getUrl($full_file_path) : $disk->url($full_file_path);
            if ($disk->has($full_file_path)) {
                $paths[] = $file_path;
                $this->saveAsset(
                    $group_id,
                    $config['storage'],
                    $file->getClientOriginalName(),
                    $file_path,
                    $file_name,
                    $file->getSize(),
                    $file->getClientMimeType()
                );
                continue;
            }
            $disk->putFileAs(
                '',
                $file,
                $full_file_path
            );
//            $paths[] = $file_path;

            $attachment = $this->saveAsset(
                $group_id,
                $config['storage'],
                $file->getClientOriginalName(),
                $file_path,
                $file_name,
                $file->getSize(),
                $file->getClientMimeType()
            );
            $paths[] = [
                'id' => $attachment->id,
                'path' => $attachment->path,
            ];
        }


        return $paths;
    }

    public function uploadImage(Request $request): array
    {
        return $this->uploadFile($request, [
            'file_ext' => [
                'jpg',
                'jpeg',
                'png',
                'gif',
            ],

            'file_mime' => [
                'image/jpeg',
                'image/png',
                'image/gif',
            ],
        ]);
    }

    private function saveAsset(
        int $group_id,
        string $storage_type,
        string $name,
        string $path,
        string $md5_file,
        int $size,
        string $mime_type
    )
    {
        return Attachment::query()->whereNotExists(function($query) use ($md5_file) {
            return $query->where('md5_file', $md5_file);
        })->create([
            'group_id' => $group_id,
            'storage_type' => $storage_type,
            'name' => $name,
            'path' => $path,
            'md5_file' => $md5_file,
            'size' => $size,
            'mime_type' => $mime_type,
        ]);
    }
}
