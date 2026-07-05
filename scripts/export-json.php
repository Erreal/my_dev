<?php
/**
 * CLI script to export database to JSON files.
 * 
 * Usage: php scripts/export-json.php
 * 
 * This script is used by GitHub Actions during the build process
 * to export the latest data from the production database.
 */

// Load admin config
require_once __DIR__ . '/../admin/config.php';

use App\Config\Database;

echo "Starting JSON export...\n";

try {
    $db = Database::getInstance();
    $dataPath = FRONTEND_DATA_PATH;

    if (!is_dir($dataPath)) {
        mkdir($dataPath, 0755, true);
        echo "Created data directory: {$dataPath}\n";
    }

    // Export profile
    $profile = $db->fetchOne('SELECT * FROM profile WHERE id = 1');
    if ($profile) {
        unset($profile['id'], $profile['created_at'], $profile['updated_at']);
        file_put_contents(
            $dataPath . '/profile.json',
            json_encode($profile, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
        echo "✓ Exported profile\n";
    } else {
        echo "⚠ No profile found\n";
    }

    // Export technologies
    $technologies = $db->fetchAll('SELECT * FROM technologies ORDER BY sort_order');
    foreach ($technologies as &$tech) {
        unset($tech['created_at'], $tech['updated_at']);
    }
    file_put_contents(
        $dataPath . '/technologies.json',
        json_encode($technologies, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
    );
    echo "✓ Exported " . count($technologies) . " technologies\n";

    // Export experience
    $experience = $db->fetchAll('SELECT * FROM experience ORDER BY sort_order');
    foreach ($experience as &$exp) {
        unset($exp['created_at'], $exp['updated_at']);
    }
    file_put_contents(
        $dataPath . '/experience.json',
        json_encode($experience, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
    );
    echo "✓ Exported " . count($experience) . " experience entries\n";

    // Export projects with technologies and screenshots
    $projects = $db->fetchAll('SELECT * FROM projects ORDER BY sort_order');
    foreach ($projects as &$project) {
        unset($project['created_at'], $project['updated_at']);

        // Get technology IDs
        $techRows = $db->fetchAll(
            'SELECT technology_id FROM project_technologies WHERE project_id = ? ORDER BY technology_id',
            [$project['id']]
        );
        $project['technologies'] = array_column($techRows, 'technology_id');

        // Get screenshots
        $screenshots = $db->fetchAll(
            'SELECT * FROM project_screenshots WHERE project_id = ? ORDER BY sort_order',
            [$project['id']]
        );
        foreach ($screenshots as &$screenshot) {
            unset($screenshot['id'], $screenshot['project_id'], $screenshot['created_at']);
        }
        $project['screenshots'] = $screenshots;

        unset($project['id']);
    }
    file_put_contents(
        $dataPath . '/projects.json',
        json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
    );
    echo "✓ Exported " . count($projects) . " projects\n";

    echo "\n✅ Export completed successfully!\n";
    echo "Files written to: {$dataPath}\n";

} catch (\Throwable $e) {
    echo "\n❌ Export failed: " . $e->getMessage() . "\n";
    exit(1);
}