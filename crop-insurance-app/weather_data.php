<?php
require_once __DIR__ . '/db.php';

/**
 * WEATHER_DATA TABLE HELPERS
 * Schema:
 *  weather_data(weather_id, location_id, temperature, humidity, wind_speed, uv_index, recorded_at)
 */

function weather_get_all()
{
    global $conn;
    $sql = "SELECT weather_id, location_id, temperature, humidity, wind_speed, uv_index, recorded_at FROM weather_data ORDER BY weather_id";
    $result = $conn->query($sql);

    $rows = [];
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->free();
    }
    return $rows;
}

function weather_create($location_id, $temperature, $humidity, $wind_speed, $uv_index, $recorded_at)
{
    global $conn;
    $stmt = $conn->prepare(
        "INSERT INTO weather_data (location_id, temperature, humidity, wind_speed, uv_index, recorded_at) VALUES (?, ?, ?, ?, ?, ?)"
    );
    $stmt->bind_param('idddds', $location_id, $temperature, $humidity, $wind_speed, $uv_index, $recorded_at);
    if (!$stmt->execute()) {
        throw new Exception('Failed to insert weather data: ' . $stmt->error);
    }
    $id = $stmt->insert_id;
    $stmt->close();
    return $id;
}


