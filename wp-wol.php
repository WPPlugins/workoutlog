<?php
/*
Plugin Name: wol - workoutlog - English Version
Plugin URI: http://wol.marcel-malitz.de
Description: workoutlog enables you to track your workout activities.  For example, you can record the distance, time, and nature of the activity.  In addition, you can add more activities and statistics in your templates.  All necessary database tables are added to your database when you activate this plugin.  You can manage workoutlog under Manage -> workoutlog.
Author: Marcel Malitz
Author URI: http://www.marcel-malitz.de

Copyright 2006  Marcel Malitz  (email : wol@marcel-malitz.de)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function wol_adminPage() {
	if ( isset( $_POST['wol_insert'] ) ) {
		global $user_ID, $wpdb, $table_prefix;
		$wpdb->query("INSERT INTO {$table_prefix}wol_data (userid, category, date, duration,
		distance, comment)
		VALUES ($user_ID, $_POST[wol_category], '$_POST[wol_date]', '$_POST[wol_duration]', 
		'$_POST[wol_distance]', '$_POST[wol_comment]')");
	}
	if ( isset( $_GET['wol_delwoid'] ) ) {
		global $user_ID, $wpdb, $table_prefix;
		$wpdb->query("DELETE FROM {$table_prefix}wol_data WHERE id=$_GET[wol_delwoid] AND userid=$user_ID");
	}
	if ( isset( $_POST['wol_delcat'] ) ) {
		global $wpdb, $user_level, $table_prefix;
		if ( $user_level >= 2 ) {
			$wpdb->query("DELETE FROM {$table_prefix}wol_category WHERE id=$_POST[wol_delcatid]");
		}
	}
	if ( isset( $_POST['wol_addcat'] ) ) {
		global $wpdb, $user_level, $table_prefix;
		if ( $user_level >= 2 ) {
			$wpdb->query("INSERT INTO {$table_prefix}wol_category (name) VALUES ('$_POST[wol_addcatname]')");
		}
	}
	if ( isset( $_GET['wol_overviewyear'] ) ) {
		global $user_ID;
		update_option('wol_overviewyear' . $user_ID, $_GET['wol_overviewyear']);
	}
	if ( isset( $_GET['wol_overviewmonth'] ) ) {
		global $user_ID;
		update_option('wol_overviewmonth' . $user_ID, $_GET['wol_overviewmonth']);
	}
?>
	<div class="wrap">
		<form method="post" action="<?php echo $_SERVER[PHP_SELF] . '?page=' . basename(__FILE__);?>">
		<h2>wol - Training Register</h2>
		<div style="float:left;">
			<div>Date (YYYY:MM:TT):</div>
			<div><input type="text" name="wol_date" value="<?php echo date("Y-m-d");?>"/></div>
		</div>
		<div style="float:left;">
			<div>Duration (HH:MM):</div>
			<div><input type="text" name="wol_duration" value="HH:MM"/></div>
		</div>
		<div style="float:left;">
			<div>Distance:</div>
			<div><input type="text" name="wol_distance"/> miles</div>
		</div>
		<div style="float:left;">
			<div>Activity:</div>
			<div><select name="wol_category" size="1">
			<?php
			global $wpdb, $table_prefix;
			$wol_categories = $wpdb->get_results("SELECT * FROM {$table_prefix}wol_category ORDER BY name ASC");
			foreach ( $wol_categories as $wol_category ) {
				echo "<option value='$wol_category->id'>$wol_category->name</option>";
			}
			?></select></div>
		</div>
		<div style="float:left;">
			<div>Comments:</div>
			<div><input type="text" name="wol_comment"/></div>
		</div>
		<div class="submit">
		<input type="submit" name="wol_insert" value="Register Training"/></div>   
		</form>
		<div style="clear:both;"> </div>
	</div>
	<div class="wrap">
		<h2>wol - Manage Activities</h2>
		<div>
		<form method="post" action="<?php echo $_SERVER[PHP_SELF] . '?page=' . basename(__FILE__);?>">
			<div>Select Activity:</div>
			<div style="float:left;">
			<select size="1" name="wol_delcatid">
			<option>-</option>
			<?php
			global $wpdb, $table_prefix;
			$wol_categories = $wpdb->get_results("SELECT * FROM {$table_prefix}wol_category ORDER BY name ASC");
			foreach ( $wol_categories as $wol_category ) {
				echo "<option value='$wol_category->id'>$wol_category->name</option>";
			}
			?>
			</select>
			</div>
			<div class="submit">
			<input type="submit" name="wol_delcat" value="Delete Activity"/></div>
		<form>
		</div>
		<div>
		<form method="post" action="<?php echo $_SERVER[PHP_SELF] . '?page=' . basename(__FILE__);?>">
			<div style="float:left;">
				<div>Register Activity</div>
				<div><input type="text" name="wol_addcatname"/></div>
			</div>
			<div class="submit">
			<input type="submit" name="wol_addcat" value="Add Activity"/></div>
		<form>
		</div>
		<div style="clear:both"></div>
	</div>
	<div class="wrap">
		<h2>wol - Month View</h2>
		<div style="clear:both;margin-bottom:10px;"><strong>Year:</strong> 
		<?php
			global $wpdb, $user_ID, $table_prefix;
			$wol_rows = $wpdb->get_results("
				SELECT YEAR(date) AS year 
				FROM {$table_prefix}wol_data WHERE userid = $user_ID 
				GROUP BY year");
			if ( $wol_rows != null ) {
				foreach ( $wol_rows as $wol_row ) {
					echo "<a href='$_SERVER[PHP_SELF]?page=" 
					. basename(__FILE__) . 
					"&wol_overviewyear=$wol_row->year' 
					title='Select Year.'>
					$wol_row->year</a> ";
				}
			}
		?>
		</div>
		<div style="clear:both;margin-bottom:10px"><strong>Month:</strong> 
			<?php
			global $wpdb, $user_ID, $table_prefix;
			$wol_rows = $wpdb->get_results("
				SELECT MONTH(date) AS month 
				FROM {$table_prefix}wol_data WHERE userid = $user_ID 
				GROUP BY month");
			if ( $wol_rows != null ) {
				foreach ( $wol_rows as $wol_row ) {
					echo "<a href='$_SERVER[PHP_SELF]?page=" 
					. basename(__FILE__) . 
					"&wol_overviewmonth=$wol_row->month' 
					title='Select Month.'>
					$wol_row->month</a> ";
				}
			}
			?>
		</div>
		<div style="clear:both"></div>
		<table>
		<tr>
			<th>Date</th>
			<th>Duration</th>
			<th>Distance</th>
			<th>Average</th>
			<th>Activity</th>
			<th>Comments</th>
			<th></th>
		</tr>
		<?php
			global $wpdb, $user_ID, $table_prefix;
			add_option('wol_overviewmonth' . $user_ID, date('n'));
			add_option('wol_overviewyear' . $user_ID, date('Y'));
			$wol_rows = $wpdb->get_results("
			SELECT {$table_prefix}wol_data.id, name, date, duration, distance, 
			comment, 
			round((distance / (TIME_TO_SEC(duration) / 3600)),2) AS average
			FROM {$table_prefix}wol_data JOIN {$table_prefix}wol_category 
			ON {$table_prefix}wol_data.category = {$table_prefix}wol_category.id 
			WHERE userid = $user_ID 
			AND MONTH(date) = " . get_option('wol_overviewmonth' . $user_ID) . 
			" AND YEAR(date) = " . get_option('wol_overviewyear' . $user_ID) .
			" ORDER BY date, {$table_prefix}wol_data.id");
			if ( $wol_rows != null ) {
				foreach ( $wol_rows as $wol_row ) { 
					echo "
					<tr>
					<td>$wol_row->date</td>
					<td>$wol_row->duration h</td>
					<td>$wol_row->distance miles</td>
					<td>$wol_row->average miles/h</td>
					<td>$wol_row->name</td>
					<td>$wol_row->comment</td>
					<td><a href='$_SERVER[PHP_SELF]?page=" 
					. basename(__FILE__) . 
					"&wol_delwoid=$wol_row->id' 
					title='Delete Entry'>
					Delete</a></td>
					</tr>";
				}
			}
		?>
		</table>
		</div>
	</div>

<?php
}

function wol_addAdminStylesheet() {
	echo '<style type="text/css">
	td, th { text-align: left; }
	table { width: 100%; }
	</style>';
}

function wol_currentYearStat() {
	global $wpdb, $table_prefix;
	$wol_rows = $wpdb->get_results("SELECT
		SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS overallduration,
		ROUND(SUM(distance),1) AS overalldistance,
		ROUND(SUM(distance) / (SUM(TIME_TO_SEC(duration)) / 3600), 2) AS overallaverage,
		name
		FROM {$table_prefix}wol_data JOIN {$table_prefix}wol_category 
		ON {$table_prefix}wol_data.category = {$table_prefix}wol_category.id 
		WHERE YEAR(date) = YEAR(NOW()) 
		GROUP BY category 
		ORDER BY name");
	if ( $wol_rows != null ) {
		echo '<li id="wol_curyearstat">';
		wol_printStat("Annual Statistics " . date('Y'), &$wol_rows);
		echo '</li>';
	}
}

function wol_printStat($head, $wol_rows) {
	echo "<h3>$head</h3>";
	foreach( $wol_rows as $wol_row ) {
		echo "<h4>$wol_row->name</h4>
			<div>Training Time: <span>$wol_row->overallduration h</span></div>
			<div>Training Distance: <span>$wol_row->overalldistance miles</span></div>
			<div>Average Speed: <span>$wol_row->overallaverage miles/h</span></div>";
	} 
}

function wol_currentWeekStat() {
	global $wpdb, $table_prefix;
	$wol_rows = $wpdb->get_results("SELECT
		SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS overallduration,
		ROUND(SUM(distance),1) AS overalldistance,
		ROUND(SUM(distance) / (SUM(TIME_TO_SEC(duration)) / 3600), 2) AS overallaverage,
		name
		FROM {$table_prefix}wol_data JOIN {$table_prefix}wol_category 
		ON {$table_prefix}wol_data.category = {$table_prefix}wol_category.id 
		WHERE WEEK(date) = WEEK(NOW()) 
		GROUP BY category 
		ORDER BY name");
	if ( $wol_rows != null ) {
		echo '<li id="wol_curweekstat">';
		wol_printStat("Current Weekly Statistics", &$wol_rows);
		echo '</li>';
	}
}

function wol_currentUserWeekStat($displayname) {
	global $wpdb, $table_prefix;
	$wol_rows = $wpdb->get_results("SELECT 
		SEC_TO_TIME(SUM(TIME_TO_SEC(duration))) AS overallduration, 
		ROUND(SUM(distance),1) AS overalldistance, 
		ROUND(SUM(distance) / (SUM(TIME_TO_SEC(duration)) / 3600), 2) AS overallaverage, 
		name 
		FROM {$table_prefix}wol_data JOIN {$table_prefix}wol_category JOIN {$table_prefix}users 
		ON {$table_prefix}wol_data.category = {$table_prefix}wol_category.id AND {$table_prefix}wol_data.userid = {$table_prefix}users.ID 
		WHERE WEEK(date) = WEEK(NOW()) 
		AND display_name = '$displayname' 
		GROUP BY category 
		ORDER BY name");
	if ( $wol_rows != null ) {
		echo '<div id="wol_curuserweekstat">';
		wol_printStat("Current Weekly Statistics for $displayname", &$wol_rows);
		echo '</div>';
	}
}

function wol_addPages() {
	add_management_page('wol - workoutlog', 'workoutlog', 2, __FILE__, 'wol_adminPage');
}

function wol_createTables() {
	global $table_prefix, $wpdb;
	$wpdb->query("
	CREATE TABLE IF NOT EXISTS `{$table_prefix}wol_category` (
	  `id` int(10) unsigned NOT NULL auto_increment,
	  `name` varchar(30)  NOT NULL default '',
	  PRIMARY KEY  (`id`)
	);");

	$wpdb->query("
	CREATE TABLE IF NOT EXISTS `{$table_prefix}wol_data` (
	  `id` int(10) unsigned NOT NULL auto_increment,
	  `userid` int(10) unsigned NOT NULL default '0',
	  `category` int(10) unsigned NOT NULL default '0',
	  `date` date NOT NULL default '0000-00-00',
	  `duration` time default NULL,
	  `distance` float unsigned default NULL,
	  `comment` varchar(255) NOT NULL default '',
	  PRIMARY KEY  (`id`)
	);");
	
}

add_action('admin_menu', 'wol_addPages');
add_action('admin_head', 'wol_addAdminStylesheet');
add_action('activate_' . basename(__FILE__),'wol_createTables');

?>
