<?php

/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simple to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See https://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - https://datatables.net/license_mit
 */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */

// DB table to use
$sql_details = array(
    'user' => 'root',
    'pass' => '',
    'db'   => 'absensiapi',
    'host' => 'localhost'
);

// Table to use
$table = 'kategorishift as ks';
// Primary key
$primaryKey = 'ks.id'; // Adjust as necessary based on your table structure

// Columns to select and format
$columns = array(
    array('db' => 'users.nama', 'dt' => 0),
    array('db' => 'users.npk', 'dt' => 1),
    array('db' => 'ks.date', 'dt' => 2),
    array('db' => 'ks.shift1', 'dt' => 3),
    array(
        'db'        => 'absensici.waktuci',
        'dt'        => 4,
        'formatter' => function ($d, $row) {
            return $d ? date('H:i', strtotime($d)) : 'NO IN';
        }
    ),
    array(
        'db'        => 'absensico.waktuco',
        'dt'        => 5,
        'formatter' => function ($d, $row) {
            return $d ? date('H:i', strtotime($d)) : 'NO OUT';
        }
    ),
    array(
        'db' => 'ks.date',
        'dt' => 6,
        'formatter' => function ($d, $row) {
            // Additional logic for status
            // e.g., return 'Tepat Waktu' or 'Terlambat' based on your logic
            return $d; // This should reflect the status based on your calculations
        }
    ),
);

// Integrate with SSP for DataTables
echo json_encode(
    SSP::simple($request->all(), $sql_details, $table, $primaryKey, $columns)
);
