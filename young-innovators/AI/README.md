# 🤖 Artificial Intelligence Components

This folder contains the Artificial Intelligence components I worked on for the **Young Innovators Senior Project** at **Beirut Arab University**.

My main responsibility in the project focused on developing and integrating AI functionality into the educational platform, particularly the **PDF-to-Game generation system**, AI assistants, and the connection between AI-generated educational content and interactive learning games.

---

## 📌 Overview

The AI component of Young Innovators was designed to make educational content more interactive.

Instead of requiring Content Providers to manually create game content, the platform allows educational material to be uploaded as a PDF. The system extracts the lesson content, processes it using Artificial Intelligence, and converts the generated output into structured data that can be used by educational game templates.

The platform also includes separate AI assistants for **Students** and **Content Providers**, with different behaviors depending on the role of the user.

---

# 📄 PDF-to-Game Generation

📁 [**View PDF-to-Game Code →**](./pdf-to-game/)

The **PDF-to-Game Generation System** is one of the main AI features I worked on for Young Innovators.

Its purpose is to transform educational material from a PDF document into structured educational content that can be loaded into interactive games.

## 🔄 Workflow

```text
Educational PDF
       │
       ▼
   PDF Upload
       │
       ▼
  Text Extraction
    pdfplumber
       │
       ▼
   OpenAI API
       │
       ▼
AI-Generated Educational Content
       │
       ▼
Structured Game Data
       │
       ▼
Educational Game Template
       │
       ▼
Playable Learning Game
```

### 1. PDF Upload

A Content Provider uploads an educational PDF through the platform.

The uploaded document contains the lesson material that will be used to generate the educational game content.

### 2. Text Extraction

The PDF is processed using **Python** and **pdfplumber**.

The educational text is extracted from the document and prepared so that it can be sent to the AI model.

### 3. AI Processing

The extracted lesson content is sent to the **OpenAI API**.

Structured prompts are used to instruct the AI to generate educational content based on the uploaded lesson material.

The goal is to ensure that the generated questions and activities remain related to the original educational content.

### 4. Structured Game Data

The AI response is transformed into structured data that can be understood by the game system.

This allows the generated educational content to be separated from the game itself.

Instead of manually rewriting a game for every lesson, different educational material can be generated and loaded into the existing game templates.

### 5. Interactive Game

The structured educational content is then connected to an interactive game template.

This allows the same game system to work with different lessons and topics depending on the PDF uploaded by the Content Provider.

---

## 🎯 PDF-to-Game Responsibilities

My work on this feature included:

- Processing uploaded educational PDF documents
- Extracting lesson content using `pdfplumber`
- Preparing extracted content for AI processing
- Integrating the OpenAI API
- Designing prompts for educational content generation
- Generating questions and answers from lesson material
- Converting AI responses into structured game data
- Connecting generated content with interactive game templates
- Testing different uploaded PDFs
- Debugging the PDF-to-game generation workflow
- Integrating the AI service with parts of the web platform

---

## 🛠️ PDF-to-Game Technologies

- Python
- OpenAI API
- pdfplumber
- Flask
- Flask-CORS
- python-dotenv
- Prompt Engineering

---

# 🧒 Student AI Learning Buddy

📁 [**View Student Assistant Code →**](./student-assistant/)

The **Student AI Learning Buddy** is an AI assistant designed to support students while using the Young Innovators platform.

Because the platform is intended for younger learners, the assistant uses dedicated AI instructions designed to make responses easier to understand and more appropriate for an educational environment.

## 🧠 Student Assistant Behavior

The Student Learning Buddy is designed to:

- Explain concepts using simple language
- Provide clear and understandable responses
- Guide students step by step
- Use examples when explaining concepts
- Encourage students during the learning process
- Focus conversations on educational topics
- Support learning rather than acting as a general-purpose chatbot

The Student Assistant uses its own AI configuration and prompt behavior separate from the Content Provider Assistant.

---

# 👨‍🏫 Content Provider AI Assistant

📁 [**View Provider Assistant Code →**](./provider-assistant/)

The **Content Provider AI Assistant** is designed for educators and Content Providers using the Young Innovators platform.

Its role is different from the Student Learning Buddy because it focuses on educational content preparation and learning activities rather than explaining lessons to students.

## 🧠 Content Provider Assistant Behavior

The assistant is designed to support tasks such as:

- Understanding educational material
- Identifying important lesson concepts
- Supporting educational content preparation
- Organizing learning material
- Planning educational activities
- Supporting game-content preparation
- Helping structure learning experiences

The Content Provider Assistant uses its own dedicated AI prompt and configuration.

---

# 🔀 Role-Specific AI Design

Young Innovators uses separate AI behavior for different types of users.

```text
                 Young Innovators AI
                         │
             ┌───────────┴───────────┐
             │                       │
             ▼                       ▼
     Student Assistant       Content Provider
                              Assistant
             │                       │
             ▼                       ▼
 Simple explanations        Content preparation
 Learning guidance          Lesson organization
 Student support            Activity planning
 Educational examples       Game preparation
```

This separation allows each assistant to behave according to the needs of its intended user.

---

# 🎮 AI and Game Integration

The AI system was developed to work together with the educational games in Young Innovators.

The AI generates educational content from lesson material, while the game templates provide the interactive experience.

```text
            Uploaded PDF
                 │
                 ▼
         Educational Content
                 │
                 ▼
           AI Generation
                 │
                 ▼
        Structured Game Data
                 │
                 ▼
        Selected Game Template
                 │
                 ▼
        Interactive Learning
              Experience
```

Educational game types developed for the platform include:

- 🔐 Escape Room
- 🥊 Boxing Game
- 🐍 Snake Game
- ✏️ Fill in the Blank

This approach separates the **educational content** from the **game interface**, making it possible to generate different learning content without rebuilding the entire game.

---

# 🌐 AI and Web Integration

The AI functionality was integrated into the Young Innovators web platform using Python services and web APIs.

A simplified interaction looks like this:

```text
Content Provider
       │
       ▼
   Web Interface
       │
       ▼
   Flask API
       │
       ▼
 PDF Processing
       │
       ▼
   OpenAI API
       │
       ▼
Generated Game Data
       │
       ▼
 Educational Game
```

This required communication between the website, the Python AI services, generated educational data, and the game system.

---

# 📂 Folder Structure

```text
AI/
│
├── README.md
│
├── pdf-to-game/
│   └── app.py
│
├── student-assistant/
│   └── chatbot.py
│
└── provider-assistant/
    └── chatbot.py
```

### 📄 `pdf-to-game/`

Contains the main AI workflow responsible for processing uploaded PDF material and generating structured educational game content.

### 🧒 `student-assistant/`

Contains the AI functionality for the Student Learning Buddy.

### 👨‍🏫 `provider-assistant/`

Contains the AI functionality for the Content Provider Assistant.

---

# 🛠️ Technologies Used

| Technology | Purpose |
|---|---|
| **Python** | AI services and PDF processing |
| **OpenAI API** | AI-generated educational content and assistant functionality |
| **pdfplumber** | Extracting text from PDF documents |
| **Flask** | Providing web API endpoints for AI services |
| **Flask-CORS** | Supporting communication between web components and Flask services |
| **python-dotenv** | Loading environment variables securely |
| **Prompt Engineering** | Controlling AI behavior and generated content |

---

# 🔐 API Key Security

API credentials are not stored directly inside the source code.

Environment variables are used instead.

Example:

```python
import os

api_key = os.getenv("OPENAI_KEY_PROVIDER")
```

Sensitive files such as:

```text
.env
```

should remain local and should **not be uploaded to GitHub**.

An example environment configuration may look like:

```text
OPENAI_KEY_PROVIDER=YOUR_API_KEY
OPENAI_KEY_CHILD=YOUR_API_KEY
```

These values are examples only. Real API credentials should never be committed to the repository.

---

# 💡 What I Learned

Working on the AI components of Young Innovators gave me practical experience applying Artificial Intelligence inside a complete software system rather than using AI as a standalone tool.

Through this project, I gained hands-on experience with:

- Large Language Model API integration
- Prompt engineering
- PDF processing
- Extracting and preparing educational text
- Structuring AI-generated output
- Flask API development
- Communication between frontend and AI services
- Role-specific AI assistants
- Dynamic educational content generation
- Integrating AI-generated content into interactive games
- Debugging AI workflows
- Testing AI responses with different educational material
- Integrating AI functionality into a full-stack platform

One of the most valuable parts of the project was learning how to transform AI-generated information into **structured data that could be used directly by an interactive application**.

---

# 👩‍💻 My Contribution

Young Innovators was developed collaboratively as a **team Senior Project**.

My primary focus was the Artificial Intelligence side of the platform.

My contributions included:

- AI-powered PDF-to-game generation
- PDF content extraction and processing
- OpenAI API integration
- Prompt design and AI behavior
- Educational content generation
- Structured game-data generation
- Integration between generated content and game templates
- Student AI Learning Buddy
- Content Provider AI Assistant
- Role-specific AI configurations
- Flask AI services
- AI-to-website integration
- Testing and debugging AI functionality
- Contributions to parts of the web platform

---

## 🔗 Return to Young Innovators

[**← Back to Young Innovators Project**](../)
