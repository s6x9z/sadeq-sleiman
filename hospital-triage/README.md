# 🚑 Hospital Emergency Triage System

## Two Implementations: Algorithms in Java & Programming Language Concepts in Python

The **Hospital Emergency Triage System** is an academic project developed in two separate implementations at **Beirut Arab University**.

Both implementations explore the same core problem — organizing and prioritizing patients in an emergency department — but approach it from different Computer Science perspectives.

### ☕ Java Implementation — Algorithms

Developed for **CMPS441**, this version focuses on algorithm design and performance using:

- QuickSort
- QuickSelect
- Randomized pivot selection
- Deterministic Median-of-Three pivot selection
- Runtime comparison
- Severity-based patient prioritization

### 🐍 Python Implementation — CPL

Developed for **CMPS445**, this version focuses on Python programming concepts and patient-management functionality using:

- Functions
- Lists
- Dictionaries
- Loops
- Conditionals
- Sorting
- Searching
- Input validation
- Patient editing and discharge
- Statistics

Together, the two implementations demonstrate how the same real-world problem can be modeled using different programming languages, programming paradigms, and algorithmic techniques.

---

# 📌 Project Overview

Emergency departments may receive many patients with different levels of urgency.

A triage system helps organize patients so that more urgent cases can be identified before lower-priority cases.

The project explores this idea through two independent implementations:

```text
                   Hospital Emergency
                     Triage System
                           │
             ┌─────────────┴─────────────┐
             │                           │
             ▼                           ▼
        Java Version                Python Version
        CMPS441                     CMPS445
             │                           │
             ▼                           ▼
        Algorithms               Programming Concepts
             │                           │
       QuickSort                     Lists
       QuickSelect                    Dictionaries
       Pivot Strategies              Functions
       Performance                    Sorting
       Severity Scores                Searching
                                      Patient Management
```

---

# ✨ Project Highlights

## ☕ Java Version

- Severity-based patient prioritization
- Manual patient entry
- Random patient generation
- QuickSort implementation
- QuickSelect implementation
- Randomized pivot selection
- Deterministic Median-of-Three pivot selection
- Algorithm performance comparison
- Patient search
- Triage statistics
- Triage-level classification
- Runtime measurement

## 🐍 Python Version

- Add patients
- Automatically assign triage levels
- View waiting list
- Sort patients by urgency and age
- Filter patients by triage level
- Search by name or symptom
- Edit patient information
- Discharge patients
- View patient statistics
- Input validation
- Triage-help system
- Menu-driven terminal interface

---

# 🎯 Project Objectives

The overall objectives of the two implementations were to:

- Model an emergency triage workflow
- Prioritize patients according to urgency
- Apply programming concepts to a practical problem
- Compare different implementation approaches
- Practice algorithm design and analysis
- Apply searching and sorting techniques
- Build menu-driven terminal applications
- Handle user input and validation
- Organize and process patient information
- Apply Java and Python to the same problem domain

---

# ☕ Java Implementation

## Algorithm-Based Hospital Emergency Triage System

[**View Java Implementation →**](./java/)

The Java implementation focuses on **algorithm design and performance analysis**.

Each patient contains:

```text
Name
Severity Score
```

The severity score ranges from:

```text
0 → 100
```

and determines the patient's triage level.

---

## 🩺 Java Triage Levels

The Java implementation converts patient severity scores into four urgency levels.

```text
Severity ≥ 75
      ↓
Level 1 — Critical

Severity 50–74
      ↓
Level 2 — High Priority

Severity 25–49
      ↓
Level 3 — Normal Priority

Severity < 25
      ↓
Level 4 — Minor
```

The application describes the levels as:

```text
Level 1 → Critical – immediate treatment

Level 2 → High priority – see soon

Level 3 → Normal – can wait

Level 4 → Minor/Unknown – low urgency
```

---

# 👤 Java Patient Model

The Java version uses a `Patient` class.

Each patient stores:

```java
private String name;
private double severity;
```

The class provides methods for retrieving patient information.

```java
public String getName() {
    return name;
}

public double getSeverity() {
    return severity;
}
```

This keeps patient data organized using Object-Oriented Programming.

---

# 🔀 QuickSort

The Java version implements **QuickSort** to sort all patients according to severity.

Patients are arranged from:

```text
Most Critical
      ↓
Least Critical
```

Conceptually:

```text
Patient Array
      │
      ▼
Select Pivot
      │
      ▼
Partition by Severity
      │
      ├──────────────┐
      ▼              ▼
More Severe      Less Severe
      │              │
      ▼              ▼
QuickSort        QuickSort
      │              │
      └──────┬───────┘
             ▼
       Sorted Patients
```

The partition method compares patient severity values:

```java
if (arr[j].getSeverity() > pivot) {
    i++;
    swap(arr, i, j);
}
```

Using `>` causes higher-severity patients to appear first.

---

# 🎯 QuickSelect

The Java implementation also uses **QuickSelect**.

QuickSelect allows the program to find the:

```text
k-th most critical patient
```

without fully sorting the entire patient list.

For example:

```text
k = 1
→ Most critical patient

k = 2
→ Second most critical patient

k = 5
→ Fifth most critical patient
```

The program allows the user to enter `k` and select the pivot strategy.

```text
Find k-th Patient
       │
       ▼
Select Pivot
       │
       ▼
Partition Array
       │
       ▼
Where is k?
   ┌───┼───┐
   │   │   │
 Left Pivot Right
   │   │   │
   ▼   ▼   ▼
Search Return Search
```

QuickSelect avoids sorting the entire array when only one ranked patient is required.

---

# 🎲 Randomized Pivot

One available pivot strategy selects a random element.

```java
low + rand.nextInt(high - low + 1)
```

Conceptually:

```text
Patient Array

P1 P2 P3 P4 P5 P6
         ↑
    Random Pivot
```

Randomization can reduce the likelihood of consistently poor pivot choices for certain input arrangements.

---

# 📐 Deterministic Median-of-Three Pivot

The second strategy uses **Median-of-Three** selection.

The algorithm examines:

```text
First Element
Middle Element
Last Element
```

and selects the median value as the pivot.

```text
First ─┐
       │
Middle ├──► Median Value ──► Pivot
       │
Last ──┘
```

The implementation compares severity values from the three positions.

---

# ⚔️ Pivot Strategy Comparison

The Java project compares:

```text
QuickSelect — Randomized Pivot

QuickSelect — Deterministic Pivot

QuickSort — Randomized Pivot

QuickSort — Deterministic Pivot
```

Each algorithm is executed several times and measured using:

```java
System.nanoTime()
```

The program then displays average execution times in milliseconds.

Example structure:

```text
=== Average Times (ms) ===

QuickSelect (random)
QuickSelect (deterministic)

QuickSort (random)
QuickSort (deterministic)
```

This allows the project to explore not just correctness, but also **algorithm performance**.

---

# 🎲 Java Input Methods

The Java application supports two ways of generating patients.

## Manual Entry

The user specifies:

```text
Patient Name
Severity Score
```

---

## Random Generation

The program can automatically generate:

```text
Patient_1
Patient_2
Patient_3
...
```

with random severity values between approximately:

```text
0 and 100
```

This makes it easier to test algorithms using larger patient sets.

---

# 🔎 Java Patient Search

The application allows patients to be searched by name.

The search is:

- Case-insensitive
- Keyword-based

Conceptually:

```text
Search Keyword
      │
      ▼
Loop Through Patients
      │
      ▼
Name Contains Keyword?
   │             │
  Yes            No
   │             │
   ▼             ▼
Display        Continue
Patient        Search
```

---

# 📊 Java Statistics

The Java version can display:

- Total number of patients
- Number of Level 1 patients
- Number of Level 2 patients
- Number of Level 3 patients
- Number of Level 4 patients
- Average severity

Example:

```text
--- Statistics ---

Total patients: 10
Critical (1): ...
High (2): ...
Normal (3): ...
Minor (4): ...

Average severity: ...
```

---

# 🖥️ Java Menu

The main menu provides:

```text
1. Show patients

2. Find k-th most critical patient
   using QuickSelect

3. Sort patients by severity
   using QuickSort

4. Compare randomized vs
   deterministic performance

5. Search patient by name

6. Show statistics

7. Triage help

8. Exit
```

---

# 🏗️ Java Architecture

The Java version is divided into three main classes.

```text
TriageSystem.java
       │
       ├─────────────┐
       ▼             ▼
Patient.java    QuickAlgorithms.java
```

### `TriageSystem.java`

Responsible for:

- User interaction
- Patient input
- Random patient generation
- Triage levels
- Menu system
- Search
- Statistics
- Algorithm execution
- Runtime comparison

### `Patient.java`

Responsible for:

- Patient name
- Severity score
- Patient object representation

### `QuickAlgorithms.java`

Responsible for:

- QuickSort
- QuickSelect
- Partitioning
- Randomized pivot selection
- Median-of-Three selection
- Array swapping

---

# 🐍 Python Implementation

## Programming-Language-Based Hospital Triage System

[**View Python Implementation →**](./python/)

The Python implementation approaches the same hospital triage concept from a different perspective.

Instead of focusing on QuickSort and QuickSelect, this version focuses on **patient management and Python programming constructs**.

Each patient stores:

```text
ID
Name
Age
Gender
Symptom
Triage Level
```

Patients are represented using Python dictionaries and stored inside a list.

---

# 📚 Python Data Structure

A patient is represented as:

```python
patient = {
    "id": next_id,
    "name": name,
    "age": age,
    "gender": gender,
    "symptom": symptom,
    "level": level
}
```

Patients are then stored inside:

```python
patients = []
```

This demonstrates the use of:

- Lists
- Dictionaries
- Dynamic data
- Python collection operations

---

# 🩺 Symptom-Based Triage

The Python version assigns urgency based on symptom keywords.

The program defines three main symptom groups.

## Level 1 — Critical

Examples include:

```text
chest pain
unconscious
severe bleeding
stroke
heart attack
severe burns
not breathing
```

---

## Level 2 — High Priority

Examples include:

```text
high fever
difficulty breathing
fracture
vomiting
severe headache
deep cut
asthma
```

---

## Level 3 — Normal Priority

Examples include:

```text
cough
headache
stomach pain
sore throat
back pain
minor cut
cold
flu
```

---

## Level 4 — Unknown / Minor

If no predefined symptom keyword matches, the patient receives:

```text
Level 4
```

with the recommendation that the condition requires further assessment.

> The symptom rules are simplified for the academic programming exercise and are not real clinical triage rules.

---

# 🔄 Python Triage Workflow

```text
Patient Arrives
      │
      ▼
Enter Patient Information
      │
      ▼
Enter Main Symptom
      │
      ▼
get_triage_level()
      │
      ▼
Check Symptom Keywords
      │
  ┌───┼────┬───────┐
  │   │    │       │
  ▼   ▼    ▼       ▼
 L1   L2   L3      L4
      │
      ▼
Create Patient Dictionary
      │
      ▼
Add Patient to Waiting List
```

---

# ➕ Add Patient

The Python application allows a new patient to be registered.

The user enters:

```text
Name
Age
Gender
Main Symptom
```

The system then:

1. Determines the triage level
2. Assigns a patient ID
3. Creates a patient dictionary
4. Adds the patient to the waiting list

---

# ✅ Input Validation

The Python version includes validation for patient input.

For example, the age field requires a numerical value within the accepted range.

```python
if age.isdigit():
    age = int(age)
```

The application also prevents required text fields from being empty.

This makes the interface more resilient to invalid user input.

---

# 📋 Waiting List

The waiting list displays all current patients sorted according to:

```text
1. Triage Level
2. Age
```

Lower triage numbers have greater urgency:

```text
Level 1
   ↓
Level 2
   ↓
Level 3
   ↓
Level 4
```

Within the same level, older patients are displayed first.

The Python implementation uses:

```python
sorted(
    patients,
    key=lambda x: (x["level"], -x["age"])
)
```

---

# 🔍 Filter by Triage Level

The system allows the user to display only patients belonging to a selected triage level.

For example:

```text
View Level 1
      ↓
Critical Patients Only
```

The filtering uses a Python list comprehension.

---

# 🔎 Search Patients

Users can search according to:

```text
Patient Name
or
Symptom
```

The search is case-insensitive and supports partial matching.

For example:

```text
Search:
headache
```

may find patients whose symptom contains the word `headache`.

---

# ✏️ Edit Patient Information

Existing patient information can be modified.

Editable fields include:

- Name
- Age
- Gender
- Symptom

If the symptom changes, the system recalculates the patient's triage level.

```text
Edit Symptom
     │
     ▼
New Symptom
     │
     ▼
Recalculate Triage Level
```

---

# 🚪 Discharge Patient

Patients can be removed from the waiting list after treatment.

The system searches for the patient's ID and removes the corresponding dictionary from the list.

```text
Enter Patient ID
      │
      ▼
Find Patient
      │
   ┌──┴──┐
   │     │
 Found  Missing
   │     │
   ▼     ▼
Remove  Error
Patient Message
```

---

# 📊 Python Statistics

The application calculates statistics about the current waiting list.

It displays:

- Total patients waiting
- Number of Level 1 patients
- Number of Level 2 patients
- Number of Level 3 patients
- Number of Level 4 patients
- Average patient age

---

# 🆘 Triage Help

The application contains a help section explaining the four triage categories and giving example symptoms.

This makes the terminal program easier to understand during use.

---

# 🖥️ Python Menu

The Python application provides:

```text
1. Add Patient

2. View Waiting List

3. View Patients by Triage Level

4. Search Patients

5. Edit Patient Info

6. Discharge Patient

7. Show Statistics

8. Triage Help

9. Exit
```

The program continues running until the user selects:

```text
9. Exit
```

---

# 🔄 Java vs Python

Although both versions use the same Hospital Emergency Triage concept, they were created for different academic purposes.

| Area | ☕ Java Version | 🐍 Python Version |
|---|---|---|
| Main Focus | Algorithms | Programming Language Concepts |
| Course | CMPS441 | CMPS445 |
| Patient Priority | Numeric severity | Symptom keywords |
| Main Structure | Patient objects + arrays | Lists + dictionaries |
| Sorting | Custom QuickSort | Python `sorted()` |
| Selection | QuickSelect | Not required |
| Pivot Strategies | Random + Median-of-Three | Not applicable |
| Performance Testing | Yes | No |
| Random Data Generation | Yes | No |
| Patient Addition | Initial input | Dynamic |
| Patient Editing | No | Yes |
| Patient Discharge | No | Yes |
| Search | Name | Name or symptom |
| Statistics | Severity statistics | Level counts + average age |
| Main Goal | Algorithm analysis | Python programming practice |

---

# 🧠 Different Approaches to the Same Problem

The two versions demonstrate how different requirements lead to different software designs.

```text
                    Triage Problem
                         │
             ┌───────────┴───────────┐
             │                       │
             ▼                       ▼
       Algorithm Question      Management Question

"Who is the k-th most      "How can we manage,
critical patient?"          search, edit, and
                            prioritize patients?"
             │                       │
             ▼                       ▼
          Java                    Python
             │                       │
             ▼                       ▼
      QuickSelect              Lists
      QuickSort                Dictionaries
      Pivot Analysis           Functions
      Runtime Testing          Sorting
```

---

# 🛠️ Technologies Used

## ☕ Java Implementation

- Java
- Object-Oriented Programming
- Arrays
- Recursion
- QuickSort
- QuickSelect
- Randomized Algorithms
- Median-of-Three
- Runtime Analysis
- Eclipse IDE

## 🐍 Python Implementation

- Python
- Functions
- Lists
- Dictionaries
- Conditionals
- Loops
- Lambda expressions
- Sorting
- Searching
- Input validation
- Terminal applications

---

# 📂 Project Structure

```text
hospital-triage/
│
├── README.md
│
├── java/
│   ├── README.md
│   ├── src/
│   │   ├── TriageSystem.java
│   │   ├── QuickAlgorithms.java
│   │   └── Patient.java
│   │
│   └── documentation/
│       └── Report.pdf
│
└── python/
    ├── README.md
    ├── src/
    │   └── triage_system.py
    │
    └── documentation/
        ├── Report.pdf
        └── Presentation.pdf
```

---

# 📁 Explore the Project

## ☕ Java Algorithms Implementation

Explore the algorithm-focused version containing QuickSort, QuickSelect, severity-based triage, pivot strategies, and performance testing.

[**View Java Version →**](./java/)

---

## 🐍 Python CPL Implementation

Explore the Python version containing symptom-based triage, patient management, searching, editing, filtering, sorting, and statistics.

[**View Python Version →**](./python/)

---

# 💡 What I Learned

Working with two implementations of the same problem provided an opportunity to compare different programming approaches.

The Java project provided experience with:

- Algorithm design
- QuickSort
- QuickSelect
- Recursion
- Partitioning
- Randomized algorithms
- Deterministic pivot selection
- Runtime measurement
- Algorithm-performance comparison
- Object-Oriented Programming

The Python project provided experience with:

- Python syntax
- Dynamic typing
- Lists
- Dictionaries
- Functions
- Conditionals
- Loops
- Sorting
- Searching
- Filtering
- Input validation
- Data management
- Menu-driven applications

Together, the projects demonstrate how the same problem can be approached differently depending on the **course objective, programming language, and system requirements**.

---

# ⚠️ Academic / Medical Disclaimer

These projects were developed for **Computer Science education and algorithm/programming practice**.

The triage levels, severity thresholds, and symptom classifications used in these applications are simplified programming rules.

They are **not clinical triage systems** and should not be used to make medical decisions, diagnose patients, determine treatment priority, or replace evaluation by qualified healthcare professionals.

---

# 🎓 Academic Context

## ☕ Java Version

**Fundamentals of Algorithms — CMPS441**  
**Beirut Arab University**

This implementation focuses on algorithm design through QuickSort, QuickSelect, pivot strategies, and performance comparison.

---

## 🐍 Python Version

**CMPS445 — CPL**  
**Beirut Arab University**

This implementation focuses on programming-language concepts and developing a functional patient-management system in Python.

---

## 🔗 Project Navigation

[**☕ Java Version →**](./java/) · [**🐍 Python Version →**](./python/)
