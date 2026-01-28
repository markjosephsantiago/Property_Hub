from sklearn.cluster import DBSCAN
import pandas as pd
import matplotlib.pyplot as plt

# Load CSV
data = pd.read_csv("reservations.csv")

# Remove missing values
data = data.dropna(subset=["guest_count", "duration_days"])

# Prepare features
X = data[["guest_count", "duration_days"]]

# Try smaller eps and tweak min_samples
db = DBSCAN(eps=0.38, min_samples=2).fit(X)

# Add cluster labels
data["cluster"] = db.labels_

# Save outliers
outliers = data[data["cluster"] == -1]
outliers.to_csv("outliers.csv", index=False)

# Plot
plt.figure(figsize=(10,6))
plt.scatter(data["guest_count"], data["duration_days"], c=data["cluster"], cmap="plasma", s=100)
plt.title("DBSCAN Clustering of Reservations")
plt.xlabel("Guest Count")
plt.ylabel("Duration (Days)")
plt.colorbar(label="Cluster ID")
plt.show()
