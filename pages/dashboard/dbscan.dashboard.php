<?php
include "../../includes/conn.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>DBSCAN Clustering Dashboard</title>
    <link rel="stylesheet" href="../../dist/css/adminlte.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="content-wrapper p-4">
        <h2>📊 DBSCAN Clustering Dashboard</h2>

        <button id="runDbscan" class="btn btn-primary mb-3">Run DBSCAN</button>

        <table class="table table-bordered" id="resultTable">
            <thead>
                <tr>
                    <th>Reservation ID</th>
                    <th>Guest Name</th>
                    <th>Guest Count</th>
                    <th>Duration (days)</th>
                    <th>Room Number</th>
                    <th>Cluster</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>

        <canvas id="clusterChart" height="100"></canvas>
    </div>
</div>

<script>
let clusterChart;

async function loadData() {
    const response = await fetch("dbscan.data.php");
    const data = await response.json();

    const tbody = document.querySelector("#resultTable tbody");
    tbody.innerHTML = "";
    data.reservations.forEach(row => {
        let clusterBadge = row.cluster_label == -1
            ? `<span class='badge badge-danger'>Outlier</span>`
            : `<span class='badge badge-success'>Cluster ${row.cluster_label}</span>`;
        
        tbody.innerHTML += `
            <tr>
                <td>${row.reservation_id}</td>
                <td>${row.guestName}</td>
                <td>${row.guest_count}</td>
                <td>${row.duration_days}</td>
                <td>${row.room_number}</td>
                <td>${clusterBadge}</td>
            </tr>
        `;
    });

    if (clusterChart) clusterChart.destroy();

    const ctx = document.getElementById('clusterChart').getContext('2d');
    clusterChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.clusters.labels,
            datasets: [{
                label: 'Number of Reservations',
                data: data.clusters.counts,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
            }]
        }
    });
}

document.getElementById("runDbscan").addEventListener("click", async () => {
    const res = await fetch("dbscan.data.php?run=1");
    const msg = await res.text();
    alert(msg);
    loadData();
});

loadData();
</script>
</body>
</html>
