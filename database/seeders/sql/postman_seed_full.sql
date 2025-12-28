-- DATA SEEDING FOR FULL SYSTEM TESTING
-- Execute in DBeaver

-- 1. USERS & PROFILES
-- Ensure Dummy User exists
INSERT INTO auth_users (id, username, google_id, role, created_at, updated_at)
VALUES (999, 'dummy_tester', 'google_dummy_999', 'player', NOW(), NOW())
ON DUPLICATE KEY UPDATE username=VALUES(username);

INSERT INTO playerprofile (PlayerId, lifetime_scores, weak_areas, confidence_level, created_at, updated_at)
VALUES ('player_dummy_profiling_infinite', '{"pendapatan": 65, "anggaran": 65, "tabungan_dan_dana_darurat": 50, "utang": 45, "investasi": 60, "asuransi_dan_proteksi": 60, "tujuan_jangka_panjang": 60, "overall": 58}', '["tabungan_dan_dana_darurat", "utang"]', 0.8, NOW(), NOW())
ON DUPLICATE KEY UPDATE lifetime_scores=VALUES(lifetime_scores);

-- 2. BOARD TILES (Required for Movement)
-- User wants GET /tile/6. Assuming ID='6' or Index=6. Providing both for safety.
INSERT INTO boardtiles (tile_id, position_index, type, name, category, linked_content)
VALUES (6, 6, 'property', 'Apartemen', 'investasi', '{"type": "asset_purchase", "value": 150000}')
ON DUPLICATE KEY UPDATE name=VALUES(name);

-- 3. CONTENT: SCENARIOS
-- User wants GET /scenario/uang_bulanan_01
INSERT INTO scenarios (id, category, title, question, difficulty, expected_benefit, created_at, updated_at) VALUES 
('uang_bulanan_01', 'anggaran', 'Uang Bulanan Menipis', 'Uang bulanan tinggal sedikit di akhir bulan.', 10, 5, NOW(), NOW())
ON DUPLICATE KEY UPDATE title=VALUES(title);

INSERT INTO scenario_options (scenarioId, optionId, text, is_correct, response, scoreChange) VALUES
('uang_bulanan_01', 'A', 'Makan mie instan sisa bulan.', 1, 'Hemat pangkal kaya.', '{"anggaran": 10}'),
('uang_bulanan_01', 'B', 'Pinjam uang teman.', 0, 'Jangan biasakan berhutang.', '{"utang": -10}')
ON DUPLICATE KEY UPDATE text=VALUES(text);

-- 4. CONTENT: CARDS (Risk/Chance)
-- User wants GET /card/risk/risk_07 and GET /card/chance/chance_10
INSERT INTO cards (id, type, title, narration, categories, scoreChange, action, created_at, updated_at) VALUES 
('risk_07', 'risk', 'Dompet Hilang', 'Dompet jatuh di jalan.', '["tabungan"]', -100000, 'pay', NOW(), NOW()),
('chance_10', 'chance', 'Menang Lomba', 'Juara 1 lomba makan kerupuk.', '["pendapatan"]', 250000, 'receive', NOW(), NOW())
ON DUPLICATE KEY UPDATE title=VALUES(title);

-- 5. CONTENT: QUIZ
-- User wants GET /card/quiz/quiz_01 to return "Apa fungsi utama dana darurat?"
INSERT INTO quiz_cards (id, question, tags, difficulty, correctOption, correctScore, incorrectScore, created_at, updated_at) VALUES
('quiz_01', 'Apa fungsi utama dana darurat?', '["tabungan_dan_dana_darurat"]', 1, 'B', 10, 0, NOW(), NOW())
ON DUPLICATE KEY UPDATE question=VALUES(question), tags=VALUES(tags), correctOption=VALUES(correctOption);

INSERT INTO quiz_options (quizId, optionId, text) VALUES
('quiz_01', 'A', 'Untuk liburan'),
('quiz_01', 'B', 'Untuk kebutuhan mendesak'),
('quiz_01', 'C', 'Untuk investasi saham')
ON DUPLICATE KEY UPDATE text=VALUES(text);

-- 6. SESSION (mock active session for testing /session/... endpoints directly)
-- Session ID: sess_test_01 | Host: player_dummy_profiling_infinite
INSERT INTO game_sessions (sessionId, host_player_id, max_players, max_turns, status, current_turn, current_player_id, game_state, created_at, updated_at)
VALUES ('sess_test_01', 'player_dummy_profiling_infinite', 4, 20, 'active', 1, 'player_dummy_profiling_infinite', '{"turn_phase": "start"}', NOW(), NOW())
ON DUPLICATE KEY UPDATE status='active';

INSERT INTO participatesin (sessionId, playerId, position, score, player_order, connection_status, is_ready, joined_at, created_at, updated_at)
VALUES ('sess_test_01', 'player_dummy_profiling_infinite', 0, 1000, 1, 'connected', 1, NOW(), NOW(), NOW())
ON DUPLICATE KEY UPDATE connection_status='connected';