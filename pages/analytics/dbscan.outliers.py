from sklearn.cluster import DBSCAN
import pandas as pd
import matplotlib.pyplot as plt

# Load CSV
data = pd.read_csv("reservations.csv")

# Remove missing values
data = data.dropna(subset=["guest_count", "duration"])

# Prepare features
X = data[["guest_count", "duration"]]

# Try smaller eps and tweak min_samples
db = DBSCAN(eps=0.38, min_samples=2).fit(X)

# Add cluster labels
data["cluster"] = db.labels_

# Save outliers
outliers = data[data["cluster"] == -1]
outliers.to_csv("outliers.csv", index=False)

# Plot
# Plot
plt.figure(figsize=(10,6))
scatter = plt.scatter(data["guest_count"], data["duration"], c=data["cluster"], cmap="plasma", s=100)
plt.title("Booking Pattern Analysis (Guest Count vs Duration)")
plt.xlabel("Guest Count (Number of People)")
plt.ylabel("Stay Duration (Days)")
plt.colorbar(scatter, label="Booking Segment (Cluster ID)")
plt.show()
