<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LookupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('lookup_championship_states')->insertOrIgnore([
            ['code' => 'planned',       'name' => 'Planned',        'description' => 'Being organized, not yet public',     'color_hex' => '#94a3b8', 'sort_order' => 1],
            ['code' => 'registration',  'name' => 'Registration',   'description' => 'Open for team registration',          'color_hex' => '#3b82f6', 'sort_order' => 2],
            ['code' => 'ongoing',       'name' => 'Ongoing',        'description' => 'Championship is active',              'color_hex' => '#22c55e', 'sort_order' => 3],
            ['code' => 'finished',      'name' => 'Finished',       'description' => 'Championship has concluded',          'color_hex' => '#6b7280', 'sort_order' => 4],
            ['code' => 'cancelled',     'name' => 'Cancelled',      'description' => 'Championship was cancelled',          'color_hex' => '#ef4444', 'sort_order' => 5],
        ]);

        DB::table('lookup_match_states')->insertOrIgnore([
            ['code' => 'pending',     'name' => 'Pending',     'description' => 'Created but not scheduled',       'color_hex' => '#94a3b8', 'is_terminal' => false, 'sort_order' => 1],
            ['code' => 'scheduled',   'name' => 'Scheduled',   'description' => 'Has a date and time assigned',    'color_hex' => '#3b82f6', 'is_terminal' => false, 'sort_order' => 2],
            ['code' => 'live',        'name' => 'Live',        'description' => 'Match is in progress',            'color_hex' => '#22c55e', 'is_terminal' => false, 'sort_order' => 3],
            ['code' => 'suspended',   'name' => 'Suspended',   'description' => 'Temporarily stopped',             'color_hex' => '#f59e0b', 'is_terminal' => false, 'sort_order' => 4],
            ['code' => 'postponed',   'name' => 'Postponed',   'description' => 'Moved to a new date',             'color_hex' => '#a78bfa', 'is_terminal' => false, 'sort_order' => 5],
            ['code' => 'finished',    'name' => 'Finished',    'description' => 'Match has concluded',             'color_hex' => '#6b7280', 'is_terminal' => true,  'sort_order' => 6],
            ['code' => 'cancelled',   'name' => 'Cancelled',   'description' => 'Definitively cancelled',          'color_hex' => '#ef4444', 'is_terminal' => true,  'sort_order' => 7],
        ]);

        DB::table('lookup_event_types')->insertOrIgnore([
            ['code' => 'goal',           'name' => 'Goal',              'affects_score' => true,  'is_sanction' => false, 'requires_player' => true,  'color_hex' => '#22c55e', 'sort_order' => 1],
            ['code' => 'own_goal',       'name' => 'Own Goal',          'affects_score' => true,  'is_sanction' => false, 'requires_player' => true,  'color_hex' => '#f59e0b', 'sort_order' => 2],
            ['code' => 'penalty',        'name' => 'Penalty Scored',    'affects_score' => true,  'is_sanction' => false, 'requires_player' => true,  'color_hex' => '#22c55e', 'sort_order' => 3],
            ['code' => 'penalty_missed', 'name' => 'Penalty Missed',    'affects_score' => false, 'is_sanction' => false, 'requires_player' => true,  'color_hex' => '#ef4444', 'sort_order' => 4],
            ['code' => 'assist',         'name' => 'Assist',            'affects_score' => false, 'is_sanction' => false, 'requires_player' => true,  'color_hex' => '#3b82f6', 'sort_order' => 5],
            ['code' => 'yellow_card',    'name' => 'Yellow Card',       'affects_score' => false, 'is_sanction' => true,  'requires_player' => true,  'color_hex' => '#fbbf24', 'sort_order' => 6],
            ['code' => 'double_yellow',  'name' => 'Double Yellow',     'affects_score' => false, 'is_sanction' => true,  'requires_player' => true,  'color_hex' => '#f97316', 'sort_order' => 7],
            ['code' => 'red_card',       'name' => 'Red Card',          'affects_score' => false, 'is_sanction' => true,  'requires_player' => true,  'color_hex' => '#ef4444', 'sort_order' => 8],
            ['code' => 'expulsion',      'name' => 'Expulsion',         'affects_score' => false, 'is_sanction' => true,  'requires_player' => true,  'color_hex' => '#dc2626', 'sort_order' => 9],
            ['code' => 'temp_card',      'name' => 'Temporary Card',    'affects_score' => false, 'is_sanction' => true,  'requires_player' => true,  'color_hex' => '#f59e0b', 'sort_order' => 10],
            ['code' => 'substitution',   'name' => 'Substitution',      'affects_score' => false, 'is_sanction' => false, 'requires_player' => true,  'color_hex' => '#6b7280', 'sort_order' => 11],
            ['code' => 'injury',         'name' => 'Injury',            'affects_score' => false, 'is_sanction' => false, 'requires_player' => true,  'color_hex' => '#f87171', 'sort_order' => 12],
            ['code' => 'other',          'name' => 'Other',             'affects_score' => false, 'is_sanction' => false, 'requires_player' => false, 'color_hex' => '#94a3b8', 'sort_order' => 99],
        ]);

        DB::table('lookup_phase_types')->insertOrIgnore([
            ['code' => 'group_stage',       'name' => 'Group Stage',        'description' => 'Round-robin within groups',           'sort_order' => 1],
            ['code' => 'round_robin',       'name' => 'Round Robin',        'description' => 'All teams play against each other',   'sort_order' => 2],
            ['code' => 'knockout',          'name' => 'Knockout',           'description' => 'Single elimination',                  'sort_order' => 3],
            ['code' => 'two_legged',        'name' => 'Two Legged',         'description' => 'Home and away fixtures',              'sort_order' => 4],
            ['code' => 'third_place',       'name' => 'Third Place',        'description' => 'Third place playoff',                 'sort_order' => 5],
            ['code' => 'final',             'name' => 'Final',              'description' => 'Championship final',                  'sort_order' => 6],
        ]);

        DB::table('lookup_sanction_types')->insertOrIgnore([
            ['code' => 'yellow_card',    'name' => 'Yellow Card',      'triggers_suspension' => false, 'yellows_to_accumulate' => 3,    'sort_order' => 1],
            ['code' => 'double_yellow',  'name' => 'Double Yellow',    'triggers_suspension' => true,  'yellows_to_accumulate' => null, 'sort_order' => 2],
            ['code' => 'red_card',       'name' => 'Red Card',         'triggers_suspension' => true,  'yellows_to_accumulate' => null, 'sort_order' => 3],
            ['code' => 'suspension',     'name' => 'Suspension',       'triggers_suspension' => true,  'yellows_to_accumulate' => null, 'sort_order' => 4],
            ['code' => 'fine',           'name' => 'Fine',             'triggers_suspension' => false, 'yellows_to_accumulate' => null, 'sort_order' => 5],
            ['code' => 'ban',            'name' => 'Ban',              'triggers_suspension' => true,  'yellows_to_accumulate' => null, 'sort_order' => 6],
        ]);

        DB::table('lookup_referee_roles')->insertOrIgnore([
            ['code' => 'main',          'name' => 'Main Referee',       'description' => 'Central match referee',           'sort_order' => 1],
            ['code' => 'assistant_1',   'name' => 'Assistant 1',        'description' => 'Left line',                       'sort_order' => 2],
            ['code' => 'assistant_2',   'name' => 'Assistant 2',        'description' => 'Right line',                      'sort_order' => 3],
            ['code' => 'fourth',        'name' => 'Fourth Official',    'description' => 'Substitutions and time control',  'sort_order' => 4],
            ['code' => 'var',           'name' => 'VAR',                'description' => 'Video assistant referee',         'sort_order' => 5],
        ]);

        DB::table('lookup_positions')->insertOrIgnore([
            ['code' => 'goalkeeper',        'name' => 'Goalkeeper',             'abbreviation' => 'GK',  'zone' => 'goalkeeper', 'sort_order' => 1],
            ['code' => 'center_back',       'name' => 'Center Back',            'abbreviation' => 'CB',  'zone' => 'defense',    'sort_order' => 2],
            ['code' => 'right_back',        'name' => 'Right Back',             'abbreviation' => 'RB',  'zone' => 'defense',    'sort_order' => 3],
            ['code' => 'left_back',         'name' => 'Left Back',              'abbreviation' => 'LB',  'zone' => 'defense',    'sort_order' => 4],
            ['code' => 'defensive_mid',     'name' => 'Defensive Midfielder',   'abbreviation' => 'CDM', 'zone' => 'midfield',   'sort_order' => 5],
            ['code' => 'central_mid',       'name' => 'Central Midfielder',     'abbreviation' => 'CM',  'zone' => 'midfield',   'sort_order' => 6],
            ['code' => 'attacking_mid',     'name' => 'Attacking Midfielder',   'abbreviation' => 'CAM', 'zone' => 'midfield',   'sort_order' => 7],
            ['code' => 'right_winger',      'name' => 'Right Winger',           'abbreviation' => 'RW',  'zone' => 'attack',     'sort_order' => 8],
            ['code' => 'left_winger',       'name' => 'Left Winger',            'abbreviation' => 'LW',  'zone' => 'attack',     'sort_order' => 9],
            ['code' => 'striker',           'name' => 'Striker',                'abbreviation' => 'ST',  'zone' => 'attack',     'sort_order' => 10],
            ['code' => 'second_striker',    'name' => 'Second Striker',         'abbreviation' => 'SS',  'zone' => 'attack',     'sort_order' => 11],
            ['code' => 'other',             'name' => 'Other',                  'abbreviation' => 'OTH', 'zone' => null,         'sort_order' => 99],
        ]);

        DB::table('lookup_registration_states')->insertOrIgnore([
            ['code' => 'pending',   'name' => 'Pending',    'description' => 'Awaiting approval',              'color_hex' => '#f59e0b', 'sort_order' => 1],
            ['code' => 'approved',  'name' => 'Approved',   'description' => 'Registration confirmed',         'color_hex' => '#22c55e', 'sort_order' => 2],
            ['code' => 'rejected',  'name' => 'Rejected',   'description' => 'Registration not approved',      'color_hex' => '#ef4444', 'sort_order' => 3],
            ['code' => 'withdrawn', 'name' => 'Withdrawn',  'description' => 'Team voluntarily withdrew',      'color_hex' => '#94a3b8', 'sort_order' => 4],
        ]);

        DB::table('lookup_dominant_feet')->insertOrIgnore([
            ['code' => 'right', 'name' => 'Right'],
            ['code' => 'left',  'name' => 'Left'],
            ['code' => 'both',  'name' => 'Both'],
        ]);

        DB::table('lookup_medical_states')->insertOrIgnore([
            ['code' => 'fit',         'name' => 'Fit',            'description' => 'Fully fit to play',          'can_play' => true,  'color_hex' => '#22c55e', 'sort_order' => 1],
            ['code' => 'injured',     'name' => 'Injured',        'description' => 'Currently injured',          'can_play' => false, 'color_hex' => '#ef4444', 'sort_order' => 2],
            ['code' => 'recovering',  'name' => 'Recovering',     'description' => 'In recovery process',        'can_play' => false, 'color_hex' => '#f59e0b', 'sort_order' => 3],
            ['code' => 'suspended',   'name' => 'Suspended',      'description' => 'Serving a suspension',       'can_play' => false, 'color_hex' => '#a78bfa', 'sort_order' => 4],
            ['code' => 'unknown',     'name' => 'Unknown',        'description' => 'Status not yet determined',  'can_play' => false, 'color_hex' => '#94a3b8', 'sort_order' => 5],
        ]);
    }
}
