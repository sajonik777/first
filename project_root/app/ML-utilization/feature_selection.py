import pandas as pd
from sklearn.feature_selection import SelectKBest
from sklearn.feature_selection import f_regression

def select_features(data):
    # Select features using SelectKBest and f_regression
    X = data.drop('target', axis=1)
    y = data['target']
    selector = SelectKBest(score_func=f_regression, k=5)
    X_new = selector.fit_transform(X, y)
    selected_features = X.columns[selector.get_support(indices=True)].tolist()
    return selected_features