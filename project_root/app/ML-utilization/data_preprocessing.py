import pandas as pd
import chardet
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error

def load_data(file_path):
    with open(file_path, 'rb') as file:
        result = chardet.detect(file.read())
    encoding = result['encoding']
    data = pd.read_csv(file_path, encoding=encoding)
    return data

def preprocess_data(data):
    # Perform data preprocessing steps
    ...

    return data

def select_features(data):
    features = data[['Raw capacity']]
    target = data['Usable capacity']
    return features, target

def split_data(features, target):
    X_train, X_test, y_train, y_test = train_test_split(features, target, test_size=0.2, random_state=42)
    return X_train, X_test, y_train, y_test

def train_model(X_train, y_train):
    model = LinearRegression()
    model.fit(X_train, y_train)
    return model

def evaluate_model(model, X_test, y_test):
    predictions = model.predict(X_test)
    mse = mean_squared_error(y_test, predictions)
    return mse

def preprocess_data(data):
    # Perform data preprocessing steps
    ...

    return data

def select_features(data):
    features = data[['Raw capacity']]
    target = data['Usable capacity']
    return features, target

def split_data(features, target):
    X_train, X_test, y_train, y_test = train_test_split(features, target, test_size=0.2, random_state=42)
    return X_train, X_test, y_train, y_test

def train_model(X_train, y_train):
    model = LinearRegression()
    model.fit(X_train, y_train)
    return model

def evaluate_model(model, X_test, y_test):
    predictions = model.predict(X_test)
    mse = mean_squared_error(y_test, predictions)
    return mse

...rest of the code...