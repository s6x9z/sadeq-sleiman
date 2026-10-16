# 🎮 Educational Games

This folder contains the interactive educational games developed for the **Young Innovators Senior Project**.

The games are designed to work with educational content generated through the platform's **AI-powered PDF-to-Game system**.

Instead of manually creating new questions for every lesson, educational content can be extracted from an uploaded PDF, processed by AI, converted into structured game data, and loaded into an interactive game template.

---

## 🧠 How the Games Connect to AI

```text
Educational PDF
       │
       ▼
Text Extraction
       │
       ▼
OpenAI Processing
       │
       ▼
Generated Educational Content
       │
       ▼
Structured Game Data
       │
       ▼
Selected Game Template
       │
       ▼
Interactive Learning Game
```

This approach separates the **educational content** from the **game interface**.

The same game template can therefore be used with different lesson material generated from different PDFs.

---

# 🔐 Escape Room

📁 [**View Escape Room Files →**](./escape-room/)

The Escape Room game transforms educational questions into a themed progression experience.

Students move through different rooms by answering questions based on the uploaded educational material.

### Features

- AI-generated educational questions
- Multiple stages or rooms
- Hints
- Progression through the game
- Dynamic lesson content
- Interactive learning experience

---

# 🥊 Boxing Game

📁 [**View Boxing Game Files →**](./boxing/)

The Boxing Game combines educational questions with a boxing-themed interactive experience.

Students answer questions while progressing through the game.

Correct responses help the player continue while reinforcing concepts from the lesson material.

### Features

- Educational questions
- Interactive gameplay
- AI-generated content
- Game progression
- Lesson-based learning

---

# 🐍 Snake Game

📁 [**View Snake Game Files →**](./snake/)

The Snake Game integrates educational questions into a Snake-style game.

Students interact with the game while identifying answers related to the current educational question.

### Features

- Interactive Snake gameplay
- Educational questions
- Generated lesson content
- Visual interaction with answers
- Game-based learning

---

# ✏️ Fill in the Blank

📁 [**View Fill in the Blank Files →**](./fill-in-the-blank/)

The Fill in the Blank activity transforms educational material into interactive questions where students identify missing concepts or words.

### Features

- AI-generated educational content
- Fill-in-the-blank questions
- Lesson-based answers
- Simple interactive learning
- Reusable game structure

---

# 🔄 Dynamic Game Content

The games were designed so that the educational material does not need to be permanently written into the game code.

Instead, generated data can be loaded into the game.

```text
Game Template
     +
Generated Lesson Data
     │
     ▼
Playable Educational Activity
```

This makes it possible to reuse game templates with different educational topics.

---

# 🛠️ Technologies Used

- HTML5
- CSS3
- JavaScript
- AI-generated structured data
- CSV-based game content
- Python-generated educational content

---

# 📂 Folder Structure

```text
games/
│
├── README.md
│
├── escape-room/
│
├── boxing/
│
├── snake/
│
└── fill-in-the-blank/
```

---

# 🤖 Relationship to My AI Work

My primary role in Young Innovators focused on the Artificial Intelligence components of the system.

A key part of that work involved connecting generated educational content with the game system.

The goal was to create a workflow where:

**PDF → AI → Structured Content → Game**

This allowed educational material uploaded by Content Providers to become interactive learning content rather than remaining static documents.

---

## 🔗 Related Sections

[🤖 **View AI Components →**](../AI/)

[**← Back to Young Innovators Project**](../)
