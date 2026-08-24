<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS Menu;

            CREATE PROCEDURE Menu(
                IN access_type ENUM('All', 'Excluded', 'Selected', 'None'),
                IN menu_type VARCHAR(50),
                IN role_id INT(9),
                IN user_id BIGINT(20),
                IN menu_status VARCHAR(50)
            )
            BEGIN

                DECLARE menuItem VARCHAR(20);
                DECLARE menuCondition TEXT DEFAULT '';

                WHILE LOCATE(',', menu_type) > 0 DO

                    SET menuItem = SUBSTRING_INDEX(menu_type, ',', 1);

                    SET menuCondition = CONCAT(
                        menuCondition,
                        IF(menuCondition = '', '', ' OR '),
                        'FIND_IN_SET(''', menuItem, ''', module_action.menu_type)'
                    );

                    SET menu_type = SUBSTRING(
                        menu_type,
                        LOCATE(',', menu_type) + 1
                    );

                END WHILE;
                
                SET menuCondition = CONCAT(
                    menuCondition,
                    IF(menuCondition = '', '', ' OR '),
                    'FIND_IN_SET(''', menu_type, ''', module_action.menu_type)'
                );
                
                SET @menu_list = REPLACE(menu_status, ',', ''',''');
                IF (access_type = 'All') THEN
                    SET @sql = CONCAT(
                        'SELECT * FROM module_action ',
                        'WHERE (', menuCondition, ')',
                        'AND status = \"1\" ',
                        'AND menu_status IN (''', @menu_list, ''') ',
                        'AND id IN (',
                            'SELECT action_id FROM role_action WHERE role_id = ', role_id, ' ',
                            'UNION ',
                            'SELECT action_id FROM right_action WHERE user_id = ', user_id, ' ',
                            'AND role_id = ', role_id,
                        ') ',
                        'ORDER BY menu_sequence'
                    );
                ELSE
                    SET @sql = CONCAT(
                        'SELECT * FROM module_action ',
                        'WHERE (', menuCondition, ')',
                        'AND status = \"1\" ',
                        'AND menu_status IN (''', @menu_list, ''') ',
                        'AND id IN (',
                            'SELECT action_id FROM role_action WHERE role_id = ', role_id, ' ',
                            'UNION ',
                            'SELECT action_id FROM right_action WHERE user_id = ', user_id, ' ',
                            'AND role_id = ', role_id,
                        ') ',
                        'ORDER BY menu_sequence'
                    );
                END IF;

                PREPARE stmt FROM @sql;
                EXECUTE stmt;
                DEALLOCATE PREPARE stmt;
            END
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS `Menu`;");
    }
};
