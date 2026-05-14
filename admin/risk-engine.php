<?php

function getRiskLevel($user_id, $conn) {

    // count violations per user
    $query = mysqli_query(
        $conn,
        "SELECT COUNT(*) as total FROM violations WHERE user_id='$user_id'"
    );

    $row = mysqli_fetch_assoc($query);
    $count = $row['total'];

    // RISK LOGIC
    if ($count >= 3) {
        return ["High", "badge-high"];
    }
    elseif ($count >= 1) {
        return ["Medium", "badge-medium"];
    }
    else {
        return ["Low", "badge-low"];
    }
}

?>