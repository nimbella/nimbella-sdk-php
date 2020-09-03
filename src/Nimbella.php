<?php

/**
 * Copyright (c) 2020-present, Nimbella, Inc.
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Nimbella;

use \Google\Cloud\Storage\StorageClient;
use \Google\Cloud\Storage\Bucket;

class Nimbella
{
  public function redis(): \Predis\Client
  {
    $redisIP = $_ENV['__NIM_REDIS_IP'];
    $redisPassword = $_ENV['__NIM_REDIS_PASSWORD'];
    if ($redisIP && $redisPassword) {
      $client = new \Predis\Client([
        'scheme' => 'tcp',
        'host'   => $redisIP,
        'port'   => 6379,
      ]);
      $client->auth($redisPassword);
      return $client;
    } else {
      throw new \Exception('Key-Value store is not available.');
    }
  }

  public function storage(): Bucket
  {
    $creds = $_ENV['__NIM_STORAGE_KEY'];
    if (!$creds || strlen($creds) == 0) {
      throw new \Exception('Objectstore credentials are not available');
    }

    $namespace = $_ENV['__OW_NAMESPACE'];
    $apiHost = $_ENV['__OW_API_HOST'];
    if (!$namespace || !$apiHost) {
      throw new \Exception('Not enough information in the environment to determine the object store bucket name');
    }

    $hostname = str_replace('https://', '', $apiHost);
    $bucket = 'data-' . $namespace . '-' . str_replace('.', '-', $hostname);

    try {
      $parsedCreds = json_decode($creds, true);
      $project_id = $parsedCreds['project_id'];
      $storageOptions = [
        'projectId' => $project_id,
        'keyFile' => $parsedCreds
      ];

      $storage = new StorageClient($storageOptions);
      return $storage->bucket($bucket);
    } catch (\Exception $e) {
    }

    throw new \Exception('Insufficient information in provided credentials or credentials were invalid');
  }
}
