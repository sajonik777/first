import pandas as pd
from sklearn.preprocessing import StandardScaler

def load_data(filepath):
    # Load data from the given filepath
    data = pd.read_csv(filepath)
    return data

def handle_missing_values(data):
    # Handle missing values
    # This is a placeholder, actual implementation will depend on the data
    data = data.fillna(0)
    return data

def handle_outliers(data):
    # Handle outliers
    # This is a placeholder, actual implementation will depend on the data
    return data

def preprocess_data(data):
    # Preprocess data
    data = handle_missing_values(data)
    data = handle_outliers(data)
    return data