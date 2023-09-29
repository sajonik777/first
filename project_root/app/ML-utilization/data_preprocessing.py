import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.linear_model import LinearRegression
from sklearn.metrics import mean_squared_error

def create_json_template(output_data):
    json_template = {
        "capacityGb": output_data.get("capacityGb", None),
        "contextId": output_data.get("contextId", None),
        "id": output_data.get("id", None),
        "lifecycleStatus": output_data.get("lifecycleStatus", None),
        "lifecycleStatusDate": output_data.get("lifecycleStatusDate", None),
        "ownerSystem": output_data.get("ownerSystem", None),
        "raidType": output_data.get("raidType", None),
        "redundancy": output_data.get("redundancy", None),
        "redundancyClass": output_data.get("redundancyClass", None),
        "remark": output_data.get("remark", None),
        "rootVolume": output_data.get("rootVolume", None),
        "snapshotSchedule": output_data.get("snapshotSchedule", None),
        "snapshotSizeGb": output_data.get("snapshotSizeGb", None),
        "snapshotUsedGb": output_data.get("snapshotUsedGb", None),
        "sourceId": output_data.get("sourceId", None),
        "sourceSystem": output_data.get("sourceSystem", None),
        "usedGb": output_data.get("usedGb", None),
        "visibleId": output_data.get("visibleId", None),
        "volumeType": output_data.get("volumeType", None)
    }
    return json_template

def load_data(file_path):
    data = pd.read_json(file_path)
    return data

def preprocess_data(data):
    # Perform data preprocessing steps
    ...

    return data

def select_features(data):
    features = data[['capacityGb']]
    target = data['usedGb']
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