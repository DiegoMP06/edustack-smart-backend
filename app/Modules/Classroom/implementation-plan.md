# Migration of Classroom Module to Modular Architecture

This plan covers migrating the remaining parts of the Classroom module from the legacy architecture to the premium modular architecture (matching the `Blog` module style). All modular logic is already written in `app/Modules/Classroom`; we will delete the legacy files, clean up routes and providers, and rewrite the unit tests under `tests/Unit/Classroom` to run against the modular namespace.

## User Review Required

> [!IMPORTANT]
> The legacy controllers (`app/Http/Controllers/Classroom/`), form requests (`app/Http/Requests/Classroom/`), resources (`app/Http/Resources/Classroom/`), and routes (`routes/modules/classroom.php`) will be **deleted**. All routing and application logic will now go through the modular architecture implemented in `app/Modules/Classroom`.

> [!NOTE]
> The Eloquent Models under `app/Models/Classroom/` are **not legacy**; they remain in that namespace as is standard for all modules in this project (matching `app/Models/Blog/`), and are correctly integrated.

## Proposed Changes

### Clean up Legacy Code

We will delete all legacy files to ensure that we are completely moved to the new architecture.

#### [DELETE] [Legacy Controllers](file:///home/diego-meneses/Escritorio/edustack-smart/edustack-smart-backend/app/Http/Controllers/Classroom)
- Deleting the folder `app/Http/Controllers/Classroom` containing all 17 legacy controllers.

#### [DELETE] [Legacy Requests](file:///home/diego-meneses/Escritorio/edustack-smart/edustack-smart-backend/app/Http/Requests/Classroom)
- Deleting the folder `app/Http/Requests/Classroom` containing all 19 legacy requests.

#### [DELETE] [Legacy Resources](file:///home/diego-meneses/Escritorio/edustack-smart/edustack-smart-backend/app/Http/Resources/Classroom)
- Deleting the folder `app/Http/Resources/Classroom` containing all 2 legacy resources.

#### [DELETE] [Legacy Route File](file:///home/diego-meneses/Escritorio/edustack-smart/edustack-smart-backend/routes/modules/classroom.php)
- Deleting the global legacy route file.

---

### Routing Configuration

#### [MODIFY] [app.php](file:///home/diego-meneses/Escritorio/edustack-smart/edustack-smart-backend/bootstrap/app.php)
- Remove the redundant/mismatched loading block:
  ```php
  if (file_exists(base_path('app/Modules/Classroom/routes/course.php'))) {
      require base_path('app/Modules/Classroom/routes/course.php');
  }
  ```
  Since `ClassroomProvider` correctly registers `app/Modules/Classroom/routes/classroom.php`, this legacy check is unnecessary.

---

### Unit Tests Migration

All tests in `tests/Unit/Classroom` currently import classes from the legacy namespaces and some perform direct controller instantiations (`new AssignmentController` etc.) and reflect on rules which are now in the Form Requests. We will rewrite these tests to point to the modular classes and use standard testing conventions (e.g. resolving controllers via `app(...)` or mocking Use Cases as needed).

#### [MODIFY] [Classroom Tests](file:///home/diego-meneses/Escritorio/edustack-smart/edustack-smart-backend/tests/Unit/Classroom)
We will modify all files in `tests/Unit/Classroom` to:
- Use modular namespaces (e.g. `App\Modules\Classroom\Http\Controllers\...`, `App\Modules\Classroom\Http\Requests\...`).
- Fix validation test expectations (testing rules via their respective Form Request classes rather than direct reflection on controllers, as controllers no longer declare rules inline).
- Resolve controllers using `app(...)` or instantiating with mock use cases.

The files to be updated:
1. `tests/Unit/Classroom/AssignmentControllerTest.php`
2. `tests/Unit/Classroom/AssignmentSubmissionControllerTest.php`
3. `tests/Unit/Classroom/ClassroomFormRequestsTest.php`
4. `tests/Unit/Classroom/CourseAnnouncementControllerTest.php`
5. `tests/Unit/Classroom/CourseContentControllerTest.php`
6. `tests/Unit/Classroom/CourseControllerTest.php`
7. `tests/Unit/Classroom/CourseDiscussionCloseControllerTest.php`
8. `tests/Unit/Classroom/CourseDiscussionControllerTest.php`
9. `tests/Unit/Classroom/CourseDiscussionReplyControllerTest.php`
10. `tests/Unit/Classroom/CourseEnrollmentControllerTest.php`
11. `tests/Unit/Classroom/CourseLessonContentControllerTest.php`
12. `tests/Unit/Classroom/CourseLessonControllerTest.php`
13. `tests/Unit/Classroom/CourseLessonStatusControllerTest.php`
14. `tests/Unit/Classroom/CourseSectionControllerTest.php`
15. `tests/Unit/Classroom/CourseTeacherControllerTest.php`
16. `tests/Unit/Classroom/LessonCompletionControllerTest.php`
17. `tests/Unit/Classroom/SubmissionCommentControllerTest.php`

---

## Verification Plan

### Automated Tests
We will execute the classroom unit tests and architecture tests to ensure the application builds, routes match, and all tests pass perfectly:
- `php artisan test tests/Unit/Architecture/ClassroomModuleArchitectureTest.php`
- `php artisan test tests/Unit/Classroom`
- `vendor/bin/pint --dirty --format agent` (to format any edited PHP files)
