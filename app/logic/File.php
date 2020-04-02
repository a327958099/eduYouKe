<?php
namespace app\logic;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use AlibabaCloud\Vod\Vod;
use OSS\Core\OssException;
use OSS\OssClient;
use app\util\Vod as vodTool;

class File
{

	public function __construct()
    {
        $this->accessKeyId = 'LTAImHXukA9AUfAc';
        $this->accessKeySecret = 'aHdnjeI2OYhSVhLaeOrisq0GmibgMD';
        $this->regionId = 'cn-shanghai';
        $this->Client = 'Client';
        $this->initVodClient($this->accessKeyId,$this->accessKeySecret);
    }

    //
    public function initVodClient($accessKeyId,$accessKeySecret)
    {
    	return AlibabaCloud::accessKeyClient($accessKeyId, $accessKeySecret)
                ->regionId($this->regionId)
                ->connectTimeout(1)
                ->timeout(3)
                ->name($this->Client);

    }


       //
    public function uploadVideo($file)
    {	
        
    	$auth = $this->CreateUploadVideoAuth('测试',$file->getOriginalName());

    	$uploadAuth = json_decode(base64_decode($auth['UploadAuth']), true);
        $UploadAddress = json_decode(base64_decode($auth['UploadAddress']), true);
    	try {

            $ossClient = new OssClient(
                $uploadAuth['AccessKeyId'],
                $uploadAuth['AccessKeySecret'],
                $UploadAddress['Endpoint'],
                false,
                $uploadAuth['SecurityToken']
            );

            $ossClient->setTimeout(86400);
            $ossClient->setConnectTimeout(3);
            $ossClient->setUseSSL(false);

            $result = $ossClient->uploadFile($UploadAddress['Bucket'], $UploadAddress['FileName'], $file->getPathName());

            return $auth['VideoId'];
             
		} catch (OssException $e) {
			print $e->getMessage();
		}
    }



    public function CreateUploadVideoAuth($title,$fileName)
    {

		 $auth = Vod::v20170321()->createUploadVideo()->client($this->Client)
	    	->withTitle($title)
	        ->withFileName($fileName)
	        ->format('JSON')
	        ->request();  
	    return $auth->toArray();
    }



}