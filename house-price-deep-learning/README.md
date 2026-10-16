# 🏠 House Price Prediction with Deep Neural Networks

## Nonlinear House Price Modeling Using Deep Learning

This project was developed for the **Deep Learning (CMPS470)** course at **Beirut Arab University**.

I developed a machine learning and deep learning workflow for predicting residential house prices using the **Ames Housing dataset**.

The project explores how preprocessing, feature engineering, target transformation, and neural-network depth affect regression performance.

Several models were implemented and compared:

- 📈 Linear Regression baseline
- 🧠 1-Layer Neural Network
- 🧠 3-Layer Neural Network
- 🧠 5-Layer Neural Network

The project also investigates the internal representations learned by a neural network using **Principal Component Analysis (PCA)**.

---

# 📌 Project Overview

House-price prediction is a regression problem in which the goal is to estimate the selling price of a property based on characteristics such as its size, age, location, quality, garage information, and other structural features.

Housing datasets present several challenges because they contain:

- Numerical features
- Categorical features
- Missing values
- Skewed variables
- A large number of property characteristics
- Nonlinear relationships between features and price

The goal of this project was not only to train a prediction model, but also to study how different preprocessing techniques and neural-network architectures affect model performance.

---

# 🎯 Project Objectives

The main objectives were to:

- Explore and preprocess the Ames Housing dataset
- Handle missing numerical and categorical values
- Engineer additional property-related features
- Apply transformations to skewed variables
- Encode categorical features
- Normalize model inputs
- Compare raw and logarithmic house-price targets
- Establish a Linear Regression baseline
- Build neural networks with different depths
- Compare 1-layer, 3-layer, and 5-layer networks
- Evaluate models using regression metrics
- Analyze training and validation behavior
- Investigate learned neural-network representations using PCA

---

# 👩‍💻 My Contribution

I was responsible for the **complete implementation, experimentation, analysis, documentation, and presentation of this project**.

My work included:

- Preparing and exploring the housing dataset
- Handling missing data
- Feature engineering
- Encoding categorical variables
- Applying logarithmic transformations
- Standardizing model inputs
- Creating training and validation datasets
- Implementing the Linear Regression baseline
- Designing neural-network architectures using TensorFlow/Keras
- Training 1-layer, 3-layer, and 5-layer neural networks
- Comparing raw-price and log-price learning
- Evaluating models using MSE and MAPE
- Analyzing generalization behavior
- Extracting hidden-layer representations
- Applying PCA for representation analysis
- Interpreting experimental results
- Developing the complete Jupyter Notebook
- Preparing the project report
- Preparing the project presentation

---

# 📊 Dataset

The project uses the **Ames Housing dataset**.

The dataset loaded in the notebook contains:

```text
1,460 houses
81 original columns
```

The target variable is:

```text
SalePrice
```

The dataset contains a combination of numerical and categorical information describing properties.

Examples include:

- Property size
- Lot area
- Basement area
- Floor area
- Construction year
- Garage information
- Neighborhood
- Overall property characteristics
- Sale information

---

# 🧹 Data Preprocessing

Before training the models, several preprocessing steps were performed.

## Missing Numerical Values

Missing values in numerical columns were replaced using the **median** value of the corresponding feature.

```python
for col in df.select_dtypes(include=['int64', 'float64']):
    df[col].fillna(df[col].median(), inplace=True)
```

Using the median provides a simple way to handle missing numerical values while reducing the influence of extreme observations.

---

## Missing Categorical Values

Missing categorical values were replaced with:

```text
None
```

Example:

```python
for col in df.select_dtypes(include=['object']):
    df[col].fillna("None", inplace=True)
```

This preserves the information that a categorical feature was unavailable rather than dropping the entire record.

---

# 🧠 Feature Engineering

Additional features were created from existing property information to provide the models with more meaningful inputs.

## Total Area

```python
df['TotalArea'] = (
    df['TotalBsmtSF']
    + df['1stFlrSF']
    + df['2ndFlrSF']
)
```

This combines major living-area measurements into a single feature representing overall usable property area.

---

## House Age

```python
df['HouseAge'] = df['YrSold'] - df['YearBuilt']
```

Instead of treating construction year alone as the most meaningful feature, this estimates the age of the property at the time it was sold.

---

## Garage Age

```python
df['GarageAge'] = df['YrSold'] - df['GarageYrBlt']
```

This creates a feature describing the age of the garage at the time of sale.

---

# 📉 Log Transformation

House prices can contain high-value properties that create a skewed target distribution.

To address this, the project experimented with a logarithmic transformation of `SalePrice`.

```python
y_raw = df['SalePrice']

df['SalePrice'] = np.log1p(df['SalePrice'])

y_log = df['SalePrice']
```

The project also applied logarithmic transformations to selected skewed numerical features such as:

```text
GrLivArea
LotArea
```

Example:

```python
df['GrLivArea'] = np.log1p(df['GrLivArea'])
df['LotArea'] = np.log1p(df['LotArea'])
```

The purpose of these transformations was to reduce skewness and create distributions that may be easier for the models to learn.

---

# 🔤 Categorical Encoding

Machine-learning models require numerical inputs.

Categorical variables were therefore transformed using **one-hot encoding**.

```python
df_onehot = pd.get_dummies(df)
```

This creates numerical indicator columns representing the categories found in the dataset.

---

# 🔀 Training and Validation Split

The data was divided using an:

```text
80% Training
20% Validation
```

split.

```python
X_train, X_val, y_train_log, y_val_log = train_test_split(
    X,
    y_log,
    test_size=0.2,
    random_state=42
)
```

The same split configuration was used to maintain consistency across experiments.

---

# 📏 Feature Scaling

The input features were standardized using **StandardScaler**.

```python
scaler = StandardScaler()

X_train = scaler.fit_transform(X_train)
X_val = scaler.transform(X_val)
```

Standardization helps ensure that features with larger numerical ranges do not dominate features with smaller values during training.

---

# 🧪 Experiment 1 — Raw Price vs Log Price

One experiment investigated the difference between training a neural network using:

```text
Raw SalePrice
```

and:

```text
log(SalePrice)
```

A neural network containing hidden layers with **64 and 32 neurons** was trained for both targets.

```python
model = Sequential([
    Dense(64, activation='relu'),
    Dense(32, activation='relu'),
    Dense(1)
])
```

The model used:

```text
Optimizer: Adam
Loss: Mean Squared Error
Epochs: 20
```

Residual plots were then generated to inspect prediction errors.

The log-transformed target was used in the later model-comparison experiments.

---

# 🧠 Experiment 2 — Architecture Comparison

The project compared models with different levels of complexity.

```text
Linear Regression
       │
       ├── 1-Layer Neural Network
       │
       ├── 3-Layer Neural Network
       │
       └── 5-Layer Neural Network
```

The goal was to investigate whether increasing model depth improved the ability to learn nonlinear relationships in the housing data.

---

# 📈 Linear Regression Baseline

Linear Regression was implemented as a baseline model.

```python
lin = LinearRegression()

lin.fit(X_train, y_train_log)

pred_lin = lin.predict(X_val)
```

The baseline provides a reference point for determining whether the additional complexity of neural networks improves prediction performance.

---

# 🧠 1-Layer Neural Network

The first architecture used a single hidden layer.

```text
Input
  │
  ▼
64 Neurons — ReLU
  │
  ▼
Output
```

This provides a relatively simple nonlinear regression model.

---

# 🧠 3-Layer Neural Network

The medium-depth architecture contains three hidden layers.

```text
Input
  │
  ▼
64 Neurons — ReLU
  │
  ▼
32 Neurons — ReLU
  │
  ▼
32 Neurons — ReLU
  │
  ▼
Output
```

Among the neural-network architectures in the saved experiment, the **3-layer network produced the strongest validation metrics**.

---

# 🧠 5-Layer Neural Network

A deeper model was also tested.

```text
Input
  │
  ▼
64 Neurons — ReLU
  │
  ▼
32 Neurons — ReLU
  │
  ▼
32 Neurons — ReLU
  │
  ▼
32 Neurons — ReLU
  │
  ▼
32 Neurons — ReLU
  │
  ▼
Output
```

This experiment helped investigate whether adding additional network depth produced better generalization.

---

# ⚙️ Neural Network Configuration

The neural networks were built using **TensorFlow/Keras**.

The models used:

```text
Activation: ReLU
Optimizer: Adam
Loss Function: Mean Squared Error
Training Epochs: 20
```

The output layer contains one neuron because house-price prediction is a **regression problem**.

---

# 📏 Evaluation Metrics

Two regression metrics were used to evaluate the models.

## Mean Squared Error — MSE

MSE measures the average squared difference between the predicted value and the actual value.

Lower values indicate smaller prediction errors.

---

## Mean Absolute Percentage Error — MAPE

MAPE measures the prediction error relative to the actual values.

Lower values indicate better predictive performance.

---

# 📈 Saved Experiment Results

The saved notebook run produced the following validation results:

| Model | MSE | MAPE |
|---|---:|---:|
| **Linear Regression** | **0.0231** | **0.00753** |
| 1-Layer Neural Network | 1.9630 | 0.07746 |
| **3-Layer Neural Network** | **1.0523** | **0.06185** |
| 5-Layer Neural Network | 1.3541 | 0.06803 |

---

# 🏆 Model Comparison

In the saved notebook run, the **Linear Regression baseline produced the lowest overall MSE and MAPE**.

Among the neural-network models, the:

**3-Layer Neural Network**

produced the strongest performance.

Its results were better than both the 1-layer and 5-layer neural networks:

```text
                MSE        MAPE

1-Layer NN     1.9630      0.07746

3-Layer NN     1.0523      0.06185   ← Best Neural Network

5-Layer NN     1.3541      0.06803
```

This experiment demonstrates an important machine-learning principle:

> A more complex model does not automatically produce better predictions.

Increasing neural-network depth improved performance from the 1-layer to the 3-layer model, but adding further depth did not improve the saved validation results.

---

# 📉 Generalization Analysis

Training and validation loss were analyzed to investigate how the architectures generalized to unseen data.

The notebook tracks the loss history of the neural networks and visualizes their validation behavior.

```python
plt.plot(h1.history['loss'], label="train 1-layer")
plt.plot(h1.history['val_loss'], label="val 1-layer")

plt.plot(h3.history['val_loss'], label="val 3-layer")
plt.plot(h5.history['val_loss'], label="val 5-layer")
```

Comparing training and validation loss helps detect behavior such as:

- Underfitting
- Good generalization
- Overfitting

---

# 🔬 Representation Analysis

The project also investigates what the neural network learns internally.

A hidden-layer representation was extracted from the 3-layer neural network.

```python
hidden_model = Model(
    inputs=model3.inputs,
    outputs=model3.layers[1].output
)

features = hidden_model.predict(X_val)
```

Instead of examining only the final prediction, this makes it possible to study the features created internally by the neural network.

---

# 📊 PCA Visualization

The hidden-layer representation contains many dimensions.

**Principal Component Analysis (PCA)** was used to reduce the learned representation to two dimensions.

```python
pca = PCA(n_components=2)

reduced = pca.fit_transform(features)
```

The transformed data was visualized using a scatter plot.

```python
plt.scatter(
    reduced[:, 0],
    reduced[:, 1],
    alpha=0.5
)
```

This experiment provides insight into how the neural network organizes information internally while learning from the housing features.

---

# 🏗️ Complete Workflow

```text
Ames Housing Dataset
         │
         ▼
Data Exploration
         │
         ▼
Missing Value Handling
         │
         ▼
Feature Engineering
         │
         ▼
Log Transformation
         │
         ▼
One-Hot Encoding
         │
         ▼
Train / Validation Split
         │
         ▼
Standardization
         │
         ├─────────────────────┐
         │                     │
         ▼                     ▼
Linear Regression       Neural Networks
                              │
                 ┌────────────┼────────────┐
                 ▼            ▼            ▼
              1-Layer      3-Layer      5-Layer
                 │            │            │
                 └────────────┼────────────┘
                              ▼
                     Model Evaluation
                              │
                              ▼
                    Generalization Analysis
                              │
                              ▼
                     Hidden Representation
                              │
                              ▼
                             PCA
```

---

# 🛠️ Technologies Used

## 🐍 Programming

- Python

## 🧠 Deep Learning

- TensorFlow
- Keras
- Dense Neural Networks
- ReLU Activation
- Adam Optimizer

## 🤖 Machine Learning

- scikit-learn
- Linear Regression
- StandardScaler
- Train-Test Split
- PCA

## 📊 Data Processing

- pandas
- NumPy

## 📈 Visualization

- Matplotlib

## 💻 Development Environment

- Jupyter Notebook
- Google Colab

---

# 💻 Implementation

The project notebook contains the complete experimental workflow, including:

1. Loading the dataset
2. Exploring missing values
3. Handling missing data
4. Engineering additional features
5. Applying logarithmic transformations
6. Encoding categorical features
7. Dividing the data into training and validation sets
8. Standardizing model features
9. Comparing raw and log price targets
10. Training Linear Regression
11. Building neural networks
12. Comparing network depths
13. Calculating MSE and MAPE
14. Analyzing generalization
15. Extracting hidden-layer features
16. Applying PCA
17. Visualizing learned representations

[**View Deep Learning Notebook →**](./notebook/DeepLearning.ipynb)

---

# 📂 Project Structure

```text
house-price-deep-learning/
│
├── README.md
│
├── notebook/
│   └── DeepLearning.ipynb
│
└── documentation/
    ├── Report.pdf
    └── Presentation.pdf
```

---

# 📁 Explore the Project

## 🧠 Deep Learning Notebook

View the complete implementation containing preprocessing, feature engineering, model training, architecture comparison, evaluation, and representation analysis.

[**View Notebook →**](./notebook/DeepLearning.ipynb)

---

## 📄 Project Report

The project report explains the problem, preprocessing approach, feature engineering, deep-learning methodology, results, and analysis.

[**View Report →**](./documentation/Report.pdf)

---

## 🎤 Project Presentation

The project presentation provides a visual overview of the dataset, preprocessing, architectures, experiments, results, PCA analysis, and conclusions.

[**View Presentation →**](./documentation/Presentation.pdf)

---

# 💡 What I Learned

This project gave me practical experience with the complete deep-learning regression workflow.

Through the project, I gained hands-on experience with:

- Regression problems
- Dataset preprocessing
- Missing-value handling
- Feature engineering
- Categorical encoding
- Log transformations
- Feature standardization
- Linear Regression baselines
- TensorFlow and Keras
- Dense neural networks
- ReLU activation
- Adam optimization
- Loss functions
- Neural-network depth
- Model training
- Validation analysis
- MSE
- MAPE
- Generalization analysis
- Hidden-layer representations
- Principal Component Analysis
- Model comparison
- Jupyter Notebook development
- Experiment interpretation

One of the most valuable lessons from the project was that **increasing model complexity does not automatically improve predictive performance**.

The experiments showed the importance of comparing deep-learning models against simpler baselines and evaluating generalization rather than assuming that a deeper network will always perform better.

---

# 🔮 Future Improvements

This project could be extended by:

- Performing hyperparameter tuning
- Training models for additional epochs with early stopping
- Adding dropout regularization
- Adding L1 or L2 regularization
- Using batch normalization
- Testing different activation functions
- Comparing additional regression algorithms
- Performing cross-validation
- Evaluating additional regression metrics such as RMSE and MAE
- Performing more detailed feature-importance analysis
- Expanding representation analysis
- Saving the trained model for reuse
- Building a user interface for house-price predictions
- Creating an API for serving predictions

---

# 🎓 Academic Context

**Deep Learning — CMPS470**  
**Beirut Arab University**

This project was completed as an individual Deep Learning course project.

## 👩‍💻 My Role

I was responsible for the complete project, including:

- Data preprocessing
- Feature engineering
- Machine-learning baseline implementation
- Neural-network architecture development
- Model training
- Model evaluation
- Architecture comparison
- Generalization analysis
- PCA representation analysis
- Jupyter Notebook implementation
- Result interpretation
- Project report
- Project presentation

---

## 🔗 Project Navigation

[**🧠 Notebook →**](./notebook/DeepLearning.ipynb) · [**📄 Report →**](./documentation/Report.pdf) · [**🎤 Presentation →**](./documentation/Presentation.pdf)
