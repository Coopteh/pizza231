<?php
namespace Controllers;
use Views\CourseTemplate;

class CourseController
{
    private int $courseId;
    
    public function __construct(int $courseId)
    {
        $this->courseId = $courseId;
    }
    
    public function get(): string
    {
        return CourseTemplate::renderCourse($this->courseId);
    }
}