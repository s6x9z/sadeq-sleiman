# ❤️ Heart Disease Prediction

## Machine Learning Classification Using Random Forest and Decision Tree

This project was developed for the **Data Mining (CMPS452)** course at **Beirut Arab University**.

I developed this project to explore the use of **machine learning for heart disease prediction** using patient medical attributes.

The project compares two classification algorithms:

- 🌲 Random Forest
- 🌳 Decision Tree

Both models were trained and evaluated using the same dataset and performance metrics.

In the saved experiment, the **Random Forest classifier achieved the strongest overall performance**, reaching an accuracy of **97.39%**.

---

# 📌 Project Overview

Heart disease is an important healthcare challenge, making early detection and risk assessment valuable applications for data analysis and machine learning.

The purpose of this project was to build and compare machine learning models that classify whether a patient is associated with heart disease based on several medical attributes.

The complete project workflow included:

- Dataset exploration
- Data preparation
- Feature and target separation
- Train-test splitting
- Random Forest implementation
- Decision Tree implementation
- Model training
- Prediction
- Performance evaluation
- Model comparison
- Result interpretation

---

# 🎯 Project Objectives

The main objectives of the project were to:

- Explore a heart disease dataset
- Prepare the dataset for machine learning
- Identify the input features and target variable
- Split the dataset into training and testing sets
- Build a Random Forest classifier
- Build a Decision Tree classifier
- Train both models using the same dataset
- Generate predictions using test data
- Evaluate both models using multiple metrics
- Compare the performance of the two classifiers
- Determine which model produced the strongest results

---

# 👩‍💻 My Contribution

I was responsible for the **complete technical implementation and analysis of this project**.

My work included:

- Selecting and preparing the heart disease dataset
- Exploring the dataset using Python and pandas
- Identifying the input features and target variable
- Separating features from the target
- Preparing the training and testing datasets
- Implementing the Random Forest classifier
- Implementing the Decision Tree classifier
- Training both machine learning models
- Generating predictions using test data
- Calculating model evaluation metrics
- Comparing the performance of both algorithms
- Developing the complete Jupyter Notebook
- Analyzing and interpreting the results
- Preparing the project report
- Preparing the project presentation

This project allowed me to work through the full machine learning workflow from dataset preparation to model evaluation and comparison.

---

# 📊 Dataset

The dataset used in this project contains:

```text
573 patient records
13 input features
1 target variable
14 columns in total
```

The target variable represents the heart disease classification outcome.

```text
0 → No heart disease
1 → Heart disease
```

---

## 🧾 Dataset Features

| Feature | Description |
|---|---|
| `age` | Patient age |
| `sex` | Patient sex |
| `cp` | Chest pain type |
| `trestbps` | Resting blood pressure |
| `chol` | Cholesterol level |
| `fbs` | Fasting blood sugar |
| `restecg` | Resting electrocardiographic result |
| `thalach` | Maximum heart rate achieved |
| `exang` | Exercise-induced angina |
| `oldpeak` | ST depression |
| `slope` | Slope of the peak exercise ST segment |
| `ca` | Number of major vessels |
| `thal` | Thal-related diagnostic value |
| `target` | Heart disease classification |

---

# 🧠 Machine Learning Approach

This project uses **supervised machine learning**.

Because the target variable contains two possible classes, the problem is treated as a **binary classification task**.

The overall workflow was:

```text
Heart Disease Dataset
        │
        ▼
    Load Dataset
        │
        ▼
Explore and Prepare Data
        │
        ▼
Separate Features & Target
        │
        ▼
   Train/Test Split
        │
        ├───────────────┐
        │               │
        ▼               ▼
 Random Forest     Decision Tree
        │               │
        ▼               ▼
     Training         Training
        │               │
        ▼               ▼
   Predictions       Predictions
        │               │
        └───────┬───────┘
                ▼
        Model Evaluation
                │
                ▼
       Performance Comparison
```

---

# 🔀 Train-Test Split

The dataset was divided into training and testing data.

```text
80% Training Data
20% Testing Data
```

The split was implemented using scikit-learn:

```python
X_train, X_test, y_train, y_test = train_test_split(
    X,
    y,
    test_size=0.2,
    random_state=42
)
```

The training set is used to train the machine learning models.

The testing set is kept separate and used to evaluate how well the models perform on data they did not train on.

---

# 🌲 Random Forest Classifier

Random Forest was used as one of the main classification algorithms.

A Random Forest is an ensemble machine learning method that combines the predictions of multiple Decision Trees.

Using multiple trees can improve generalization and reduce some of the overfitting problems that may occur with a single Decision Tree.

The model was trained using:

```python
rf = RandomForestClassifier()

rf.fit(X_train, y_train)

rf_pred = rf.predict(X_test)
```

The predictions were then evaluated using the selected classification metrics.

---

# 🌳 Decision Tree Classifier

A Decision Tree classifier was also implemented.

Decision Trees classify observations by repeatedly splitting the data according to feature values.

The model was trained using:

```python
dt = DecisionTreeClassifier()

dt.fit(X_train, y_train)

dt_pred = dt.predict(X_test)
```

Using both models allowed their performance to be compared under the same conditions.

---

# 📏 Evaluation Metrics

The models were evaluated using four main classification metrics.

## ✅ Accuracy

Accuracy measures the percentage of all predictions that were classified correctly.

It provides an overall measure of model performance.

---

## 🎯 Precision

Precision measures how many of the cases predicted as positive were actually positive.

Higher precision means fewer false positive predictions.

---

## 🔎 Recall

Recall measures how many of the actual positive cases were correctly identified by the model.

Higher recall means the model successfully identified more of the positive cases.

---

## ⚖️ F1 Score

The F1 Score combines precision and recall into a single metric.

It is useful when both false positives and false negatives are important when evaluating the classifier.

---

# 📈 Model Results

The saved notebook experiment produced the following results:

| Model | Accuracy | Precision | Recall | F1 Score |
|---|---:|---:|---:|---:|
| **Random Forest** | **97.39%** | **98.11%** | **96.30%** | **97.20%** |
| Decision Tree | 95.65% | 98.04% | 92.59% | 95.24% |

---

# 🏆 Model Comparison

The **Random Forest classifier achieved the strongest overall performance** in the saved experiment.

Compared with the Decision Tree model, Random Forest achieved:

- Higher accuracy
- Slightly higher precision
- Higher recall
- Higher F1 Score

### Accuracy Comparison

```text
Random Forest
████████████████████ 97.39%

Decision Tree
███████████████████  95.65%
```

### F1 Score Comparison

```text
Random Forest
████████████████████ 97.20%

Decision Tree
███████████████████  95.24%
```

The results demonstrate that the ensemble-based Random Forest model performed better overall than the single Decision Tree model on this dataset.

---

# 🛠️ Technologies Used

## 🐍 Programming

- Python

## 🤖 Machine Learning

- scikit-learn
- Random Forest
- Decision Tree
- Supervised Learning
- Binary Classification

## 📊 Data Processing

- pandas

## 💻 Development Environment

- Jupyter Notebook
- Google Colab

---

# 💻 Implementation

The Jupyter Notebook contains the complete machine learning workflow used in the project.

The implementation includes:

1. Importing the required libraries
2. Loading the heart disease dataset
3. Exploring the dataset
4. Separating input features and target
5. Creating training and testing datasets
6. Implementing Random Forest
7. Implementing Decision Tree
8. Training both models
9. Generating predictions
10. Calculating evaluation metrics
11. Comparing the two models
12. Interpreting the results

[**View Machine Learning Notebook →**](./notebook/DataMining_Project.ipynb)

---

# 📂 Project Structure

```text
heart-disease-prediction/
│
├── README.md
│
├── notebook/
│   └── DataMining_Project.ipynb
│
├── data/
│   └── Dataset.csv
│
└── documentation/
    ├── Report.pdf
    └── Presentation.pdf
```

---

# 📁 Explore the Project

## 💻 Machine Learning Notebook

The notebook contains the complete Python implementation including dataset preparation, model training, prediction, evaluation, and comparison.

[**View Notebook →**](./notebook/DataMining_Project.ipynb)

---

## 📊 Dataset

The dataset contains the patient attributes used to train and evaluate the classification models.

[**View Dataset →**](./data/Dataset.csv)

---

## 📄 Project Report

The academic report contains the project background, methodology, implementation, results, and discussion.

[**View Report →**](./documentation/Report.pdf)

---

## 🎤 Project Presentation

The presentation summarizes the project objectives, machine learning approach, model implementation, and results.

[**View Presentation →**](./documentation/Presentation.pdf)

---

# 💡 What I Learned

This project gave me practical experience applying machine learning techniques to a real classification problem.

Through the project, I gained hands-on experience with:

- Supervised machine learning
- Binary classification
- Dataset exploration
- Data preparation
- Feature and target separation
- Train-test splitting
- Random Forest classification
- Decision Tree classification
- Model training
- Prediction
- Accuracy analysis
- Precision analysis
- Recall analysis
- F1 Score analysis
- Model evaluation
- Model comparison
- Python machine learning libraries
- Jupyter Notebook development
- Technical documentation
- Result interpretation

One of the most valuable parts of the project was learning how to compare machine learning models using multiple evaluation metrics rather than relying only on accuracy.

---

# 🔮 Future Improvements

The project could be extended further by:

- Testing additional classification algorithms
- Comparing Logistic Regression, Support Vector Machines, or K-Nearest Neighbors
- Performing more detailed exploratory data analysis
- Adding data visualizations
- Analyzing feature correlations
- Evaluating class balance
- Using cross-validation
- Performing hyperparameter tuning
- Analyzing Random Forest feature importance
- Testing the models using a larger dataset
- Building a simple user interface for entering patient data
- Creating an API for model predictions
- Saving and loading the trained machine learning model

---

# 🎓 Academic Context

**Data Mining — CMPS452**  
**Beirut Arab University**

This project was completed as part of the Data Mining course.

## 👩‍💻 My Role

I was responsible for the complete technical implementation and analysis of the project, including:

- Dataset preparation
- Machine learning implementation
- Random Forest development
- Decision Tree development
- Model training
- Prediction
- Performance evaluation
- Model comparison
- Jupyter Notebook development
- Result interpretation
- Project documentation
- Project presentation

---

# ⚠️ Disclaimer

This project was developed for **academic and educational purposes**.

The machine learning models demonstrated here are experiments performed on the provided dataset and are **not intended to provide medical diagnosis, medical advice, or replace evaluation by qualified healthcare professionals**.

---

## 🔗 Project Navigation

[**💻 Notebook →**](./notebook/DataMining_Project.ipynb) · [**📊 Dataset →**](./data/Dataset.csv) · [**📄 Report →**](./documentation/Report.pdf) · [**🎤 Presentation →**](./documentation/Presentation.pdf)
