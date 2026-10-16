# 🔐 Cipher Forensics & Attack Recommendation Engine

## Classical Cipher Detection and Cryptanalysis Recommendation System

The **Cipher Forensics & Attack Recommendation Engine** is a Python-based cybersecurity project developed for the **Computer Security (CMPS455)** course at **Beirut Arab University**.

The project analyzes encrypted text, examines letter-frequency and ciphertext patterns, estimates the most likely **classical cipher family**, explains the reasoning behind the classification, and recommends an appropriate cryptanalysis approach.

The system focuses on three classical encryption techniques:

- 🔄 Caesar Cipher
- 🔤 Monoalphabetic Substitution Cipher
- 🔀 Transposition Cipher

Rather than simply performing encryption or decryption, the project focuses on the **forensic analysis of ciphertext** and the reasoning process that can be used when investigating classical encryption methods.

---

# 📌 Project Overview

When an unknown encrypted message is encountered, one of the first steps in cryptanalysis is understanding the characteristics of the ciphertext.

Different encryption techniques can create different statistical and structural patterns.

This project was designed to analyze those characteristics automatically using Python.

The engine performs:

- Ciphertext preprocessing
- Letter-frequency analysis
- Unique-letter analysis
- Pattern inspection
- Rule-based cipher-family estimation
- Classification explanation
- Cryptanalysis strategy recommendation

The result is a simple educational tool that demonstrates how ciphertext characteristics can be analyzed before attempting cryptanalysis.

---

# 🎯 Project Objectives

The main objectives of the project were to:

- Apply Computer Security concepts using Python
- Analyze encrypted text programmatically
- Calculate letter frequencies in ciphertext
- Examine patterns in encrypted messages
- Estimate the most likely classical cipher family
- Explain the reasoning behind the estimated classification
- Recommend a suitable cryptanalysis approach
- Demonstrate practical classical cryptanalysis concepts
- Combine analysis, classification, explanation, and recommendation in one application

---

# 👩‍💻 My Contribution

I was responsible for the **complete development, implementation, testing, analysis, documentation, and presentation of this project**.

My work included:

- Designing the Cipher Forensics & Attack Recommendation Engine
- Implementing the complete application using Python
- Developing ciphertext preprocessing logic
- Implementing letter-frequency analysis
- Counting and analyzing unique letters
- Designing rule-based cipher-family detection
- Supporting Caesar, Monoalphabetic Substitution, and Transposition cipher families
- Generating explanations for cipher classifications
- Designing cryptanalysis recommendation logic
- Mapping cipher families to appropriate attack strategies
- Developing the command-line interface
- Testing the program using different ciphertext examples
- Analyzing and interpreting program results
- Preparing the project report
- Preparing the project presentation

This project allowed me to turn theoretical concepts from **cryptography and cryptanalysis** into a practical cybersecurity application.

---

# 🧠 System Workflow

The engine follows a simple forensic-analysis workflow:

```text
                   Ciphertext
                       │
                       ▼
                Text Processing
                       │
                       ▼
               Frequency Analysis
                       │
                       ▼
              Unique Letter Count
                       │
                       ▼
                Pattern Analysis
                       │
                       ▼
                Cipher Detection
                       │
          ┌────────────┼────────────┐
          │            │            │
          ▼            ▼            ▼
       Caesar     Substitution  Transposition
          │            │            │
          └────────────┼────────────┘
                       ▼
                Explain Decision
                       │
                       ▼
             Recommend Attack
                       │
                       ▼
                    Result
```

---

# 🔎 Ciphertext Analysis

Before attempting to classify the cipher, the program analyzes the encrypted text.

The analysis focuses on alphabetic characters and examines how frequently different letters occur.

A simplified process looks like:

```text
Encrypted Message
       │
       ▼
Read Characters
       │
       ▼
Keep Alphabetic Characters
       │
       ▼
Normalize Letter Case
       │
       ▼
Count Letter Frequency
       │
       ▼
Analyze Character Distribution
```

This produces information that can be used during the cipher-family estimation process.

---

# 📊 Frequency Analysis

**Frequency analysis** studies how often characters appear in encrypted text.

Natural languages contain statistical patterns. For example, some letters appear significantly more often than others.

Classical substitution-based encryption may preserve parts of these frequency relationships even when the letters themselves are changed.

The program creates a frequency table representing how many times each letter appears in the ciphertext.

Example:

```text
A : 4
E : 7
H : 2
R : 5
T : 6
```

This information can then be used together with other ciphertext characteristics during analysis.

---

# 🔢 Unique Letter Analysis

The engine also determines how many different alphabetic characters occur in the ciphertext.

For example:

```text
Ciphertext:
KHOOR

Unique Letters:
K, H, O, R

Number of Unique Letters:
4
```

The number and distribution of letters provide simple statistical characteristics that can be incorporated into rule-based cipher detection.

---

# 🧩 Pattern-Based Detection

The program uses a **rule-based approach** to estimate the most likely cipher family.

The analysis combines characteristics such as:

- Letter frequencies
- Number of unique letters
- Character variation
- Ciphertext patterns

The classification process can be represented as:

```text
Ciphertext
     │
     ▼
Frequency Analysis
     │
     ▼
Unique Letter Analysis
     │
     ▼
Pattern Evaluation
     │
     ▼
Rule-Based Classification
     │
     ▼
Estimated Cipher Family
```

This approach is intentionally lightweight and was developed to demonstrate the reasoning process behind basic cipher analysis.

---

# 🔄 Caesar Cipher

A **Caesar Cipher** is a substitution cipher in which every plaintext letter is shifted by the same number of positions in the alphabet.

For example:

```text
Plaintext:
HELLO

Shift:
+3

Ciphertext:
KHOOR
```

Because the same shift is applied to every letter, Caesar encryption has a very limited key space compared with modern encryption systems.

## 🔎 Recommended Analysis

For ciphertext estimated to use Caesar encryption, the engine recommends examining the possible alphabet shifts.

```text
Detected Family
      │
      ▼
 Caesar Cipher
      │
      ▼
Test Possible Shifts
      │
      ▼
Examine Candidate Plaintext
```

---

# 🔤 Monoalphabetic Substitution Cipher

A **Monoalphabetic Substitution Cipher** replaces every plaintext letter with another letter according to a fixed substitution mapping.

Unlike a Caesar Cipher, the substitution is not based on one constant shift.

For example:

```text
Plain Alphabet:
ABCDEFGHIJKLMNOPQRSTUVWXYZ

Substitution:
QWERTYUIOPASDFGHJKLZXCVBNM
```

Every occurrence of the same plaintext letter is replaced by the same corresponding ciphertext letter.

## 🔎 Recommended Analysis

Because substitution ciphers may preserve statistical patterns from the original language, **frequency analysis** can help investigate them.

Frequently occurring ciphertext characters can be compared with common letter frequencies in natural language.

```text
Ciphertext
     │
     ▼
Frequency Analysis
     │
     ▼
Identify Common Characters
     │
     ▼
Compare Language Patterns
     │
     ▼
Investigate Substitutions
```

---

# 🔀 Transposition Cipher

A **Transposition Cipher** changes the positions of plaintext characters instead of replacing the characters themselves.

The original letters remain present, but their order is rearranged.

Conceptually:

```text
Original Characters
A B C D E F

        ↓

Rearranged Characters
C E A F B D
```

Because the characters themselves are preserved, transposition ciphers behave differently from substitution-based encryption.

## 🔎 Recommended Analysis

For suspected transposition ciphers, the engine recommends examining:

- Character arrangement
- Possible structural patterns
- Ordering relationships
- Potential grouping patterns

---

# 🛡️ Attack Recommendation Engine

After estimating the cipher family, the system recommends a cryptanalysis strategy appropriate for that type of classical cipher.

```text
          Detected Cipher
                 │
       ┌─────────┼──────────┐
       │         │          │
       ▼         ▼          ▼
    Caesar   Substitution  Transposition
       │         │          │
       ▼         ▼          ▼
   Shift     Frequency    Structural
  Analysis    Analysis     Analysis
```

This makes the system more than a classifier.

It produces:

```text
Cipher Classification
        +
Explanation
        +
Recommended Analysis Strategy
```

---

# 💡 Classification Explanation

The program does not only display a cipher-family prediction.

It also provides an explanation describing why the ciphertext was classified in that category.

This makes the application easier to understand and demonstrates the reasoning process behind the result.

The output includes:

```text
1. Ciphertext Analysis

2. Letter Frequencies

3. Estimated Cipher Family

4. Explanation

5. Recommended Cryptanalysis Strategy
```

---

# 💻 Command-Line Application

The Cipher Forensics Engine runs as a Python command-line application.

A typical interaction follows:

```text
=== Cipher Forensics and Attack Recommendation Engine ===

Enter ciphertext:
[User enters encrypted text]

=== Pattern Analysis ===

Letter Frequency:
[Frequency information]

Number of Unique Letters:
[Result]

=== Estimated Encryption Family ===

Detected Cipher:
[Cipher family]

=== Explanation ===

[Reason for the classification]

=== Recommended Attack Strategy ===

[Recommended analysis method]
```

This keeps the program simple and allows the focus to remain on the cybersecurity and cryptanalysis logic.

---

# 🏗️ Program Architecture

The system can be represented as several connected components:

```text
                 User Input
                     │
                     ▼
              Ciphertext Engine
                     │
          ┌──────────┼───────────┐
          │          │           │
          ▼          ▼           ▼
      Frequency   Pattern      Unique
       Analysis   Analysis     Letters
          │          │           │
          └──────────┼───────────┘
                     ▼
              Cipher Detector
                     │
                     ▼
               Explanation
                     │
                     ▼
          Attack Recommendation
                     │
                     ▼
                  Output
```

---

# 🛠️ Technologies Used

## 🐍 Programming

- Python 3

## 🔐 Computer Security

- Classical Cryptography
- Cryptanalysis
- Cipher Identification
- Security Analysis

## 🔎 Analysis Techniques

- Frequency Analysis
- Pattern Analysis
- Character Counting
- Unique-Letter Analysis
- Rule-Based Classification
- Structural Analysis

## 🔄 Cipher Families

- Caesar Cipher
- Monoalphabetic Substitution Cipher
- Transposition Cipher

## 🔧 Development Tools

- Visual Studio Code
- Python
- Terminal

---

# 📂 Project Structure

```text
cipher-forensics/
│
├── README.md
│
├── src/
│   └── Cipher_Engine.py
│
└── documentation/
    ├── Report.pdf
    └── Presentation.pdf
```

---

# 📁 Explore the Project

## 💻 Source Code

The Python source code contains the complete Cipher Forensics & Attack Recommendation Engine, including:

- Ciphertext processing
- Frequency analysis
- Pattern analysis
- Cipher-family detection
- Classification explanations
- Attack recommendations
- Command-line interaction

[**View Source Code →**](./src/Cipher_Engine.py)

---

## 📄 Project Report

The academic report provides further information about the project, including the problem, objectives, security concepts, implementation, testing, results, and conclusions.

[**View Report →**](./documentation/Report.pdf)

---

## 🎤 Project Presentation

The project presentation provides a visual overview of the Cipher Forensics & Attack Recommendation Engine and the cybersecurity concepts used in the project.

[**View Presentation →**](./documentation/Presentation.pdf)

---

# 💡 What I Learned

This project gave me practical experience applying **Computer Security concepts through Python programming**.

Through the project, I gained hands-on experience with:

- Classical cryptography
- Cryptanalysis concepts
- Ciphertext processing
- Frequency analysis
- Pattern recognition
- Character-distribution analysis
- Cipher-family identification
- Rule-based classification
- Security reasoning
- Attack-strategy recommendation
- Python application development
- Command-line program design
- Testing and debugging
- Technical documentation
- Result interpretation

One of the most valuable parts of the project was learning that cryptanalysis can begin by **studying the statistical and structural characteristics of ciphertext before attempting to understand the underlying plaintext**.

---

# ⚠️ Limitations

The Cipher Forensics & Attack Recommendation Engine was designed as an **educational implementation of classical cryptanalysis concepts**.

The cipher detection mechanism uses simplified rules and does not represent a complete professional cipher-identification system.

Current limitations include:

- Support for only three classical cipher families
- Rule-based classification
- Simplified frequency analysis
- Limited structural analysis
- No automatic plaintext recovery
- No confidence score for classification
- Detection performance may vary depending on ciphertext length and characteristics

These limitations also provide opportunities for future development.

---

# 🔮 Future Improvements

The project could be expanded by:

- Supporting additional classical cipher families
- Adding Vigenère Cipher analysis
- Implementing more advanced frequency analysis
- Analyzing letter pairs and repeated patterns
- Performing digram and trigram analysis
- Adding automated Caesar-shift testing
- Improving substitution-cipher analysis
- Adding more advanced transposition detection
- Calculating statistical similarity scores
- Adding confidence scores to classifications
- Testing with larger ciphertext samples
- Introducing machine learning for cipher classification
- Creating a graphical user interface
- Building a web-based version of the engine
- Expanding the testing dataset

---

# 🎓 Academic Context

**Computer Security — CMPS455**  
**Beirut Arab University**

This project was completed as an **individual Computer Security course project**.

## 👩‍💻 My Role

I was responsible for the complete project, including:

- Project design
- Python implementation
- Ciphertext analysis
- Frequency analysis
- Pattern-analysis logic
- Cipher-family detection
- Classification explanations
- Attack recommendation logic
- Testing and debugging
- Result analysis
- Project documentation
- Project presentation

---

# ⚠️ Educational Use

This project was developed for **academic and educational purposes**.

It demonstrates classical cryptography, cryptanalysis, pattern analysis, and security reasoning concepts using historical cipher techniques.

The engine is intended as a learning implementation rather than a production security or cryptanalysis system.

---

## 🔗 Project Navigation

[**💻 Source Code →**](./src/Cipher_Engine.py) · [**📄 Report →**](./documentation/Report.pdf) · [**🎤 Presentation →**](./documentation/Presentation.pdf)
