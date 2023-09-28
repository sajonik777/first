import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_squared_error
from data_preprocessing import load_data, preprocess_data
from feature_selection import select_features

def split_data(features, target):
    # Split data into training and testing sets
    X_train, X_test, y_train, y_test = train_test_split(features, target, test_size=0.2, random_state=42)
    return X_train, X_test, y_train, y_test

def train_model(X_train, y_train):
    # Train model
    model = RandomForestRegressor(random_state=42)
    model.fit(X_train, y_train)
    return model

def evaluate_model(model, X_test, y_test):
    # Evaluate model
    predictions = model.predict(X_test)
    mse = mean_squared_error(y_test, predictions)
    return mse

def main():
    # Load data
    data = load_data('/path/to/data.csv')

    # Preprocess data
    data = preprocess_data(data)

    # Select features
    features = select_features(data)

    # Split data
    X_train, X_test, y_train, y_test = split_data(features, data['target'])

    # Train model
    model = train_model(X_train, y_train)

    # Evaluate model
    mse = evaluate_model(model, X_test, y_test)
    print(f'Mean Squared Error: {mse}')

if __name__ == '__main__':
    main()