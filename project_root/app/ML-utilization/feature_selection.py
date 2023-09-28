from sklearn.feature_selection import SelectKBest, f_regression

def select_features(data):
    # Perform feature selection using SelectKBest and f_regression
    X = data.drop('target', axis=1)
    y = data['target']
    selector = SelectKBest(score_func=f_regression, k=5)
    selector.fit(X, y)
    selected_features = X.columns[selector.get_support()]
    return selected_features