# ⚙️ Backend

This folder contains the backend components used by the **Young Innovators Senior Project**.

The backend supports core platform functionality such as authentication, user roles, course management, progress tracking, parental features, provider functionality, game progress, and communication with AI-powered services.

---

## 📌 Backend Responsibilities

The backend handles functionality including:

- User registration and login
- Role-based user access
- Student profiles
- Parent-child account linking
- Content Provider features
- Course creation and enrollment
- Student progress tracking
- XP and achievement data
- Game progress
- Administrative functionality
- Database communication
- Integration with AI-related features

---

# 👥 Role-Based System

Young Innovators supports four primary user roles:

```text
Young Innovators
       │
       ├── Student
       ├── Parent
       ├── Content Provider
       └── Administrator
```

Each role has access to different platform features.

---

# 👨‍🎓 Student Features

The backend supports student functionality such as:

- Viewing available courses
- Enrolling in courses
- Tracking learning progress
- Saving game progress
- Managing XP and achievements
- Accessing personalized learning features

---

# 👨‍👩‍👧 Parent Features

Parent functionality includes:

- Linking parent and child accounts
- Viewing student progress
- Monitoring enrolled courses
- Viewing learning activity
- Supporting parent-related communication features

---

# 👨‍🏫 Content Provider Features

The Content Provider backend supports:

- Course creation
- Educational content management
- PDF uploads
- Generated learning content
- Game-related content
- Provider analytics

The Content Provider workflow also connects with the AI system used for **PDF-to-Game generation**.

---

# 👨‍💼 Administrator Features

Administrator functionality includes:

- User management
- Platform content management
- Provider management
- Monitoring uploaded content
- Administrative controls

---

# 🤖 Backend and AI Integration

The web backend works alongside separate Python AI services.

A simplified architecture is:

```text
Web Interface
     │
     ▼
PHP Backend
     │
     ├──────────────► MySQL Database
     │
     ▼
Python / Flask AI Services
     │
     ▼
OpenAI API
     │
     ▼
Generated Educational Content
```

This allows the web platform, database, AI components, and educational games to work together.

---

# 🛠️ Technologies

- PHP
- MySQL
- phpMyAdmin
- Python
- Flask APIs
- JavaScript
- HTTP/API communication

---

# 📂 Backend Structure

The backend is organized into API endpoints and supporting components for different platform features.

```text
backend/
│
├── README.md
├── api/
│   ├── auth/
│   ├── user/
│   ├── student/
│   ├── parent/
│   ├── provider/
│   ├── courses/
│   ├── game/
│   └── admin/
│
└── database.sql
```

The exact files inside each API section implement the functionality required by the corresponding part of the platform.

---

# 🗄️ Database

Young Innovators uses a **MySQL relational database**.

The database stores information related to:

- Users
- Roles
- Students
- Parent-child relationships
- Courses
- Enrollments
- Student progress
- XP and achievements
- Uploaded educational content
- Generated game information
- Platform communication

The database schema is available in:

📄 [`database.sql`](./database.sql)

---

# 🔐 Security

Sensitive credentials should not be committed to this repository.

Files containing:

- Database passwords
- API keys
- Tokens
- Environment variables
- Private credentials

should remain local or be replaced with safe example values.

---

# 👩‍💻 My Contribution

Young Innovators was developed collaboratively as a team Senior Project.

My primary responsibility focused on the **Artificial Intelligence components**, including the PDF-to-Game system and AI assistants.

I also contributed to parts of the web platform and integration between:

- AI services
- Frontend interfaces
- Backend functionality
- Educational games

---

## 🔗 Related Sections

[🤖 **View AI Components →**](../AI/)

[🎮 **View Educational Games →**](../games/)

[🌐 **View Frontend →**](../frontend/)

[**← Back to Young Innovators Project**](../)
