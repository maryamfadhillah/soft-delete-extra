# Laravel Custom Soft Delete

A simple Laravel package that extends the default **SoftDeletes** functionality by adding custom fields such as `is_deleted` and `deleted_by` while still keeping `deleted_at` from Laravel's native implementation.

---

## Features

- Extends Laravel's native **SoftDeletes**.
- Automatically fills:
  - `is_deleted` → mark row as deleted (1 = deleted, 0 = active).
  - `deleted_by` → stores the user ID who deleted the record.
- Still keeps `deleted_at` for compatibility with Laravel's query builder and Eloquent methods.
- Can be reused across multiple models using a single trait.

---

## Installation

1. Install via Composer:

```bash
composer require maryamfadhillah/soft-delete-extra
```

2. Add the trait to your Model

```
use Illuminate\Database\Eloquent\Model;
use App\Traits\CustomSoftDelete;

class Product extends Model
{
    use CustomSoftDelete;

    protected $fillable = [
        'name',
        'deleted_by',
        'is_deleted',
        // ...
    ];
}
```

----

## Database Requirement

Make sure your table contains the following columns in addition to your normal fields:

```
ALTER TABLE products
ADD deleted_at DATETIME NULL,
ADD deleted_by INT NULL,
ADD is_deleted TINYINT DEFAULT 0;
```

----

## Usage

It's completely the same as Laravel's Soft Delete.
