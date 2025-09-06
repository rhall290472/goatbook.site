<?php
if (!session_id()) {
  session_start();
}
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
!  #                                                                        #  !
! /##########################################################################\ !
!//                                                                          \\!
!/                                                                            \!
!==============================================================================!
*/



/******************************************************************************
 * 
 * 
 * 
 *****************************************************************************/
/**
 * The Singleton class defines the `GetInstance` method that serves as an
 * alternative to constructor and lets clients access the same instance of this
 * class over and over.
 */
class cGOAT
{
  /**
   * The Singleton's instance is stored in a static field. This field is an
   * array, because we'll allow our Singleton to have subclasses. Each item in
   * this array will be an instance of a specific Singleton's subclass. You'll
   * see how this works in a moment.
   */
  private static $instances = [];
  private static $year;

  /**
   * The Singleton's constructor should always be private to prevent direct
   * construction calls with the `new` operator.
   */
  protected function __construct() {}

  /**
   * Singletons should not be cloneable.
   */
  protected function __clone() {}

  /**
   * Singletons should not be restorable from strings.
   */
  public function __wakeup()
  {
    throw new \Exception("Cannot unserialize a singleton.");
  }

  /******************************************************************************
   * 
   * 
   * 
   *****************************************************************************/
  public static function getConfigData()
  {

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
      //ip from share internet
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
      //ip pass from proxy
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
      $ip = $_SERVER['REMOTE_ADDR'];
    }

    $userdata  = array();

    if (!strcmp($ip, "::1")) {
      $userdata['dbhost'] = "localhost";
      $userdata['dbuser'] = "root";
      $userdata['dbpass'] = "";
      $userdata['db']     = "goat";
    }
    //        else if( (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)){
    //            $userdata['dbhost'] = "rhall29047217205.ipagemysql.com";
    //            $userdata['dbuser'] = "rhall29047217205";
    //            $userdata['dbpass'] = "w3frRWX^&q";
    //            $userdata['db']     = "goat";
    //        }
    else {
      $userdata['dbhost'] = "rhall29047217205.ipagemysql.com";
      $userdata['dbuser'] = "webuser1";
      $userdata['dbpass'] = "webuser1";
      $userdata['db']     = "goat";
    }

    return $userdata;
  }

  /**
   * This is the static method that controls the access to the singleton
   * instance. On the first run, it creates a singleton object and places it
   * into the static field. On subsequent runs, it returns the client existing
   * object stored in the static field.
   *
   * This implementation lets you subclass the Singleton class while keeping
   * just one instance of each subclass around.
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
   *
   * @return DbConn
   */
  private static function initConnection()
  {
    $db = self::getInstance();
    $connConf = self::getConfigData();
    $db->dbConn = new mysqli($connConf['dbhost'], $connConf['dbuser'], $connConf['dbpass'], $connConf['db']);
    //        $str = sprintf("Log in information: Host: %s, User: %s, Password: %s, Database: %s",
    //          $connConf['dbhost'], $connConf['dbuser'], $connConf['dbpass'], $connConf['db']);
    //        error_log($str);
    $db->dbConn->set_charset('utf8');
    return $db;
  }

  /**
   * @return mysqli
   */
  public static function getDbConn()
  {
    $db = new mysqli;
    try {
      $db = self::initConnection();
      return $db->dbConn;
    } catch (Exception $ex) {
      $strError = "I was unable to open a connection to the database. " . $ex->getMessage();
      error_log($strError, 0);
      return $db;
    }
  }
  public static function getPDOConn()
  {
    $pdo = self::getInstance();
    $connConf = self::getConfigData();
    try {
      $pdo = new PDO('mysql:host=' . $connConf['dbhost'] . ';dbname=' . $connConf['db'] . ';charset=utf8', $connConf['dbuser'], $connConf['dbpass']);
    } catch (PDOException $exception) {
      // If there is an error with the connection, stop the script and display the error.
      exit('Failed to connect to database!');
    }
    return $pdo;
  }
  /**************************************************************************
   **
   ** doQuery()
   ** Excutes a mysqli_query
   **
   *************************************************************************/
  public static function &doQuery($sql)
  {
    $Result = null;
    try {
      $mysqli = self::getDbConn();
      $Result = $mysqli->query($sql);
      if (!$Result) {
        $strError = $mysqli->error;
        error_log($strError, 0);
      }
    } catch (Exception $ex) {
      $strError = "I was unable to execute query. " . $ex->getMessage();
      error_log($strError, 0);
      $Result = null;
    }
    return $Result;
  }
  /**************************************************************************
   **
   **
   **
   *************************************************************************/
  public static function function_alert($msg)
  {
    echo "<script type='text/javascript'>alert('$msg');</script>";
  }
  /*=============================================================================
     *
     * This function will return left length of a string
     * 
     *===========================================================================*/
  public static function left($str, $length)
  {
    return substr($str, 0, $length);
  }
  /*=============================================================================
     *
     * This function will return mid length of a string
     * 
     *===========================================================================*/
  public static function mid($str, $start, $length)
  {
    return substr($str, $start, $length);
  }
  /*=============================================================================
     *
     * This function will return right length of a string
     * 
     *===========================================================================*/
  public static function right($str, $length)
  {
    return substr($str, -$length);
  }
  /******************************************************************************
   **
   *****************************************************************************/
  public static function GotoURL($url)
  {
    echo "<script>location.replace('$url')</script>";
  }
  /*=============================================================================
    **
    ** GetFormData() - This function will get the data from the user form.
    **  parameter $data - the id to the posted value.
    ** 
    **===========================================================================*/
  public static function &GetFormData($data)
  {
    if (isset($_POST[$data])) {
      $return = addslashes($_POST[$data]);
    } else {
      // This is needed for check boxes, if box is not checked will not
      // Post a value.
      $return = "0";
    }
    return $return;
  }
  /**************************************************************************
   **
   ** DisplayArea()
   **  parameter - $areaIDX - an index to the area table
   **              $element_name- name for control in the form.
   **
   ** This function will display a select dropdown filled in with the areas
   ** from the database table area. If passed a index, the default value of
   ** the selection with be set.
   **
   *************************************************************************/
  public static function DisplayArea($areaIDX, $element_name)
  {
    // Create a sql statement to get the area names from data table
    $sql = "SELECT * from area ORDER BY `name` ASC";
    $result_area = self::doQuery($sql);

    echo "<select class='form-control' name='$element_name' >";
    echo '<option value="0"> </option>';
    while ($rowArea = $result_area->fetch_assoc()) {
      $strSelected = ($rowArea['IDX'] == $areaIDX) ? "selected" : "";
      echo sprintf("<option %s value=" . $rowArea['IDX'] . ">" . $rowArea['name'] . "</option>", $strSelected);
    }
    echo "</select>";
  }
  /******************************************************************************
   **
   ** DispayActivityType()
   **  parameter - $typeIDX - Index to data table
   **              $element_name - name for control in the form.
   **
   ** This function will display a select dropdown filled in with the activity
   ** typs from the database table type. If passed a index, the default value of
   ** the selection with be set.
   **
   ** 
   *****************************************************************************/
  public static function DisplayActivityType($areaIDX, $element_name)
  {
    // Create a sql statement to get the area names from data table
    $sql = "SELECT * from type ORDER BY `activity_type` ASC";
    $result_type = self::doQuery($sql);

    echo "<select class='form-control' name='$element_name' >";
    echo "<option value=\"\" </option>";
    while ($rowType = $result_type->fetch_assoc()) {
      $strSelected = ($rowType['IDX'] == $areaIDX) ? "selected" : "";
      echo sprintf("<option %s value=" . $rowType['IDX'] . ">" . $rowType['activity_type']  . "</option>", $strSelected);
    }
    echo "</select>";
  }
  /*=============================================================================
     *
     * This function will allow the user to select the area to view campsites
     * 
     *===========================================================================*/
  public static function SelectCampSite()
  {
    echo "<h2>Select Sort Option</h2>";

    // Get the areas from the database for the Sort By Area dropdown box
    $sql = "SELECT * FROM `area` ORDER BY `name` ASC";
    $ResultArea = self::doQuery($sql);
    if (!$ResultArea) {
      $strErr = "ERROR: SelectArea() - " . $sql . " @  " . __FILE__ . ", " . __LINE__;
      error_log($strErr);
      self::function_alert("ERROR: SelectArea()");
      exit();
    }

    // Get the areas from the database for the Sort By Activity Type dropdown box
    $sql = "SELECT * FROM `type` ORDER BY `activity_type` ASC";
    $ResultType = self::doQuery($sql);
    if (!$ResultType) {
      $strErr = "ERROR: SelectArea() - " . $sql . " @  " . __FILE__ . ", " . __LINE__;
      error_log($strErr);
      self::function_alert("ERROR: SelectArea()");
      exit();
    }


    // Fill up the drop down with merit badge names
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
        <div class="col-auto py-4">
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
        <div class="col-auto py-4">
          <input class='btn btn-primary btn-sm' type='submit' name='SubmitActivityType' placeholder='Type' value='Sort By Activity Type' />
        </div>
      </div>
      </div>
    </form>
<?php
  }

  /******************************************************************************
   **
   ** InsertSite() - This function will add a new camp site to the database table
   **  sites
   **
   ** patameter $FormData[]
   **  $FormData['area']
   **  $FormData['name']
   **  $FormData['type1']
   **  $FormData['type2']
   **  $FormData['map']
   **  $FormData['facilities']
   **  $FormData['directions']
   **
   *****************************************************************************/
  public static function InsertSite($FormData)
  {

    $sql = "INSERT INTO `site`(`area`, `type1`, `type2`, `name`, `facilities`, 
        `map`, `embedmap`, `directions`) 
        VALUES ('$FormData[area]', '$FormData[type1]', '$FormData[type2]', '$FormData[name]', '$FormData[facilities]', 
        '$FormData[map]',  '$FormData[embedmap]', '$FormData[directions]')";
    $Results = self::doQuery($sql);
    return $Results;
  }
  /******************************************************************************
   **
   ** InsertActity() - This function will add a new activity to the database 
   **                  table
   **
   ******************************************************************************/
  public static function InsertActity($FormData)
  {

    $sql = "INSERT INTO `type`(`activity_type`) VALUES ('$FormData[type2]')";
    $Results = self::doQuery($sql);
    return $Results;
  }
  /******************************************************************************
   **
   *****************************************************************************/
  public static function UpdateSite($Site)
  {

    $sqlStmt = "UPDATE `site` SET `area`='$Site[area]',`name`='$Site[name]', `type1`='$Site[type1]',`type2`='$Site[type2]',
            `map`='$Site[map]',`facilities`='$Site[facilities]',`IsDeleted`='$Site[IsDeleted]',`embedmap`='$Site[embedmap]',
            `directions`='$Site[directions]',
            `edited_by`='$_SESSION[username]' 
            WHERE `IDX`='$Site[IDX]'";

    // Excute the sql Statement
    $Result = self::doQuery($sqlStmt);
    return $Result;
  }
  /*****************************************************************************
   *
   * We have to create an audit trail this way beacuse iPage does not support
   * the use of triggers in the database.
   *
   *****************************************************************************/
  public static function CreateAudit($Old, $New)
  {
    $Index = 0;
    $Indexid = 'IDX';
    //    if ($IdKey == 'IDX') {
    $PrimaryKey = 'IDX';
    $db = 'site_audit_trail';
    //    } 
    //    else {
    //      $PrimaryKey = 'Scoutid';
    //      $db = 'scouts_audit_trail';
    //    }
    foreach ($New as $key => $value) {
      // Don't audit the coachesid value
      if ($Index == 0) {
        if ($Old[$key] != $New[$key]) {
          //ERROR NOT COMPARE SAME SITE
        }
        //        $Indexid = $key;
        //        $Index++;
        //        continue;
      } else if ($Old[$key] != $New[$key]) {
        $sqlStmt = "INSERT INTO `$db`(`$PrimaryKey`, `column_name`, `old_value`, `new_value`, `done_by`)
                     VALUES ('$New[$Indexid]','$key','$Old[$key]','$New[$key]','$_SESSION[username]')";
        //Excute the sql Statement
        $Result = self::doQuery($sqlStmt);
      }
      $Index++;
    }
    return $Result;
  }

  /******************************************************************************
   **
   *****************************************************************************/
  public static function GetAreaText($IDX)
  {
    $strRtn = "Area Not Found";
    if (isset($IDX)) {
      $sql = "SELECT * from `area` WHERE IDX = '$IDX'";
      $Results = self::doQuery($sql);
      if ($Results) {
        // Should only havea  single row
        $row = $Results->fetch_assoc();
        if ($row)
          $strRtn = $row['name'];
        else {
          $StrErr = "ERROR: Bad Area Index value -" . $IDX . " @  " . __FILE__ . ", " . __LINE__;
          error_log($StrErr);
          $strRtn = "Bad Area Index";
        }
      }
    }

    return $strRtn;
  }
  /******************************************************************************
   **
   *****************************************************************************/
  public static function GetActivityText($IDX)
  {
    $sql = "SELECT * from `type` WHERE IDX = '$IDX'";
    $Results = self::doQuery($sql);
    // Should only havea  single row
    if ($Results) {
      $row = $Results->fetch_assoc();
      if ($row)
        return $row['activity_type'];
      else
        return "";
    } else
      return "";
  }
  /******************************************************************************
   **
   *****************************************************************************/
  public static function GetRating($IDX)
  {
    $nRtn = 0;

    $sql = "SELECT AVG(`rating`) FROM `reviews` WHERE `site_idx`='" . $IDX . "'";
    $Results = self::doQuery($sql);
    if ($Results) {
      $row = $Results->fetch_assoc();
      if ($row)
        $nRtn = number_format((float)$row['AVG(`rating`)'], 2, '.', '');
    }
    return $nRtn;
  }
  /******************************************************************************
   **
   *****************************************************************************/
  public static function GetLastReviewd($IDX)
  {
    $strRtn = "";

    $sql = "SELECT submit_date FROM reviews WHERE site_idx = '" . $IDX . "' AND submit_date = (SELECT MAX(submit_date)) ORDER BY submit_date ASC";
    $Results = self::doQuery($sql);
    if ($Results) {
      $row = $Results->fetch_assoc();
      if ($row) {
        $date = new DateTime($row['submit_date']);
        $strRtn = $date->format("m-d-Y");
      }
    }
    return $strRtn;
  }
  /******************************************************************************
   **
   *****************************************************************************/
  public static function GetSkillLevel($IDX)
  {
    $StrRtn = 0;

    $sql = "SELECT AVG(`skill_level`) FROM reviews WHERE site_idx = '" . $IDX . "'";
    $Results = self::doQuery($sql);
    if ($Results) {
      $row = $Results->fetch_assoc();
      if ($row) {
        $Rtn = number_format((float)$row['AVG(`skill_level`)'], 0, '.', '');
        $sql = "SELECT skill FROM skill_level WHERE IDX='" . $Rtn . "'";
        $Results = self::doQuery($sql);
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
  /******************************************************************************
   **
   *****************************************************************************/
  public static function HasInfo($map)
  {
    if (!empty($map))
      return "&#x2714";
    else
      return "";
  }
  /******************************************************************************
   **
   *****************************************************************************/
  public static function HasMap($embedmap)
  {
    if (!empty($embedmap))
      return "&#x2714";
    else
      return "";
  }
}
