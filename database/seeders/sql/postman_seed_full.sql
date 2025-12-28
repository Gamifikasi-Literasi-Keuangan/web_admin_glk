-- DATA SEEDING FOR FULL SYSTEM TESTING
-- Execute in DBeaver
-- Note: FullGameSeeder already creates most game content, this only adds test players & sessions

-- 1. USERS & PROFILES (Test user only, admin already exists from AdminUserSeeder)
-- INSERT INTO auth_users (id, username, google_id, role, created_at, updated_at)
-- VALUES (999, 'dummy_tester', 'google_dummy_999', 'player', NOW(), NOW())
-- ON DUPLICATE KEY UPDATE username=VALUES(username);

INSERT INTO playerprofile (PlayerId, lifetime_scores, weak_areas, confidence_level, created_at, updated_at)
VALUES ('player_dummy_profiling_infinite', '{"pendapatan": 65, "anggaran": 65, "tabungan_dan_dana_darurat": 50, "utang": 45, "investasi": 60, "asuransi_dan_proteksi": 60, "tujuan_jangka_panjang": 60, "overall": 58}', '["tabungan_dan_dana_darurat", "utang"]', 0.8, NOW(), NOW())
ON DUPLICATE KEY UPDATE lifetime_scores=VALUES(lifetime_scores);

-- 2. SESSION (mock active session for testing /session/... endpoints directly)
-- Session ID: sess_test_01 | Host: player_dummy_profiling_infinite
INSERT INTO game_sessions (sessionId, host_player_id, max_players, max_turns, status, current_turn, current_player_id, game_state, created_at, updated_at)
VALUES ('sess_test_01', 'player_dummy_profiling_infinite', 4, 20, 'active', 1, 'player_dummy_profiling_infinite', '{"turn_phase": "start"}', NOW(), NOW())
ON DUPLICATE KEY UPDATE status='active';

INSERT INTO participatesin (sessionId, playerId, position, score, player_order, connection_status, is_ready, joined_at, created_at, updated_at)
VALUES ('sess_test_01', 'player_dummy_profiling_infinite', 0, 1000, 1, 'connected', 1, NOW(), NOW(), NOW())
ON DUPLICATE KEY UPDATE connection_status='connected';