<?php

namespace App\Services;

use App\Models\UserOffDay;

class OffDayService
{
  public function getAllOffDaysOfUser($userId)
  {
    return UserOffDay::where('user_id', $userId)
      ->get();
  }

  public function getAllOffDaysByDate($date)
  {
    return UserOffDay::where('date', $date)
      ->with('user')
      ->get();
  }

  public function batchInsert($data)
  {
    $batchData = [];
    foreach ($data['newOffDays'] as $date) {
      array_push($batchData, [
        'user_id' => $data['userId'],
        'date' => $date,
        'reason' => $data['reason'],
        'created_at' => now(),
        'updated_at' => now(),
      ]);
    }
    UserOffDay::insert($batchData);
  }
}
