<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\Document;

class GoogleCloudDocumentaiV1DocumentSchemaMetadata extends \Google\Model
{
  /**
   * @var bool
   */
  public $documentAllowMultipleLabels;
  /**
   * @var bool
   */
  public $documentSplitter;

  /**
   * @param bool
   */
  public function setDocumentAllowMultipleLabels($documentAllowMultipleLabels)
  {
    $this->documentAllowMultipleLabels = $documentAllowMultipleLabels;
  }
  /**
   * @return bool
   */
  public function getDocumentAllowMultipleLabels()
  {
    return $this->documentAllowMultipleLabels;
  }
  /**
   * @param bool
   */
  public function setDocumentSplitter($documentSplitter)
  {
    $this->documentSplitter = $documentSplitter;
  }
  /**
   * @return bool
   */
  public function getDocumentSplitter()
  {
    return $this->documentSplitter;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDocumentaiV1DocumentSchemaMetadata::class, 'Google_Service_Document_GoogleCloudDocumentaiV1DocumentSchemaMetadata');
