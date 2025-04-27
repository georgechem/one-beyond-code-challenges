<?php
namespace App\Controllers;

require_once __DIR__ . '/../Data/data.php';
require_once __DIR__ . '/../Models/Reservation.php';

class FineController {
    // POST /reservations
    public function reserve() {
        // TODO: Implement logic to reserve a book currently on loan.
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Reserve book functionality to be implemented.']);
    }

    // GET /reservations
    public function status() {
        // TODO: Implement logic to return reservation status for a given borrower and book.
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Reservation status functionality to be implemented.']);
    }
}
