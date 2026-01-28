import pandas as pd
from sklearn.preprocessing import StandardScaler
from sklearn.neighbors import NearestNeighbors
import matplotlib.pyplot as plt
import numpy as np

# Load data
data = pd.read_csv("reservations.csv")

# Clean and select numeric columns
X = data[['guest_count', 'duration_days']].dropna()
X_scaled = StandardScaler().fit_transform(X)

# Compute nearest neighbors
neighbors = NearestNeighbors(n_neighbors=2)
neighbors_fit = neighbors.fit(X_scaled)
distances, indices = neighbors_fit.kneighbors(X_scaled)

# Sort distances to get k-distance curve
distances = np.sort(distances[:, 1])

# Plot k-distance graph
plt.figure(figsize=(8, 5))
plt.plot(distances)
plt.title("K-Distance Graph (use elbow point as eps)")
plt.xlabel("Data Points (sorted)")
plt.ylabel("Distance to Nearest Neighbor")
plt.grid(True)
plt.show()
