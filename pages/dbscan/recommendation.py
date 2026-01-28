import pandas as pd
import pymysql
from sklearn.cluster import DBSCAN
from sklearn.preprocessing import StandardScaler

# Database connection
connection = pymysql.connect(
    host="localhost",
    user="root",
    password="",
    database="pms_db"
)

try:
    # Get reservation data
    query = "SELECT reservation_id, room_id, guest_count, duration FROM tbl_reservations"
    df = pd.read_sql(query, connection)

    if df.empty:
        print("No reservation data found.")
    else:
        # Prepare features for clustering
        features = df[['guest_count', 'duration']]
        scaled_features = StandardScaler().fit_transform(features)

        # Apply DBSCAN
        db = DBSCAN(eps=1.0, min_samples=2).fit(scaled_features)
        df['cluster_id'] = db.labels_

        # Clear old cluster data
        with connection.cursor() as cursor:
            cursor.execute("TRUNCATE TABLE tbl_recommendation_clusters")
            connection.commit()

        # Insert new cluster results
        insert_query = """
            INSERT INTO tbl_recommendation_clusters (reservation_id, room_id, cluster_id)
            VALUES (%s, %s, %s)
        """
        with connection.cursor() as cursor:
            for _, row in df.iterrows():
                cursor.execute(insert_query, (row['reservation_id'], row['room_id'], int(row['cluster_id'])))
            connection.commit()

        print(" DBSCAN clustering completed and saved to tbl_recommendation_clusters.")

finally:
    connection.close()
