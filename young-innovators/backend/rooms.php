<?php
require_once __DIR__ . '/../config.php';

$user = requireAuth('student');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

function roomCode(): string {
    return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

if ($action === 'create') {
    $pdo = db();

    $gamePage = post('game_page');
    $csvFile = post('csv_file');
    $courseId = post('course_id');

    if (!$gamePage || !$csvFile) {
        jsonError('Missing game data');
    }

    do {
        $code = roomCode();
        $stmt = $pdo->prepare("SELECT id FROM game_rooms WHERE room_code=?");
        $stmt->execute([$code]);
    } while ($stmt->fetch());

    $pdo->prepare("
        INSERT INTO game_rooms
        (room_code, game_page, csv_file, course_id, host_user_id, status)
        VALUES (?, ?, ?, ?, ?, 'waiting')
    ")->execute([
        $code,
        $gamePage,
        $csvFile,
        $courseId ?: null,
        $user['id']
    ]);

    $pdo->prepare("
        INSERT INTO game_room_players
        (room_code, user_id, display_name, is_host)
        VALUES (?, ?, ?, 1)
    ")->execute([
        $code,
        $user['id'],
        $user['display_name'] ?? $user['first_name'] ?? 'Host'
    ]);

    jsonOk([
        'room_code' => $code
    ]);
}

if ($action === 'join') {
    $pdo = db();

    $code = post('room_code');

    if (!$code) {
        jsonError('Missing room code');
    }

    $stmt = $pdo->prepare("SELECT * FROM game_rooms WHERE room_code=?");
    $stmt->execute([$code]);
    $room = $stmt->fetch();

    if (!$room) {
        jsonError('Room not found');
    }

    if ($room['status'] !== 'waiting') {
        jsonError('Game already started');
    }

    $pdo->prepare("
        INSERT INTO game_room_players
        (room_code, user_id, display_name, is_host)
        VALUES (?, ?, ?, 0)
        ON DUPLICATE KEY UPDATE is_kicked=0
    ")->execute([
        $code,
        $user['id'],
        $user['display_name'] ?? $user['first_name'] ?? 'Player'
    ]);

    jsonOk([
        'room' => $room
    ]);
}

if ($action === 'status') {
    $pdo = db();

    $code = get('room_code');

    $stmt = $pdo->prepare("SELECT * FROM game_rooms WHERE room_code=?");
    $stmt->execute([$code]);
    $room = $stmt->fetch();

    if (!$room) {
        jsonError('Room not found');
    }

    $stmt = $pdo->prepare("
        SELECT user_id, display_name, is_host, is_kicked
        FROM game_room_players
        WHERE room_code=?
        ORDER BY is_host DESC, joined_at ASC
    ");
    $stmt->execute([$code]);

    jsonOk([
        'room' => $room,
        'players' => $stmt->fetchAll()
    ]);
}

if ($action === 'start') {
    $pdo = db();

    $code = post('room_code');

    $stmt = $pdo->prepare("
        SELECT * FROM game_rooms
        WHERE room_code=? AND host_user_id=?
    ");
    $stmt->execute([$code, $user['id']]);
    $room = $stmt->fetch();

    if (!$room) {
        jsonError('Only host can start');
    }

    $pdo->prepare("
        UPDATE game_rooms SET status='started'
        WHERE room_code=?
    ")->execute([$code]);

    jsonOk();
}

if ($action === 'leave') {
    $pdo = db();

    $code = post('room_code');

    $pdo->prepare("
        DELETE FROM game_room_players
        WHERE room_code=? AND user_id=?
    ")->execute([$code, $user['id']]);

    jsonOk();
}

if ($action === 'kick') {
    $pdo = db();

    $code = post('room_code');
    $targetId = (int) post('target_user_id');

    $stmt = $pdo->prepare("
        SELECT id FROM game_rooms
        WHERE room_code=? AND host_user_id=?
    ");
    $stmt->execute([$code, $user['id']]);

    if (!$stmt->fetch()) {
        jsonError('Only host can kick');
    }

    $pdo->prepare("
        UPDATE game_room_players
        SET is_kicked=1
        WHERE room_code=? AND user_id=? AND is_host=0
    ")->execute([$code, $targetId]);

    jsonOk();
}
if ($action === 'submit_answer') {
    $pdo = db();

    $code = post('room_code');
    $roomNumber = (int) post('room_number');
    $isCorrect = post('is_correct') === '1';

    if (!$code || !$roomNumber) {
        jsonError('Missing room data');
    }

    if (!$isCorrect) {
        jsonOk([
            'winner' => false,
            'message' => 'Wrong answer'
        ]);
    }

    $stmt = $pdo->prepare("
        SELECT *
        FROM game_rooms
        WHERE room_code=?
    ");
    $stmt->execute([$code]);
    $room = $stmt->fetch();

    if (!$room) {
        jsonError('Room not found');
    }

    if ($room['current_question_status'] === 'answered') {
        jsonOk([
            'winner' => false,
            'already_answered' => true,
            'winner_name' => $room['last_winner_name']
        ]);
    }

    $pdo->prepare("
        UPDATE game_rooms
        SET current_question_status='answered',
            last_winner_user_id=?,
            last_winner_name=?,
            last_answer_at=NOW()
        WHERE room_code=?
          AND current_question_status='open'
    ")->execute([
        $user['id'],
        $user['display_name'] ?? $user['first_name'] ?? 'Player',
        $code
    ]);

    jsonOk([
        'winner' => true,
        'winner_name' => $user['display_name'] ?? $user['first_name'] ?? 'Player'
    ]);
}

if ($action === 'next_room') {
    $pdo = db();

    $code = post('room_code');
    $nextRoom = (int) post('room_number');

    $stmt = $pdo->prepare("
        SELECT *
        FROM game_rooms
        WHERE room_code=? AND host_user_id=?
    ");
    $stmt->execute([$code, $user['id']]);

    if (!$stmt->fetch()) {
        jsonError('Only host can move room');
    }

    $pdo->prepare("
        UPDATE game_rooms
        SET current_room=?,
            current_question_status='open',
            last_winner_user_id=NULL,
            last_winner_name=NULL,
            last_answer_at=NULL
        WHERE room_code=?
    ")->execute([
        $nextRoom,
        $code
    ]);

    jsonOk();
}
if ($action === 'update_position') {
    $pdo = db();

    $code = post('room_code');
    $x = (int) post('x');
    $y = (int) post('y');

    $pdo->prepare("
        UPDATE game_room_players
        SET pos_x=?, pos_y=?
        WHERE room_code=? AND user_id=? AND is_kicked=0
    ")->execute([$x, $y, $code, $user['id']]);

    jsonOk();
}

if ($action === 'sync_state') {
    $pdo = db();

    $code = get('room_code');
    $roomNumber = (int) get('room_number');

    $stmt = $pdo->prepare("
        SELECT * FROM game_rooms
        WHERE room_code=?
    ");
    $stmt->execute([$code]);
    $room = $stmt->fetch();

    if (!$room) {
        jsonError('Room not found');
    }

    $stmt = $pdo->prepare("
        SELECT user_id, display_name, is_host, is_kicked, color, pos_x, pos_y, score
        FROM game_room_players
        WHERE room_code=? AND is_kicked=0
        ORDER BY is_host DESC, joined_at ASC
    ");
    $stmt->execute([$code]);
    $players = $stmt->fetchAll();

    $stmt = $pdo->prepare("
        SELECT user_id, display_name, answer_rank, points_awarded
        FROM game_room_answers
        WHERE room_code=? AND room_number=?
        ORDER BY answer_rank ASC
    ");
    $stmt->execute([$code, $roomNumber]);
    $answers = $stmt->fetchAll();

    jsonOk([
        'room' => $room,
        'players' => $players,
        'answers' => $answers
    ]);
}

if ($action === 'submit_ranked_answer') {
    $pdo = db();

    $code = post('room_code');
    $roomNumber = (int) post('room_number');
    $isCorrect = post('is_correct') === '1';

    if (!$code || !$roomNumber) {
        jsonError('Missing room data');
    }

    if (!$isCorrect) {
        jsonOk([
            'correct' => false,
            'message' => 'Wrong answer'
        ]);
    }

    $stmt = $pdo->prepare("
        SELECT id
        FROM game_room_answers
        WHERE room_code=? AND room_number=? AND user_id=?
    ");
    $stmt->execute([$code, $roomNumber, $user['id']]);

    if ($stmt->fetch()) {
        jsonOk([
            'correct' => true,
            'already_answered' => true
        ]);
    }

    $stmt = $pdo->prepare("
        SELECT COUNT(*)
        FROM game_room_answers
        WHERE room_code=? AND room_number=?
    ");
    $stmt->execute([$code, $roomNumber]);

    $rank = ((int)$stmt->fetchColumn()) + 1;

    if ($rank > 4) {
        $rank = 4;
    }

    $pointsMap = [
        1 => 100,
        2 => 75,
        3 => 50,
        4 => 25
    ];

    $points = $pointsMap[$rank];

    $pdo->prepare("
        INSERT INTO game_room_answers
        (room_code, room_number, user_id, display_name, answer_rank, points_awarded)
        VALUES (?, ?, ?, ?, ?, ?)
    ")->execute([
        $code,
        $roomNumber,
        $user['id'],
        $user['display_name'] ?? $user['first_name'] ?? 'Player',
        $rank,
        $points
    ]);

    $pdo->prepare("
        UPDATE game_room_players
        SET score = score + ?
        WHERE room_code=? AND user_id=?
    ")->execute([
        $points,
        $code,
        $user['id']
    ]);

    jsonOk([
        'correct' => true,
        'rank' => $rank,
        'points' => $points
    ]);
}
if ($action === 'boxing_answer') {
    $pdo = db();

    $code = post('room_code');
    $questionIndex = (int) post('question_index');
    $isCorrect = post('is_correct') === '1';

    if (!$code) {
        jsonError('Missing room code');
    }

    if (!$isCorrect) {
        jsonOk([
            'winner' => false,
            'message' => 'Wrong answer'
        ]);
    }

    $roomNumber = $questionIndex + 1;

    $stmt = $pdo->prepare("
        SELECT *
        FROM game_room_answers
        WHERE room_code=? AND room_number=?
        LIMIT 1
    ");
    $stmt->execute([$code, $roomNumber]);

    if ($stmt->fetch()) {
        jsonOk([
            'winner' => false,
            'already_answered' => true
        ]);
    }

    $displayName =
        $user['display_name'] ??
        $user['first_name'] ??
        'Player';

    $pdo->prepare("
        INSERT INTO game_room_answers
        (room_code, room_number, user_id, display_name, answer_rank, points_awarded)
        VALUES (?, ?, ?, ?, 1, 100)
    ")->execute([
        $code,
        $roomNumber,
        $user['id'],
        $displayName
    ]);

    $pdo->prepare("
        UPDATE game_room_players
        SET score = score + 100
        WHERE room_code=? AND user_id=?
    ")->execute([
        $code,
        $user['id']
    ]);

    jsonOk([
        'winner' => true,
        'winner_name' => $displayName
    ]);
}

if ($action === 'boxing_state') {
    $pdo = db();

    $code = get('room_code');

    if (!$code) {
        jsonError('Missing room code');
    }

    $stmt = $pdo->prepare("
        SELECT *
        FROM game_rooms
        WHERE room_code=?
    ");
    $stmt->execute([$code]);
    $room = $stmt->fetch();

    $stmt = $pdo->prepare("
        SELECT *
        FROM game_room_players
        WHERE room_code=? AND is_kicked=0
        ORDER BY joined_at ASC
        LIMIT 2
    ");
    $stmt->execute([$code]);
    $players = $stmt->fetchAll();

    $stmt = $pdo->prepare("
        SELECT *
        FROM game_room_answers
        WHERE room_code=?
        ORDER BY room_number ASC
    ");
    $stmt->execute([$code]);
    $answers = $stmt->fetchAll();

    jsonOk([
        'room' => $room,
        'players' => $players,
        'answers' => $answers,
        'current_user_id' => $user['id']
    ]);
}
jsonError('Unknown action');