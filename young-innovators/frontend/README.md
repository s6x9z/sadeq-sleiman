# 🌐 Frontend

This folder contains the frontend interface for the **Young Innovators Senior Project**.

The web platform provides separate interfaces for Students, Parents, Content Providers, and Administrators while connecting users with courses, educational games, progress tracking, and AI-powered features.

---

## ✨ Main Interfaces

### 🏠 Home Page

The main landing page introduces Young Innovators and provides navigation to the platform.

### 🔐 Authentication

The platform includes login and registration interfaces for the different user roles.

### 👨‍🎓 Student Dashboard

Students can:

- Browse and enroll in courses
- Access educational games
- Track learning progress
- Earn XP and badges
- Access the Student AI Learning Buddy

### 👨‍👩‍👧 Parent Dashboard

Parents can:

- Manage linked child accounts
- Monitor student progress
- View learning activity and achievements

### 👨‍🏫 Content Provider Dashboard

Content Providers can:

- Manage educational courses
- Upload educational PDF material
- Generate game content using AI
- View course activity
- Access the Content Provider AI Assistant

The Content Provider interface is particularly important to the AI workflow because it connects the web platform to the **PDF-to-Game generation service**.

### 👨‍💼 Administrator Dashboard

Administrators can manage platform users and content.

---

# 🤖 AI Integration

The frontend communicates with the AI services using JavaScript and HTTP requests.

For example, the Content Provider workflow follows:

```text
Content Provider Dashboard
           │
           ▼
      Upload PDF
           │
           ▼
      JavaScript
           │
           ▼
      Flask AI API
           │
           ▼
   Generated Game Data
           │
           ▼
      Game Template
```

The platform also contains interfaces for the:

- Student AI Learning Buddy
- Content Provider AI Assistant

These interfaces communicate with their corresponding Python AI services.

---

# 🛠️ Technologies

- HTML5
- CSS3
- JavaScript
- REST/API communication
- Flask AI services
- PHP backend APIs

---

# 📂 Main Files

```text
frontend/
├── index.html
├── login.html
├── register.html
├── student-dashboard.html
├── parent-dashboard.html
├── provider-dashboard.html
├── admin-dashboard.html
├── browse-courses.html
├── child-chatbot.html
├── provider-chatbot.html
├── style.css
├── script.js
├── security.js
└── logo.png
```

---

## 👩‍💻 My Contribution

Young Innovators was developed collaboratively as a team Senior Project.

My main responsibility was the Artificial Intelligence side of the platform, while I also contributed to parts of the website and the integration between the web interface, AI services, and educational games.

A particularly important part of my website work involved the **Content Provider workflow**, where uploaded PDFs are sent to the AI service and the generated content is connected to the selected educational game.

---

## 🔗 Related Sections

[🤖 **View AI Components →**](../AI/)

[🎮 **View Educational Games →**](../games/)

[**← Back to Young Innovators Project**](../)
