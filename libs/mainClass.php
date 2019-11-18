<?php

declare(strict_types=1);

if (!defined('IPS_BASE')) {
  // --- BASE MESSAGE
  define('IPS_BASE',                         10000); //Kernel Base Message
  define('IPS_MODULBASE',                    20000); //Modul Base Message
  define('IPS_KERNELSTARTED',         IPS_BASE + 1); //Post Ready Message
  define('IPS_KERNELSHUTDOWN',        IPS_BASE + 2); //Pre Shutdown Message, Runlevel UNINIT Follows
}

if (!defined('IPS_KERNELMESSAGE')) {
  // --- KERNEL
  define('IPS_KERNELMESSAGE',         IPS_BASE + 100);        //Kernel Message
  define('KR_CREATE',                 IPS_KERNELMESSAGE + 1); //Kernel is beeing created
  define('KR_INIT',                   IPS_KERNELMESSAGE + 2); //Kernel Components are beeing initialised, Modules loaded, Settings read
  define('KR_READY',                  IPS_KERNELMESSAGE + 3); //Kernel is ready and running
  define('KR_UNINIT',                 IPS_KERNELMESSAGE + 4); //Got Shutdown Message, unloading all stuff
  define('KR_SHUTDOWN',               IPS_KERNELMESSAGE + 5); //Uninit Complete, Destroying Kernel Inteface
}

if (!defined('IPS_LOGMESSAGE')) {
  // --- KERNEL LOGMESSAGE
  define('IPS_LOGMESSAGE',            IPS_BASE + 200);     //Logmessage Message
  define('KL_MESSAGE',                IPS_LOGMESSAGE + 1); //Normal Message                      | FG: Black | BG: White  | STLYE : NONE
  define('KL_SUCCESS',                IPS_LOGMESSAGE + 2); //Success Message                     | FG: Black | BG: Green  | STYLE : NONE
  define('KL_NOTIFY',                 IPS_LOGMESSAGE + 3); //Notiy about Changes                 | FG: Black | BG: Blue   | STLYE : NONE
  define('KL_WARNING',                IPS_LOGMESSAGE + 4); //Warnings                            | FG: Black | BG: Yellow | STLYE : NONE
  define('KL_ERROR',                  IPS_LOGMESSAGE + 5); //Error Message                       | FG: Black | BG: Red    | STLYE : BOLD
  define('KL_DEBUG',                  IPS_LOGMESSAGE + 6); //Debug Informations + Script Results | FG: Grey  | BG: White  | STLYE : NONE
  define('KL_CUSTOM',                 IPS_LOGMESSAGE + 7); //User Message                        | FG: Black | BG: White  | STLYE : NONE
}

if (!defined('IPS_MODULEMESSAGE')) {
  // --- MODULE LOADER
  define('IPS_MODULEMESSAGE',         IPS_BASE + 300);        //Module Loader Message
  define('ML_LOAD',                   IPS_MODULEMESSAGE + 1); //Module loaded
  define('ML_UNLOAD',                 IPS_MODULEMESSAGE + 2); //Module unloaded
}

if (!defined('IPS_OBJECTMESSAGE')) {
  // --- OBJECT MANAGER
  define('IPS_OBJECTMESSAGE',         IPS_BASE + 400);         //Object Manager Message
  define('OM_REGISTER',               IPS_OBJECTMESSAGE + 1);  //Object was registered
  define('OM_UNREGISTER',             IPS_OBJECTMESSAGE + 2);  //Object was unregistered
  define('OM_CHANGEPARENT',           IPS_OBJECTMESSAGE + 3);  //Parent was Changed
  define('OM_CHANGENAME',             IPS_OBJECTMESSAGE + 4);  //Name was Changed
  define('OM_CHANGEINFO',             IPS_OBJECTMESSAGE + 5);  //Info was Changed
  define('OM_CHANGETYPE',             IPS_OBJECTMESSAGE + 6);  //Type was Changed
  define('OM_CHANGESUMMARY',          IPS_OBJECTMESSAGE + 7);  //Summary was Changed
  define('OM_CHANGEPOSITION',         IPS_OBJECTMESSAGE + 8);  //Position was Changed
  define('OM_CHANGEREADONLY',         IPS_OBJECTMESSAGE + 9);  //ReadOnly was Changed
  define('OM_CHANGEHIDDEN',           IPS_OBJECTMESSAGE + 10); //Hidden was Changed
  define('OM_CHANGEICON',             IPS_OBJECTMESSAGE + 11); //Icon was Changed
  define('OM_CHILDADDED',             IPS_OBJECTMESSAGE + 12); //Child for Object was added
  define('OM_CHILDREMOVED',           IPS_OBJECTMESSAGE + 13); //Child for Object was removed
  define('OM_CHANGEIDENT',            IPS_OBJECTMESSAGE + 14); //Ident was Changed
}

if (!defined('IPS_INSTANCEMESSAGE')) {
  // --- INSTANCE MANAGER
  define('IPS_INSTANCEMESSAGE',       IPS_BASE + 500);           //Instance Manager Message
  define('IM_CREATE',                 IPS_INSTANCEMESSAGE + 1);  //Instance created
  define('IM_DELETE',                 IPS_INSTANCEMESSAGE + 2);  //Instance deleted
  define('IM_CONNECT',                IPS_INSTANCEMESSAGE + 3);  //Instance connected
  define('IM_DISCONNECT',             IPS_INSTANCEMESSAGE + 4);  //Instance disconncted
  define('IM_CHANGESTATUS',           IPS_INSTANCEMESSAGE + 5);  //Status was Changed
  define('IM_CHANGESETTINGS',         IPS_INSTANCEMESSAGE + 6);  //Settings were Changed
  define('IM_CHANGESEARCH',           IPS_INSTANCEMESSAGE + 7);  //Searching was started/stopped
  define('IM_SEARCHUPDATE',           IPS_INSTANCEMESSAGE + 8);  //Searching found new results
  define('IM_SEARCHPROGRESS',         IPS_INSTANCEMESSAGE + 9);  //Searching progress in %
  define('IM_SEARCHCOMPLETE',         IPS_INSTANCEMESSAGE + 10); //Searching is complete
}

if (!defined('IPS_VARIABLEMESSAGE')) {
  // --- VARIABLE MANAGER
  define('IPS_VARIABLEMESSAGE',       IPS_BASE + 600);          //Variable Manager Message
  define('VM_CREATE',                 IPS_VARIABLEMESSAGE + 1); //Variable Created
  define('VM_DELETE',                 IPS_VARIABLEMESSAGE + 2); //Variable Deleted
  define('VM_UPDATE',                 IPS_VARIABLEMESSAGE + 3); //On Variable Update
  define('VM_CHANGEPROFILENAME',      IPS_VARIABLEMESSAGE + 4); //On Profile Name Change
  define('VM_CHANGEPROFILEACTION',    IPS_VARIABLEMESSAGE + 5); //On Profile Action Change
}

if (!defined('IPS_SCRIPTMESSAGE')) {
  // --- SCRIPT MANAGER
  define('IPS_SCRIPTMESSAGE',         IPS_BASE + 700);        //Script Manager Message
  define('SM_CREATE',                 IPS_SCRIPTMESSAGE + 1); //On Script Create
  define('SM_DELETE',                 IPS_SCRIPTMESSAGE + 2); //On Script Delete
  define('SM_CHANGEFILE',             IPS_SCRIPTMESSAGE + 3); //On Script File changed
  define('SM_BROKEN',                 IPS_SCRIPTMESSAGE + 4); //Script Broken Status changed
}

if (!defined('IPS_EVENTMESSAGE')) {
  // --- EVENT MANAGER
  define('IPS_EVENTMESSAGE',          IPS_BASE + 800);        //Event Scripter Message
  define('EM_CREATE',                 IPS_EVENTMESSAGE + 1);  //On Event Create
  define('EM_DELETE',                 IPS_EVENTMESSAGE + 2);  //On Event Delete
  define('EM_UPDATE',                 IPS_EVENTMESSAGE + 3);
  define('EM_CHANGEACTIVE',           IPS_EVENTMESSAGE + 4);
  define('EM_CHANGELIMIT',            IPS_EVENTMESSAGE + 5);
  define('EM_CHANGESCRIPT',           IPS_EVENTMESSAGE + 6);
  define('EM_CHANGETRIGGER',          IPS_EVENTMESSAGE + 7);
  define('EM_CHANGETRIGGERVALUE',     IPS_EVENTMESSAGE + 8);
  define('EM_CHANGETRIGGEREXECUTION', IPS_EVENTMESSAGE + 9);
  define('EM_CHANGECYCLIC',           IPS_EVENTMESSAGE + 10);
  define('EM_CHANGECYCLICDATEFROM',   IPS_EVENTMESSAGE + 11);
  define('EM_CHANGECYCLICDATETO',     IPS_EVENTMESSAGE + 12);
  define('EM_CHANGECYCLICTIMEFROM',   IPS_EVENTMESSAGE + 13);
  define('EM_CHANGECYCLICTIMETO',     IPS_EVENTMESSAGE + 14);
}

if (!defined('IPS_MEDIAMESSAGE')) {
  // --- MEDIA MANAGER
  define('IPS_MEDIAMESSAGE',          IPS_BASE + 900);       //Media Manager Message
  define('MM_CREATE',                 IPS_MEDIAMESSAGE + 1); //On Media Create
  define('MM_DELETE',                 IPS_MEDIAMESSAGE + 2); //On Media Delete
  define('MM_CHANGEFILE',             IPS_MEDIAMESSAGE + 3); //On Media File changed
  define('MM_AVAILABLE',              IPS_MEDIAMESSAGE + 4); //Media Available Status changed
  define('MM_UPDATE',                 IPS_MEDIAMESSAGE + 5);
}

if (!defined('IPS_LINKMESSAGE')) {
  // --- LINK MANAGER
  define('IPS_LINKMESSAGE',           IPS_BASE + 1000);     //Link Manager Message
  define('LM_CREATE',                 IPS_LINKMESSAGE + 1); //On Link Create
  define('LM_DELETE',                 IPS_LINKMESSAGE + 2); //On Link Delete
  define('LM_CHANGETARGET',           IPS_LINKMESSAGE + 3); //On Link TargetID change
}

if (!defined('IPS_FLOWMESSAGE')) {
  // --- DATA HANDLER
  define('IPS_FLOWMESSAGE',           IPS_BASE + 1100);     //Data Handler Message
  define('DM_CONNECT',                IPS_FLOWMESSAGE + 1); //On Instance Connect
  define('DM_DISCONNECT',             IPS_FLOWMESSAGE + 2); //On Instance Disconnect
}

if (!defined('IPS_ENGINEMESSAGE')) {
  // --- SCRIPT ENGINE
  define('IPS_ENGINEMESSAGE',         IPS_BASE + 1200);       //Script Engine Message
  define('SE_UPDATE',                 IPS_ENGINEMESSAGE + 1); //On Library Refresh
  define('SE_EXECUTE',                IPS_ENGINEMESSAGE + 2); //On Script Finished execution
  define('SE_RUNNING',                IPS_ENGINEMESSAGE + 3); //On Script Started execution
}

if (!defined('IPS_PROFILEMESSAGE')) {
  // --- PROFILE POOL
  define('IPS_PROFILEMESSAGE',        IPS_BASE + 1300);
  define('PM_CREATE',                 IPS_PROFILEMESSAGE + 1);
  define('PM_DELETE',                 IPS_PROFILEMESSAGE + 2);
  define('PM_CHANGETEXT',             IPS_PROFILEMESSAGE + 3);
  define('PM_CHANGEVALUES',           IPS_PROFILEMESSAGE + 4);
  define('PM_CHANGEDIGITS',           IPS_PROFILEMESSAGE + 5);
  define('PM_CHANGEICON',             IPS_PROFILEMESSAGE + 6);
  define('PM_ASSOCIATIONADDED',       IPS_PROFILEMESSAGE + 7);
  define('PM_ASSOCIATIONREMOVED',     IPS_PROFILEMESSAGE + 8);
  define('PM_ASSOCIATIONCHANGED',     IPS_PROFILEMESSAGE + 9);
}

if (!defined('IPS_TIMERMESSAGE')) {
  // --- TIMER POOL
  define('IPS_TIMERMESSAGE',          IPS_BASE + 1400);      //Timer Pool Message
  define('TM_REGISTER',               IPS_TIMERMESSAGE + 1);
  define('TM_UNREGISTER',             IPS_TIMERMESSAGE + 2);
  define('TM_SETINTERVAL',            IPS_TIMERMESSAGE + 3);
  define('TM_UPDATE',                 IPS_TIMERMESSAGE + 4);
  define('TM_RUNNING',                IPS_TIMERMESSAGE + 5);
}

if (!defined('IS_SBASE')) {
  // --- STATUS CODES
  define('IS_SBASE',                           100); //Status Codes
  define('IS_CREATING',               IS_SBASE + 1); //Module is being created
  define('IS_ACTIVE',                 IS_SBASE + 2); //Module created and running
  define('IS_DELETING',               IS_SBASE + 3); //Module is being deleted
  define('IS_INACTIVE',               IS_SBASE + 4); //Module is not beeing used
  define('IS_NOTCREATED',             IS_EBASE + 5); //Instance could not be created

  // --- ERROR CODES
  define('IS_EBASE',                           200); //Default Error Codes
}

if (!defined('vtBoolean')) {
  define('vtBoolean', 0);
  define('vtInteger', 1);
  define('vtFloat',   2);
  define('vtString',  3);
}

if (!defined('vtNoValue')) {
  define('vtNoValue',  -1);
  define('vtNoString', "");
  define('vtNoForm', "{}");
}


trait DebugHelper
{


  /**
  * Ergänzt SendDebug um die Möglichkeit Objekte und Array auszugeben.
  *
  * @access protected
  * @param string $Message Nachricht für Data.
  * @param WebSocketFrame|mixed $Data Daten für die Ausgabe.
  * @return int $Format Ausgabeformat für Strings.
  */
  protected function SendDebug($Message, $Data, $Format) : void {

    if (is_a($Data, "WebSocketFrame")) {
      $this->SendDebug($Message." FIN", ($Data->Fin ? "true" : "false"), 0);
      $this->SendDebug($Message." OpCode", WebSocketOPCode::ToString($Data->OpCode), 0);
      $this->SendDebug($Message." Mask", ($Data->Mask ? "true" : "false"), 0);

      if ($Data->MaskKey != "") {
        $this->SendDebug($Message." MaskKey", $Data->MaskKey, 1);
      }

      if ($Data->Payload != "") {
        $this->SendDebug($Message." Payload", $Data->Payload, ($Data->OpCode == WebSocketOPCode::text ? (int)$Data->Mask : ($Format)));
      }

      if ($Data->PayloadRAW != "") {
        $this->SendDebug($Message." PayloadRAW", $Data->PayloadRAW, ($Data->OpCode == WebSocketOPCode::text ? 0 : 1));
      }
    }
    elseif (is_object($Data)) {
      foreach ($Data as $Key => $DebugData) {
        $this->SendDebug($Message.":".$Key, $DebugData, 0);
      }
    }
    elseif (is_array($Data)) {
      foreach ($Data as $Key => $DebugData) {
        $this->SendDebug($Message.":".$Key, $DebugData, 0);
      }
    } else {
      if (is_bool($Data)) {
        parent::SendDebug($Message, ($Data ? "true" : "false"), 0);
      } else {
        parent::SendDebug($Message, (string)$Data, $Format);
      }
    }
  }


}


trait ParentInstance
{


  private function getParentInfo(int $id) : int {

    $parentID = $this->getParentInstance($id);

    if ($parentID > 0) {
      $status = $this->getParentStatus($parentID);

      if ($status) {
        return $parentID;
      }
    }

    return (int)false;

  }


  private function getParentInstance(int $id) : int {

    if (IPS_InstanceExists($id)) {
      $instanceID = IPS_GetInstance($id);
      return $instanceID['ConnectionID'];
    }

    return 0;

  }


  private function getParentStatus(int $id) : int
  {

    $instanceID = IPS_GetInstance($id);
    return $instanceID['InstanceStatus'];

  }


}


trait WebOAuth
{


  private function RegisterOAuth($webOAuth) {

    $Instances = IPS_GetInstanceListByModuleID('{F99BF07D-CECA-438B-A497-E4B55F139D37}');

    if (count($Instances) > 0 && (IPS_GetInstance($Instances[0])['InstanceStatus'] == 102)) { //Instance is active
      $oAuths = json_decode(IPS_GetProperty($Instances[0], 'ClientIDs'), true);
      $match  = false;

      //Search or Update WebHook client to our instanceID
      foreach ($oAuths as $idx => $oAuth) {
        if ($oAuth['ClientID'] == $webOAuth) {
          if ($oAuth['TargetID'] == $this->InstanceID) {
            return;
          }

          $oAuths[$idx]['TargetID'] = $this->InstanceID;
          $match = true;
        }
      }

      //add a new WebOAuth
      if (!$match) {
        $oAuths[] = array(
          'ClientID' => $webOAuth,
          'TargetID' => $this->InstanceID
        );
      }

      IPS_SetProperty($Instances[0], "ClientIDs", json_encode($oAuths));
      IPS_ApplyChanges($Instances[0]);
    }
  }


}


trait InstanceHelper
{


  private function getObjectByProperty($moduleID, $property, $value) : int {

    $Instances = IPS_GetInstanceListBymoduleID($moduleID);

    foreach ($Instances as $objectID) {
      if (@IPS_GetProperty($objectID, $property) == $value) {
        return $objectID;
      }
    }

    return (int)false;

  }


}


/**
 * Trait mit Hilfsfunktionen für den Datenaustausch.
 */
trait InstanceStatus
{

  /**
  * Interne Funktion des SDK.
  *
  * @access public
  */
  protected function MessageSink($TimeStamp, $SenderID, $Message, $Data) {

    switch ($Message) {
      case FM_CONNECT:
        $this->RegisterParent();

        if ($this->HasActiveParent()) {
          $this->IOChangeState(IS_ACTIVE);
        } else {
          $this->IOChangeState(IS_INACTIVE);
        }
        break;

      case FM_DISCONNECT:
        $this->RegisterParent();
        $this->IOChangeState(IS_INACTIVE);
        break;

      case IM_CHANGESTATUS:
        if ($SenderID == $this->ParentID) {
          $this->IOChangeState($Data[0]);
        }
        break;
    }

  }

  /**
  * Ermittelt den Parent und verwaltet die Einträge des Parent im MessageSink
  * Ermöglicht es das Statusänderungen des Parent empfangen werden können.
  *
  * @access protected
  * @return int ID des Parent.
  */
  protected function RegisterParent() {

    $saveID   = $this->ParentID;
    $parentID = @IPS_GetInstance($this->InstanceID)['ConnectionID'];

    if ($parentID <> $saveID) {
      if ($saveID > 0) {
        $this->UnregisterMessage($saveID, IM_CHANGESTATUS);
      }

      if ($parentID > 0) {
        $this->RegisterMessage($parentID, IM_CHANGESTATUS);
      } else {
        $parentID = 0;
      }

      $this->ParentID = $parentID;
    }

    return $parentID;

  }


  /**
  * Prüft den Parent auf vorhandensein und Status.
  *
  * @access protected
  * @return bool True wenn Parent vorhanden und in Status 102, sonst false.
  */
  protected function HasActiveParent() {

    $instance = @IPS_GetInstance($this->InstanceID);

    if ($instance['ConnectionID'] > 0) {
      $parent = IPS_GetInstance($instance['ConnectionID']);

      if ($parent['InstanceStatus'] == 102) {
        return true;
      }
    }

    return false;
  }


}


trait BufferHelper
{


  /**
  * Wert einer Eigenschaft aus den InstanceBuffer lesen.
  *
  * @access public
  * @param string $name Propertyname
  * @return mixed Value of Name
  */
  public function __get($name) {

    if (strpos($name, "Multi_") === 0) {
      $Lines = vtNoString;

      foreach ($this->{"BufferListe_".$name} as $index) {
        $Lines .= $this->{"Part_". $name.$index};
      }

      return unserialize($Lines);
    }

    return unserialize($this->GetBuffer($name));

  }


  /**
  * Wert einer Eigenschaft in den InstanceBuffer schreiben.
  *
  * @access public
  * @param string $name Propertyname
  * @param mixed Value of Name
  */
  public function __set($name, $value) {

    $data = serialize($value);

    if (strpos($name, "Multi_") === 0) {
      $OldBuffers = $this->{"BufferListe_".$name};

      if ($OldBuffers == false) {
        $OldBuffers = array();
      }

      $Lines = str_split($data, 8000);

      foreach ($Lines as $index => $line) {
        $this->{"Part_".$name.$index} = $line;
      }

      $NewBuffers = array_keys($Lines);
      $this->{"BufferListe_".$name} = $NewBuffers;
      $DelBuffers = array_diff_key($OldBuffers, $NewBuffers);

      foreach ($DelBuffers as $DelBuffer) {
        $this->{"Part_".$name.$DelBuffer} = "";
      }

      return;
    }

    $this->SetBuffer($name, $data);

  }


}
