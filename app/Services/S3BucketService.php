<?php

namespace App\Services;

use Aws\S3\S3Client;
use Exception;

class S3BucketService
{
    protected $s3;

    public function __construct()
    {
        $this->s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    // 1️⃣ Create a New S3 Bucket
    public function createBucket($bucketName)
    {
        try {
            $this->s3->createBucket(['Bucket' => $bucketName]);
            $this->s3->waitUntil('BucketExists', ['Bucket' => $bucketName]);
            return "Bucket '$bucketName' created successfully!";
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // 2️⃣ Check if a Bucket Exists
    public function checkBucketExists($bucketName)
    {
        try {
            $this->s3->headBucket(['Bucket' => $bucketName]);
            return "Bucket '$bucketName' exists!";
        } catch (Exception $e) {
            return "Bucket '$bucketName' does not exist.";
        }
    }

    // 3️⃣ List All S3 Buckets
    public function listBuckets()
    {
        try {
            $result = $this->s3->listBuckets();
            return $result['Buckets'];
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }

    // 4️⃣ Delete an S3 Bucket (Must be Empty)
    public function deleteBucket($bucketName)
    {
        try {
            // Ensure the bucket is empty before deleting
            $objects = $this->s3->listObjects(['Bucket' => $bucketName]);
            if (!empty($objects['Contents'])) {
                foreach ($objects['Contents'] as $object) {
                    $this->s3->deleteObject([
                        'Bucket' => $bucketName,
                        'Key'    => $object['Key'],
                    ]);
                }
            }

            // Delete the bucket
            $this->s3->deleteBucket(['Bucket' => $bucketName]);
            return "Bucket '$bucketName' deleted successfully!";
        } catch (Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
}
