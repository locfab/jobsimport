<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/utils/utils.php';

class UtilsTest extends TestCase {
    public function testGetFilesFilterByExtensions() {
        $directoryPath = __DIR__ . '/resources';
        $extensions = ['json', 'xml'];

        $result = getFilesFilterByExtensions($directoryPath, $extensions);

        $this->assertIsArray($result);
        $this->assertEquals(5, count($result));
    }
    public function testGetNOFilesFilterByBadExtensions() {
        $directoryPath = __DIR__ . '/resources';
        $extensions = ['txt', 'md'];

        $result = getFilesFilterByExtensions($directoryPath, $extensions);

        $this->assertIsArray($result);
        $this->assertEquals(0, count($result));
    }
}

