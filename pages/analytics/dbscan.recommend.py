from sklearn.cluster import DBSCAN
from sklearn.preprocessing import StandardScaler
import pandas as pd
import json

import os

# Get the directory where the script is located
base_dir = os.path.dirname(os.path.abspath(__file__))
csv_path = os.path.join(base_dir, "reservations.csv")
json_path = os.path.join(base_dir, "recommendations.json")

# 1. Load Data
try:
    data = pd.read_csv(csv_path)
except FileNotFoundError:
    print(f"Error: {csv_path} not found. Please run export.reservations.php first.")
    exit(1)

# 2. Preprocessing
# Ensure guest_count and duration are numeric
data["guest_count"] = pd.to_numeric(data["guest_count"], errors='coerce')
data["duration_days"] = pd.to_numeric(data["duration_days"], errors='coerce')
data = data.dropna(subset=["guest_count", "duration_days", "room_type"])

# 3. Apply DBSCAN
# Features: guest_count and duration_days
X = data[["guest_count", "duration_days"]]

# Scale the features
scaler = StandardScaler()
X_scaled = scaler.fit_transform(X)

# WEIGHTING: Give guest_count more importance (3.0 weight)
# This forces clusters to separate strictly by room capacity needs
X_weighted = X_scaled.copy()
X_weighted[:, 0] = X_weighted[:, 0] * 3.0 

# Using refined parameters
# eps=0.7 allows for some duration variance while keeping guest counts distinct
# min_samples=3 ensures we pick up smaller valid booking patterns
db = DBSCAN(eps=0.7, min_samples=3).fit(X_weighted)
data["cluster"] = db.labels_

# 4. Generate Recommendations per Cluster
valid_clusters = data[data["cluster"] != -1]

recommendations = []

for cluster_id in sorted(valid_clusters["cluster"].unique()):
    cluster_data = valid_clusters[valid_clusters["cluster"] == cluster_id]
    
    # Calculate boundaries
    min_guests = int(cluster_data["guest_count"].min())
    max_guests = int(cluster_data["guest_count"].max())
    min_duration = int(cluster_data["duration_days"].min())
    max_duration = int(cluster_data["duration_days"].max())
    
    # Find most popular room_type (Mode)
    recommended_room = cluster_data["room_type"].mode()[0]
    
    recommendations.append({
        "cluster_id": int(cluster_id),
        "min_guests": min_guests,
        "max_guests": max_guests,
        "min_duration": min_duration,
        "max_duration": max_duration,
        "recommended_room": recommended_room
    })

# 5. Save to JSON for PHP to consume
with open(json_path, "w") as f:
    json.dump(recommendations, f, indent=4)

print(f"Successfully generated {len(recommendations)} cluster recommendations.")
