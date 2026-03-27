<?php
// lib/DataLoader.php
namespace Lib;

class DataLoader
{
    private static array $cache = [];
    
    /**
     * Загрузить все курсы
     */
    public static function loadCourses(): array
    {
        $key = 'courses';
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        
        $file = __DIR__ . '/../data/courses.php';
        if (!file_exists($file)) {
            throw new \RuntimeException("Файл данных курсов не найден: {$file}");
        }
        
        self::$cache[$key] = require $file;
        return self::$cache[$key];
    }
    
    /**
     * Загрузить конкретный курс по ID
     */
    public static function loadCourse(int $id): ?array
    {
        $courses = self::loadCourses();
        return $courses[$id] ?? null;
    }
    
    /**
     * Добавить новый курс (для админ-панели в будущем)
     */
    public static function addCourse(array $courseData): bool
    {
        $courses = self::loadCourses();
        $newId = max(array_keys($courses)) + 1;
        $courseData['id'] = $newId;
        $courses[$newId] = $courseData;
        
        $file = __DIR__ . '/../data/courses.php';
        $content = "<?php\n// data/courses.php\nreturn " . var_export($courses, true) . ";\n";
        
        return file_put_contents($file, $content) !== false;
    }
    
    /**
     * Получить количество курсов
     */
    public static function getCoursesCount(): int
    {
        return count(self::loadCourses());
    }
}