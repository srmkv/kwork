<?php

use App\Http\Controllers\Api\Course\CategoryCourseController;
use App\Http\Controllers\Api\Course\CourseController;
use App\Http\Controllers\Api\Course\CourseReviewController;
use Illuminate\Support\Facades\Route;

Route::get('courses', [CourseController::class, 'index']);

// Курс по слагу
Route::get('coursesslug', [CourseController::class, 'getBySlug']);
// Вывод отзывов о курсе
Route::get('courses/reviews', [CourseReviewController::class, 'index']);
Route::get('courses/reviews/list', [CourseReviewController::class, 'list']);
Route::get('course_categories/allSpecialities', [CategoryCourseController::class, 'allSpecialities']);
Route::get('course_categories/show', [CategoryCourseController::class, 'show']);