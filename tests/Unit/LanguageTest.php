<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Lib\Language;

class LanguageTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $_SESSION = [];
        $_GET = [];

        $reflection = new \ReflectionClass(Language::class);
        $currentLang = $reflection->getProperty('currentLang');
        $currentLang->setAccessible(true);
        $currentLang->setValue(null, 'ru');
        $translations = $reflection->getProperty('translations');
        $translations->setAccessible(true);
        $translations->setValue(null, []);
    }

    protected function tearDown(): void
    {
        $_SESSION = [];
        $_GET = [];
        parent::tearDown();
    }

    public function testInitDefaultLanguage(): void
    {
        Language::init();
        $this->assertEquals('ru', Language::getCurrentLang());
    }

    public function testSetLanguageFromSession(): void
    {
        $_SESSION['lang'] = 'en';
        Language::init();
        $this->assertEquals('en', Language::getCurrentLang());
    }

    public function testSetLanguageFromGet(): void
    {
        $_GET['lang'] = 'en';
        Language::init();
        $this->assertEquals('en', Language::getCurrentLang());
        $this->assertEquals('en', $_SESSION['lang']);
    }

    public function testInvalidLanguageFallback(): void
    {
        $_SESSION['lang'] = 'invalid';
        Language::init();
        $this->assertEquals('ru', Language::getCurrentLang());
    }

    public function testGetTranslationExists(): void
    {
        Language::init();

        $translation = Language::get('nav_home');
        $this->assertIsString($translation);
        $this->assertNotEmpty($translation);
    }

    public function testGetTranslationWithParams(): void
    {
        Language::init();

        $translation = Language::get('nav_home', ['test' => 'value']);
        $this->assertIsString($translation);
    }

    public function testGetTranslationFallbackToRussian(): void
    {
        Language::setLang('en');
        Language::init();

        $translation = Language::get('nav_home');
        $this->assertIsString($translation);
        $this->assertNotEmpty($translation);
    }

    public function testGetTranslationKeyNotFound(): void
    {
        Language::init();

        $translation = Language::get('nonexistent_key_12345');
        $this->assertEquals('nonexistent_key_12345', $translation);
    }

    public function testSetLang(): void
    {
        Language::setLang('en');
        $this->assertEquals('en', Language::getCurrentLang());
        $this->assertEquals('en', $_SESSION['lang']);
    }

    public function testSetLangInvalid(): void
    {
        Language::setLang('invalid');
        $this->assertEquals('ru', Language::getCurrentLang());
    }

    public function testGetAvailableLangs(): void
    {
        $langs = Language::getAvailableLangs();
        $this->assertIsArray($langs);
        $this->assertContains('ru', $langs);
        $this->assertContains('en', $langs);
        $this->assertCount(2, $langs);
    }

    public function testGetLangName(): void
    {
        $this->assertEquals('Русский', Language::getLangName('ru'));
        $this->assertEquals('English', Language::getLangName('en'));
        $this->assertEquals('invalid', Language::getLangName('invalid'));
    }

    public function testMultilingualCourseData(): void
    {
        $coursesFile = __DIR__ . '/../../data/courses.php';
        $this->assertFileExists($coursesFile);

        $courses = require $coursesFile;

        foreach ($courses as $course) {
            $this->assertArrayHasKey('ru', $course['title']);
            $this->assertArrayHasKey('en', $course['title']);
            $this->assertArrayHasKey('ru', $course['description']);
            $this->assertArrayHasKey('en', $course['description']);
        }
    }

    public function testLanguageFileExists(): void
    {
        $langFile = __DIR__ . '/../../data/languages.php';
        $this->assertFileExists($langFile);

        $translations = require $langFile;

        $this->assertArrayHasKey('ru', $translations);
        $this->assertArrayHasKey('en', $translations);
        $this->assertIsArray($translations['ru']);
        $this->assertIsArray($translations['en']);
    }

    public function testTranslationKeysConsistency(): void
    {
        $langFile = __DIR__ . '/../../data/languages.php';
        $translations = require $langFile;

        $ruKeys = array_keys($translations['ru']);
        $enKeys = array_keys($translations['en']);

        $missingInEn = array_diff($ruKeys, $enKeys);
        $missingInRu = array_diff($enKeys, $ruKeys);

        $this->assertEmpty($missingInEn, 'Следующие ключи отсутствуют в EN: ' . implode(', ', $missingInEn));
        $this->assertEmpty($missingInRu, 'Следующие ключи отсутствуют в RU: ' . implode(', ', $missingInRu));
    }
}
