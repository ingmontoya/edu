# Sistema de Gestión Académica para Colegios - MVP

## Contexto del Proyecto

Desarrollar un MVP de sistema de gestión académica para colegios en Colombia usando un template existente de Laravel + Nuxt. Debe cumplir con el **Decreto 1290** del Ministerio de Educación Nacional. 

**Objetivo:** Sistema funcional en 3-4 semanas.
**Presupuesto cliente:** $2,000,000 COP

---

## Stack Tecnológico (Template Base)

- **Backend:** Laravel 12 + PHP 8.2 + PostgreSQL
- **Frontend:** Nuxt 4 + Vue 3 + TypeScript + Nuxt UI + Pinia
- **Authentication:** Laravel Sanctum (ya configurado)
- **Architecture:** REST API + SPA
- **DevOps:** Laravel Sail (Docker)
- **PDF:** barryvdh/laravel-dompdf

---

## Estructura del Proyecto

```
school-management/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── Auth/                     # Ya existe en template
│   │   │   ├── InstitutionController.php
│   │   │   ├── AcademicYearController.php
│   │   │   ├── PeriodController.php
│   │   │   ├── GradeController.php
│   │   │   ├── GroupController.php
│   │   │   ├── AreaController.php
│   │   │   ├── SubjectController.php
│   │   │   ├── StudentController.php
│   │   │   ├── TeacherController.php
│   │   │   ├── GuardianController.php
│   │   │   ├── GradeRecordController.php
│   │   │   ├── AttendanceController.php
│   │   │   ├── ReportCardController.php
│   │   │   ├── AnnouncementController.php
│   │   │   └── PortalController.php
│   │   ├── Requests/
│   │   └── Resources/
│   ├── Models/
│   ├── Services/
│   │   ├── GradeCalculatorService.php
│   │   └── ReportCardPdfService.php
│   └── Enums/
│       ├── UserRole.php
│       ├── PerformanceLevel.php
│       └── AttendanceStatus.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/views/pdf/
│   └── report-card.blade.php
├── routes/api.php
│
└── frontend/
    └── app/
        ├── components/
        │   ├── ui/                       # Ya existe (Nuxt UI)
        │   ├── grades/
        │   │   ├── GradeInput.vue
        │   │   ├── GradeTable.vue
        │   │   └── PerformanceBadge.vue
        │   ├── attendance/
        │   │   └── AttendanceStatus.vue
        │   └── reports/
        │       └── ReportCardPreview.vue
        ├── composables/
        │   ├── useApi.ts                 # Ya existe
        │   ├── useGrades.ts
        │   └── useAcademic.ts
        ├── layouts/
        │   ├── default.vue               # Ya existe
        │   └── portal.vue
        ├── pages/
        │   ├── index.vue
        │   ├── auth/                     # Ya existe
        │   ├── dashboard.vue
        │   ├── institution/
        │   │   └── settings.vue
        │   ├── academic/
        │   │   ├── years/
        │   │   │   ├── index.vue
        │   │   │   └── [id].vue
        │   │   ├── grades/
        │   │   │   └── index.vue
        │   │   ├── groups/
        │   │   │   ├── index.vue
        │   │   │   └── [id].vue
        │   │   └── subjects/
        │   │       └── index.vue
        │   ├── students/
        │   │   ├── index.vue
        │   │   └── [id].vue
        │   ├── teachers/
        │   │   ├── index.vue
        │   │   └── [id].vue
        │   ├── grades/
        │   │   ├── index.vue
        │   │   └── record.vue
        │   ├── attendance/
        │   │   └── index.vue
        │   ├── reports/
        │   │   ├── cards/
        │   │   │   └── index.vue
        │   │   └── consolidation.vue
        │   └── portal/
        │       ├── index.vue
        │       └── grades.vue
        ├── stores/
        │   ├── auth.ts                   # Ya existe
        │   ├── institution.ts
        │   └── academic.ts
        └── middleware/
            ├── auth.ts                   # Ya existe
            └── role.ts
```

---

## Módulos del MVP

### 1. Autenticación y Roles
### 2. Gestión de Institución
### 3. Gestión Académica (Años, Períodos, Grados, Grupos, Áreas, Asignaturas)
### 4. Gestión de Personas (Estudiantes, Docentes, Acudientes)
### 5. Registro de Notas
### 6. Control de Asistencia
### 7. Generación de Boletines PDF
### 8. Portal de Acudientes (Solo lectura)
### 9. Reportes (Consolidados, Estudiantes con bajo rendimiento)

---

## Enums

### app/Enums/UserRole.php

```php
<?php

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case COORDINATOR = 'coordinator';
    case TEACHER = 'teacher';
    case GUARDIAN = 'guardian';
    
    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador',
            self::COORDINATOR => 'Coordinador',
            self::TEACHER => 'Docente',
            self::GUARDIAN => 'Acudiente',
        };
    }
}
```

### app/Enums/PerformanceLevel.php

```php
<?php

namespace App\Enums;

enum PerformanceLevel: string
{
    case SUPERIOR = 'superior';
    case HIGH = 'high';
    case BASIC = 'basic';
    case LOW = 'low';
    
    public function label(): string
    {
        return match($this) {
            self::SUPERIOR => 'Desempeño Superior',
            self::HIGH => 'Desempeño Alto',
            self::BASIC => 'Desempeño Básico',
            self::LOW => 'Desempeño Bajo',
        };
    }
    
    public static function fromGrade(float $grade): self
    {
        return match(true) {
            $grade >= 4.6 => self::SUPERIOR,
            $grade >= 4.0 => self::HIGH,
            $grade >= 3.0 => self::BASIC,
            default => self::LOW,
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::SUPERIOR => 'green',
            self::HIGH => 'blue',
            self::BASIC => 'yellow',
            self::LOW => 'red',
        };
    }
}
```

### app/Enums/AttendanceStatus.php

```php
<?php

namespace App\Enums;

enum AttendanceStatus: string
{
    case PRESENT = 'present';
    case ABSENT = 'absent';
    case LATE = 'late';
    case EXCUSED = 'excused';
    
    public function label(): string
    {
        return match($this) {
            self::PRESENT => 'Presente',
            self::ABSENT => 'Ausente',
            self::LATE => 'Tardanza',
            self::EXCUSED => 'Excusa',
        };
    }
    
    public function icon(): string
    {
        return match($this) {
            self::PRESENT => '✓',
            self::ABSENT => '✗',
            self::LATE => 'T',
            self::EXCUSED => 'E',
        };
    }
    
    public function color(): string
    {
        return match($this) {
            self::PRESENT => 'green',
            self::ABSENT => 'red',
            self::LATE => 'yellow',
            self::EXCUSED => 'blue',
        };
    }
}
```

---

## Migraciones (PostgreSQL)

Crear en este orden exacto:

### 1. add_role_to_users_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('guardian');
            $table->string('document_type')->nullable(); // CC, TI, CE, RC, etc
            $table->string('document_number')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('photo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'document_type', 'document_number', 'phone', 'address', 'photo']);
        });
    }
};
```

### 2. create_institutions_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('institutions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('nit')->nullable();
            $table->string('dane_code')->nullable();
            $table->string('logo')->nullable();
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('department')->nullable();
            $table->string('rector_name')->nullable();
            $table->json('grading_scale')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('institutions');
    }
};
```

### 3. create_academic_years_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('academic_years', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->year('year');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
            
            $table->unique(['institution_id', 'year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('academic_years');
    }
};
```

### 4. create_periods_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('number');
            $table->decimal('weight', 5, 2)->default(25.00);
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
            
            $table->unique(['academic_year_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('periods');
    }
};
```

### 5. create_grades_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('short_name');
            $table->integer('order');
            $table->string('level'); // preescolar, primaria, secundaria, media
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};
```

### 6. create_groups_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('director_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->integer('capacity')->nullable();
            $table->timestamps();
            
            $table->unique(['grade_id', 'academic_year_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};
```

### 7. create_areas_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};
```

### 8. create_subjects_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('area_id')->constrained()->cascadeOnDelete();
            $table->foreignId('grade_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->integer('weekly_hours')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subjects');
    }
};
```

### 9. create_teachers_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->string('specialization')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
```

### 10. create_students_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->string('enrollment_code')->nullable();
            $table->date('enrollment_date');
            $table->string('status')->default('active'); // active, inactive, withdrawn, graduated
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
```

### 11. create_teacher_assignments_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(
                ['teacher_id', 'subject_id', 'group_id', 'academic_year_id'],
                'teacher_assignment_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_assignments');
    }
};
```

### 12. create_guardians_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('relationship')->nullable();
            $table->string('occupation')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guardians');
    }
};
```

### 13. create_student_guardian_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_guardian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guardian_id')->constrained()->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
            
            $table->unique(['student_id', 'guardian_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_guardian');
    }
};
```

### 14. create_grade_records_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained()->cascadeOnDelete();
            $table->decimal('grade', 3, 1)->nullable();
            $table->text('observations')->nullable();
            $table->text('recommendations')->nullable();
            $table->timestamps();
            
            $table->unique(['student_id', 'subject_id', 'period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_records');
    }
};
```

### 15. create_attendances_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('group_id')->constrained()->cascadeOnDelete();
            $table->foreignId('period_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->string('status'); // present, absent, late, excused
            $table->text('observation')->nullable();
            $table->foreignId('registered_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['student_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
```

### 16. create_announcements_table

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('institution_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('content');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
```

---

## Modelos Eloquent

### app/Models/Institution.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    protected $fillable = [
        'name', 'nit', 'dane_code', 'logo', 'address',
        'phone', 'email', 'city', 'department', 'rector_name', 'grading_scale',
    ];

    protected $casts = [
        'grading_scale' => 'array',
    ];

    public function academicYears(): HasMany
    {
        return $this->hasMany(AcademicYear::class);
    }

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }

    public function teachers(): HasMany
    {
        return $this->hasMany(Teacher::class);
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function activeYear()
    {
        return $this->academicYears()->where('is_active', true)->first();
    }
}
```

### app/Models/AcademicYear.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AcademicYear extends Model
{
    protected $fillable = [
        'institution_id', 'year', 'start_date', 'end_date', 'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function periods(): HasMany
    {
        return $this->hasMany(Period::class)->orderBy('number');
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
```

### app/Models/Period.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Period extends Model
{
    protected $fillable = [
        'academic_year_id', 'name', 'number', 'weight',
        'start_date', 'end_date', 'is_closed',
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_closed' => 'boolean',
    ];

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function gradeRecords(): HasMany
    {
        return $this->hasMany(GradeRecord::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
```

### app/Models/Grade.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grade extends Model
{
    protected $fillable = [
        'institution_id', 'name', 'short_name', 'order', 'level',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(Group::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
```

### app/Models/Group.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    protected $fillable = [
        'grade_id', 'academic_year_id', 'director_id', 'name', 'capacity',
    ];

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function director(): BelongsTo
    {
        return $this->belongsTo(User::class, 'director_id');
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public function activeStudents(): HasMany
    {
        return $this->hasMany(Student::class)->where('status', 'active');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function getFullNameAttribute(): string
    {
        return $this->grade->short_name . ' - ' . $this->name;
    }
}
```

### app/Models/Area.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $fillable = ['institution_id', 'name', 'order'];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
```

### app/Models/Subject.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'area_id', 'grade_id', 'name', 'weekly_hours', 'order',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function gradeRecords(): HasMany
    {
        return $this->hasMany(GradeRecord::class);
    }

    public function teacherAssignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class);
    }
}
```

### app/Models/Teacher.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teacher extends Model
{
    protected $fillable = ['user_id', 'institution_id', 'specialization'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TeacherAssignment::class);
    }

    public function gradeRecords(): HasMany
    {
        return $this->hasMany(GradeRecord::class);
    }
}
```

### app/Models/TeacherAssignment.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherAssignment extends Model
{
    protected $fillable = [
        'teacher_id', 'subject_id', 'group_id', 'academic_year_id',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function academicYear(): BelongsTo
    {
        return $this->belongsTo(AcademicYear::class);
    }
}
```

### app/Models/Student.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'user_id', 'group_id', 'enrollment_code', 'enrollment_date', 'status',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function guardians(): BelongsToMany
    {
        return $this->belongsToMany(Guardian::class, 'student_guardian')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function gradeRecords(): HasMany
    {
        return $this->hasMany(GradeRecord::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
```

### app/Models/Guardian.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Guardian extends Model
{
    protected $fillable = ['user_id', 'relationship', 'occupation'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(Student::class, 'student_guardian')
            ->withPivot('is_primary')
            ->withTimestamps();
    }
}
```

### app/Models/GradeRecord.php

```php
<?php

namespace App\Models;

use App\Enums\PerformanceLevel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class GradeRecord extends Model
{
    protected $fillable = [
        'student_id', 'subject_id', 'period_id', 'teacher_id',
        'grade', 'observations', 'recommendations',
    ];

    protected $casts = [
        'grade' => 'decimal:1',
    ];

    protected $appends = ['performance_level', 'performance_label'];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(Teacher::class);
    }

    protected function performanceLevel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->grade !== null 
                ? PerformanceLevel::fromGrade((float) $this->grade)->value 
                : null,
        );
    }

    protected function performanceLabel(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->grade !== null 
                ? PerformanceLevel::fromGrade((float) $this->grade)->label() 
                : null,
        );
    }
}
```

### app/Models/Attendance.php

```php
<?php

namespace App\Models;

use App\Enums\AttendanceStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'student_id', 'group_id', 'period_id', 'date',
        'status', 'observation', 'registered_by',
    ];

    protected $casts = [
        'date' => 'date',
        'status' => AttendanceStatus::class,
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}
```

### app/Models/Announcement.php

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Announcement extends Model
{
    protected $fillable = [
        'institution_id', 'user_id', 'title', 'content',
        'is_published', 'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
```

---

## API Routes

### routes/api.php

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InstitutionController;
use App\Http\Controllers\Api\AcademicYearController;
use App\Http\Controllers\Api\PeriodController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\GuardianController;
use App\Http\Controllers\Api\GradeRecordController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\ReportCardController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\PortalController;

// ... endpoints existentes de auth del template ...

// Rutas protegidas
Route::middleware('auth:sanctum')->group(function () {
    
    // Institution
    Route::get('/institution', [InstitutionController::class, 'show']);
    Route::put('/institution', [InstitutionController::class, 'update']);
    Route::post('/institution/logo', [InstitutionController::class, 'uploadLogo']);
    
    // Academic Years
    Route::apiResource('academic-years', AcademicYearController::class);
    Route::post('academic-years/{academicYear}/activate', [AcademicYearController::class, 'activate']);
    
    // Periods
    Route::get('periods', [PeriodController::class, 'index']);
    Route::apiResource('academic-years.periods', PeriodController::class)->shallow();
    Route::post('periods/{period}/close', [PeriodController::class, 'close']);
    Route::post('periods/{period}/open', [PeriodController::class, 'open']);
    
    // Grades (grados escolares)
    Route::apiResource('grades', GradeController::class);
    
    // Groups
    Route::apiResource('groups', GroupController::class);
    Route::get('groups/{group}/students', [GroupController::class, 'students']);
    Route::post('groups/{group}/assign-director', [GroupController::class, 'assignDirector']);
    
    // Areas & Subjects
    Route::apiResource('areas', AreaController::class);
    Route::apiResource('subjects', SubjectController::class);
    
    // Students
    Route::apiResource('students', StudentController::class);
    Route::get('students/{student}/grades', [StudentController::class, 'grades']);
    Route::get('students/{student}/attendance', [StudentController::class, 'attendance']);
    Route::post('students/{student}/assign-guardian', [StudentController::class, 'assignGuardian']);
    
    // Teachers
    Route::apiResource('teachers', TeacherController::class);
    Route::get('teachers/{teacher}/assignments', [TeacherController::class, 'assignments']);
    Route::post('teachers/{teacher}/assign', [TeacherController::class, 'assign']);
    Route::delete('teachers/{teacher}/unassign/{assignment}', [TeacherController::class, 'unassign']);
    
    // Guardians
    Route::apiResource('guardians', GuardianController::class);
    Route::get('guardians/{guardian}/students', [GuardianController::class, 'students']);
    
    // Grade Records (Notas)
    Route::get('grade-records', [GradeRecordController::class, 'index']);
    Route::post('grade-records/bulk', [GradeRecordController::class, 'bulkStore']);
    Route::put('grade-records/{gradeRecord}', [GradeRecordController::class, 'update']);
    Route::get('grade-records/by-student/{student}', [GradeRecordController::class, 'byStudent']);
    Route::get('grade-records/worksheet', [GradeRecordController::class, 'worksheet']);
    
    // Attendance (Asistencia)
    Route::get('attendance', [AttendanceController::class, 'index']);
    Route::post('attendance/bulk', [AttendanceController::class, 'bulkStore']);
    Route::put('attendance/{attendance}', [AttendanceController::class, 'update']);
    Route::get('attendance/report', [AttendanceController::class, 'report']);
    Route::get('attendance/daily/{group}', [AttendanceController::class, 'daily']);
    
    // Report Cards (Boletines)
    Route::get('report-cards/student/{student}/period/{period}', [ReportCardController::class, 'show']);
    Route::get('report-cards/student/{student}/period/{period}/pdf', [ReportCardController::class, 'pdf']);
    Route::get('report-cards/group/{group}/period/{period}/pdf', [ReportCardController::class, 'bulkPdf']);
    
    // Reports
    Route::get('reports/consolidation', [ReportController::class, 'consolidation']);
    Route::get('reports/failing-students', [ReportController::class, 'failingStudents']);
    Route::get('reports/attendance-summary', [ReportController::class, 'attendanceSummary']);
    
    // Announcements
    Route::apiResource('announcements', AnnouncementController::class);
    Route::post('announcements/{announcement}/publish', [AnnouncementController::class, 'publish']);
    
    // Portal Acudiente (rutas específicas)
    Route::prefix('portal')->group(function () {
        Route::get('students', [PortalController::class, 'students']);
        Route::get('students/{student}/grades', [PortalController::class, 'grades']);
        Route::get('students/{student}/attendance', [PortalController::class, 'attendance']);
        Route::get('students/{student}/report-card/{period}/pdf', [PortalController::class, 'reportCardPdf']);
        Route::get('announcements', [PortalController::class, 'announcements']);
    });
});
```

---

## Servicios

### app/Services/GradeCalculatorService.php

```php
<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Period;
use App\Enums\PerformanceLevel;

class GradeCalculatorService
{
    /**
     * Calcula el promedio de un estudiante en un período
     */
    public function calculatePeriodAverage(Student $student, Period $period): ?float
    {
        $grades = $student->gradeRecords()
            ->where('period_id', $period->id)
            ->whereNotNull('grade')
            ->pluck('grade');
        
        if ($grades->isEmpty()) {
            return null;
        }
        
        return round($grades->avg(), 1);
    }
    
    /**
     * Calcula la nota final ponderada de una asignatura
     */
    public function calculateFinalGrade(Student $student, int $subjectId, int $academicYearId): ?float
    {
        $periods = Period::where('academic_year_id', $academicYearId)->get();
        
        $weightedSum = 0;
        $totalWeight = 0;
        
        foreach ($periods as $period) {
            $gradeRecord = $student->gradeRecords()
                ->where('subject_id', $subjectId)
                ->where('period_id', $period->id)
                ->first();
            
            if ($gradeRecord && $gradeRecord->grade !== null) {
                $weightedSum += $gradeRecord->grade * $period->weight;
                $totalWeight += $period->weight;
            }
        }
        
        if ($totalWeight === 0) {
            return null;
        }
        
        return round($weightedSum / $totalWeight, 1);
    }
    
    /**
     * Calcula el puesto del estudiante en el grupo
     */
    public function calculateRanking(Student $student, Period $period): int
    {
        $groupStudents = Student::where('group_id', $student->group_id)
            ->where('status', 'active')
            ->get();
        
        $averages = $groupStudents->map(function ($s) use ($period) {
            return [
                'student_id' => $s->id,
                'average' => $this->calculatePeriodAverage($s, $period) ?? 0,
            ];
        })->sortByDesc('average')->values();
        
        $position = $averages->search(fn($item) => $item['student_id'] === $student->id);
        
        return $position !== false ? $position + 1 : 0;
    }
    
    /**
     * Obtiene el nivel de desempeño
     */
    public function getPerformanceLevel(float $grade): PerformanceLevel
    {
        return PerformanceLevel::fromGrade($grade);
    }
    
    /**
     * Verifica si un estudiante aprueba una asignatura
     */
    public function isPassingGrade(float $grade, float $passingGrade = 3.0): bool
    {
        return $grade >= $passingGrade;
    }
}
```

### app/Services/ReportCardPdfService.php

```php
<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Period;
use App\Models\Institution;
use App\Models\Attendance;
use App\Enums\AttendanceStatus;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardPdfService
{
    public function __construct(
        private GradeCalculatorService $calculator
    ) {}
    
    public function generate(Student $student, Period $period): \Barryvdh\DomPDF\PDF
    {
        $data = $this->buildReportCardData($student, $period);
        
        return Pdf::loadView('pdf.report-card', $data)
            ->setPaper('letter', 'portrait')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'sans-serif',
            ]);
    }
    
    public function generateBulk(array $studentIds, Period $period): \Barryvdh\DomPDF\PDF
    {
        $students = Student::whereIn('id', $studentIds)->get();
        
        $allData = $students->map(fn($student) => $this->buildReportCardData($student, $period));
        
        return Pdf::loadView('pdf.report-card-bulk', ['reports' => $allData])
            ->setPaper('letter', 'portrait');
    }
    
    private function buildReportCardData(Student $student, Period $period): array
    {
        $student->load(['user', 'group.grade', 'group.director.user']);
        $institution = Institution::first();
        
        // Notas agrupadas por área
        $gradeRecords = $student->gradeRecords()
            ->where('period_id', $period->id)
            ->with(['subject.area'])
            ->get()
            ->groupBy('subject.area.name');
        
        // Calcular promedios
        $allGrades = $student->gradeRecords()
            ->where('period_id', $period->id)
            ->whereNotNull('grade')
            ->pluck('grade');
        
        $periodAverage = $allGrades->isNotEmpty() ? round($allGrades->avg(), 1) : null;
        
        // Puesto
        $ranking = $this->calculator->calculateRanking($student, $period);
        $totalStudents = Student::where('group_id', $student->group_id)
            ->where('status', 'active')
            ->count();
        
        // Asistencia
        $attendanceData = $this->getAttendanceData($student, $period);
        
        // Asignaturas perdidas
        $failingSubjects = $student->gradeRecords()
            ->where('period_id', $period->id)
            ->where('grade', '<', 3.0)
            ->with('subject')
            ->get();
        
        return [
            'institution' => $institution,
            'student' => $student,
            'period' => $period,
            'group' => $student->group,
            'grade' => $student->group->grade,
            'director' => $student->group->director,
            'gradesByArea' => $gradeRecords,
            'periodAverage' => $periodAverage,
            'performanceLevel' => $periodAverage 
                ? $this->calculator->getPerformanceLevel($periodAverage)->label() 
                : null,
            'ranking' => $ranking,
            'totalStudents' => $totalStudents,
            'attendance' => $attendanceData,
            'failingSubjects' => $failingSubjects,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ];
    }
    
    private function getAttendanceData(Student $student, Period $period): array
    {
        $attendances = Attendance::where('student_id', $student->id)
            ->where('period_id', $period->id)
            ->get();
        
        $total = $attendances->count();
        $present = $attendances->where('status', AttendanceStatus::PRESENT)->count();
        $absent = $attendances->where('status', AttendanceStatus::ABSENT)->count();
        $late = $attendances->where('status', AttendanceStatus::LATE)->count();
        $excused = $attendances->where('status', AttendanceStatus::EXCUSED)->count();
        
        return [
            'total_days' => $total,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'percentage' => $total > 0 ? round(($present + $excused) / $total * 100, 1) : 100,
        ];
    }
}
```

---

## Controllers Principales

### app/Http/Controllers/Api/GradeRecordController.php

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GradeRecord;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Period;
use App\Services\GradeCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GradeRecordController extends Controller
{
    public function __construct(
        private GradeCalculatorService $calculator
    ) {}
    
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'subject_id' => 'required|exists:subjects,id',
            'period_id' => 'required|exists:periods,id',
        ]);
        
        $students = Student::where('group_id', $request->group_id)
            ->where('status', 'active')
            ->with(['user:id,name,document_number'])
            ->orderBy('id')
            ->get();
        
        $existingRecords = GradeRecord::where('subject_id', $request->subject_id)
            ->where('period_id', $request->period_id)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->keyBy('student_id');
        
        $data = $students->map(function ($student) use ($existingRecords) {
            $record = $existingRecords->get($student->id);
            return [
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'document_number' => $student->user->document_number,
                'record_id' => $record?->id,
                'grade' => $record?->grade,
                'performance_level' => $record?->performance_level,
                'performance_label' => $record?->performance_label,
                'observations' => $record?->observations,
                'recommendations' => $record?->recommendations,
            ];
        });
        
        return response()->json($data);
    }
    
    public function bulkStore(Request $request): JsonResponse
    {
        $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'period_id' => 'required|exists:periods,id',
            'records' => 'required|array',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.grade' => 'nullable|numeric|min:1|max:5',
            'records.*.observations' => 'nullable|string|max:1000',
            'records.*.recommendations' => 'nullable|string|max:1000',
        ]);
        
        $teacher = auth()->user()->teacher;
        
        if (!$teacher && auth()->user()->role !== 'admin') {
            return response()->json(['message' => 'No autorizado'], 403);
        }
        
        $saved = [];
        
        foreach ($request->records as $record) {
            if ($record['grade'] !== null && $record['grade'] !== '') {
                $gradeRecord = GradeRecord::updateOrCreate(
                    [
                        'student_id' => $record['student_id'],
                        'subject_id' => $request->subject_id,
                        'period_id' => $request->period_id,
                    ],
                    [
                        'teacher_id' => $teacher?->id ?? 1,
                        'grade' => round($record['grade'], 1),
                        'observations' => $record['observations'] ?? null,
                        'recommendations' => $record['recommendations'] ?? null,
                    ]
                );
                $saved[] = $gradeRecord;
            }
        }
        
        return response()->json([
            'message' => 'Notas guardadas correctamente',
            'count' => count($saved),
        ]);
    }
    
    public function worksheet(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'period_id' => 'required|exists:periods,id',
        ]);
        
        $group = Group::with('grade')->findOrFail($request->group_id);
        $period = Period::findOrFail($request->period_id);
        
        $students = Student::where('group_id', $request->group_id)
            ->where('status', 'active')
            ->with(['user:id,name'])
            ->get();
        
        $subjects = Subject::where('grade_id', $group->grade_id)
            ->with('area')
            ->orderBy('area_id')
            ->orderBy('order')
            ->get();
        
        $allRecords = GradeRecord::where('period_id', $request->period_id)
            ->whereIn('student_id', $students->pluck('id'))
            ->whereIn('subject_id', $subjects->pluck('id'))
            ->get()
            ->groupBy('student_id');
        
        $worksheet = $students->map(function ($student) use ($subjects, $allRecords) {
            $studentRecords = $allRecords->get($student->id, collect())->keyBy('subject_id');
            
            $grades = $subjects->map(fn($subject) => [
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'area_name' => $subject->area->name,
                'grade' => $studentRecords->get($subject->id)?->grade,
                'performance' => $studentRecords->get($subject->id)?->performance_label,
            ]);
            
            $validGrades = $grades->pluck('grade')->filter()->values();
            $average = $validGrades->isNotEmpty() ? round($validGrades->avg(), 1) : null;
            
            return [
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'grades' => $grades,
                'average' => $average,
                'performance' => $average ? $this->calculator->getPerformanceLevel($average)->label() : null,
            ];
        })->sortByDesc('average')->values();
        
        // Calcular ranking
        $worksheet = $worksheet->map(function ($item, $index) {
            $item['ranking'] = $item['average'] !== null ? $index + 1 : null;
            return $item;
        });
        
        return response()->json([
            'group' => $group,
            'period' => $period,
            'subjects' => $subjects,
            'worksheet' => $worksheet->sortBy('student_name')->values(),
        ]);
    }
    
    public function byStudent(Student $student, Request $request): JsonResponse
    {
        $query = $student->gradeRecords()->with(['subject.area', 'period']);
        
        if ($request->period_id) {
            $query->where('period_id', $request->period_id);
        }
        
        $records = $query->get()->groupBy('period_id');
        
        return response()->json($records);
    }
}
```

### app/Http/Controllers/Api/AttendanceController.php

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Group;
use App\Enums\AttendanceStatus;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'date' => 'required|date',
        ]);
        
        $students = Student::where('group_id', $request->group_id)
            ->where('status', 'active')
            ->with(['user:id,name,document_number'])
            ->orderBy('id')
            ->get();
        
        $attendances = Attendance::where('group_id', $request->group_id)
            ->where('date', $request->date)
            ->get()
            ->keyBy('student_id');
        
        $data = $students->map(function ($student) use ($attendances) {
            $attendance = $attendances->get($student->id);
            return [
                'student_id' => $student->id,
                'student_name' => $student->user->name,
                'document_number' => $student->user->document_number,
                'attendance_id' => $attendance?->id,
                'status' => $attendance?->status?->value ?? null,
                'observation' => $attendance?->observation,
            ];
        });
        
        return response()->json($data);
    }
    
    public function bulkStore(Request $request): JsonResponse
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
            'period_id' => 'required|exists:periods,id',
            'date' => 'required|date',
            'records' => 'required|array',
            'records.*.student_id' => 'required|exists:students,id',
            'records.*.status' => 'required|in:present,absent,late,excused',
            'records.*.observation' => 'nullable|string|max:500',
        ]);
        
        $saved = [];
        
        foreach ($request->records as $record) {
            $attendance = Attendance::updateOrCreate(
                [
                    'student_id' => $record['student_id'],
                    'date' => $request->date,
                ],
                [
                    'group_id' => $request->group_id,
                    'period_id' => $request->period_id,
                    'status' => $record['status'],
                    'observation' => $record['observation'] ?? null,
                    'registered_by' => auth()->id(),
                ]
            );
            $saved[] = $attendance;
        }
        
        return response()->json([
            'message' => 'Asistencia registrada correctamente',
            'count' => count($saved),
        ]);
    }
    
    public function report(Request $request): JsonResponse
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'period_id' => 'required|exists:periods,id',
        ]);
        
        $attendances = Attendance::where('student_id', $request->student_id)
            ->where('period_id', $request->period_id)
            ->orderBy('date')
            ->get();
        
        $summary = [
            'total_days' => $attendances->count(),
            'present' => $attendances->where('status', AttendanceStatus::PRESENT)->count(),
            'absent' => $attendances->where('status', AttendanceStatus::ABSENT)->count(),
            'late' => $attendances->where('status', AttendanceStatus::LATE)->count(),
            'excused' => $attendances->where('status', AttendanceStatus::EXCUSED)->count(),
        ];
        
        $summary['attendance_percentage'] = $summary['total_days'] > 0 
            ? round(($summary['present'] + $summary['excused']) / $summary['total_days'] * 100, 1)
            : 100;
        
        return response()->json([
            'summary' => $summary,
            'records' => $attendances,
        ]);
    }
    
    public function daily(Group $group): JsonResponse
    {
        $today = Carbon::today();
        
        $students = Student::where('group_id', $group->id)
            ->where('status', 'active')
            ->with(['user:id,name'])
            ->get();
        
        $attendances = Attendance::where('group_id', $group->id)
            ->where('date', $today)
            ->get()
            ->keyBy('student_id');
        
        return response()->json([
            'date' => $today->toDateString(),
            'group' => $group->load('grade'),
            'students' => $students->map(fn($s) => [
                'id' => $s->id,
                'name' => $s->user->name,
                'status' => $attendances->get($s->id)?->status?->value,
            ]),
            'summary' => [
                'total' => $students->count(),
                'registered' => $attendances->count(),
                'pending' => $students->count() - $attendances->count(),
            ],
        ]);
    }
}
```

### app/Http/Controllers/Api/ReportCardController.php

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Period;
use App\Models\Group;
use App\Services\ReportCardPdfService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ReportCardController extends Controller
{
    public function __construct(
        private ReportCardPdfService $pdfService
    ) {}
    
    public function show(Student $student, Period $period): JsonResponse
    {
        $student->load(['user', 'group.grade', 'group.director.user']);
        
        $gradeRecords = $student->gradeRecords()
            ->where('period_id', $period->id)
            ->with(['subject.area'])
            ->get();
        
        return response()->json([
            'student' => $student,
            'period' => $period,
            'grades' => $gradeRecords,
        ]);
    }
    
    public function pdf(Student $student, Period $period)
    {
        $pdf = $this->pdfService->generate($student, $period);
        
        $filename = sprintf(
            'boletin_%s_%s_%s.pdf',
            str_replace(' ', '_', $student->user->name),
            $period->name,
            now()->format('Ymd')
        );
        
        return $pdf->download($filename);
    }
    
    public function bulkPdf(Group $group, Period $period)
    {
        $studentIds = $group->students()
            ->where('status', 'active')
            ->pluck('id')
            ->toArray();
        
        $pdf = $this->pdfService->generateBulk($studentIds, $period);
        
        $filename = sprintf(
            'boletines_%s_%s_%s.pdf',
            $group->full_name,
            $period->name,
            now()->format('Ymd')
        );
        
        return $pdf->download($filename);
    }
}
```

---

## Template PDF del Boletín

### resources/views/pdf/report-card.blade.php

```blade
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Boletín - {{ $student->user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #333;
        }
        
        .page {
            padding: 15px;
        }
        
        /* Encabezado */
        .header {
            display: table;
            width: 100%;
            border-bottom: 2px solid #1a365d;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        .header-logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
        }
        
        .header-logo img {
            max-width: 70px;
            max-height: 70px;
        }
        
        .header-info {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        
        .header-info h1 {
            font-size: 14px;
            color: #1a365d;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        
        .header-info p {
            font-size: 9px;
            color: #666;
        }
        
        .header-period {
            display: table-cell;
            width: 120px;
            vertical-align: middle;
            text-align: right;
        }
        
        .period-badge {
            background: #1a365d;
            color: white;
            padding: 8px 12px;
            font-size: 11px;
            font-weight: bold;
            border-radius: 4px;
        }
        
        /* Información del estudiante */
        .student-info {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px;
            margin-bottom: 15px;
        }
        
        .student-info table {
            width: 100%;
        }
        
        .student-info td {
            padding: 3px 5px;
        }
        
        .student-info .label {
            font-weight: bold;
            color: #4a5568;
            width: 100px;
        }
        
        .student-info .value {
            color: #1a202c;
        }
        
        /* Tabla de notas */
        .grades-section {
            margin-bottom: 15px;
        }
        
        .section-title {
            background: #1a365d;
            color: white;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 0;
        }
        
        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        
        .grades-table th,
        .grades-table td {
            border: 1px solid #cbd5e0;
            padding: 5px 8px;
            text-align: left;
        }
        
        .grades-table th {
            background: #edf2f7;
            font-weight: bold;
            color: #2d3748;
            font-size: 9px;
            text-transform: uppercase;
        }
        
        .grades-table td {
            font-size: 10px;
        }
        
        .grades-table .grade-cell {
            text-align: center;
            font-weight: bold;
            width: 50px;
        }
        
        .grades-table .performance-cell {
            width: 120px;
            font-size: 9px;
        }
        
        .area-header {
            background: #e2e8f0;
            font-weight: bold;
            color: #2d3748;
        }
        
        /* Colores de desempeño */
        .performance-superior { color: #276749; background: #c6f6d5; }
        .performance-alto { color: #2b6cb0; background: #bee3f8; }
        .performance-basico { color: #975a16; background: #fefcbf; }
        .performance-bajo { color: #c53030; background: #fed7d7; }
        
        /* Resumen */
        .summary-section {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .summary-box {
            display: table-cell;
            width: 25%;
            padding: 5px;
        }
        
        .summary-card {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px;
            text-align: center;
        }
        
        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #1a365d;
        }
        
        .summary-card .label {
            font-size: 8px;
            color: #718096;
            text-transform: uppercase;
        }
        
        /* Asistencia */
        .attendance-section {
            margin-bottom: 15px;
        }
        
        .attendance-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .attendance-table th,
        .attendance-table td {
            border: 1px solid #cbd5e0;
            padding: 5px 10px;
            text-align: center;
            font-size: 9px;
        }
        
        .attendance-table th {
            background: #edf2f7;
            font-weight: bold;
        }
        
        /* Observaciones */
        .observations-section {
            margin-bottom: 15px;
        }
        
        .observations-box {
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 10px;
            min-height: 60px;
            background: #fffaf0;
        }
        
        .observations-box p {
            font-size: 9px;
            color: #744210;
        }
        
        /* Firmas */
        .signatures-section {
            display: table;
            width: 100%;
            margin-top: 30px;
        }
        
        .signature-box {
            display: table-cell;
            width: 33%;
            text-align: center;
            padding: 0 15px;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-bottom: 5px;
            padding-top: 5px;
        }
        
        .signature-name {
            font-weight: bold;
            font-size: 9px;
        }
        
        .signature-role {
            font-size: 8px;
            color: #666;
        }
        
        /* Pie de página */
        .footer {
            position: fixed;
            bottom: 10px;
            left: 15px;
            right: 15px;
            text-align: center;
            font-size: 8px;
            color: #a0aec0;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- Encabezado -->
        <div class="header">
            <div class="header-logo">
                @if($institution->logo)
                    <img src="{{ storage_path('app/public/' . $institution->logo) }}" alt="Logo">
                @else
                    <div style="width:70px;height:70px;background:#e2e8f0;border-radius:4px;"></div>
                @endif
            </div>
            <div class="header-info">
                <h1>{{ $institution->name }}</h1>
                <p>NIT: {{ $institution->nit }} - DANE: {{ $institution->dane_code }}</p>
                <p>{{ $institution->address }} - {{ $institution->city }}, {{ $institution->department }}</p>
                <p>Tel: {{ $institution->phone }} - {{ $institution->email }}</p>
                <p style="margin-top:5px;font-weight:bold;font-size:11px;">INFORME ACADÉMICO</p>
            </div>
            <div class="header-period">
                <div class="period-badge">
                    {{ $period->name }}<br>
                    {{ $period->academicYear->year }}
                </div>
            </div>
        </div>
        
        <!-- Información del estudiante -->
        <div class="student-info">
            <table>
                <tr>
                    <td class="label">Estudiante:</td>
                    <td class="value" colspan="3"><strong>{{ $student->user->name }}</strong></td>
                </tr>
                <tr>
                    <td class="label">Documento:</td>
                    <td class="value">{{ $student->user->document_type }} {{ $student->user->document_number }}</td>
                    <td class="label">Código:</td>
                    <td class="value">{{ $student->enrollment_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td class="label">Grado:</td>
                    <td class="value">{{ $grade->name }}</td>
                    <td class="label">Grupo:</td>
                    <td class="value">{{ $group->name }}</td>
                </tr>
                <tr>
                    <td class="label">Director(a):</td>
                    <td class="value" colspan="3">{{ $director?->user?->name ?? 'Sin asignar' }}</td>
                </tr>
            </table>
        </div>
        
        <!-- Resumen -->
        <div class="summary-section">
            <div class="summary-box">
                <div class="summary-card">
                    <div class="value">{{ $periodAverage ?? '-' }}</div>
                    <div class="label">Promedio</div>
                </div>
            </div>
            <div class="summary-box">
                <div class="summary-card">
                    <div class="value" style="font-size:12px;">{{ $performanceLevel ?? '-' }}</div>
                    <div class="label">Desempeño</div>
                </div>
            </div>
            <div class="summary-box">
                <div class="summary-card">
                    <div class="value">{{ $ranking }}/{{ $totalStudents }}</div>
                    <div class="label">Puesto</div>
                </div>
            </div>
            <div class="summary-box">
                <div class="summary-card">
                    <div class="value">{{ $attendance['percentage'] }}%</div>
                    <div class="label">Asistencia</div>
                </div>
            </div>
        </div>
        
        <!-- Tabla de notas por área -->
        <div class="grades-section">
            <div class="section-title">VALORACIÓN ACADÉMICA</div>
            <table class="grades-table">
                <thead>
                    <tr>
                        <th style="width:40%;">Asignatura</th>
                        <th class="text-center" style="width:15%;">Nota</th>
                        <th style="width:25%;">Desempeño</th>
                        <th style="width:20%;">Observaciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gradesByArea as $areaName => $records)
                        <tr class="area-header">
                            <td colspan="4">{{ $areaName }}</td>
                        </tr>
                        @foreach($records as $record)
                            @php
                                $perfClass = match(true) {
                                    $record->grade >= 4.6 => 'performance-superior',
                                    $record->grade >= 4.0 => 'performance-alto',
                                    $record->grade >= 3.0 => 'performance-basico',
                                    default => 'performance-bajo',
                                };
                            @endphp
                            <tr>
                                <td style="padding-left:20px;">{{ $record->subject->name }}</td>
                                <td class="grade-cell {{ $perfClass }}">{{ number_format($record->grade, 1) }}</td>
                                <td class="performance-cell {{ $perfClass }}">{{ $record->performance_label }}</td>
                                <td style="font-size:8px;">{{ Str::limit($record->observations, 50) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Asistencia -->
        <div class="attendance-section">
            <div class="section-title">ASISTENCIA DEL PERÍODO</div>
            <table class="attendance-table">
                <thead>
                    <tr>
                        <th>Días Hábiles</th>
                        <th>Asistencias</th>
                        <th>Inasistencias</th>
                        <th>Tardanzas</th>
                        <th>Excusas</th>
                        <th>% Asistencia</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $attendance['total_days'] }}</td>
                        <td style="color:green;">{{ $attendance['present'] }}</td>
                        <td style="color:red;">{{ $attendance['absent'] }}</td>
                        <td style="color:orange;">{{ $attendance['late'] }}</td>
                        <td style="color:blue;">{{ $attendance['excused'] }}</td>
                        <td><strong>{{ $attendance['percentage'] }}%</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Asignaturas con bajo rendimiento -->
        @if($failingSubjects->count() > 0)
            <div class="observations-section">
                <div class="section-title" style="background:#c53030;">ASIGNATURAS CON DESEMPEÑO BAJO</div>
                <div class="observations-box" style="background:#fff5f5;border-color:#feb2b2;">
                    <p>
                        El estudiante presenta desempeño bajo en las siguientes asignaturas y debe realizar actividades de nivelación:
                        <strong>{{ $failingSubjects->pluck('subject.name')->join(', ') }}</strong>
                    </p>
                </div>
            </div>
        @endif
        
        <!-- Observaciones generales -->
        <div class="observations-section">
            <div class="section-title">OBSERVACIONES DEL DIRECTOR DE GRUPO</div>
            <div class="observations-box">
                <p>
                    _________________________________________________________________________________________________________
                    _________________________________________________________________________________________________________
                    _________________________________________________________________________________________________________
                </p>
            </div>
        </div>
        
        <!-- Escala de valoración -->
        <div style="font-size:8px;color:#666;margin-top:10px;">
            <strong>ESCALA DE VALORACIÓN (Decreto 1290):</strong>
            Desempeño Superior (4.6 - 5.0) |
            Desempeño Alto (4.0 - 4.5) |
            Desempeño Básico (3.0 - 3.9) |
            Desempeño Bajo (1.0 - 2.9)
        </div>
        
        <!-- Firmas -->
        <div class="signatures-section">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">{{ $director?->user?->name ?? '____________________' }}</div>
                <div class="signature-role">Director(a) de Grupo</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">____________________</div>
                <div class="signature-role">Coordinador(a) Académico</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-name">____________________</div>
                <div class="signature-role">Rector(a)</div>
            </div>
        </div>
        
        <!-- Pie de página -->
        <div class="footer">
            Documento generado el {{ $generatedAt }} | {{ $institution->name }} | Sistema de Gestión Académica
        </div>
    </div>
</body>
</html>
```

---

## Seeders

### database/seeders/DatabaseSeeder.php

```php
<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Institution;
use App\Models\AcademicYear;
use App\Models\Period;
use App\Models\Grade;
use App\Models\Group;
use App\Models\Area;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Institución
        $institution = Institution::create([
            'name' => 'Institución Educativa Ejemplo',
            'nit' => '900.123.456-7',
            'dane_code' => '108001001234',
            'address' => 'Calle 45 # 23-15',
            'phone' => '605 3456789',
            'email' => 'contacto@colegioejemplo.edu.co',
            'city' => 'Barranquilla',
            'department' => 'Atlántico',
            'rector_name' => 'María Elena Rodríguez',
        ]);
        
        // 2. Usuario Admin
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'document_type' => 'CC',
            'document_number' => '1234567890',
        ]);
        
        // 3. Año académico
        $year = AcademicYear::create([
            'institution_id' => $institution->id,
            'year' => 2025,
            'start_date' => '2025-01-20',
            'end_date' => '2025-11-28',
            'is_active' => true,
        ]);
        
        // 4. Períodos
        $periods = [
            ['name' => 'Primer Período', 'number' => 1, 'weight' => 25, 'start_date' => '2025-01-20', 'end_date' => '2025-03-28'],
            ['name' => 'Segundo Período', 'number' => 2, 'weight' => 25, 'start_date' => '2025-04-01', 'end_date' => '2025-06-13'],
            ['name' => 'Tercer Período', 'number' => 3, 'weight' => 25, 'start_date' => '2025-07-07', 'end_date' => '2025-09-12'],
            ['name' => 'Cuarto Período', 'number' => 4, 'weight' => 25, 'start_date' => '2025-09-15', 'end_date' => '2025-11-28'],
        ];
        
        foreach ($periods as $p) {
            Period::create(array_merge($p, ['academic_year_id' => $year->id]));
        }
        
        // 5. Grados
        $gradesData = [
            ['name' => 'Preescolar', 'short_name' => 'Pre', 'order' => 0, 'level' => 'preescolar'],
            ['name' => 'Transición', 'short_name' => 'Trans', 'order' => 1, 'level' => 'preescolar'],
            ['name' => 'Primero', 'short_name' => '1°', 'order' => 2, 'level' => 'primaria'],
            ['name' => 'Segundo', 'short_name' => '2°', 'order' => 3, 'level' => 'primaria'],
            ['name' => 'Tercero', 'short_name' => '3°', 'order' => 4, 'level' => 'primaria'],
            ['name' => 'Cuarto', 'short_name' => '4°', 'order' => 5, 'level' => 'primaria'],
            ['name' => 'Quinto', 'short_name' => '5°', 'order' => 6, 'level' => 'primaria'],
            ['name' => 'Sexto', 'short_name' => '6°', 'order' => 7, 'level' => 'secundaria'],
            ['name' => 'Séptimo', 'short_name' => '7°', 'order' => 8, 'level' => 'secundaria'],
            ['name' => 'Octavo', 'short_name' => '8°', 'order' => 9, 'level' => 'secundaria'],
            ['name' => 'Noveno', 'short_name' => '9°', 'order' => 10, 'level' => 'secundaria'],
            ['name' => 'Décimo', 'short_name' => '10°', 'order' => 11, 'level' => 'media'],
            ['name' => 'Undécimo', 'short_name' => '11°', 'order' => 12, 'level' => 'media'],
        ];
        
        foreach ($gradesData as $g) {
            Grade::create(array_merge($g, ['institution_id' => $institution->id]));
        }
        
        // 6. Áreas y Asignaturas
        $areasData = [
            'Matemáticas' => ['Matemáticas', 'Geometría', 'Estadística'],
            'Humanidades' => ['Lengua Castellana', 'Inglés', 'Lectura Crítica'],
            'Ciencias Naturales' => ['Biología', 'Química', 'Física'],
            'Ciencias Sociales' => ['Historia', 'Geografía', 'Democracia'],
            'Educación Artística' => ['Artes Plásticas', 'Música'],
            'Educación Física' => ['Educación Física'],
            'Tecnología' => ['Informática', 'Tecnología'],
            'Ética y Valores' => ['Ética', 'Religión'],
        ];
        
        $grades = Grade::all();
        
        foreach ($areasData as $areaName => $subjects) {
            $area = Area::create([
                'institution_id' => $institution->id,
                'name' => $areaName,
            ]);
            
            foreach ($subjects as $subjectName) {
                foreach ($grades as $grade) {
                    Subject::create([
                        'area_id' => $area->id,
                        'grade_id' => $grade->id,
                        'name' => $subjectName,
                        'weekly_hours' => rand(2, 5),
                    ]);
                }
            }
        }
        
        // 7. Crear grupos (2 por grado)
        foreach ($grades as $grade) {
            foreach (['A', 'B'] as $groupName) {
                Group::create([
                    'grade_id' => $grade->id,
                    'academic_year_id' => $year->id,
                    'name' => $groupName,
                    'capacity' => 35,
                ]);
            }
        }
        
        // 8. Crear docentes
        $teacherNames = [
            'María García', 'Carlos Rodríguez', 'Ana Martínez', 
            'Luis Hernández', 'Patricia López'
        ];
        
        foreach ($teacherNames as $name) {
            $user = User::create([
                'name' => $name,
                'email' => Str::slug($name) . '@colegioejemplo.edu.co',
                'password' => bcrypt('password'),
                'role' => 'teacher',
                'document_type' => 'CC',
                'document_number' => fake()->numerify('##########'),
            ]);
            
            Teacher::create([
                'user_id' => $user->id,
                'institution_id' => $institution->id,
            ]);
        }
        
        // 9. Crear estudiantes (10 por grupo)
        $firstNames = [
            'Santiago', 'Valentina', 'Matías', 'Sofía', 'Samuel',
            'Isabella', 'Nicolás', 'Mariana', 'Alejandro', 'Luciana',
            'Sebastián', 'Gabriela', 'Daniel', 'Valeria', 'Tomás',
            'Camila', 'Martín', 'Sara', 'Joaquín', 'Paula'
        ];
        
        $lastNames = [
            'García', 'Rodríguez', 'Martínez', 'López', 'González',
            'Hernández', 'Pérez', 'Sánchez', 'Ramírez', 'Torres'
        ];
        
        foreach (Group::all() as $group) {
            for ($i = 0; $i < 10; $i++) {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)] . ' ' . $lastNames[array_rand($lastNames)];
                
                $user = User::create([
                    'name' => "$firstName $lastName",
                    'email' => Str::slug("$firstName-$lastName") . '-' . Str::random(4) . '@estudiante.edu.co',
                    'password' => bcrypt('password'),
                    'role' => 'guardian',
                    'document_type' => 'TI',
                    'document_number' => fake()->numerify('##########'),
                ]);
                
                Student::create([
                    'user_id' => $user->id,
                    'group_id' => $group->id,
                    'enrollment_date' => '2025-01-15',
                    'status' => 'active',
                ]);
            }
        }
    }
}
```

---

## Instalación de Dependencias

```bash
# DomPDF para generación de boletines
sail composer require barryvdh/laravel-dompdf

# Publicar configuración
sail artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## Prioridad de Desarrollo

| Semana | Tareas |
|--------|--------|
| **1** | Migraciones, Modelos, Seeders, API Institución/Años/Períodos/Grados/Grupos/Asignaturas |
| **2** | API Estudiantes/Docentes, Frontend CRUD básico, Middleware de roles |
| **3** | Registro de notas (API + Frontend), Asistencia, Cálculos automáticos |
| **4** | Boletines PDF, Portal acudientes, Reportes consolidados, Testing |

---

## Comandos para Iniciar

```bash
# Backend
cp .env.example .env
sail up -d
sail composer install
sail artisan key:generate
sail artisan migrate --seed
sail artisan storage:link

# Frontend
cd frontend
pnpm install
cp .env.example .env
pnpm dev
```

---

## Credenciales por Defecto

- **Admin:** admin@example.com / password
- **Docentes:** [nombre]@colegioejemplo.edu.co / password

---

## Notas Importantes

1. **Validar notas:** Siempre entre 1.0 y 5.0 con un decimal
2. **Decreto 1290:** Escala cualitativa automática según nota numérica
3. **Puesto:** Se calcula ordenando por promedio descendente
4. **Boletines:** Usar DomPDF, no Snappy (requiere wkhtmltopdf)
5. **Soft deletes:** Estudiantes solo cambian de estado, no se eliminan
6. **Asistencia:** Calcular porcentaje = (presentes + excusas) / total días

Comienza creando las migraciones en el orden especificado, luego los modelos con sus relaciones Eloquent.
