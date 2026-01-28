
import pandas as pd
from sklearn.cluster import DBSCAN

# Read from exported CSV
df = pd.read_csv("booking_data.csv")

# Convert checkin/checkout to timestamp
df['checkin_ts'] = pd.to_datetime(df['checkin']).astype(int) // 10**9
df['checkout_ts'] = pd.to_datetime(df['checkout']).astype(int) // 10**9

# Cluster
X = df[['room_id', 'checkin_ts', 'checkout_ts']]
dbscan = DBSCAN(eps=1000000, min_samples=2)
df['cluster'] = dbscan.fit_predict(X)

# Output to CSV
df.to_csv("booking_clusters.csv", index=False)
print("Clustering complete. Results saved to booking_clusters.csv.")
