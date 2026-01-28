import mysql.connector
import pandas as pd
from sklearn.cluster import DBSCAN
from sklearn.preprocessing import StandardScaler
from sklearn.preprocessing import LabelEncoder

pd.set_option('future.no_silent_downcasting', True)

conn = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="pms_db"
)

cursor = conn.cursor()

query = """
SELECT r.reservation_id, 
       COALESCE(r.guest_count, 0) AS guest_count,
       DATEDIFF(r.checkout, r.checkin) AS duration_days,
       rm.room_number,
       DAYOFWEEK(r.checkin) AS checkin_dayofweek -- 1=Sunday, 7=Saturday
FROM tbl_reservations r
JOIN tbl_rooms rm ON rm.room_id = r.room_id
WHERE r.checkin IS NOT NULL 
  AND r.checkout IS NOT NULL
"""
cursor.execute(query)
rows = cursor.fetchall()
columns = [desc[0] for desc in cursor.description]
df = pd.DataFrame(rows, columns=columns)

if df.empty:
    print("No valid data to cluster.")
    cursor.close()
    conn.close()
    exit()

print(f"Data loaded from DB. Total rows: {len(df)}")


le = LabelEncoder()
df['room_encoded'] = le.fit_transform(df['room_number'])


features = df[['guest_count', 'duration_days', 'room_encoded', 'checkin_dayofweek']].fillna(0).astype(float)

scaler = StandardScaler()
scaled_features = scaler.fit_transform(features) 
print("Features scaled successfully.")


dbscan = DBSCAN(eps=0.8, min_samples=5).fit(scaled_features) 
df['cluster'] = dbscan.labels_
print("Clustering completed!")


for idx, row in df.iterrows():
    cursor.execute("""
        UPDATE tbl_reservations
        SET cluster_label = %s
        WHERE reservation_id = %s
    """, (int(row['cluster']), int(row['reservation_id'])))

conn.commit()
print("Clustering results saved to database!")

cursor.close()
conn.close()