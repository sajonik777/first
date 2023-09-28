from data_preprocessing import load_data, preprocess_data
from feature_selection import select_features
from model import split_data, train_model, evaluate_model

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