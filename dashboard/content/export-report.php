<?php
include_once '../../config.php';

if (isset($_POST['export'])) {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=data.csv');
    $output = fopen("php://output", "w");

    // Write headers to CSV file
    fputcsv($output, array('Table', 'Data'));

    // List of tables to export
    $tables = ['users', 'tasks', 'projects', 'departments'];
    
    foreach ($tables as $table) {
        $result = $conn->query("SELECT * FROM $table");
        if ($result->num_rows > 0) {
            // Write table name as a new section
            fputcsv($output, array($table, ''));  // Table header
            
            // Get column names
            $fields = array();
            while ($fieldinfo = $result->fetch_field()) {
                $fields[] = $fieldinfo->name;
            }
            fputcsv($output, $fields);
            
            // Fetch rows
            while ($row = $result->fetch_assoc()) {
                fputcsv($output, $row);
            }
            // Add a blank line after each table for separation
            fputcsv($output, array(''));
        }
    }
    
    fclose($output);
    exit();
}
?>