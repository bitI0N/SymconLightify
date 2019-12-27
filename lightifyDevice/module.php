<?php

declare(strict_types=1);

require_once __DIR__.'/../libs/lightifyControl.php';


class lightifyDevice extends IPSModule
{


  use LightifyControl;


  public function Create() {

    parent::Create();

    //Store at runtime
    $this->RegisterPropertyString("class", vtNoString);
    $this->RegisterPropertyInteger("type", vtNoValue);
    $this->RegisterPropertyString("zigBee", vtNoString);
    $this->RegisterPropertyString("UUID", vtNoString);

    $this->RegisterAttributeInteger("transition", classConstant::TIME_MIN);
    $this->ConnectParent(classConstant::MODULE_GATEWAY);

  }


  public function ApplyChanges() {

    $this->RegisterMessage(0, IPS_KERNELSTARTED);
    parent::ApplyChanges();

    if (IPS_GetKernelRunlevel() != KR_READY) {
      return;
    }

    //Apply filter
    //$filter = ".*".preg_quote(trim(json_encode($this->ReadPropertyString("UUID")), '"')).".*";
    //$this->SetReceiveDataFilter($filter);

    $this->WriteAttributeInteger("transition", classConstant::TIME_MIN);

  }


  public function MessageSink($TimeStamp, $SenderID, $Message, $Data) {

    switch ($Message) {
      case IPS_KERNELSTARTED:
        $this->ApplyChanges();
        break;
    }

  }


  public function GetConfigurationForm() {

    if (!$this->HasActiveParent()) {
      $this->SetStatus(201);
      return vtNoForm;
    }

    //Validate
    $type = $this->ReadPropertyInteger("type");

    if ($type != vtNoValue) {
      $formJSON = json_decode(file_get_contents(__DIR__."/form.json"), true);
      $class = $this->ReadPropertyString("class");

      if ($class == "Sensor" || $class == "Switch" || $type == classConstant::TYPE_ALL_DEVICES) {
        $stateID = @$this->GetIDForIdent("ALL_DEVICES");
        $formJSON['actions'][0]['items'][2]['visible'] = false;
      } else {
        $stateID = @$this->GetIDForIdent("STATE");
      }

      if ($stateID && GetValueBoolean($stateID)) {
        $formJSON['elements'][0]['items'][1]['visible'] = true;
      } else {
        $formJSON['elements'][0]['items'][2]['visible'] = true;
      }

      return json_encode($formJSON);
    }

    $elements[] = [
      'type'    => "Label",
      'caption' => "Group can only be configured over the Lightify Discovery Instance!"
    ];

    $status[] = [
      'code'    => 104,
      'icon'    => "inactive",
      'caption' => "Device is inactive"
    ];

    $formJSON = [
      'elements' => $elements,
      'status'   => $status
    ];

    $this->SetStatus(104);
    return json_encode($formJSON);

  }


  public function ReceiveData($JSONString) {

    $data  = json_decode($JSONString, true);
    $debug = IPS_GetProperty($data['id'], "debug");
    //IPS_LogMessage("SymconOSR", "<Device|Receive:data>   ".IPS_GetName($this->InstanceID)."|".count($data)."|".json_encode($data));

    foreach ($data['buffer'] as $device) {
      if ($device['UUID'] == $this->ReadPropertyString("UUID")) {
        if ($device['type'] == classConstant::TYPE_ALL_DEVICES) {
          $newState = (bool)$device['state'];

          if (false === ($stateID  = @$this->GetIDForIdent("ALL_DEVICES"))) {
            $stateID = $this->RegisterVariableBoolean("ALL_DEVICES", $this->Translate("State"), "OSR.Switch", 313);
            $this->EnableAction("ALL_DEVICES");
          }

          if ($stateID) {
             $state = GetValueBoolean($stateID);

            if ($state != $newState) {
              SetValueBoolean($stateID, $newState);
            }
          }
        } else {
          $this->setDeviceInfo($device);
          break;
        }
      }
    }

  }


  private function setDeviceInfo(array $data) : void {

    //Decode device class
    $light = $plug = $motion = false;
    $type  = $data['type'];

    switch ($type) {
      case classConstant::TYPE_PLUG_ONOFF:
        $plug = true;
        break;

      case classConstant::TYPE_SENSOR_MOTION:
        $motion = true;
        break;

      case classConstant::TYPE_DIMMER_2WAY:
        $dimmer = true;
        break;

      case classConstant::TYPE_SWITCH_4WAY:
      case classConstant::TYPE_SWITCH_MINI:
        $switch = true;
        break;

      default:
        $light = true;
    }

    //Additional informations
    $disable  = true;
    $zigBee   = $data['zigBee'];
    $firmware = $data['firmware'];

    if ($light || $plug || $motion) {
      $RGB = ($type & 8) ? true: false;
      $CCT = ($type & 2) ? true: false;
      $CLR = ($type & 4) ? true: false;

      $hue    = $color = $level      = vtNoString;
      $temperature     = $saturation = vtNoString;

      if ($light) {
        $level = $data['level'];
        $white = $data['white'];
        $rgb   = $data['rgb'];
        $hex   = $this->lightifyBase->RGB2HEX($rgb);
        $hsv   = $this->lightifyBase->HEX2HSV($hex);
      }

      if ($RGB) {
        $hue        = $hsv['h'];
        $color      = hexdec($hex);
        $saturation = $hsv['s'];
      }

      if ($CCT) {
        $temperature = $data['cct'];
      }

      if (false === ($onlineID = @$this->GetIDForIdent("ONLINE"))) {
        //$this->MaintainVariable("ONLINE", $this->Translate("Online"), vtBoolean, "OSR.Switch", 312, true);
        //$onlineID = $this->GetIDForIdent("ONLINE");
        $onlineID = $this->RegisterVariableBoolean("ONLINE", $this->Translate("Online"), "OSR.Switch", 312);

        IPS_SetDisabled($onlineID, true);
        IPS_SetHidden($onlineID, true);
      }

      if ($onlineID) {
        $online  = $data['online'];
        $disable = (bool)!$online;

        if (GetValueBoolean($onlineID) != $online) {
          SetValueBoolean($onlineID, $online);
        }
      }

      if (false === ($stateID = @$this->GetIDForIdent("STATE"))) {
        //$this->MaintainVariable("STATE", $this->Translate("State"), vtBoolean, "OSR.Switch", 313, $online);
        //$stateID = $this->GetIDForIdent("STATE");
        $stateID = $this->RegisterVariableBoolean("STATE", $this->Translate("State"), "OSR.Switch", 313);
      }

      if ($stateID) {
        $state = ($motion) ? (bool)$data['rgb']['r'] : $data['state'];

        if ($light || $plug) {
          if ($disable) {
            $this->DisableAction("STATE");
          } else {
            $this->EnableAction("STATE");
          }
        }

        if (!$disable && GetValueBoolean($stateID) != $state) {
          SetValueBoolean($stateID, $state);
        }
      }

      if ($light || $plug) {
        if ($RGB) {
          if (false === ($hueID = @$this->GetIDForIdent("HUE"))) {
            //$this->MaintainVariable("HUE", $this->Translate("Hue"), vtInteger, "OSR.Hue", 314, true);
            //$hueID = $this->GetIDForIdent("HUE");
            $hueID = $this->RegisterVariableInteger("HUE", $this->Translate("Hue"), "OSR.Hue", 314);

            IPS_SetDisabled($hueID, true);
            IPS_SetHidden($hueID, true);
          }

          if ($hueID) {
            if (!$disable && GetValueInteger($hueID) != $hue) {
              SetValueInteger($hueID, $hue);
            }
          }

          if (false === ($colorID = @$this->GetIDForIdent("COLOR"))) {
            $this->MaintainVariable("COLOR", $this->Translate("Color"), vtInteger, "~HexColor", 315, true);
            $colorID = $this->GetIDForIdent("COLOR");

            //$colorID = $this->RegisterVariableInteger("COLOR", $this->Translate("Color"), "~HexColor", 315);
            //$colorID = $this->RegisterVariableInteger("COLOR", "Color", "~HexColor", 315);
            IPS_SetIcon($colorID, "Paintbrush");
          }

          if ($colorID) {
            if ($disable) {
              $this->DisableAction("COLOR");
            } else {
              $this->EnableAction("COLOR");
            }

            if (!$disable && GetValueInteger($colorID) != $color) {
              SetValueInteger($colorID, $color);
            }
          }

          if (false === ($saturationID = @$this->GetIDForIdent("SATURATION"))) {
            //$this->MaintainVariable("SATURATION", $this->Translate("Saturation"), vtInteger, "OSR.Intensity", 318, true);
            //$saturationID = $this->GetIDForIdent("SATURATION");
            $saturationID = $this->RegisterVariableInteger("SATURATION", $this->Translate("Saturation"), "OSR.Intensity", 318);
            IPS_SetIcon($saturationID, "Intensity");
          }

          if ($saturationID) {
            if ($disable) {
              $this->DisableAction("SATURATION");
            } else {
              $this->EnableAction("SATURATION");
            }

            if (!$disable && GetValueInteger($saturationID) != $saturation) {
              SetValueInteger($saturationID, $saturation);
            }
          }
        }

        if ($CCT) {
          if (false === ($temperatureID = @$this->GetIDForIdent("COLOR_TEMPERATURE"))) {
            $profile = ($RGB) ? "OSR.ColorTempExt" : "OSR.ColorTemp";

            //$this->MaintainVariable("COLOR_TEMPERATURE", $this->Translate("Color Temperature"), vtInteger, "OSR.ColorTemp", 316, true);
            //$temperatureID = $this->GetIDForIdent("COLOR_TEMPERATURE");
            $temperatureID = $this->RegisterVariableInteger("COLOR_TEMPERATURE", $this->Translate("Color Temperature"), "OSR.ColorTemp", 316);
          }

          if ($temperatureID) {
            if ($disable) {
              $this->DisableAction("COLOR_TEMPERATURE");
            } else {
              $this->EnableAction("COLOR_TEMPERATURE");
            }

            if (!$disable && GetValueInteger($temperatureID) != $temperature) {
              SetValueInteger($temperatureID, $temperature);
            }
          }
        }

        if ($light) {
          if (false === ($levelID = @$this->GetIDForIdent("LEVEL"))) {
            //$this->MaintainVariable("LEVEL", $this->Translate("Level"), vtInteger, "OSR.Intensity", 317, true);
            //$levelID = $this->GetIDForIdent("LEVEL");
            $levelID = $this->RegisterVariableInteger("LEVEL", $this->Translate("Level"), "OSR.Intensity", 317);
            IPS_SetIcon($levelID, "Sun");
          }

          if ($levelID) {
            if ($disable) {
              $this->DisableAction("LEVEL");
            } else {
              $this->EnableAction("LEVEL");
            }

            if (!$disable && GetValueInteger($levelID) != $level) {
              SetValueInteger($levelID, $level);
            }
          }
        }
      }

      if ($motion) {
        if (false === ($motionID = @$this->GetIDForIdent("MOTION"))) {
          //$this->MaintainVariable("MOTION", $this->Translate("Motion"), vtBooelan, "~Motion", 322, true);
          //$$motionID = $this->GetIDForIdent("MOTION");
          $motionID = $this->RegisterVariableBoolean("MOTION", $this->Translate("Motion"), "~Motion", 322);
        }

        if ($motionID) {
          $detect = $data['rgb']['g']; //Motion detection = green

          if ($disable) {
            $this->DisableAction("MOTION");
          } else {
            $this->EnableAction("MOTION");
          }

          if (!$disable && GetValueBoolean($motionID) != $detect) {
            SetValueBoolean($motionID, $detect);
          }
        }
      }

      //Firmware
      if (false === ($firmwareID = @$this->GetIDForIdent("FIRMWARE"))) {
        $firmwareID = $this->RegisterVariableString("FIRMWARE", $this->Translate("Firmware"), "", 324);
        IPS_SetDisabled($firmwareID, true);
      }

      if ($firmwareID) {
        if (GetValueString($firmwareID) != $firmware) {
          SetValueString($firmwareID, $firmware);
        }
      }
    }

  }


}
