# Database Schema Documentation

## Overview
This document provides a comprehensive overview of the database schema for the Research Portal application.

## Table Relationships

### Core Entity Relationships
```
Users (1) ←→ (1) Scholars
Users (1) ←→ (1) Supervisors
Scholars (1) ←→ (M) Thesis Submissions
Thesis Submissions (1) ←→ (M) Thesis Submission Certificates
Scholars (1) ←→ (1) Office Notes
Scholars (M) ←→ (M) Supervisors (through supervisor_assignments)
```

## Table Definitions

### 1. users
**Purpose:** User authentication and basic profile information

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| role_id | unsignedBigInteger | FOREIGN KEY → roles.id | User role reference |
| name | varchar(255) | NOT NULL | Full name |
| email | varchar(255) | UNIQUE, NOT NULL | Email address |
| user_type | enum | NOT NULL | scholar, supervisor, hod, da, so, ar, dr, hvc |
| email_verified_at | timestamp | NULLABLE | Email verification timestamp |
| password | varchar(255) | NOT NULL | Hashed password |
| remember_token | varchar(100) | NULLABLE | Remember me token |
| created_at | timestamp | NULLABLE | Creation timestamp |
| updated_at | timestamp | NULLABLE | Last update timestamp |

### 2. roles
**Purpose:** User role definitions

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| name | varchar(255) | NOT NULL | Role name |
| description | text | NULLABLE | Role description |
| created_at | timestamp | NULLABLE | Creation timestamp |
| updated_at | timestamp | NULLABLE | Last update timestamp |

### 3. scholars
**Purpose:** Scholar profiles and Ph.D. registration data

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| user_id | bigint unsigned | FOREIGN KEY → users.id | User reference |
| admission_id | bigint unsigned | FOREIGN KEY → admissions.id | Admission reference |
| enrollment_number | varchar(255) | UNIQUE, NULLABLE | Enrollment number |
| first_name | varchar(255) | NOT NULL | First name |
| last_name | varchar(255) | NOT NULL | Last name |
| date_of_birth | date | NULLABLE | Date of birth |
| gender | enum | NULLABLE | male, female, other |
| contact_number | varchar(255) | NULLABLE | Contact number |
| address | text | NULLABLE | Address |
| research_area | varchar(255) | NULLABLE | Research area |
| enrollment_status | enum | DEFAULT 'pending' | pending, enrolled, completed |
| registration_form_status | enum | DEFAULT 'not_started' | not_started, in_progress, submitted, under_review, approved |
| registration_form_submitted_at | timestamp | NULLABLE | Form submission timestamp |
| [40+ Ph.D. Registration Fields] | various | NULLABLE | Registration form data |
| created_at | timestamp | NULLABLE | Creation timestamp |
| updated_at | timestamp | NULLABLE | Last update timestamp |

### 4. thesis_submissions
**Purpose:** Thesis submission records and approval workflow

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| scholar_id | bigint unsigned | FOREIGN KEY → scholars.id | Scholar reference |
| supervisor_id | bigint unsigned | FOREIGN KEY → supervisors.id | Supervisor reference |
| hod_id | bigint unsigned | FOREIGN KEY → users.id | HOD reference |
| title | varchar(500) | NOT NULL | Thesis title |
| abstract | text | NOT NULL | Thesis abstract |
| file_path | varchar(500) | NOT NULL | Thesis file path |
| supporting_documents | json | NULLABLE | Supporting documents |
| status | enum | DEFAULT 'pending_supervisor_approval' | Submission status |
| submission_date | timestamp | NOT NULL | Submission timestamp |
| is_resubmission | boolean | DEFAULT false | Is resubmission flag |
| rejection_count | integer | DEFAULT 0 | Rejection count |
| [Approval Workflow Fields] | various | NULLABLE | Approval workflow data |
| created_at | timestamp | NULLABLE | Creation timestamp |
| updated_at | timestamp | NULLABLE | Last update timestamp |

### 5. thesis_submission_certificates
**Purpose:** Generated certificates for thesis submissions

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| scholar_id | bigint unsigned | FOREIGN KEY → scholars.id | Scholar reference |
| thesis_submission_id | bigint unsigned | FOREIGN KEY → thesis_submissions.id | Thesis reference |
| certificate_type | enum | NOT NULL | pre_phd_presentation, research_papers, peer_reviewed_journal |
| status | enum | DEFAULT 'pending' | pending, approved, generated |
| certificate_data | json | NULLABLE | Certificate-specific data |
| generated_file_path | varchar(500) | NULLABLE | Generated file path |
| generated_at | timestamp | NULLABLE | Generation timestamp |
| generated_by | bigint unsigned | FOREIGN KEY → users.id | Generator reference |
| remarks | text | NULLABLE | Additional remarks |
| created_at | timestamp | NULLABLE | Creation timestamp |
| updated_at | timestamp | NULLABLE | Last update timestamp |

### 6. office_notes
**Purpose:** Official registration notes generated by DA

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| scholar_id | bigint unsigned | FOREIGN KEY → scholars.id | Scholar reference |
| file_number | varchar(255) | NULLABLE | File number |
| dated | date | NULLABLE | Note date |
| candidate_name | varchar(255) | NULLABLE | Candidate name |
| research_subject | varchar(500) | NULLABLE | Research subject |
| supervisor_name | varchar(255) | NULLABLE | Supervisor name |
| supervisor_designation | varchar(255) | NULLABLE | Supervisor designation |
| supervisor_address | text | NULLABLE | Supervisor address |
| supervisor_retirement_date | date | NULLABLE | Supervisor retirement date |
| co_supervisor_name | varchar(255) | NULLABLE | Co-supervisor name |
| co_supervisor_designation | varchar(255) | NULLABLE | Co-supervisor designation |
| co_supervisor_address | text | NULLABLE | Co-supervisor address |
| co_supervisor_retirement_date | date | NULLABLE | Co-supervisor retirement date |
| [Academic Details] | various | NULLABLE | Academic information |
| [Administrative Details] | various | NULLABLE | Administrative information |
| status | enum | DEFAULT 'draft' | draft, generated, approved |
| notes | text | NULLABLE | Additional notes |
| created_at | timestamp | NULLABLE | Creation timestamp |
| updated_at | timestamp | NULLABLE | Last update timestamp |

### 7. supervisors
**Purpose:** Supervisor information and capacity

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| user_id | bigint unsigned | FOREIGN KEY → users.id | User reference |
| department_id | bigint unsigned | FOREIGN KEY → departments.id | Department reference |
| designation | varchar(255) | NULLABLE | Designation |
| specialization | varchar(255) | NULLABLE | Specialization |
| max_scholars | integer | DEFAULT 5 | Maximum scholars |
| current_scholars | integer | DEFAULT 0 | Current scholars count |
| supervisor_type | enum | DEFAULT 'internal' | internal, external |
| created_at | timestamp | NULLABLE | Creation timestamp |
| updated_at | timestamp | NULLABLE | Last update timestamp |

### 8. supervisor_assignments
**Purpose:** Scholar-supervisor relationships

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| scholar_id | bigint unsigned | FOREIGN KEY → scholars.id | Scholar reference |
| supervisor_id | bigint unsigned | FOREIGN KEY → supervisors.id | Supervisor reference |
| assigned_date | date | NOT NULL | Assignment date |
| status | enum | DEFAULT 'pending' | pending, assigned, rejected |
| justification | text | NULLABLE | Assignment justification |
| remarks | text | NULLABLE | Additional remarks |
| drc_minutes_uploaded | boolean | DEFAULT false | DRC minutes uploaded |
| created_at | timestamp | NULLABLE | Creation timestamp |
| updated_at | timestamp | NULLABLE | Last update timestamp |

### 9. departments
**Purpose:** Academic departments

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| name | varchar(255) | NOT NULL | Department name |
| code | varchar(10) | UNIQUE, NOT NULL | Department code |
| description | text | NULLABLE | Department description |
| hod_id | bigint unsigned | FOREIGN KEY → users.id | HOD reference |
| created_at | timestamp | NULLABLE | Creation timestamp |
| updated_at | timestamp | NULLABLE | Last update timestamp |

### 10. admissions
**Purpose:** Admission records

| Column | Type | Constraints | Description |
|--------|------|-------------|-------------|
| id | bigint unsigned | PRIMARY KEY, AUTO_INCREMENT | Unique identifier |
| scholar_id | bigint unsigned | FOREIGN KEY → scholars.id | Scholar reference |
| department_id | bigint unsigned | FOREIGN KEY → departments.id | Department reference |
| admission_date | date | NOT NULL | Admission date |
| session | varchar(20) | NOT NULL | Academic session |
| status | enum | DEFAULT 'active' | active, completed, withdrawn |
| created_at | timestamp | NULLABLE | Creation timestamp |
| updated_at | timestamp | NULLABLE | Last update timestamp |

## Indexes

### Primary Indexes
- All tables have `id` as PRIMARY KEY
- All foreign key columns are indexed

### Unique Indexes
- `users.email`
- `scholars.enrollment_number`
- `departments.code`

### Composite Indexes
- `thesis_submissions(scholar_id, status)`
- `supervisor_assignments(scholar_id, supervisor_id)`
- `thesis_submission_certificates(scholar_id, thesis_submission_id)`

## Constraints

### Foreign Key Constraints
```sql
-- Users to Roles
ALTER TABLE users ADD CONSTRAINT users_role_id_foreign 
FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE;

-- Scholars to Users
ALTER TABLE scholars ADD CONSTRAINT scholars_user_id_foreign 
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE;

-- Scholars to Admissions
ALTER TABLE scholars ADD CONSTRAINT scholars_admission_id_foreign 
FOREIGN KEY (admission_id) REFERENCES admissions(id) ON DELETE SET NULL;

-- Thesis Submissions to Scholars
ALTER TABLE thesis_submissions ADD CONSTRAINT thesis_submissions_scholar_id_foreign 
FOREIGN KEY (scholar_id) REFERENCES scholars(id) ON DELETE CASCADE;

-- Thesis Submission Certificates
ALTER TABLE thesis_submission_certificates ADD CONSTRAINT thesis_submission_certificates_scholar_id_foreign 
FOREIGN KEY (scholar_id) REFERENCES scholars(id) ON DELETE CASCADE;

ALTER TABLE thesis_submission_certificates ADD CONSTRAINT thesis_submission_certificates_thesis_submission_id_foreign 
FOREIGN KEY (thesis_submission_id) REFERENCES thesis_submissions(id) ON DELETE CASCADE;
```

## Data Types

### Enums
- **user_type:** scholar, supervisor, hod, da, so, ar, dr, hvc
- **enrollment_status:** pending, enrolled, completed
- **registration_form_status:** not_started, in_progress, submitted, under_review, approved
- **thesis_status:** pending_supervisor_approval, supervisor_approved, hod_approved, da_approved, so_approved, ar_approved, dr_approved, hvc_approved, rejected
- **certificate_type:** pre_phd_presentation, research_papers, peer_reviewed_journal
- **supervisor_type:** internal, external

### JSON Fields
- **scholars.registration_documents:** Array of uploaded document paths
- **thesis_submissions.supporting_documents:** Array of supporting document paths
- **thesis_submission_certificates.certificate_data:** Certificate-specific data

## Sample Data

### Default Roles
```sql
INSERT INTO roles (name, description) VALUES
('scholar', 'Research Scholar'),
('supervisor', 'Research Supervisor'),
('hod', 'Head of Department'),
('da', 'Dean of Academic Affairs'),
('so', 'Section Officer'),
('ar', 'Assistant Registrar'),
('dr', 'Deputy Registrar'),
('hvc', 'Higher Verification Committee');
```

### Default Users
```sql
-- Scholar
INSERT INTO users (role_id, name, email, user_type, password) VALUES
(1, 'Test Scholar', 'scholar@example.com', 'scholar', '$2y$10$...');

-- Supervisor
INSERT INTO users (role_id, name, email, user_type, password) VALUES
(2, 'Test Supervisor', 'supervisor@example.com', 'supervisor', '$2y$10$...');

-- DA
INSERT INTO users (role_id, name, email, user_type, password) VALUES
(4, 'Test DA', 'da@example.com', 'da', '$2y$10$...');
```

## Migration Files

### Core Migrations
- `0001_01_01_000000_create_users_table.php`
- `2025_09_02_202805_create_roles_table.php`
- `2025_09_02_202949_create_scholars_table.php`
- `2025_09_02_202949_create_departments_table.php`
- `2025_09_02_202949_create_supervisors_table.php`
- `2025_09_02_202949_create_admissions_table.php`
- `2025_09_02_202949_create_thesis_submissions_table.php`
- `2025_09_15_210119_create_thesis_submission_certificates_table.php`
- `2025_09_15_055444_create_office_notes_table.php`

### Foreign Key Migrations
- `2025_09_15_095019_add_foreign_key_constraint_scholars_admission_id.php`
- `2025_09_15_095317_add_foreign_key_constraint_users_role_id.php`
- `2025_09_15_095336_add_foreign_key_constraint_admissions_scholar_id.php`
- `2025_09_15_095357_add_foreign_key_constraint_admissions_department_id.php`

## Performance Considerations

### Query Optimization
- Use indexes on frequently queried columns
- Implement proper foreign key constraints
- Use appropriate data types
- Consider partitioning for large tables

### Storage Optimization
- Use JSON for flexible data storage
- Implement file cleanup for old documents
- Consider archiving for completed records

## Backup Strategy

### Daily Backups
```bash
# Database backup
mysqldump -u root -p uniraj_res > backup_$(date +%Y%m%d).sql

# File storage backup
tar -czf storage_backup_$(date +%Y%m%d).tar.gz storage/
```

### Recovery Procedures
```bash
# Restore database
mysql -u root -p uniraj_res < backup_20240101.sql

# Restore files
tar -xzf storage_backup_20240101.tar.gz
```

---

*Last Updated: January 2025*
*Schema Version: 1.0.0*
