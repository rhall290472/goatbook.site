<?php
/*
!==============================================================================!
!\                                                                            /!
!\\                                                                          //!
! \##########################################################################/ !
!  #         This is Proprietary Software of Richard Hall                   #  !
!  ##########################################################################  !
!  ##########################################################################  !
!  #                                                                        #  !
!  #                                                                        #  !
!  #   Copyright 2024 - Richard Hall                                        #  !
!  #                                                                        #  !
!  #   The information contained herein is the property of Richard          #  !
!  #   Hall, and shall not be copied, in whole or in part, or               #  !
!  #   disclosed to others in any manner without the express written        #  !
!  #   authorization of Richard Hall.                                       #  !
!  #                                                                        #  !
! /##########################################################################\ !
!//                                                                          \\!
!/                                                                            \!
!==============================================================================!
*/

/**
 * Singleton class for managing database connections and campsite-related operations.
 *
 * This class implements the Singleton pattern to ensure a single instance is used
 * throughout the application. It provides methods for database connectivity, querying,
 * and managing campsite data such as areas, activities, and reviews.
 *
 * @author Richard Hall
 * @copyright 2024 Richard Hall
 * @package cGOAT
 */
class cGOAT
{
  /**
   * Stores instances of Singleton subclasses.
   *
   * @var array
   */
  private static $instances = [];

  /**
   * Stores the year (currently unused).
   *
   * @var string|null
   */
  private static $year;

  /**
   * Database connection instance.
   *
   * @var mysqli|null
   */
  private $dbConn;

  /**
   * Private constructor to prevent direct instantiation.
   */
  protected function __construct() {}

  /**
   * Prevents cloning of the Singleton instance.
   */
  protected function __clone() {}

  /**
   * Prevents unserializing of the Singleton instance.
   *
   * @throws Exception
   */
  public function __wakeup()
  {
    throw new \Exception("Cannot unserialize a singleton.");
  }

  /**
   * Retrieves database configuration based on client IP.
   *
   * Determines if the request is from localhost or a remote server and returns
   * appropriate database connection credentials.
   *
   * @return array Database configuration array with keys: dbhost, dbuser, dbpass, db
   */
  public static function getConfigData()
  {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    $userdata = [];

    if (!strcmp($ip, "::1") || !strcmp($ip, "127.0.0.1")) {
      $userdata['dbhost'] = "localhost";
      $userdata['dbuser'] = "root";
      $userdata['dbpass'] = "";
      $userdata['db'] = "goat";
    } else {
      $userdata['dbhost'] = "rhall29047217205.ipagemysql.com";
      $userdata['dbuser'] = "webuser1";
      $userdata['dbpass'] = "webuser1";
      $userdata['db'] = "goat";
    }

    return $userdata;
  }

  /**
   * Gets the Singleton instance of the class.
   *
   * Ensures only one instance of the class or its subclasses exists.
   *
   * @return cGOAT The Singleton instance
   */
  public static function getInstance()
  {
    $cls = static::class;
    if (!isset(self::$instances[$cls])) {
      self::$instances[$cls] = new static();
    }

    return self::$instances[$cls];
  }

  /**
   * Initializes a MySQLi database connection.
   *
   * @return cGOAT Instance with initialized database connection
   */
  private static function initConnection()
  {
    $db = self::getInstance();
    $connConf = self::getConfigData();
    $db->dbConn = new mysqli($connConf['dbhost'], $connConf['dbuser'], $connConf['dbpass'], $connConf['db']);
    $db->dbConn->set_charset('utf8');
    return $db;
  }

  /**
   * Retrieves a MySQLi database connection.
   *
   * @return mysqli The database connection object
   * @throws Exception If connection fails
   */
  public static function getDbConn()
  {
    $db = new mysqli;
    try {
      $db = self::initConnection();
      return $db->dbConn;
    } catch (Exception $ex) {
      $strError = "Unable to open a connection to the database. " . $ex->getMessage() . __FILE__ . " " . __LINE__;
      error_log($strError, 0);
    }
    return $db;
  }

  /**
   * Retrieves a PDO database connection.
   *
   * @return PDO The PDO connection object
   * @throws PDOException If connection fails
   */
  public static function getPDOConn()
  {
    $connConf = self::getConfigData();
    try {
      $pdo = new PDO(
        'mysql:host=' . $connConf['dbhost'] . ';dbname=' . $connConf['db'] . ';charset=utf8',
        $connConf['dbuser'],
        $connConf['dbpass']
      );
    } catch (PDOException $exception) {
      $strError = "Failed to connect to database! " . $exception->getMessage() . __FILE__ . " " . __LINE__;
      error_log($strError, 0);
      exit('Failed to connect to database!');
    }
    return $pdo;
  }

  /**
   * Executes a MySQLi query with prepared statements.
   *
   * @param string $sql The SQL query with placeholders
   * @param array $params Array of parameters to bind (optional)
   * @return mysqli_result|null The query result or null on failure
   */
  public static function &doQuery($sql, $params = [])
  {
    $Result = null;
    try {
      $mysqli = self::getDbConn();
      $stmt = $mysqli->prepare($sql);
      if ($stmt === false) {
        $strError = "Prepare failed: " . $mysqli->error . " in query: $sql";
        error_log($strError, 0);
        return $Result;
      }

      if (!empty($params)) {
        $types = str_repeat('s', count($params)); // Assume all params are strings for simplicity
        $stmt->bind_param($types, ...$params);
      }

      $stmt->execute();
      $Result = $stmt->get_result() ?: true; // For queries like INSERT/UPDATE, return true
      $stmt->close();
    } catch (Exception $ex) {
      $strError = "Unable to execute query. " . $ex->getMessage() . __FILE__ . " " . __LINE__;
      error_log($strError, 0);
      $Result = null;
    }
    return $Result;
  }

  /**
   * Displays a JavaScript alert message.
   *
   * @param string $msg The message to display
   */
  public static function function_alert($msg)
  {
    echo "<script type='text/javascript'>alert('$msg');</script>";
  }

  /**
   * Returns the left portion of a string.
   *
   * @param string $str The input string
   * @param int $length Number of characters to return
   * @return string The substring
   */
  public static function left($str, $length)
  {
    return substr($str, 0, $length);
  }

  /**
   * Returns a portion of a string from a starting position.
   *
   * @param string $str The input string
   * @param int $start Starting position
   * @param int $length Number of characters to return
   * @return string The substring
   */
  public static function mid($str, $start, $length)
  {
    return substr($str, $start, $length);
  }

  /**
   * Returns the right portion of a string.
   *
   * @param string $str The input string
   * @param int $length Number of characters to return
   * @return string The substring
   */
  public static function right($str, $length)
  {
    return substr($str, -$length);
  }

  /**
   * Redirects to a specified URL using JavaScript.
   *
   * Note: This method is marked for removal.
   *
   * @param string $url The URL to redirect to
   */
  public static function GotoURL($url)
  {
    $strError = "Need to remove this function!!. " . __FILE__ . " " . __LINE__;
    error_log($strError, 0);
    echo "<script>location.replace('$url')</script>";
  }

  /**
   * Retrieves and sanitizes form data from POST request.
   *
   * Returns "0" for checkboxes if not set.
   *
   * @param string $data The form field name
   * @return string The sanitized form data
   */
  public static function &GetFormData($data)
  {
    if (isset($_POST[$data])) {
      $return = addslashes($_POST[$data]);
    } else {
      $return = "0";
    }
    return $return;
  }

  /**
   * Displays a dropdown of areas from the database.
   *
   * @param int $areaIDX The selected area index
   * @param string $element_name The name of the select element
   */
  public static function DisplayArea($areaIDX, $element_name)
  {
    $sql = "SELECT * FROM area ORDER BY `name` ASC";
    $result_area = self::doQuery($sql);

    echo "<select class='form-control' name='$element_name' >";
    echo '<option value="0"> </option>';
    while ($rowArea = $result_area->fetch_assoc()) {
      $strSelected = ($rowArea['IDX'] == $areaIDX) ? "selected" : "";
      echo sprintf("<option %s value=" . $rowArea['IDX'] . ">" . $rowArea['name'] . "</option>", $strSelected);
    }
    echo "</select>";
  }

  /**
   * Displays a dropdown of activity types from the database.
   *
   * @param int $areaIDX The selected activity type index
   * @param string $element_name The name of the select element
   */
  public static function DisplayActivityType($areaIDX, $element_name)
  {
    $sql = "SELECT * FROM type ORDER BY `activity_type` ASC";
    $result_type = self::doQuery($sql);

    echo "<select class='form-control' name='$element_name' >";
    echo "<option value=\"\" </option>";
    while ($rowType = $result_type->fetch_assoc()) {
      $strSelected = ($rowType['IDX'] == $areaIDX) ? "selected" : "";
      echo sprintf("<option %s value=" . $rowType['IDX'] . ">" . $rowType['activity_type']  . "</option>", $strSelected);
    }
    echo "</select>";
  }

  /**
   * Displays a form for selecting campsite sort options.
   *
   * Creates dropdowns for sorting by area and activity type.
   */
  public static function SelectCampSite()
  {
    echo "<h2>Select Sort Option</h2>";

    $sql = "SELECT * FROM `area` ORDER BY `name` ASC";
    $ResultArea = self::doQuery($sql);
    if (!$ResultArea) {
      $strErr = "ERROR: SelectArea() - " . $sql . " @  " . __FILE__ . ", " . __LINE__;
      error_log($strErr);
      self::function_alert("ERROR: SelectArea()");
      exit();
    }

    $sql = "SELECT * FROM `type` ORDER BY `activity_type` ASC";
    $ResultType = self::doQuery($sql);
    if (!$ResultType) {
      $strErr = "ERROR: SelectArea() - " . $sql . " @  " . __FILE__ . ", " . __LINE__;
      error_log($strErr);
      self::function_alert("ERROR: SelectArea()");
      exit();
    }

?>
    <form method=post>
      <div class="form-row px-3">
        <div class="col-auto">
          <label for='Unit'>&nbsp;</label>
          <select class='form-control' id='Area' name='Area'>
            <option value="0"> </option>
            <?php
            while ($rowArea = $ResultArea->fetch_assoc()) {
              echo "<option value=" . $rowArea['IDX'] .  ">" . $rowArea['name']  . "</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-auto py-45">
          <input class='btn btn-primary btn-sm' type='submit' name='SubmitArea' placeholder='Area' value='Sort By Area' />
        </div>
        <div class="col-auto">
          <label for='Unit'>&nbsp;</label>
          <select class='form-control' id='Type' name='Type'>
            <option value="0"> </option>
            <?php
            while ($rowType = $ResultType->fetch_assoc()) {
              echo "<option value=" . $rowType['IDX'] .  ">" . $rowType['activity_type']  . "</option>";
            }
            ?>
          </select>
        </div>
        <div class="col-auto py-45">
          <input class='btn btn-primary btn-sm' type='submit' name='SubmitActivityType' placeholder='Type' value='Sort By Activity Type' />
        </div>
      </div>
    </form>
<?php
  }

  /**
   * Inserts a new campsite into the database.
   *
   * @param array $FormData Array containing site details:
   *                        area, name, type1, type2, map, embedmap, facilities, directions
   * @return mysqli_result|null The query result or null on failure
   */
  public static function InsertSite($FormData)
  {
    $sql = "INSERT INTO `site` (`area`, `type1`, `type2`, `name`, `facilities`, `map`, `embedmap`, `directions`) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $params = [
      $FormData['area'],
      $FormData['type1'],
      $FormData['type2'],
      $FormData['name'],
      $FormData['facilities'],
      $FormData['map'],
      $FormData['embedmap'],
      $FormData['directions']
    ];
    return self::doQuery($sql, $params);
  }

  /**
   * Inserts a new activity type into the database.
   *
   * @param array $FormData Array containing the activity type (type2)
   * @return mysqli_result|null The query result or null on failure
   */
  public static function InsertActity($FormData)
  {
    $sql = "INSERT INTO `type` (`activity_type`) VALUES (?)";
    return self::doQuery($sql, [$FormData['type2']]);
  }

  /**
   * Updates an existing campsite in the database.
   *
   * @param array $Site Array containing site details: IDX, area, name, type1, type2, map, facilities, IsDeleted, embedmap, directions
   * @return mysqli_result|null The query result or null on failure
   */
  public static function UpdateSite($Site)
  {
    $sql = "UPDATE `site` SET `area`=?, `name`=?, `type1`=?, `type2`=?, `map`=?, `facilities`=?, `IsDeleted`=?, `embedmap`=?, `directions`=?, `edited_by`=? WHERE `IDX`=?";
    $params = [
      $Site['area'],
      $Site['name'],
      $Site['type1'],
      $Site['type2'],
      $Site['map'],
      $Site['facilities'],
      $Site['IsDeleted'],
      $Site['embedmap'],
      $Site['directions'],
      $_SESSION['username'] ?? 'unknown',
      $Site['IDX']
    ];
    return self::doQuery($sql, $params);
  }

  /**
   * Creates an audit trail for changes to a campsite.
   *
   * Logs changes to the site_audit_trail table when differences are found between old and new data.
   *
   * @param array $Old The original site data
   * @param array $New The updated site data
   * @return mysqli_result|null The query result or null on failure
   */
  public static function CreateAudit($Old, $New)
  {
    $Index = 0;
    $Indexid = 'IDX';
    $PrimaryKey = 'IDX';
    $db = 'site_audit_trail';
    $Result = null;

    foreach ($New as $key => $value) {
      if ($Index == 0) {
        if ($Old[$key] != $New[$key]) {
          $strError = "ERROR: Comparing different sites in audit trail. " . __FILE__ . " " . __LINE__;
          error_log($strError, 0);
        }
      } else if ($Old[$key] != $New[$key]) {
        $sql = "INSERT INTO `$db` (`$PrimaryKey`, `column_name`, `old_value`, `new_value`, `done_by`) VALUES (?, ?, ?, ?, ?)";
        $params = [
          $New[$Indexid],
          $key,
          $Old[$key],
          $New[$key],
          $_SESSION['username'] ?? 'unknown'
        ];
        $Result = self::doQuery($sql, $params);
      }
      $Index++;
    }
    return $Result;
  }

  /**
   * Retrieves the name of an area by its index.
   *
   * @param int|null $IDX The area index
   * @return string The area name or error message if not found
   */
  public static function GetAreaText($IDX)
  {
    $strRtn = "Area Not Found";
    if (isset($IDX)) {
      $sql = "SELECT * FROM `area` WHERE IDX = ?";
      $Results = self::doQuery($sql, [$IDX]);
      if ($Results) {
        $row = $Results->fetch_assoc();
        if ($row) {
          $strRtn = $row['name'];
        } else {
          $StrErr = "ERROR: Bad Area Index value -" . $IDX . " @  " . __FILE__ . ", " . __LINE__;
          error_log($StrErr);
          $strRtn = "Bad Area Index";
        }
      }
    }
    return $strRtn;
  }

  /**
   * Retrieves the activity type name by its index.
   *
   * @param int $IDX The activity type index
   * @return string The activity type name or empty string if not found
   */
  public static function GetActivityText($IDX)
  {
    $sql = "SELECT * FROM `type` WHERE IDX = ?";
    $Results = self::doQuery($sql, [$IDX]);
    if ($Results) {
      $row = $Results->fetch_assoc();
      if ($row) {
        return $row['activity_type'];
      }
    }
    return "";
  }

  /**
   * Calculates the average rating for a campsite.
   *
   * @param int $IDX The campsite index
   * @return float The average rating formatted to two decimal places
   */
  public static function GetRating($IDX)
  {
    $nRtn = 0;
    $sql = "SELECT AVG(`rating`) FROM `reviews` WHERE `site_idx` = ?";
    $Results = self::doQuery($sql, [$IDX]);
    if ($Results) {
      $row = $Results->fetch_assoc();
      if ($row) {
        $nRtn = number_format((float)$row['AVG(`rating`)'], 2, '.', '');
      }
    }
    return $nRtn;
  }

  /**
   * Retrieves the date of the most recent review for a campsite.
   *
   * @param int $IDX The campsite index
   * @return string The formatted date (m-d-Y) or empty string if no reviews
   */
  public static function GetLastReviewd($IDX)
  {
    $strRtn = "";
    $sql = "SELECT submit_date FROM reviews WHERE site_idx = ? AND submit_date = (SELECT MAX(submit_date)) ORDER BY submit_date ASC";
    $Results = self::doQuery($sql, [$IDX]);
    if ($Results) {
      $row = $Results->fetch_assoc();
      if ($row) {
        $date = new DateTime($row['submit_date']);
        $strRtn = $date->format("m-d-Y");
      }
    }
    return $strRtn;
  }

  /**
   * Retrieves the average skill level for a campsite.
   *
   * Maps the average skill level to a descriptive string from the skill_level table.
   *
   * @param int $IDX The campsite index
   * @return string The skill level description or 0 if not found
   */
  public static function GetSkillLevel($IDX)
  {
    $StrRtn = 0;
    $sql = "SELECT AVG(`skill_level`) FROM reviews WHERE site_idx = ?";
    $Results = self::doQuery($sql, [$IDX]);
    if ($Results) {
      $row = $Results->fetch_assoc();
      if ($row) {
        $Rtn = number_format((float)$row['AVG(`skill_level`)'], 0, '.', '');
        $sql = "SELECT skill FROM skill_level WHERE IDX = ?";
        $Results = self::doQuery($sql, [$Rtn]);
        if ($Results) {
          $row = $Results->fetch_assoc();
          if ($row) {
            $StrRtn = $row['skill'];
          }
        }
      }
    }
    return $StrRtn;
  }

  /**
   * Checks if a campsite has additional information.
   *
   * @param string $map The map data
   * @return string Checkmark if map data exists, empty string otherwise
   */
  public static function HasInfo($map)
  {
    if (!empty($map)) {
      return "&#x2714";
    }
    return "";
  }

  /**
   * Checks if a campsite has an embedded map.
   *
   * @param string $embedmap The embedded map data
   * @return string Checkmark if embedmap data exists, empty string otherwise
   */
  public static function HasMap($embedmap)
  {
    if (!empty($embedmap)) {
      return "&#x2714";
    }
    return "";
  }
}
