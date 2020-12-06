<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use Aws\Sts\StsClient;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadController extends Controller
{
    //
    public function index(){
        return view("upload.index");
    }

    public function upload(Request $request){
        /**
         * @var UploadedFile $file
         */
        $file=$request->file("file");
        $file->move(storage_path("uploads"), $file->getClientOriginalName());
        echo test();
        dd($file);

    }

    public function uploadToS3()
    {
        $role="arn:aws:iam::154713672373:role/s3-role";
        $bucketName="demo-hungnv";
        $client = new StsClient([
            'profile' => 'default',
            'region' => 'us-west-2',
            'version' => '2011-06-15'
        ]);

        $result = $client->assumeRole([
            'RoleArn' => $role,
            'RoleSessionName' => 'session1'
        ]);

        $s3Client = new S3Client([
            'version'     => '2006-03-01',
            'region'      => 'us-west-2',
            'credentials' =>  [
                'key'    => $result['Credentials']['AccessKeyId'],
                'secret' => $result['Credentials']['SecretAccessKey'],
                'token'  => $result['Credentials']['SessionToken']
            ]
        ]);

        $s3Client->putObject([
            'Bucket' => $bucketName,
            'Key'    => 'my-object',
            'Body'   => fopen(storage_path("temp/decorate.png"), 'r'),
            'ACL'    => 'public-read',
        ]);

    }
}
