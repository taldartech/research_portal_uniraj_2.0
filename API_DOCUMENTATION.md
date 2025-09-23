# API Documentation - Research Portal

## Table of Contents
1. [Authentication](#authentication)
2. [Scholar Endpoints](#scholar-endpoints)
3. [Supervisor Endpoints](#supervisor-endpoints)
4. [DA Endpoints](#da-endpoints)
5. [Common Response Formats](#common-response-formats)
6. [Error Handling](#error-handling)
7. [Rate Limiting](#rate-limiting)

---

## Authentication

### Overview
The Research Portal uses Laravel Breeze for authentication with session-based authentication.

### Login
**Endpoint:** `POST /scholar/login`

**Request Body:**
```json
{
    "email": "scholar@example.com",
    "password": "password",
    "remember": false
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "Test Scholar",
        "email": "scholar@example.com",
        "user_type": "scholar"
    }
}
```

### Logout
**Endpoint:** `POST /scholar/logout`

**Response:**
```json
{
    "status": "success",
    "message": "Logged out successfully"
}
```

---

## Scholar Endpoints

### Profile Management

#### Get Profile
**Endpoint:** `GET /scholar/profile`

**Response:**
```json
{
    "scholar": {
        "id": 1,
        "user_id": 1,
        "enrollment_number": "2024001",
        "first_name": "John",
        "last_name": "Doe",
        "date_of_birth": "1995-01-01",
        "gender": "male",
        "contact_number": "+91-9876543210",
        "address": "123 Main St, City, State",
        "research_area": "Computer Science",
        "enrollment_status": "enrolled",
        "registration_form_status": "submitted",
        "created_at": "2024-01-01T00:00:00.000000Z",
        "updated_at": "2024-01-01T00:00:00.000000Z"
    }
}
```

#### Update Profile
**Endpoint:** `PATCH /scholar/profile`

**Request Body:**
```json
{
    "first_name": "John",
    "last_name": "Doe",
    "date_of_birth": "1995-01-01",
    "gender": "male",
    "contact_number": "+91-9876543210",
    "address": "123 Main St, City, State",
    "research_area": "Computer Science"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Profile updated successfully",
    "scholar": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "updated_at": "2024-01-01T12:00:00.000000Z"
    }
}
```

### Ph.D. Registration Form

#### Get Registration Form
**Endpoint:** `GET /scholar/registration/phd-form`

**Response:**
```json
{
    "scholar": {
        "id": 1,
        "registration_form_status": "not_started",
        "father_name": null,
        "mother_name": null,
        "nationality": null,
        "category": null,
        "occupation": null,
        "is_teacher": false,
        "teacher_employer": null,
        "appearing_other_exam": false,
        "other_exam_details": null,
        "research_topic_title": null,
        "phd_faculty": null,
        "phd_subject": null,
        "supervisor_name": null,
        "supervisor_designation": null,
        "supervisor_department": null,
        "supervisor_college": null,
        "supervisor_address": null,
        "supervisor_letter_number": null,
        "supervisor_letter_date": null,
        "has_co_supervisor": false,
        "co_supervisor_name": null,
        "co_supervisor_designation": null,
        "co_supervisor_department": null,
        "co_supervisor_college": null,
        "co_supervisor_address": null,
        "co_supervisor_letter_number": null,
        "co_supervisor_letter_date": null,
        "undergraduate_degree": null,
        "undergraduate_university": null,
        "undergraduate_year": null,
        "undergraduate_percentage": null,
        "post_graduate_degree": null,
        "post_graduate_university": null,
        "post_graduate_year": null,
        "post_graduate_percentage": null,
        "net_slet_csir_gate_year": null,
        "net_slet_csir_gate_roll_number": null,
        "net_slet_csir_gate_score": null,
        "coursework_marks_obtained": null,
        "mpat_merit_number": null,
        "registration_documents": []
    }
}
```

#### Submit Registration Form
**Endpoint:** `POST /scholar/registration/phd-form`

**Request Body:**
```json
{
    "father_name": "Robert Doe",
    "mother_name": "Jane Doe",
    "nationality": "Indian",
    "category": "General",
    "occupation": "Student",
    "is_teacher": false,
    "appearing_other_exam": false,
    "research_topic_title": "Machine Learning Applications",
    "phd_faculty": "Science",
    "phd_subject": "Computer Science",
    "supervisor_name": "Dr. Smith",
    "supervisor_designation": "Professor",
    "supervisor_department": "Computer Science",
    "supervisor_college": "University of Rajasthan",
    "supervisor_address": "Department of Computer Science, University of Rajasthan",
    "supervisor_letter_number": "LTR/2024/001",
    "supervisor_letter_date": "2024-01-01",
    "has_co_supervisor": false,
    "undergraduate_degree": "B.Tech",
    "undergraduate_university": "University of Rajasthan",
    "undergraduate_year": "2017",
    "undergraduate_percentage": "85.5",
    "post_graduate_degree": "M.Tech",
    "post_graduate_university": "University of Rajasthan",
    "post_graduate_year": "2019",
    "post_graduate_percentage": "88.2",
    "net_slet_csir_gate_year": "2019",
    "net_slet_csir_gate_roll_number": "123456789",
    "net_slet_csir_gate_score": "850",
    "coursework_marks_obtained": "85",
    "mpat_merit_number": "001",
    "registration_documents": ["document1.pdf", "document2.pdf"],
    "action": "submit"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Registration form submitted successfully",
    "scholar": {
        "id": 1,
        "registration_form_status": "submitted",
        "registration_form_submitted_at": "2024-01-01T12:00:00.000000Z"
    }
}
```

### Thesis Submission

#### Get Thesis Submission Form
**Endpoint:** `GET /scholar/thesis/submission-form`

**Response:**
```json
{
    "scholar": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "enrollment_number": "2024001",
        "canSubmitThesis": true
    }
}
```

#### Submit Thesis
**Endpoint:** `POST /scholar/thesis/submit-new`

**Request Body (multipart/form-data):**
```
title: "Advanced Machine Learning Techniques"
abstract: "This thesis presents novel approaches to machine learning..."
thesis_file: [file]
supporting_documents[]: [file1, file2, ...]
```

**Response:**
```json
{
    "status": "success",
    "message": "Thesis submitted successfully. Waiting for supervisor approval.",
    "thesis": {
        "id": 1,
        "scholar_id": 1,
        "title": "Advanced Machine Learning Techniques",
        "abstract": "This thesis presents novel approaches to machine learning...",
        "status": "pending_supervisor_approval",
        "submission_date": "2024-01-01T12:00:00.000000Z"
    }
}
```

#### Get Thesis Status
**Endpoint:** `GET /scholar/thesis/submissions/status`

**Response:**
```json
{
    "scholar": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe"
    },
    "thesisSubmissions": [
        {
            "id": 1,
            "title": "Advanced Machine Learning Techniques",
            "abstract": "This thesis presents novel approaches...",
            "status": "supervisor_approved",
            "submission_date": "2024-01-01T12:00:00.000000Z",
            "certificates": [
                {
                    "id": 1,
                    "certificate_type": "pre_phd_presentation",
                    "status": "generated",
                    "generated_at": "2024-01-02T10:00:00.000000Z"
                }
            ]
        }
    ]
}
```

### Certificate Generation

#### Generate Certificate
**Endpoint:** `POST /scholar/thesis/{thesis}/generate-certificate`

**Request Body:**
```json
{
    "certificate_type": "pre_phd_presentation",
    "certificate_data": {
        "presentation_date": "2024-01-15",
        "venue": "Department of Computer Science, University of Rajasthan"
    }
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Certificate generated successfully",
    "certificate": {
        "id": 1,
        "scholar_id": 1,
        "thesis_submission_id": 1,
        "certificate_type": "pre_phd_presentation",
        "status": "generated",
        "generated_at": "2024-01-02T10:00:00.000000Z"
    }
}
```

#### View Certificate
**Endpoint:** `GET /scholar/thesis/certificate/{certificate}`

**Response:**
```json
{
    "certificate": {
        "id": 1,
        "certificate_type": "pre_phd_presentation",
        "certificate_type_name": "Pre-Ph.D. Presentation Certificate",
        "status": "generated",
        "certificate_data": {
            "presentation_date": "2024-01-15",
            "venue": "Department of Computer Science, University of Rajasthan"
        },
        "generated_at": "2024-01-02T10:00:00.000000Z",
        "scholar": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "enrollment_number": "2024001"
        },
        "thesisSubmission": {
            "id": 1,
            "title": "Advanced Machine Learning Techniques"
        }
    }
}
```

#### Download Certificate
**Endpoint:** `GET /scholar/thesis/certificate/{certificate}/download`

**Response:** PDF file download

---

## Supervisor Endpoints

### Scholar Management

#### List Assigned Scholars
**Endpoint:** `GET /supervisor/scholars`

**Response:**
```json
{
    "scholars": [
        {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "enrollment_number": "2024001",
            "research_area": "Computer Science",
            "registration_form_status": "submitted",
            "thesis_submissions_count": 1,
            "assigned_date": "2024-01-01"
        }
    ]
}
```

#### Edit Scholar Form
**Endpoint:** `GET /supervisor/scholars/{scholar}/form`

**Response:**
```json
{
    "scholar": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "registration_form_status": "submitted",
        "research_topic_title": "Machine Learning Applications",
        "supervisor_name": "Dr. Smith",
        "supervisor_designation": "Professor",
        "supervisor_department": "Computer Science",
        "supervisor_college": "University of Rajasthan",
        "supervisor_address": "Department of Computer Science, University of Rajasthan",
        "supervisor_letter_number": "LTR/2024/001",
        "supervisor_letter_date": "2024-01-01",
        "has_co_supervisor": false,
        "co_supervisor_name": null,
        "co_supervisor_designation": null,
        "co_supervisor_department": null,
        "co_supervisor_college": null,
        "co_supervisor_address": null,
        "co_supervisor_letter_number": null,
        "co_supervisor_letter_date": null,
        "undergraduate_degree": "B.Tech",
        "undergraduate_university": "University of Rajasthan",
        "undergraduate_year": "2017",
        "undergraduate_percentage": "85.5",
        "post_graduate_degree": "M.Tech",
        "post_graduate_university": "University of Rajasthan",
        "post_graduate_year": "2019",
        "post_graduate_percentage": "88.2",
        "net_slet_csir_gate_year": "2019",
        "net_slet_csir_gate_roll_number": "123456789",
        "net_slet_csir_gate_score": "850",
        "coursework_marks_obtained": "85",
        "mpat_merit_number": "001",
        "registration_documents": ["document1.pdf", "document2.pdf"]
    }
}
```

#### Update Scholar Form
**Endpoint:** `PATCH /supervisor/scholars/{scholar}/form`

**Request Body:**
```json
{
    "research_topic_title": "Advanced Machine Learning Applications",
    "supervisor_name": "Dr. John Smith",
    "supervisor_designation": "Professor",
    "supervisor_department": "Computer Science",
    "supervisor_college": "University of Rajasthan",
    "supervisor_address": "Department of Computer Science, University of Rajasthan",
    "supervisor_letter_number": "LTR/2024/002",
    "supervisor_letter_date": "2024-01-02",
    "action": "approve"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Scholar form updated successfully",
    "scholar": {
        "id": 1,
        "registration_form_status": "under_review",
        "updated_at": "2024-01-02T12:00:00.000000Z"
    }
}
```

---

## DA Endpoints

### Office Note Management

#### List Eligible Scholars
**Endpoint:** `GET /da/office-notes/eligible-scholars`

**Response:**
```json
{
    "scholars": [
        {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "enrollment_number": "2024001",
            "registration_form_status": "approved",
            "research_topic_title": "Advanced Machine Learning Applications",
            "supervisor": {
                "id": 1,
                "user": {
                    "name": "Dr. John Smith"
                }
            },
            "user": {
                "email": "scholar@example.com"
            }
        }
    ]
}
```

#### Generate Office Note
**Endpoint:** `GET /da/office-notes/generate/{scholar}`

**Response:**
```json
{
    "scholar": {
        "id": 1,
        "first_name": "John",
        "last_name": "Doe",
        "enrollment_number": "2024001",
        "research_topic_title": "Advanced Machine Learning Applications",
        "supervisor_name": "Dr. John Smith",
        "supervisor_designation": "Professor",
        "supervisor_address": "Department of Computer Science, University of Rajasthan",
        "post_graduate_university": "University of Rajasthan",
        "post_graduate_degree": "M.Tech",
        "post_graduate_percentage": "88.2",
        "net_slet_csir_gate_year": "2019",
        "net_slet_csir_gate_roll_number": "123456789",
        "coursework_marks_obtained": "85",
        "mpat_merit_number": "001"
    }
}
```

#### Store Office Note
**Endpoint:** `POST /da/office-notes/generate/{scholar}`

**Request Body:**
```json
{
    "file_number": "ON/2024/001",
    "dated": "2024-01-15",
    "supervisor_retirement_date": "2030-12-31",
    "co_supervisor_retirement_date": null,
    "drc_approval_date": "2024-01-10",
    "registration_fee_receipt_number": "RFR/2024/001",
    "registration_fee_date": "2024-01-05",
    "commencement_date": "2024-01-20",
    "enrollment_number": "2024001",
    "supervisor_registration_page_number": "15",
    "supervisor_seats_available": 3,
    "candidates_under_guidance": 2,
    "notes": "Additional notes for office note"
}
```

**Response:**
```json
{
    "status": "success",
    "message": "Office Note generated successfully",
    "officeNote": {
        "id": 1,
        "scholar_id": 1,
        "file_number": "ON/2024/001",
        "dated": "2024-01-15",
        "candidate_name": "John Doe",
        "research_subject": "Advanced Machine Learning Applications",
        "supervisor_name": "Dr. John Smith",
        "supervisor_designation": "Professor",
        "supervisor_address": "Department of Computer Science, University of Rajasthan",
        "status": "generated",
        "created_at": "2024-01-15T10:00:00.000000Z"
    }
}
```

#### View Office Note
**Endpoint:** `GET /da/office-notes/{officeNote}`

**Response:**
```json
{
    "officeNote": {
        "id": 1,
        "file_number": "ON/2024/001",
        "dated": "2024-01-15",
        "candidate_name": "John Doe",
        "research_subject": "Advanced Machine Learning Applications",
        "supervisor_name": "Dr. John Smith",
        "supervisor_designation": "Professor",
        "supervisor_address": "Department of Computer Science, University of Rajasthan",
        "supervisor_retirement_date": "2030-12-31",
        "co_supervisor_name": null,
        "co_supervisor_designation": null,
        "co_supervisor_address": null,
        "co_supervisor_retirement_date": null,
        "ug_university": "University of Rajasthan",
        "ug_class": "B.A./B.Sc./B.Com",
        "ug_marks": "N/A",
        "ug_percentage": "N/A",
        "ug_division": "N/A",
        "pg_university": "University of Rajasthan",
        "pg_class": "M.Tech",
        "pg_marks": "88.2",
        "pg_percentage": "88.2",
        "pg_division": "First",
        "pat_year": "2019",
        "pat_merit_number": "123456789",
        "coursework_marks_obtained": "85",
        "coursework_merit_number": "001",
        "drc_approval_date": "2024-01-10",
        "registration_fee_receipt_number": "RFR/2024/001",
        "registration_fee_date": "2024-01-05",
        "commencement_date": "2024-01-20",
        "enrollment_number": "2024001",
        "supervisor_registration_page_number": "15",
        "supervisor_seats_available": 3,
        "candidates_under_guidance": 2,
        "status": "generated",
        "notes": "Additional notes for office note",
        "scholar": {
            "id": 1,
            "first_name": "John",
            "last_name": "Doe",
            "enrollment_number": "2024001"
        }
    }
}
```

#### Download Office Note
**Endpoint:** `GET /da/office-notes/{officeNote}/download`

**Response:** PDF file download

---

## Common Response Formats

### Success Response
```json
{
    "status": "success",
    "message": "Operation completed successfully",
    "data": {
        // Response data
    }
}
```

### Error Response
```json
{
    "status": "error",
    "message": "Error description",
    "errors": {
        "field_name": ["Validation error message"]
    }
}
```

### Validation Error Response
```json
{
    "message": "The given data was invalid.",
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password field is required."]
    }
}
```

---

## Error Handling

### HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | OK - Request successful |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid request data |
| 401 | Unauthorized - Authentication required |
| 403 | Forbidden - Access denied |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation errors |
| 500 | Internal Server Error - Server error |

### Common Error Messages

| Error | Description |
|-------|-------------|
| `UNAUTHORIZED` | User not authenticated |
| `FORBIDDEN` | User lacks permission |
| `NOT_FOUND` | Resource not found |
| `VALIDATION_ERROR` | Request data validation failed |
| `FILE_TOO_LARGE` | Uploaded file exceeds size limit |
| `INVALID_FILE_TYPE` | Uploaded file type not allowed |
| `DATABASE_ERROR` | Database operation failed |

---

## Rate Limiting

### Default Limits
- **API Requests:** 60 requests per minute per IP
- **Login Attempts:** 5 attempts per minute per IP
- **File Uploads:** 10 uploads per hour per user

### Rate Limit Headers
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1640995200
```

### Rate Limit Exceeded Response
```json
{
    "status": "error",
    "message": "Too Many Attempts.",
    "retry_after": 60
}
```

---

## File Upload

### Supported File Types
- **Documents:** PDF, DOC, DOCX
- **Images:** JPG, JPEG, PNG
- **Maximum Size:** 50MB per file

### Upload Endpoints
- **Registration Documents:** `POST /scholar/registration/phd-form`
- **Thesis Files:** `POST /scholar/thesis/submit-new`
- **Supporting Documents:** `POST /scholar/thesis/submit-new`

### File Upload Response
```json
{
    "status": "success",
    "message": "File uploaded successfully",
    "file_path": "storage/registration_documents/document.pdf",
    "file_size": 1024000,
    "file_type": "application/pdf"
}
```

---

## Pagination

### Request Parameters
- `page`: Page number (default: 1)
- `per_page`: Items per page (default: 15, max: 100)

### Response Format
```json
{
    "data": [
        // Array of items
    ],
    "current_page": 1,
    "last_page": 5,
    "per_page": 15,
    "total": 75,
    "from": 1,
    "to": 15,
    "links": {
        "first": "http://api.example.com/endpoint?page=1",
        "last": "http://api.example.com/endpoint?page=5",
        "prev": null,
        "next": "http://api.example.com/endpoint?page=2"
    }
}
```

---

## Webhooks

### Available Webhooks
- `thesis.submitted` - When a thesis is submitted
- `thesis.approved` - When a thesis is approved
- `certificate.generated` - When a certificate is generated
- `office_note.generated` - When an office note is generated

### Webhook Payload
```json
{
    "event": "thesis.submitted",
    "timestamp": "2024-01-01T12:00:00.000000Z",
    "data": {
        "thesis_id": 1,
        "scholar_id": 1,
        "title": "Advanced Machine Learning Techniques",
        "status": "pending_supervisor_approval"
    }
}
```

---

## SDKs and Libraries

### PHP
```bash
composer require research-portal/sdk
```

### JavaScript
```bash
npm install @research-portal/sdk
```

### Python
```bash
pip install research-portal-sdk
```

---

## Testing

### Test Environment
- **Base URL:** `https://staging-api.research-portal.com`
- **Test Credentials:** Available in test documentation

### Postman Collection
- **Collection URL:** `https://api.postman.com/collections/12345678`
- **Environment:** Available for download

---

*Last Updated: January 2025*
*API Documentation Version: 1.0.0*
